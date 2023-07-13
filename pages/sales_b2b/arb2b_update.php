<?php

include "../../include/koneksi.php";
session_start();

$row = $_GET['row'];

// Master Data

$id_b2bar           = $_POST['id_arb2b_mst'];
$tanggal            = $_POST['tanggal_arb2b'];
$idAkunDebet        = explode(":",$_POST['akun_debet_arb2b'])[0];
$noAkunDebet        = explode(" - ", explode(":",$_POST['akun_debet_arb2b'])[1])[0];
$total              = $_POST['total_arb2b_value'];
$keterangan         = $_POST['keterangan'];
$id_user            = $_SESSION['user']['username'];

$sql_master         = "UPDATE b2bar SET `id_akun_debet`='$idAkunDebet', `no_akun_debet`='$noAkunDebet', `tgl_ar`='$tanggal', `total`='$total', `remaining`='$total', `keterangan`='$keterangan', `user`='$id_user', `deleted`='0', `lastmodified`=NOW() WHERE id='$id_b2bar'";
$query              = mysql_query($sql_master);

// Detail Data

$sql_reset          = "UPDATE b2bar_detail SET `deleted`=1 WHERE id_parent='$id_b2bar'";
$query              = mysql_query($sql_reset );

for($i=1; $i<$row; $i++){
  if($_POST['idarb2b'.$i] != ''){
    $idb2b            = $_POST['idarb2b'.$i];
    $statusb2b        = substr($_POST['numb2b'.$i], 0, 8) == "B2BRETUR" ? 'RETUR' : 'DO' ;

    $sql_check        = "SELECT * FROM b2bar_detail WHERE id_b2b='$idb2b' LIMIT 1";

    $query_check        = mysql_query($sql_check);
    $rs                 = mysql_fetch_array($query_check);

    if($rs){
      $sql_detail       = "UPDATE b2bar_detail SET `deleted`=0 WHERE id='".$rs['id']."'";
    } else{
      $sql_detail       = "INSERT INTO b2bar_detail (`id_parent`, `parent`, `id_b2b`, `deleted`, `lastmodified`) VALUES ('$id_b2bar', '$statusb2b', '$idb2b', 0, NOW())";
    }
    $query            = mysql_query($sql_detail);
  }
}

?>

// * Untuk untuk pada saat ini, diasumsikan semua arb2b adalah nilai penuh dari b2bdo dan b2breturn

<script language="javascript">
  window.close();
</script>