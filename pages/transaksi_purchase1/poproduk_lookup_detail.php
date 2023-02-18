<?php
include "../../include/koneksi.php";

$id = $_GET['id'];

$sql_cmd = "SELECT * FROM `mst_produk` WHERE `deleted`=0 AND id='".$id."' LIMIT 1";
$sql = mysql_query($sql_cmd);
$sql = mysql_fetch_array($sql);

echo json_encode($sql);
?>