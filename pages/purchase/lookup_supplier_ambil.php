<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$sql_note="SELECT d.id, d.nama FROM mst_supplier d  where (d.deleted=0) and d.id = '".$id."' LIMIT 1";
//$sql_note="select * from mst_dropshipper where (deleted=0) and id = '".$id."' LIMIT 1";
//$sql = mysql_query("select * from mst_dropshipper where (deleted=0) and nama = '".$id."' LIMIT 1");
//var_dump($sql_note);die;
$sql = mysql_query($sql_note);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>