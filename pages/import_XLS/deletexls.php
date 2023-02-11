<!-- import excel ke mysql -->
<?php 
error_reporting(0);

// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
		//menghapus data di xlscamou
		$sql_delete="";	
		$sql_delete="TRUNCATE TABLE oln_xlscamou";	
		$hasil_delete=mysql_query($sql_delete) or die (mysql_error());

// alihkan halaman ke importcamou.php
header("location:importcamou.php");
?>