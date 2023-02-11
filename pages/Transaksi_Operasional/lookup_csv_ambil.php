<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$sql_note = "SELECT `id`, `id_import`, `norek`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, (jumlah-payment) as `jumlah`, `saldoawal`, `mutasikredit`, FORMAT((jumlah-payment),0) as jumlahhidden FROM acc_prebank WHERE id='".$id."' ";
//var_dump($sql_note);die;
$sql = mysql_query($sql_note);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>