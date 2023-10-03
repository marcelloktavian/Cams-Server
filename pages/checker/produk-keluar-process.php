<?php

include '../../include/koneksi.php';
require 'PHPExcel.php';

ini_set('max_execution_time', 3000);

?>


<style>
    tr:nth-child(even){
      background: lightgray;
    }

    td:nth-child(even){
      font-weight: bold;
    }
  </style>

<center>
  <h1>File Compare Result</h1>
  <h3><?= $_FILES['excel_file']['name'] ?></h3>

  <?php

  $date = $_POST['tanggalolnso'];

  $excel_file = $_FILES['excel_file']['tmp_name'];
  $excel = PHPExcel_IOFactory::load($excel_file);

  $data = [];

  foreach ($excel->getWorksheetIterator() as $worksheet) {
    foreach ($worksheet->getRowIterator() as $row) {
      $details = $worksheet->getCellByColumnAndRow(1, $row->getRowIndex())->getValue();

      if($details != '' OR $details != null){
        $data[] = [
          'nama' => $worksheet->getCellByColumnAndRow(1, $row->getRowIndex())->getValue(),
          'total' => $worksheet->getCellByColumnAndRow(23, $row->getRowIndex())->getValue(),
        ];
      }
    }
  }
  ?>

  <table border = 1 width=100%>
    <tr><th>No</th><th>Barang</th><th>Qtyd Excel</th><th>Qty Database</th></tr>

  <?php 
  $i = 1;
  
  foreach($data as $line){
    $query = mysql_query("SELECT COALESCE(SUM('a.namabrg'),0) AS total_barang FROM olnsodetail a LEFT JOIN olnso b ON a.id_trans=b.id_trans WHERE a.namabrg LIKE '".$line['nama']."%' AND DATE(b.lastmodified) = '".$_POST['tanggalolnso']."' GROUP BY a.namabrg");
    var_dump("SELECT COALESCE(SUM('a.namabrg'),0) AS total_barang FROM olnsodetail a LEFT JOIN olnso b ON a.id_trans=b.id_trans WHERE a.namabrg = '".$line['nama']."' AND DATE(b.lastmodified) = '".$_POST['tanggalolnso']."' GROUP BY a.namabrg");
    $olnso = mysql_fetch_array($query);
    ?>
    <tr <?= ($line['total'] != $olnso['total_barang']) ? "style='background-color:lightred';" : "" ?>>
      <td><?= $i ?></td>
      <td><?= $line['nama'] ?></td>
      <td><?= $line['total'] ?></td>
      <td><?= $olnso['total_barang'] ?></td>
    </tr>
    <p> </p>
    <?php
    $i ++;
  }?>
  </table>
