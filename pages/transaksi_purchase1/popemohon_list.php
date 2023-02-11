<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

$sql_text = "SELECT a.* FROM `mst_pemohon_po` a WHERE a.`deleted`=0 AND `pemohon` LIKE '%$q%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id'].":".$r['pemohon'];
  echo "$list \n";
}
?>