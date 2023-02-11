<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql_note = "SELECT id, nama, 'Dropshipper' AS `status` FROM mst_dropshipper WHERE deleted=0  and nama LIKE '%$q%' UNION ALL SELECT id, nama, 'Customer' AS `status` FROM mst_b2bcustomer WHERE deleted=0 and nama LIKE '%$q%'";

    $sql = mysql_query($sql_note);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'].":".$r['status'];
	echo "$nama \n";
	}
    
?>
