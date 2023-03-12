<?php
include "../../include/koneksi.php";

$id = $_GET['id'];

$sql_cmd = "SELECT a.*,b.id AS id_akun, b.nama AS nama_akun, b.noakun AS nomor_akun FROM `mst_supplier` a LEFT JOIN `det_coa` b ON CAST(SUBSTRING_INDEX(b.noakun, '.', 1) AS UNSIGNED)=2 AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(b.noakun, '.', 2), '.', -1) AS UNSIGNED)=1 AND CAST(SUBSTR(b.noakun,-4) AS UNSIGNED)=a.id WHERE a.`deleted`=0 AND a.id=$id LIMIT 1";

$sql = mysql_query($sql_cmd);
$sql = mysql_fetch_array($sql);

echo json_encode($sql);
?>