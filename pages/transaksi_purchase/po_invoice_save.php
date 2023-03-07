<?php
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------

$nomor_invoice        = $_POST['nomor_invoice'];
$tanggal_invoice      = $_POST['tanggal_invoice'];
$tanggal_jatuh_tempo  = $_POST['tanggal_jatuh_tempo'];
$keterangan           = $_POST['keterangan'];
$id_supplier             = explode(':', $_POST['supplier'])[0];
$supplier             = explode(':', $_POST['supplier'])[1];
$qty                  = $_POST['total_qty_inv'];
$total                = $_POST['total_inv_value'];

$sql_master           = "INSERT INTO `mst_invoice` (`nomor_invoice`,`tanggal_invoice`,`tanggal_jatuh_tempo`,`keterangan`,`id_supplier`,`supplier`,`qty`,`total`,`total_remaining`) VALUES ('$nomor_invoice','$tanggal_invoice','$tanggal_jatuh_tempo','$keterangan','$id_supplier','$supplier','$qty','$total',$total)";

$query                = mysql_query($sql_master);

// detail data processing -----------------
$get_id_invoice       = "SELECT `id` FROM `mst_invoice` WHERE `nomor_invoice`='$nomor_invoice' AND `tanggal_invoice`='$tanggal_invoice' AND `total`='$total'";

$sql_get_id           = mysql_query($get_id_invoice);
$id_invoice           = mysql_fetch_array($sql_get_id);

for($i=1; $i<$row; $i++){
  if($_POST['id'.$i] != ''){
    $id_po            = $_POST['id_po'.$i];
    $id_detail        = $_POST['id'.$i];
    $id_produk        = $_POST['id_produk'.$i];
    $nama_produk      = $_POST['produk_jasa'.$i];
    $qty              = $_POST['qty_inv'.$i];
    $price            = $_POST['dpp_unit'.$i];
    $satuan           = $_POST['satuan'.$i];
    $persen_ppn       = $_POST['persen_ppn'.$i];
    $subtotal         = $_POST['subtotal_inv'.$i];
    $idAkun           = $_POST['idAkun'.$i];
    $nomorAkun        = $_POST['nomorAkun'.$i];
    $namaAkun         = $_POST['namaAkun'.$i];

    $sql_detail       = "INSERT INTO `det_invoice` (`id_po`,`id_detail`,`id_invoice`,`id_produk`,`nama_produk`,`qty`,`price`,`satuan`,`persen_ppn`,`subtotal`,`id_akun`,`nomor_akun`,`nama_akun`) VALUES ('$id_po','$id_detail','$id_invoice[0]','$id_produk','$nama_produk','$qty','$price','$satuan','$persen_ppn','$subtotal','$idAkun','$nomorAkun','$namaAkun')";

    $query            = mysql_query($sql_detail);
  }
}

$sql_qty          = "UPDATE `det_po` SET `det_po`.`qty_terbayar`=`det_po`.`qty_terbayar`+(SELECT `qty` FROM `det_invoice` WHERE det_invoice.id_detail=det_po.id AND det_invoice.id_produk=det_po.`id_produk` AND det_invoice.id_invoice=".$id_invoice[0]." AND det_invoice.`deleted`=0) WHERE `det_po`.id=(SELECT `id_detail` FROM `det_invoice` WHERE det_invoice.id_detail=det_po.id AND det_invoice.id_invoice=".$id_invoice[0]." AND `deleted`=0)";

$query            = mysql_query($sql_qty);

?>

<script language="javascript">
  window.close();
</script>