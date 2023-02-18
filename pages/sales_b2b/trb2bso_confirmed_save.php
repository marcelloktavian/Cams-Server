<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];

include("../../include/koneksi.php");
    function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
 
	function getincrementnumber2()
	{
	$q = mysql_fetch_array( mysql_query('select id_trans from b2bdo order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -4);
	$bulan=substr($q['id_trans'], -6,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
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
	$id="BDO".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
	return $id;
	}
	
	//--------tarik parameter utk simpan---------------
	$id_customer  = $_POST['id_customer'];
	$id_transb2bso= $_POST['id_trans'];
	$tglkirim     = $_POST['tglkirim'];
	$tgljatuhtempo     = $_POST['tgljatuhtempo'];
	$id_pkb       = getnewnotrxwait2(); 
	$id_salesman  = $_POST['id_salesman'];
	$id_address   = $_POST['id_address'];
	$id_expedition = $_POST['id_expedition'];
	$exp_fee 	= $_POST['exp_fee'];
	$exp_code 	= $_POST['exp_code'];
	$exp_note 	= $_POST['exp_note'];
	$note 		= $_POST['txtbrg'];
	$ref_kode 		= $_POST['ref_kode'];
	$row=$_POST['jum'];
	 
	//var_dump('tanggal= '.$tgl);die;
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$faktur=$_POST['faktur'];
	$totalfaktur=$_POST['totalfaktur'];;
	$totalkirim=$_POST['totalkirim'];; 
    //var_dump("id_trans=".
    //id_trans tidak kosong karena id_transnya sudah ada yaitu id_pemesanan 	
    if($_GET['id_trans']!=''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_detail  = $_POST['Id'.$i];
		$id_product = $_POST['BARCODE'.$i];
		$NamaBrg 	= $_POST['NamaBrg'.$i];
		$id31 		= $_POST['S31id'.$i];
		$k31 		= $_POST['K31_'.$i];
		$id32 		= $_POST['S32id'.$i];
		$k32 		= $_POST['K32_'.$i];
		$id33 		= $_POST['S33id'.$i];
		$k33 		= $_POST['K33_'.$i];
		$id34 		= $_POST['S34id'.$i];
		$k34 		= $_POST['K34_'.$i];
		$id35 		= $_POST['S35id'.$i];
		$k35 		= $_POST['K35_'.$i];
		$id36 		= $_POST['S36id'.$i];
		$k36 		= $_POST['K36_'.$i];
		$id37 		= $_POST['S37id'.$i];
		$k37 		= $_POST['K37_'.$i];
		$id38 		= $_POST['S38id'.$i];
		$k38 		= $_POST['K38_'.$i];
		$id39 		= $_POST['S39id'.$i];
		$k39 		= $_POST['K39_'.$i];
		$id40 		= $_POST['S40id'.$i];
		$k40 		= $_POST['K40_'.$i];
		$id41 		= $_POST['S41id'.$i];
		$k41 		= $_POST['K41_'.$i];
		$id42 		= $_POST['S42id'.$i];
		$k42 		= $_POST['K42_'.$i];
		$id43 		= $_POST['S43id'.$i];
		$k43 		= $_POST['K43_'.$i];
		$id44 		= $_POST['S44id'.$i];
		$k44 		= $_POST['K44_'.$i];
		$id45 		= $_POST['S45id'.$i];
		$k45 		= $_POST['K45_'.$i];
		$id46 		= $_POST['S46id'.$i];
		$k46 		= $_POST['K46_'.$i];
		$Size 		= $_POST['Size'.$i];
		$Qty 		= $_POST['Qty'.$i];
		$QtyKirim 	= $_POST['Kirim'.$i];
		$Sisa 	    = $_POST['Sisa'.$i];
		$Disc 	    = $_POST['Disc'.$i];
		$Harga 		= str_replace(",","", $_POST['Harga'.$i]);
		$Subtotal 	= $Harga * $QtyKirim;
		//$faktur 	+= str_replace(",","", $Subtotal);
		//$totalqty 	+= str_replace(",","", $_POST['QtyKirim'.$i]);
		//piutang diisi dengan nilai grandfaktur
		//$piutang += str_replace(",","", $grandfaktur);
		//---akhir tarik parameter detail---
		if(($QtyKirim=='') or ($QtyKirim==0))
		{
		//by pass qty_kirim yang 0 agar tidak usah diinsert ke b2bdo_detail
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO b2bdo_detail( id_trans,id_b2bso_det,id_product,namabrg,id31,qty31,id32,qty32,id33,qty33,id34,qty34,id35,qty35,id36,qty36,id37,qty37,id38,qty38,id39,qty39,id40,qty40,id41,qty41,id42,qty42,id43,qty43,id44,qty44,id45,qty45,id46,qty46,harga_satuan,jumlah_beli,jumlah_kirim,sisa,disc) VALUES ('".$id_pkb."','".$id_detail."','".$id_product."','".$NamaBrg."','".$id31."','".$k31."','".$id32."','".$k32."','".$id33."','".$k33."','".$id34."','".$k34."','".$id35."','".$k35."','".$id36."','".$k36."','".$id37."','".$k37."','".$id38."','".$k38."','".$id39."','".$k39."','".$id40."','".$k40."','".$id41."','".$k41."','".$id42."','".$k42."','".$id43."','".$k43."','".$id44."','".$k44."','".$id45."','".$k45."','".$id46."','".$k46."','".$Harga."','".$Qty."','".$QtyKirim."','".$Sisa."','".$Disc."')";
		// var_dump($query);die;
		$hasil = mysql_query($query) or die ("insert b2bdo_detail error".mysql_error());
	   
		//Buat update jumlah_kirim di b2bso_detail 
		if ($id31 >0)
		{
		$update_31 = "";
		$update_31 = "update b2bso_detail set kirim31=kirim31+'".$k31."' where b2bso_id='".$id_detail."' AND id31='".$id31."'";
		mysql_query($update_31) or die ("update kirim31 di b2bso_detail ERROR".mysql_error());
		}
		if ($id32 >0)
		{
		$update_32 = "";
		$update_32 = "update b2bso_detail set kirim32=kirim32+'".$k32."' where b2bso_id='".$id_detail."' AND id32='".$id32."'";
		mysql_query($update_32) or die ("update kirim32 di b2bso_detail ERROR".mysql_error());
		}
		if ($id33 >0)
		{
		$update_33 = "";
		$update_33 = "update b2bso_detail set kirim33=kirim33+'".$k33."' where b2bso_id='".$id_detail."' AND id33='".$id33."'";
		mysql_query($update_33) or die ("update kirim33 di b2bso_detail ERROR".mysql_error());
		}
		if ($id34 >0)
		{
		$update_34 = "";
		$update_34 = "update b2bso_detail set kirim34=kirim34+'".$k34."' where b2bso_id='".$id_detail."' AND id34='".$id34."'";
		mysql_query($update_34) or die ("update kirim34 di b2bso_detail ERROR".mysql_error());
		}
		if ($id35 >0)
		{
		$update_35 = "";
		$update_35 = "update b2bso_detail set kirim35=kirim35+'".$k35."' where b2bso_id='".$id_detail."' AND id35='".$id35."'";
		// var_dump($update_35);
		mysql_query($update_35) or die ("update kirim35 di b2bso_detail ERROR".mysql_error());
		}
		if ($id36 >0)
		{
		$update_36 = "";
		$update_36 = "update b2bso_detail set kirim36=kirim36+'".$k36."' where b2bso_id='".$id_detail."' AND id36='".$id36."'";
		mysql_query($update_36) or die ("update kirim36 di b2bso_detail ERROR".mysql_error());
		}
		if ($id37 >0)
		{
		$update_37 = "";
		$update_37 = "update b2bso_detail set kirim37=kirim37+'".$k37."' where b2bso_id='".$id_detail."' AND id37='".$id37."'";
		mysql_query($update_37) or die ("update kirim37 di b2bso_detail ERROR".mysql_error());
		}
		if ($id38 >0)
		{
		$update_38 = "";
		$update_38 = "update b2bso_detail set kirim38=kirim38+'".$k38."' where b2bso_id='".$id_detail."' AND id38='".$id38."'";
		mysql_query($update_38) or die ("update kirim38 di b2bso_detail ERROR".mysql_error());
		}
		if ($id39 >0)
		{
		$update_39 = "";
		$update_39 = "update b2bso_detail set kirim39=kirim39+'".$k39."' where b2bso_id='".$id_detail."' AND id39='".$id39."'";
		mysql_query($update_39) or die ("update kirim39 di b2bso_detail ERROR".mysql_error());
		}
		if ($id40 >0)
		{
		$update_40 = "";
		$update_40 = "update b2bso_detail set kirim40=kirim40+'".$k40."' where b2bso_id='".$id_detail."' AND id40='".$id40."'";
		mysql_query($update_40) or die ("update kirim40 di b2bso_detail ERROR".mysql_error());
		}
		if ($id41 >0)
		{
		$update_41 = "";
		$update_41 = "update b2bso_detail set kirim41=kirim41+'".$k41."' where b2bso_id='".$id_detail."' AND id41='".$id41."'";
		mysql_query($update_41) or die ("update kirim41 di b2bso_detail ERROR".mysql_error());
		}
		if ($id42 >0)
		{
		$update_42 = "";
		$update_42 = "update b2bso_detail set kirim42=kirim42+'".$k42."' where b2bso_id='".$id_detail."' AND id42='".$id42."'";
		mysql_query($update_42) or die ("update kirim42 di b2bso_detail ERROR".mysql_error());
		}
		if ($id43 >0)
		{
		$update_43 = "";
		$update_43 = "update b2bso_detail set kirim43=kirim43+'".$k43."' where b2bso_id='".$id_detail."' AND id43='".$id43."'";
		mysql_query($update_43) or die ("update kirim43 di b2bso_detail ERROR".mysql_error());
		}
		if ($id44 >0)
		{
		$update_44 = "";
		$update_44 = "update b2bso_detail set kirim44=kirim44+'".$k44."' where b2bso_id='".$id_detail."' AND id44='".$id44."'";

		mysql_query($update_44) or die ("update kirim44 di b2bso_detail ERROR".mysql_error());
		}
		if ($id45 >0)
		{
		$update_45 = "";
		$update_45 = "update b2bso_detail set kirim45=kirim45+'".$k45."' where b2bso_id='".$id_detail."' AND id45='".$id45."'";
		mysql_query($update_45) or die ("update kirim45 di b2bso_detail ERROR".mysql_error());
		}
		if ($id46 >0)
		{
		$update_46 = "";
		$update_46 = "update b2bso_detail set kirim46=kirim46+'".$k46."' where b2bso_id='".$id_detail."' AND id46='".$id46."'";
		mysql_query($update_46) or die ("update kirim46 di b2bso_detail ERROR".mysql_error());
		}
		$update_pengiriman = "";
		$update_pengiriman = "update b2bso_detail set jumlah_kirim=jumlah_kirim+'".$QtyKirim."' where b2bso_id='".$id_detail."'";
		
		mysql_query($update_pengiriman) or die ("update jumlah_kirim di b2bso_detail ERROR".mysql_error());
		
		}
	}

	//Update master b2b_so totalkirim ditambah total qty_kirim
	$sql_update="";
	$sql_update = "UPDATE b2bso set totalkirim=totalkirim+".$totalkirim." where id_trans='".$id_transb2bso."'";
	//var_dump($sql_update);die;
	mysql_query($sql_update) or die ("update totalkirim error".mysql_error());

	$no_faktur = '';
	// -- Generate nomor faktur
	$sqBDO = mysql_query("SELECT id FROM b2bdo");
	$num_rows = mysql_num_rows($sqBDO);
	if ($num_rows == 0) {
		$sqlno1 = "SELECT CONCAT('AK',SUBSTRING(YEAR(NOW()),3,2),IF(MONTH(NOW()) < 10,CONCAT('0',MONTH(NOW())),MONTH(NOW())),'0001') AS no_faktur";
		$sqno1 = mysql_query($sqlno1);
		while($rsno1=mysql_fetch_array($sqno1)){
			$no_faktur = $rsno1['no_faktur'];
		}
	}else{
		$sqlno1 = "SELECT IF(SUBSTRING(MAX(no_faktur),3,2) = SUBSTRING(YEAR(NOW()),3,2) AND SUBSTRING(MAX(no_faktur),5,2) = (IF(MONTH(NOW()) < 10,CONCAT('0',MONTH(NOW())),MONTH(NOW()))), CONCAT('AK',SUBSTRING(YEAR(NOW()),3,2),IF(MONTH(NOW()) < 10,CONCAT('0',MONTH(NOW())),MONTH(NOW())),IF(LENGTH(SUBSTRING(MAX(no_faktur),7,4)+1)=1,CONCAT('000',SUBSTRING(MAX(no_faktur),7,4)+1),IF(LENGTH(SUBSTRING(MAX(no_faktur),7,4)+1)=2,CONCAT('00',SUBSTRING(MAX(no_faktur),7,4)+1),IF(LENGTH(SUBSTRING(MAX(no_faktur),7,4)+1)=3,CONCAT('0',SUBSTRING(MAX(no_faktur),7,4)+1),CONCAT(SUBSTRING(MAX(no_faktur),7,4)+1)) ) )),CONCAT('AK',SUBSTRING(YEAR(NOW()),3,2),IF(MONTH(NOW()) < 10,CONCAT('0',MONTH(NOW())),MONTH(NOW())),'0001')) AS no_faktur FROM b2bdo LIMIT 1";
		$sqno1 = mysql_query($sqlno1);
		while($rsno1=mysql_fetch_array($sqno1)){
			$no_faktur = $rsno1['no_faktur'];
		}
	}

	// if ($_POST['nofaktur']!='') {
	// 	$no_faktur = $_POST['nofaktur'];
	// }

	//--- simpan master -----------------------------
	$sql_master="";
	$sql_master="insert into b2bdo(id_trans,id_transb2bso,no_faktur,tgl_trans,tgljatuhtempo,id_salesman,id_customer,totalfaktur,faktur,piutang,totalkirim,id_address,id_expedition,exp_code,exp_note,exp_fee,note,user)values('".$id_pkb."','".$id_transb2bso."','".$no_faktur."','".$tglkirim."','".$tgljatuhtempo."','".$id_salesman."','".$id_customer."','".$totalfaktur."','".$faktur."','".$totalfaktur."','".$totalkirim."','".$id_address."','".$id_expedition."','".$exp_code."','".$exp_note."','".$exp_fee."','".$note."','".$user."')";
	//var_dump($id_expedition);die;
	mysql_query($sql_master) or die ("insert b2bdo error".mysql_error());
	//---akhir simpan master----------------------
	
	$id_user=$_SESSION['user']['username'];
	
	$total='';
	$customer='';
	$namacustomer='';
	$tgl = '';
	$q = mysql_fetch_array( mysql_query("SELECT b2bdo.id_trans,b2bdo.totalfaktur,b2bdo.id_customer,mst_b2bcustomer.nama,b2bdo.tgl_trans FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.id_trans='".$id_pkb."' LIMIT 1"));
	$idtrans=$q['id_trans'];
	$total=$q['totalfaktur'];
	$customer=$q['id_customer'];
	$namacustomer=$q['nama'];
	$tgl=$q['tgl_trans'];

	//insert
	$masterNo = '';
	$q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
	FROM jurnal ORDER BY id DESC LIMIT 1"));
	$masterNo=$q['nomor'];

	// execute for master
	$sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`) VALUES ('$masterNo','$tgl','Penjualan B2B - $namacustomer - $no_faktur - $ref_kode','$total','$total','0','$id_user',NOW()) ";
	mysql_query($sql_master) or die (mysql_error());

	//get master id terakhir
	$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
	$idparent=$q['id'];
	
	$dpp = round($total / 1.11);
	$ppn = round(round($total / 1.11) * 0.11);

	$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('01.05.',IF(LENGTH('$customer')=1,'0000',IF(LENGTH('$customer')=2,'000',IF(LENGTH('$customer')=3,'00',IF(LENGTH('$customer')=4,'0','')))), '$customer')");
	while($akun1 = mysql_fetch_array($query1)){
		// piutang b2b 
		$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
		mysql_query($sqlakun1) or die (mysql_error());
	}

	$query2=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.03.',IF(LENGTH('$customer')=1,'0000',IF(LENGTH('$customer')=2,'000',IF(LENGTH('$customer')=3,'00',IF(LENGTH('$customer')=4,'0','')))), '$customer')");
	while($akun2 = mysql_fetch_array($query2)){
		// penjualan b2b
		$sqlakun2="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun2['id']."','".$akun2['noakun']."','".$akun2['nama']."','".$akun2['status']."','0','$dpp','','0', '$id_user',NOW()) ";
		mysql_query($sqlakun2) or die (mysql_error());
	}

	$query3=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.01.00000'");
	while($akun3 = mysql_fetch_array($query3)){
		// ppn
		$sqlakun3="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun3['id']."','".$akun3['noakun']."','".$akun3['nama']."','".$akun3['status']."','0','$ppn','','0', '$id_user',NOW()) ";
		mysql_query($sqlakun3) or die (mysql_error());
	}

	}
	
	//header("location:beli_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
