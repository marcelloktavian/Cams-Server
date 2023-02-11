<?php
// panggil fungsi validasi xss dan injection
/*
$server =  "localhost";
$username = "root";
$password = "";
$database = "sbsys";
*/
include "../koneksi/koneksi.php";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");
$id = $_GET['id_kategori'];
$sql = mysql_query("select * from jenis_barang where id_jenis = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
	
echo json_encode($row);

?>
