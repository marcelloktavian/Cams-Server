<?php 

include "../../include/koneksi.php";

if (!isset($_GET['q'])) return;
$q = strtolower($_GET['q']);

  $sql_get_detail = "SELECT x.no_akun, x.nama_akun FROM (SELECT DISTINCT(`noakun`) as no_akun, nama as nama_akun FROM det_coa WHERE id_parent = '58') AS x WHERE x.no_akun LIKE '%$q%' OR x.nama_akun LIKE '%$q%'";

  $query_get_detail = mysql_query($sql_get_detail);

  while($line = mysql_fetch_array($query_get_detail)){
    $list = $line['no_akun'].':'.$line['nama_akun'];
    echo "$list \n";
  };

?>