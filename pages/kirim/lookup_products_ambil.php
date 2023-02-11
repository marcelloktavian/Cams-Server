<?php
include"../../include/koneksi.php";

$id = $_GET['nama'];
$sql_cmd ="select * from mst_products where (aktif='Y') and id = '".$id."' LIMIT 1";
//var_dump('here'+$sql_cmd);die;  
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>