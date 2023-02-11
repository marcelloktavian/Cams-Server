<!-- import excel ke mysql -->
<?php 
error_reporting(0);
$id=$_GET['id'];
// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
		//menghapus data di xlscamou
		$sql_delete="";	
		$sql_delete="DELETE FROM b2b_xls_custproduct where id=$id";
		//var_dump($sql_delete);die;	
		$hasil_delete=mysql_query($sql_delete) or die (mysql_error());

// alihkan halaman ke importcamou.php
header("location:importcustomerproduct.php");
?>