<!-- import excel ke mysql -->
<?php 
// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
// menghubungkan dengan library excel reader
include "../../assets/xlsreader/excel_reader2.php";
?>

<?php
error_reporting(0);

// upload file xls
$target = basename($_FILES['filecamou']['name']) ;
move_uploaded_file($_FILES['filecamou']['tmp_name'], $target);

// beri permisi agar file xls dapat di baca
chmod($_FILES['filecamou']['name'],0777);

// mengambil isi file xls
$data = new Spreadsheet_Excel_Reader($_FILES['filecamou']['name'],false);
// menghitung jumlah baris data yang ada
$jumlah_baris = $data->rowcount($sheet_index=0);

// jumlah default data yang berhasil di import
$berhasil = 0;
for ($i=2; $i<=$jumlah_baris; $i++){

	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
	$oln_order_id     	= $data->val($i, 1);
	$oln_productid    	= $data->val($i, 2);
	$oln_productname  	= $data->val($i, 3);
	$oln_price     	  	= $data->val($i, 4);
	$oln_qty   		  	= $data->val($i, 5);
	$oln_totalprice   	= $data->val($i, 6);
    $oln_tax     	  	= $data->val($i, 7);
	$oln_size   	  	= $data->val($i, 8);
	$oln_note  		  	= $data->val($i, 9);
    $oln_ordertotal   	= $data->val($i, 10);
	$oln_orderstatus  	= $data->val($i, 11);
	$oln_customer  	  	= $data->val($i, 12);
    $oln_customerid   	= $data->val($i, 13);
    $oln_customer_email = $data->val($i, 14);
    $oln_customer_telp  = $data->val($i, 15);
	$oln_expnote   	  	= $data->val($i, 16);
	$oln_penerima  	  	= $data->val($i, 17);
    $oln_address      	= $data->val($i, 18);
	$oln_telp   	  	= $data->val($i, 19);
	$oln_shipmethod	  	= $data->val($i, 20);
	$oln_provinsi  	  	= $data->val($i, 21);
    $oln_postalcode	  	= $data->val($i, 22);
	$oln_kotakab   	  	= $data->val($i, 23);
	$oln_kecamatan    	= $data->val($i, 24);
    $oln_customer_address   = $data->val($i, 25);
	$oln_customer_provinsi  = $data->val($i, 26);
	$oln_customer_postalcode= $data->val($i, 27);
	$oln_customer_kotakab   = $data->val($i, 28);
	$oln_customer_kecamatan = $data->val($i, 29);
	$oln_tgl  		  	= $data->val($i, 30);
    
	
	$sql_insert="";
	//if($nama != "" && $alamat != "" && $telepon != ""){
	if($oln_order_id != ""){
		// input data ke database (table oln_xlscamou_cr)
		
		$sql_insert="INSERT into oln_xlscamou_cr values(
		''
		,'$oln_order_id'  
		,'$oln_productid' 
		,'$oln_productname' 
		,'$oln_price'     	
		,'$oln_qty'   		 
		,'$oln_totalprice'  
		,'$oln_tax'     	  
		,'$oln_size'
		,'$oln_note'
		,'$oln_ordertotal'
		,'$oln_orderstatus'
		,'$oln_customer'
		,'$oln_customerid'
		,'$oln_customer_email'
		,'$oln_customer_telp'
		,'$oln_expnote'
		,'$oln_penerima'
		,'$oln_address'
		,'$oln_telp'
		,'$oln_shipmethod'
		,'$oln_provinsi'
		,'$oln_postalcode'
		,'$oln_kotakab'
		,'$oln_kecamatan'
		,'$oln_customer_address' 
	    ,'$oln_customer_provinsi'
		,'$oln_customer_postalcode'
		,'$oln_customer_kotakab'
		,'$oln_customer_kecamatan'
		,'$oln_tgl'
		,''
		,''
    	,'0')";
		
		//var_dump($sql_insert);die;
		$hasil=mysql_query($sql_insert);		
		$berhasil++;
	}
}
		//inisialisasi product dan pelanggan
		$sql_update_product="";
		$sql_update_product="UPDATE oln_xlscamou_cr xls SET xls.product_id=(SELECT id FROM mst_products p WHERE (p.oln_product_id=xls.oln_productid) AND(p.size=xls.oln_size)),xls.dropshipper_id = (SELECT id FROM mst_dropshipper d WHERE (d.oln_customer_id=xls.oln_customerid))";
		$update_product=mysql_query($sql_update_product);
		

// hapus kembali file .xls yang di upload tadi
unlink($_FILES['filecamou']['name']);

// alihkan halaman ke index.php
header("location:importcamoucredit.php?berhasil=$berhasil");
?>