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
	$q = mysql_fetch_array( mysql_query('select id_trans from olnsoreturn order by id_trans desc limit 0,1'));
	
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
	$id="ORN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
    	
	//$id_registrasi = getnewnotrxwait();
	$id_pkb = getnewnotrxwait2();
	//--------tarik parameter utk simpan---------------
	//$id_supplier = $_POST['id_supplier'];
	$id_oln			  = $_POST['id_oln'];
	$tgl   			  = $_POST['tanggal']; 
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
	$totalqtyreturn=0; 
	$totalpenalty=0; 
     	
    if($_GET['id_trans']==''){
	//add data return
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_product = $_POST['IDP'.$i];
		$namabrg = mysql_real_escape_string($_POST['NamaBrg'.$i]);
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = $_POST['Qty'.$i];
		$Return = $_POST['Return'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$HargaNett = str_replace(",","", $_POST['NettPrice'.$i]);
		$Size = $_POST['Size'.$i];
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$Pinalty= str_replace(",","", $_POST['Pinalty'.$i]);
		$totalqtyreturn += str_replace(",","", $_POST['Return'.$i]);
		$totalpenalty += $Pinalty * $totalqtyreturn;
		//---akhir tarik parameter detail---
		//diinput hanya yang ada returnnya
		if($Return==''){
		}
		else
		{
		//---simpan detail---
		//var_dump($query);die;
		$query = "INSERT INTO olnsoreturn_detail(id_trans,id_oln,id_product,namabrg,harga_satuan,harga_nett,disc_return,jumlah_beli,jumlah_return,size,subtotal_return) VALUES ('".$id_pkb."','".$id_oln."','".$id_product."','".$namabrg."','".$Harga."','".$HargaNett."','".$Pinalty."','".$Qty."','".$Return."','".$Size."','".$Subtotal."')";
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
	//--- simpan master -----------------------------
	$faktur = str_replace(".","",$_POST['faktur']);
	$tunai = str_replace(".","",$_POST['tunai']);
	$transfer = str_replace(".","",$_POST['transfer']);
	$keterangan = $_POST['txtbrg'];
	$sisa_deposit = 0;
    $sisa_deposit = $simpan_deposit-$byr_deposit;
	$piutang = 0;
	$disc_faktur = str_replace(".","",$_POST['disc_faktur']);
	//total faktur itu faktur - disc_faktur
	$totalfaktur = $faktur-$disc_faktur;
	$sql_master="";
	$sql_master="insert into olnsoreturn(id_trans,id_oln,ref_kode,tgl_trans,id_dropshipper,nama,telp,alamat,id_address,id_expedition,exp_code,exp_fee,exp_note,total,faktur,totalqty,discount,tunai,transfer,discount_faktur,penalty,note,user)values('".$id_pkb."','".$id_oln."','".$ref_kode."','".$tgl."','".$id_dropshipper."','".$nama."','".$telp."','".$alamat."','".$id_address."','".$id_expedition."','".$exp_code."','".$exp_fee."','".$exp_note."','".$totalfaktur."','".$faktur."','".$totalqtyreturn."','".$disc_dp."','".$tunai."','".$transfer."','".$disc_faktur."','".$totalpenalty."','".$keterangan."','".$id_user."')";
	// var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	
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
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
