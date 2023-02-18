<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

$sql_text = "SELECT a.* FROM `mst_produk` a WHERE a.`id_supplier`=0 AND a.`deleted`=0 AND `produk_jasa` LIKE '%$q%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id'].":".$r['produk_jasa']."|".$r['satuan'];
  echo "$list \n";
}
?>