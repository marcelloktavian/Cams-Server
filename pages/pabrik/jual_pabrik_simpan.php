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
	$id_pkb	= $_POST['kode_hidden'];
	$tgl    = $_POST['tanggal'];
	$kode   = max_id();
	$row=$_POST['jum'];
	 
	//var_dump('tanggal= '.$tgl);die;
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi subtotal
	$grandfaktur=0;
	$totalfaktur=0;
	$totalqty=0; 
     	
    //fungsi simpan hanya untuk meng-update data saja.
	if($_GET['id_trans']!='')
	{
	//-update detail------------------------
	
		for ($i=1; $i<=$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		//qty disavenya based pcs
		$Qty = $_POST['Qty'.$i];
		$id_jenis = $_POST['Id'.$i];
		$Harga_yard = str_replace(",","", $_POST['Harga'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
        $grandfaktur = $Harga_yard * $Qty;
		$totalfaktur += str_replace(",","", $grandfaktur);
			
				$sql_update="update trbeli_detail set harga_yard= '".$Harga_yard."' where id_jenis = '".$id_jenis."'"." and id_trans='".$id_pkb."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	//var_dump("sql=".$sql_update.",id_detail=".$id_detail.",baris=".$row);die;
				mysql_query($sql_update);
		}
		
		//update master------------------------------
		$sql_master="update trbeli set faktur_murni='".$totalfaktur."',totalfaktur='".$totalfaktur."',piutang='".$totalfaktur."',tgl_trans='".$tgl."', totalqty='".$totalqty."', id_supplier='".$_POST['id_supplier']."' where id_trans='".$_GET['id_trans']."'";
		//var_dump("sql=".$sql_master.",id_supplier=".$_POST['id_supplier'].",id_trans=".$_GET['id_trans']);die;		
		mysql_query($sql_master) or die (mysql_error());
	
	}
	
	
	header("location:jual_pabrik_notajns.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
