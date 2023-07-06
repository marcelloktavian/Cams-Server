<?php
include "../../include/koneksi.php";

if(isset($_GET['action']) && $_GET['action'] == 'akunkredit'){
  $id = $_GET['id'];

  $sql_cmd = "SELECT c.id AS id_akun, c.`noakun` AS nomor_akun, c.nama AS nama_akun FROM mst_b2bcustomer b LEFT JOIN det_coa c ON c.noakun = CONCAT('04.03.', LPAD(b.id, 5, 0)) WHERE b.id='".$id."'";

  $sql = mysql_query($sql_cmd);
  $sql = mysql_fetch_array($sql);

  echo json_encode($sql);
}
?>