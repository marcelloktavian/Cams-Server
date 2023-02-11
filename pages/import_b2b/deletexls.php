<!-- import excel ke mysql -->
<?php 
error_reporting(0);

// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
		//menghapus data di xlscamou
		$sql_delete="";	
		$sql_delete="TRUNCATE TABLE b2b_xls_custproduct";	
		$hasil_delete=mysql_query($sql_delete) or die (mysql_error());

// alihkan halaman ke importcamou.php
header("location:importcustomerproduct.php");
?>