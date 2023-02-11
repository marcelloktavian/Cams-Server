<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$sql_cmd ="select stok from inventory_balance where (size<>'' AND size is not NULL) and id = '".$id."' LIMIT 1";
//var_dump('here'+$sql_cmd);die;  
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>