<?php

include "../../include/koneksi.php";
session_start();


// general variable -------------------------
$row                = $_GET['row'];

// master data processing
$roman_month        = str_pad(date('n'), 2, '0', STR_PAD_LEFT);
$year_digit         = date('y');

$dokumen_header     = 'B2BRETUR'.date('y').$roman_month.'';

$serial_check_sql   = "SELECT `b2breturn_num` FROM `b2breturn` WHERE `b2breturn_num` LIKE '%$dokumen_header%' ORDER BY `id` DESC LIMIT 1";

$serial_result      = mysql_query($serial_check_sql);
$serial             = mysql_fetch_array($serial_result);

if($serial[0] != null){
  $serial_number    = intval(substr($serial[0], -5))+1;
}
else {
  $serial_number    = 1;
}

$serial_number      = str_pad($serial_number, 5, '0', STR_PAD_LEFT);

$b2breturn_num      = $dokumen_header.$serial_number;
$b2breturn_date     = $_POST['tanggal_b2breturn'];
$b2breturn_cust     = explode(' : ', $_POST['customer_b2breturn'])[0];
$b2breturn_type     = $_POST['type_b2breturn'];
$b2breturn_totalqty = $_POST['total_qty_b2breturn'];
$b2breturn_total    = $_POST['total_b2breturn_value'];
$catatan            = $_POST['keterangan'];
$id_user            = $_SESSION['user']['username'];

$sql_master         = "INSERT INTO `b2breturn` (b2breturn_num, b2bcust_id, id_kategori, tgl_return, qty, total, keterangan, user) VALUES ('".$b2breturn_num."', '".$b2breturn_cust."', '".$b2breturn_type."','".$b2breturn_date."', '".$b2breturn_totalqty."','".$b2breturn_total."', '".$catatan."', '".$id_user."')";

$query              = mysql_query($sql_master);

// detail data processing -----------------

$get_id_b2breturn   = "SELECT `id` FROM `b2breturn` WHERE `b2breturn_num` = '$b2breturn_num' AND `b2bcust_id`='$b2breturn_cust' AND `tgl_return` = '$b2breturn_date' AND `total` = '$b2breturn_total' AND `keterangan` = '$catatan' ";

$sql_get_id         = mysql_query($get_id_b2breturn);
$id_b2breturn       = mysql_fetch_array($sql_get_id);

for($i=1; $i<$row; $i++){
  if($_POST['idb2b'.$i] != ''){
    $num_trans_do = $_POST['idb2b'.$i];
    $id_master_do = $_POST['idmstb2b'.$i];
    $id_detail_do = $_POST['iddetb2b'.$i];
    $id_produk    = $_POST['idproduk'.$i];
    $nama_produk  = $_POST['namaproduk'.$i];
    $harga_satuan = $_POST['harga'.$i];
    $subtotal     = $_POST['total'.$i];
    $id31         = $_POST['id-'.$i."-31"];
    $qty31        = $_POST['qty-'.$i."-31"];
    $id32         = $_POST['id-'.$i."-32"];
    $qty32        = $_POST['qty-'.$i."-32"];
    $id33         = $_POST['id-'.$i."-33"];
    $qty33        = $_POST['qty-'.$i."-33"];
    $id34         = $_POST['id-'.$i."-34"];
    $qty34        = $_POST['qty-'.$i."-34"];
    $id35         = $_POST['id-'.$i."-35"];
    $qty35        = $_POST['qty-'.$i."-35"];
    $id36         = $_POST['id-'.$i."-36"];
    $qty36        = $_POST['qty-'.$i."-36"];
    $id37         = $_POST['id-'.$i."-37"];
    $qty37        = $_POST['qty-'.$i."-37"];
    $id38         = $_POST['id-'.$i."-38"];
    $qty38        = $_POST['qty-'.$i."-38"];
    $id39         = $_POST['id-'.$i."-39"];
    $qty39        = $_POST['qty-'.$i."-39"];
    $id40         = $_POST['id-'.$i."-40"];
    $qty40        = $_POST['qty-'.$i."-40"];
    $id41         = $_POST['id-'.$i."-41"];
    $qty41        = $_POST['qty-'.$i."-41"];
    $id42         = $_POST['id-'.$i."-42"];
    $qty42        = $_POST['qty-'.$i."-42"];
    $id43         = $_POST['id-'.$i."-43"];
    $qty43        = $_POST['qty-'.$i."-43"];
    $id44         = $_POST['id-'.$i."-44"];
    $qty44        = $_POST['qty-'.$i."-44"];
    $id45         = $_POST['id-'.$i."-45"];
    $qty45        = $_POST['qty-'.$i."-45"];
    $id46         = $_POST['id-'.$i."-46"];
    $qty46        = $_POST['qty-'.$i."-46"];

    $sql_detail = "INSERT INTO b2breturn_detail (`id_parent`, `b2bdo_num`, `id_trans_do`, `id_b2bdo_det`, `id_product`, `namabrg`, `id31`, `qty31`, `id32`, `qty32`, `id33`, `qty33`, `id34`, `qty34`, `id35`, `qty35`, `id36`, `qty36`, `id37`, `qty37`, `id38`, `qty38`, `id39`, `qty39`, `id40`, `qty40`, `id41`, `qty41`, `id42`, `qty42`, `id43`, `qty43`, `id44`, `qty44`, `id45`, `qty45`, `id46`, `qty46`, harga_satuan, subtotal) VALUES ('".$id_b2breturn[0]."', '".$num_trans_do."', '".$id_master_do."', '".$id_detail_do."', '".$id_produk."' ,'".$nama_produk."', '".$id31."', '".$qty31."', '".$id32."', '".$qty32."', '".$id33."', '".$qty33."', '".$id34."', '".$qty34."', '".$id35."', '".$qty35."', '".$id36."', '".$qty36."', '".$id37."', '".$qty37."', '".$id38."', '".$qty38."', '".$id39."', '".$qty39."', '".$id40."', '".$qty40."', '".$id41."', '".$qty41."', '".$id42."', '".$qty42."', '".$id43."', '".$qty43."', '".$id44."', '".$qty44."', '".$id45."', '".$qty45."', '".$id46."', '".$qty46."', '".$harga_satuan."', '".$subtotal."')";

    $query      = mysql_query($sql_detail);
  }
}

// * Untuk progress dan list pengurangan data pada do dan data yang telah di return pada b2breturn disimpan pada view b2breturn_qty (tidak perlu melakukan penambahan atau perubahan query pada file ini)

?>
<script language="javascript">
  window.close();
</script>
