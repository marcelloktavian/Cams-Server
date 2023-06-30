<?php
include("../../include/koneksi.php");

$q = strtolower($_GET["q"]);
if (!$q) return;

    $sql_products ="SELECT a.* FROM `mst_coa` a ";

    $query = '';
    $countnya = 0;

    // if($q=='ppn' || $q=='PPN' || $q=='09.01' || $q=='09.01.' || $q=='09.01.0' || $q=='09.01.00' || $q=='09.01.000' || $q=='09.01.0000' || $q=='09.01.00000'){
    //     $query = "SELECT * FROM mst_coa WHERE (noakun like '%$q%' OR nama like '%$q%') ";
    // }else{
        $sql1 = mysql_query($sql_products." where a.deleted=0 AND (SUBSTRING(noakun,1,2)='01' OR SUBSTRING(noakun,1,2)='05' OR SUBSTRING(noakun,1,2)='06') ");
        while($r1 = mysql_fetch_array($sql1)) {
            if ($countnya == 0) {
                $query .= "select id, noakun, nama, jenis from mst_coa where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' AND (noakun like '%$q%' OR nama like '%$q%') ";
            } else {
                $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' AND (noakun like '%$q%' OR nama like '%$q%') ";
            }
            $countnya++;
            $sql2 = mysql_query("SELECT * FROM det_coa WHERE id_parent='".$r1['id']."' ORDER by noakun ASC");
            while($r2 = mysql_fetch_array($sql2)) {
                $query .= " UNION ALL select id, noakun, nama, '' as jenis from det_coa where id='".$r2['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000'  AND (noakun like '%$q%' OR nama like '%$q%') ";
            }
        }
    // }

	

	$sql = mysql_query($query);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama'].";".$r['noakun'];
	echo "$nama \n";
	}

?>
