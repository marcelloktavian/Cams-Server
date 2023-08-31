<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");    
	//$row=$_POST['jum'];
	$row=$_GET['row'];
	// var_dump($row);die;
	//--------akhir tarik parameter utk simpan------------
     	
    if($_GET['id_trans']!='')
	{
	$id_pkb = $_GET['id_trans'];
	//edit data----------
	//-update detail------------------------
		for ($i=1; $i<$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
			$delete = $_POST['delete1'.$i];
			$id_detail = $_POST['Id'.$i];		
			$nomor = $_POST['Nomor'.$i];
			$namaakun = $_POST['Nama'.$i];

			if($id_pkb=='' && $id_detail=='' && $delete==''){
			//echo "<script> alert('SKIP PROSES delete= $delete,id_detail=$id_detail,id_comp=$id_comp,baris=$i',sql=$sql_insert');</script>";
				
			}
			else
			{
				if($delete=='' && $id_detail==''){
					// $query_detail = "INSERT INTO accountdet_balance(`id_parent`,`noakun`,`nama`,`user`,`lastmodified`) VALUES ('".$id_pkb."','".$nomor."','".$namaakun."','".$user."',NOW())";
					// // var_dump($query_detail);
					// $hasil = mysql_query($query_detail) or die (mysql_error());	

					$query_detail = "INSERT INTO det_coa(`id_parent`,`noakun`,`nama`,`user`,`lastmodified`) VALUES ('".$id_pkb."','".$nomor."','".$namaakun."','".$user."',NOW())";
					// var_dump($query_detail);
					$hasil = mysql_query($query_detail) or die (mysql_error());	
	            }
				else if($delete=='' && $id_detail!=''){
					// $sql_update="UPDATE `accountdet_balance` SET `noakun`='".$nomor."',`nama`='".$namaakun."',`user`='".$user."',`lastmodified`=NOW() WHERE `id`='".$id_detail."' ";
					// // var_dump($sql_update);
					// $hasil_update = mysql_query($sql_update) or die (mysql_error());

					$sql_update="UPDATE `det_coa` SET `noakun`='".$nomor."',`nama`='".$namaakun."',`user`='".$user."',`lastmodified`=NOW() WHERE `id`='".$id_detail."' ";
					// var_dump($sql_update);
					$hasil_update = mysql_query($sql_update) or die (mysql_error());

					$sql_update="UPDATE `jurnal_detail` SET `nama_akun`='".$namaakun."',`user`='".$user."',`lastmodified`=NOW() WHERE `no_akun`='".$nomor."' ";
					// var_dump($sql_update);die;
					$hasil_update = mysql_query($sql_update) or die (mysql_error());
				}
				else if($delete!='' && $id_detail==''){
					// $sql_delete="delete from accountdet_balance where id ='".$delete."'";
					// // var_dump($sql_delete);
					// $hasil_delete = mysql_query($sql_delete) or die (mysql_error());
					
					$sql_delete="delete from det_coa where id ='".$delete."'";
					// var_dump($sql_delete);
					$hasil_delete = mysql_query($sql_delete) or die (mysql_error());
				}
		    }
	    }
		
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
