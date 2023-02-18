<?php
include "../../include/koneksi.php";

// general function -------------------------
function monthToRoman($month){
  $month         = intval($month);
  $result        = '';

  $lookup = array(
    'X'=> 10, 'IX'=> 9, 'V'=> 5, 'IV'=> 4, 'I'=> 1,
  );

  foreach($lookup as $roman => $value){
    $match_val   = intval($month/$value);
    $result     .= str_repeat($roman,$match_val);
    $month       = $month % $value;
  }
  return $result;
}

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------
$roman_month      = monthToRoman(date('n'));
$year_digit       = date('y');

$dokumen_header   = 'AK'.date('y').'/PO-'.$roman_month.'/';

$serial_check_sql = "SELECT `dokumen` FROM `mst_po` WHERE `dokumen` LIKE '%$dokumen_header%' ORDER BY `id` DESC LIMIT 1";

$serial_result    = mysql_query($serial_check_sql);
$serial           = mysql_fetch_array($serial_result);

if($serial[0] != null){
  $serial_number  = end(explode('/', $serial['dokumen']))+1;
}
else {
  $serial_number  = 1;
}

$serial_number    = str_pad($serial_number, 3, '0', STR_PAD_LEFT);

$no_dokumen       = $dokumen_header.$serial_number;
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
$catatan          = $_POST['catatan'];

$sql_master       = "INSERT INTO `mst_po` (dokumen, id_supplier, nama_supplier, tgl_po, eta_pengiriman, id_pemohon, nama_pemohon, total_dpp, total_qty, ppn, grand_total, catatan) VALUES ('$no_dokumen','$id_supplier','$nama_supplier','$tgl_po','$eta_pengiriman','$id_pemohon','$pemohon','$total_dpp','$total_qty','$ppn','$grand_total','$catatan')";

$sql              = mysql_query($sql_master);

// detail data processing -----------------
$get_id_po            = "SELECT `id` FROM `mst_po` WHERE `dokumen` = '$no_dokumen'";

$sql                  = mysql_query($get_id_po);
$id_po                = mysql_fetch_array($sql);

for($i=1; $i<$row; $i++){
  if($_POST['id'.$i] != ''){
    $id_produk          = explode(':',$_POST['id'.$i])[0];
    $nama_produk        = $_POST['produk_jasa'.$i];
    $qty                = $_POST['qty'.$i];
    $price              = $_POST['dpp'.$i];
    $satuan             = $_POST['satuan'.$i];
    $subtotal           = $_POST['sub_total'.$i];

    $sql_detail         = "INSERT INTO `det_po` (id_po, id_produk, nama_produk, qty, price, satuan, subtotal) VALUES ('$id_po[0]','$id_produk','$nama_produk','$qty','$price','$satuan','$subtotal')";

    $sql                = mysql_query($sql_detail);
  }
}
?>

<script language="javascript">
  window.close();
</script>