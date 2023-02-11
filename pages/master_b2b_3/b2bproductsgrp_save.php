<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");    
    $nama             = addslashes($_POST['nama']);
	$kode             = $_POST['kode'];
	$harga		      = $_POST['price'];
	$totalqty		  = $_POST['totalqty'];
	$id_kategori   	  = $_POST['id_kategori'];
	$note   	      = $_POST['txtbrg'];
	//$row=$_POST['jum'];
	$row=$_GET['row'];
	//--------akhir tarik parameter utk simpan------------
     	
    if($_GET['id_trans']==''){
	//--- simpan master -----------------------------
	$sql_master="";
	$sql_master="INSERT INTO  mst_b2bproductsgrp(`kode`,`nama`,`harga`,`totalqty`,`note`,`id_category`,`user`,`lastmodified`) VALUES('".$kode."','".$nama."','".$harga."','".$totalqty."','".$note."','".$id_kategori."','".$user."',NOW())";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	//last_id
	$id_pkb			  = mysql_insert_id();
	//add detail
		for ($i=1; $i<$row; $i++)
		{
		//---tarik parameter detail---
		$id_p 		= $_POST['IDP'.$i];
		$size 	 = $_POST['Size'.$i];
		$namabrg = $_POST['NamaBrg'.$i];
		$qty = 1;
			//---akhir tarik parameter detail---
			if($id_p==''){
			}
			else
			{
			//---simpan detail---
			$query_detail = "INSERT INTO mst_b2bproductsgrp_detail(`id_productsgrp`,`id_product`,`nama`,`qty`,`size`) VALUES ('".$id_pkb."','".$id_p."','".$nama."','".$qty."','".$size."')";
			//var_dump($query_detail);die;
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
		$id_p = $_POST['IDP'.$i];
		$size = $_POST['Size'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$qty = 1;
		$namabrg = $_POST['NamaBrg'.$i];
		//$Disc= str_replace(",","", $_POST['Disc'.$i]);
			//echo "<script> alert('PROSES Penyimpanan delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i');</script>";
			
			if($id_p=='' && $id_detail=='' && $delete==''){
			//echo "<script> alert('SKIP PROSES delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
				
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into mst_b2bproductsgrp_detail(id_productsgrp,id_product,nama,qty,size) VALUES ('".$id_pkb."','".$id_p."','".$nama."','".$qty."','".$size."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
	
				$hasil_insert = mysql_query($sql_insert) or die (mysql_error());
	            }
				else if($delete=='' && $id_detail!=''){
				$sql_update="update mst_b2bproductsgrp_detail set id_productsgrp='".$id_pkb."',id_product= '".$id_p."', size= '".$size."', nama= '".$namabrg."',qty= '".$qty."' where id = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	//mysql_query($sql_update);
				$hasil_update = mysql_query($sql_update) or die (mysql_error());
	            
				}
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from mst_b2bproductsgrp_detail where id ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		        //var_dump($sql_delete);die;
				//mysql_query($sql_delete);
				$hasil_delete = mysql_query($sql_delete) or die (mysql_error());	
				}
		    }
	    }
		//update master------------------------------
		$sql_master_up="update mst_b2bproductsgrp set nama='".$nama."',kode='".$kode."',harga='".$harga."',totalqty='".$totalqty."',note='".$note."',id_category='".$id_kategori."',user='".$id_user."',lastmodified=now() where id= '".$_GET['id_trans']."'";
		//var_dump($sql_master_up);die;
		mysql_query($sql_master_up) or die (mysql_error());
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
