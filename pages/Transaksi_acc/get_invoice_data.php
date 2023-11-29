<?php

include("../../include/koneksi.php");
include "../../include/config.php";

if (isset($_GET['action']) && strtolower($_GET['action']) == 'po_data' && isset($_GET['id'])) {
  $query = "SELECT di.price AS harga,mi.tanggal_invoice as tanggal FROM det_invoice di JOIN mst_invoice mi ON di.id_invoice = mi.id WHERE di.id = ".$_GET['id'] ;

  $get_data = mysql_query($query);
  $data = mysql_fetch_array($get_data);

  // var_dump($data);
  header("Content-Type: application/json");

  echo json_encode($data);
  exit();

}