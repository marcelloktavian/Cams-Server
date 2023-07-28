<?php

include "../../include/koneksi.php";

if (!isset($_GET['q'])) return;
$q = strtolower($_GET['q']);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'getnama'){
  $sql_get_nama = "SELECT x.nama_akun FROM (SELECT DISTINCT(`nama`) as nama_akun FROM det_coa WHERE nama LIKE 'Piutang OLN - %') AS x WHERE x.nama_akun LIKE '%$q%'";

  $query_get_nama = mysql_query($sql_get_nama);
  
  while($line = mysql_fetch_array($query_get_nama)){
    $list = $line['nama_akun'];
    echo "$list \n";
  };
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'getnomor'){
  $sql_get_nomor = "SELECT x.no_akun FROM (SELECT DISTINCT(`noakun`) as no_akun FROM det_coa WHERE noakun LIKE '01.04.%') AS x WHERE x.no_akun LIKE '%$q%'";

  $query_get_nomor = mysql_query($sql_get_nomor);

  while($line = mysql_fetch_array($query_get_nomor)){
    $list = $line['no_akun'];
    echo "$list \n";
  };
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'getdebet'){
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
  };
}
?>