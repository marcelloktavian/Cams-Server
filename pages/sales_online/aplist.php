<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, OnlineCredit, $group_acess);
$allow_post = is_show_menu(POST_POLICY, OnlineCredit, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, OnlineCredit, $group_acess);

if(isset($_GET['tgl_jto']) && strtolower($_GET['tgl_jto']) != ''){
  $tgl_jto = $_GET['tgl_jto'];
}
else{
  $tgl_jto = date("Y-m-d");
}

if(isset($_GET['supplier_filter']) && strtolower($_GET['supplier_filter']) != ''){
  $tgl_jto = $_GET['supplier_filter'];
}
else{
  $tgl_jto = "";
}

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  if(!$sidx) $sidx=1;

  if(isset($_GET['tgl_jto']) && $_GET['tgl_jto'] != ''){
    $sql_aplist = "SELECT x.id_supplier AS x_id_supplier,x.supplier AS x_supplier, x.bank AS x_bank, x.rekening AS x_rekening, x.total_hutang_jto AS x_total_hutang, y.id_supplier AS y_id_supplier, y.supplier AS y_supplier, y.bank AS y_bank, y.rekening AS y_rekening, y.total_hutang AS y_total_hutang FROM (SELECT a.id_supplier, a.supplier, b.bank, b.rekening, SUM(a.qty) AS `total_qty_jto`, SUM(a.total) AS `total_hutang_jto`, SUM(a.total_payment) AS `total_payment_jto`, SUM(a.total_remaining) AS `total_remaining_jto` FROM `mst_invoice` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id WHERE a. id IN (SELECT DISTINCT a.id_invoice FROM `det_ap` a LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` <= '".$_GET['tgl_jto']."' AND posting=1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$_GET['supplier_filter']."%' AND a.deleted=0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.id_supplier, a.supplier, b.bank, b.rekening, SUM(a.qty) AS `total_qty`, SUM(a.total) AS `total_hutang`, SUM(a.total_payment) AS `total_payment`, SUM(a.total_remaining) AS `total_remaining` FROM `mst_invoice` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM `det_ap` a LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` > '".$_GET['tgl_jto']."' AND b.posting=1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$_GET['supplier_filter']."%' AND a.deleted=0 GROUP BY a.id_supplier) AS y ON x.id_supplier=y.id_supplier UNION SELECT x.id_supplier AS x_id_supplier,x.supplier AS x_nama_supplier, x.bank AS x_bank, x.rekening AS x_rekening, x.total_hutang_jto AS x_total_hutang, y.id_supplier AS y_id_supplier, y.supplier AS y_supplier, y.bank AS y_bank, y.rekening AS y_rekening, y.total_hutang AS y_total_hutang FROM (SELECT a.id_supplier, a.supplier, b.bank, b.rekening, SUM(a.qty) AS `total_qty_jto`, SUM(a.total) AS `total_hutang_jto`, SUM(a.total_payment) AS `total_payment_jto`, SUM(a.total_remaining) AS `total_remaining_jto` FROM `mst_invoice` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM `det_ap` a LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` <= '".$_GET['tgl_jto']."' AND posting=1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$_GET['supplier_filter']."%' AND a.deleted=0 GROUP BY a.id_supplier) AS x RIGHT JOIN (SELECT a.id_supplier, a.supplier, b.bank, b.rekening, SUM(a.qty) AS `total_qty`, SUM(a.total) AS `total_hutang`, SUM(a.total_payment) AS `total_payment`, SUM(a.total_remaining) AS `total_remaining` FROM `mst_invoice` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM `det_ap` a LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` > '".$_GET['tgl_jto']."' AND b.posting=1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$_GET['supplier_filter']."%' AND a.deleted=0 GROUP BY a.id_supplier) AS y ON x.id_supplier=y.id_supplier";
  }
  else{
    $sql_aplist = "SELECT x.id_supplier AS x_id_supplier,x.supplier AS y_supplier, x.bank AS y_bank, x.rekening AS y_rekening, x.total_hutang_jto AS y_total_hutang FROM (SELECT a.id_supplier, a.supplier, b.bank, b.rekening, SUM(a.qty) AS `total_qty_jto`, SUM(a.total) AS `total_hutang_jto`, SUM(a.total_payment) AS `total_payment_jto`, SUM(a.total_remaining) AS `total_remaining_jto` FROM `mst_invoice` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM `det_ap` a  LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE b.posting=1 GROUP BY a.id_ap) AND a.deleted=0 GROUP BY a.id_supplier) AS x";
  }

  $q = $db->query($sql_aplist);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start<0) $start = 0;
  $q = $db->query($sql_aplist." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;

  $total_jto = 0; $total_belum_jto = 0; $grand_total_ap = 0;

  $i = 0;
  foreach($data1 as $line){
    $responce['rows'][$i]['id']       = isset($line['x_id_supplier']) ? $line['x_id_supplier'] : $line['y_id_supplier'];
    $responce['rows'][$i]['cell']     = array(
      isset($line['x_supplier']) && $line['x_supplier'] != null ? $line['x_supplier'] : $line['y_supplier'],
      isset($line['x_bank']) && $line['x_bank'] != null ? $line['x_bank'] : $line['y_bank'],
      isset($line['x_rekening']) && $line['x_rekening'] != null ? $line['x_rekening'] : $line['y_rekening'],
      isset($line['x_total_hutang']) && $line['x_total_hutang'] != null ? number_format($line['x_total_hutang'],0) : 0,
      isset($line['y_total_hutang']) && $line['y_total_hutang'] != null ? number_format($line['y_total_hutang'],0) : 0,
      number_format((!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']))+(!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']))),
    );
    $i++;

    $total_jto += (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']));
    $total_belum_jto += (!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
  }

  $grand_total_ap += $total_belum_jto+$total_jto;

  $responce['userdata']['x_total_hutang'] = number_format($total_jto);
  $responce['userdata']['y_total_hutang'] = number_format($total_belum_jto);
  $responce['userdata']['grand_total_ap'] = number_format($grand_total_ap);

  if(!isset($responce)){
    $responce = [];
  }

  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id       = $_GET['id'];
  $tgl_jto  = $_GET['tgl_jto'];

  $sql_sub  = "SELECT * FROM `det_ap` a  LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` <= '".($tgl_jto==''?date("Y-m-d"):$tgl_jto)."' AND b.`id_supplier`='".$id."' AND b.posting=1";

  $query    = $db->query($sql_sub);
  $count    = $query->rowCount();

  $data1    = $query->fetchAll(PDO::FETCH_ASSOC);

  $i        = 0;
  $responce = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $line['ap_num'],
      str_replace("-", "/", $line['ap_date']),
      $line['no_invoice'],
      str_replace("-", "/", $line['tanggal_invoice']),
      str_replace("-", "/", $line['tanggal_jatuh_tempo']),
      number_format($line['total']),
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
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
    <form id="filter_aplist" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Tanggal JTO</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="tgl_jto" name="tgl_jto" readonly></td>
            <td> Filter <input type="text" id="supplier_filter" name="supplier_filter" />(Supplier)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadAPList()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_aplist"></table>
<div id="pager_table_aplist"></div>

<script type="text/javascript">
  $('#tgl_jto').datepicker({
    dateFormat : "dd-mm-yy"
  });

  $("#tgl_jto").datepicker('setDate', '<?php echo date('d-m-Y')?>');

  $("#supplier_filter").autocomplete("pages/transaksi_purchase/aplistsupplier_list.php", {width: 400});

  function gridReloadAPList(){
    let tgl_jto   = ($("#tgl_jto").val()).split("-");
    tgl_jto   = tgl_jto[2]+"-"+tgl_jto[1]+"-"+tgl_jto[0];

    let supplier_aplist = $('#supplier_filter').val();

    let v_url       = '<?php echo BASE_URL?>pages/sales_online/trolnarcredit.php?action=json&tgl_jto='+tgl_jto+'&supplier_filter='+supplier_aplist;
    jQuery("#table_aplist").setGridParam({url:v_url,page:1,subGridUrl:'<?= BASE_URL.'pages/sales_online/trolnarcredit.php?action=json_sub&tgl_jto='?>'+tgl_jto,caption:"Account Payable List Per"+$("#tgl_jto").val()}).trigger("reloadGrid");
  }

  $(document).ready(function(){

    $('#table_aplist').jqGrid({
      url               : '<?= BASE_URL.'pages/sales_online/trolnarcredit.php?action=json'; ?>',
      datatype      : 'json',
      colNames      : ['Supplier', 'Bank', 'Rekening', 'Total JTO', 'Total Belum JTO', 'Total AP'],
      colModel      : [
        {name: 'supplier', index: 'supplier', align: 'left', width: 50, searchoptions: {sopt: ['cn']}},
        {name: 'bank', index: 'bank', align: 'center', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'rekening', index: 'rekening', align: 'left', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'x_total_hutang', index: 'rekening', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'y_total_hutang', index: 'rekening', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'grand_total_ap', index: 'grand_total_ap', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_aplist',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      caption       : "Account Payable List Per <?= date('d/m/Y'); ?>",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      footerrow : true,
      userDataOnFooter : true,
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/sales_online/trolnarcredit.php?action=json_sub&tgl_jto='?>',
      subGridModel  : [
        {
          name  : ['Nomor AP','Tanggal AP','Nomor Invoice','Tanggal Invoice','Tanggal JTO','Total'],
          width : [120,100,120,100,100,120],
          align : ['center','center','center','center','center','right'],
        }
      ],
    });
    $('#table_ap_list').jqGrid('navGrid', '#pager_table_aplist', {edit:false, add:false, del:false, search:false});
  });
</script>