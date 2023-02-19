<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);
$sup = $_GET['sup'];

if (!$q) return;

$sql_text = "SELECT a.*,date_format(a.tgl_quotation, '%d-%m-%Y') as tgl_quotation_formatted FROM `mst_produk` a WHERE a.`id_supplier`='".$sup."' AND a.`deleted`=0 AND `produk_jasa` LIKE '%$q%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id'].":".$r['produk_jasa']." - ".$r['satuan']." : ".$r['tgl_quotation_formatted']." Rp ".number_format($r['harga'], 0);
  echo "$list \n";
}
?>