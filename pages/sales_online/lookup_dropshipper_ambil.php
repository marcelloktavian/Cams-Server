<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$sql_note="SELECT d.id, d.nama,d.disc,d.type,d.note,jual.deposit AS trdeposit FROM mst_dropshipper d LEFT JOIN (SELECT id_dropshipper,SUM(IFNULL(deposit,0)) AS deposit FROM olndeposit od 
GROUP BY id_dropshipper ) AS jual ON d.id = jual.id_dropshipper where (d.deleted=0) and d.id = '".$id."' LIMIT 1";
//$sql_note="select * from mst_dropshipper where (deleted=0) and id = '".$id."' LIMIT 1";
//$sql = mysql_query("select * from mst_dropshipper where (deleted=0) and nama = '".$id."' LIMIT 1");
//var_dump($sql_note);die;
$sql = mysql_query($sql_note);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>