<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess    = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY, penyusutan, $group_acess);
$allow_delete   = is_show_menu(DELETE_POLICY, penyusutan, $group_acess);


if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
    $page = isset($_GET['page'])?$_GET['page']:1;
    $limit = isset($_GET['rows'])?$_GET['rows']:10;
    $sidx = isset($_GET['sidx'])?$_GET['sidx']:'nilai_sisa_aset';
    $sord = isset($_GET['sord'])?$_GET['sord']:'DESC';
  
    $startdate = isset($_GET['startdate'])?$_GET['startdate']:DATE('d/m/Y');
    $enddate = isset($_GET['enddate'])?$_GET['enddate']:DATE('d/m/Y');
    $aset = isset($_GET['aset'])?$_GET['aset']:'';
  
    $where = " WHERE nama_aset_pemberhentian IS NULL ";
  
    if($startdate != null && $startdate != ""){
      $where .= " AND tanggal_jurnal BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
    }
  
    if($aset != null && $aset != ""){
      $where .= " AND nama_aset LIKE '%".$aset."%' ";
    }
  
    $queryIndex = "SELECT *,COALESCE ( total_aset - total_penyusutan, 0 ) AS nilai_sisa_aset 
    FROM
      (SELECT a.tgl AS tanggal_jurnal,SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' disusutkan ', - 1 ), ' durasi', 1 ) AS nama_aset,SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' penyusutan ', - 1 ), ' Bulan', 1 ) AS durasi_penyusutan,b.nama_akun AS nama_akun_aset,b.debet AS total_aset FROM cron_jurnal a LEFT JOIN cron_jurnal_detail b ON a.`id` = b.`id_parent` WHERE a.`status` = 'PEMBELIAN ASET' AND b.nama_akun LIKE 'Aset Tetap - %' AND a.deleted = 0 
      ) AS a
      LEFT JOIN (SELECT SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, 'Penyusutan ', - 1 ), ' ke ', 1 ) AS nama_aset_penyusutan,b.nama_akun,SUM( b.kredit ) AS total_penyusutan FROM cron_jurnal a LEFT JOIN cron_jurnal_detail b ON a.`id` = b.`id_parent` WHERE a.`status` = 'PENYUSUTAN ASET' AND b.nama_akun LIKE 'Akumulasi Depresiasi & Amortisasi - %' AND DATE ( tgl ) <= CURDATE() AND a.deleted = 0 GROUP BY nama_aset_penyusutan 
      ) AS b ON a.nama_aset = b.nama_aset_penyusutan
      LEFT JOIN (
      SELECT
        SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' Aset - ', - 1 ), ' Penyusutan ', 1 ) AS nama_aset_pemberhentian 
      FROM
        jurnal a 
      WHERE
        a.`status` = 'LIKUIDITAS ASET' 
        AND a.keterangan LIKE 'Likuidasi Aset - %' 
        AND a.deleted = 0 
      ) AS c ON TRIM( a.nama_aset )= TRIM( c.nama_aset_pemberhentian ) ";

    $q = $db->query($queryIndex.$where);
    $count = $q->rowCount();
  
    $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
    if ($page > $total_pages) $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
  
    $responce['page'] = $page;
    $responce['total'] = $total_pages;
    $responce['records'] = $count;
  
    $q = $db->query($queryIndex.$where."
      ORDER BY `".$sidx."` ".$sord."
      LIMIT ".$start.", ".$limit
    );
  
    $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
  
    $i = 0;
    foreach($data1 as $line){
      $delete = $allow_delete ? '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/Transaksi_acc/penyusutan.php?action=cancel_aset&sisa='.($line['total_aset']-$line['total_penyusutan']).'&aset='.$line['nama_aset'].'\',\'table_penyusutan\')" href="javascript:void(0);">Likuidasi</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Likuidasi</a>';
  
      $hapus = $allow_delete ? '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/Transaksi_acc/penyusutan.php?action=passhapus&nama_aset='.$line['nama_aset'].'\',\'table_penyusutan\')" href="javascript:;">Hapus</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Hapus</a>';
      $responce['rows'][$i]['id']     = str_replace(' ','_',$line['nama_aset']);
      $responce['rows'][$i]['cell']   = array(
        $line['nama_aset'],
        $line['tanggal_jurnal'],
        $line['durasi_penyusutan'].' Bulan',
        number_format($line['total_aset']),
        number_format($line['total_penyusutan']),
        number_format($line['total_aset']-$line['total_penyusutan']),
        $delete,
        $hapus
      );
      $i++;
    }
  
    if(!isset($responce)){
      $responce = [];
    }
    echo json_encode($responce);
    exit;
  
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
    $aset     = str_replace('_',' ',$_GET['id']);
    
    $sql_sub = "SELECT COALESCE(j.no_jurnal, '') AS no_jurnal,c.tgl,c.keterangan,c.total_debet FROM cron_jurnal c LEFT JOIN jurnal j ON c.keterangan = j.keterangan WHERE c.tgl <= CURDATE() AND c.keterangan LIKE '%".$aset."%' AND c.`status`='PENYUSUTAN ASET' AND c.deleted=0";
  
    $query    = $db->query($sql_sub);
    $count    = $query->rowCount();
  
    $data1    = $query->fetchAll(PDO::FETCH_ASSOC);
  
    $i        = 0;

    $responce = new stdClass();

    foreach($data1 as $line){
      $responce->rows[$i]['id']   = $i;
      $responce->rows[$i]['cell'] = array(
        $i + 1,
        $line['no_jurnal'],
        $line['tgl'],
        $line['keterangan'],
        number_format($line['total_debet']),
      );

      $i ++;
    }

    if(!isset($responce)){
      $responce = [];
    }
    echo json_encode($responce);
    exit;
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'tambah_aset') {
    // 0. DATA
    $tanggalPembelian = $_POST['tanggal-pembelian-aset'];
    $uniqueAset = date('ymd', strtotime($tanggalPembelian)).date('His');
  
    $id_user = $_SESSION['user']['username'];
  
    $namaAset = $_POST['nama-aset'].' / '.$uniqueAset;
    $durasiPenyusutan = $_POST['durasi-penyusutan'];
    $akunPembelian = explode(':',$_POST['akun-pembelian-aset'])[0];
    $nilaiPembelian = $_POST['nilai-pembelian-aset'];
    $ppnPembelian = $_POST['ppn-pembelian-aset'];
    $nilaiPPN = $_POST['nilai-ppn-aset'];
    $keterangan = 'Pembelian Aset yang disusutkan '.$namaAset.' durasi penyusutan '.$durasiPenyusutan.' Bulan. Keterangan Manual :'.$_POST['keterangan-penyusutan'];
  
    $idparent="";
  
    if((int)$nilaiPembelian > 0){
      // 1. BUAT AKUN ASET DAN AKUMULASI
  
      $siapAkunAset = mysql_query("SELECT id, noakun, nama FROM mst_coa WHERE noakun = '01.02.00000' AND deleted=0");
      $akunAset = mysql_fetch_array($siapAkunAset);
  
      $buatAkunAset = mysql_query("SELECT TRIM(LEADING '0' FROM noakun) AS trimmed_noakun FROM det_coa WHERE noakun LIKE '%01.02.%' ORDER BY noakun DESC LIMIT 1;");
      $akunAsetBaru = mysql_fetch_array($buatAkunAset);
  
        $namaakun = $akunAset['nama'].' - '.$namaAset;
        $idakun = $akunAset['id'];
        $akun = (int)(explode('.',$akunAsetBaru['trimmed_noakun'])[2])+1;
        $user = $_SESSION['user']['username'];
  
      $insertAkunAset = $db->prepare("INSERT INTO det_coa VALUES(NULL, '$idakun', CONCAT('01.02.',LPAD('$akun', 5 ,'0')), '$namaakun', '$user', NOW())");
      $insertAkunAset->execute();
  
      $siapAkunAkumulasi = mysql_query("SELECT id, noakun, nama FROM mst_coa WHERE noakun = '01.10.00000' AND deleted=0");
      $akunAkumulasi = mysql_fetch_array($siapAkunAkumulasi);
  
      $buatAkunAkumulasi = mysql_query("SELECT TRIM(LEADING '0' FROM noakun) AS trimmed_noakun FROM det_coa WHERE noakun LIKE '%01.10.%' ORDER BY noakun DESC LIMIT 1;");
      $akunAkumulasiBaru = mysql_fetch_array($buatAkunAkumulasi);
  
        $namaakun = $akunAkumulasi['nama'].' - '.$namaAset;
        $idakun = $akunAkumulasi['id'];
        $akunAkumulasiNomor = (int)(explode('.',$akunAkumulasiBaru['trimmed_noakun'])[2])+1;
        $user = $_SESSION['user']['username'];
  
      $insertAkunAkumulasi = $db->prepare("INSERT INTO det_coa VALUES(NULL, '$idakun', CONCAT('01.10.',LPAD('$akunAkumulasiNomor', 5 ,'0')), '$namaakun', '$user', NOW())");
      $insertAkunAkumulasi->execute();
  
      // 2. INSERT JURNAL PEMBELIAN
  
      $jurnalMasterPembelian = $db->prepare("INSERT INTO `cron_jurnal` (`tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('".$tanggalPembelian."', '".$keterangan."', '".((int)$nilaiPembelian+(int)$nilaiPPN)."', '".((int)$nilaiPembelian+(int)$nilaiPPN)."', '0', '".$id_user."', NOW(), 'PEMBELIAN ASET') ");
      $jurnalMasterPembelian->execute();
  
      $jurnalMasterPembelianID=mysql_fetch_array(mysql_query("SELECT id FROM `cron_jurnal` WHERE `total_kredit`='".((int)$nilaiPembelian+(int)$nilaiPPN)."' AND `keterangan`='".$keterangan."' AND user='".$id_user."' LIMIT 1"));
      $idparent=$jurnalMasterPembelianID['id'];
  
      $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE nama = '".$akunAset['nama']." - ".$namaAset."'"));
      $idakun=$ambilAkunAset['id'];
      $noakun=$ambilAkunAset['noakun'];
      $namaakun=$ambilAkunAset['nama'];
  
      $idakunPembelian= "";
      $noakunPembelian= "";
      $namaakunPembelian= "";
  
      $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun = '$akunPembelian'"));
      if(mysql_num_rows(mysql_query("SELECT * FROM det_coa WHERE noakun = '$akunPembelian'")) == '0'){
        $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE noakun = '$akunPembelian' AND deleted=0"));
        $idakunPembelian     = $ambilAkunAkumulasi['id'];
        $noakunPembelian     = $ambilAkunAkumulasi['noakun'];
        $namaakunPembelian   = $ambilAkunAkumulasi['nama'];
      } else {
        $idakunPembelian     = $ambilAkunAkumulasi['id'];
        $noakunPembelian     = $ambilAkunAkumulasi['noakun'];
        $namaakunPembelian   = $ambilAkunAkumulasi['nama'];
      }
  
      $sql_detail="INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakunPembelian','$noakunPembelian', '$namaakunPembelian','Detail','0','".((int)$nilaiPembelian+(int)$nilaiPPN)."','','0', '$id_user','PENDING',NOW())";
      mysql_query($sql_detail) or die (mysql_error());
  
      $sql_detail="INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','".$nilaiPembelian."','0','','0', '$id_user','PENDING',NOW())";
      mysql_query($sql_detail) or die (mysql_error());
  
      if($nilaiPPN != '0' AND $nilaiPPN != ""){
        $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE id='57'"));
        $idakun=$ambilAkunAset['id'];
        $noakun=$ambilAkunAset['noakun'];
        $namaakun=$ambilAkunAset['nama'];
  
        $sql_detail="INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Parent','".$nilaiPPN."','0','','0', '$id_user','PENDING',NOW())";
        mysql_query($sql_detail) or die (mysql_error());
      }
  
      // 3. INSERT JURNAL PENYUSUTAN SEBANYAK N BULAN
  
      $ambilAkunBebanPenyusutan=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE noakun='06.19.00000'"));
      $idakunBebanPenyusutan=$ambilAkunBebanPenyusutan['id'];
      $noakunBebanPenyusutan=$ambilAkunBebanPenyusutan['noakun'];
      $namaakunBebanPenyusutan=$ambilAkunBebanPenyusutan['nama'];
  
      $startingMonth = date('n', strtotime($tanggalPembelian));
      $startingYear = date('Y', strtotime($tanggalPembelian));
      $akumulasiYear = date('Y', strtotime($tanggalPembelian));
  
      $nilaiTotal = ((int)$nilaiPembelian);
      $satuanPenyusutan = round((float) str_replace(',', '', number_format($nilaiPembelian / $durasiPenyusutan, 2)));
  
      $lastDateOfMonth = "";
  
      for ($i = 0; $i < $durasiPenyusutan; $i++) {
        $lastDateOfMonth = date('Y-m-t', strtotime($startingYear."-".$startingMonth."-01"));
  
        if($i+1 == $durasiPenyusutan){
          $satuanPenyusutan = $nilaiTotal;
        } else {
          $nilaiTotal -= $satuanPenyusutan ;
        }
  
        // 3.5 INSERT JURNAL PENYUSUTAN
  
        $jurnalMasterPenyusutan = $db->prepare("INSERT INTO `cron_jurnal` (`tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$lastDateOfMonth', 'Penyusutan ".$namaAset." ke ".($i+1)." dari ".($durasiPenyusutan)." Bulan', '".$satuanPenyusutan."', '".$satuanPenyusutan."', '0', '".$id_user."', NOW(), 'PENYUSUTAN ASET') ");
        $jurnalMasterPenyusutan->execute();
  
        $jurnalMasterPenyusutanID=mysql_fetch_array(mysql_query("SELECT id FROM `cron_jurnal` WHERE `total_debet`='$satuanPenyusutan' AND keterangan = 'Penyusutan ".$namaAset." ke ".($i+1)." dari ".($durasiPenyusutan)." Bulan' AND user = '".$id_user."' LIMIT 1"));
        $idparentPenyusutan=$jurnalMasterPenyusutanID['id'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunBebanPenyusutan','$noakunBebanPenyusutan','$namaakunBebanPenyusutan','Parent','".$satuanPenyusutan."','0','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun=CONCAT('01.10.',LPAD('$akunAkumulasiNomor', 5 ,'0'))"));
        $idakunAkumulasi=$ambilAkunAkumulasi['id'];
        $noakunAkumulasi=$ambilAkunAkumulasi['noakun'];
        $namaakunAkumulasi=$ambilAkunAkumulasi['nama'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','0','".$satuanPenyusutan."','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        if ($startingMonth == 12) {
          $startingMonth = 1;
          $startingYear ++;
        } else {
          $startingMonth++;
        }
      }
  
  
      // 4. INSERT JURNAL AKUMULASI DI TIAP AKHIR TAHUN
  
      $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun=CONCAT('01.10.',LPAD('$akunAkumulasiNomor', 5 ,'0'))"));
        $idakunAkumulasi=$ambilAkunAkumulasi['id'];
        $noakunAkumulasi=$ambilAkunAkumulasi['noakun'];
        $namaakunAkumulasi=$ambilAkunAkumulasi['nama'];
  
        $loopingAkumulasi = $akumulasiYear+$durasiPenyusutan/12;
  
      for ($i = $akumulasiYear; $i <= $loopingAkumulasi; $i++) {
  
        $getSumAkumulasi = mysql_query("SELECT SUM(total_debet) AS total_akumulasi FROM cron_jurnal WHERE keterangan LIKE 'Penyusutan ".$namaAset." ke%' AND tgl BETWEEN '".$i."-01-01' AND '".$i."-12-31' AND deleted=0");
  
        $sumAkumulasi = mysql_fetch_array($getSumAkumulasi);
        $sumAkumulasiValue=$sumAkumulasi['total_akumulasi'];
  
        $jurnalMasterAkumulasi = $db->prepare("INSERT INTO `cron_jurnal` (`tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('".$i."-12-31', 'Akumulasi Penyusutan Aset ".$namaAset." Tahun ".$i."', '".$sumAkumulasiValue."', '".$sumAkumulasiValue."', '0', '".$id_user."', NOW(), 'AKUMULASI PENYUSUTAN') ");
        $jurnalMasterAkumulasi->execute();
  
        $jurnalMasterAkumulasiID=mysql_fetch_array(mysql_query("SELECT id FROM `cron_jurnal` WHERE keterangan = 'Akumulasi Penyusutan Aset ".$namaAset." Tahun ".$i."' AND user = '".$id_user."' LIMIT 1"));
        $idparent=$jurnalMasterAkumulasiID['id'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','".$sumAkumulasiValue."','0','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE nama = '".$akunAset['nama']." - ".$namaAset."'"));
        $idakun=$ambilAkunAset['id'];
        $noakun=$ambilAkunAset['noakun'];
        $namaakun=$ambilAkunAset['nama'];
  
        $sql_detail="INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','0','".$sumAkumulasiValue."','','0', '$id_user','PENDING',NOW())";
        mysql_query($sql_detail) or die (mysql_error());
      }
    }
  
  
    $stmt = $db->prepare("SELECT * FROM cron_jurnal WHERE id='".$idparent."'");
    $stmt->execute();
  
    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Aset berhasil ditambahkan';
    } else if((int)$nilaiPembelian == 0){
      $r['stat'] = 0; $r['message'] = 'Aset gagal ditambahkan, Nilai pembelian tidak boleh nol';
    } else {
      $r['stat'] = 0; $r['message'] = 'Aset gagal ditambahkan';
    }
  
    echo json_encode($r);
    exit();
  
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'cancel_aset') {
    include 'penyusutan_cancel_form.php';
    exit();
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'cancel_aset_proses') {
    // 1. PERSIAPAN DATA
    $id_user = $_SESSION['user']['username'];
    $namaAset=$_POST['nama-aset'];
    $tanggalPemberhentian=$_POST['tanggal-pemberhentian-aset'];
    $akunPemberhentian=explode(':',$_POST['akun-pemberhentian-aset'])[0];
    $nilaiPemberhentian=$_POST['nilai-pemberhentian-aset'];
    $ppnPemberhentian=$_POST['nilai-ppn-pemberhentian'];
    $nilaiSisaAset=$_POST['nilai-sisa-aset'];
  
    // 2. DELETE JURNAL PENYUSUTAN MULAI BULAN PEMBERHENTIAN
    $queryDeletePenyusutan = mysql_query("UPDATE jurnal SET deleted=1 WHERE keterangan LIKE '%".$namaAset."%' AND `status`='PENYUSUTAN ASET' AND DATE(tgl) >= DATE('".$tanggalPemberhentian."')");
  
    // 3. DELETE JURNAL AKUMULASI MULAI TAHUN PEMBERHENTIAN
    $queryDeleteAkumulasi = mysql_query("UPDATE jurnal SET deleted=1 WHERE keterangan LIKE '%".$namaAset."%' AND `status`='AKUMULASI PENYUSUTAN' AND DATE(tgl) >= DATE('".$tanggalPemberhentian."')");
  
    // 4. MASUKAN JURNAL CANCEL
    $nomorJurnalCancel = '';
    $query = mysql_query("SELECT DISTINCT CONCAT(CAST(no_jurnal AS UNSIGNED) + 1) AS nomor FROM jurnal WHERE tgl = '".$tanggalPemberhentian."' ORDER BY no_jurnal DESC LIMIT 1");
  
    if(mysql_num_rows($query) > 0){
    }else{
      $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR('".$tanggalPemberhentian."'),3), IF(LENGTH(MONTH('".$tanggalPemberhentian."'))=1, CONCAT('0',MONTH('".$tanggalPemberhentian."')),MONTH('".$tanggalPemberhentian."')), IF(LENGTH(DAY('".$tanggalPemberhentian."'))=1, CONCAT('0',DAY('".$tanggalPemberhentian."')),DAY('".$tanggalPemberhentian."')), '00001') as nomor ");
    }
  
    $q = mysql_fetch_array($query);
    $nomorJurnalCancel=$q['nomor'];
  
    $queryGetLast = mysql_query("SELECT *, COALESCE(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(keterangan, 'ke', -1), 'dari', 1)),0) AS last_month, COALESCE(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(keterangan, 'dari', -1), 'Bulan', 1)),0) AS total_month FROM jurnal WHERE keterangan LIKE '%".$namaAset."%' AND MONTH(tgl)=MONTH('".$tanggalPemberhentian."') AND `status`='PENYUSUTAN ASET' ORDER BY tgl DESC LIMIT 1");
  
    $lastPenyusutan = mysql_fetch_array($queryGetLast);
  
    $jurnalMasterCancel = $db->prepare("INSERT INTO `jurnal` (`no_jurnal`, `tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$nomorJurnalCancel', '".$tanggalPemberhentian."', 'Likuidasi Aset - ".$namaAset." Penyusutan ".$lastPenyusutan['last_month']." dari ".$lastPenyusutan['total_month']."', '".$nilaiPemberhentian."', '".$nilaiPemberhentian."', '0', '".$id_user."', NOW(), 'LIKUIDITAS ASET') ");
    $jurnalMasterCancel->execute();
  
    $jurnalMasterCancelID=mysql_fetch_array(mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$nomorJurnalCancel' AND keterangan = 'Likuidasi Aset - ".$namaAset." Penyusutan ".$lastPenyusutan['last_month']." dari ".$lastPenyusutan['total_month']."' AND user = '".$id_user."' LIMIT 1"));
    $idparent=$jurnalMasterCancelID['id'];
  
    // PERSIAPAN DATA DETAIL
  
    $ambilAkunPemberhentian=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun = '$akunPemberhentian'"));
    if(mysql_num_rows(mysql_query("SELECT * FROM det_coa WHERE noakun = '$akunPemberhentian'")) == '0'){
      $ambilAkunPemberhentian=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE noakun = '$akunPemberhentian' AND deleted=0"));
      $idAkunPemberhentian     = $ambilAkunPemberhentian['id'];
      $noAkunPemberhentian     = $ambilAkunPemberhentian['noakun'];
      $namaAkunPemberhentian   = $ambilAkunPemberhentian['nama'];
    } else {
      $idAkunPemberhentian     = $ambilAkunPemberhentian['id'];
      $noAkunPemberhentian     = $ambilAkunPemberhentian['noakun'];
      $namaAkunPemberhentian   = $ambilAkunPemberhentian['nama'];
    }
  
    $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE TRIM(nama) = 'Aset Tetap - ".$namaAset."'"));
    $idAkunAset=$ambilAkunAset['id'];
    $noAkunAset=$ambilAkunAset['noakun'];
    $namaAkunAset=$ambilAkunAset['nama'];
  
    // INSERT JURNAL DETAIL CANCEL
  
    $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idAkunPemberhentian','$noAkunPemberhentian','$namaAkunPemberhentian','Detail','".$nilaiPemberhentian."','0','','0', '$id_user',NOW())";
    mysql_query($sql_detail) or die (mysql_error());
  
    if($ppnPemberhentian != '0' AND $ppnPemberhentian != ""){
      $ambilAkunPPN=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE id='57'"));
      $idakun=$ambilAkunPPN['id'];
      $noakun=$ambilAkunPPN['noakun'];
      $namaakun=$ambilAkunPPN['nama'];
  
      $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Parent','0','".$ppnPemberhentian."','','0', '$id_user',NOW())";
      mysql_query($sql_detail) or die (mysql_error());
    }
  
    $stmt = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idAkunAset','$noAkunAset','$namaAkunAset','Detail','0','".((float)$nilaiSisaAset)."','','0', '$id_user',NOW())");
    $stmt->execute();
  
    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Berhasil memberhentikan aset';
    } else {
      $r['stat'] = 0; $r['message'] = 'Gagal memberhentikan aset';
    }
  
    echo json_encode($r);
    exit();
  
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'passhapus') {
    include 'penyusutan_passhapus.php';exit();
      exit;
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'pr  ocess_passhapus') {
    //cek apakah pass sama atau tidak
    $stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('".$_POST['pass_jm_edit']."') AND (user_id=3 OR user_id=13)");
    $stmt->execute();
  
    $affected_rows = $stmt->rowCount();
    if ($affected_rows > 0) {
      $r['stat'] = 1; $r['message'] = 'Success';
      $stmt = $db->prepare("UPDATE `jurnal` SET `deleted`=1 WHERE keterangan LIKE '%".$_POST['nama_aset']."%'");
      $stmt->execute();
      $stmt = $db->prepare("UPDATE cron_jurnal SET `deleted`=1 WHERE keterangan LIKE '%".$_POST['nama_aset']."%'");
      $stmt->execute();
      $stmt = $db->prepare("DELETE FROM `det_coa` WHERE nama LIKE '%".$_POST['nama_aset']."%'");
      $stmt->execute();
    }
    else {
      $r['stat'] = 0; 
      $r['message'] = 'Failed';
    }
    echo json_encode($r);
    exit;
} else if(isset($_GET['action']) && strtolower($_GET['action'] == 'tambah_penyusutan')) {
  
    $uniqueAset = date('ymd', strtotime($_POST['tanggal-pembelian-aset'])).date('His');
    $id_user = $_SESSION['user']['username'];
    $nama_aset = '';
  
    // check data apakah dari invoice atau manual
    if (preg_match('/.+ - .+ : .+/', $_POST['nama-aset'])) {
      $namaAset = explode(" - ",explode(" : ",$_POST['nama-aset'])[0])[1] . " / " .$uniqueAset;
    } else {
      $namaAset = $_POST['nama-aset'].' / '.$uniqueAset;
    }
  
    $tanggalPembelian = $_POST['tanggal-pembelian-aset'];
    $durasiPenyusutan = $_POST['durasi-penyusutan'];
    $nilaiPembelian = $_POST['nilai-pembelian-aset'];
    $ppnPembelian = $_POST['ppn-pembelian-aset'];
    $nilaiPPN = $_POST['nilai-ppn-aset'];
    $keterangan = 'Pembelian Aset yang disusutkan '.$namaAset.' durasi penyusutan '.$durasiPenyusutan.' Bulan. Keterangan Manual :'.$_POST['keterangan-penyusutan'];
  
    if ((int)$nilaiPembelian > 0) {
      
      // get nomor akun dan master data aset
  
      $qAset = mysql_query(
        "SELECT CONCAT(SUBSTRING(dc.noakun, 1, 6), LPAD(CAST(SUBSTRING_INDEX(dc.noakun, '.', -1) AS UNSIGNED) + 1, 5, '0')) AS dc_acc,
        mc.id, 
        mc.noakun, 
        mc.nama
        FROM det_coa dc
        JOIN mst_coa mc ON dc.id_parent = mc.id
        WHERE dc.noakun LIKE '01.02.%' ORDER BY dc.noakun DESC LIMIT 1"
      ); 
      $aset = mysql_fetch_array($qAset);
      
      // insert akun aset akumulasi
        $namaakun = $aset['nama'].' - '.$namaAset;
        $idakun = $aset['id'];
        $akun = $aset['dc_acc'];
        $user = $_SESSION['user']['username'];
  
      $insertAkun = $db->prepare("INSERT INTO det_coa VALUES(NULL,?,?,?,?,NOW())");
      $insertAkun->execute([$idakun,$akun,$namaakun,$user]);
  
      $akumulasi = mysql_fetch_array(
        mysql_query(
          "SELECT CONCAT(SUBSTRING(dc.noakun,1,6),LPAD(CAST(SUBSTRING_INDEX(dc.noakun,'.',-1) as UNSIGNED) + 1,5,'0')) as dc_acc,mc.id, mc.noakun, mc.nama 
              FROM det_coa dc 
              JOIN mst_coa mc ON dc.id_parent = mc.id 
            WHERE dc.noakun like '01.10.%' ORDER BY dc.noakun DESC LIMIT 1;"
          )
      );
  
  
        $namaakun = $akumulasi['nama'].' - '.$namaAset;
        $idakun = $akumulasi['id'];
        $akunAkumulasiNomor = $akumulasi['dc_acc'];
        $user = $_SESSION['user']['username'];
  
      $insertAkunAkumulasi = $db->prepare("INSERT INTO det_coa VALUES(NULL, ?, ?, ?, ?, NOW())");
      $insertAkunAkumulasi->execute([$idakun,$akunAkumulasiNomor,$namaakun,$user]);
  
      // prepare data 
  
      $ambilAkunBebanPenyusutan=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE noakun='06.19.00000'"));
      $idakunBebanPenyusutan=$ambilAkunBebanPenyusutan['id'];
      $noakunBebanPenyusutan=$ambilAkunBebanPenyusutan['noakun'];
      $namaakunBebanPenyusutan=$ambilAkunBebanPenyusutan['nama'];
  
      $startingMonth = date('n', strtotime($tanggalPembelian));
      $startingYear = date('Y', strtotime($tanggalPembelian));
      $akumulasiYear = date('Y', strtotime($tanggalPembelian));
  
      $nilaiTotal = ((int)$nilaiPembelian);
      $satuanPenyusutan = round((float) str_replace(',', '', number_format($nilaiPembelian / $durasiPenyusutan, 2)));
  
      $lastDateOfMonth = "";
  
      // insert data penyusutan sebanyak n
      for ($i = 0; $i < $durasiPenyusutan; $i++) {
        $lastDateOfMonth = date('Y-m-t', strtotime($startingYear."-".$startingMonth."-01"));
  
        if($i+1 == $durasiPenyusutan){
          $satuanPenyusutan = $nilaiTotal;
        } else {
          $nilaiTotal -= $satuanPenyusutan ;
        }
  
        $jurnalMasterPenyusutan = $db->prepare("INSERT INTO `cron_jurnal` (`tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$lastDateOfMonth', 'Penyusutan ".$namaAset." ke ".($i+1)." dari ".($durasiPenyusutan)." Bulan', '".$satuanPenyusutan."', '".$satuanPenyusutan."', '0', '".$id_user."', NOW(), 'PENYUSUTAN ASET') ");
        $jurnalMasterPenyusutan->execute();
  
        $jurnalMasterPenyusutanID=mysql_fetch_array(mysql_query("SELECT id FROM `cron_jurnal` WHERE keterangan = 'Penyusutan ".$namaAset." ke ".($i+1)." dari ".($durasiPenyusutan)." Bulan' AND user = '".$id_user."' LIMIT 1"));
        $idparentPenyusutan=$jurnalMasterPenyusutanID['id'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunBebanPenyusutan','$noakunBebanPenyusutan','$namaakunBebanPenyusutan','Parent','".$satuanPenyusutan."','0','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun=CONCAT('01.10.',LPAD('$akunAkumulasiNomor', 5 ,'0'))"));
        $idakunAkumulasi=$ambilAkunAkumulasi['id'];
        $noakunAkumulasi=$ambilAkunAkumulasi['noakun'];
        $namaakunAkumulasi=$ambilAkunAkumulasi['nama'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','0','".$satuanPenyusutan."','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        if ($startingMonth == 12) {
          $startingMonth = 1;
          $startingYear ++;
        } else {
          $startingMonth++;
        }
      }
  
      //  insert jurnal akumulasi tiap tahun
  
      $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun='$akunAkumulasiNomor'"));
        $idakunAkumulasi=$ambilAkunAkumulasi['id'];
        $noakunAkumulasi=$ambilAkunAkumulasi['noakun'];
        $namaakunAkumulasi=$ambilAkunAkumulasi['nama'];
  
        $loopingAkumulasi = $akumulasiYear+$durasiPenyusutan/12;
  
      for ($i = $akumulasiYear; $i <= $loopingAkumulasi; $i++) {
        $getSumAkumulasi = mysql_query("SELECT SUM(total_debet) AS total_akumulasi FROM cron_jurnal WHERE keterangan LIKE 'Penyusutan ".$namaAset." ke%' AND tgl BETWEEN '".$i."-01-01' AND '".$i."-12-31' AND deleted=0");
  
        $sumAkumulasi = mysql_fetch_array($getSumAkumulasi);
        $sumAkumulasiValue=$sumAkumulasi['total_akumulasi'];
  
        $jurnalMasterAkumulasi = $db->prepare("INSERT INTO `cron_jurnal` (`tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('".$i."-12-31', 'Akumulasi Penyusutan Aset ".$namaAset." Tahun ".$i."', '".$sumAkumulasiValue."', '".$sumAkumulasiValue."', '0', '".$id_user."', NOW(), 'AKUMULASI PENYUSUTAN') ");
        $jurnalMasterAkumulasi->execute();
  
        $jurnalMasterAkumulasiID=mysql_fetch_array(mysql_query("SELECT id FROM `cron_jurnal` WHERE keterangan = 'Akumulasi Penyusutan Aset ".$namaAset." Tahun ".$i."' AND user = '".$id_user."' LIMIT 1"));
        $idparent=$jurnalMasterAkumulasiID['id'];
  
        $jurnalDetailPenyusutan = $db->prepare("INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','".$sumAkumulasiValue."','0','','0', '$id_user','PENDING',NOW())");
        $jurnalDetailPenyusutan->execute();
  
        $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE nama = '".$aset['nama']." - ".$namaAset."'"));
        $idakun=$ambilAkunAset['id'];
        $noakun=$ambilAkunAset['noakun'];
        $namaakun=$ambilAkunAset['nama'];
  
        $sql_detail="INSERT INTO cron_jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','0','".$sumAkumulasiValue."','','0', '$id_user','PENDING',NOW())";
        mysql_query($sql_detail) or die (mysql_error());
      }
    }
  
    if (preg_match('/.+ - .+ : .+/', $_POST['nama-aset'])) {
      $invoiceId = explode(" - ",$_POST['nama-aset'])[0];
      $updateInvoice = $db->prepare("UPDATE det_invoice SET data_qty = data_qty +1 WHERE id = ?");
      $updateInvoice->execute([$invoiceId]);
    }
  
  
    $stmt = $db->prepare("SELECT * FROM cron_jurnal WHERE id='".$idparent."'");
    $stmt->execute();
  
    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Aset berhasil ditambahkan';
    } else if((int)$nilaiPembelian == 0){
      $r['stat'] = 0; $r['message'] = 'Aset gagal ditambahkan, Nilai pembelian tidak boleh nol';
    } else {
      $r['stat'] = 0; $r['message'] = 'Aset gagal ditambahkan';
    }
  
    echo json_encode($r);
    exit();
}
  

?>

<script type='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<div class="ui-widget ui-form" style="margin-bottom:5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_ap" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Filter</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_penyusutan" name="startdate_penyusutan" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_penyusutan" name="enddate_penyusutan" readonly></td>
            <!-- <td> 
               Tipe Biaya 
              <select name="tipe" id="tipe">
                <option value="semua" selected>Semua</option>
                <option value="langsung">Biaya Langsung</option>
                <option value="tidak-langsung">Biaya Tidak Langsung</option>
              </select>
              &nbsp; 
            </td> -->
            <td><input type="text" id="aset_penyusutan" name="aset_penyusutan" />(Nama Aset)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadPenyusutan()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<div class="btn_box">
  <?php
    if($allow_add) :
  ?>
    <button class="btn btn-success" onclick="javascript:popup_form('<?= BASE_URL?>pages/Transaksi_acc/penyusutan_form.php', 'table_penyusutan')">Tambah Aset</button>
  <?php endif; ?>
</div>


<table id="table_penyusutan"></table>
<div id="pager_table_penyusutan"></div>

<script>
    $('#startdate_penyusutan').datepicker({
        dateFormat: "dd/mm/yy"
    });

    $('#enddate_penyusutan').datepicker({
        dateFormat: "dd/mm/yy"
    });

    $( "#startdate_penyusutan" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
    $( "#enddate_penyusutan" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );

    function gridReloadPenyusutan(){
    let startdate   = ($("#startdate_penyusutan").val());
		let enddate     = ($("#enddate_penyusutan").val());
		let aset        = $("#aset_penyusutan").val();

    let v_url       = '<?= BASE_URL ?>pages/Transaksi_acc/penyusutan.php?action=json&startdate='+startdate+'&enddate='+enddate+'&aset='+aset;
		jQuery("#table_penyusutan").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
    }

    $(document).ready(()=>{
    $('#table_penyusutan').jqGrid({
      url           : '<?= BASE_URL.'pages/Transaksi_acc/penyusutan.php?action=json'?>',
      datatype      : 'json',
      colNames      : ['Nama Aset','Tanggal Pembelian', 'Durasi Penyusutan', 'Nilai Beli DPP', 'Total Penyusutan', 'Nilai Sisa Aset', 'Likuidasi', 'Hapus'],
      colModel      : [
        {name: 'nama_aset', index: 'nama_aset', align: 'left', width: 70, searchoptions: {sopt: ['cn']}},
        {name:'tanggal_pembelian_aset', index: 'tanggal_pembelian_aset', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'durasi_penyusutan', index: 'durasi_penyusutan', align: 'center', width: 20, formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions:{sopt: ['cn']}},
        {name: 'durasi_penyusutan', index: 'durasi_penyusutan', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'nilai_beli_aset', index: 'nilai_beli_aset', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'nilai_sisa_aset', index: 'nilai_sisa_aset', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'cancel', index: 'cancel', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'hapus', index: 'hapus', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [20, 1000],
      pager         : '#pager_table_penyusutan',
      sortname      : 'nilai_sisa_aset',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Aktiva",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/Transaksi_acc/penyusutan.php?action=json_sub'?>',
      subGridModel  : [
        {
          name  : ['No','Nomor Jurnal','Tanggal Penyusutan','Keterangan','Nilai Penyusutan'],
          width : [30,80,80,300,100],
          align : ['center','center','center','left','right'],
        }
      ],
    });
    $('#table_penyusutan').jqGrid('navGrid', '#pager_table_penyusutan', {edit:false, add:false, del:false, search:false});
  });

</script>