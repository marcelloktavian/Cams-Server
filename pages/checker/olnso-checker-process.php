<?php

include '../../include/koneksi.php';
require 'PHPExcel.php';

ini_set('max_execution_time', 3000);

?>

<center>
  <h1>File Compare Result</h1>
  <h3><?= $_FILES['excel_file']['name'] ?></h3>

  <?php

  $excel_file = $_FILES['excel_file']['tmp_name'];
  $excel = PHPExcel_IOFactory::load($excel_file);

  $data = [];

  foreach ($excel->getWorksheetIterator() as $worksheet) {
    foreach ($worksheet->getRowIterator() as $row) {
      $details = $worksheet->getCellByColumnAndRow(8, $row->getRowIndex())->getValue();

      preg_match('/Sub-Total - Rp\. ([\d,]+)/', $details, $subtotal_matches);
      $subtotal = isset($subtotal_matches[1]) ? str_replace(',', '', $subtotal_matches[1]) : '';

      preg_match('/PPN \(11%\) - Rp\. ([\d,]+)/', $details, $ppn_matches);
      $ppn = isset($ppn_matches[1]) ? str_replace(',', '', $ppn_matches[1]) : '';

      preg_match('/\nTotal - Rp\. ([\d,-]+)/', $details, $transfer_matches);
      $transfer = isset($transfer_matches[1]) ? str_replace(',', '', $transfer_matches[1]) : '';

      preg_match('/Store Credit - Rp\. ([\d,-]+)/', $details, $deposit_matches);
      $deposit = isset($deposit_matches[1]) ? str_replace(',', '', $deposit_matches[1]) : '';

      preg_match('/(?<!Sub-Total)(?<!PPN \(11%\))(?<!Total)([A-Z\s]+)\s*-\s*Rp\.\s*([\d,]+)/', $details, $expfee_matches);
      $expfee = isset($expfee_matches[2]) ? str_replace(',', '', $expfee_matches[2]) : '';

      $data[] = [
        'ref_kode' => $worksheet->getCellByColumnAndRow(0, $row->getRowIndex())->getValue(),
        'transfer' => $transfer,
        'deposit' => $deposit,
        'subtotal' => $subtotal,
        'ppn' => $ppn,
        'expfee' => $expfee,
      ];
    }
  }
  ?>

  <style>
    tr:nth-child(even){
      background: lightgray;
    }

    td:nth-child(even){
      font-weight: bold;
    }
  </style>

  <table border = 1 width=100%>
    <thead><tr><th>ID TRANS</th><th>EXCEL Ref Kode</th><th>DATA Ref Kode</th><th>EXCEL transfer</th><th>DATA Transfer</th><th>EXCEL Deposit</th><th>DATA Deposit</th><th>EXCEL Subtotal</th><th>DATA Faktur</th><th>EXCEL exp fee</th><th>DATA exp fee</th><th>Result</th></tr></thead>
    <tbody>
  <?php

  $no_id = [];
  $error_id = [];

  foreach ($data as $row) {
    $ref_kode = $row['ref_kode'];
    $transfer = $row['transfer'];
    $deposit = -$row['deposit'];
    $subtotal_ppn = $row['subtotal'] + $row['ppn'];
    $expfee = $row['expfee'];

    $query = mysql_query("SELECT * FROM olnso WHERE ref_kode = '$ref_kode'");
    $olnso = mysql_fetch_array($query);
    ?>
    
    <tr <?= $olnso['deleted']==0 ? '':'style=background-color:yellow;' ?>>

    <td><?= $olnso['id_trans'] ?></td>
    <td><?= $row['ref_kode']?></td>
    <td><?= $olnso['ref_kode']?></td>
    <td><?= $row['transfer']?></td>
    <td <?= $row['transfer'] == $olnso['transfer'] ? "" : "style=background-color:red; font-weight: bold; color: white;"?>><?= $olnso['transfer']?></td>
    <td><?= $row['deposit']?></td>
    <td <?= -$row['deposit'] == $olnso['deposit'] ? "" : "style=background-color:red; font-weight: bold; color: white;"?>><?= $olnso['deposit']?></td>
    <td><?= ($row['ppn']+$row['subtotal'])?></td>
    <td <?= ($row['ppn']+$row['subtotal']) == $olnso['faktur'] ? "" : "style=background-color:red; font-weight: bold; color: white;"?>><?= $olnso['faktur']?></td>
    <td><?= $row['expfee']?></td>
    <td <?= $row['expfee'] == $olnso['exp_fee'] ? "" : "style=background-color:red; font-weight: bold; color: white;"?>><?= $olnso['exp_fee']?></td>

    <?php

    if ($olnso) {
      if ($transfer == $olnso['transfer'] && $deposit == $olnso['deposit'] && $subtotal_ppn == $olnso['faktur'] && $expfee == $olnso['exp_fee']) {
        echo "<td style='background-color:lightgreen'>Match</td>";
      } else {
        echo "<td style='background-color:red'>Not match</td>";
        array_push($error_id, $olnso['id_trans']);
      }
    } else {
      echo "<td style='background-color:yellow'>Not found</td>";
      array_push($no_id, $ref_kode);
    }

    echo '</tr>';
  }
  ?>
    </tbody>
  </table>

  <br>

  <table border=1 width=100%>
    <tr><th width=20%>Title</th><th>Result</th></tr>

    <tr style="font-weight:bold; background-color:red; color:white;">
      <td>Error Data ID :</h4>
      <td><?php foreach($error_id as $row){ echo "'$row',"; }?></td>
    </tr>
    <tr style="font-weight:bold; color:yellow; background-color: black;">
      <td>Missing Data ID :</td>
      <td><?php foreach($no_id as $row){ echo "'$row',"; }?></td>
    </tr>
  </table>
</center>

<script>
  alert('Not Macthed : <?= count($error_id) ?>\nNot Found : <?= count($no_id) ?>');
</script>