<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
// $id = $_GET["id"];
// $stat = $_GET["stat"];
if (!$q) return;
	// if($stat == 'Dropshipper'){
	// 	$sql_note = "(SELECT so.id, so.id_trans, DATE_FORMAT(so.tgl_trans, '%d/%m/%Y') as tgl, (so.faktur-so.payment) as total, dp.nama FROM olnso so LEFT JOIN mst_dropshipper dp ON dp.id=so.id_dropshipper WHERE so.deleted=0 AND so.stkirim=1 AND (so.faktur-so.payment)<>0 AND so.id_dropshipper='$id' AND so.id_trans LIKE '%$q%' ORDER BY so.id DESC LIMIT 10) ";
	// }else if($stat == 'Customer'){
	// 	$sql_note = "(SELECT doo.id, doo.id_trans, DATE_FORMAT(doo.tgl_trans, '%d/%m/%Y') AS tgl, (doo.totalfaktur-doo.payment) AS total, cus.nama FROM b2bdo doo LEFT JOIN mst_b2bcustomer cus ON doo.id_customer=cus.id WHERE doo.deleted=0 AND (doo.totalfaktur-doo.payment)<>0  AND cus.id='$id' AND  id_trans LIKE '%$q%' OR id_transb2bso LIKE '%$q%' ORDER BY doo.id DESC LIMIT 10)";
	// }else{
		$sql_note = "(SELECT so.id, so.id_trans, DATE_FORMAT(so.tgl_trans, '%d/%m/%Y') as tgl, (so.faktur-so.payment) as total, dp.nama FROM olnso so LEFT JOIN mst_dropshipper dp ON dp.id=so.id_dropshipper WHERE so.deleted=0 AND so.stkirim=1 AND (so.faktur-so.payment)<>0 AND so.id_trans LIKE '%$q%' ORDER BY so.id DESC LIMIT 5) UNION ALL (SELECT doo.id, doo.id_trans, DATE_FORMAT(doo.tgl_trans, '%d/%m/%Y') AS tgl, (doo.totalfaktur-doo.payment) AS total, cus.nama FROM b2bdo doo LEFT JOIN mst_b2bcustomer cus ON doo.id_customer=cus.id WHERE doo.deleted=0 AND (doo.totalfaktur-doo.payment)<>0 AND  id_trans LIKE '%$q%' OR id_transb2bso LIKE '%$q%' ORDER BY doo.id DESC LIMIT 5)";
	// }

	// var_dump($sql_note);
    $sql = mysql_query($sql_note);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":ID Trans : ".$r['id_trans'].' ('.$r['nama'].')<br>Tanggal Trans : '.$r['tgl'].'<br>Total : '.number_format($r['total'],0);
	echo "$nama \n";
	}
    
?>