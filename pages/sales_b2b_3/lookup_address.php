<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
	//$sql_text="select * from mst_address where (deleted=0)  and (  kecamatan LIKE '%$q%' or kabupaten LIKE '%$q%')";
	$sql_text="select * from mst_address where (deleted=0)  and (  kecamatan LIKE '%$q%')";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['kecamatan']."-".$r['kabupaten']."-".$r['provinsi'];
	echo "$nama \n";
	}
    
?>
