<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="select * from mst_composition where (aktif='Y') and deleted=0 and  nama LIKE '%$q%'";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'];
	echo "$nama \n";
	}
    
?>
