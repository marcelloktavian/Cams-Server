<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<?php
	include("../include/koneksi.php");
	?>
</head>
<?php
// SELECT id, CONCAT(SUBSTR(b.noakun,1,8),'00000') AS akun FROM mst_coa b
// $data = mysql_query("SELECT id, CONCAT(SUBSTR(b.noakun,1,8),SUBSTR(b.noakun,9),'000') as akun FROM mst_coa b");
// while($d = mysql_fetch_array($data)){
//     $query = "UPDATE mst_coa SET noakun='".$d['akun']."' WHERE id='".$d['id']."'  ";
//     mysql_query($query);
// }

// $data = mysql_query("SELECT id,CONCAT(SUBSTR(noakun,1,8),'000',SUBSTR(noakun,9)) AS akun FROM det_coa");
// while($d = mysql_fetch_array($data)){
//     $query = "UPDATE det_coa SET noakun='".$d['akun']."' WHERE id='".$d['id']."'  ";
//     mysql_query($query);
// }

// $data = mysql_query("INSERT INTO det_coa(id, id_parent, noakun, nama)
// (SELECT NULL, '17', CONCAT('02.02.',IF(LENGTH(id)=1,'0000',IF(LENGTH(id)=2,'000',IF(LENGTH(id)=3,'00',IF(LENGTH(id)=4,'0','')))),id) AS akun, CONCAT('Saldo Titipan ',nama) AS nama FROM mst_dropshipper WHERE deleted=0)");

// $data = mysql_query("SELECT id, CONCAT('4.01.00.',IF(LENGTH(id)=1,'0000',IF(LENGTH(id)=2,'000',IF(LENGTH(id)=3,'00',IF(LENGTH(id)=4,'0','')))),id) AS akun FROM mst_dropshipper");
// while($d = mysql_fetch_array($data)){
//     $query = "UPDATE mst_dropshipper SET no_akun='".$d['akun']."' WHERE id='".$d['id']."'  ";
//     mysql_query($query);
// }

// $data = mysql_query("INSERT INTO det_coa(id, id_parent, noakun, nama)
// (SELECT NULL, '33', CONCAT('4.03.00.',IF(LENGTH(id)=1,'0000',IF(LENGTH(id)=2,'000',IF(LENGTH(id)=3,'00',IF(LENGTH(id)=4,'0','')))),id) AS akun, CONCAT('Pendapatan B2B - ',nama) AS nama FROM mst_b2bcustomer WHERE deleted=0)");

// $data = mysql_query("SELECT id, CONCAT('4.03.00.',IF(LENGTH(id)=1,'0000',IF(LENGTH(id)=2,'000',IF(LENGTH(id)=3,'00',IF(LENGTH(id)=4,'0','')))),id) AS akun FROM mst_b2bcustomer");
// while($d = mysql_fetch_array($data)){
//     $query = "UPDATE mst_b2bcustomer SET no_akun='".$d['akun']."' WHERE id='".$d['id']."'  ";
//     mysql_query($query);
// }
?>