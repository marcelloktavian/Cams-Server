<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql = mysql_query("select * from tblsupplier where (deleted=0) and (type=2) and  namaperusahaan LIKE '%$q%'");
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['namaperusahaan'];
	echo "$nama \n";
	}
    
?>
