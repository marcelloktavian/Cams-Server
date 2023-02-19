<?php require_once '../../include/config.php';


$group_acess  = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add    = is_show_menu(ADD_POLICY   , produkpo, $group_acess);
$allow_edit   = is_show_menu(EDIT_POLICY  , produkpo, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, produkpo, $group_acess);

if(isset($_GET['action']) && strtolower ($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

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

  $sql_purchproducts = "SELECT b.`vendor`,a.* FROM `mst_produk` a LEFT JOIN `mst_supplier` b on b.id = a.id_supplier ";
  $q = $db->query($sql_purchproducts.$where);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start<0) $start = 0;

  $q = $db->query($sql_purchproducts.$where." 
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
      $edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_purchase/purchproducts.php?action=edit&id='.$line['id'].'\',\'table_purchproducts\')" href="javascript:void(0);">Edit</a>';
    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
    
    if($allow_delete)
      $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_purchase/purchproducts.php?action=delete&id='.$line['id'].'\',\'table_purchproducts\')" href="javascript:;">Delete</a>';
    else
    $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      $line['id'],
      $line['produk_jasa'],
      $line['vendor'],
      $line['tgl_quotation'],
      $line['satuan'],
      number_format($line['harga'],0),
      $line['kategori'],
      number_format($line['penyusutan'],0),
      $line['hpp'] == 0 ? 'Tidak':'Iya', 
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
  include 'purchproducts_form.php';exit();
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
  $stmt = $db->prepare("UPDATE `mst_produk` SET deleted=? WHERE id=?");
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
  $getIdSupplier = $db->prepare("SELECT id FROM `mst_supplier` WHERE `vendor`=:vendor");
  $getIdSupplier->execute(array(':vendor'=>$_POST['supplier']));

  $idSupplier = $getIdSupplier->fetch(PDO::FETCH_ASSOC);

  if (is_array($idSupplier) || is_object($idSupplier))
  {
    foreach($idSupplier as $dataId){
      $id_mst = $dataId['id'];
    }
  } else {
    $id_mst = $idSupplier['id'];
  }

  if(isset($_POST['id'])){
    $stmt = $db->prepare("UPDATE `mst_produk` SET `produk_jasa`=?, `id_supplier`=?, `tgl_quotation`=?, `satuan`=?, `harga`=?, `kategori`=?, `penyusutan`=?, `hpp`=? ,`lastmodified`=NOW() WHERE id=?");
    $stmt->execute(array(strtoupper($_POST['produk_jasa']), $id_mst, $_POST['tgl_quotation'], strtoupper($_POST['satuan']), $_POST['harga'], strtoupper($_POST['kategori']), $_POST['penyusutan'], $_POST['hpp'], $_POST['id']));

    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Success';
    } else {
      $r['stat'] = 0; $r['message'] = 'Failed';
    }
  } else {
    $stmt = $db->prepare("INSERT INTO `mst_produk` (`produk_jasa`,`id_supplier`,`tgl_quotation`, `satuan`, `harga`, `kategori`, `penyusutan`, `hpp`, `lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if($stmt->execute(array(strtoupper($_POST['produk_jasa']), '0', $_POST['tgl_quotation'], strtoupper($_POST['satuan']), $_POST['harga'], strtoupper($_POST['kategori']), $_POST['penyusutan'], $_POST['hpp']))){
      $r['stat'] = 1; $r['message'] = 'Success';
    } else {
      $r['stat'] = 0; $r['message'] = 'Failed';
    }
  }
  echo json_encode($r);
  exit;
}
?>

<table id="table_purchproducts"></table>
<div id="pager_table_purchproducts"></div>

<div class="btn_box">
  <?php if ($allow_add) {?>
    <a href="javascript: void(0)">
    <?php echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_purchase/purchproducts.php?action=add\',\'table_purchproducts\')" class="btn">Tambah</button>'; ?>
  <?php } ?>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#table_purchproducts').jqGrid({
      url       : '<?php echo BASE_URL.'pages/master_purchase/purchproducts.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID', 'Produk / Jasa', 'Supplier', 'Tanggal Quotation', 'Satuan', 'DPP/Unit', 'Kategori', 'Bulan Penyusutan', 'Mempengaruhi HPP', 'Edit', 'Delete'],
      colModel  : [
        {name:'id', index:'id', align:'right', width:30, searchoptions: {sopt:['cn']}},
        {name:'produk_jasa', index:'produk_jasa', searchoptions: {sopt:['cn']}},
        {name:'supplier', index:'supplier', searchoptions: {sopt:['cn']}},
        {name:'tgl_quotation', index:'tgl_quotation', align:'center', width:90, searchoptions: {sopt:['cn']}, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}},
        {name:'satuan', index:'satuan', align:'center', searchoptions: {sopt:['cn']}, width:100},
        {name:'harga', index:'harga', align:'right', searchoptions: {sopt:['cn']}},
        {name:'kategori', index:'kategori', align:'center', width:100, searchoptions: {sopt:['cn']}},
        {name:'penyusutan', index:'penyusutan', align:'right', width:90, searchoptions: {sopt:['cn']}},
        {name:'hpp', index:'hpp', align:'center', width:90, searchoptions: {sopt:['cn']}},
        {name:'Edit', index:'edit', align:'center', width:50, sortable: false, search: false},
        {name:'Delete', index:'delete', align:'center', width:50, sortable: false, search: false},
      ],
      rowNum    : 20,
      rowList   : [10, 20, 30],
      pager     : '#pager_table_purchproducts',
      sortname  : 'produk_jasa',
      autowidth : true,
      height    : '460',
      viewrecords : true,
      rownumbers  : true,
      sortorder   : 'asc',
      caption     : "Purchase Products Data",
      ondblClickRow : function(rowid){
        alert(rowid);
      }
    });
    $('#table_purchproducts').jqGrid('navGrid', '#pager_table_purchproducts', {edit:false, add:false, del:false});
  });
</script>
