<?php
include "../../include/koneksi.php";

$id = $_GET['id'];

$sql_cmd = "SELECT a.*,b.id AS id_akun, b.nama AS nama_akun, b.noakun AS nomor_akun
FROM `mst_supplier` a LEFT JOIN `det_coa` b ON b.`noakun` = CONCAT('02.01.1',CONCAT(
  CASE
    WHEN LENGTH(a.id) = 1 THEN CONCAT('000', a.id)
    WHEN LENGTH(a.id) = 2 THEN CONCAT('00', a.id)
    WHEN LENGTH(a.id) = 3 THEN CONCAT('0', a.id)
    ELSE a.id
  END
))
WHERE a.`deleted`=0 AND a.id=$id";

$sql = mysql_query($sql_cmd);
$sql = mysql_fetch_array($sql);

echo json_encode($sql);
?>