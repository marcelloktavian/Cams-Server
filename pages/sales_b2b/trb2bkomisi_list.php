<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

if(isset($_GET['req']) && strtolower($_GET['req']) == 'customer'){
  $sql_text = "SELECT a.* FROM mst_b2bcustomer a WHERE nama LIKE '%".$q."%' OR id LIKE '%".$q."%'";
  $sql = mysql_query($sql_text);

  while($r = mysql_fetch_array($sql)){
    $list = $r['nama'];
    echo "$list \n";
  }
} else if(isset($_GET['req']) && strtolower($_GET['req']) == 'salesman'){
  $sql_text = "SELECT a.* FROM mst_b2bsalesman a WHERE nama LIKE '%".$q."%' OR id LIKE '%".$q."%'";
  $sql = mysql_query($sql_text);

  while($r = mysql_fetch_array($sql)){
    $list = $r['nama'];
    echo "$list \n";
  }
}
?>