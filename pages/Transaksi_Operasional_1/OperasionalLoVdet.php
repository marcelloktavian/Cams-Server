<?php
include("../../include/koneksi.php");

$id = $_GET['id'];
$sql_cmd =" SELECT * FROM mst_operasional WHERE deleted=0  and id = '".$id."' LIMIT 1";
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>