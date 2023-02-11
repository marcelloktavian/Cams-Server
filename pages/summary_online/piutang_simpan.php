<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
    //------------buat master_id----------------------   
    function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
	function getincrementnumber2()
	{
	$q = mysql_fetch_array( mysql_query('select id_trans from trpiutang order by id_trans desc limit 0,1'));
	
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
	$id="TPO".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
	return $id;
	}	
	$id_pkb = getnewnotrxwait2();
	//--------tarik parameter utk simpan---------------
	$id_dropshipper = $_POST['id_dropshipper'];
	//$id_pkb		 = $_POST['kode_hidden'];
	$tgl   		 = $_POST['tgl'];
	$tunai   	 = $_POST['tunai'];
	$transfer    = $_POST['transfer'];
	$faktur      = $_POST['faktur'];
	$keterangan  = $_POST['keterangan'];
	$info        = $_POST['info'];
	
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$totalfaktur=0;
	$totalqty=0; 
	$totalpiutang=0; 
	$totalbayar=0; 
     	
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		//$Id_Part = $_POST['BARCODE'.$i];
		$Id_Part = $_POST['Id'.$i];
		$Tgl = $_POST['Tgl'.$i];
		$Invoice = str_replace(",","", $_POST['Invoice'.$i]);
		$Piutang = str_replace(",","", $_POST['Piutang'.$i]);
		$Sisa = str_replace(",","", $_POST['Sisa'.$i]);
		$Bayar = str_replace(",","", $_POST['Bayar'.$i]);
		$Bank = str_replace(",","", $_POST['Bank'.$i]);
		$totalfaktur += $Bayar+$Bank ;
		$Piutang_update=$Sisa-($Bayar+$Bank);
		
		$totalqty += 1;
		
		//---akhir tarik parameter detail---
		if($Id_Part==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO trpiutang_detail(id_transjual, id_trans, faktur, piutang,piutang_update, bayar,bank) VALUES ('".$Id_Part."','".$id_pkb."','".$Invoice."','".$Piutang."','".$Piutang_update."','".$Bayar."','".$Bank."')";
		$hasil = mysql_query($query) or die (mysql_error());
	
	//Buat update piutang penjualan----------------------
		$update = "";
		$update = "UPDATE olnso set pelunasan=pelunasan+".$totalfaktur." where id_trans='".$Id_Part."'";
		mysql_query($update) or die ("update piutang error".mysql_error());
		}
	}
	//--- simpan master ---------------------------
	
	$sisapiutang=$totalpiutang-$totalfaktur;
	//$sisa_deposit = $simpan_deposit-$byr_deposit;	
	$sql_master="insert into trpiutang(id_trans,id_transjual,tgl_trans,id_customer,totalfaktur,totalqty,faktur,tunai,transfer,piutang,keterangan,info,id_user) values('".$id_pkb."','".$Id_Part."',NOW(),'".$id_dropshipper."','".$totalfaktur."','".$totalqty."','".$faktur."','".$tunai."','".$transfer."','".$sisapiutang."','".$keterangan."','".$info."','".$id_user."')";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die ("simpan piutang error".mysql_error());
	//---akhir simpan master----------------------
	
	}
	else if($_GET['id_trans']!='')
	{
	//edit data----------
	//-update detail------------------------
	
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
		
		//---tarik parameter detail---
		//$Id_Part = $_POST['BARCODE'.$i];
		$Id_Part = $_POST['Id'.$i];
		$Tgl = $_POST['Tgl'.$i];
		$Invoice = str_replace(",","", $_POST['Invoice'.$i]);
		$Piutang = str_replace(",","", $_POST['Piutang'.$i]);
		$Bayar = str_replace(",","", $_POST['Bayar'.$i]);
		$totalfaktur += str_replace(",","", $_POST['Bayar'.$i]);
		$totalqty += 1;
			
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trpiutang_detail(id_transjual, id_trans,faktur,piutang,bayar) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Faktur."','".$Piutang."','".$Bayar."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";	
				mysql_query($sql_insert) ;
				/*
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				*/
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trpiutang_detail set id_transjual = '".$Id_Part."', faktur= '".$Faktur."', piutang= '".$Piutang."', bayar= '".$Bayar."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trpiutang_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
				mysql_query($sql_delete);
				}
		    }
		}
		
		//update master------------------------------
		mysql_query("update trpiutang set totalfaktur='".$totalfaktur."', totalqty='".$totalqty."',faktur='".$faktur."',tunai='".$tunai."',transfer='".$transfer."', id_customer='".$_POST['id_customer']."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}	
	header("location:piutang_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
