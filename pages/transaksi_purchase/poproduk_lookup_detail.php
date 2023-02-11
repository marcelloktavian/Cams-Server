<?php
include "../../include/koneksi.php";

$id = $_GET['id'];

$sql_cmd = "SELECT a.*, b.pkp FROM `mst_produk` a LEFT JOIN `mst_supplier` b ON a.id_supplier = b.id WHERE a.`deleted`=0 AND a.id='".$id."' LIMIT 1";
$sql = mysql_query($sql_cmd);
$sql = mysql_fetch_array($sql);

echo json_encode($sql);
?>