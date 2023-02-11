<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql = mysql_query("select * from hrd_pph21 where (deleted=0) and  nama_pph21 LIKE '%$q%'");
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id_pph21'].":".$r['nama_pph21'];
	echo "$nama \n";
	}
    
?>
