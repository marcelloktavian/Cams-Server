<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql = mysql_query("select * from hrd_pendapatan_potongan where (deleted=0) and  nama_penpot LIKE '%$q%'");
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id_penpot'].":".$r['nama_penpot']." (".ucfirst($r['type']).")";
	echo "$nama \n";
	}
    
?>
