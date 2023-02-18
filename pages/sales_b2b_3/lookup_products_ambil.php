<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$id_cust = $_GET['id_cust'];
$sql_cmd ="SELECT pc.*,pd.size FROM mst_b2bcustomer_product pc LEFT JOIN mst_b2bproducts pd ON pc.products_id=pd.id WHERE (pc.closed=0) and pc.products_id = '".$id."' and pc.b2bcustomer_id='".$id_cust."' LIMIT 1";
//var_dump($sql_cmd);  
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>