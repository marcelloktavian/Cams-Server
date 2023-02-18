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
	$q = mysql_fetch_array( mysql_query('select id_trans from invmasuk order by id_trans desc limit 0,1'));
	
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
	$id="INV".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
    
	$row=$_POST['jum'];	 
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$totalqty=0;   
    if($_GET['id_trans']==''){
	//add data
	$id_pkb = getnewnotrxwait2();
	
	//--- parameter simpan master -----------------------------
	$tgl   			  = $_POST['tanggal']; 
	$kode		  	  = $_POST['kode'];
	//$nama             = addslashes($_POST['nama']);
	$id_inventory     = $_POST['id_inventory'];
	$totalqty = str_replace(".","",$_POST['totalqty']);
	$keterangan = $_POST['txtbrg'];	
	
	//------simpan master---------------------
    $sql_master="";
	$sql_master="insert into invmasuk(id_trans,id_inventory,kode,totalqty,tgl_trans,catatan,user,lastmodified)values('".$id_pkb."','".$id_inventory."','".$kode."','".$totalqty."','".$tgl."','".$keterangan."','".$id_user."',NOW())";
	// var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	$id_oln_auto = mysql_insert_id();
	//---akhir simpan master----------------------

	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_product = $_POST['IDP'.$i];
		$namabrg = addslashes($_POST['NamaBrg'.$i]);
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = $_POST['Qty'.$i];
		$Size = $_POST['Size'.$i];
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		//$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		//---akhir tarik parameter detail---
		if($id_product==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO invmasuk_detail( id_trans,id_product,namabrg,jumlah_beli,size,subtotal,id_oln_auto) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Qty."','".$Size."','".$Subtotal."','".$id_oln_auto."')";
		//var_dump($query);die;
		$hasil = mysql_query($query) or die (mysql_error());
		}
	 }
	}
	else if($_GET['id_trans']!='')
	{
    //edit data----------
	$id_pkb	= $_GET['id_trans'];	
	//-update detail------------------------
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];		
		$id_product = $_POST['IDP'.$i];//
		$namabrg = addslashes($_POST['NamaBrg'.$i]);
		$Qty = $_POST['Qty'.$i];
		$Size = $_POST['Size'.$i];
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
        		
			if($id_product=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="INSERT INTO invmasuk_detail( id_trans,id_product,namabrg,size,jumlah_beli,subtotal) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Size."','".$Qty."','".$Subtotal."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$id_product,baris=$i');</script>";	
				mysql_query($sql_insert) ;	
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update invmasuk_detail set id_product = '".$id_product."', id_trans= '".$id_pkb."', namabrg= '".$namabrg."', size= '".$Size."', jumlah_beli= '".$Qty."',subtotal='".$Subtotal."' where id_inv_d = '".$id_detail."'";
				//var_dump($sql_update);die;
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from invmasuk_detail where id_inv_d ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		        //var_dump($sql_delete);die;
				mysql_query($sql_delete);
				}
		    }
		}
	
		//update master------------------------------
		$kode		= $_POST['kode'];
		$nama		= $_POST['nama'];
		$tgl	 	= $_POST['tanggal'];
		$id_inventory = $_POST['id_inventory'];
		$keterangan = $_POST['txtbrg'];
		
		$sql_master_up="";
		$sql_master_up="update invmasuk set tgl_trans='".$tgl."',kode='".$kode."',nama='".$nama."',id_inventory='".$id_inventory."', totalqty='".$totalqty."',catatan='".$keterangan."',user='".$id_user."',lastmodified=now() where id_trans='".$_GET['id_trans']."'";
		//var_dump($sql_master_up);die;
		mysql_query($sql_master_up) or die (mysql_error());
	}
   //Buat update stok barang----------------------
		/*
		$stok = "";
		$stok = "INSERT INTO stok_gudang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
		mysql_query($stok) or die ("update stok1 error".mysql_error());
		*/
	
	/*
	echo"sql_insert= ".$sql_insert;
	echo"<br/>sql_update= ".$sql_update;
	echo"<br/>sql_delete= ".$sql_delete;
	echo"<br/>delete= ".$delete;
	echo"<br/>id_detail= ".$detail;
	echo"<br/>id_detail= ".$detail;
	*/
	//DIMATIKAN AGAR LANGSUNG SAVE
	//header("location:trolnso_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
