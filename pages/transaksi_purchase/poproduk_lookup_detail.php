<?php
include "../../include/koneksi.php";

$id = $_GET['id'];

$sql_cmd = "SELECT a.*, b.pkp, c.nama FROM `mst_produk` a LEFT JOIN det_coa c ON c.id=a.id_akun LEFT JOIN `mst_supplier` b ON a.id_supplier = b.id WHERE a.`deleted`=0 AND a.id='".$id."' LIMIT 1";
$sql = mysql_query($sql_cmd);
$sql = mysql_fetch_array($sql);

echo json_encode($sql);
?>