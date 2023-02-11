<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
	
	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
   
	function getincrementnumber2()
	{
	$q = mysql_fetch_array( mysql_query('select id_trans from olnso order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -5);
	$bulan=substr($q['id_trans'], -7,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
	//echo"Kode=".$kode."Num=".$num."bulan=".$bulan;
	
	if($num==0 || $num==null || $bulan!=$bln_skrng)		
	{
		$temp = 1;
	}
	else
	{
		$temp=$num+1;
	}
	return $temp;
}

	function getmonthyeardate2()
	{
	$today = date('ym');
	return $today;
	}

	function getnewnotrxwait2()
	{
	
	$temp=getmonthyeardate2();
	$temp2=getincrementnumber2();
	$id="OLN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
    //validasi php untuk mencari kode web yang sama
	$rows=0;
	//mengecek apakah kode_webnya kosong atau tidak
	if(isset($_POST['ref_code']) and !empty($_POST['ref_code'])){
		$sql_validasi="SELECT * FROM olnso WHERE (ref_kode IS NOT NULL OR ref_kode <> '0' OR ref_kode <> '' ) and ref_kode ='".$_POST['ref_code']."' AND deleted=0 ORDER BY id DESC";
		//var_dump($sql_validasi);die;
		$rows = mysql_num_rows($sql_validasi);
		//var_dump($sql_validasi);	
		$sql = mysql_query($sql_validasi);
		//menghitung jumlah baris yang double
		$rows = mysql_num_rows($sql);
		//echo"jumlah baris=".$rows;die;	
		$rs = mysql_fetch_array($sql);
		$id_trans=$rs['id_trans'];
	}
	else if(isset($_POST['exp_code']) and !empty($_POST['exp_code'])){
		$sql_validasi="SELECT * FROM olnso WHERE (exp_code IS NOT NULL OR exp_code <> '0' OR exp_code <> '' ) and exp_code ='".$_POST['exp_code']."' AND deleted=0 ORDER BY id DESC";
		//var_dump($sql_validasi);die;
		$rows = mysql_num_rows($sql_validasi);
		//var_dump($sql_validasi);	
		$sql = mysql_query($sql_validasi);
		//menghitung jumlah baris yang double
		$rows = mysql_num_rows($sql);
		//echo"jumlah baris=".$rows;die;	
		$rs = mysql_fetch_array($sql);
		$id_trans=$rs['id_trans'];
	}
//kalo ada ref_kode double langsung di stop dan di redirect ke page berikutnya
if ($rows>0) { header("location:error_double.php?id_trans=".$id_trans."");}
else
//kalo tidak ada double lanjutkan save data
{	
	//$id_registrasi = getnewnotrxwait();
	$id_pkb = getnewnotrxwait2();
	//--------tarik parameter utk simpan---------------
	//$id_supplier = $_POST['id_supplier'];
	//$id_pkb			  = $_POST['kode_hidden'];
	//$tgl   			  = $_POST['tanggal']; diganti krn diinputnya jadi now()
	$ref_kode		  = $_POST['ref_code'];
	$nama             = addslashes($_POST['nama']);
	$telp             = $_POST['telp'];
	$alamat           = addslashes($_POST['alamat']);
	$id_dropshipper   = $_POST['id_dropshipper'];
	$disc_dp   		  = $_POST['disc_dropshipper'];
	$id_address   	  = $_POST['id_address'];
	$id_expedition    = $_POST['id_expedition'];
	$exp_code         = $_POST['exp_code'];
	$exp_fee          = $_POST['exp_fee'];
	$exp_note         = $_POST['exp_note'];
	
	//$kode   = max_id(); diganti dengan no.invoice yang diinput manual
	//$kode   = $_POST['invoice'];
	$row=$_POST['jum'];
	 
	//var_dump('tanggal= '.$tgl);die;
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$tunai=0;
	$transfer=0;
	$kartu=0;
	$subtotal=0;
	$simpan_deposit=0;
	$byr_deposit=0;
	$totalfaktur=0;
	$totalqty=0; 
    //--- parameter simpan master -----------------------------
	$totalqty = str_replace(".","",$_POST['totalqty']);
	$faktur = str_replace(".","",$_POST['faktur']);
	$tunai = str_replace(".","",$_POST['tunai']);
	$transfer = str_replace(".","",$_POST['transfer']);
	$keterangan = $_POST['txtbrg'];
	$simpan_deposit = $_POST['simpan_deposit'];
	$byr_deposit = $_POST['byr_deposit'];
	$sisa_deposit = 0;
    $sisa_deposit = $simpan_deposit-$byr_deposit;
	$piutang = 0;
	$piutang = str_replace(".","",$_POST['piutang']);
	$disc_faktur = str_replace(".","",$_POST['disc_faktur']);
	//total faktur itu faktur + ongkir - disc_faktur
	$totalfaktur = $faktur+$exp_fee-$disc_faktur;
	//------simpan master---------------------
    $sql_master="";
	$sql_master="insert into olnso(id_trans,ref_kode,tgl_trans,id_dropshipper,nama,telp,alamat,id_address,id_expedition,exp_code,exp_fee,exp_note,total,faktur,totalqty,discount,tunai,transfer,deposit,simpan_deposit,piutang,discount_faktur,note,user)values('".$id_pkb."','".$ref_kode."',NOW(),'".$id_dropshipper."','".$nama."','".$telp."','".$alamat."','".$id_address."','".$id_expedition."','".$exp_code."','".$exp_fee."','".$exp_note."','".$totalfaktur."','".$faktur."','".$totalqty."','".$disc_dp."','".$tunai."','".$transfer."','".$byr_deposit."','".$simpan_deposit."','".$piutang."','".$disc_faktur."','".$keterangan."','".$id_user."')";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	$id_oln_auto = mysql_insert_id();
	
	//---akhir simpan master----------------------
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_product = $_POST['IDP'.$i];
		$namabrg = addslashes($_POST['NamaBrg'.$i]);
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$Size = $_POST['Size'.$i];
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		//---akhir tarik parameter detail---
		if($id_product==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO olnsodetail( id_trans,id_product,namabrg,harga_satuan,disc,jumlah_beli,size,subtotal,id_oln_auto) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Harga."','".$Disc."','".$Qty."','".$Size."','".$Subtotal."','".$id_oln_auto."')";
		//var_dump($query);die;
		$hasil = mysql_query($query) or die (mysql_error());
	   
		//Buat update stok barang----------------------
		/*
		$stok = "";
		$stok = "INSERT INTO stok_gudang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
		mysql_query($stok) or die ("update stok1 error".mysql_error());
		*/
		}
	}
	/*
	//--- parameter simpan master -----------------------------
	$faktur = str_replace(".","",$_POST['faktur']);
	$tunai = str_replace(".","",$_POST['tunai']);
	$transfer = str_replace(".","",$_POST['transfer']);
	$keterangan = $_POST['txtbrg'];
	$simpan_deposit = $_POST['simpan_deposit'];
	$byr_deposit = $_POST['byr_deposit'];
	$sisa_deposit = 0;
    $sisa_deposit = $simpan_deposit-$byr_deposit;
	$piutang = 0;
	$piutang = str_replace(".","",$_POST['piutang']);
	$disc_faktur = str_replace(".","",$_POST['disc_faktur']);
	//total faktur itu faktur + ongkir - disc_faktur
	$totalfaktur = $faktur+$exp_fee-$disc_faktur;
	*/
	/*
	//Piutang > 0 = penjualan belum lunas,pembyrn lebih kecil dari totalfaktur
	if(($tunai+$transfer+$kartu+$byr_deposit) < $totalfaktur) {
	$piutang = $totalfaktur - ($tunai+$transfer+$kartu+$byr_deposit) ;
	}
	//Piutang < 0 (negatif) = bayarnya kelebihan alias ada deposit
	else {
	$piutang = 0;
	}
	//Field simpan_deposit = dipakai untuk menyimpan transaksi yang pembayarannya berlebih,sehingga ada deposit
	//Field deposit dipakai untuk menyimpan pembayaran pakai deposit (byr_deposit) yang sudah tersimpan dari deposit pelanggan
	*/
	/*
	$sql_master="";
	$sql_master="insert into olnso(id_trans,ref_kode,tgl_trans,id_dropshipper,nama,telp,alamat,id_address,id_expedition,exp_code,exp_fee,exp_note,total,faktur,totalqty,discount,tunai,transfer,deposit,simpan_deposit,piutang,discount_faktur,note,user)values('".$id_pkb."','".$ref_kode."',NOW(),'".$id_dropshipper."','".$nama."','".$telp."','".$alamat."','".$id_address."','".$id_expedition."','".$exp_code."','".$exp_fee."','".$exp_note."','".$totalfaktur."','".$faktur."','".$totalqty."','".$disc_dp."','".$tunai."','".$transfer."','".$byr_deposit."','".$simpan_deposit."','".$piutang."','".$disc_faktur."','".$keterangan."','".$id_user."')";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	*/
	//---simpan deposit pelanggan--------------------------------
	$sql_deposit="";
	if ($byr_deposit > 0)
	{
	$sql_deposit="insert into olndeposit(id_trans,kode,tgl_trans,id_dropshipper,totalfaktur,tunai,deposit,catatan) values('".$id_pkb."','".$kode."',NOW(),'".$id_dropshipper."','-".$byr_deposit."','-".$byr_deposit."','-".$byr_deposit."','JUAL BYR DEPOSIT')";
	mysql_query($sql_deposit)or die (mysql_error());
	}
	else if ($simpan_deposit > 0)
	{
	$sql_deposit="insert into olndeposit(id_trans,kode,tgl_trans,id_dropshipper,totalfaktur,tunai,deposit,catatan) values('".$id_pkb."','".$kode."',NOW(),'".$id_dropshipper."','".$simpan_deposit."','".$simpan_deposit."','".$simpan_deposit."','JUAL SIMPAN DEPOSIT')";
	mysql_query($sql_deposit)or die (mysql_error());
    }

	}
		
	/*
	echo"sql_insert= ".$sql_insert;
	echo"<br/>sql_update= ".$sql_update;
	echo"<br/>sql_delete= ".$sql_delete;
	echo"<br/>delete= ".$delete;
	echo"<br/>id_detail= ".$detail;
	echo"<br/>id_detail= ".$detail;
	*/
	//DIMATIKAN AGAR LANGSUNG HILANG SAVENYA
	//header("location:trolnso_nota.php?id_trans=".$id_pkb."");

}	

	//update harga
	$data = mysql_query("SELECT dt.id_trans,DATE_FORMAT(m.lastmodified,'%d/%m/%Y') AS tglposted, m.ref_kode AS id_web,m.exp_fee AS ongkir,d.nama AS dropshipper,SUM(dt.jumlah_beli) AS qty_detail,m.totalqty,SUM(ceil(dt.subtotal * (1-m.discount))) AS total_detail,m.total,m.faktur ,m.nama AS pembeli,e.nama AS expedition,m.state,m.discount AS discdp,m.discount_faktur AS disc_faktur, m.deposit, m.transfer FROM olnsodetail dt  INNER JOIN olnso m ON dt.id_trans = m.id_trans  LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id LEFT JOIN mst_expedition e ON m.id_expedition = e.id WHERE ((m.deleted=0) AND (m.state='0') AND DATE(m.lastmodified) BETWEEN STR_TO_DATE(DATE_FORMAT(NOW(),'%d/%m/%Y'),'%d/%m/%Y') AND STR_TO_DATE(DATE_FORMAT(NOW(),'%d/%m/%Y'),'%d/%m/%Y')) GROUP BY dt.id_trans HAVING (SUM(CEIL(dt.subtotal * (1-m.discount))) <> m.faktur) OR (SUM(dt.jumlah_beli) <> m.totalqty) ORDER BY m.id_trans ASC");
	while($d = mysql_fetch_array($data)){
		$status='';
		if($d['deposit']==0 && $d['transfer']==0){
			$status = 'Credit';
		}else{
			$status = 'Cash';
		}
				if($status == 'cash'){
					if($d['deposit']!=0){
						$sqldetail="UPDATE olnso AS so JOIN (SELECT SUM(CEIL(dt.subtotal * (1-m.discount))) AS total FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans = m.id_trans WHERE ((m.deleted=0) AND (m.state='0') AND m.id_trans='".$d['id_trans']."') GROUP BY dt.id_trans) AS tot SET so.total=CEIL(tot.total), so.faktur=CEIL(tot.total), so.deposit=CEIL(tot.total) WHERE so.id_trans='".$d['id_trans']."';";
					}else if($d['transfer']!=0){
						$sqldetail="UPDATE olnso AS so JOIN (SELECT SUM(CEIL(dt.subtotal * (1-m.discount))) AS total FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans = m.id_trans WHERE ((m.deleted=0) AND (m.state='0') AND m.id_trans='".$d['id_trans']."') GROUP BY dt.id_trans) AS tot SET so.total=CEIL(tot.total), so.faktur=CEIL(tot.total), so.transfer=CEIL(tot.total) WHERE so.id_trans='".$d['id_trans']."';" ; 
					}
				}else{
					$sqldetail = "UPDATE olnso AS so JOIN (SELECT SUM(CEIL(dt.subtotal * (1-m.discount))) AS total FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans = m.id_trans WHERE ((m.deleted=0) AND (m.state='0') AND m.id_trans='".$d['id_trans']."') GROUP BY dt.id_trans) AS tot SET so.total=CEIL(tot.total), so.faktur=CEIL(tot.total), so.piutang=CEIL(tot.total) WHERE so.id_trans='".$d['id_trans']."';"; 
				}
			
		$update=mysql_query($sqldetail) or die (mysql_error());
		$sqlperbaikan = "INSERT INTO `tbl_log`(`id_trans`, `tanggal`, `id_web`, `dropshipper`, `qty_detail`, `qty_master`, `total_detail`, `total_master`, `faktur`, `status`, `lastmodified`) VALUES ('".$d['id_trans']."','".$d['tglposted']."','".$d['id_web']."','".$d['dropshipper']."','".$d['qty_detail']."','".$d['totalqty']."','".$d['total_detail']."','".$d['total']."','".$d['faktur']."','".$status."',NOW())";
		$perbaikan=mysql_query($sqlperbaikan) or die (mysql_error());
	}
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
