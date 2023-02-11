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
	$q = mysql_fetch_array( mysql_query('select id_trans from b2bso order by id_trans desc limit 0,1'));
	
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
	$id="B2B".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
	
	//$id_registrasi = getnewnotrxwait();
	$id_pkb = getnewnotrxwait2();
	//--------tarik parameter utk simpan---------------
	//$id_supplier = $_POST['id_supplier'];
	$tgl   			  = $_POST['tanggal']; 
	$ref_kode		  = $_POST['ref_code'];
	$nama             = addslashes($_POST['nama']);
	$telp             = $_POST['telp'];
	$alamat           = addslashes($_POST['alamat']);
	$id_kategori      = $_POST['id_kategori'];
	$id_salesman      = $_POST['id_salesman'];
	$id_customer      = $_POST['id_customer'];
	$id_address   	  = 0;
	/* diisinya belakangan
	$id_expedition    = $_POST['id_expedition'];
	$exp_code         = $_POST['exp_code'];
	$exp_fee          = $_POST['exp_fee'];
	$exp_note         = $_POST['exp_note'];
	*/
	$id_expedition    = '';
	$exp_code         = '';
	$exp_fee          = '';
	$exp_note         = '';
	//coba ambil dengan metoda get
	//$row=$_POST['jum'];
	$row=$_GET['jum'];
	 
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
     	
    if($_GET['id_trans']==''){
	//add data
	for ($i=1; $i<$row; $i++)
	{
		//---tarik parameter detail---
		$id_product = $_POST['IDP'.$i];
		$namabrg = $_POST['NamaBrg'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = $_POST['SUBTOTALQTY'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$Pricelist = str_replace(",","", $_POST['Pricelist'.$i]);
		//qty size dan idnya
		$qty36 = $_POST['S36_'.$i];
		$s36id = $_POST['S36id'.$i];
		$qty37 = $_POST['S37_'.$i];
		$s37id = $_POST['S37id'.$i];
		$qty38 = $_POST['S38_'.$i];
		$s38id = $_POST['S38id'.$i];
		$qty39 = $_POST['S39_'.$i];
		$s39id = $_POST['S39id'.$i];
		$qty40 = $_POST['S40_'.$i];
		$s40id = $_POST['S40id'.$i];
		$qty41 = $_POST['S41_'.$i];
		$s41id = $_POST['S41id'.$i];
		$qty42 = $_POST['S42_'.$i];
		$s42id = $_POST['S42id'.$i];
		$s43   = $_POST['S43_'.$i];
		$s43id = $_POST['S43id'.$i];
		$s44   = $_POST['S44_'.$i];
		$s44id = $_POST['S44id'.$i];
		$s45   = $_POST['S45_'.$i];
		$s45id = $_POST['S45id'.$i];
		$qty46   = $_POST['S46_'.$i];
		$s46id = $_POST['S46id'.$i];

	
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
		$totalqty += str_replace(",","", $_POST['SUBTOTALQTY'.$i]);
		//---akhir tarik parameter detail---
		if($id_product==''){
		}
		else
		{
		//---simpan detail---
		$query = "INSERT INTO b2bso_detail( id_trans,id_product,namabrg,harga_satuan,harga_act,disc,jumlah_beli,id36,qty36,id37,qty37,id38,qty38,id39,qty39,id40,qty40,id41,qty41,id42,qty42,id43,qty43,id44,qty44,id45,qty45,id46,qty46,subtotal) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Harga."','".$Pricelist."','".$Disc."','".$Qty."','".$s36id."','".$qty36."','".$s37id."','".$qty37."','".$s38id."','".$qty38."','".$s39id."','".$qty39."','".$s40id."','".$qty40."','".$s41id."','".$qty41."','".$s42id."','".$qty42."','".$s43id."','".$qty43."','".$s44id."','".$qty44."','".$s45id."','".$qty45."','".$s46id."','".$qty46."','".$Subtotal."')";
		//var_dump($query);die;
		$hasil = mysql_query($query) or die (mysql_error());
	    
		//Buat insert stok composition----------------------
		//$sql_comp ="INSERT INTO b2bso_composition (id_trans,id_product,id_composition,namabrg,jumlah_beli,size,harga_satuan) SELECT '$id_pkb',bd.products_id,bd.composition_id,'$namabrg','$Qty','$Size','$Harga' FROM mst_b2bproducts_detail bd WHERE bd.products_id=$id_product";
		//$hasil_comp = mysql_query($sql_comp) or die (mysql_error());
	    
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
	$simpan_deposit = $_POST['simpan_deposit'];
	$byr_deposit = $_POST['byr_deposit'];
	$sisa_deposit = 0;
    $sisa_deposit = $simpan_deposit-$byr_deposit;
	$piutang = 0;
	$piutang = str_replace(".","",$_POST['piutang']);
	$totalfaktur = $faktur+$exp_fee;
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
	$sql_master="";
	$sql_master="insert into b2bso(id_trans,ref_kode,tgl_trans,id_kategori,id_salesman,id_customer,nama,telp,alamat,id_address,id_expedition,exp_code,exp_fee,exp_note,total,faktur,totalqty,tunai,transfer,deposit,simpan_deposit,piutang,note,user)values('".$id_pkb."','".$ref_kode."','".$tgl."','".$id_kategori."','".$id_salesman."','".$id_customer."','".$nama."','".$telp."','".$alamat."','".$id_address."','".$id_expedition."','".$exp_code."','".$exp_fee."','".$exp_note."','".$totalfaktur."','".$faktur."','".$totalqty."','".$tunai."','".$transfer."','".$byr_deposit."','".$simpan_deposit."','".$piutang."','".$keterangan."','".$id_user."')";
	//var_dump($sql_master);die;
	mysql_query($sql_master) or die (mysql_error());
	//---akhir simpan master----------------------
	//---simpan deposit pelanggan--------------------------------
	/*
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
    */
	}
	else if($_GET['id_trans']!='')
	{
	//edit data----------
	$id_pkb	= $_GET['id_trans'];	
	//-update detail------------------------
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
		
		$id_product = $_POST['IDP'.$i];//
		$namabrg = $_POST['NamaBrg'.$i];
		$Qty = $_POST['SUBTOTALQTY'.$i];
		$Pricelist = str_replace(",","", $_POST['Pricelist'.$i]);
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$Size = $_POST['Size'.$i];
		$Subtotal= str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$Disc= str_replace(",","", $_POST['Disc'.$i]);
		$totalqty += str_replace(",","", $_POST['SUBTOTALQTY'.$i]);
        $grandfaktur = $Harga_yard * $Qty;
		$totalfaktur += str_replace(",","", $grandfaktur);
        		
		//qty size dan idnya
		$qty36 = $_POST['S36_'.$i];
		$s36id = $_POST['S36id'.$i];
		$qty37 = $_POST['S37_'.$i];
		$s37id = $_POST['S37id'.$i];
		$qty38 = $_POST['S38_'.$i];
		$s38id = $_POST['S38id'.$i];
		$qty39 = $_POST['S39_'.$i];
		$s39id = $_POST['S39id'.$i];
		$qty40 = $_POST['S40_'.$i];
		$s40id = $_POST['S40id'.$i];
		$qty41 = $_POST['S41_'.$i];
		$s41id = $_POST['S41id'.$i];
		$qty42 = $_POST['S42_'.$i];
		$s42id = $_POST['S42id'.$i];
		$s43   = $_POST['S43_'.$i];
		$s43id = $_POST['S43id'.$i];
		$s44   = $_POST['S44_'.$i];
		$s44id = $_POST['S44id'.$i];
		$s45   = $_POST['S45_'.$i];
		$s45id = $_POST['S45id'.$i];
		$qty46 = $_POST['S46_'.$i];
		$s46id = $_POST['S46id'.$i];

			if($id_product=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="INSERT INTO b2bso_detail( id_trans,id_product,namabrg,harga_satuan,harga_act,disc,jumlah_beli,id36,qty36,id37,qty37,id38,qty38,id39,qty39,id40,qty40,id41,qty41,id42,qty42,id43,qty43,id44,qty44,id45,qty45,id46,qty46,subtotal) VALUES ('".$id_pkb."','".$id_product."','".$namabrg."','".$Harga."','".$Pricelist."','".$Disc."','".$Qty."','".$s36id."','".$qty36."','".$s37id."','".$qty37."','".$s38id."','".$qty38."','".$s39id."','".$qty39."','".$s40id."','".$qty40."','".$s41id."','".$qty41."','".$s42id."','".$qty42."','".$s43id."','".$qty43."','".$s44id."','".$qty44."','".$s45id."','".$qty45."','".$s46id."','".$qty46."','".$Subtotal."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$id_product,baris=$i');</script>";	
				mysql_query($sql_insert) ;
				/*
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				*/
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update b2bso_detail set id_product = '".$id_product."', id_trans= '".$id_pkb."', namabrg= '".$namabrg."', harga_satuan= '".$Harga."', harga_act= '".$Pricelist."', jumlah_beli= '".$Qty."',id36='".$s36id."',qty36='".$qty36."',id37='".$s37id."',qty37='".$qty37."',id38='".$s38id."',qty38='".$qty38."',id39='".$s39id."',qty39='".$qty39."',id40='".$s40id."',qty40='".$qty40."',id41='".$s41id."',qty41='".$qty41."',id42='".$s42id."',qty42='".$qty42."',id43='".$s43id."',qty43='".$qty43."',id44='".$s44id."',qty44='".$qty44."',id45='".$s45id."',qty45='".$qty45."',id46='".$s46id."',qty46='".$qty46."',subtotal='".$Subtotal."' where b2bso_id = '".$id_detail."'";
				//var_dump($sql_update);die;
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		    	mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from b2bso_detail where b2bso_id ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		        //var_dump($sql_delete);die;
				mysql_query($sql_delete);
				}
		    //Buat insert stok composition----------------------
			//delete detail composition dulu baru diinsert ulang
			//$sql_comp_del ="DELETE FROM b2bso_composition WHERE id_trans='$id_pkb'";
			//var_dump($sql_comp_del);die;
			//$hasil_comp_del = mysql_query($sql_comp_del) or die (mysql_error());
			
			//$sql_comp_ed ="INSERT INTO b2bso_composition (id_trans,id_product,id_composition,namabrg,jumlah_beli,size,harga_satuan) SELECT '$id_pkb',bd.products_id,bd.composition_id,'$namabrg','$Qty','$Size','$Harga' FROM mst_b2bproducts_detail bd WHERE bd.products_id=$id_product";
			//$hasil_comp_ed = mysql_query($sql_comp_ed) or die (mysql_error());
			}
	    
		}
	
		//update master------------------------------
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
		$totalfaktur = $faktur+$exp_fee;
	
		$sql_master_up="";
		$sql_master_up="update b2bso set tgl_trans='".$tgl."',ref_kode='".$ref_kode."',nama='".$nama."',alamat='".$alamat."',telp='".$telp."',id_kategori='".$id_kategori."',id_salesman='".$id_salesman."',id_customer='".$id_customer."',id_address='".$id_address."',id_expedition='".$id_expedition."',exp_code='".$exp_code."',exp_fee='".$exp_fee."',exp_note='".$exp_note."', totalqty='".$totalqty."', totalqty='".$totalqty."', total='".$totalfaktur."', faktur='".$faktur."', totalqty='".$totalqty."',tunai='".$tunai."',transfer='".$transfer."', deposit='".$byr_deposit."',simpan_deposit='".$simpan_deposit."',piutang='".$piutang."',note='".$keterangan."',user='".$id_user."' where id_trans='".$_GET['id_trans']."'";
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
