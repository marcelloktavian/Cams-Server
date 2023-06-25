<?php

include "../../include/koneksi.php";
session_start();

// general variable -------------------
$row = $_GET['row'];

// master data processing -------------
$b2breturn_id       = $_POST['id_b2breturn'];
$b2breturn_date     = $_POST['tanggal_b2breturn'];
$b2breturn_cust     = explode(' : ', $_POST['customer_b2breturn'])[0];
$b2breturn_type     = $_POST['type_b2breturn'];
$b2breturn_totalqty = $_POST['total_qty_b2breturn'];
$b2breturn_total    = $_POST['total_b2breturn_value'];
$catatan            = $_POST['keterangan'];
$id_user            = $_SESSION['user']['username'];

$sql_master         = "UPDATE b2breturn SET qty='$b2breturn_totalqty', total='$b2breturn_total', keterangan='$catatan', user='$id_user' WHERE id='$b2breturn_id' ";

$query              = mysql_query($sql_master);

// detail data processing --------------------

$sql_detail_reset = "UPDATE b2breturn_detail SET deleted=1 WHERE id_parent='$b2breturn_id'";
$query_reset      = mysql_query($sql_detail_reset);

for($i=1; $i<$row; $i++){
  if(isset($_POST['idb2b'.$i]) && $_POST['idb2b'.$i] != null && $_POST['idb2b'.$i] != ''){
    $id_b2breturn_detail = $_POST['id_b2breturn_det'.$i];
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

    $sql_detail_check = "SELECT * FROM b2breturn_detail a WHERE a.id_trans_do='$id_master_do' AND a.id_b2bdo_det='$id_detail_do' AND a.id_product='$id_produk' AND deleted=1 LIMIT 1";

    $query_check      = mysql_query($sql_detail_check);
    $detail_check         = mysql_fetch_array($query_check);

    if($query_check){
      $sql_detail = "UPDATE b2breturn_detail SET `id_parent` = '$b2breturn_id', `b2bdo_num` = '$num_trans_do', `id_trans_do` = '$id_master_do', `id_b2bdo_det` = '$id_detail_do', `id_product` = '$id_produk', `namabrg` = '$nama_produk', `id31` = '$id31', `qty31` = '$qty31', `id32` = '$id32', `qty32` = '$qty32', `id33` = '$id33', `qty33` = '$qty33', `id34` = '$id34', `qty34` = '$qty34', `id35` = '$id35', `qty35` = '$qty35', `id36` = '$id36', `qty36` = '$qty36', `id37` = '$id37', `qty37` = '$qty37', `id38` = '$id38', `qty38` = '$qty38', `id39` = '$id39', `qty39` = '$qty39', `id40` = '$id40', `qty40` = '$qty40', `id41` = '$id41', `qty41` = '$qty41', `id42` = '$id42', `qty42` = '$qty42', `id43` = '$id43', `qty43` = '$qty43', `id44` = '$id44', `qty44` = '$qty44', `id45` = '$id45', `qty45` = '$qty45', `id46` = '$id46', `qty46` = '$qty46', harga_satuan = '$harga_satuan', subtotal = '$subtotal', deleted = 0 WHERE id='$detail_check[0]'";
    } else {
      $sql_detail = "INSERT INTO b2breturn_detail (`id_parent`, `b2bdo_num`, `id_trans_do`, `id_b2bdo_det`, `id_product`, `namabrg`, `id31`, `qty31`, `id32`, `qty32`, `id33`, `qty33`, `id34`, `qty34`, `id35`, `qty35`, `id36`, `qty36`, `id37`, `qty37`, `id38`, `qty38`, `id39`, `qty39`, `id40`, `qty40`, `id41`, `qty41`, `id42`, `qty42`, `id43`, `qty43`, `id44`, `qty44`, `id45`, `qty45`, `id46`, `qty46`, harga_satuan, subtotal) VALUES ('".$b2breturn_id."', '".$num_trans_do."', '".$id_master_do."', '".$id_detail_do."', '".$id_produk."' ,'".$nama_produk."', '".$id31."', '".$qty31."', '".$id32."', '".$qty32."', '".$id33."', '".$qty33."', '".$id34."', '".$qty34."', '".$id35."', '".$qty35."', '".$id36."', '".$qty36."', '".$id37."', '".$qty37."', '".$id38."', '".$qty38."', '".$id39."', '".$qty39."', '".$id40."', '".$qty40."', '".$id41."', '".$qty41."', '".$id42."', '".$qty42."', '".$id43."', '".$qty43."', '".$id44."', '".$qty44."', '".$id45."', '".$qty45."', '".$id46."', '".$qty46."', '".$harga_satuan."', '".$subtotal."')";
    }

    $query      = mysql_query($sql_detail);
  }
} 

// * Untuk progress dan list pengurangan data pada do dan data yang telah di return pada b2breturn disimpan pada view b2breturn_qty (tidak perlu melakukan penambahan atau perubahan query pada file ini)
?>

<script language="javascript">
  window.close();
</script>
