<?php

require_once '../../include/config.php';
include '../../include/koneksi.php';

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post   = is_show_menu(POST_POLICY, trb2bpiutangPembayaran, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page = isset($_GET['page'])?$_GET['page']:1;
  $limit = isset($_GET['rows'])?$_GET['rows']:10;
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'no_faktur';
  $sord = isset($_GET['sord'])?$_GET['sord']:''; 

  $startdate = isset($_GET['startdate'])?$_GET['startdate']:DATE('Y-m-d');
  $enddate = isset($_GET['enddate'])?$_GET['enddate']:DATE('Y-m-d');
  $customer = isset($_GET['customer'])?$_GET['customer']:'';
  $salesman = isset($_GET['salesman'])?$_GET['salesman']:'';

  $where = " WHERE tgl_trans > STR_TO_DATE('30/09/2023','%d/%m/%Y') AND id_jurnal_atur_komisi IS NULL AND komisi ";

  if($startdate != null && $startdate != ""){
    $where .= " AND tgl_trans BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
  }

  if($customer != null && $customer != ""){
    $where .= " AND nama_customer LIKE '%".$customer."%' ";
  }

  if($salesman != null && $salesman != ""){
    $where .= " AND nama_salesman LIKE '%".$salesman."%' ";
  }

  $having = " HAVING piutang_do-COALESCE(total_return,0)-COALESCE(piutang_terbayar,0) = 0";

  $query = "SELECT *, piutang_do-COALESCE(total_return,0) AS piutang_akhir, piutang_do-COALESCE(total_return,0)-COALESCE(piutang_terbayar,0) AS piutang_sisa FROM (
    SELECT a.id AS id_b2bso, b.id AS id_b2bdo, a.id_trans AS id_trans_so, b.id_trans AS id_trans_do, b.no_faktur, DATE(b.tgl_trans) as tgl_trans, a.id_customer, c.nama AS nama_customer, c.alamat AS alamat_customer, c.no_telp AS telp_customer, a.id_salesman, d.nama AS nama_salesman, d.alamat AS alamat_salesman, d.no_telp AS telp_salesman, a.piutang AS piutang_so, SUM(b.piutang) AS piutang_do, a.totalqty, a.totalkirim AS totalkirim_so, SUM(b.totalkirim) AS totalkirim_do FROM b2bso a LEFT JOIN b2bdo b ON a.id_trans=b.id_transb2bso LEFT JOIN mst_b2bcustomer c ON a.id_customer=c.id LEFT JOIN mst_b2bsalesman d ON a.id_salesman=d.id WHERE b.no_faktur IS NOT NULL AND b.deleted=0 AND a.deleted=0 GROUP BY no_faktur
  ) AS a LEFT JOIN (
    SELECT a.id_trans_do AS id_b2bdo, a.b2bdo_num, (a.qty31+a.qty32+a.qty33+a.qty34+a.qty35+a.qty36+a.qty37+a.qty38+a.qty39+a.qty40+a.qty41+a.qty42+a.qty43+a.qty44+a.qty45+a.qty46) AS total_qty ,SUM(a.subtotal) AS total_return FROM b2breturn_detail a LEFT JOIN b2breturn b ON a.id_parent=b.id WHERE a.deleted=0 AND b.deleted=0 AND b.post=1 GROUP BY a.b2bdo_num
  ) AS b ON a.id_b2bdo=b.id_b2bdo LEFT JOIN (
    SELECT id AS id_jurnal, no_jurnal, tgl AS tgl_jurnal, keterangan AS keterangan_jurnal, SUM(total_debet) AS piutang_terbayar, SUBSTRING_INDEX(keterangan, '-',-1) AS nomor_faktur_jurnal FROM jurnal WHERE keterangan LIKE 'Pembayaran Piutang%' AND `status`='B2B PAY' AND deleted=0 GROUP BY SUBSTRING_INDEX(keterangan, '-',-1)
  ) AS c ON a.no_faktur=TRIM(c.nomor_faktur_jurnal) LEFT JOIN (
    SELECT id AS id_jurnal_atur_komisi, SUBSTRING_INDEX(keterangan, '-',-1) AS nomor_faktur_komisi FROM jurnal WHERE keterangan LIKE 'Pengaturan Komisi Sales%' AND `status`='B2B ATUR KOMISI' AND deleted=0 GROUP BY SUBSTRING_INDEX(keterangan, '-',-1)
  ) AS d ON a.no_faktur=TRIM(d.nomor_faktur_komisi) LEFT JOIN (
    SELECT c.no_faktur AS no_faktur_komisi, SUM(b.jumlah_kirim*(b.harga_satuan-(b.harga_satuan*disc)))-b.jumlah_kirim*a.harga AS komisi FROM mst_b2bproductsgrp a LEFT JOIN b2bdo_detail b ON a.id=b.id_product LEFT JOIN b2bdo c ON c.id_trans=b.id_trans WHERE a.deleted=0 AND c.deleted=0 GROUP BY c.no_faktur
  ) AS e ON a.no_faktur=e.no_faktur_komisi ".$where.$having;

  $q = $db->query($query);
  $count = $q->rowCount();

  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $responce['page'] = $page;
  $responce['total'] = $total_pages;
  $responce['records'] = $count;

  $q = $db->query($query."
    ORDER BY `".$sidx."` ".$sord."
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach($data1 as $line){
    $post = $allow_post ? '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_b2b/trb2bkomisi_pay.php?no_faktur='.$line['no_faktur'].'&id_customer='.$line['id_customer'].'&nama_customer='.$line['nama_customer'].'\')">Atur Komisi</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Atur Komisi</a>';

    $responce['rows'][$i]['id']     = $line['no_faktur'];
    $responce['rows'][$i]['cell']   = array(
      $line['id_trans_do'],
      $line['no_faktur'],
      $line['tgl_trans'],
      $line['nama_customer'],
      $line['nama_salesman'],
      // number_format($line['totalqty']),
      number_format($line['totalkirim_do']-$line['total_qty']),
      // number_format($line['piutang_so']),
      number_format($line['piutang_akhir']),
      number_format($line['piutang_terbayar']),
      number_format($line['komisi']),
      $post
    );
    $i++;
  }

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
  $no_faktur = $_GET['id'];

  $sql_sub = "SELECT a.nama AS nama_barang, b.jumlah_kirim AS total_qty, b.harga_satuan AS harga_faktur, a.harga AS harga_resale, b.disc AS disc, b.jumlah_kirim*a.harga AS total_murni, (b.jumlah_kirim*(b.harga_satuan-(b.harga_satuan*disc))) AS total_resale, (b.jumlah_kirim*(b.harga_satuan-(b.harga_satuan*disc)))-b.jumlah_kirim*a.harga AS komisi FROM mst_b2bproductsgrp a LEFT JOIN b2bdo_detail b ON a.id=b.id_product LEFT JOIN b2bdo c ON c.id_trans=b.id_trans WHERE a.deleted=0 AND c.deleted=0 AND c.`no_faktur`='".$no_faktur."'";

  $q1 = $db->query($sql_sub);
  $data1 = $q1->fetchAll(PDO::FETCH_ASSOC);

  $i=0;
  $responce = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['komisi'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $line['nama_barang'],
      $line['total_qty'],
      number_format($line['disc'],2)."%",
      number_format($line['harga_faktur'],0),
      number_format($line['harga_resale'],0),
      number_format($line['total_resale'],0),
      number_format($line['total_murni'],0),
      number_format($line['komisi'],0),
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
    <form id="filter_ap" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Tanggal B2B DO</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_trb2bkomisi" name="startdate_trb2bkomisi" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_trb2bkomisi" name="enddate_trb2bkomisi" readonly></td>
            <td> Filter <input type="text" id="customervalue_trb2bkomisi" name="customervalue_trb2bkomisi" />(Customer) <input type="text" id="salesmanvalue_trb2bkomisi" name="salesmanvalue_trb2bkomisi" />(Salesman)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadtrb2bkomisi()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_trb2bkomisi"></table>
<div id="pager_table_trb2bkomisi"></div>

<script>
  $('#startdate_trb2bkomisi').datepicker({
    dateFormat: "dd/mm/yy"
  });

  $('#enddate_trb2bkomisi').datepicker({
    dateFormat: "dd/mm/yy"
  });

  $( "#startdate_trb2bkomisi" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_trb2bkomisi" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );

  function gridReloadtrb2bkomisi(){
    let startdate   = ($("#startdate_trb2bkomisi").val());
		let enddate     = ($("#enddate_trb2bkomisi").val());

		let customer      = $("#customervalue_trb2bkomisi").val();
    let salesman      = $("#salesmanvalue_trb2bkomisi").val();

		let v_url       = '<?php echo BASE_URL?>pages/sales_b2b/trb2bkomisi.php?action=json&startdate='+startdate+'&enddate='+enddate+'&customer='+customer+'&salesman='+salesman;
		jQuery("#table_trb2bkomisi").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(()=>{
    $('#customervalue_trb2bkomisi').autocomplete("<?= BASE_URL.'pages/sales_b2b/trb2bkomisi_list.php?req=customer'?>", {width: 400});
    $('#salesmanvalue_trb2bkomisi').autocomplete("<?= BASE_URL.'pages/sales_b2b/trb2bkomisi_list.php?req=salesman'?>", {width: 400});

    $('#table_trb2bkomisi').jqGrid({
      url           : '<?= BASE_URL.'pages/sales_b2b/trb2bkomisi.php?action=json'?>',
      datatype      : 'json',
      colNames      : ['Nomor DO','Nomor Faktur', 'Tanggal DO', 'Customer', 'Salesman', 'Total QTY Akhir (-Retur)', 'Piutang Akhir (-Retur)', 'Piutang Terbayar', 'Komisi', 'Pembayaran'],
      colModel      : [
        {name: 'id_trans_so', index: 'id_trans_so', align: 'center', width: 40, searchoptions: {sopt: ['cn']}},
        {name: 'no_faktur', index: 'no_faktur', align: 'center', width: 40, searchoptions: {sopt: ['cn']}},
        {name:'tgl_trans', index: 'tgl_trans', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'nama_customer', index: 'nama_customer', align: 'left', width: 60, searchoptions:{sopt: ['cn']}},
        {name: 'nama_salesman', index: 'nama_salesman', align: 'left', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'total_qty_akhir', index: 'total_qty_akhir', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'piutang_akhir', index: 'piutang_akhir', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'piutang_terbayar', index: 'piutang_terbayar', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'piutang_sisa', index: 'piutang_sisa', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'post', index: 'post', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [20, 1000],
      pager         : '#pager_table_trb2bkomisi',
      sortname      : 'no_faktur',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "B2B Komisi Sales",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid : true,
      subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2bkomisi.php?action=json_sub'; ?>',
      subGridModel: [
          { 
            name : ['No','Nama Barang','Qty','Diskon','Harga Faktur','Harga Resale','Subtotal Faktur','Subtotal Resale', 'Komisi'], 
            width : [40,200,50,50,50,50,50,50,50],
          align : ['right','left','right','right','right','right','right','right','right'],
         } 
        ],
    });
    $('#table_trb2bkomisi').jqGrid('navGrid', '#pager_table_trb2bkomisi', {edit:false, add:false, del:false, search:false});
  });
</script>