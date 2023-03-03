<?php require_once '../../include/config.php'; 

$group_acess  = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add    = is_show_menu(ADD_POLICY   , pemohonpo, $group_acess);
$allow_edit   = is_show_menu(EDIT_POLICY  , pemohonpo, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, pemohonpo, $group_acess);

if(isset($_GET['action']) && strtolower ($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

  $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
  $limit = isset($_GET['rows'])?$_GET['rows']:20; // get how many rows we want to have into the grid
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'pemohon'; // get index row - i.e. user click to sort
  $sord = isset($_GET['sord'])?$_GET['sord']:'';

  if(!$sidx) $sidx=1;

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

  $sql_purchpemohon = "SELECT a.* FROM `mst_pemohon_po` a";
  $q = $db->query($sql_purchpemohon.$where);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start<0) $start = 0;

  $q = $db->query($sql_purchpemohon.$where." 
    ORDER BY `".$sidx."` ".$sord." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;

  $i=0;
  foreach($data1 as $line){
    if($allow_edit)
      $edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_purchase/purchpemohon.php?action=edit&id='.$line['id'].'\',\'table_purchpemohon\')" href="javascript:void(0);">Edit</a>';
    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
    
    if($allow_delete)
      $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_purchase/purchpemohon.php?action=delete&id='.$line['id'].'\',\'table_purchpemohon\')" href="javascript:;">Delete</a>';
    else
    $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      $line['id'],
      $line['pemohon'],
      $line['keterangan'],
      $edit,
      $delete,
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }

  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && (strtolower($_GET['action']) == 'add' || strtolower($_GET['action']) == 'edit')) {
  include 'purchpemohon_form.php';exit();
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
  $stmt = $db->prepare("UPDATE `mst_pemohon_po` SET deleted=? WHERE id=?");
  $stmt->execute(array(1, $_GET['id']));
  $affected_rows = $stmt->rowCount();

  if($affected_rows > 0) {
    $r['stat'] = 1; $r['message'] = 'Success';
  }
  else {
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
  if(isset($_POST['id'])){
    $stmt = $db->prepare("UPDATE `mst_pemohon_po` SET `pemohon`=?, `keterangan`=?, `lastmodified`=NOW() WHERE id=?");
    $stmt->execute(array(strtoupper($_POST['pemohon']), strtoupper($_POST['keterangan']), $_POST['id']));

    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Success';
    } else {
      $r['stat'] = 0; $r['message'] = 'Failed';
    }
  } else {
    $stmt = $db->prepare("INSERT INTO `mst_pemohon_po` (`pemohon`,`keterangan`,`lastmodified`) VALUES(?, ?, NOW())");

    if($stmt->execute(array(strtoupper($_POST['pemohon']),strtoupper($_POST['keterangan'])))){
      $r['stat'] = 1; $r['message'] = 'Success';
    } else {
      $r['stat'] = 0; $r['message'] = 'Failed';
    }
  }
  echo json_encode($r);
  exit;
}
?>
<table id="table_purchpemohon"></table>
<div id="pager_table_purchpemohon"></div>

<div class="btn_box">
  <?php if ($allow_add) {?>
    <a href="javascript: void(0)">
      <?php echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_purchase/purchpemohon.php?action=add\',\'table_purchpemohon\')" class="btn">Tambah</button>'; ?>
  <?php } ?>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#table_purchpemohon').jqGrid({
      url       : '<?php echo BASE_URL.'pages/master_purchase/purchpemohon.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID', 'Nama Pemohon', 'Keterangan', 'Edit', 'Delete'],
      colModel  : [
        {name:'id', index:'id', align:'right', width:30, searchoptions: {sopt:['cn']}},
        {name:'pemohon', index:'pemohon', width:100, searchoptions: {sopt:['cn']}},
        {name:'keterangan', index:'keterangan', searchoptions: {sopt:['cn']}},
        {name:'Edit', index:'edit', align:'center', width:50, sortable: false, search: false},
        {name:'Delete', index:'delete', align:'center', width:50, sortable: false, search: false},
      ],
      rowNum    : 20,
      rowList   : [10, 20, 30],
      pager     : '#pager_table_purchpemohon',
      sortname  : 'pemohon',
      autowidth : true,
      height    : '460',
      viewrecords : true,
      rownumbers  : true,
      sortorder   : 'asc',
      caption     : "Pemohon PO Data",
      ondblClickRow : function(rowid){
        alert(rowid);
      }
    });
    $('#table_purchpemohon').jqGrid('navGrid', '#pager_table_purchpemohon', {edit:false, add:false, del:false});
  });
</script>
