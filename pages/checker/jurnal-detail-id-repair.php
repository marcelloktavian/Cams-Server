<?php

include '../../include/koneksi.php';
ini_set('max_execution_time', 3000);

$query = mysql_query("SELECT * FROM det_coa");

?>
<center>
<h1>Jurnal Detail COA ID Repair</h1>
<h3>Parameter : Detail COA</h3>

<table border=1 width=100%>

<tr><th>ID AKUN</th><th>NOMOR AKUN</th><th>NAMA AKUN</th><th>TOTAL REPAIRED</th></tr>
<?php
$i = 0;
while($row = mysql_fetch_array($query)){
  $id_akun = $row['id'];
  $nama_akun = str_replace("'","\'",$row['nama']);
  $nomor_akun = $row['noakun'];
  $repair_query = mysql_query("UPDATE jurnal_detail SET `id_akun`='$id_akun', `nama_akun`='$nama_akun' WHERE `no_akun`='$nomor_akun'") or die(mysql_error());
  ?>
  <tr><td><?= $id_akun ?></td><td><?= $nomor_akun ?></td><td><?= $nama_akun ?></td><td><?= mysql_affected_rows() ?></td></tr>
  <?php
};
?>

</table>
</center>

<?php

include '../../include/koneksi.php';
ini_set('max_execution_time', 3000);

$query = mysql_query("SELECT * FROM mst_coa WHERE deleted=0");

?>
<center>
<h1>Jurnal Detail COA ID Repair</h1>
<h3>Parameter : Master COA</h3>

<table border=1 width=100%>

<tr><th>ID AKUN</th><th>NOMOR AKUN</th><th>NAMA AKUN</th><th>TOTAL REPAIRED</th></tr>
<?php
$i = 0;
while($row = mysql_fetch_array($query)){
  $id_akun = $row['id'];
  $nama_akun = str_replace("'","\'",$row['nama']);
  $nomor_akun = $row['noakun'];
  $repair_query = mysql_query("UPDATE jurnal_detail SET `id_akun`='$id_akun', `nama_akun`='$nama_akun' WHERE `no_akun`='$nomor_akun'") or die(mysql_error());
  ?>
  <tr><td><?= $id_akun ?></td><td><?= $nomor_akun ?></td><td><?= $nama_akun ?></td><td><?= mysql_affected_rows() ?></td></tr>
  <?php
};
?>

</table>
</center>