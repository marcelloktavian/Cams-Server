<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$sql = mysql_query("select * from hrd_pph21 where (deleted=0) and id_pph21 = '".$id."' LIMIT 1");
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>