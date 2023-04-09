<?php

require_once '../../include/config.php';
include "../../include/koneksi.php";

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, OnlineCredit, $group_acess);
$allow_post = is_show_menu(POST_POLICY, OnlineCredit, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, OnlineCredit, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  if(!$sidx) $sidx=1;

  if(isset($_GET['nomor_akun']) && $_GET['nomor_akun'] != ''){
  $sql_arcr = "SELECT a.no_akun, a.nama_akun, b.nama, b.type, SUM(a.debet) as total_piutang, SUM(a.kredit) as total_pembayaran, (SUM(a.debet) - SUM(a.kredit)) as sisa_piutang FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON c.id=a.id_parent WHERE DATE(c.tgl) > '2023-01-01' AND a.`nama_akun` LIKE 'Piutang OLN - %' AND a.`no_akun` LIKE '%".$_GET['nomor_akun']."%' GROUP BY a.`no_akun` HAVING (SUM(a.debet) - SUM(a.kredit)) > 0 ";
  }
  else{
    $sql_arcr = "SELECT a.no_akun, a.nama_akun, b.nama, b.type, SUM(a.debet) as total_piutang, SUM(a.kredit) as total_pembayaran, (SUM(a.debet) - SUM(a.kredit)) as sisa_piutang FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON c.id=a.id_parent WHERE DATE(c.tgl) > '2023-01-01' AND a.`nama_akun` LIKE 'Piutang OLN - %' GROUP BY a.`no_akun` HAVING (SUM(a.debet) - SUM(a.kredit)) > 0 ";
  }

  $q = $db->query($sql_arcr);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start<0) $start = 0;

  $q = $db->query($sql_arcr." 
    ORDER BY `".$sidx."` ".$sord." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;

  $total_piutang = 0; $total_pembayaran = 0; $sisa_piutang = 0;

  $i=0;
  foreach($data1 as $line){
    if($allow_post){
      $pay = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_online/trolnarcredit_pay.php?no_akun='.$line['no_akun'].'&sisa_piutang='.$line['sisa_piutang'].'\',\'table_trolnarcr\')" href="javascript:void(0);">Pay</a>';
    }
    else{
      $pay = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Pay</a>';
    }

    $detail = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_online/trolnarcredit_excel.php?no_akun='.$line['no_akun'].'\')" href="javascript:void(0)">Detail</a>';

    $responce['rows'][$i]['id']     = $line['no_akun'];
    $responce['rows'][$i]['cell']   = array(
      $line['no_akun'],
      $line['nama_akun'],
      $line['type'],
      number_format($line['total_piutang'],0),
      number_format($line['total_pembayaran'],0),
      number_format($line['sisa_piutang'],0),
      $pay,
      $detail
    );

    $total_piutang += $line['total_piutang'];
    $total_pembayaran += $line['total_pembayaran'];
    $sisa_piutang += $line['sisa_piutang'];

    $i++;
  }

  $responce['userdata']['total_piutang'] = number_format($total_piutang,0);
  $responce['userdata']['total_pembayaran'] = number_format($total_pembayaran,0);
  $responce['userdata']['sisa_piutang'] = number_format($sisa_piutang,0);

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'pembayaran'){
  $id_user=$_SESSION['user']['username'];
  
  $masterNo = '';
  $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor FROM jurnal ORDER BY id DESC LIMIT 1");
  if(mysql_num_rows($query) == '1'){
  }else{
    $query = mysql_query("select CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
  }

  $q = mysql_fetch_array($query);
  $masterNo=$q['nomor'];

  $akun_get=mysql_fetch_array( mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `noakun`='".$_POST['no_akun']."' LIMIT 1"));
  $idakun=$akun_get['id'];
  $noakun=$akun_get['noakun'];
  $namaakun=$akun_get['nama'];

  $date_day = explode('/', $_POST['date_arcrpay'])[0];
  $date_month = explode('/', $_POST['date_arcrpay'])[1];
  $date_year = explode('/', $_POST['date_arcrpay'])[2];

  $date_formatted = $date_year.'-'.$date_month.'-'.$date_day;

  $tanggal_jurnal = date_format(date_create($date_formatted) ,"Y-m-d");

  $sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo','".$tanggal_jurnal."','Pembayaran ".$namaakun."','".$_POST['payment_arcrpay']."','".$_POST['payment_arcrpay']."','0','$id_user',NOW(),'AR') ";
  mysql_query($sql_master) or die (mysql_error());

  $parent_id=mysql_fetch_array( mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$masterNo' LIMIT 1"));
  $idparent=$parent_id['id'];

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','Detail','0','".$_POST['payment_arcrpay']."','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $nomor_akun_debet = explode(':', $_POST['akun_debet_arcrpay'])[0];

  $akun_debet_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1"));

  if(mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1")) === false){
    $akun_debet_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1"));
  }

  $idakun_debet=$akun_debet_get['id'];
  $noakun_debet=$akun_debet_get['noakun'];
  $namaakun_debet=$akun_debet_get['nama'];

  $stmt = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun_debet','$noakun_debet','$namaakun_debet','Detail','".$_POST['payment_arcrpay']."','0','','0', '$id_user',NOW())");
  $stmt->execute();

  $affected_rows = $stmt->rowCount();
  if($affected_rows > 0) {
    $r['stat'] = 1;
    $r['message'] = 'Success';
  }
  else {
    $r['stat'] = 0;
    $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
?>

<script type='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<div class="ui-widget ui-form" style="margin-bottom: 5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>
  <div class="ui-widget-content ui-corner-bottom ui-corner-all form-control">
    <form id="filter_trolnarcredit" class="ui-helper-clearfix">
      <table>
        <tr>
          <td style="text-align:left;">Nomor Akun</td>
          <td>Nama Akun</td>
          <td></td>
        </tr>
        <tr>
          <td><input value="" type="text" id="filter_no_akun" name="filter_no_akun" autocomplete="off"></td>
          <td><input value="" type="text" id="filter_nama_akun" name="filter_nama_akun" autocomplete="off" style="width:30em;"></td>
          <td><div class="ui-corner-all form-control"><button class="btn" onclick="gridReloadARCR()" type="button">Cari</button></div></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<table id="table_trolnarcr"></table>
<div id="pager_table_trolnarcr"></div>

<script>
  $(document).ready(function(){
    $('#table_trolnarcr').jqGrid({
      url           :'<?= BASE_URL.'pages/sales_online/trolnarcredit.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['Nomor Akun', 'Nama Akun', 'Type', 'Total Piutang', 'Total Pembayaran', 'Sisa Piutang', 'Pay', 'Detail'],
      colModel      : [
        {name: 'no_akun', index: 'nomor_akun', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
        {name: 'nama_akun', index: 'nama_akun', align: 'left', width: 95, searchoptions: {sopt: ['cn']}},
        {name: 'type', index: 'type', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
        {name: 'total_piutang', index: 'total_piutang', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'total_pembayaran', index: 'total_pembayaran', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'sisa_piutang', index: 'sisa_piutang', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'pay', index: 'pay', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
        {name: 'detail', index: 'detail', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10,20,30],
      pager         : '#pager_table_trolnarcr',
      sortname      : 'no_akun',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'asc',
      caption       : 'AR OLN Credit',
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      footerrow : true,
      userDataOnFooter : true,
    });
    $('#table_trolnarcr').jqGrid('navGrid', '#pager_table_trolnarcr', {edit:false, add:false, del:false, search:false});

    // * AUTO COMPLETE NOMOR AKUN
    $('#filter_no_akun').autocomplete("pages/sales_online/trolnarcredit_lookup_akun.php?action=getnomor", {width: 400});

    $('#filter_no_akun').result(function(event, data, formatted){
      let no_akun = $('#filter_nomor_akun').val();

      $.ajax({
        url         : 'pages/sales_online/trolnarcredit_lookup_detail.php?detail=getnama',
        dataType    : 'json',
        data        : 'nomor_akun='+formatted,
        success     : function(data){
          let nama_akun = data.nama_akun;
            $('#filter_nama_akun').val(nama_akun);
        }
      });
    });

    // * AUTO COMPLETE NAMA AKUN
    $('#filter_nama_akun').autocomplete("pages/sales_online/trolnarcredit_lookup_akun.php?action=getnama", {width: 400});

    $('#filter_nama_akun').result(function(event, data, formatted){
      let no_akun = $('#filter_nama_akun').val();

      $.ajax({
        url         : 'pages/sales_online/trolnarcredit_lookup_detail.php?detail=getnomor',
        dataType    : 'json',
        data        : 'nama_akun='+formatted,
        success     : function(data){
          let nomor_akun = data.no_akun;
            $('#filter_no_akun').val(nomor_akun);
        }
      });
    });
  });

  function gridReloadARCR(){
    let noakun      = $('#filter_no_akun').val();

    var v_url       = '<?php echo BASE_URL?>pages/sales_online/trolnarcredit.php?action=json&nomor_akun='+noakun;
    jQuery("#table_trolnarcr").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }
</script>