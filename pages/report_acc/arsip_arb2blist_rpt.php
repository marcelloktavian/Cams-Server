<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post   = is_show_menu(POST_POLICY, arb2blist, $group_acess);

if(isset($_GET['arb2blist_customer_filter']) && strtolower($_GET['arb2blist_customer_filter']) != ''){
  $filter = $_GET['arb2blist_customer_filter'];
} else {
  $filter = "";
}

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page       = isset($_GET['page'])?$_GET['page']:1;
  $limit      = isset($_GET['rows'])?$_GET['rows']:20;
  $sidx       = isset($_GET['sidx'])?$_GET['sidx']:'id';
  $sord       = isset($_GET['sord'])?$_GET['sord']:''; 

  $sql_b2blist = "SELECT id_customer,
  nama_customer,
  id_akun_kredit,
  no_akun_kredit,
  nama_akun_kredit,
  no_telp,
  SUM(total_pembayaran) AS total_pembayaran, 
  SUM(total_piutang) AS total_piutang
  FROM (
  SELECT a.b2bcust_id AS id_customer, a.no_akun_kredit AS no_akun_kredit, c.nama AS nama_akun_kredit, b.nama AS nama_customer, b.no_telp, a.id_akun_kredit, 0 AS total_pembayaran, a.total AS total_piutang, 'AR' AS `data` FROM b2bar a LEFT JOIN mst_b2bcustomer b ON a.`b2bcust_id`=b.id LEFT JOIN det_coa c ON a.`id_akun_kredit`=c.id WHERE a.`lastmodified` > '2023-07-01' AND a.deleted=0 AND a.post=1 AND b.nama LIKE '%".$filter."%'

  UNION

  SELECT b.id AS id_customer, NULL AS no_akun_kredit, NULL AS nama_akun_kredit, b.nama AS nama_customer, b.no_telp AS no_telp, NULL AS id_akun_kredit, a.`total_debet` AS total_pembayaran, 0 AS total_piutang, 'PAY' AS `data` FROM jurnal a LEFT JOIN mst_b2bcustomer b ON a.keterangan LIKE CONCAT('%Pembayaran Piutang B2B - %',b.nama,'%') WHERE a.lastmodified > '2023-07-01' AND a.`status` = 'B2B AR' AND a.deleted=0 AND b.nama LIKE '%".$filter."%'
  ) AS subquery
  GROUP BY id_customer HAVING SUM(total_pembayaran) = SUM(total_piutang) ";

  $q = $db->query($sql_b2blist);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;

  if($start<0) $start = 0;
  $q = $db->query($sql_b2blist." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;

  $total_arb2blist = 0;
  $total_remaining = 0;
  $total_payment = 0;

  $i = 0;
  foreach($data1 as $line){
    $row_payment = (isset($line['total_pembayaran']) && $line['total_pembayaran'] != null ? $line['total_pembayaran'] : 0);
    $row_piutang = (isset($line['total_piutang']) && $line['total_piutang'] != null ? $line['total_piutang'] : 0);
    $row_sisa = $row_piutang - $row_payment;

    $id_akun = $line['id_akun_kredit'];
    $no_telp = $line['no_telp'];

    if($row_sisa == -0){
      $row_sisa = 0;
    }

    if($row_sisa == 0){

      $responce['rows'][$i]['id']       = $line['id_customer'];
      $responce['rows'][$i]['cell']     = array(
        $line['nama_customer'],
        $line['no_akun_kredit'],
        $line['nama_akun_kredit']." (".$line['no_telp'].")",
        number_format($row_piutang),
        number_format($row_payment),
        number_format($row_sisa),
      );
      $i++;

      $total_arb2blist += $row_piutang;
      $total_remaining += $row_sisa;
      $total_payment += $row_payment;
    }
  }

  $responce['userdata']['arsip_arb2blist_row_total'] = number_format($total_arb2blist);
  $responce['userdata']['arsip_arb2blist_row_sisa'] = number_format($total_remaining);
  $responce['userdata']['arsip_arb2blist_row_payment'] = number_format($total_payment);

  if(!isset($responce)){
    $responce = [];
  }

  echo json_encode($responce);
  exit;

} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id_customer  = $_GET['id'];

  $sql_customer   = "SELECT * FROM mst_b2bcustomer WHERE id='$id_customer'";

  $q = mysql_fetch_array(mysql_query($sql_customer));
  $nama_customer = $q['nama'];

  $sql_sub        = "SELECT *, date_format(tgl, '%d/%m/%Y') AS tgl_jurnal FROM jurnal WHERE keterangan LIKE CONCAT('%Pembayaran Piutang B2B % ','%".$nama_customer."%') AND status = 'B2B AR' AND deleted=0";

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

<script tpye='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<div class="ui-widget ui-form" style="margin-bottom:5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_arb2blist" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Filter</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" id="arsip_arb2blist_customer_filter" name="arsip_arb2blist_customer_filter" />(Customer)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadArsipARB2BList()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_arsip_arb2blist"></table>
<div id="pager_table_arsip_arb2blist"></div>

<script type="text/javascript">

  function gridReloadArsipARB2BList(){ 
    let arsip_arb2blist_customer = $('#arsip_arb2blist_customer_filter').val();

    let v_url       = '<?php echo BASE_URL?>pages/report_acc/arsip_arb2blist_rpt.php?action=json'+'&arb2blist_customer_filter='+arsip_arb2blist_customer;
    jQuery("#table_arsip_arb2blist").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(function(){

  $('#arsip_arb2blist_customer_filter').autocomplete("<?= BASE_URL.'pages/sales_b2b/arb2blist_cust_list.php'?>", {width: 400});

  $('#table_arsip_arb2blist').jqGrid({
    url               : '<?= BASE_URL.'pages/report_acc/arsip_arb2blist_rpt.php?action=json'; ?>',
    datatype      : 'json',
    colNames      : ['Customer', 'Nomor Akun', 'Nama Akun', 'Total Piutang', 'Total Pembayaran', 'Total Sisa'],
    colModel      : [
      {name: 'arsip_arb2blist_customer', index: 'arsip_arb2blist_customer', align: 'left', width: 25, searchoptions: {sopt: ['cn']}},
      {name: 'arsip_arb2blist_no_akun_kredit', index: 'arsip_arb2blist_no_akun_kredit', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
      {name: 'arsip_arb2blist_nama_akun_kredit', index: 'arsip_arb2blist_nama_akun_kredit', align: 'left', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'arsip_arb2blist_row_total', index: 'arsip_arb2blist_row_total', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'arsip_arb2blist_row_payment', index: 'arsip_arb2blist_row_payment', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'arsip_arb2blist_row_sisa', index: 'arsip_arb2blist_row_sisa', align: 'right', width: 40, searchoptions: {sopt: ['cn']}}
    ],
    rowNum            : 20,
    rowList           : [10, 20, 30],
    pager             : '#pager_table_arsip_arb2blist',
    autowidth         : true,
    height            : '460',
    viewrecords       : true,
    rownumbers        : true,
    caption           : "Arsip AR B2B List",
    ondblClickRow     : function(rowid){
      alert(rowid);
    },
    footerrow         : true,
    userDataOnFooter  : true,
    subGrid           : true,
    subGridUrl    : '<?= BASE_URL.'pages/report_acc/arsip_arb2blist_rpt.php?action=json_sub'?>',
    subGridModel  : [
      {
        name  : ['No','Nomor Jurnal','Tanggal Jurnal','Keterangan','Total Debit', 'Total Kredit'],
        width : [30,100,100,400,100,100],
        align : ['right','center','center','left','right','right'],
      }
    ],
  });
  $('#table_arsip_arb2blist').jqGrid('navGrid', '#pager_table_arsip_arb2blist', {edit:false, add:false, del:false, search:false});
  });
</script>