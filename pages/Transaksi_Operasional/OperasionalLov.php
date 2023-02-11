<?php
include("../../include/koneksi.php");

$q = strtolower($_GET["q"]);
if (!$q) return;
	$sql_text="SELECT det.*, mst.nama_jenis FROM det_jenisbiaya det LEFT JOIN mst_jenisbiaya mst ON mst.id=det.id_parent WHERE mst.deleted=0 AND det.nama_biaya LIKE '%$q%' ";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama_biaya']." (".$r['nama_jenis'].")";
	echo "$nama \n";
	}
	

?>
