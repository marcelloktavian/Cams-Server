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
    $sql_arcr = "SELECT a.no_akun, a.nama_akun, b.nama, b.type, SUM(a.debet) as total_piutang, SUM(a.kredit) as total_pembayaran, (SUM(a.debet) - SUM(a.kredit)) as sisa_piutang FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON c.id=a.id_parent WHERE DATE(c.tgl) > '2023-01-01' AND a.`nama_akun` LIKE 'Piutang OLN - %' AND c.deleted=0 AND a.`no_akun` LIKE '%".$_GET['nomor_akun']."%' GROUP BY a.`no_akun` HAVING (SUM(a.debet) - SUM(a.kredit)) = 0 ";
  }
  else{
    $sql_arcr = "SELECT a.no_akun, a.nama_akun, b.nama, b.type, SUM(a.debet) as total_piutang, SUM(a.kredit) as total_pembayaran, (SUM(a.debet) - SUM(a.kredit)) as sisa_piutang FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON c.id=a.id_parent WHERE DATE(c.tgl) > '2023-01-01' AND c.deleted=0 AND a.`nama_akun` LIKE 'Piutang OLN - %' GROUP BY a.`no_akun` HAVING (SUM(a.debet) - SUM(a.kredit)) = 0 ";
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
    if(true){
      if($allow_post){
        $pay = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/report_acc/trolnarcredit_pay.php?no_akun='.$line['no_akun'].'&sisa_piutang='.$line['sisa_piutang'].'\',\'table_arsip_trolnarcr\')" href="javascript:void(0);">Pay</a>';
      }
      else{
        $pay = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Pay</a>';
      }
  
      $detail = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/report_acc/trolnarcredit_excel.php?no_akun='.$line['no_akun'].'\')" href="javascript:void(0)">Detail</a>';
  
      $responce['rows'][$i]['id']     = $line['no_akun'];
      $responce['rows'][$i]['cell']   = array(
        $line['no_akun'],
        $line['nama_akun'],
        $line['type'],
        number_format($line['total_piutang'],0),
        number_format($line['total_pembayaran'],0),
        number_format($line['sisa_piutang'],0)
      );
  
      $total_piutang += $line['total_piutang'];
      $total_pembayaran += $line['total_pembayaran'];
      $sisa_piutang += $line['sisa_piutang'];
  
      $i++;
    }
  }

  $responce['userdata']['total_piutang'] = number_format($total_piutang,0);
  $responce['userdata']['total_pembayaran'] = number_format($total_pembayaran,0);
  $responce['userdata']['sisa_piutang'] = number_format($sisa_piutang,0);

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $no_akun  = $_GET['id'];

  $sql_customer   = "SELECT * FROM det_coa WHERE noakun='$no_akun'";

  $q = mysql_fetch_array(mysql_query($sql_customer));
  $nama_akun = $q['nama'];

  $sql_sub        = "SELECT b.*, date_format(b.tgl, '%d/%m/%Y') AS tgl_jurnal FROM `jurnal_detail` a LEFT JOIN jurnal b ON a.`id_parent`=b.id WHERE a.`nama_akun` LIKE '".$nama_akun."%' AND kredit > 0";

  $query          = $db->query($sql_sub);
  $count          = $query->rowCount();

  $data1          = $query->fetchAll(PDO::FETCH_ASSOC);

  $i              = 0;
  $responce       = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $line['no_jurnal'],
      $line['tgl_jurnal'],
      $line['keterangan'],
      number_format($line['total_debet'], 0),
      number_format($line['total_kredit'], 0),
    );
    $i++;
  }
  echo json_encode($responce);
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
    <form id="filter_arsip_trolnarcredit" class="ui-helper-clearfix">
      <table>
        <tr>
          <td style="text-align:left;">Nomor Akun</td>
          <td>Nama Akun</td>
          <td></td>
        </tr>
        <tr>
          <td><input value="" type="text" id="filter_no_akun_arsip_arcr" name="filter_no_akun_arsip_arcr" autocomplete="off"></td>
          <td><input value="" type="text" id="filter_nama_akun_arsip_arcr" name="filter_nama_akun_arsip_arcr" autocomplete="off" style="width:30em;"></td>
          <td><div class="ui-corner-all form-control"><button class="btn" onclick="gridReloadArsipARCR()" type="button">Cari</button></div></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<table id="table_arsip_trolnarcr"></table>
<div id="pager_table_arsip_trolnarcr"></div>

<script>
  $(document).ready(function(){
    $('#table_arsip_trolnarcr').jqGrid({
      url           :'<?= BASE_URL.'pages/report_acc/arsip_trolnarcredit_rpt.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['Nomor Akun', 'Nama Akun', 'Type', 'Total Piutang', 'Total Pembayaran', 'Sisa Piutang'],
      colModel      : [
        {name: 'no_akun', index: 'nomor_akun', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
        {name: 'nama_akun', index: 'nama_akun', align: 'left', width: 95, searchoptions: {sopt: ['cn']}},
        {name: 'type', index: 'type', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
        {name: 'total_piutang', index: 'total_piutang', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'total_pembayaran', index: 'total_pembayaran', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'sisa_piutang', index: 'sisa_piutang', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10,20,30],
      pager         : '#pager_table_arsip_trolnarcr',
      sortname      : 'no_akun',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'asc',
      caption       : 'Arsip AR OLN Credit',
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      footerrow : true,
      userDataOnFooter : true,
      subGrid           : true,
      subGridUrl    : '<?= BASE_URL.'pages/report_acc/arsip_trolnarcredit_rpt.php?action=json_sub'?>',
      subGridModel  : [
        {
          name  : ['No','Nomor Jurnal','Tanggal Jurnal','Keterangan','Total Debit','Total Kredit'],
          width : [30,100,100,400,100,100],
          align : ['right','center','center','left','right','right'],
        }
      ],
    });
    $('#table_arsip_trolnarcr').jqGrid('navGrid', '#pager_table_arsip_trolnarcr', {edit:false, add:false, del:false, search:false});

    // * AUTO COMPLETE NOMOR AKUN
    $('#filter_no_akun_arsip_arcr').autocomplete("pages/report_acc/arsip_trolnarcredit_lookup_akun.php?action=getnomor", {width: 400});

    $('#filter_no_akun_arsip_arcr').result(function(event, data, formatted){
      let no_akun = $('#filter_nomor_akun_arsip_arcr').val();

      $.ajax({
        url         : 'pages/report_acc/arsip_trolnarcredit_lookup_detail.php?detail=getnama',
        dataType    : 'json',
        data        : 'nomor_akun='+formatted,
        success     : function(data){
          let nama_akun = data.nama_akun;
            $('#filter_nama_akun_arsip_arcr').val(nama_akun);
        }
      });
    });

    // * AUTO COMPLETE NAMA AKUN
    $('#filter_nama_akun_arsip_arcr').autocomplete("pages/report_acc/arsip_trolnarcredit_lookup_akun.php?action=getnama", {width: 400});

    $('#filter_nama_akun_arsip_arcr').result(function(event, data, formatted){
      let no_akun = $('#filter_nama_akun_arsip_arcr').val();

      $.ajax({
        url         : 'pages/report_acc/arsip_trolnarcredit_lookup_detail.php?detail=getnomor',
        dataType    : 'json',
        data        : 'nama_akun='+formatted,
        success     : function(data){
          let nomor_akun = data.no_akun;
            $('#filter_no_akun_arsip_arcr').val(nomor_akun);
        }
      });
    });
  });

  function gridReloadArsipARCR(){
    let noakun      = $('#filter_no_akun_arsip_arcr').val();

    var v_url       = '<?php echo BASE_URL?>pages/report_acc/arsip_trolnarcredit_rpt.php?action=json&nomor_akun='+noakun;
    jQuery("#table_arsip_trolnarcr").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }
</script>