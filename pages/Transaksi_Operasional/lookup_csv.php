<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql_note = "SELECT `id`, `id_import`, `norek`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, (jumlah-payment) as `jumlah`, `saldoawal`, `mutasikredit` FROM acc_prebank WHERE keterangan LIKE '%$q%' AND (jumlah-payment) <> 0 ";

    $sql = mysql_query($sql_note);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":Periode : ".$r['periode'].'<br>Keterangan : '.$r['keterangan'].'<br>Total : '.number_format($r['jumlah'],0);
	echo "$nama \n";
	}
    
?>
