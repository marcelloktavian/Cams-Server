<?php
include"../../include/koneksi.php";

$id = $_GET['nama'];
$sql = mysql_query("select * from mst_expedition where (deleted=0) and nama = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>