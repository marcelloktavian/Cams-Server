<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql_note = "Select d.* from mst_supplier d  where (d.deleted=0) and d.nama LIKE '%$q%'";
	
	$sql = mysql_query($sql_note);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'];
	echo "$nama \n";
	}
    
?>
