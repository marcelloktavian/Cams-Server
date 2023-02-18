<?php
include("../../include/koneksi.php");

$exp_code = str_replace(' ','',$_POST['expcode'] );

$sql = "SELECT id FROM olnso so WHERE deleted=0 and exp_code = '".$exp_code."' and exp_code <> ''";

$result = mysql_query($sql);

$row = mysql_num_rows($result);

// var_dump($row);die;

echo json_encode($row);
?>