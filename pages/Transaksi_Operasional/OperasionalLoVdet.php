<?php
include("../../include/koneksi.php");

$id = $_GET['id'];
$sql_cmd ="SELECT det.*, mst.nama_jenis FROM det_jenisbiaya det LEFT JOIN mst_jenisbiaya mst ON mst.id=det.id_parent WHERE mst.deleted=0 AND det.id = '".$id."' LIMIT 1";
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>