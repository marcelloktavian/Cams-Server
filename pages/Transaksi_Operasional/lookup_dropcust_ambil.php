<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$stat = $_GET['stat'];
if($stat=='Customer'){
    $sql_note = "SELECT id, nama, 'Customer' AS `status` FROM mst_b2bcustomer WHERE deleted=0 AND id='".$id."' ";
}else{
    $sql_note = "SELECT id, nama, 'Dropshipper' AS `status` FROM mst_dropshipper WHERE deleted=0 AND id='".$id."' ";
}
//var_dump($sql_note);die;
$sql = mysql_query($sql_note);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>