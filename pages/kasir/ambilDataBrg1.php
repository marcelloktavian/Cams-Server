<?php
// panggil fungsi validasi xss dan injection
/*
$server =  "localhost";
$username = "root";
$password = "admin";
$database = "sbsys";
*/
include "../koneksi/koneksi.php";
// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");
//YJS

if (isset($_GET['list_barcode'])){
	$list_id = $_GET['list_barcode'];
	$jenis = $_GET['jenis'];
	//if ($jenis!="") $jenis = " AND b.id_jenis = ".$jenis;
	$jenis = '';
	//$sql = mysql_query("Select sb.id_barang,b.nm_barang,b.hrg_jual,sb.stok,b.kode_brg from barang b left join stok_barang sb  on sb.id_barang = b.id_barang AND sb.tgl_trans = (SELECT MAX(tgl_trans) FROM stok_barang WHERE id_barang = sb.id_barang) where b.kode_brg IN (".$list_id.") ".$jenis);
	$sql = mysql_query("Select b.id_barang,b.nm_barang,b.hrg_jual,b.kode_brg from barang b where b.kode_brg IN (".$list_id.") ".$jenis);
	$result = array();
	while ($row = mysql_fetch_assoc($sql)) {
		$result[] = $row; 
	}
	echo json_encode($result);
	
}
else {
	$id = $_GET['barcode'];
	$sql = mysql_query("Select sb.id_barang,b.nm_barang,b.hrg_jual,sb.stok from stok_barang sb left join barang b on sb.id_barang = b.id_barang where sb.id_barang = '".$id."' LIMIT 1");

	$row = mysql_fetch_array($sql);
	echo json_encode($row);
}
//$sql = mysql_query("Select * from stok_barang where id_barang = '".$id."' LIMIT 1");


?>
