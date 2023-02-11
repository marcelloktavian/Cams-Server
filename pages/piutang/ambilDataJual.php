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
$id = $_GET['barcode'];
//$sql = mysql_query("Select sb.id_barang,b.nm_barang,b.hrg_jual,sb.stok from stok_barang sb left join barang b on sb.id_barang = b.id_barang where sb.id_barang = '".$id."' LIMIT 1");
$sql = mysql_query("Select * from trjual where piutang > 0 and id_trans = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
	
echo json_encode($row);

?>
