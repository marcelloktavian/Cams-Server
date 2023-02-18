<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");    
    $nama             = addslashes($_POST['nama']);
	$kode             = $_POST['kode'];
	$harga		      = $_POST['price'];
	$size          	  = $_POST['size'];
	$tipe             = $_POST['tipe'];
	$id_kategori   	  = $_POST['id_kategori'];
	//$row=$_POST['jum'];
	$row=$_GET['row'];
	//--------akhir tarik parameter utk simpan------------
     	
    if($_GET['id_trans']==''){
	//--- simpan master -----------------------------
	$sql_master="";
	$sql_master="INSERT INTO  mst_b2bproducts(`kode`,`nama`,`harga`,`size`,`type`,`id_category`,`user`,`lastmodified`) VALUES('".$kode."','".$nama."','".$harga."','".$size."','".$tipe."','".$id_kategori."','".$user."',NOW())";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	//last_id
	$id_pkb			  = mysql_insert_id();
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_comp = $_POST['IDP'.$i];
		$Qty = 1;
		//---akhir tarik parameter detail---
		if($id_comp==''){
		}
		else
		{
		//---simpan detail---
		$query_detail = "INSERT INTO mst_b2bproducts_detail(`products_id`,`composition_id`,`qty`) VALUES ('".$id_pkb."','".$id_comp."','".$Qty."')";
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
		$id_comp = $_POST['IDP'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = 1;
		$NettPrice= str_replace(",","", $_POST['NettPrice'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
			//echo "<script> alert('PROSES Penyimpanan delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i');</script>";
			
			if($id_comp=='' && $id_detail=='' && $delete==''){
			//if($id_comp==''){
			
			//echo "<script> alert('SKIP PROSES delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
				
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into mst_b2bproducts_detail(products_id,composition_id,qty) VALUES ('".$id_pkb."','".$id_comp."','".$Qty."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
	
				$hasil_insert = mysql_query($sql_insert) or die (mysql_error());
	            }
				else if($delete=='' && $id_detail!=''){
				$sql_update="update mst_b2bproducts_detail set products_id='".$id_pkb."',composition_id= '".$id_comp."', qty= '".$Qty."' where products_detail_id = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	//mysql_query($sql_update);
				$hasil_update = mysql_query($sql_update) or die (mysql_error());
	            
				}
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from mst_b2bproducts_detail where products_detail_id ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		        //var_dump($sql_delete);die;
				//mysql_query($sql_delete);
				$hasil_delete = mysql_query($sql_delete) or die (mysql_error());
	            
				}
		    }
	    
		}
		
		//update master------------------------------
		$sql_master_up="update mst_b2bproducts set nama='".$nama."',kode='".$kode."',harga='".$harga."',size='".$size."',type='".$tipe."',id_category='".$id_kategori."',user='".$id_user."',lastmodified=now() where id= '".$_GET['id_trans']."'";
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
