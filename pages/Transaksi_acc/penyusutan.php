<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess    = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY, penyusutan, $group_acess);
$allow_delete   = is_show_menu(DELETE_POLICY, penyusutan, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page = isset($_GET['page'])?$_GET['page']:1;
  $limit = isset($_GET['rows'])?$_GET['rows']:10;
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'nojurnal';
  $sord = isset($_GET['sord'])?$_GET['sord']:''; 

  $startdate = isset($_GET['startdate'])?$_GET['startdate']:DATE('Y-m-d');
  $enddate = isset($_GET['enddate'])?$_GET['enddate']:DATE('Y-m-d');
  $aset = isset($_GET['aset'])?$_GET['aset']:'';

  $where = " WHERE tanggal_jurnal IS NOT NULL ";

  if($startdate != null && $startdate != ""){
    $where .= " AND tgl_trans BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
  }

  if($aset != null && $aset != ""){
    $where .= " AND nama_aset LIKE '%".$aset."%' ";
  }

  $queryIndex = "SELECT *, total_aset-total_penyusutan AS nilai_sisa_aset FROM (
    SELECT a.tgl AS tanggal_jurnal, SUBSTRING_INDEX(SUBSTRING_INDEX(a.keterangan, ' disusutkan ', -1), ' durasi', 1) AS nama_aset, b.nama_akun AS nama_akun_aset, b.debet AS total_aset FROM jurnal a LEFT JOIN jurnal_detail b ON a.`id`=b.`id_parent` WHERE a.`status`='PEMBELIAN ASET' AND b.nama_akun LIKE 'Aset Tetap - %' AND a.deleted=0
  ) AS a LEFT JOIN (
    SELECT SUBSTRING_INDEX( SUBSTRING_INDEX(a.keterangan, 'Penyusutan ', - 1), ' ke ', 1 ) AS nama_aset_penyusutan, b.nama_akun, SUM(b.kredit) AS total_penyusutan FROM jurnal a LEFT JOIN jurnal_detail b ON a.`id` = b.`id_parent` WHERE a.`status` = 'PENYUSUTAN ASET' AND b.nama_akun LIKE 'Akumulasi Depresiasi & Amortisasi - %' AND a.deleted = 0 GROUP BY nama_aset_penyusutan
  ) AS b ON a.nama_aset=b.nama_aset_penyusutan ";

  $q = $db->query($queryIndex);
  $count = $q->rowCount();

  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $responce['page'] = $page;
  $responce['total'] = $total_pages;
  $responce['records'] = $count;

  $q = $db->query($queryIndex."
    ORDER BY `".$sidx."` ".$sord."
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach($data1 as $line){
    $delete = $allow_delete ? '<a onclick="javascript:custom_alert(\'Coming Soon\')" href="javascript:void(0);">Hapus Aset</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Hapus Aset</a>';

    $responce['rows'][$i]['id']     = $line['nama_aset'];
    $responce['rows'][$i]['cell']   = array(
      $line['nama_aset'],
      $line['tanggal_jurnal'],
      '',
      number_format($line['total_aset']),
      number_format($line['total_penyusutan']),
      number_format($line['nilai_sisa_aset']),
      $delete
    );
    $i++;
  }

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;

} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  //
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'tambah_aset') {
  // 0. DATA
  $uniqueAset = date('ymdhs');

  $id_user = $_SESSION['user']['username'];

  $namaAset = $_POST['nama-aset'].' / '.$uniqueAset;
  $tanggalPembelian = $_POST['tanggal-pembelian-aset'];
  $durasiPenyusutan = $_POST['durasi-penyusutan'];
  $akunPembelian = explode(':',$_POST['akun-pembelian-aset'])[0];
  $nilaiPembelian = $_POST['nilai-pembelian-aset'];
  $ppnPembelian = $_POST['ppn-pembelian-aset'];
  $nilaiPPN = $_POST['nilai-ppn-aset'];
  $keterangan = 'Pembelian Aset yang disusutkan '.$namaAset.' durasi penyusutan '.$durasiPenyusutan.' Bulan. Keterangan Manual :'.$_POST['keterangan-penyusutan'];

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
  $nomorJurnalPembelian = '';
  $query = mysql_query("SELECT DISTINCT CONCAT(CAST(no_jurnal AS UNSIGNED) + 1) AS nomor FROM jurnal WHERE tgl = '".$tanggalPembelian."' ORDER BY no_jurnal DESC LIMIT 1");

  if(mysql_num_rows($query) > 0){
  }else{
    $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR('".$tanggalPembelian."'),3), IF(LENGTH(MONTH('".$tanggalPembelian."'))=1, CONCAT('0',MONTH('".$tanggalPembelian."')),MONTH('".$tanggalPembelian."')), IF(LENGTH(DAY('".$tanggalPembelian."'))=1, CONCAT('0',DAY('".$tanggalPembelian."')),DAY('".$tanggalPembelian."')), '00001') as nomor ");
  }

  $q = mysql_fetch_array($query);
  $nomorJurnalPembelian=$q['nomor'];

  $jurnalMasterPembelian = $db->prepare("INSERT INTO `jurnal` (`no_jurnal`, `tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$nomorJurnalPembelian', '".$tanggalPembelian."', '".$keterangan."', '".((int)$nilaiPembelian+(int)$nilaiPPN)."', '".((int)$nilaiPembelian+(int)$nilaiPPN)."', '0', '".$id_user."', NOW(), 'PEMBELIAN ASET') ");
  $jurnalMasterPembelian->execute();

  $jurnalMasterPembelianID=mysql_fetch_array(mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$nomorJurnalPembelian' LIMIT 1"));
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

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakunPembelian','$noakunPembelian','$namaakunPembelian','Detail','0','".((int)$nilaiPembelian+(int)$nilaiPPN)."','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','".$nilaiPembelian."','0','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  if($nilaiPPN != '0' AND $nilaiPPN != ""){
    $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM mst_coa WHERE id='57'"));
    $idakun=$ambilAkunAset['id'];
    $noakun=$ambilAkunAset['noakun'];
    $namaakun=$ambilAkunAset['nama'];

    $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Parent','".$nilaiPPN."','0','','0', '$id_user',NOW())";
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
  $satuanPenyusutan = ($nilaiPembelian)/$durasiPenyusutan;

  $lastDateOfMonth = "";

  for ($i = 0; $i < $durasiPenyusutan; $i++) {
    $lastDateOfMonth = date('Y-m-t', strtotime($startingYear."-".$startingMonth."-01"));

    if($i+1 == $durasiPenyusutan){
      $satuanPenyusutan = $nilaiTotal;
    } else {
      $nilaiTotal -= $satuanPenyusutan ;
    }

    // 3.5 INSERT JURNAL PENYUSUTAN

    $nomorJurnalPenyusutan = '';
    $queryNomorJurnalPenyusutan = mysql_query("SELECT DISTINCT CONCAT(CAST(no_jurnal AS UNSIGNED) + 1) AS nomor FROM jurnal WHERE tgl = '".$lastDateOfMonth."' ORDER BY no_jurnal DESC LIMIT 1");
  
    if(mysql_num_rows($queryNomorJurnalPenyusutan) == '1'){
    }else{
      $queryNomorJurnalPenyusutan = mysql_query("SELECT CONCAT(SUBSTR(YEAR('".$lastDateOfMonth."'),3), IF(LENGTH(MONTH('".$lastDateOfMonth."'))=1, CONCAT('0',MONTH('".$lastDateOfMonth."')),MONTH('".$lastDateOfMonth."')), IF(LENGTH(DAY('".$lastDateOfMonth."'))=1, CONCAT('0',DAY('".$lastDateOfMonth."')),DAY('".$lastDateOfMonth."')), '00001') as nomor ");
    }
  
    $q = mysql_fetch_array($queryNomorJurnalPenyusutan);
    $nomorJurnalPenyusutan=$q['nomor'];

    $jurnalMasterPenyusutan = $db->prepare("INSERT INTO `jurnal` (`no_jurnal`, `tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$nomorJurnalPenyusutan', '$lastDateOfMonth', 'Penyusutan ".$namaAset." ke ".($i+1)." dari ".($durasiPenyusutan)." Bulan', '".$satuanPenyusutan."', '".$satuanPenyusutan."', '0', '".$id_user."', NOW(), 'PENYUSUTAN ASET') ");
    $jurnalMasterPenyusutan->execute();

    $jurnalMasterPenyusutanID=mysql_fetch_array(mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$nomorJurnalPenyusutan' LIMIT 1"));
    $idparentPenyusutan=$jurnalMasterPenyusutanID['id'];

    $jurnalDetailPenyusutan = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunBebanPenyusutan','$noakunBebanPenyusutan','$namaakunBebanPenyusutan','Parent','".$satuanPenyusutan."','0','','0', '$id_user',NOW())");
    $jurnalDetailPenyusutan->execute();

    $ambilAkunAkumulasi=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE noakun=CONCAT('01.10.',LPAD('$akunAkumulasiNomor', 5 ,'0'))"));
    $idakunAkumulasi=$ambilAkunAkumulasi['id'];
    $noakunAkumulasi=$ambilAkunAkumulasi['noakun'];
    $namaakunAkumulasi=$ambilAkunAkumulasi['nama'];

    $jurnalDetailPenyusutan = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparentPenyusutan','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','0','".$satuanPenyusutan."','','0', '$id_user',NOW())");
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

  for ($i = 0; $i < ceil($durasiPenyusutan/12); $i++) {
    $nomorJurnalAkumulasi = '';
    $query = mysql_query("SELECT DISTINCT CONCAT(CAST(no_jurnal AS UNSIGNED) + 1) AS nomor FROM jurnal WHERE tgl = '".$akumulasiYear."-12-31' ORDER BY no_jurnal DESC LIMIT 1");

    if(mysql_num_rows($query) > 0){
    }else{
      $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
    }

    $q = mysql_fetch_array($query);
    $nomorJurnalAkumulasi=$q['nomor'];

    $getSumAkumulasi = mysql_query("SELECT SUM(total_debet) AS total_akumulasi FROM jurnal WHERE keterangan LIKE 'Penyusutan ".$namaAset."%' AND tgl BETWEEN '".$akumulasiYear."-01-01' AND '".$akumulasiYear."-12-31' AND deleted=0");

    $sumAkumulasi = mysql_fetch_array($getSumAkumulasi);
    $sumAkumulasiValue=$sumAkumulasi['total_akumulasi'];

    $jurnalMasterAkumulasi = $db->prepare("INSERT INTO `jurnal` (`no_jurnal`, `tgl`, `keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`, `status`) VALUES ('$nomorJurnalAkumulasi', '".$akumulasiYear."-12-31', 'Akumulasi Penyusutan Aset ".$namaAset." Tahun ".$akumulasiYear."', '".$sumAkumulasiValue."', '".$sumAkumulasiValue."', '0', '".$id_user."', NOW(), 'AKUMULASI PENYUSUTAN') ");
    $jurnalMasterAkumulasi->execute();

    $jurnalMasterAkumulasiID=mysql_fetch_array(mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$nomorJurnalAkumulasi' LIMIT 1"));
    $idparent=$jurnalMasterAkumulasiID['id'];

    $jurnalDetailPenyusutan = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakunAkumulasi','$noakunAkumulasi','$namaakunAkumulasi','Detail','0','".$sumAkumulasiValue."','','0', '$id_user',NOW())");
    $jurnalDetailPenyusutan->execute();

    $ambilAkunAset=mysql_fetch_array( mysql_query("SELECT * FROM det_coa WHERE nama = '".$akunAset['nama']." - ".$namaAset."'"));
    $idakun=$ambilAkunAset['id'];
    $noakun=$ambilAkunAset['noakun'];
    $namaakun=$ambilAkunAset['nama'];

    $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','0','".$sumAkumulasiValue."','','0', '$id_user',NOW())";
    mysql_query($sql_detail) or die (mysql_error());

    $akumulasiYear ++;
  }

  // $stmt = $db->prepare("SELECT * FROM jurnal WHERE id='".$id_parent."'");
  // $stmt->execute(array(strtoupper($_POST['pemohon']), strtoupper($_POST['keterangan']), $_POST['id']));

  // $affected_rows = $stmt->rowCount();
  // if($affected_rows > 0){
  //   $r['stat'] = 1; $r['message'] = 'Success';
  // } else {
  //   $r['stat'] = 0; $r['message'] = 'Failed';
  // }
  echo json_encode('Success');
  exit;
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
      <label for="" class="ui-helper-reset label-control">Tanggal Pembelian</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_penyusutan" name="startdate_penyusutan" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_penyusutan" name="enddate_penyusutan" readonly></td>
            <td> Filter <input type="text" id="aset_penyusutan" name="aset_penyusutan" />(Nama Aset)</td>
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
  if($allow_add){?>
    <button class="btn btn-success" onclick="javascript:popup_form('<?= BASE_URL?>pages/Transaksi_acc/penyusutan_form.php', 'table_penyusutan')">Tambah Aset</button>
  <?php } ?>
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

    let v_ulr       = '<?= BASE_URL ?>pages/Transaksi_acc/penyusutan.php?action=json&startdate='+startdate+'&enddate='+enddate+'&aset='+aset;
		jQuery("#table_penyusutan").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(()=>{
    $('#table_penyusutan').jqGrid({
      url           : '<?= BASE_URL.'pages/Transaksi_acc/penyusutan.php?action=json'?>',
      datatype      : 'json',
      colNames      : ['Nama Aset','Tanggal Pembelian', 'Durasi Penyusutan', 'Nilai Beli DPP', 'Total Penyusutan', 'Nilai Sisa Aset', 'Hapus'],
      colModel      : [
        {name: 'nama_aset', index: 'nama_aset', align: 'left', width: 60, searchoptions: {sopt: ['cn']}},
        {name:'tanggal_pembelian_aset', index: 'tanggal_pembelian_aset', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'durasi_penyusutan', index: 'durasi_penyusutan', align: 'left', width: 30, searchoptions:{sopt: ['cn']}},
        {name: 'nilai_beli_aset', index: 'nilai_beli_aset', align: 'left', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'durasi_penyusutan', index: 'durasi_penyusutan', align: 'left', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'nilai_sisa_aset', index: 'nilai_sisa_aset', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
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
      caption       : "Penyusutan Aset",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
    });
    $('#table_penyusutan').jqGrid('navGrid', '#pager_table_penyusutan', {edit:false, add:false, del:false, search:false});
  });
</script>