<?php
$id_user=$_SESSION['id_user'];
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------
if (isset($_GET['id'])){
  $id_supplier = $_GET['id'];
}
else {
  $id_supplier = 0;
}
$supplier = strtoupper($_POST['supplier']);
$pic      = strtoupper($_POST['pic']);
$alamat   = $_POST['alamat'];
$contact  = $_POST['contact'];
$email    = $_POST['email'];
$ktp      = $_POST['ktp'];
$bank     = $_POST['bank'];
$rekening = $_POST['rekening'];
$npwp     = $_POST['npwp'];
$pkp      = $_GET['pkp'];
$totalqty = $_POST['totalqty'];

$check_master = "SELECT * FROM `mst_supplier` WHERE id='$id_supplier' LIMIT 1";
$check_val = mysql_query($check_master);
$check_val = mysql_fetch_array($check_val);

$check_count = $check_val[0];

if($check_count != null){
  $sql_master = "UPDATE `mst_supplier` SET `vendor`='$supplier', `pic`='$pic', `alamat`='$alamat', `telp`='$contact', `email`='$email', `ktp`='$ktp', `bank`='$bank', `rekening`='$rekening', `npwp`='$npwp' , `pkp`='$pkp', `item`='$totalqty', `lastmodified`=NOW() WHERE id='$id_supplier'";
  
  $sql = mysql_query($sql_master);
}
else{
  $sql_master = "INSERT INTO `mst_supplier` (vendor, pic, alamat, telp, email, ktp, bank, rekening, npwp, pkp, item, lastmodified) VALUES ('$supplier', '$pic', '$alamat', '$contact', '$email', '$ktp', '$bank', '$rekening','$npwp', '$pkp', '$totalqty', NOW())";
  $sql = mysql_query($sql_master);

  $last_id = mysql_insert_id();
	$akun = '';
	$namaakun = '';
	$idakun = '';

	// Akun Hutang Dagang Vendor
	$query_mysql = mysql_query("SELECT id, CONCAT(SUBSTR(noakun,1,6),'1', IF(LENGTH('$last_id')=1,'000',IF(LENGTH('$last_id')=2,'00',IF(LENGTH('$last_id')=3,'0',''))), '$last_id') AS akun, noakun, nama
	FROM mst_coa WHERE noakun = '02.01.00000' AND deleted=0")or die(mysql_error());
	while($data = mysql_fetch_array($query_mysql)){
		$akun = $data['akun'];
		$namaakun = $data['nama'].' - '.$supplier;
		$idakun = $data['id'];
		$user = $_SESSION['user']['username'];
		
		$sqlinsert="INSERT INTO det_coa VALUES(NULL, '$idakun', '$akun', '$namaakun', '$user', NOW())";
		mysql_query($sqlinsert) or die (mysql_error());
	}
}



// detail data processing -----------------
if($id_supplier == 0){
  $id_supplier = "SELECT `id` FROM `mst_supplier` WHERE `vendor`='".$supplier."' AND `pic`='".$pic."' AND `ktp`='".$ktp."' LIMIT 1";

  $sql = mysql_query($id_supplier);
  $id_supplier = mysql_fetch_array($sql);

  $sql_reset = "UPDATE `mst_produk` SET `id_supplier`='0' WHERE `id_supplier`='".$id_supplier['id']."'";

  $id_sup = $id_supplier['id'];
}
else{
  $sql_reset = "UPDATE `mst_produk` SET `id_supplier`='0' WHERE `id_supplier`='".$id_supplier."'";

  $id_sup = $id_supplier;
}

$sql = mysql_query($sql_reset);

for($i=1; $i<$row; $i++){
  if(isset($_POST['id'.$i])){
    $id_detail = explode(':',$_POST['id'.$i])[0];

    $sql_detail = "UPDATE `mst_produk` SET `id_supplier`='".$id_sup."' WHERE `id`='".$id_detail."'";

    $sql = mysql_query($sql_detail);
  }
}

?>
<script language="javascript">
  window.close();
</script>