<?php
include("../../include/koneksi.php");

$q = strtolower($_GET["q"]);
if (!$q) return;
	$sql_text=" SELECT * FROM mst_operasional WHERE deleted=0 AND namaoperasional LIKE '%$q%' ";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['namaoperasional'];
	echo "$nama \n";
	}
	

?>
