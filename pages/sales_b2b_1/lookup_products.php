<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
$id_cust = strtolower($_GET["id_cust"]);
	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="select pd.*,b.size from mst_b2bcustomer_product pd left join mst_b2bproducts b on pd.products_id=b.id where (pd.closed=0) and (pd.b2bcustomer_id='$id_cust') and  nama_produk LIKE '%$q%'";
	//var_dump($sql_text);die;
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['products_id'].":".$r['nama_produk']."-".$r['size'];
	echo "$nama \n";
	}
    
?>
