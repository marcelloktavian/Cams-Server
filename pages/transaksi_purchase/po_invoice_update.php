<?php
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------

$id_invoice           = $_GET['id'];

$nomor_invoice        = $_POST['nomor_invoice'];
$tanggal_invoice      = $_POST['tanggal_invoice'];
$tanggal_jatuh_tempo  = $_POST['tanggal_jatuh_tempo'];
$keterangan           = $_POST['keterangan'];
$id_supplier          = explode(':', $_POST['supplier'])[0];
$supplier             = explode(':', $_POST['supplier'])[1];
$qty                  = $_POST['total_qty_inv'];
$total                = $_POST['total_inv_value'];

$sql_update           = "UPDATE `mst_invoice` SET `nomor_invoice`='$nomor_invoice',`tanggal_invoice`='$tanggal_invoice',`tanggal_jatuh_tempo`='$tanggal_jatuh_tempo',`keterangan`='$keterangan',`id_supplier`='$id_supplier',`supplier`='$supplier',`qty`='$qty',`total`='$total',`total_remaining`='$total' WHERE `id`=".$id_invoice."";

$query                = mysql_query($sql_update);

// detail data processing -----------------
$reset_sql_detail     = "UPDATE `det_invoice` SET `deleted`=1 WHERE `id_invoice`=".$id_invoice."";
$reset_sql            = mysql_query($reset_sql_detail);

for($i=1; $i<$row; $i++){
  if(isset($_POST['id'.$i]) && $_POST['id'.$i] != ''){
    $id_po            = $_POST['id_po'.$i];
    $id_detail        = $_POST['id'.$i];
    $id_produk        = $_POST['id_produk'.$i];
    $nama_produk      = $_POST['produk_jasa'.$i];
    $qty              = $_POST['qty_inv'.$i];
    $price            = $_POST['dpp_unit'.$i];
    $satuan           = $_POST['satuan'.$i];
    $persen_ppn       = $_POST['persen_ppn'.$i];
    $subtotal         = $_POST['subtotal_inv'.$i];

    $sql_detail_check = "SELECT * FROM `det_invoice` WHERE `id_po`=".$id_po." AND `id_produk`=".$id_produk." AND `id`='$id_detail'";

    $sql_detail_check = mysql_query($sql_detail_check);
    $detail_check     = mysql_fetch_array($sql_detail_check);

    if($detail_check[0] > 0){
      $sql_detail       = "UPDATE `det_invoice` SET `qty`='$qty', `subtotal`='$subtotal',`deleted`=0 WHERE `id`='$id_detail'";
    }
    else {
      $sql_detail       = "INSERT INTO `det_invoice` (`id_po`,`id_detail`,`id_invoice`,`id_produk`,`nama_produk`,`qty`,`price`,`satuan`,`persen_ppn`,`subtotal`) VALUES ('$id_po','$id_detail','$id_invoice','$id_produk','$nama_produk','$qty','$price','$satuan','$persen_ppn','$subtotal')";
    }

    // var_dump($sql_detail);

    $sql              = mysql_query($sql_detail);
  }
}

?>