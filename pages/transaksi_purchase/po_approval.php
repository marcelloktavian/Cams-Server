<?php

require_once '../../include/config.php';

$group_access   = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY   , po, $group_access);
$allow_edit     = is_show_menu(EDIT_POLICY  , po, $group_access);
$allow_delete   = is_show_menu(DELETE_POLICY, po, $group_access);
$allow_post     = is_show_menu(POST_POLICY  , po, $group_access);

if(isset($_GET['action']) && strtolower($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

  $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
  $limit = isset($_GET['rows'])?$_GET['rows']:20; // get how many rows we want to have into the grid
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; // get index row - i.e. user click to sort
  $sord = isset($_GET['sord'])?$_GET['sord']:'';

  if(!$sidx) $sidx = 1;

  // << searching _filter ------------------------------
  if(isset($_GET['filter']) && $_GET['filter'] != ''){
    $filter_value = " AND (`nomor_invoice` LIKE '%".$_GET['filter']."%' OR `keterangan` LIKE '%".$_GET['filter']."%' OR `supplier` LIKE '%".$_GET['filter']."%') ";
  }
  else{
    $filter_value = '';
  }

  if((!isset($_GET['startdate_invoice']) && !isset($_GET['enddate_invoice']))||($_GET['startdate_invoice'] == '' && $_GET['enddate_invoice'] == '')){
    $where = " WHERE deleted=0 AND tanggal_invoice>='".date("Y-m-d")."' AND tanggal_invoice<='".date("Y-m-d")."'".$filter_value;
  }else if($_GET['startdate_invoice'] != '' && $_GET['enddate_invoice'] == ''){
    $where = " WHERE deleted=0 AND tanggal_invoice>='".$_GET['startdate_invoice']."'".$filter_value;
  }
  else if($_GET['startdate_invoice'] == '' && $_GET['enddate_invoice'] != ''){
    $where = " WHERE deleted=0 AND tanggal_invoice<='".$_GET['enddate_invoice']."'".$filter_value;
  }
  else{
    $where = " WHERE deleted=0 AND tanggal_invoice>='".$_GET['startdate_invoice']."' AND tanggal_invoice<='".$_GET['enddate_invoice']."'".$filter_value;
  }
  // -------------------- end of searching _filter >>
  $sql_po  = "SELECT *,  date_format(tanggal_invoice, '%d-%m-%Y') as tanggal_invoice_formatted, date_format(tanggal_jatuh_tempo, '%d-%m-%Y') as tanggal_jatuh_tempo_formatted FROM `mst_invoice` ";
  $q = $db->query($sql_po.$where);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start<0) $start = 0;

  $q = $db->query($sql_po.$where." 
    ORDER BY `".$sidx."` ".$sord." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;
  
  // $responce = array();
  $total_qty = 0; $total_inv = 0; $total_payment = 0; $total_remaining = 0;

  $i=0;
  foreach($data1 as $line){
    if($allow_edit){
      if($line['post_ap'] == '1'){
        $edit = '<a onclick="javascript:custom_alert(\'Invoice yang sudah dipost ke AP tidak dapat diedit\')" href="javascript:void(0);" disabled>Edit</a>';
      }
      else{
        $edit = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/transaksi_purchase/po_invoice_edit.php?id='.$line['id'].'\',\'table_invoice\')" href="javascript:void(0);">Edit</a>';
      }
    }
    else{
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Edit</a>';
    }

    if($allow_post){
      if($line['total_payment'] != '0'){
        $postInvoice = '<a onclick="javascript:custom_alert(\'Invoice sudah diproses pada AP.\')" href="javascript:void(0);">Unpost</a>';
      }
      else if($line['post_ap'] == '1'){
        $postInvoice = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=postap&val=0&id='.$line['id'].'\',\'table_invoice\')" href="javascript:void(0);">Unpost</a>';
      }
      else{
        $postInvoice = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=postap&val=1&id='.$line['id'].'\',\'table_invoice\')" href="javascript:void(0);">Post</a>';
      }
    }
    else{
      $postInvoice = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Post</a>';
    }

    if($allow_delete){
      if($line['post_ap'] == '1'){
        $delete = '<a onclick="javascript:custom_alert(\'Invoice yang sudah dipost ke AP tidak dapat dihapus\')" href="javascript:void(0);" disabled>Delete</a>';
      }
      else{
        $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=delete&id='.$line['id'].'\',\'table_invoice\')" href="javascript:void(0);">Delete</a>';
      }
    }
    else{
      $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Post</a>';
    }

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      $line['id'],
      $line['nomor_invoice'],
      $line['tanggal_invoice'],
      $line['tanggal_jatuh_tempo'],
      $line['supplier'],
      number_format($line['qty']),
      number_format($line['total']),
      number_format($line['total_payment']),
      number_format($line['total_remaining']),
      $line['keterangan'],
      $postInvoice,
      $edit,
      $delete,
    );

    $total_qty += $line['qty'];
    $total_inv += $line['total'];
    $total_payment += $line['total_payment'];
    $total_remaining += $line['total_remaining'];

    $i++;
  }

  $responce['userdata']['qty'] = number_format($total_qty,0);
  $responce['userdata']['total'] = number_format($total_inv,0);
  $responce['userdata']['total_payment'] = number_format($total_payment,0);
  $responce['userdata']['total_remaining'] = number_format($total_remaining,0);

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id     = $_GET['id'];

  $query  = "SELECT x.*,y.qty_po,y.qty_terbayar FROM (SELECT a.*,b.`dokumen`,b.`nama_supplier`,b.`tgl_po`, DATE_FORMAT(b.tgl_po, '%d/%m/%Y') AS tgl_po_formatted FROM `det_invoice` a LEFT JOIN `mst_po` b ON a.`id_po`=b.`id` WHERE a.`id_invoice`=".$id." AND a.deleted = 0) AS `x` JOIN (SELECT a.qty AS qty_po,a.qty_terbayar,b.id_detail AS id_join FROM `det_po` a LEFT JOIN `det_invoice` b ON b.id_detail=a.id WHERE b.id_invoice=".$id." AND b.`deleted`=0) AS `y` ON x.id_detail=y.id_join";

  $exe    = $db->query($query);
  $count  = $exe->rowCount();
  $data1  = $exe->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  $responce = '';

  foreach ($data1 as $line){
    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $i + 1,
      $line['dokumen'],
      $line['tgl_po_formatted'],
      $line['nama_produk'],
      $line['qty'],
      $line['qty_po'],
      $line['qty_terbayar'],
      number_format($line['price']),
      $line['satuan'],
      number_format($line['subtotal']),
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add'){
  include 'po_invoice_add.php'; exit(); exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'postap'){
  $q_post   = $db->prepare("UPDATE `mst_invoice` SET `post_ap`=? WHERE `id`=?");
  $q_post->execute(array($_GET['val'], $_GET['id']));
  
  $affected_rows = $q_post->rowCount();

  if($affected_rows > 0){
    $r['stat'] = 1; $r['message'] = 'Succes';
  }
  else{
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete'){
  $qty_po   = $db->prepare("UPDATE `det_po` SET `det_po`.`qty_terbayar`=`det_po`.`qty_terbayar`-(SELECT `qty` FROM `det_invoice` WHERE det_invoice.id_detail=det_po.id AND det_invoice.id_produk=det_po.`id_produk` AND det_invoice.id_invoice=".$_GET['id']." AND det_invoice.`deleted`=0) WHERE `det_po`.id=(SELECT `id_detail` FROM `det_invoice` WHERE det_invoice.id_detail=det_po.id AND det_invoice.id_invoice=".$_GET['id']." AND `deleted`=0)");

  $qty_po->execute();

  $q_post   = $db->prepare("UPDATE `mst_invoice` SET `deleted`=? WHERE `id`=?");
  $q_post->execute(array(1, $_GET['id']));
  $affected_rows = $q_post->rowCount();

  if($affected_rows > 0){
    $r['stat'] = 1; $r['message'] = 'Succes';
  }
  else{
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
?>

<div class="ui-widget ui-form" style="margin-bottom:5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>
  <div class="ui-widget-content ui-corner-bottom">
    <from id="filter_invoice" method="" action="" class="ui-helper-clearifx">
      <label for="" class="ui-helper-reset label-control">Tanggal Invoice</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_invoice" name="startdate_invoice" placeholder="Start Date" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_invoice" name="enddate_invoice" placeholder="End Date" readonly></td>
            <td> Filter <input type="text" id="filtervalue_invoice" name="filtervalue_invoice">(No Invoice, Supplier)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadInvoice()" class="btn" type="button">Cari</button>
      </div>
    </from>
  </div>
</div>

<div class="btn_box">
  <?php
    if($allow_add){ ?>
      <a href="javascript: void(0)">
        <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL?>pages/transaksi_purchase/po_approval.php?action=add')">Tambah</button>
      </a>
  <?php } ?>
</div>

<table id="table_invoice"></table>
<div id="pager_table_invoice"></div>

<script type="text/javascript">
  $('#startdate_invoice').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$('#enddate_invoice').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$( "#startdate_invoice" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
	$( "#enddate_invoice" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );

  function gridReloadInvoice(){
		var startdate_b2bdo_idx   =  ($("#startdate_invoice").val()).split("-");
		var enddate_b2bdo_idx     =  ($("#enddate_invoice").val()).split("-");

    var startdate             =  startdate_b2bdo_idx[2]+"-"+startdate_b2bdo_idx[1]+"-"+startdate_b2bdo_idx[0];
    var enddate               =  enddate_b2bdo_idx[2]+"-"+enddate_b2bdo_idx[1]+"-"+enddate_b2bdo_idx[0];

		var filterb2bdo_idx       = $("#filtervalue_invoice").val();

		var v_url ='<?php echo BASE_URL?>pages/transaksi_purchase/po_approval.php?action=json&startdate_invoice='+startdate+'&enddate_invoice='+enddate+'&filter='+filterb2bdo_idx;
		jQuery("#table_invoice").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

  $(document).ready(function(){
    $('#table_invoice').jqGrid({
      url       : '<?= BASE_URL.'pages/transaksi_purchase/po_approval.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID','Nomor Invoice','Tgl Invoice','Tgl Jatuh Tempo','Supplier','Total Qty','Total Invoice','Total Payment','Total Remaining','Keterangan','Post','Edit','Delete'],
      colModel  : [
        {name:'id', index: 'id', align: 'right', width:15, searchoptions: {sopt:['cn']}},
        {name:'nomor_invoice', index: 'nomor_invoice', align: 'left', width:40, searchoptions: {sopt:['cn']}},
        {name:'tanggal_invoice', index: 'tanggal_invoice', align: 'center', width:40, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name:'tanggal_jatuh_tempo', index: 'tanggal_jatuh_tempo', align: 'center', width:40, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name:'supplier', index:'supplier', align: 'left', width:80, searchoptions: {sopt: ['cn']}},
        {name:'qty', index:'qty', align:'right', width:25, searchoptions: {sopt: ['cn']}},
        {name:'total', index:'total', align: 'right', width:40, searchoptions: {sopt: ['cn']}},
        {name:'total_payment', index:'total_payment', align: 'right', width:40, searchoptions: {sopt: ['cn']}},
        {name:'total_remaining', index:'total_remaining', align: 'right', width:40, searchoptions: {sopt: ['cn']}},
        {name:'keterangan', index:'keterangan', align: 'left', searchoptions: {sopt: ['cn']}},
        {name: 'Post', index:'post', align:'center', width:30, sortable: false},
        {name: 'Edit', index:'edit', align:'center', width:30, sortable: false},
        {name: 'Print', index:'print', align:'center', width:30, sortable: false},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_invoice',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Purchase Invoice",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po_approval.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Dokumen','Tgl PO','Produk / Jasa','Qty Invoice','Qty PO','Qty Terproses','DPP/Unit','Satuan','Subtotal'],
          width : [30,100,70,250,70,70,70,90,70,100],
          align : ['right','left','center','left','right','right','right','right','center','right'],
        }
      ],
      footerrow : true,
      userDataOnFooter : true,
    });
    $('#table_invoice').jqGrid('navGrid', '#pager_table_invoice', {edit:false, add:false, del:false, search:false});
  });
</script>