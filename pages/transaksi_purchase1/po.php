<?php require_once '../../include/config.php';

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

  if(!$sidx) $sidx = 1;

   // << searching _filter ------------------------------
  if($_REQUEST['_search']=='false'){
    $where = ' WHERE a.deleted=0 ';
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
    $where = sprintf(" WHERE a.deleted=0 AND %s ". $operations[$_REQUEST['searchOper']], $_REQUEST['searchField'], $value);
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

  $i=0;
  foreach($data1 as $line){
    if($allow_edit)
      $edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_purchase/po_edit.php?id='.$line['id'].'\',\'table_po\')" href="javascript:void(0);">Edit</a>';
    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
    
    if($allow_delete)
      $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po.php?action=delete&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Delete</a>';
    else
      $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';

    if($allow_post)
      $post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po.php?action=post&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Post</a>';

    else
      $post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      $line['id'],
      $line['dokumen'],
      $line['pemohon'],
      $line['nama_supplier'],
      $line['tgl_po'],
      $line['eta_pengiriman'],
      $line['total_order'],
      $line['total_qty'],
      $line['ppn'],
      $line['total_dpp_order'],
      $line['catatan'],
      $post,
      $edit,
      $delete,
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
      $line['harga'],
      $line['subtotal'],
    );
    $i++;
  }
  echo  json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add'){
  include 'po_add.php'; exit(); exit;
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

<table id="table_po"></table>
<div id="pager_table_po"></div>

<div class="btn_box">
  <?php
    if ($allow_add){ ?>
    <a href="javascript: void(0)">
      <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL?>pages/transaksi_purchase/po.php?action=add')">Tambah</button>
    </a>
  <?php }?>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table_po').jqGrid({
      url       : '<?= BASE_URL.'pages/transaksi_purchase/po.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID','Dokumen','Pemohon','Supplier','Tanggal PO','ETA Pengiriman','Total Order','Total Qty','PPN','Total DPP','Note','Approval','Edit','Delete'],
      colModel  : [
        {name:'id', index: 'id', align: 'right', width:30, searchoptions: {sopt:['cn']}},
        {name:'dokumen', index: 'dokumen', align: 'left', width:50, searchoptions: {sopt:['cn']}},
        {name:'pemohon', index: 'pemohon', align: 'left', width:80, searchoptions: {sopt:['cn']}},
        {name:'supplier', index:'supplier', align: 'left', width:80, searchoptions: {sopt: ['cn']}},
        {name:'tanggal_po', index:'tanggal_po', algin: 'center', width:50, searchoptions: {sopt: ['cn']}},
        {name:'eta_pengiriman', index:'eta_pengiriman', align: 'center', width:50, searchoptions: {sopt: ['cn']}},
        {name:'total_order', index:'total_order', align: 'right', width:50, searchoptions: {sopt: ['cn']}},
        {name:'total_qty', index:'total_qty', align:'right', width:50, searchoptions: {sopt: ['cn']}},
        {name:'ppn', index:'ppn', align:'right', width: 50, searchoptions: {sopt: ['cn']}},
        {name:'total_dpp', index:'total_dpp', align:'right', width: 50, searchoptions: {sopt: ['cn']}},
        {name:'note', index:'note', algin:'left', width: 85, searchoptions: {sopt: ['cn']}},
        {name: 'Approval', index:'approval', align:'center', width:40, sortable: false},
        {name:'Edit', index:'edit', align:'center', width:40, sortable: false, search: false},
        {name:'Delete', index:'delete', align:'center', width:40, sortable: false, search: false},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_po',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Purchase Order Data",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Produk / Jasa','QTY','Satuan','DPP Produk','Subtotal'],
          width : [30,250,70,70,70,70],
          align : ['right','left','center','center','center','right'],
        }
      ],
    });
    $('#table_po').jqGrid('navGrid', '#pager_table_po', {edit:false, add:false, del:false});
  });
</script>