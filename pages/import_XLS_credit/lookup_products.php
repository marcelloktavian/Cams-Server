<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="select * from mst_products where (aktif='Y') and  nama LIKE '%$q%'";
	//var_dump($sql_text);
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama']."-".$r['size'];
	echo "$nama \n";
	}
    
?>
