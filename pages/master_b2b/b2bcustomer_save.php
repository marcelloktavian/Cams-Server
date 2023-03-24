<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");    
    /*
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
		{ $temp = 1;}
		else{ $temp=$num+1;}
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
	$id="MBB".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
	*/
	//$id_registrasi = getnewnotrxwait();
	//$id_pkb = getnewnotrxwait2();
	//--------tarik parameter utk simpan---------------
	//$id_supplier = $_POST['id_supplier'];
	//$id_pkb			  = $_POST['kode_hidden'];
	//$tgl   			  = $_POST['tanggal']; diganti krn diinputnya jadi now()
	$nama             = addslashes($_POST['nama']);
	$npwp             = $_POST['npwp'];
	$ktp           	  = $_POST['nik'];
	$telp             = $_POST['telp'];
	$alamat           = addslashes($_POST['alamat']);
	$hp		          = $_POST['handphone'];
	$contact          = $_POST['contact'];
	$id_address   	  = $_POST['id_address'];
	$acc_info         = $_POST['acc_info'];
	$due         	  = $_POST['duedate'];
	$note         	  = $_POST['txtbrg'];
	$totalqty         = $_POST['totalqty'];
	
	//$row=$_POST['jum'];
	$row=$_GET['row'];
	 
	//--------akhir tarik parameter utk simpan------------
	
     	
    if($_GET['id_trans']==''){
	//--- simpan master -----------------------------
	$sql_master="";
	$sql_master="insert into mst_b2bcustomer(nama,npwp,ktp,alamat,no_telp,hp,id_address,due,acc_info,contact,note,totalqty,user,lastmodified)values('".$nama."','".$npwp."','".$ktp."','".$alamat."','".$telp."','".$hp."','".$id_address."','".$due."','".$acc_info."','".$contact."','".$note."','".$totalqty."','".$id_user."',NOW())";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());

	$id_pkb			  = mysql_insert_id();

	$last_id = mysql_insert_id();
	$akun = '';
	$namaakun = '';
	$idakun = '';

	// Akun piutang B2B
	$query_mysql = mysql_query("SELECT id, CONCAT(SUBSTR(noakun,1,6), IF(LENGTH('$last_id')=1,'0000',IF(LENGTH('$last_id')=2,'000',IF(LENGTH('$last_id')=3,'00',IF(LENGTH('$last_id')=4,'0','')))), '$last_id') AS akun, noakun, nama
	FROM mst_coa WHERE noakun = '01.05.00000'")or die(mysql_error());
	while($data = mysql_fetch_array($query_mysql)){
		$akun = $data['akun'];
		$namaakun = $data['nama'].' - '.$nama;
		$idakun = $data['id'];
		$user = $_SESSION['user']['username'];
		
		$sqlinsert="INSERT INTO det_coa VALUES(NULL, '$idakun', '$akun', '$namaakun', '$user', NOW())";
		mysql_query($sqlinsert) or die (mysql_error());
	}

	// Saldo titipan B2B
	$query_mysql = mysql_query("SELECT id, CONCAT(SUBSTR(noakun,1,6), IF(LENGTH('$last_id')=1,'0000',IF(LENGTH('$last_id')=2,'000',IF(LENGTH('$last_id')=3,'00',IF(LENGTH('$last_id')=4,'0','')))), '$last_id') AS akun, noakun, nama
	FROM mst_coa WHERE noakun = '02.03.00000' ")or die(mysql_error());
	while($data = mysql_fetch_array($query_mysql)){
		$akun = $data['akun'];
		$namaakun = $data['nama'].' - '.$nama;
		$idakun = $data['id'];
		$user = $_SESSION['user']['username'];
		
		$sqlinsert="INSERT INTO det_coa VALUES(NULL, '$idakun', '$akun', '$namaakun', '$user', NOW())";
		mysql_query($sqlinsert) or die (mysql_error());
	}

	// Penjualan B2B
	$query_mysql = mysql_query("SELECT id, CONCAT(SUBSTR(noakun,1,6), IF(LENGTH('$last_id')=1,'0000',IF(LENGTH('$last_id')=2,'000',IF(LENGTH('$last_id')=3,'00',IF(LENGTH('$last_id')=4,'0','')))), '$last_id') AS akun, noakun, nama
	FROM mst_coa WHERE noakun = '04.03.00000'  AND deleted=0")or die(mysql_error());
	while($data = mysql_fetch_array($query_mysql)){
		$akun = $data['akun'];
		$namaakun = $data['nama'].' - '.$nama;
		$idakun = $data['id'];
		$user = $_SESSION['user']['username'];
		
		$sqlinsert="INSERT INTO det_coa VALUES(NULL, '$idakun', '$akun', '$namaakun', '$user', NOW())";
		mysql_query($sqlinsert) or die (mysql_error());
	}

	// $sqlupdate="UPDATE mst_b2bcustomer SET no_akun='$akun' WHERE id='$last_id'";
	// mysql_query($sqlupdate) or die (mysql_error());

	//---akhir simpan master----------------------
	//last_id
	
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_product = $_POST['IDP'.$i];
		$namabrg = $_POST['NamaBrg'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = 1;
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$NettPrice= str_replace(",","", $_POST['NettPrice'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
		//---akhir tarik parameter detail---
		if($id_product==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO mst_b2bcustomer_product( b2bcustomer_id,products_id,nama_produk,price,disc,qty,nett_price) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Harga."','".$Disc."','".$Qty."','".$NettPrice."')";
		// var_dump($query);
		$hasil = mysql_query($query) or die (mysql_error());
		
		}
	}
	}
	else if($_GET['id_trans']!='')
	{
	$id_pkb = $_GET['id_trans'];
	//edit data----------
	//-update detail------------------------
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];		
		$id_product = $_POST['IDP'.$i];
		$namabrg = $_POST['NamaBrg'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = 1;
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$NettPrice= str_replace(",","", $_POST['NettPrice'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
			
			if($id_product=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into mst_b2bcustomer_product(products_id, nama_produk,b2bcustomer_id,qty,price,disc,nett_price) VALUES ('".$id_product."','".$namabrg."','".$id_pkb."','".$Qty."','".$Harga."','".$Disc."','".$NettPrice."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";	
				mysql_query($sql_insert) ;
				/*
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				*/
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update mst_b2bcustomer_product set products_id = '".$id_product."', nama_produk= '".$namabrg."', qty= '".$Qty."', price= '".$Harga."', disc= '".$Disc."', nett_price= '".$NettPrice."' where b2bcustomer_detail_id = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from mst_b2bcustomer_product where b2bcustomer_detail_id ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		        //var_dump($sql_delete);die;
				mysql_query($sql_delete);
				}
		    }
	    
		}
		
		//update master------------------------------
		$sql_master_up="update mst_b2bcustomer set nama='".$nama."',npwp='".$npwp."',ktp='".$ktp."',alamat='".$alamat."',no_telp='".$telp."',hp='".$hp."',id_address='".$id_address."',due='".$due."',acc_info='".$acc_info."',contact='".$contact."',note='".$note."',totalqty='".$totalqty."',user='".$id_user."',lastmodified=now() where id= '".$_GET['id_trans']."'";
		//var_dump($sql_master_up);die;
		mysql_query($sql_master_up) or die (mysql_error());

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
