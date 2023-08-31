<?php

include '../../include/koneksi.php';
ini_set('max_execution_time', 3000);

$query = mysql_query("SELECT a.id_trans AS x_id, a.deposit AS x_dep, b.id_trans AS y_id, b.deposit AS y_dep FROM olnso a LEFT JOIN olndeposit b ON a.id_trans=b.id_trans WHERE a.id_trans LIKE 'OLN".$_POST['tahun']."%' AND a.deposit > 0 AND (a.deposit!=-b.deposit OR b.deposit IS NULL)");
?>

<center>
  <h1>Deposit Compare Result</h1>
  <h3>Parameter : <b><?=$_POST['tahun']?></b></h3>

  <table border=1 width=100%>
    <tr><th>OLNSO ID Trans</th><th>OLNSO Deposit</th><th>OLNDEP ID Trans</th><th>OLNDEP Deposit</th><th>Result</th></tr>

    <?php

    $missing = [];
    $error = [];
    if (mysql_num_rows($query) > 0) {
      while($row = mysql_fetch_array($query)){
        ?>
        <tr>
          <td><?= $row['x_id'] ?></td>
          <td><?= $row['x_dep'] ?></td>
          <td <?= $row['y_id'] != $row['x_id'] ? 'style="background-color:red"':'' ?> ><?= $row['y_id'] ?></td>
          <td <?= $row['y_dep'] != -$row['x_dep'] ? 'style="background-color:red"':'' ?> ><?= $row['y_dep'] ?></td>
          <td <?php 
          
            if($row['x_id'] == $row['y_id']){
              if($row['x_dep'] == -$row['y_dep']){
                echo " style='background-color: lightgreen'>Match";
              }
              else{
                echo " style='background-color: red'>Not Match";
                array_push($error, $row['x_id']);
              }
            }
            else{
              echo " style='background-color: yellow'>Not Found";
              array_push($missing, $row['x_id']);
            }

          ?></td>
          </tr>
        <?php
      }
      ?>
        </table>
        <br>
      <?php
    }
    else{
      echo "<table border=1 width=100%><tr><th colspan='2' style='background-color: lightgreen'>All Deposit Value is Matched</th></tr>";
      echo "<br><br><tr><th>Query</th><td>"."SELECT a.id_trans AS x_id, a.deposit AS x_dep, b.id_trans AS y_id, b.deposit AS y_dep FROM olnso a LEFT JOIN olndeposit b ON a.id_trans=b.id_trans WHERE a.id_trans LIKE 'OLN".$_POST['tahun']."%' AND a.deposit > 0 AND a.deposit!=-b.deposit"."</td></tr></table>";
    }
    ?>

  <table border=1 width=100%>
    <tr><th width=20%>Title</th><th>Result</th></tr>

    <tr style="font-weight:bold; background-color:red; color:white;">
      <td>Error Data ID :</h4>
      <td><?php foreach($error as $row){ echo "'$row',"; }?></td>
    </tr>
    <tr style="font-weight:bold; color:yellow; background-color: black;">
      <td>Missing Data ID :</td>
      <td><?php foreach($missing as $row){ echo "'$row',"; }?></td>
    </tr>
  </table>
  

</center>