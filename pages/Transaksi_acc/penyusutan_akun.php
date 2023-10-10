<?php
include("../../include/koneksi.php");
$q = strtolower($_GET['q']);

if (!$q) return;

if(isset($_GET['req']) && strtolower($_GET['req']) == 'pembelian'){
  $sql_get_master = "SELECT x.no_akun, x.nama_akun FROM (SELECT DISTINCT(`noakun`) as no_akun, nama as nama_akun FROM mst_coa WHERE noakun LIKE '01.%') AS x WHERE x.no_akun LIKE '%$q%' OR x.nama_akun LIKE '%$q%'";

  $sql_get_detail = "SELECT x.no_akun, x.nama_akun FROM (SELECT DISTINCT(`noakun`) as no_akun, nama as nama_akun FROM det_coa WHERE noakun LIKE '01.%') AS x WHERE x.no_akun LIKE '%$q%' OR x.nama_akun LIKE '%$q%'";

  $query_get_master = mysql_query($sql_get_master);

  while($line = mysql_fetch_array($query_get_master)){
    $list = $line['no_akun'].':'.$line['nama_akun'];
    echo "$list \n";
  };

  $query_get_detail = mysql_query($sql_get_detail);

  while($line = mysql_fetch_array($query_get_detail)){
    $list = $line['no_akun'].':'.$line['nama_akun'];
    echo "$list \n";
  }
}
?>