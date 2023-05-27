<?php

include "../../include/koneksi.php";

if(isset($_GET['action']) && strtolower($_GET['action']) == 'reqakunkredit') {
  if (!isset($_GET['q'])) return;
  $q = strtolower($_GET['q']);

  $sql_get_master = "SELECT x.id, x.no_akun, x.nama_akun FROM (SELECT DISTINCT(`noakun`) as no_akun, nama as nama_akun, id FROM mst_coa) AS x WHERE x.no_akun LIKE '01.%' AND (x.no_akun LIKE '%$q%' OR x.nama_akun LIKE '%$q%')";

  $sql_get_detail = "SELECT x.id, x.no_akun, x.nama_akun FROM (SELECT DISTINCT(`noakun`) as no_akun, nama as nama_akun, id FROM det_coa) AS x WHERE x.no_akun LIKE '01.%' AND (x.no_akun LIKE '%$q%' OR x.nama_akun LIKE '%$q%')";

  $query_get_master = mysql_query($sql_get_master);

  if($query_get_master!=false){
    while($line = mysql_fetch_array($query_get_master)){
      $list = $line['id'].':'.$line['no_akun'].':'.$line['nama_akun'].':Master';
      echo "$list \n";
    };
  }
  

  $query_get_detail = mysql_query($sql_get_detail);

  if($query_get_detail!=false){
    while($line_detail = mysql_fetch_array($query_get_detail)){
      $list = $line_detail['id'].':'.$line_detail['no_akun'].':'.$line_detail['nama_akun'].':Detail';
      echo "$list \n";
    };
  }
}

?>