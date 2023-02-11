<!-- import excel ke mysql -->
<?php 
error_reporting(0);

// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
 $id = uniqid();
	$sql_post="";
		//inisialisasi product dan pelanggan
		$sql_post="INSERT INTO acc_prebank(
		`norek`,`id_import`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, `jumlah`, `saldoawal`, `mutasikredit`,`lastmodified`)
		SELECT xls.`norek`, '".$id."' as id_import,xls.`namarek`, xls.`periode`, xls.`matauang`, xls.`tanggal_trans`, xls.`keterangan`, xls.`cabang`, xls.`jumlah`, xls.`saldoawal`, xls.`mutasikredit`, NOW() as lastmodified FROM `acc_xlsmutation` xls";
		//var_dump($sql_post);die;
		$hasil_post=mysql_query($sql_post);
		//menghapus data di xlscamou
		$sql_delete="";	
		$sql_delete="TRUNCATE TABLE acc_xlsmutation";	
		$hasil_delete=mysql_query($sql_delete);
		$posting=1;

// alihkan halaman ke importcamou.php
header("location:importmutation.php?posting=$posting");
?>