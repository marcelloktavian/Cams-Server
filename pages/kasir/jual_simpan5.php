<?php 
error_reporting(0);
session_start();
  
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
function max_id(){
//$bilangan=1234; // Nilai Proses
//$temp = sprintf("%07d", $bilangan);
//echo "$fzeropadded "; // Hasil 0001234

$q = mysql_fetch_array(mysql_query('select (max(id)+1) as nomor from trjual_id'));
//$temp="INV-".$q['nomor'];
$temp_id = sprintf("%06d", $q['nomor']);
return $temp_id;
}

//include("koneksi/koneksi.php");
	//--------tarik parameter utk simpan---------------
	$id_customer = $_POST['id_customer'];
	//$kode= $_POST['kode'];
	$kode   = max_id();
	//$id_pkb= $_POST['kode_hidden'];
	$id_pkb = max_id()."/".date('dmY');
	$tgl    = $_POST['tgl'];
	
	$row=$_POST['jum'];
	
	//--------akhir tarik parameter utk simpan------------
	//inisialisasi sebelum entry
	$biaya = 0;
	$faktur=0;
	$tunai=0;
	$transfer=0;
	$kartu=0;
	$simpan_deposit=0;
	$byr_deposit=0;
	$totalfaktur=0;
	$totalqty=0; 
     //	var_dump($_GET['id_trans']);die;
    if($_GET['id_trans']==''){
	//add new data from kasir
	  if(isset($_POST['cek_kasir']) && $_POST['cek_kasir'] == 'Yes')
      {
      //echo "Need wheelchair access.";
	 
		//simpan utk print
		for ($i=1; $i<$row; $i++){
		
			//---tarik parameter detail---
			//$Id_Part = $_POST['BARCODE'.$i];
			$Qty = $_POST['Qty'.$i];
			$id_jenis = $_POST['cmb_kategori_'.$i];
			$Harga = str_replace(",","", $_POST['Harga'.$i]);
			$faktur =0;//+= str_replace(",","", $_POST['SUBTOTAL'.$i]);
			$kuantum = 0;//+= str_replace(",","", $_POST['Qty'.$i]);
			$totalqty += str_replace(",","", $_POST['Qty'.$i]);
			//$names = explode(",", $_POST['txtbrg'.$i]);
			$nama_barang = $_POST['BARCODE'.$i];
			//print_r($names );
			$arr_brg = explode(",",$nama_barang);
			for ($j=0;$j<count($arr_brg) ;$j++){
				$brg = explode('=',$arr_brg[$j]);
				// if (!$brg){
					// $kode_brg  = $arr_brg[$j];
					// $qty_sat = 1;
				// } else {
					$kode_brg  = $brg[0];
					$qty_sat = (isset($brg[1])?$brg[1]:1);
					$kuantum += $qty_sat;
					$faktur += ($qty_sat*$Harga);
					$totalfaktur += ($qty_sat*$Harga);
				// }
				$query = "";
					$query = "INSERT INTO trjual_detail(kode_brg, id_trans, qty,harga) VALUES ('".$kode_brg."','".$id_pkb."','".$qty_sat."','".$Harga."')";
					$hasil = mysql_query($query) or die (mysql_error());
			
					// Buat simpan stok barang----------------------
					$stok = "";
					$stok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$kode_brg."','".$id_pkb."','-".$qty_sat."',now())";
				//	mysql_query($stok) or die ("update stok1 error".mysql_error());
					
			}
			 
					$query = "";
					$query = "INSERT INTO trjual_print(id_jenis,nama_barang, id_trans, kuantum,harga,harga_plus_ppn) VALUES ('".$id_jenis."','".$nama_barang."','".$id_pkb."','".$kuantum."','".$Harga."','".$faktur."')";
					$hasil = mysql_query($query) or die (mysql_error());
		 
	    }
	 
	  }
	  
      else
	  {
      //echo "Do not Need wheelchair access.";
      //add new data from tambah penjualan
	
		for ($i=1; $i<$row; $i++)
		{
		//---tarik parameter detail---
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$faktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
		
		//---akhir tarik parameter detail---
			if($Id_Part==''){
			}
			else
			{
			//---simpan detail---
			$query = "INSERT INTO trjual_detail(kode_brg, id_trans, qty,harga) VALUES ('".$Id_Part."','".$id_pkb."','".$Qty."','".$Harga."')";
			$hasil = mysql_query($query) or die (mysql_error());
	
			// Buat simpan stok barang----------------------
			$stok = "";
			$stok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
			mysql_query($stok) or die ("update stok1 error".mysql_error());
		
			//buat print header("location:cetaknotasukucadang.php?id=".$id_ns);
			//--- END simpan detail---
			}
		}
	  }   
	 
	//--- simpan master ---------------------------------------
	$biaya = str_replace(".","",$_POST['ongkir']);
	$tunai = str_replace(".","",$_POST['tunai']);
	$transfer = str_replace(".","",$_POST['transfer']);
	$keterangan = $_POST['txtbrg'];
	$kartu = $_POST['kartu'];
	$simpan_deposit = $_POST['simpan_deposit'];
	$byr_deposit = $_POST['byr_deposit'];
	$faktur = $totalfaktur;
	$totalfaktur +=  $biaya;
	$sisa_deposit = 0;
    $sisa_deposit = $simpan_deposit-$byr_deposit;
	$piutang = 0;
	//Piutang > 0 = penjualan belum lunas,pembyrn lebih kecil dari totalfaktur
	if(($tunai+$transfer+$kartu+$byr_deposit) < $totalfaktur) {
	$piutang = $totalfaktur - ($tunai+$transfer+$kartu+$byr_deposit) ;
	}
	//Piutang < 0 (negatif) = bayarnya kelebihan alias ada deposit
	else {$piutang = 0;}
	//Field simpan_deposit = dipakai untuk menyimpan transaksi yang pembayarannya berlebih,sehingga ada deposit
	//Field deposit dipakai untuk menyimpan pembayaran pakai deposit (byr_deposit) yang sudah tersimpan dari deposit pelanggan
	mysql_query("insert into trjual(id_trans,kode,tgl_trans,id_customer,biaya,faktur,totalfaktur,tunai,transfer,kartu,deposit,simpan_deposit,piutang,totalqty,keluhan,id_user) values('".$id_pkb."','".$kode."',NOW(),'".$id_customer."','".$biaya."','".$faktur."','".$totalfaktur."','".$tunai."','".$transfer."','".$kartu."','".$byr_deposit."','".$simpan_deposit."','".$piutang."','".$totalqty."','".$keterangan."','".$id_user."')") or die (mysql_error());
		
	//---akhir simpan master-------------------------------
	//---simpan deposit pelanggan--------------------------------
	$sql_update="";
	$sql_update="Update tblpelanggan set deposit=deposit+".$sisa_deposit." where id=".$id_customer;
	//var_dump($sql_update);die;
	mysql_query($sql_update)or die (mysql_error());
	
	}
	else if($_GET['id_trans']!='')
	{
	//edit data--------------------------------
	//-update detail---------------------------
		$row = 0; /// ameh teu masuk loooping
		for ($i=0; $i<=$row; $i++)
		{
		//---mengambil parameter---dari jual_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
		
		$Id_Part = $_POST['BARCODE'.$i];
		$Qty = $_POST['Qty'.$i];
		$Harga = str_replace(",","", $_POST['Harga'.$i]);
		$totalfaktur += str_replace(",","", $_POST['SUBTOTAL'.$i]);
		$totalqty += str_replace(",","", $_POST['Qty'.$i]);
    		
			if($Id_Part=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
				$sql_insert="insert into trjual_detail(kode_brg, id_trans,harga,qty) VALUES ('".$Id_Part."','".$_GET['id_trans']."','".$Harga."','".$Qty."')" ;
				//echo "<script> alert('INSERT delete= $delete,id_detail=$id_detail,id_part=$Id_Part,baris=$i');</script>";
		
				mysql_query($sql_insert) ;
				$sql_insert="";
				$sql_insertstok = "INSERT INTO stok_barang(id_barang, id_trans, stok, tgl_trans) VALUES ('".$Id_Part."','".$id_pkb."','-".$Qty."',now())";
	            mysql_query($sql_insertstok) ;	
				
				}
				else if($delete=='' && $id_detail!=''){
				$sql_update="update trjual_detail set kode_brg = '".$Id_Part."', harga= '".$Harga."', qty= '".$Qty."' where id_detail = '".$id_detail."'";
				//echo "<script> alert('UPDATE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_update);
				}
				
				else if($delete!='' && $id_detail==''){
				$sql_delete="delete from trjual_detail where id_detail ='".$delete."'";
				//echo "<script> alert('DELETE delete= $delete,id_detail=$id_detail,baris=$i');</script>";
		
				mysql_query($sql_delete);
				}
		    }
	    
		}
		//TSO18020029
		//update master------------------------------
		$biaya    = str_replace(".","",$_POST['ongkir']);
		$faktur   = $_POST['faktur'];
		$tunai    = str_replace(".","",$_POST['tunai']);
		$transfer = str_replace(".","",$_POST['transfer']);
		$piutang_baru = 0;
	    $piutang_baru = $faktur - ($tunai+$transfer+$kartu) ;
		mysql_query("update trjual set biaya='".$biaya."', tunai='".$tunai."', transfer='".$transfer."' ,piutang='".$piutang_baru."' where id_trans='".$_GET['id_trans']."'") or die (mysql_error());
	
	}
  
//input id transaksi
	mysql_query("insert into trjual_id(lastmodified) values(NOW())") or die (mysql_error());
    
   	
	//header("location:jual_nota.php?id_trans=".$id_pkb."");
	header("location:jual_notaKasir.php?id_trans=".$id_pkb."");
	
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
