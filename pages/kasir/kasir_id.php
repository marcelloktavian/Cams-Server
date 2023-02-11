<?php
include"../../include/koneksi.php";
//input id transaksi diganti di jual_simpan
//mysql_query("insert into trjual_id(lastmodified) values(NOW())") or die (mysql_error());
    $id_pelanggan=$_GET['id'];
    //$id_faktur=TSO18020021;
	
	$sql = mysql_query("SELECT * FROM tblpelanggan a 
	where a.id='".$id_pelanggan."'");	
	$rs = mysql_fetch_array($sql);
   
    header("location:jual_kasir_yjs.php?id=".$_GET['id']."&nama=".$rs['namaperusahaan']."");
	//header("location:jual_notaKasir1.php?id_trans=".$id_pkb."");
	
?>
