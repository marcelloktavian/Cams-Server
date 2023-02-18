<?php require_once '../../include/config.php';

$group_access   = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY   , archivepo, $group_access);
$allow_edit     = is_show_menu(EDIT_POLICY  , archivepo, $group_access);
$allow_delete   = is_show_menu(DELETE_POLICY, archivepo, $group_access);
$allow_post     = is_show_menu(POST_POLICY  , archivepo, $group_access);

if(isset($_GET['action']) && strtolower($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

  if(!$sidx) $sidx = 1;

   // << searching _filter ------------------------------
  if(isset($_GET['filter']) && $_GET['filter'] != ''){
    $filter_value = " AND (`dokumen` LIKE '%".$_GET['filter']."%' OR `nama_supplier` LIKE '%".$_GET['filter']."%' OR `nama_pemohon` LIKE '%".$_GET['filter']."%') ";
  }
  else{
    $filter_value = '';
  }

  if((!isset($_GET['startdate_po']) && !isset($_GET['enddate_po']))||($_GET['startdate_po'] == '' && $_GET['enddate_po'] == '')){
    $where = ' WHERE a.approval=1 AND a.proforma=1 AND a.deleted=0 '.$filter_value;
  }else if($_GET['startdate_po'] != '' && $_GET['enddate_po'] == ''){
    $where = " WHERE a.approval=1 AND a.proforma=1 AND a.deleted=0 AND tgl_po>='".$_GET['startdate_po']."'".$filter_value;
  }
  else if($_GET['startdate_po'] == '' && $_GET['enddate_po'] != ''){
    $where = " WHERE a.approval=1 AND a.proforma=1 AND a.deleted=0 AND tgl_po<='".$_GET['enddate_po']."'".$filter_value;
  }
  else{
    $where = " WHERE a.approval=1 AND a.proforma=1 AND a.deleted=0 AND tgl_po>='".$_GET['startdate_po']."' AND tgl_po<='".$_GET['enddate_po']."'".$filter_value;
  }
  // -------------------- end of searching _filter >>

  $sql_po = "SELECT  a.*, date_format(a.tgl_po,'%d-%m-%y') as tgl_po_formatted, date_format(a.eta_pengiriman,'%d-%m-%y') as eta_pengiriman_formatted FROM `mst_po` a ";
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

  $response['page']     = $page;
  $response['total']    = $total_pages;
  $response['records']  = $count;
  
  $responce = array();
  $i=0;
  foreach($data1 as $line){

    if($allow_post) 
      $unpost = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_archive.php?action=unpost&id='.$line['id'].'\',\'table_po_archive\')" href="javascript:;">Unpost</a>';

    else
      $unpost = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Unpost</a>';

    $print = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/transaksi_purchase/po_print.php?id='.$line['id'].'\',\'table_po_archive\')" href="javascript:;">Print</a>';

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      $line['id'],
      $line['dokumen'],
      $line['nama_pemohon'],
      $line['nama_supplier'],
      $line['tgl_po'],
      $line['eta_pengiriman'],
      number_format($line['total_qty'],0),
      number_format($line['total_dpp'],0),
      number_format($line['ppn'],0),
      number_format($line['grand_total'],0),
      $line['catatan'],
      $print,
      $unpost,
    );
    $i++;
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id = $_GET['id'];

  $query = "SELECT * FROM `det_po` WHERE `id_po`='".$id."' AND deleted = 0";

  $exe   = $db->query($query);
  $count = $exe->rowCount();
  $data1 = $exe->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  $responce = '';

  foreach ($data1 as $line){
    
    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $i + 1,
      $line['nama_produk'],
      $line['qty'],
      $line['satuan'],
      number_format($line['price'],0),
      number_format($line['subtotal'],0),
    );
    $i++;
  }
  echo  json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add'){
  include 'po_add.php'; exit(); exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'post'){
  $q_post   = $db->prepare("UPDATE `mst_po` SET `proforma`=? WHERE `id`=?");
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
elseif(isset($_GET['action']) && strtolower($_GET['action'] == 'unpost')){
  $q_post   = $db->prepare("UPDATE `mst_po` SET `proforma`=? WHERE `id`=?");
  $q_post->execute(array(0, $_GET['id']));
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
elseif(isset($_GET['action']) && strtolower($_GET['action'] == 'delete')){
  $q_del    = $db->prepare("UPDATE `mst_po` SET `deleted`=? WHERE `id`=?");
  $q_del->execute(array(1, $_GET['id']));
  $affected_rows = $q_del->rowCount();

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
  <div class="ui-widget-content ui-corner-botton">
    <from id="filter_po" method="" action="" class="ui-helper-clearifx">
      <label for="" class="ui-helper-reset label-control">Tanggal PO</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_po" name="startdate_po" placeholder="Start Date" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_po" name="enddate_po" placeholder="End Date" readonly></td>
            <td> Filter <input type="text" id="filtervalue_po" name="filtervalue_po">(No Dokumen, Pemohon, Supplier)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadPO()" class="btn" type="button">Cari</button>
      </div>
    </from>
  </div>
</div>

<table id="table_po_archive"></table>
<div id="pager_table_po_archive"></div>

<script type="text/javascript">
  $('#startdate_po').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$('#enddate_po').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$( "#startdate_po" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
	$( "#enddate_po" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );

  function gridReloadPO(){
		var startdate_b2bdo_idx   =  ($("#startdate_po").val()).split("-");
		var enddate_b2bdo_idx     =  ($("#enddate_po").val()).split("-");
    
    var startdate             =  startdate_b2bdo_idx[2]+"-"+startdate_b2bdo_idx[1]+"-"+startdate_b2bdo_idx[0];
    var enddate               =  enddate_b2bdo_idx[2]+"-"+enddate_b2bdo_idx[1]+"-"+enddate_b2bdo_idx[0];

		var filterb2bdo_idx       = $("#filtervalue_po").val();

		var v_url ='<?php echo BASE_URL?>pages/transaksi_purchase/po_archive.php?action=json&startdate_po='+startdate+'&enddate_po='+enddate+'&filter='+filterb2bdo_idx;
		jQuery("#table_po_archive").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

  $(document).ready(function(){
    $('#table_po_archive').jqGrid({
      url       : '<?= BASE_URL.'pages/transaksi_purchase/po_archive.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID','Dokumen','Pemohon','Supplier','Tanggal PO','Estimasi Pengiriman','Total Qty','Total DPP','PPN','Grand Total','Note','Print','Cancel Proforma'],
      colModel  : [
        {name:'id', index: 'id', align: 'right', width:30, searchoptions: {sopt:['cn']}},
        {name:'dokumen', index: 'dokumen', align: 'left', width:50, searchoptions: {sopt:['cn']}},
        {name:'pemohon', index: 'pemohon', align: 'left', width:80, searchoptions: {sopt:['cn']}},
        {name:'supplier', index:'supplier', align: 'left', width:80, searchoptions: {sopt: ['cn']}},
        {name:'tanggal_po', index:'tanggal_po', align: 'center', width:50, searchoptions: {sopt: ['cn']}, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}},
        {name:'eta_pengiriman', index:'eta_pengiriman', align: 'center', width:50, searchoptions: {sopt: ['cn']}, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}},
        {name:'total_qty', index:'total_qty', align:'right', width:50, searchoptions: {sopt: ['cn']}},
        {name:'total_dpp', index:'total_odpp', align: 'right', width:50, searchoptions: {sopt: ['cn']}},
        {name:'ppn', index:'ppn', align:'right', width: 50, searchoptions: {sopt: ['cn']}},
        {name:'grand_total', index:'grand_total', align:'right', width: 50, searchoptions: {sopt: ['cn']}},
        {name:'note', index:'note', algin:'left', width: 85, searchoptions: {sopt: ['cn']}},
        {name: 'Print', index:'print', align:'center', width:40, sortable: false},
        {name: 'CancelProforma', index:'cancelproforma', align:'center', width:40, sortable: false},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_po_archive',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Arsip Purchase Order",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po_archive.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Produk / Jasa','QTY','Satuan','DPP/Unit','Subtotal'],
          width : [30,250,70,70,70,70],
          align : ['right','left','center','center','right','right'],
        }
      ],
    });
    $('#table_po_archive').jqGrid('navGrid', '#pager_table_po_archive', {edit:false, add:false, del:false});
  });
</script>