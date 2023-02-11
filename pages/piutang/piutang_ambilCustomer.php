<?php
include "../koneksi/koneksi.php";

$id = $_GET['nama'];
$sql = mysql_query("select * from tblpelanggan where deleted=0 and namaperusahaan = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>