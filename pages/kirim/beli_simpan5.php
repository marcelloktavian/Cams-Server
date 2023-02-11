<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
    function max_id(){
	$q = mysql_fetch_array(mysql_query('select (max(id)+1) as nomor from trbeli_id'));
	//$temp="INV-".$q['nomor'];
	$temp_id = "AST".sprintf("%06d", $q['nomor']);
	return $temp_id;
	}
	//--------tarik parameter utk simpan---------------
	$id_supplier = $_POST['id_supplier'];
	$id_pkb= $_POST['kode_hidden'];
	$tgl   = $_POST['tgl'];
	$kode   = max_id();
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$grandfaktur=0;
	$totalfaktur=0;
	$totalqty=0; 
     	
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$id_jenis = $_POST['Idkategori'.$i];
		$Harga_yard = str_replace(",","", $_POST['Hkategori'.$i]);
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		//$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$grandfaktur = $Harga_yard * $Qty;
		$totalfaktur += str_replace(",","", $grandfaktur);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		
		//---akhir tarik parameter detail---
		if($Id_Part==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO trbeli_detail(id_barang, id_trans,id_jenis,harga_yard,qty,harga) VALUES ('".$Id_Part."','".$id_pkb."','".$id_jenis."','".$Harga_yard."','".$Qty."','".$Harga."')";
		$hasil = mysql_query($query) or die (mysql_error());
	
		//Buat update stok barang----------------------
		/*
		$stok = "";
		$stok = "INSERT INTO stok_gudang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
		mysql_query($stok) or die ("update stok1 error".mysql_error());
		*/
		}
	}
	//--- simpan master ---------------------------
	mysql_query("insert into trbeli(id_trans,kode,tgl_trans,id_supplier,totalfaktur,faktur_murni,totalqty,id_user)values('".$id_pkb."','".$kode."',NOW(),'".$id_supplier."','".$totalfaktur."','".$totalfaktur."','".$totalqty."','".$id_user."')") or die (mysql_error());
	//---akhir simpan master----------------------
	//input id transaksi buat kode
	mysql_query("insert into trbeli_id(lastmodified) values(NOW())") or die (mysql_error());

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
		
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$id_jenis = $_POST['Idkategori'.$i];
		$Harga_yard = str_replace(",","", $_POST['Hkategori'.$i]);
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		//$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
        $grandfaktur = $Harga_yard * $Qty;
		$totalfaktur += str_replace(",","", $grandfaktur);
			
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trbeli_detail(id_barang, id_trans,id_jenis,harga_yard,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$id_jenis."','".$Harga_yard."','".$Harga."','".$Qty."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";	
				mysql_query($sql_insert) ;
				/*
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				*/
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trbeli_detail set id_barang = '".$Id_Part."', id_jenis= '".$id_jenis."', harga_yard= '".$Harga_yard."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trbeli_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_delete);
				}
		    }
	    
		}
		
		//update master------------------------------
		mysql_query("update trbeli set faktur_murni='".$totalfaktur."',totalfaktur='".$totalfaktur."', totalqty='".$totalqty."', id_supplier='".$_POST['id_supplier']."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}
	
	/*
	echo"sql_insert= ".$sql_insert;
	echo"<br/>sql_update= ".$sql_update;
	echo"<br/>sql_delete= ".$sql_delete;
	echo"<br/>delete= ".$delete;
	echo"<br/>id_detail= ".$detail;
	echo"<br/>id_detail= ".$detail;
	*/
	header("location:beli_nota.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
