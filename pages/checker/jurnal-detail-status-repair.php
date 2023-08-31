<?php

include '../../include/koneksi.php';
ini_set('max_execution_time', 3000);

$query = mysql_query("SELECT a.* FROM jurnal_detail a INNER JOIN det_coa b ON a.no_akun = b.noakun WHERE `status` != 'Detail' AND `status` != 'Parent'");

?>
<center>
<h1>Jurnal Detail Status Repair</h1>
<h3>Parameter : Detail COA</h3>

<table border=1 width=100%>

<tr><th>ID JURNAL DETAIL</th><th>ID JURNAL PARENT</th><th>STATUS SEBELUM</th><th>STATUS SESUDAH</th></tr>
<?php
while($row = mysql_fetch_array($query)){
  $id = $row['id'];
  $repair_query = mysql_query("UPDATE jurnal_detail SET `status`='Detail' WHERE id='$id'") or die(mysql_error());
  ?>
  <tr><td><?= $row['id'] ?></td><td><?= $row['id_parent'] ?></td><td><?= $row['status'] ?></td><td>Detail</td></tr>
  <?php
};

?>
</table>
</center>

<?php

include '../../include/koneksi.php';
ini_set('max_execution_time', 3000);

$query = mysql_query("SELECT a.* FROM jurnal_detail a INNER JOIN mst_coa b ON a.no_akun = b.noakun WHERE `status` != 'Detail' AND `status` != 'Parent'");

?>
<center>
<h1>Jurnal Detail Status Repair</h1>
<h3>Parameter : Master COA</h3>

<table border=1 width=100%>

<tr><th>ID JURNAL DETAIL</th><th>ID JURNAL PARENT</th><th>STATUS SEBELUM</th><th>STATUS SESUDAH</th></tr>
<?php
while($row = mysql_fetch_array($query)){
  $id = $row['id'];
  $repair_query = mysql_query("UPDATE jurnal_detail SET `status`='Parent' WHERE id='$id'") or die(mysql_error());
  ?>
  <tr><td><?= $row['id'] ?></td><td><?= $row['id_parent'] ?></td><td><?= $row['status'] ?></td><td>Parent</td></tr>
  <?php
};

?>
</table>
</center>