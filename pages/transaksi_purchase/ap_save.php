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

// master data processing
$roman_month      = monthToRoman(date('n'));
$year_digit       = date('y');

$dokumen_header   = 'AK'.date('y').'/AP-'.$roman_month.'/';

$serial_check_sql = "SELECT `ap_num` FROM `mst_ap` WHERE `ap_num` LIKE '%$dokumen_header%' ORDER BY `id` DESC LIMIT 1";

$serial_result    = mysql_query($serial_check_sql);
$serial           = mysql_fetch_array($serial_result);

if($serial[0] != null){
  $serial_number  = end(explode('/', $serial['ap_num']))+1;
}
else {
  $serial_number  = 1;
}

$serial_number    = str_pad($serial_number, 3, '0', STR_PAD_LEFT);

$ap_num         = $dokumen_header.$serial_number;
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

$sql_master     = "INSERT INTO `mst_ap` (`ap_num`,`ap_date`,`id_supplier`,`nama_supplier`,`id_akun`,`no_akun`,`nama_akun`,`total_qty`,`grand_total`,`remaining`,`catatan`) VALUES ('$ap_num','$ap_date','$id_supplier','$nama_supplier','$id_akun','$nomor_akun','$nama_akun','$total_qty','$grand_total','$remaining','$catatan')";

$query          = mysql_query($sql_master);

// detail data processing -----------------

$get_id_ap      = "SELECT `id` FROM `mst_ap` WHERE `ap_num` = '$ap_num' AND `id_supplier`='$id_supplier' AND `ap_date` = '$ap_date' AND `total_qty` = '$total_qty' AND `remaining` = '$remaining' AND `catatan`='$catatan'";

$sql_get_id     = mysql_query($get_id_ap);
$id_ap          = mysql_fetch_array($sql_get_id);

for($i=1; $i<$row; $i++){
  if($_POST['id_invoice'.$i] != ''){
    $id_invoice           = $_POST['id_invoice'.$i];
    $no_invoice           = $_POST['nomor_invoice'.$i];
    $tanggal_invoice      = $_POST['tanggal_invoice'.$i];
    $tanggal_jatuh_tempo  = $_POST['tanggal_jatuh_tempo'.$i];
    $qty                  = $_POST['qty'.$i];
    $total                = $_POST['total_inv'.$i];
    $remaining            = $_POST['total_sisa_inv'.$i];

    $sql_detail = "INSERT INTO `det_ap` (`id_ap`,`id_invoice`,`no_invoice`,`tanggal_invoice`,`tanggal_jatuh_tempo`,`qty`,`remaining`,`total`) VALUES ('$id_ap[0]','$id_invoice','$no_invoice','$tanggal_invoice','$tanggal_jatuh_tempo','$qty','$remaining','$total')";
    
    $query      = mysql_query($sql_detail);
  }
}

?>

<script language="javascript">
  window.close();
</script>