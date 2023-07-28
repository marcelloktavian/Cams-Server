<?php
include "../../include/koneksi.php";

if(isset($_GET['detail']) && strtolower($_GET['detail']) == 'getnama'){
  $sql_get_nomor = "SELECT nama as nama_akun FROM det_coa WHERE noakun='".$_GET['nomor_akun']."' LIMIT 1";

  $query_get_nomor = mysql_query($sql_get_nomor);
  $line = mysql_fetch_array($query_get_nomor);

  echo json_encode($line);
}
elseif(isset($_GET['detail']) && strtolower($_GET['detail']) == 'getnomor'){

  $nama_akun_coa = trim(explode("(",$_GET['nama_akun'])[0]," ");
  $telp_akun_coa = trim(explode("(",$_GET['nama_akun'])[1]," ");

  $sql_get_nomor = "SELECT noakun as no_akun FROM det_coa WHERE nama LIKE '$nama_akun_coa%_%$telp_akun_coa' LIMIT 1";

  $query_get_nomor = mysql_query($sql_get_nomor);
  $line = mysql_fetch_array($query_get_nomor);

  echo json_encode($line);
}
?>