<!-- import excel ke mysql -->
<?php 
error_reporting(0);

// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
	$sql_post="";
		//inisialisasi product dan pelanggan
		$sql_post="INSERT INTO olnpreso(
		oln_order_id ,oln_productid ,namabrg ,size ,harga_satuan ,jumlah_beli ,tax ,subtotal,total	,oln_note,oln_orderstatus,oln_customer,oln_customerid,oln_customer_email,oln_customer_telp		,oln_customer_address,oln_customer_provinsi,oln_customer_postalcode,oln_customer_kotakab,oln_customer_kecamatan
		,oln_expnote,oln_penerima,oln_address,oln_telp,oln_provinsi,oln_postalcode
		,oln_kotakab,oln_kecamatan,oln_shipmethod,oln_tgl,id_dropshipper,id_product)
		SELECT xls.oln_order_id ,xls.oln_productid ,xls.oln_productname ,xls.oln_size,xls.oln_price,xls.oln_qty,xls.oln_tax ,xls.oln_totalprice,xls.oln_ordertotal
		,xls.oln_note,xls.oln_orderstatus,xls.oln_customer,xls.oln_customerid,xls.oln_customer_email,xls.oln_customer_telp
		,xls.oln_customer_address,xls.oln_customer_provinsi,xls.oln_customer_postalcode,xls.oln_customer_kotakab,xls.oln_customer_kecamatan
		,xls.oln_expnote,xls.oln_penerima,xls.oln_address,xls.oln_telp,xls.oln_provinsi,xls.oln_postalcode
		,xls.oln_kotakab,xls.oln_kecamatan,xls.oln_shipmethod,xls.oln_tgl,xls.product_id,xls.dropshipper_id
		FROM oln_xlscamou xls WHERE (xls.oln_orderstatus<>'Canceled')";
		// var_dump($sql_post);die;
		$hasil_post=mysql_query($sql_post);
		//menghapus data di xlscamou
		$sql_delete="";	
		$sql_delete="TRUNCATE TABLE oln_xlscamou";	
		$hasil_delete=mysql_query($sql_delete);
		$posting=1;
		
		//inisialisasi id product,namabrg dan pelanggan
		$sql_update_product="";
		$sql_update_product=" UPDATE olnpreso olp SET olp.id_product=(SELECT id FROM mst_products p WHERE (p.oln_product_id=olp.oln_productid) AND(p.size=olp.size)  AND p.deleted=0 LIMIT 1) ,olp.namabrg=(SELECT nama FROM mst_products p WHERE (p.oln_product_id=olp.oln_productid) AND(p.size=olp.size)  AND p.deleted=0 LIMIT 1)";
		$update_product=mysql_query($sql_update_product) or die (mysql_error());

		// dropshiper init
		$sql_dropshiper="";
		$sql_dropshiper=" UPDATE olnpreso olp,mst_dropshipper d SET olp.id_dropshipper = d.id WHERE  (olp.oln_customerid = d.oln_customer_id) ";
		$update_dropshipper=mysql_query($sql_dropshiper) or die (mysql_error());
	
		//inisialisasi deposit
		$sql_deposit="";
		$sql_deposit="UPDATE olnpreso olp SET olp.deposit= (olp.subtotal + olp.tax) WHERE (olp.total = 0) AND (olp.state='0') ";
		$update_deposit=mysql_query($sql_deposit) or die (mysql_error());
		

// alihkan halaman ke importcamou.php
header("location:importcamou.php?posting=$posting");
?>