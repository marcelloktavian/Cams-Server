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
  SELECT a.b2bcust_id AS id_customer, a.no_akun_kredit AS no_akun_kredit, c.nama AS nama_akun_kredit, b.nama AS nama_customer, b.no_telp, a.id_akun_kredit, 0 AS total_pembayaran, a.total AS total_piutang, 'AR' AS `data` FROM b2bar a LEFT JOIN mst_b2bcustomer b ON a.`b2bcust_id`=b.id LEFT JOIN det_coa c ON a.`id_akun_kredit`=c.id WHERE a.`lastmodified` > '2023-07-31' AND a.deleted=0 AND a.post=1 AND b.nama LIKE '%".$filter."%'

  UNION ALL

  SELECT b.id AS id_customer, NULL AS no_akun_kredit, NULL AS nama_akun_kredit, b.nama AS nama_customer, b.no_telp AS no_telp, NULL AS id_akun_kredit, a.`total_debet` AS total_pembayaran, 0 AS total_piutang, 'PAY' AS `data` FROM jurnal a LEFT JOIN mst_b2bcustomer b ON a.keterangan LIKE CONCAT('%Pembayaran Piutang B2B - %',b.nama,'%') WHERE a.lastmodified > '2023-07-31' AND a.`status` = 'B2B AR' AND a.deleted=0 AND b.nama LIKE '%".$filter."%'
  ) AS subquery
  GROUP BY id_customer HAVING SUM(total_pembayaran) != SUM(total_piutang) ";

  $q = $db->query($sql_b2blist);

  $count = $q->rowCount();

  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $responce['page'] = $page;
  $responce['total'] = $total_pages;
  $responce['records'] = $count;

  $q = $db->query($sql_b2blist." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

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

    if($row_sisa > 0){
      $payAR = $allow_post ? '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_b2b/arb2blist_pay.php?no_telp='.$no_telp.'&id_akun='.$id_akun.'&sisa_piutang='.$row_sisa.'&id='.$line['id_customer'].'\',\'table_arb2blist\')" href="javascript:void(0);">Pay</a>' : '<a onclick="javascript:custom_alert(\'Not Allowed\')">Pay</a>';

      $responce['rows'][$i]['id']       = $line['id_customer'];
      $responce['rows'][$i]['cell']     = array(
        $line['nama_customer'],
        $line['no_akun_kredit'],
        $line['nama_akun_kredit']." (".$line['no_telp'].")",
        number_format($row_piutang),
        number_format($row_payment),
        number_format($row_sisa),
        $payAR,
      );
      $i++;

      $total_arb2blist += $row_piutang;
      $total_remaining += $row_sisa;
      $total_payment += $row_payment;
    }
  }

  $responce['userdata']['row_total'] = number_format($total_arb2blist);
  $responce['userdata']['row_sisa'] = number_format($total_remaining);
  $responce['userdata']['row_payment'] = number_format($total_payment);

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

  $sql_sub        = "SELECT *, date_format(tgl, '%d/%m/%Y') AS tgl_jurnal FROM jurnal WHERE (keterangan LIKE CONCAT('%Pembayaran Piutang B2B % ','%".$nama_customer."%') OR keterangan LIKE CONCAT('%Retur B2B - % ','%".$nama_customer."%')) AND status = 'B2B AR' AND deleted=0";

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

} else if (isset($_GET['action']) && strtolower($_GET['action']) == 'pembayaran'){
  $id_user = $_SESSION['user']['username'];

  $masterNo = '';
  $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor FROM jurnal ORDER BY id DESC LIMIT 1");

  if(mysql_num_rows($query) == '1'){
  }else{
    $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
  }

  $q = mysql_fetch_array($query);
  $masterNo=$q['nomor'];

  $akun_get=mysql_fetch_array( mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `id`='".$_POST['id_akun_arb2blist']."' LIMIT 1"));
  $idakun=$akun_get['id'];
  $noakun=$akun_get['noakun'];
  $namaakun=$akun_get['nama'];

  $date_day = explode('/', $_POST['date_arb2blist'])[0];
  $date_month = explode('/', $_POST['date_arb2blist'])[1];
  $date_year = explode('/', $_POST['date_arb2blist'])[2];

  $date_formatted = $date_year.'-'.$date_month.'-'.$date_day;

  $tanggal_jurnal = date_format(date_create($date_formatted) ,"Y-m-d");

  $sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo','".$tanggal_jurnal."','Pembayaran ".$namaakun." ".$_POST['no_telp_arb2blist']."','".$_POST['payment_arb2blist']."','".$_POST['payment_arb2blist']."','0','$id_user',NOW(),'B2B AR') ";

  mysql_query($sql_master) or die (mysql_error());

  $parent_id=mysql_fetch_array( mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$masterNo' LIMIT 1"));
  $idparent=$parent_id['id'];

  $status = '';
  $querycekstatus = "SELECT COUNT(*) AS count_exists FROM det_coa WHERE noakun = '$noakun'";
  $resultcekstatus = mysql_query($querycekstatus);

  if ($resultcekstatus) {
    $rowcekstatus = mysql_fetch_assoc($resultcekstatus);
    $countExists = $rowcekstatus['count_exists'];

    if ($countExists > 0) {
      $status = 'Detail';
    } else {
      $status = 'Parent';
    }
  }
        
  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','$status','0','".$_POST['payment_arb2blist']."','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $nomor_akun_debet= explode(' - ',explode(':', $_POST['akun_debet_arb2blist'])[1])[0];

  $akun_debet_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1"));

  if(mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1")) === false){
    $akun_debet_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `noakun`='".$nomor_akun_debet."' LIMIT 1"));
  }

  $idakun_debet=$akun_debet_get['id'];
  $noakun_debet=$akun_debet_get['noakun'];
  $namaakun_debet=$akun_debet_get['nama'];

  $status = '';
  $querycekstatus = "SELECT COUNT(*) AS count_exists FROM det_coa WHERE noakun = '$noakun_debet'";
  $resultcekstatus = mysql_query($querycekstatus);

  if ($resultcekstatus) {
    $rowcekstatus = mysql_fetch_assoc($resultcekstatus);
    $countExists = $rowcekstatus['count_exists'];

    if ($countExists > 0) {
      $status = 'Detail';
    } else {
      $status = 'Parent';
    }
  }

  $stmt = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun_debet','$noakun_debet','$namaakun_debet','$status','".$_POST['payment_arb2blist']."','0','','0', '$id_user',NOW())");
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

<script tpye='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<div class="ui-widget ui-form" style="margin-bottom:5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_arb2blist" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Filter Customer</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" id="arb2blist_customer_filter" name="arb2blist_customer_filter" />(Customer)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadARB2BList()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_arb2blist"></table>
<div id="pager_table_arb2blist"></div>

<script type="text/javascript">

  function gridReloadARB2BList(){
    let arb2blist_customer = $('#arb2blist_customer_filter').val();

    let v_url       = '<?php echo BASE_URL?>pages/sales_b2b/arb2blist.php?action=json'+'&arb2blist_customer_filter='+arb2blist_customer;
    jQuery("#table_arb2blist").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(function(){

  $('#arb2blist_customer_filter').autocomplete("<?= BASE_URL.'pages/sales_b2b/arb2blist_cust_list.php'?>", {width: 400});

  $('#table_arb2blist').jqGrid({
    url               : '<?= BASE_URL.'pages/sales_b2b/arb2blist.php?action=json'; ?>',
    datatype      : 'json',
    colNames      : ['Customer', 'Nomor Akun', 'Nama Akun', 'Total Piutang', 'Total Pembayaran', 'Total Sisa', 'Pay'],
    colModel      : [
      {name: 'customer', index: 'customer', align: 'left', width: 25, searchoptions: {sopt: ['cn']}},
      {name: 'no_akun_kredit', index: 'no_akun_kredit', align: 'center', width: 15, searchoptions: {sopt: ['cn']}},
      {name: 'nama_akun_kredit', index: 'nama_akun_kredit', align: 'left', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'row_total', index: 'row_total', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'row_payment', index: 'row_payment', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'row_sisa', index: 'row_sisa', align: 'right', width: 40, searchoptions: {sopt: ['cn']}},
      {name: 'arb2blist_pay', index: 'arb2blist_pay', align: 'center', width: 20, searchoptions: {sopt: ['cn']}},
    ],
    rowNum            : 20,
    rowList           : [10, 20, 30],
    pager             : '#pager_table_arb2blist',
    autowidth         : true,
    height            : '460',
    viewrecords       : true,
    rownumbers        : true,
    caption           : "AR B2B List",
    ondblClickRow     : function(rowid){
      alert(rowid);
    },
    footerrow         : true,
    userDataOnFooter  : true,
    subGrid           : true,
    subGridUrl    : '<?= BASE_URL.'pages/sales_b2b/arb2blist.php?action=json_sub'?>',
    subGridModel  : [
      {
        name  : ['No','Nomor Jurnal','Tanggal Jurnal','Keterangan','Total Debit', 'Total Kredit'],
        width : [30,100,100,400,100,100],
        align : ['right','center','center','left','right','right'],
      }
    ],
  });
  $('#table_arb2blist').jqGrid('navGrid', '#pager_table_arb2blist', {edit:false, add:false, del:false, search:false});
  });
</script>