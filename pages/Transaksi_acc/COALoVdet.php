<?php
include("../../include/koneksi.php");

$id = $_GET['id'];
$sql_cmd ="SELECT *, 'parent' as `status` from mst_coa WHERE deleted=0 AND noakun = '$id' LIMIT 1";
$sql = mysql_query($sql_cmd);
$num = mysql_num_rows($sql);

if($num == 0){
    $sql_cmd ="SELECT det_coa.*, 'Detail' as `status` from det_coa LEFT JOIN mst_coa ON mst_coa.id=det_coa.id_parent WHERE mst_coa.deleted=0 AND det_coa.noakun = '$id' LIMIT 1";
    $sql = mysql_query($sql_cmd);
    $row = mysql_fetch_array($sql);
    echo json_encode($row); 
}else{
    $row = mysql_fetch_array($sql);
    echo json_encode($row); 
}
?>