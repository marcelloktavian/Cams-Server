<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
	//--------tarik parameter utk simpan---------------
	$id_gudang = $_POST['id_gudang'];
	$id_pkb= $_POST['kode_hidden'];
	$tgl   = $_POST['tgl'];
	
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
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
		$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		
		//---akhir tarik parameter detail---
		if($Id_Part==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO trgudang_detail(id_barang, id_trans, qty,harga) VALUES ('".$Id_Part."','".$id_pkb."','".$Qty."','".$Harga."')";
		$hasil = mysql_query($query) or die (mysql_error());
	
	/* Buat update stok barang----------------------
	$q = mysql_fetch_array( mysql_query("select * from part where id_part='".$Id_Part."'"));
	$qtypart=$q['qty'];
	$stok=$qtypart-$Qty;
	mysql_query("update part set qty='".$stok."' where id_part='".$Id_Part."'") or die ("update stok1 error".mysql_error());
		
		//buat print header("location:cetaknotasukucadang.php?id=".$id_ns);
		//--- END simpan detail---
	*/	
		}
	}
	//--- simpan master ---------------------------
	mysql_query("insert into trgudang(id_trans,tgl_trans,id_gudang,totalfaktur,totalqty,id_user)values('".$id_pkb."',NOW(),'".$id_gudang."','".$totalfaktur."','".$totalqty."','".$id_user."')") or die (mysql_error());
	//---akhir simpan master----------------------
	}
	else if($_GET['id_trans']!='')
	{
	//edit data----------
	//-update detail------------------------
	
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
		
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
        //echo "<script> alert('Gagal di tambahkan!');history.go(-1);</script>";
        //echo "<script> alert('delete= $delete,id_detail=$id_detail');</script>";
			
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trgudang_detail(id_barang, id_trans,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Harga."','".$Qty."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";
		
				mysql_query($sql_insert) ;
				//mysql_query("insert into trbeli_detail(id_barang, id_trans,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Harga."','".$Qty."')") ;
				}
				
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trgudang_detail set id_barang = '".$Id_Part."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_update);
				//mysql_query("update trbeli_detail set id_barang = '".$Id_Part."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'") or die (mysql_error());
			    //continue;
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trgudang_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_delete);
				//mysql_query("delete from trbeli_detail where id_detail ='".$id_detail."'")or die (mysql_error());
				}
		    }
	    
		}
		
		//update master------------------------------
		mysql_query("update trgudang set totalfaktur='".$totalfaktur."', totalqty='".$totalqty."', id_gudang='".$_POST['id_gudang']."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}
	
	/*
	echo"sql_insert= ".$sql_insert;
	echo"<br/>sql_update= ".$sql_update;
	echo"<br/>sql_delete= ".$sql_delete;
	echo"<br/>delete= ".$delete;
	echo"<br/>id_detail= ".$detail;
	echo"<br/>id_detail= ".$detail;
	*/
	header("location:gudang_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
