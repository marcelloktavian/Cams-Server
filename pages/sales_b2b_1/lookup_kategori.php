<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql = mysql_query("select * from mst_b2bcategory_sale where (deleted=0) and  nama LIKE '%$q%'");
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'];
	echo "$nama \n";
	}
    
?>
