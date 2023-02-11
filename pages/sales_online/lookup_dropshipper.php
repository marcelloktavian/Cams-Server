<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
	
	$sql_note = "Select d.*,jual.deposit as trdeposit from mst_dropshipper d left join (select id_dropshipper,sum(ifnull(deposit,0)) as deposit from olndeposit td group by id_dropshipper ) as jual on d.id = jual.id_dropshipper where (d.deleted=0) and d.nama LIKE '%$q%'";
	
	$sql = mysql_query($sql_note);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'];
	echo "$nama \n";
	}
    
?>
