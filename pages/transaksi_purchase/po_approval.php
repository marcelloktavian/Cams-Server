<?php require_once '../../include/config.php';

$group_access   = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY   , poapproval, $group_access);
$allow_edit     = is_show_menu(EDIT_POLICY  , poapproval, $group_access);
$allow_delete   = is_show_menu(DELETE_POLICY, poapproval, $group_access);
$allow_post     = is_show_menu(POST_POLICY  , poapproval, $group_access);

if(isset($_GET['action']) && strtolower($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

  if(!$sidx) $sidx = 1;

   // << searching _filter ------------------------------
  if($_REQUEST['_search']=='false'){
    $where = ' WHERE a.approval=1 AND a.proforma=0 AND a.deleted=0 ';
  }else {
    $operations = array (
      'eq' => "= '%s'",            // Equal
      'ne' => "<> '%s'",           // Not equal
      'lt' => "< '%s'",            // Less than
      'le' => "<= '%s'",           // Less than or equal
      'gt' => "> '%s'",            // Greater than
      'ge' => ">= '%s'",           // Greater or equal
      'bw' => "like '%s%%'",       // Begins With
      'bn' => "not like '%s%%'",   // Does not begin with
      'in' => "in ('%s')",         // In
      'ni' => "not in ('%s')",     // Not in
      'ew' => "like '%%%s'",       // Ends with
      'en' => "not like '%%%s'",   // Does not end with
      'cn' => "like '%%%s%%'",     // Contains
      'nc' => "not like '%%%s%%'", // Does not contain
      'nu' => "is null",           // Is null
      'nn' => "is not null"        // Is not null
    );
    $value = $_REQUEST['searchString'];
    $where = sprintf(" WHERE a.approval=1 AND a.proforma=0 AND a.deleted=0 AND %s ". $operations[$_REQUEST['searchOper']], $_REQUEST['searchField'], $value);
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
    if($allow_edit)
      $edit = '<a onclick="javascript:window_open(\''.BASE_URL.'pages/transaksi_purchase/po_edit.php?id='.$line['id'].'\',\'table_po_approval\')" href="javascript:void(0);">Edit</a>';
    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';

    if($allow_delete)
      $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=delete&id='.$line['id'].'\',\'table_po_approval\')" href="javascript:;">Delete</a>';
    else
      $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';

    if($allow_post)
      $post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=post&id='.$line['id'].'\',\'table_po_approval\')" href="javascript:;">Post</a>';

    else
      $post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';

    if($allow_post)
      $unpost = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_approval.php?action=unpost&id='.$line['id'].'\',\'table_po_approval\')" href="javascript:;">Unpost</a>';

    else
      $unpost = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Unpost</a>';

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
      $post,
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
  $q_post   = $db->prepare("UPDATE `mst_po` SET `approval`=? WHERE `id`=?");
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

<table id="table_po_approval"></table>
<div id="pager_table_po_approval"></div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table_po_approval').jqGrid({
      url       : '<?= BASE_URL.'pages/transaksi_purchase/po_approval.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID','Dokumen','Pemohon','Supplier','Tanggal PO','Estimasi Pengiriman','Total Qty','Total DPP','PPN','Grand Total','Note','Proforma','Cancel Approval'],
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
        {name: 'Proforma', index:'proforma', align:'center', width:40, sortable: false},
        {name: 'CancelApproval', index:'cancelapproval', align:'center', width:40, sortable: false},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_po_approval',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Purchase Order Approval",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po_approval.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Produk / Jasa','QTY','Satuan','DPP/Unit','Subtotal'],
          width : [30,250,70,70,70,70],
          align : ['right','left','center','center','right','right'],
        }
      ],
    });
    $('#table_po_approval').jqGrid('navGrid', '#pager_table_po_approval', {edit:false, add:false, del:false});
  });
</script>