<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
$q = str_replace("'","\'",$q);
	$sql_text="select * from mst_b2bproductsgrp where (aktif='Y') and deleted=0 and  nama LIKE '%$q%'";
	//var_dump($sql_text);die;
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'];
	echo "$nama \n";
	}
    
?>
