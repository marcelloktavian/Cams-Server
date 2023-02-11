<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
//include("koneksi/koneksi.php");
	//--------tarik parameter utk simpan---------------
	$id_customer = $_POST['id_customer'];
	$id_pkb= $_POST['kode_hidden'];
	$tgl   = $_POST['tgl'];
	
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi sebelum entry
	$biaya = 0;
	$faktur=0;
	$tunai=0;
	$transfer=0;
	$kartu=0;
	$totalfaktur=0;
	$totalqty=0; 
     	
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$faktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		
		//---akhir tarik parameter detail---
		if($Id_Part==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO trjual_detail(id_barang, id_trans, qty,harga) VALUES ('".$Id_Part."','".$id_pkb."','".$Qty."','".$Harga."')";
		$hasil = mysql_query($query) or die (mysql_error());
	
		// Buat simpan stok barang----------------------
		$stok = "";
		$stok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
		mysql_query($stok) or die ("update stok1 error".mysql_error());
		
		//buat print header("location:cetaknotasukucadang.php?id=".$id_ns);
		//--- END simpan detail---
		
		}
	}
	//--- simpan master ---------------------------------------
	$biaya = $_POST['ongkir'];
	$tunai = $_POST['tunai'];
	$transfer = $_POST['transfer'];
	$kartu = $_POST['kartu'];
	$deposit = $_POST['deposit'];
	$totalfaktur = $faktur + $biaya;
	
	$piutang = 0;
	$piutang = $totalfaktur - ($tunai+$transfer+$kartu) ;
	
	mysql_query("insert into trjual(id_trans,tgl_trans,id_customer,biaya,faktur,totalfaktur,tunai,transfer,kartu,piutang,totalqty,id_user)values('".$id_pkb."',NOW(),'".$id_customer."','".$biaya."','".$faktur."','".$totalfaktur."','".$tunai."','".$transfer."','".$kartu."','".$piutang."','".$totalqty."','".$id_user."')") or die (mysql_error());
		
	//---akhir simpan master-------------------------------
	}
	else if($_GET['id_trans']!='')
	{
	//edit data--------------------------------
	//-update detail---------------------------
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari jual_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
		
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
    		
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trjual_detail(id_barang, id_trans,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Harga."','".$Qty."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";
		
				mysql_query($sql_insert) ;
				//mysql_query("insert into trbeli_detail(id_barang, id_trans,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Harga."','".$Qty."')") ;
				$sql_insert="";
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trjual_detail set id_barang = '".$Id_Part."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_update);
				//mysql_query("update trbeli_detail set id_barang = '".$Id_Part."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'") or die (mysql_error());
			    //continue;
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trjual_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_delete);
				//mysql_query("delete from trbeli_detail where id_detail ='".$id_detail."'")or die (mysql_error());
				}
		    }
	    
		}
		
		//update master------------------------------
		mysql_query("update trjual set totalfaktur='".$totalfaktur."', totalqty='".$totalqty."', id_customer='".$id_customer."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}


	header("location:jual_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
