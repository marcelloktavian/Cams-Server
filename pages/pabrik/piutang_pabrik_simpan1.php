<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
	//--------tarik parameter utk simpan---------------
	$konversi=30; 	 
	$id_supplier = $_POST['id_supplier'];
	$id_pkb		 = $_POST['kode_hidden'];
	$tgl   		 = $_POST['tanggal'];
	$tunai   	 = ($_POST['tunai']);
	$transfer    = ($_POST['transfer']);
	$faktur      = ($_POST['faktur']);
	$keterangan  = $_POST['keterangan'];
	$info        = $_POST['info'];
	
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$totalfaktur=0;
	$totalqty=0; 
	$totalpiutang=0; 
	$totalbayar=0; 
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		//$Id_Part = $_POST['BARCODE'.$i];
		$Id_Part = $_POST['Id'.$i];
		$Tgl = $_POST['Tgl'.$i];
		$Invoice = str_replace(",","", $_POST['Invoice'.$i]);
		$Piutang = str_replace(",","", ($_POST['Piutang'.$i]));
		$Sisa = str_replace(",","", ($_POST['Sisa'.$i]));
		$Bayar = str_replace(",","", ($_POST['Bayar'.$i]));
		$Bank = str_replace(",","", ($_POST['Bank'.$i]));
		$totalfaktur += $Bayar+$Bank ;
		$Piutang_update=$Sisa-($Bayar+$Bank);
		/*
		$totalpiutang+=str_replace(",","", $_POST['Piutang'.$i]);
		$totalbayar += str_replace(",","", $_POST['Bayar'.$i]);
		*/
		$totalqty += 1;
		
		//---akhir tarik parameter detail---
		if($Id_Part==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO trbelipiutang_detail(id_transjual, id_trans, faktur, piutang,piutang_update, bayar,bank) VALUES ('".$Id_Part."','".$id_pkb."','".$Invoice."','".$Piutang."','".$Piutang_update."','".$Bayar."','".$Bank."')";
		$hasil = mysql_query($query) or die (mysql_error());
	
		//Buat update piutang penjualan----------------------
		$update = "";
		$update = "UPDATE trbeli set pelunasan=pelunasan+".$totalfaktur." where id_trans='".$Id_Part."'";
		//var_dump($update);die;
		mysql_query($update) or die ("update piutang error".mysql_error());
		}
	}
	//--- simpan master ---------------------------
	$sisapiutang=$totalpiutang-$totalfaktur;
		
	mysql_query("insert into trbelipiutang(id_trans,id_transjual,tgl_trans,id_supplier,totalfaktur,totalqty,faktur,tunai,transfer,piutang,keterangan,info,id_user)values('".$id_pkb."','".$Id_Part."','".$tgl."','".$id_supplier."','".$totalfaktur."','".$totalqty."','".$faktur."','".$tunai."','".$transfer."','".$sisapiutang."','".$keterangan."','".$info."','".$id_user."')") or die (mysql_error());
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
		
		//---tarik parameter detail---
		//$Id_Part = $_POST['BARCODE'.$i];
		$Id_Part = $_POST['Id'.$i];
		$Tgl = $_POST['Tgl'.$i];
		$Invoice = str_replace(",","", $_POST['Invoice'.$i]);
		$Piutang = str_replace(",","", $_POST['Piutang'.$i]);
		$Bayar = str_replace(",","", $_POST['Bayar'.$i]);
		$totalfaktur += str_replace(",","", $_POST['Bayar'.$i]);
		$totalqty += 1;
			
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trbelipiutang_detail(id_transjual, id_trans,faktur,piutang,bayar) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Faktur."','".$Piutang."','".$Bayar."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";	
				mysql_query($sql_insert) ;
				/*
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				*/
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trbelipiutang_detail set id_transjual = '".$Id_Part."', faktur= '".$Faktur."', piutang= '".$Piutang."', bayar= '".$Bayar."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trbelipiutang_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
				mysql_query($sql_delete);
				}
		    }
		}
		
		//update master------------------------------
		mysql_query("update trbelipiutang set totalfaktur='".$totalfaktur."', totalqty='".$totalqty."',faktur='".$faktur."',tunai='".$tunai."',transfer='".$transfer."', id_supplier='".$_POST['id_customer']."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}	
	header("location:piutang_pabrik_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
