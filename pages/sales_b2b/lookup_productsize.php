<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
$id = $_GET['idbrg'];

	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="SELECT id,size FROM mst_b2bproductsgrp_detail WHERE deleted=0 AND id_productsgrp=$id AND size LIKE '%$q%'";
	//var_dump($sql_text);die;
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['size'];
	echo "$nama \n";
	}
    
?>
