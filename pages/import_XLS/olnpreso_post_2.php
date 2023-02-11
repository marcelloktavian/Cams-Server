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
    $keterangan = $_POST['alamat'] ;
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

    function getincrementnumber2()
	{
        $q = mysql_fetch_array( mysql_query('select id_trans from olnso order by id_trans desc limit 0,1'));
        
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
	$id="OLN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
    }	
    

    //validasi php untuk mencari kode web yang sama
	$rows=0;
	//mengecek apakah kode_webnya kosong atau tidak
	if(isset($ref) and !empty($ref)){
		$sql_validasi="SELECT * FROM olnpreso WHERE (oln_order_id IS NOT NULL OR oln_order_id <> '0' OR oln_order_id <> '' ) and oln_order_id ='".$ref."' ORDER BY id DESC";
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
		$sql_validasi="SELECT * FROM olnpreso WHERE (oln_expnote IS NOT NULL OR oln_expnote <> '0' OR oln_expnote <> '' ) and oln_expnote ='".$exp_code."' ORDER BY id DESC";
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

        $idolnso = getnewnotrxwait2();
        
    // kondisi jika grandtotal 0 maka deposit
    if( $byr_deposit > 0 ){
          $grandtotal = 0;
    }
	else{
	$grandtotal = $_POST['total'];
	}
		//grandtotal untuk diisi di olnso	  
	    $grandtotalolnso = $_POST['total'];

        // looping
        for ($i=1; $i<$row; $i++)
        {
            // define dom Detail;
            $Iddetail         = $_POST['Id'.$i];
            $IDP        = $_POST['IDP'.$i];
            $Namabarang = addslashes($_POST['NamaBrg'.$i]);
            $harga      = $_POST['Harga'.$i];
            $qty        = $_POST['Qty'.$i];
            $size       = $_POST['Size'.$i];
            $disc       = $_POST['Disc'.$i];
            $subtotal   = $_POST['SUBTOTAL'.$i];
            $tax = $harga * 0.11;
            $totaldet = $harga+$tax;
            // end define dom
            
                $query = " UPDATE olnpreso SET id_product='$IDP',namabrg='$Namabarang',size='$size',jumlah_beli='$qty',harga_satuan='$harga',tax='$tax',subtotal='$subtotal',total='$grandtotal',oln_order_id='$ref',oln_note='$keterangan',oln_customer='$dropshipper',oln_customer_telp='$telp',oln_expnote='$exp_code',tunai='$tunai',transfer='$transfer',id_expedition='$id_expedisi',exp_fee='$exp_fee' WHERE id='$Iddetail' ";
                // var_dump($query);die;
                 $hasil = mysql_query($query) or die (mysql_error());
        }
    
        
        // jangan lupa state rubah state menjadi 1 jika sudah berhasil
        $queryState=" UPDATE olnpreso SET state='1' WHERE oln_order_id = '$ref' ";
         $hasilState = mysql_query($queryState) or die (mysql_error());

        // Master Insert to olnso
        //$queryMaster = " INSERT INTO olnso (id_trans,ref_kode,tgl_trans,nama,alamat,telp,tunai,transfer,deposit,faktur,total,piutang,totalqty,id_dropshipper,
        //id_address,id_expedition,exp_code,exp_fee,note,discount,aktif,state) SELECT '$idolnso','$ref',b.oln_tgl,b.oln_penerima, b.oln_address,
        //b.oln_telp,b.tunai,b.transfer,sum(b.deposit),sum(b.jumlah_beli * (b.harga_satuan + b.tax)),'$grandtotalolnso','0',SUM(b.jumlah_beli),b.id_dropshipper,'0',b.id_expedition,
        //b.oln_expnote,b.exp_fee,b.oln_note,'$disc_dropshipper','Y','0' FROM olnpreso b WHERE (b.oln_order_id = '$ref') ";
		
		//posting tanpa save keterangan info web
		// $queryMaster = " INSERT INTO olnso (id_trans,ref_kode,tgl_trans,nama,alamat,telp,tunai,transfer,deposit,faktur,total,piutang,totalqty,id_dropshipper,
  //       id_address,id_expedition,exp_code,exp_fee,discount,aktif,state) SELECT '$idolnso','$ref',b.oln_tgl,b.oln_penerima, b.oln_address,
  //       b.oln_telp,b.tunai,b.transfer,sum(b.deposit),sum(b.jumlah_beli * (b.harga_satuan + b.tax)),'$grandtotalolnso','0',SUM(b.jumlah_beli),b.id_dropshipper,'0',b.id_expedition,
  //       b.oln_expnote,b.exp_fee,'$disc_dropshipper','Y','0' FROM olnpreso b WHERE (b.oln_order_id = '$ref') ";
        $queryMaster = " INSERT INTO olnso (id_trans,ref_kode,tgl_trans,nama,alamat,telp,tunai,transfer,deposit,faktur,total,piutang,totalqty,id_dropshipper,
        id_address,id_expedition,exp_code,exp_fee,discount,aktif,state) SELECT '$idolnso','$ref',b.oln_tgl,b.oln_penerima, CONCAT(b.oln_address,' (kec ',b.oln_kecamatan,', ',b.oln_kotakab,', ',b.oln_provinsi,')') AS oln_address,
        b.oln_telp,b.tunai,b.transfer,sum(b.deposit),sum(b.jumlah_beli * (b.harga_satuan + b.tax)),'$grandtotalolnso','0',SUM(b.jumlah_beli),b.id_dropshipper,'0',b.id_expedition,
        b.oln_expnote,b.exp_fee,'$disc_dropshipper','Y','0' FROM olnpreso b WHERE (b.oln_order_id = '$ref') ";
         $hasilMaster = mysql_query($queryMaster) or die (mysql_error());

        // last Insert Id
        $idolnauto = mysql_insert_id();
        // detail Insert to olnsodetail
        $queryDetil = " INSERT INTO olnsodetail (id_trans,id_product,namabrg,size,jumlah_beli,disc,id_oln_auto,harga_act,subtotal_act,jumlah_beli_act)
        SELECT '$idolnso',b.id_product,b.namabrg,b.size,b.jumlah_beli,b.disc,'$idolnauto',b.harga_satuan,b.subtotal,b.jumlah_beli FROM olnpreso b
        WHERE (b.oln_order_id = '$ref')";
        $hasilDetail = mysql_query($queryDetil) or die (mysql_error()); 
        //

        //update untuk discount dari table dropshipper

        // $updateDisc = " update olnso a set a.discount = (select disc from mst_dropshipper WHERE oln_customer_id= '$id_onlineDropshipper') WHERE a.id_trans='$idolnso' ";
        // $hasilDetail = mysql_query($updateDisc) or die (mysql_error());
        // var_dump($hasilDetail);
        // 

        // UPDATE olnsodetail ols,mst_products d SET ols.harga_satuan = d.harga WHERE (ols.id_product = d.id) AND ols.id_trans='OLN200600002' 
        $qryupdateharga = "";
        $qryupdateharga = " UPDATE olnsodetail ols,mst_products d SET ols.harga_satuan = d.harga WHERE (ols.id_product = d.id) AND ols.id_trans='$idolnso' ";
        $hasilHarga = mysql_query($qryupdateharga) or die (mysql_error());
        // untuk update subtotal karena subtotalnya 0
        $qryupdatesubtotal="";
        $qryupdatesubtotal = " UPDATE olnsodetail ols SET ols.subtotal = ( ols.harga_satuan * ols.jumlah_beli ) WHERE  ols.id_trans='$idolnso' ";
        $hasilSubtotal = mysql_query($qryupdatesubtotal) or die (mysql_error());

    // kondisi untuk deposit
     $sql_deposit="";
	if ($byr_deposit > 0)
	{
    $sql_deposit="insert into olndeposit(id_trans,kode,tgl_trans,id_dropshipper ,totalfaktur ,tunai ,deposit  ,catatan)
     values('".$idolnso."' ,'".$kode."' ,( SELECT tgl_trans FROM olnso WHERE id_trans='$idolnso' ) ,'".$id_dropshipper."'  ,'-".$byr_deposit."'  ,'-".$byr_deposit."'  ,'-".$byr_deposit."'  ,'JUAL BYR DEPOSIT')";
    //  var_dump($sql_deposit);die;
	mysql_query($sql_deposit)or die (mysql_error());
	}
    
	//delete setelah posting
	$sql_deposit="DELETE FROM olnpreso where oln_order_id=".$ref;
    //  var_dump($sql_deposit);die;
	mysql_query($sql_deposit)or die (mysql_error());
	
	?>

    <script>
        window.close();
    </script>