<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="select * from mst_b2bproducts where (aktif='Y') and deleted=0 and  nama LIKE '%$q%' ORDER BY nama ASC, size ASC";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama']."-".$r['size'];
	echo "$nama \n";
	}
    
?>
