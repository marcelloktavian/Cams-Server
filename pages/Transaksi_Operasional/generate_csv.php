<?php
include"../../include/koneksi.php";

$tgl = $_GET['tgl'];
$sql_note = "SELECT id,id_import,keterangan,periode,FORMAT((jumlah-payment),0) as jml, (jumlah-payment) as jumlah FROM acc_prebank WHERE (jumlah-payment)>0 AND SUBSTR(periode,1,10) = DATE_FORMAT('".$tgl."','%d/%m/%Y')";
// var_dump($sql_note);die; 
$sql = mysql_query($sql_note);
$results = array();
while($row = mysql_fetch_array($sql))
{
   $results[] = array(
      'id' => $row['id'],
      'id_import' => $row['id_import'],
      'keterangan' => $row['keterangan'],
      'periode' => $row['periode'],
      'jumlah' => $row['jml'],
      'jumlahhidden' => $row['jumlah'],
   );
}
echo json_encode($results);


?>