<?php
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("koneksi/koneksi.php");
function getincrementnumber()
	{
		$q = mysql_fetch_array( mysql_query('select id_registrasi from registrasi order by id_registrasi desc limit 0,1'));
		
		$kode=substr($q['id_registrasi'], -4);
		$bulan=substr($q['id_registrasi'], -6,2);
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

	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}

   function getnewnotrxwait()
	{
		
		$temp=getmonthyeardate();
		$temp2=getincrementnumber();
		$id="REG".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
		return $id;
		
	}	


function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_pkb from pkb order by id_pkb desc limit 0,1'));
	
	$kode=substr($q['id_pkb'], -4);
	$bulan=substr($q['id_pkb'], -6,2);
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
	$id="PKB".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
	return $id;
	
}	
$id_registrasi = getnewnotrxwait();
$id_pkb = getnewnotrxwait2();
	
		$cek=$_GET['flag'];

if($cek =='pkb'){
	//Buat update pengiriman barang
	//mysql_query("update registrasi set flag_pkb='1' where id_registrasi='".$_GET['idregis']."'") or die (mysql_error());
	
	mysql_query("insert into pkb(id_pkb,tgl_pkb,id_registrasi,perintah,keluhan,odometer,id_user)values('".$id_pkb."',NOW(),'".$_GET['idregis']."','".$_POST['perintah']."','".$_POST['keluhan']."','".$_POST['odometer']."','".$id_user."')") or die ("error head".mysql_error());
	
	//--------------------------
	
	$row=$_POST['jum'];
	
	
	for ($i=1; $i<$row; $i++)
	{
		//---mengambil parameter---
		$Id_Jasa = $_POST['Id_Jasa'.$i];
		$HargaLabor = $_POST['HargaLabor'.$i];
		$Id_Mekanik = $_POST['Id_Mekanik'.$i];
		
		//---akhir tarik parameter detail---
		
		if($Id_Jasa=='') //jika id jasa kosong, row jasa kosong dilewat
		{
			continue;
		}
		else
		{
			//---simpan detail---
			mysql_query("insert into pkb_detail(id_jasa, id_pkb,harga,id_karyawan) VALUES ('".$Id_Jasa."','".$id_pkb."','".$HargaLabor."','".$Id_Mekanik."')") or die (mysql_error());
			//--- END simpan detail---
		}
	}
	//--- mengarahkanke halaman cetak pkb---
	 header("Location: ../pkb/cetakpkb.php?pkb=$id_pkb");
}
		else{
	mysql_query("insert into registrasi(id_registrasi,no_polisi,tgl,odometer,id_user)values('".$id_registrasi."','".$_POST['no_polisi']."',NOW(),'".$_POST['odometer']."','".$id_user."')") or die (mysql_error()); ?>
  <script language="javascript"> 
 window.close();
 window.opener.location.href='../../Registrasi.html';
</script>  

	<?php		}					?>