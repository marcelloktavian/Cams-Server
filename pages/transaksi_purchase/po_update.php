<?php
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------
$no_dokumen       = $_POST['no_dokumen'];
$id_supplier      = explode(':',$_POST['supplier'])[0];
$nama_supplier    = explode(':',$_POST['supplier'])[1];
$tgl_po           = $_POST['tanggal_po'];
$eta_pengiriman   = $_POST['eta_pengiriman'];
$id_pemohon       = explode(':',$_POST['pemohon'])[0];
$pemohon          = explode(':',$_POST['pemohon'])[1];
$total_qty        = $_POST['total_qty'];
$total_dpp        = $_POST['total_dpp'];
$ppn              = $_POST['ppn'];
$grand_total      = $_POST['grand_total'];
$pengiriman       = $_POST['pengiriman'];
$catatan          = $_POST['catatan'];

$sql_master       = "UPDATE `mst_po` SET id_supplier = '$id_supplier', nama_supplier = '$nama_supplier', tgl_po = '$tgl_po', eta_pengiriman = '$eta_pengiriman', id_pemohon = '$id_pemohon', nama_pemohon = '$pemohon', total_dpp = '$total_dpp', total_qty = '$total_qty', ppn = '$ppn', grand_total = '$grand_total', pengiriman='$pengiriman', catatan = '$catatan' WHERE dokumen = '$no_dokumen'";

$sql              = mysql_query($sql_master);

// detail data processing -----------------
$get_id_po            = "SELECT `id` FROM `mst_po` WHERE `dokumen` = '$no_dokumen'";

$sql                  = mysql_query($get_id_po);
$id_po                = mysql_fetch_array($sql);

$reset_sql_detail     = "UPDATE `det_po` SET `deleted`=1 WHERE `id_po`='".$id_po[0]."'";
$reset_sql            = mysql_query($reset_sql_detail);

for($i=1; $i<$row; $i++){
  if(isset($_POST['id'.$i]) && $_POST['id'.$i] != ''){
    $id                 = $_POST['id'.$i];
    $id_produk          = explode(':',$_POST['id'.$i])[0];
    $nama_produk        = $_POST['produk_jasa'.$i];
    $tanggal_quotation  = $_POST['tanggal_quotation'.$i];
    $qty                = $_POST['qty'.$i];
    $price              = $_POST['dpp'.$i];
    $satuan             = $_POST['satuan'.$i];
    $subtotal           = $_POST['sub_total'.$i];
    $idAkun             = $_POST['idAkun'.$i];
    $nomorAkun          = $_POST['nomorAkun'.$i];
    $namaAkun           = $_POST['namaAkun'.$i];

    $sql_detail_check   = "SELECT * FROM `det_po` WHERE `id_po` = ".$id_po[0]." AND `id_produk` = ".$id_produk." AND deleted = 1";

    var_dump($sql_detail_check);

    $sql_detail_check   = mysql_query($sql_detail_check);
    $detail_check       = mysql_fetch_array($sql_detail_check);

    if($detail_check[0] > 0){
      $sql_detail       = "UPDATE `det_po` SET qty='$qty', tgl_quotation='$tanggal_quotation', subtotal='$subtotal', `id_akun`='$idAkun' , `nomor_akun`='$nomorAkun', `nama_akun`='$namaAkun', `deleted`=0 WHERE `id_po`='".$id_po[0]."' AND `id_produk`='".$id_produk."'" ;
    }
    else {
      $sql_detail       = "INSERT INTO `det_po` (id_po, id_produk, nama_produk, tgl_quotation, qty, price, satuan, subtotal, id_akun, nomor_akun, nama_akun) VALUES ('$id_po[0]','$id_produk','$nama_produk', '$tanggal_quotation', '$qty','$price','$satuan','$subtotal','$idAkun','$nomorAkun','$namaAkun')";
    }

    var_dump($sql_detail);

    $sql                = mysql_query($sql_detail);
  }
}
?>
<script>
  window.close();
</script>