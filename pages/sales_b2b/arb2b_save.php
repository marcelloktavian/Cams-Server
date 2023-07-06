<?php

include "../../include/koneksi.php";
session_start();

$row = $_GET['row'];

// Master Data

$roman_month        = str_pad(date('n'), 2, '0', STR_PAD_LEFT);
$year_digit         = date('y');

$dokumen_header     = 'ARB2B'.date('y').$roman_month.'';

$serial_check_sql   = "SELECT `ar_num` FROM `b2bar` WHERE `ar_num` LIKE '%$dokumen_header%' ORDER BY `id` DESC LIMIT 1";

$serial_result      = mysql_query($serial_check_sql);
$serial             = mysql_fetch_array($serial_result);

if($serial[0] != null){
  $serial_number    = intval(substr($serial[0], -5))+1;
}
else {
  $serial_number    = 1;
}

$serial_number      = str_pad($serial_number, 5, '0', STR_PAD_LEFT);

$arb2b_num          = $dokumen_header.$serial_number;

$tanggal            = $_POST['tanggal_arb2b'];
$customer           = explode(" : ",$_POST['customer_arb2b'])[0];
$idAkunDebet        = explode(":",$_POST['akun_debet_arb2b'])[0];
$noAkunDebet        = explode(" - ", explode(":",$_POST['akun_debet_arb2b'])[1])[0];
$idAkunKredit       = explode(":",$_POST['akun_kredit_arb2b'])[0];
$noAkunKredit       = explode(" - ", explode(":",$_POST['akun_kredit_arb2b'])[1])[0];
$total              = $_POST['total_arb2b_value'];
$keterangan         = $_POST['keterangan'];
$id_user            = $_SESSION['user']['username'];

$sql_master         = "INSERT INTO b2bar (`ar_num`, `b2bcust_id`, `id_akun_kredit`, `no_akun_kredit`, `id_akun_debet`, `no_akun_debet`, `tgl_ar`, `total`, `remaining`, `keterangan`, `user`, `deleted`, `lastmodified`) VALUES ('$arb2b_num', '$customer', '$idAkunKredit', '$noAkunKredit', '$idAkunDebet', '$noAkunDebet', '$tanggal', '$total', '$total', '$keterangan', '$id_user', '0', NOW())";

$query              = mysql_query($sql_master);

// Detail Data

$get_id_b2bar       = "SELECT `id` FROM `b2bar` WHERE `ar_num` = '$arb2b_num' AND `b2bcust_id`        ='$customer' AND `tgl_ar` = '$tanggal' AND `total` = '$total' AND `keterangan` = '$keterangan'";

$sql_get_id         = mysql_query($get_id_b2bar);
$id_b2bar           = mysql_fetch_array($sql_get_id);

for($i=1; $i<$row; $i++){
  if($_POST['idarb2b'.$i] != ''){
    $idb2b            = $_POST['idarb2b'.$i];
    $statusb2b        = substr($_POST['numb2b'.$i], 0, 8) == "B2BRETUR" ? 'RETUR' : 'DO' ;

    $sql_detail       = "INSERT INTO b2bar_detail (`id_parent`, `parent`, `id_b2b`, `deleted`, `lastmodified`) VALUES ('$id_b2bar[0]', '$statusb2b', '$idb2b', 0, NOW())";

    $query            = mysql_query($sql_detail);
  }
}

?>

// * Untuk untuk pada saat ini, diasumsikan semua arb2b adalah nilai penuh dari b2bdo dan b2breturn

<script language="javascript">
  window.close();
</script>