<?php
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

$id_ap          = $_POST['id_mst'];
$ap_date        = $_POST['tanggal_ap'];
$id_supplier    = explode(':', $_POST['supplier'])[0];
$nama_supplier  = explode(':', $_POST['supplier'])[1];
$id_akun        = explode(':', $_POST['akun'])[0];
$nomor_akun     = explode(' | ',explode(':', $_POST['akun'])[1])[0];
$nama_akun      = explode(' | ',explode(':', $_POST['akun'])[1])[1];
$total_qty      = $_POST['total_qty_ap'];
$grand_total    = $_POST['total_ap_value'];
$remaining      = $_POST['total_ap_value'];
$catatan        = $_POST['keterangan'];

$sql_master     = "UPDATE `mst_ap` SET `total_qty`='$total_qty',`grand_total`='$grand_total',`remaining`='$remaining',`catatan`='$catatan' WHERE `id`=$id_ap";

$query          = mysql_query($sql_master);

// detail data processing -----------------

$reset_sql_detail = "UPDATE `det_ap` SET `deleted`=1 WHERE `id_ap`=$id_ap";
$reset_sql        = mysql_query($reset_sql_detail);

for($i=1; $i<$row; $i++){
  if($_POST['id_invoice'.$i] != ''){
    $id_detail            = $_POST['id_det'.$i];
    $id_invoice           = $_POST['id_invoice'.$i];
    $no_invoice           = $_POST['nomor_invoice'.$i];
    $tanggal_invoice      = $_POST['tanggal_invoice'.$i];
    $tanggal_jatuh_tempo  = $_POST['tanggal_jatuh_tempo'.$i];
    $qty                  = $_POST['qty'.$i];
    $total                = $_POST['total_inv'.$i];
    $remaining            = $_POST['total_sisa_inv'.$i];

    $sql_detail_check     = "SELECT * FROM `det_ap` WHERE `id_ap`=$id_ap AND `id_detail`=$id_detail AND `deleted` = 1";

    $sql_detail_check   = mysql_query($sql_detail_check);
    $detail_check       = mysql_fetch_array($sql_detail_check);

    if($detail_check[0] > 0){
      $sql_detail       = "UPDATE `det_ap` SET total='$total', `deleted`=0 WHERE `id_detail`=$id_detail";
    }
    else {
      $sql_detail       = "INSERT INTO `det_ap` (`id_ap`,`id_invoice`,`no_invoice`,`tanggal_invoice`,`tanggal_jatuh_tempo`,`qty`,`remaining`,`total`) VALUES ('$id_ap','$id_invoice','$no_invoice','$tanggal_invoice','$tanggal_jatuh_tempo','$qty','$remaining','$total')";
    }

    // var_dump($sql_detail);

    $sql                = mysql_query($sql_detail);
  }
}
?>
<script language="javascript">
  window.close();
</script>