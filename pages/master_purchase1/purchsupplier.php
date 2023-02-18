<?php require_once '../../include/config.php';

$group_access  = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add    = is_show_menu(ADD_POLICY   , supplier, $group_access);
$allow_edit   = is_show_menu(EDIT_POLICY  , supplier, $group_access);
$allow_delete = is_show_menu(DELETE_POLICY, supplier, $group_access);

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
  
  $sql_purchpemohon = "SELECT a.* FROM `mst_supplier` a";
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

  $response['page']     = $page;
  $response['total']    = $total_pages;
  $response['records']  = $count;

  $i=0;
  foreach($data1 as $line){
    if($allow_edit)
      $edit = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/master_purchase/purchsupplier_edit.php?id='.$line['id'].'\',\'table_purchsupplier\')" href="javascript:void(0);">Edit</a>';
    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
    
    if($allow_delete)
      $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_purchase/purchsupplier.php?action=delete&id='.$line['id'].'\',\'table_purchsupplier\')" href="javascript:;">Delete</a>';
    else
    $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';

    if($line['pkp'] == '1'){
      $line['pkp'] = 'Iya';
    }else{
      $line['pkp'] = 'Tidak';
    }

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      $line['id'],
      $line['vendor'],
      $line['pic'],
      number_format($line['item'],0),
      $line['alamat'],
      $line['telp'],
      $line['email'],
      $line['ktp'],
      $line['npwp'],
      $line['pkp'],
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

  $query = 'SELECT *, date_format(tgl_quotation,"%d-%m-%Y") as tgl FROM `mst_produk` WHERE `id_supplier`='.$id;

  $exe   = $db->query($query);
  $count = $exe->rowCount();
  $data1 = $exe->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  $responce = '';

  foreach ($data1 as $line){

    if ($line['penyusutan'] == '0'){
      $penyusutanValue = '-';
    } else {
      $penyusutanValue = $line['penyusutan'];
    }

    $responce->rows[$i]['id']   = $line['id'];
    $responce->rows[$i]['cell'] = array(
      $i + 1,
      $line['produk_jasa'],
      $line['tgl'],
      $line['kategori'],
      $penyusutanValue,
      $line['satuan'],
      number_format($line['harga'],0),
    );
    $i ++;
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && (strtolower($_GET['action']) == 'add')){
  include 'purchsupplier_add.php';exit();
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete'){
  $stmt = $db->prepare("UPDATE `mst_supplier` SET deleted=? WHERE id=?");
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
?>
<table id="table_purchsupplier"></table>
<div id="pager_table_purchsupplier"></div>

<div class="btn_box">
  <?php if ($allow_add) {?>
    <a href="javascript: void(0)">
      <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL ?>pages/master_purchase/purchsupplier.php?action=add');">Tambah</button>
    </a>
  <?php }?>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#table_purchsupplier').jqGrid({
      url           : '<?php echo BASE_URL.'pages/master_purchase/purchsupplier.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['ID', 'Supplier', 'PIC', 'Total Product', 'Alamat', 'Contact', 'Email', 'KTP','NPWP', 'PKP', 'Edit', 'Delete'],
      colModel      : [
        {name:'id', index:'id', align:'right', width:30, searchoptions: {sopt:['cn']}},
        {name:'vendor', index:'vendor', width:250, searchoptions: {sopt:['cn']}},
        {name:'pic', index:'pic', searchoptions: {sopt:['cn']}},
        {name:'produk', index:'item', align:'center', width:100, searchoptions: {sopt:['cn']}},
        {name:'alamat', index:'alamat', searchoptions: {sopt:['cn']}},
        {name:'contact', index:'contact', searchoptions: {sopt:['cn']}},
        {name:'email', index:'email', searchoptions: {sopt:['cn']}},
        {name:'ktp', index:'ktp', searchoptions: {sopt:['cn']}},
        {name:'npwp', index:'npwp', searchoptions: {sopt:['cn']}},
        {name:'pkp', index:'pkp', width:40, align:'center', searchoptions: {sopt:['cn']}},
        {name:'Edit', index:'edit', align:'center', width:50, sortable: false, search: false},
        {name:'Delete', index:'delete', align:'center', width:50, sortable: false, search: false},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_purchsupplier',
      sortname      : 'vendor',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'asc',
      caption       : "Supplier Data",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/master_purchase/purchsupplier.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Produk / Jasa','Tanggal Quotation','Kategori','Bulan Penyusutan','Satuan','Harga'],
          width : [30,250,100,70,70,70,70],
          align : ['right','left','center','center','center','center','right'],
        }
      ],
    });
    $('#table_purchsupplier').jqGrid('navGrid', '#pager_table_purchsupplier', {edit:false, add:false, del:false});
  });
</script>