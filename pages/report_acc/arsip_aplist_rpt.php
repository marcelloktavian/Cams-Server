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

if(isset($_GET['arsip_aplist_supplier_filter']) && strtolower($_GET['arsip_aplist_supplier_filter']) != ''){
  $filter = $_GET['arsip_aplist_supplier_filter'];
}
else{
  $filter = "";
}

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page       = isset($_GET['page'])?$_GET['page']:1;
  $limit      = isset($_GET['rows'])?$_GET['rows']:20;
  $sidx       = isset($_GET['sidx'])?$_GET['sidx']:'id';
  $sord       = isset($_GET['sord'])?$_GET['sord']:'';

  $sql_aplist = "SELECT * FROM (
      SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM
      (
          SELECT * FROM (
              SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
              LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
                  SELECT DISTINCT a.id_invoice FROM det_ap a
                  LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
              ) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier
          ) AS X 
          LEFT JOIN (
              SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
          ) AS Y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
          LEFT JOIN (
              SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
          ) AS z ON x.supplier = z.nama_supplier
      ) AS R1 
      LEFT JOIN (
          SELECT * FROM (
              SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
              LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
                  SELECT DISTINCT a.id_invoice FROM det_ap a
                  LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
              ) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier
          ) AS X 
          LEFT JOIN (
              SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
          ) AS Y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
          LEFT JOIN (
              SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
          ) AS z ON x.supplier = z.nama_supplier
      ) AS R2 ON R1.id_supplier = R2.id_supplier
      UNION
      SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM (
          SELECT * FROM (
              SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
              LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
                  SELECT DISTINCT a.id_invoice FROM det_ap a
                  LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
              ) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier
          ) AS X 
          LEFT JOIN (
              SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
          ) AS Y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
          LEFT JOIN (
              SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
          ) AS z ON x.supplier = z.nama_supplier
      ) AS R1 
      RIGHT JOIN (
          SELECT * FROM (
              SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
              LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
                  SELECT DISTINCT a.id_invoice FROM det_ap a
                  LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
              ) AND a.supplier LIKE '%".$filter."%' AND a.deleted = 0 GROUP BY a.id_supplier
          ) AS X 
          LEFT JOIN (
              SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
          ) AS Y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
          LEFT JOIN (
              SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
          ) AS z ON x.supplier = z.nama_supplier
      ) AS R2 ON R1.id_supplier = R2.id_supplier
  ) AS DerivedTableAlias
  HAVING (COALESCE(x_total_hutang,0)+COALESCE(y_total_hutang,0)) = COALESCE(x_total_payment,0) ";
  // }
  // else{
  //   $sql_aplist = "SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS y_total_hutang, R1.total_payment AS y_total_payment FROM (SELECT * FROM (SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (SELECT DISTINCT a.id_invoice FROM det_ap a LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE posting = 1 GROUP BY a.id_ap) AND a.supplier LIKE '%%' AND a.deleted = 0 GROUP BY a.id_supplier) AS x LEFT JOIN (SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') LEFT JOIN (SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap) AS z ON x.supplier=z.nama_supplier) AS R1";
  // }

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

  $total_jto = 0; $total_belum_jto = 0; $grand_total_ap = 0; $total_remaining = 0; $total_payment = 0;

  $i = 0;
  foreach($data1 as $line){
    $row_payment = (isset($line['x_total_payment']) && $line['x_total_payment'] != null ? $line['x_total_payment'] : $line['y_total_payment']);
    $row_total =  (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']))+(!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
    $row_sisa = $row_total-$row_payment;

    if($row_sisa == -0){
      $row_sisa = 0;
    }

    if($row_sisa == 0){
      $nomor_akun = (isset($line['x_no_akun']) && $line['x_no_akun'] != null ? $line['x_no_akun'] : $line['y_no_akun']);

      $no_telp = (isset($line['y_telp']) && $line['y_telp'] != null ? $line['y_telp'] : $line['x_telp']);

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

  $sql_sub  = "SELECT b.no_jurnal, b.tgl, b.keterangan, b.total_debet FROM mst_supplier a LEFT JOIN jurnal b ON b.keterangan LIKE CONCAT('%Pembayaran Hutang Dagang - %',a.vendor,'%') WHERE a.deleted = 0 AND b.deleted=0 AND a.id='".$id."'";

  $query    = $db->query($sql_sub);
  $count    = $query->rowCount();

  $data1    = $query->fetchAll(PDO::FETCH_ASSOC);

  $i        = 0;
  $responce = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['no_jurnal'];
    $responce->rows[$i]['cell'] = array(
      $line['no_jurnal'],
      $line['tgl'],
      $line['keterangan'],
      number_format($line['total_debet']),
      number_format($line['total_debet']),
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
    <form id="filter_arsip_aplist" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Filter Supplier</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" id="arsip_aplist_supplier_filter" name="arsip_aplist_supplier_filter" />(Supplier)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadArsipAPList()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_arsip_aplist"></table>
<div id="pager_table_arsip_aplist"></div>

<script type="text/javascript">

  $("#arsip_aplist_supplier_filter").autocomplete("pages/report_acc/aplistsupplier_list.php", {width: 400});

  function gridReloadArsipAPList(){
 
    let supplier_aplist = $('#arsip_aplist_supplier_filter').val();

    let v_url       = '<?php echo BASE_URL?>pages/report_acc/arsip_aplist_rpt.php?action=json'+'&arsip_aplist_supplier_filter='+supplier_aplist;
    jQuery("#table_arsip_aplist").setGridParam({url:v_url,page:1,subGridUrl:'<?= BASE_URL.'pages/report_acc/arsip_aplist_rpt.php?action=json_sub'?>',caption:"Arsip Account Payable List"}).trigger("reloadGrid");
  }

  $(document).ready(function(){

    $('#table_arsip_aplist').jqGrid({
      url               : '<?= BASE_URL.'pages/report_acc/arsip_aplist_rpt.php?action=json'; ?>',
      datatype      : 'json',
      colNames      : ['Supplier', 'Bank', 'Rekening', 'Total JTO', 'Total Belum JTO', 'Total Sisa', 'Total Diproses', 'Total AP'],
      colModel      : [
        {name: 'supplier', index: 'supplier', align: 'left', width: 45, searchoptions: {sopt: ['cn']}},
        {name: 'bank', index: 'bank', align: 'left', width: 35, searchoptions: {sopt: ['cn']}},
        {name: 'rekening', index: 'rekening', align: 'left', width: 20, searchoptions: {sopt: ['cn']}},
        {name: 'x_total_hutang', index: 'x_total_hutang', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'y_total_hutang', index: 'y_total_hutang', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'total_remaining', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'total_payment', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'grand_total_ap', index: 'grand_total_ap', align: 'right', width: 30, searchoptions: {sopt: ['cn']}}
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_arsip_aplist',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      caption       : "Arsip Account Payable List",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      footerrow : true,
      userDataOnFooter : true,
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/report_acc/arsip_aplist_rpt.php?action=json_sub'?>',
      subGridModel  : [
        {
          name  : ['Nomor Jurnal','Tanggal','Keterangan','Total Debet','Total Kredit'],
          width : [120,100,400,100,100],
          align : ['center','center','left','right','right'],
        }
      ],
    });
    $('#table_arsip_aplist').jqGrid('navGrid', '#pager_table_arsip_aplist', {edit:false, add:false, del:false, search:false});
  });
</script>