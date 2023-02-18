<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");    
    $nama             = addslashes($_POST['nama']);
	$id_kategori   	  = $_POST['id_kategori'];
	$keterangan   	  = $_POST['txtbrg'];
	//$row=$_POST['jum'];
	$row=$_GET['row'];
	// var_dump($row);die;
	//--------akhir tarik parameter utk simpan------------
     	
    if($_GET['id_trans']==''){
	//--- simpan master -----------------------------
	$sql_master="";
	$sql_master="INSERT INTO  mst_jenisbiaya(`id_kategori`,`nama_jenis`,`keterangan`,`user`,`lastmodified`) VALUES('".$id_kategori."','".$nama."','".$keterangan."','".$user."',NOW())";
	// var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	//last_id
	$id_parent	= mysql_insert_id();
	//add detail
		for ($i=1; $i<$row; $i++)
		{
		//---tarik parameter detail---
		$namabiaya = $_POST['NamaBiaya'.$i];
		$satuan    = $_POST['Satuan'.$i];

			//---akhir tarik parameter detail---
			if($id_parent==0){
			}
			else
			{
			//---simpan detail---
			$query_detail = "INSERT INTO det_jenisbiaya(`id_parent`,`nama_biaya`,`satuan`,`user`,`lastmodified`) VALUES ('".$id_parent."','".$namabiaya."','".$satuan."','".$user."',NOW())";
			// var_dump($query_detail);die;
			$hasil = mysql_query($query_detail) or die (mysql_error());		
			}
		}
	}
	else if($_GET['id_trans']!='')
	{
	$id_pkb = $_GET['id_trans'];
	//edit data----------
	//-update detail------------------------
		for ($i=1; $i<$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
			$delete = $_POST['delete1'.$i];
			$id_detail = $_POST['Id'.$i];		
			$namabiaya = $_POST['NamaBiaya'.$i];
			$satuan = $_POST['Satuan'.$i];

			if($id_pkb=='' && $id_detail=='' && $delete==''){
			//echo "<script> alert('SKIP PROSES delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
				
			}
			else
			{
				if($delete=='' && $id_detail==''){
					$query_detail = "INSERT INTO det_jenisbiaya(`id_parent`,`nama_biaya`,`satuan`,`user`,`lastmodified`) VALUES ('".$id_pkb."','".$namabiaya."','".$satuan."','".$user."',NOW())";
					// var_dump($query_detail);
					$hasil = mysql_query($query_detail) or die (mysql_error());	
	            }
				else if($delete=='' && $id_detail!=''){
					$sql_update="UPDATE `det_jenisbiaya` SET `nama_biaya`='".$namabiaya."',`satuan`='".$satuan."',`user`='".$user."',`lastmodified`=NOW() WHERE `id`='".$id_detail."' ";
					// var_dump($sql_update);
					$hasil_update = mysql_query($sql_update) or die (mysql_error());
				}
				else if($delete!='' && $id_detail==''){
					$sql_delete="delete from det_jenisbiaya where id ='".$delete."'";
					// var_dump($sql_delete);
					$hasil_delete = mysql_query($sql_delete) or die (mysql_error());	
				}
		    }
	    }
		//update master------------------------------
		$sql_master="";
		$sql_master="UPDATE `mst_jenisbiaya` SET `id_kategori`='".$id_kategori."',`nama_jenis`='".$nama."',`keterangan`='".$keterangan."',`user`='".$user."',`lastmodified`=NOW() WHERE `id`='".$id_pkb."' ";
		//var_dump($sql_master);die;
		mysql_query($sql_master) or die (mysql_error());
	}
	
	/*
	echo"sql_insert= ".$sql_insert;
	echo"<br/>sql_update= ".$sql_update;
	echo"<br/>sql_delete= ".$sql_delete;
	echo"<br/>delete= ".$delete;
	echo"<br/>id_detail= ".$detail;
	echo"<br/>id_detail= ".$detail;
	*/
	//DIMATIKAN AGAR LANGSUNG HILANG SAVENYA
	//header("location:trolnso_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
