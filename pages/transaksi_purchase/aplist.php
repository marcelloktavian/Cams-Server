<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, aplist, $group_acess);
$allow_post = is_show_menu(POST_POLICY, aplist, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, aplist, $group_acess);
if(isset($_GET['tgl_jto']) && strtolower($_GET['tgl_jto']) != ''){
  $tgl_jto = $_GET['tgl_jto'];
}
else{
  $tgl_jto = date("Y-m-d");
}

if(isset($_GET['supplier_filter']) && strtolower($_GET['supplier_filter']) != ''){
  $filter = $_GET['supplier_filter'];
}
else{
  $filter = "";
}

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  if(!$sidx) $sidx=1;

  // if(isset($_GET['tgl_jto']) && $_GET['tgl_jto'] != ''){
    $sql_aplist = "SELECT R1.id_supplier AS x_id_supplier,R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM

    (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R1
    
    LEFT JOIN (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R2 ON R1.id_supplier=R2.id_supplier
    
    UNION
    
    SELECT R1.id_supplier AS x_id_supplier,R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM
    (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R1
    
    RIGHT JOIN (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R2 ON R1.id_supplier=R2.id_supplier";
  // }
  // else{
  //   $sql_aplist = "SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS y_total_hutang, R1.total_payment AS y_total_payment FROM (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R1";
  // }

  $q = $db->query($sql_aplist);

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
  $total_jto = 0; $total_belum_jto = 0; $grand_total_ap = 0; $total_remaining = 0; $total_payment = 0;

  $i = 0;
  foreach($data1 as $line){
    $row_payment = (isset($line['x_total_payment']) && $line['x_total_payment'] != null ? $line['x_total_payment'] : $line['y_total_payment']);
    $row_total =  (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']))+(!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
    $row_sisa = $row_total-$row_payment;

    if($row_sisa == -0){
      $row_sisa = 0;
    }

    if($row_sisa > 0){
      $nomor_akun = (isset($line['x_no_akun']) && $line['x_no_akun'] != null ? $line['x_no_akun'] : $line['y_no_akun']);

      $no_telp = (isset($line['y_telp']) && $line['y_telp'] != null ? $line['y_telp'] : $line['x_telp']);

      if($allow_post){
        $payAP = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_purchase/aplist_pay.php?no_telp='.$no_telp.'&no_akun='.$nomor_akun.'&sisa_piutang='.$row_sisa.'&id='.(isset($line['x_id_supplier'])&&$line['x_id_supplier']!="" ? $line['x_id_supplier'] : $line['y_id_supplier']).'\',\'table_aplist\')" href="javascript:void(0);">Pay</a>';
      }
      else{
        $payAP = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Pay</a>';
      }

      $responce['rows'][$i]['id']       = isset($line['x_id_supplier']) ? $line['x_id_supplier'] : $line['y_id_supplier'];
      $responce['rows'][$i]['cell']     = array(
        isset($line['x_supplier']) && $line['x_supplier'] != null ? $line['x_supplier'] : $line['y_supplier'],
        isset($line['x_bank']) && $line['x_bank'] != null ? $line['x_bank'] : $line['y_bank'],
        isset($line['x_rekening']) && $line['x_rekening'] != null ? $line['x_rekening'] : $line['y_rekening'],
        isset($line['x_total_hutang']) && $line['x_total_hutang'] != null ? number_format($line['x_total_hutang'],0) : 0,
        isset($line['y_total_hutang']) && $line['y_total_hutang'] != null ? number_format($line['y_total_hutang'],0) : 0,
        number_format($row_sisa),
        number_format($row_payment),
        number_format($row_total),
        $payAP,
      );
      $i++;

      $total_jto += (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']));
      $total_belum_jto += (!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
      $total_remaining += $row_sisa;
      $total_payment += $row_payment;
    }
  }

  $grand_total_ap += $total_belum_jto+$total_jto;
  $responce['userdata']['x_total_hutang'] = number_format($total_jto);
  $responce['userdata']['y_total_hutang'] = number_format($total_belum_jto);
  $responce['userdata']['grand_total_ap'] = number_format($grand_total_ap);
  $responce['userdata']['total_remaining'] = number_format($total_remaining);
  $responce['userdata']['total_payment'] = number_format($total_payment);

  if(!isset($responce)){
    $responce = [];
  }

  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id       = $_GET['id'];
  $tgl_jto  = $_GET['tgl_jto'];

  $sql_sub  = "SELECT *, date_format(tanggal_invoice,'%d-%m-%Y') as tglinv, date_format(tanggal_jatuh_tempo,'%d-%m-%Y') as tgljto, date_format(ap_date,'%d-%m-%Y') as tglap FROM `det_ap` a  LEFT JOIN `mst_ap` b ON a.id_ap=b.id WHERE a.`tanggal_jatuh_tempo` <= '".($tgl_jto==''?date("Y-m-d"):$tgl_jto)."' AND b.`id_supplier`='".$id."' AND b.posting=1";

  $query    = $db->query($sql_sub);
  $count    = $query->rowCount();

  $data1    = $query->fetchAll(PDO::FETCH_ASSOC);

  $i        = 0;
  $responce = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $line['ap_num'],
      $line['tglap'],
      $line['no_invoice'],
      $line['tglinv'],
      $line['tgljto'],
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
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'pembayaran'){
  $id_user = $_SESSION['user']['username'];

  $masterNo = '';
  $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor FROM jurnal ORDER BY id DESC LIMIT 1");

  if(mysql_num_rows($query) == '1'){
  }else{
    $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
  }

  $q = mysql_fetch_array($query);
  $masterNo=$q['nomor'];

  $akun_get=mysql_fetch_array( mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `noakun`='".$_POST['no_akun']."' LIMIT 1"));
  $idakun=$akun_get['id'];
  $noakun=$akun_get['noakun'];
  $namaakun=$akun_get['nama'];

  $date_day = explode('/', $_POST['date_aplist'])[0];
  $date_month = explode('/', $_POST['date_aplist'])[1];
  $date_year = explode('/', $_POST['date_aplist'])[2];

  $date_formatted = $date_year.'-'.$date_month.'-'.$date_day;

  $tanggal_jurnal = date_format(date_create($date_formatted) ,"Y-m-d");

  $sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo','".$tanggal_jurnal."','Pembayaran ".$namaakun." ".$_POST['no_telp']."','".$_POST['payment_aplist']."','".$_POST['payment_aplist']."','0','$id_user',NOW(),'AP') ";
  mysql_query($sql_master) or die (mysql_error());

  $parent_id=mysql_fetch_array( mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$masterNo' LIMIT 1"));
  $idparent=$parent_id['id'];

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','AP','".$_POST['payment_aplist']."','0','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $nomor_akun_kredit = explode(':', $_POST['akun_kredit_aplist'])[0];

  $akun_kredit_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_kredit."' LIMIT 1"));

  if(mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `mst_coa` WHERE `noakun`='".$nomor_akun_kredit."' LIMIT 1")) === false){
    $akun_kredit_get=mysql_fetch_array(mysql_query("SELECT id, noakun, nama FROM `det_coa` WHERE `noakun`='".$nomor_akun_kredit."' LIMIT 1"));
  }

  $idakun_kredit=$akun_kredit_get['id'];
  $noakun_kredit=$akun_kredit_get['noakun'];
  $namaakun_kredit=$akun_kredit_get['nama'];

  $stmt = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun_kredit','$noakun_kredit','$namaakun_kredit','AP','0','".$_POST['payment_aplist']."','','0', '$id_user',NOW())");
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

    let v_url       = '<?php echo BASE_URL?>pages/transaksi_purchase/aplist.php?action=json&tgl_jto='+tgl_jto+'&supplier_filter='+supplier_aplist;
    jQuery("#table_aplist").setGridParam({url:v_url,page:1,subGridUrl:'<?= BASE_URL.'pages/transaksi_purchase/aplist.php?action=json_sub&tgl_jto='?>'+tgl_jto,caption:"Account Payable List Per"+$("#tgl_jto").val()}).trigger("reloadGrid");
  }

  $(document).ready(function(){

    $('#table_aplist').jqGrid({
      url               : '<?= BASE_URL.'pages/transaksi_purchase/aplist.php?action=json'; ?>',
      datatype      : 'json',
      colNames      : ['Supplier', 'Bank', 'Rekening', 'Total JTO', 'Total Belum JTO', 'Total Sisa', 'Total Diproses', 'Total AP', 'Pay'],
      colModel      : [
        {name: 'supplier', index: 'supplier', align: 'left', width: 45, searchoptions: {sopt: ['cn']}},
        {name: 'bank', index: 'bank', align: 'left', width: 35, searchoptions: {sopt: ['cn']}},
        {name: 'rekening', index: 'rekening', align: 'left', width: 20, searchoptions: {sopt: ['cn']}},
        {name: 'x_total_hutang', index: 'x_total_hutang', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'y_total_hutang', index: 'y_total_hutang', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'total_remaining', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'total_payment', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'grand_total_ap', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'aplist_pay', index: 'aplist_pay', align: 'center', width: 20, searchoptions: {sopt: ['cn']}},
      ],
      pager         : '#pager_table_aplist',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      caption       : "Account Payable List",
      pgbuttons : false,
      viewrecords : false,
      pgtext : "",
      pginput : false,
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      footerrow : true,
      userDataOnFooter : true,
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/aplist.php?action=json_sub&tgl_jto='?>',
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