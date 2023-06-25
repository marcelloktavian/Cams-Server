<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

$sql_text = "SELECT a.* FROM mst_b2bcustomer a WHERE nama LIKE '%".$q."%' OR id LIKE '%".$q."%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id']." : ".$r['nama'];
  echo "$list \n";
}
?>