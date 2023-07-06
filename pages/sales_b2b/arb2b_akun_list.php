<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

$sql_text = "SELECT a.* FROM mst_coa a WHERE nama LIKE '%".$q."%' OR noakun LIKE '%".$q."%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id'].":".$r['noakun']." - ".$r['nama'];
  echo "$list \n";
}

$sql_text = "SELECT a.* FROM det_coa a WHERE nama LIKE '%".$q."%' OR noakun LIKE '%".$q."%'";
$sql = mysql_query($sql_text);

while($r = mysql_fetch_array($sql)){
  $list = $r['id'].":".$r['noakun']." - ".$r['nama'];
  echo "$list \n";
}
?>