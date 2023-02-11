<?php
include"../include/koneksi.php";

$id = $_GET['nama'];
$sql = mysql_query("select * from tblpelanggan where namaperusahaan = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>