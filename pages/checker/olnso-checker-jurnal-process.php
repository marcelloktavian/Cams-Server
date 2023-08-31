<?php

include '../../include/koneksi.php';
require 'PHPExcel.php';

ini_set('max_execution_time', 3000);

$mulai = '2023-01-01';
$selesai = '2023-01-31';

$id_dropshipper = '2';
$dropshipper = 'Eshajorii (+6282119089210)';

$query_olnso = mysql_query("SELECT * FROM olnso WHERE tgl_trans BETWEEN '".$mulai."' AND '".$selesai."' AND deleted=0 AND id_dropshipper='".$id_dropshipper."'");
?>

<center>
  <h1>Jurnal - OLNSO Compare Result</h1>
  <h3>Parameter <?= $mulai ?> - <?= $selesai ?></h3>
  <h3>Dropshipper <?= $dropshipper ?></h3>

  <table border=1 width=100%>
    <tr><th>OLNSO ID Trans</th><th>OLNSO Total</th><th>Jurnal Penjualan</th><th>Jurnal Retur</th><th>Result</th></tr>
    <?php

    $total = 0;
    $missing = [];

    $total_olnso = 0;
    $total_penjualan = 0;
    $total_retur = 0;

    if(mysql_num_rows($query_olnso) > 0){
      while($row = mysql_fetch_array($query_olnso)){
        $query1 = mysql_query("SELECT * FROM jurnal WHERE keterangan = 'Penjualan OLN Kredit - ".$dropshipper." - ".$row['id_trans']."' AND deleted=0");

        $query2 = mysql_query("SELECT * FROM jurnal WHERE keterangan = 'CANCELLED OLN Kredit - ".$dropshipper." - ".$row['id_trans']."' AND deleted=0");

        $row1 = mysql_fetch_array($query1);
        $row2 = mysql_fetch_array($query2);

        if($row1['total_kredit']-$row2['total_kredit'] != $row['total']){
          array_push($missing, $row['id_trans']);
        }
        ?>
          <tr <?= $row1['total_kredit']-$row2['total_kredit'] != $row['total'] ? "style='background-color:light-red;'" : "" ?>>
            <td><?= $row['id_trans'] ?></td>
            <td><?= $row['total'] ?></td>
            <?php
              echo "<td>".$row1['total_kredit']."</td>";
              if(isset($row2['total_kredit'])){
                echo "<td>".$row2['total_kredit']."</td>";
              }else{
                echo "<td>0</td>";
              }
            ?>
            <td <?php if($row1['total_kredit']-$row2['total_kredit'] != $row['total']){
              $total ++;
              array_push($missing, $row['id_trans']);
              echo " style='background-color: red'>Not Match";
            } else {
              if(mysql_num_rows($query1) > 1){
                echo " style='background-color: yellow'>Double";
              } else if (mysql_num_rows($query2) > 1){
                echo " style='background-color: yellow'>Double";
              } else {
                echo " style='background-color: lightgreen'>Match";
              }
            }?></td>
          </tr>
        <?php
        $total_olnso += $row['total'];
        $total_penjualan += $row1['total_kredit'];
        $total_retur += $row2['total_kredit'];
      }
    }
    ?>
    <tr>
      <td></td>
      <td><?= $total_olnso ?></td>
      <td><?= $total_penjualan ?></td>
      <td><?= $total_retur ?></td>
      <td></td>
    </tr>
  </table>

  <table border=1 width=100%>
    <tr><th width=20%>Title</th><th width=5%>Total</th><th>Result</th></tr>

    <tr style="font-weight:bold; background-color:red; color:white;">
      <td>Not Match Data ID :</td>
      <td><?= $total ?></td>
      <td><?php foreach($missing as $row){ echo "'$row',"; }?></td>
    </tr>
  </table>
</center>

<script>
  alert('<?= $total ?> Data Not Match');
</script>