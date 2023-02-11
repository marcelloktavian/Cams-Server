<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$id_trans=$_GET['trans'];

if(substr($id_trans,0,3) == 'OLN'){
    $sql_note = "SELECT olnso.id, olnso.id_trans, DATE_FORMAT(olnso.tgl_trans, '%d/%m/%Y') as tgl, (olnso.faktur-olnso.payment) as total, FORMAT((olnso.faktur-olnso.payment),0) as total2, (olnso.faktur-olnso.payment) as total1, 'Dropshipper' as stat,id_dropshipper as idnya, mst_dropshipper.nama as namadropcust FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE olnso.id='".$id."' ";
}else{
    $sql_note = "SELECT b2bdo.id, b2bdo.id_trans, DATE_FORMAT(b2bdo.tgl_trans, '%d/%m/%Y') as tgl, (b2bdo.totalfaktur-b2bdo.payment) as total,FORMAT((b2bdo.totalfaktur-b2bdo.payment),0) as total2,(b2bdo.totalfaktur-b2bdo.payment)  as total1,'Customer' as stat,id_customer as idnya, mst_b2bcustomer.nama as namadropcust FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.id='".$id."' ";
}
//var_dump($sql_note);die;
$sql = mysql_query($sql_note);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>