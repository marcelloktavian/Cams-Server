<?php
include"../include/koneksi.php";
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql = mysql_query("select * from tblpelanggan where namaperusahaan LIKE '%$q%'");
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['namaperusahaan'];
	echo "$nama \n";
	}
    
?>
