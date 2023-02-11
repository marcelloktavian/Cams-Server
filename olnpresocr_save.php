<?php
    // include
    error_reporting(0);
    require "../../include/koneksi.php";

    $action = $_GET['action'];

    $totalqty = $_POST['totalqty'];
    $total_blmdisc = $_POST['total_blmdisc'];
    $kode_hidden = $_POST['kode_hidden'];
    $ref = $_POST['ref_code'];
    $dropshipper = $_POST['dropshipperinfo'];
    $id_dropshipper = $_POST['id_dropshipper'];
    $disc_dropshipper = $_POST['disc_dropshipper'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $region = $_POST['region'];
    $keterangan = $_POST['keterangan'] ;
    $expedisi = $_POST['expedition'];
    $id_expedisi = $_POST['id_expedition'];
    $exp_code = $_POST['exp_code'];
    $exp_fee = $_POST['exp_fee'];
    $exp_note = $_POST['exp_note'];
    $keterangan_bawah = $_POST['txtbrg'];
    $disc_faktur = $_POST['disc_faktur'];
    $tunai = $_POST['tunai'];
    $transfer = $_POST['transfer'];
    $byr_deposit=$_POST['byr_deposit'];
    $piutang=$_POST['piutang'];
    $id_onlineDropshipper = $_POST['id_onlineDropshipper'];
    $telp_dropshipper = $_POST['telp_dropshipper'];
    $grandtotal = $_POST['total'];


    $row=$_POST['jum'];

    // function getincrementnumber2()
	// {
    //     $q = mysql_fetch_array( mysql_query('select id_trans from olnpreso order by id_trans desc limit 0,1'));
        
    //     $kode=substr($q['id_trans'], -5);
    //     $bulan=substr($q['id_trans'], -7,2);
    //     $bln_skrng=date('m');
    //     $num=(int)$kode;
    //     //echo"Kode=".$kode."Num=".$num."bulan=".$bulan;
        
    //     if($num==0 || $num==null || $bulan!=$bln_skrng)		
    //     {
    //         $temp = 1;
    //     }
    //     else
    //     {
    //         $temp=$num+1;
    //     }
    //     return $temp;
    // }

	// function getmonthyeardate2()
	// {
	// $today = date('ym');
	// return $today;
	// }

	// function getnewnotrxwait2()
	// {
	// $temp=getmonthyeardate2();
	// $temp2=getincrementnumber2();
	// $id="OLP".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	// return $id;
    // }	
    

    //validasi php untuk mencari kode web yang sama
	//mengecek apakah kode_webnya kosong atau tidak
	if(isset($ref) and !empty($ref)){
		$sql_validasi="SELECT * FROM olnpreso_cr WHERE (oln_order_id IS NOT NULL OR oln_order_id <> '0' OR oln_order_id <> '' ) and oln_order_id ='".$ref."' ORDER BY id DESC";
		//var_dump($sql_validasi);die;
		$rows = mysql_num_rows($sql_validasi);
		//var_dump($sql_validasi);	
		$sql = mysql_query($sql_validasi);
		//menghitung jumlah baris yang double
		$rows = mysql_num_rows($sql);
		//echo"jumlah baris=".$rows;die;	
		$rs = mysql_fetch_array($sql);
		$id_trans=$rs['id_trans'];
	}
	else if(isset($exp_code) and !empty($exp_code)){
		$sql_validasi="SELECT * FROM olnpreso_cr WHERE (oln_expnote IS NOT NULL OR oln_expnote <> '0' OR oln_expnote <> '' ) and oln_expnote ='".$exp_code."' ORDER BY id DESC";
		//var_dump($sql_validasi);die;
		$rows = mysql_num_rows($sql_validasi);
		//var_dump($sql_validasi);	
		$sql = mysql_query($sql_validasi);
		//menghitung jumlah baris yang double
		$rows = mysql_num_rows($sql);
		//echo"jumlah baris=".$rows;die;	
		$rs = mysql_fetch_array($sql);
		$id_trans=$rs['id_trans'];
    }
    //kalo ada ref_kode double langsung di stop dan di redirect ke page berikutnya
    //kalo tidak ada double lanjutkan save data
    // end validasi
    
        // kondisi jika grandtotal 0 maka deposit
        if( $byr_deposit > 0 ){
            $grandtotal = 0;
        }
        


        // looping
        $totalakhir = 0;
        for ($i=1; $i<$row; $i++)
        {
            $iddelete = $_POST['delete1'.$i];
            $iddet = $_POST['Id'.$i];
            $idbrg = $_POST['BARCODE'.$i];
            $idolnbrg = '';
            $namabrg = $_POST['NamaBrg'.$i];
            $Qty = $_POST['Qty'.$i];
            $size = $_POST['Size'.$i];
            $Harga = $_POST['Harga'.$i];
            $disc = $_POST['Disc'.$i];
            $tax = $Harga * 0.11;
            $sub = $Qty * $Harga;
            $grandtotal = '0';

            if ($iddelete!=null) {
                $delete = "DELETE FROM olnpreso_cr WHERE id='$iddelete'";
                $hasildelete = mysql_query($delete) or die (mysql_error());
            }

            $queryoln = "SELECT id, oln_product_id FROM mst_products WHERE id='".$idbrg."'";
            $result = mysql_query($queryoln);
            $rownya = mysql_num_rows($result);

            if ($rownya==1) {
                $hasilnya = mysql_fetch_assoc($result) or die (mysql_error());  
                $idolnbrg = $hasilnya["oln_product_id"];
            }

            if ($iddelete==null && $iddet=='' && $idbrg != '') {
                //id detail kosong = insert
                $query = "INSERT INTO olnpreso_cr(id_product, namabrg, size, jumlah_beli, harga_satuan, tax, subtotal, total, oln_order_id ,oln_note ,oln_customer, oln_customer_telp, oln_expnote, tunai, transfer, id_expedition, exp_fee, oln_penerima, oln_address, oln_telp, oln_customerid, id_dropshipper,oln_productid,oln_noteexp,oln_keterangan) VALUES('$idbrg', '$namabrg', '$size', '$Qty', '$Harga', '$tax', '$sub', '$grandtotal', '$ref','$keterangan',  '$dropshipper', '$telp', '$exp_code', '$tunai',  '$transfer','$id_expedisi', '$exp_fee','$nama','$alamat', '$telp', '$id_onlineDropshipper', '$id_dropshipper','$idolnbrg','$exp_note','$keterangan_bawah')";
                $hasil = mysql_query($query) or die (mysql_error());
            }else if($iddelete==null && $iddet!='' && $idbrg != ''){
                //id detail ada = update
                $query = " UPDATE olnpreso_cr SET id_product='$idbrg',namabrg='$namabrg',size='$size',jumlah_beli='$Qty',harga_satuan='$Harga',tax='$tax',subtotal='$sub',total='$grandtotal',oln_order_id='$ref',oln_note='$keterangan',oln_customer='$dropshipper',oln_customer_telp='$telp',oln_expnote='$exp_code',tunai='$tunai',transfer='$transfer',id_expedition='$id_expedisi',exp_fee='$exp_fee', oln_penerima='$nama', oln_address='$alamat', oln_telp='$telp', oln_customerid='$id_onlineDropshipper', id_dropshipper='$id_dropshipper',oln_productid='$idolnbrg', oln_noteexp='$exp_note', oln_keterangan='$keterangan_bawah' WHERE id='$iddet' ";
                $hasil = mysql_query($query) or die (mysql_error());
            }
                // var_dump($query);
                $sub = $Qty * ($Harga + $tax);
                $totalakhir = $totalakhir + $sub;
        }

        if ($exp_fee==null || $exp_fee=='') {
            $totalakhir = $totalakhir; 
        } else {
           $totalakhir = $totalakhir + $exp_fee;
        }
        
        $update = "UPDATE olnpreso_cr SET total = '$totalakhir' WHERE oln_order_id='$ref'";
        $hasilupdate = mysql_query($update) or die (mysql_error());
        // die;

    ?>

    <script>
        window.close();
    </script>