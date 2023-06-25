<?php 

require_once '../../include/config.php';
include "../../include/koneksi.php";

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post   = is_show_menu(POST_POLICY, trb2breturn, $group_acess);
$allow_add    = is_show_menu(ADD_POLICY, trb2breturn, $group_acess);
$allow_edit   = is_show_menu(EDIT_POLICY, trb2breturn, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, trb2breturn, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  $startdate = isset($_GET['startdate_b2breturn'])?$_GET['startdate_b2breturn']:date('Y-m-d');
  $enddate = isset($_GET['enddate_b2breturn'])?$_GET['enddate_b2breturn']:date('Y-m-d'); 
  $filter=$_GET['filter'];
  $status=$_GET['status'];

  $page = isset($_GET['page'])?$_GET['page']:1;
  $limit = isset($_GET['rows'])?$_GET['rows']:10;
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_return';
  $sord = isset($_GET['sord'])?$_GET['sord']:''; 

  $where = " WHERE ret.deleted=0 ";

  if($startdate != null && $startdate != ""){
    $where .= " AND tgl_return BETWEEN '$startdate' AND '$enddate' ";
  }

  if($filter != null && $filter != ""){
    $where .= " AND b2breturn_num LIKE '".$filter."' ";
  }

  if($status != null && $status != ""){
    if($status == "posted"){
      $where .= "AND post LIKE '1' ";
    }
    else if($status == "unposted"){
      $where .= "AND post LIKE '0' ";
    }
  }

  $sql = "SELECT ret.*, cat.nama AS kategori FROM b2breturn ret LEFT JOIN mst_b2bcategory_sale cat ON ret.id_kategori=cat.id ".$where;

  $q = $db->query($sql);
  $count = $q->rowCount();

  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $q = $db->query($sql."
    ORDER BY `".$sidx."` ".$sord."
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach($data1 as $line){
    $post = $allow_post ? ($line['post'] == '0' ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=post&id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Post</a>': '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=unpost&id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Unpost</a>') : ($line['post'] == '0' ? '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Post</a>': '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Unpost</a>');
    $edit = $allow_edit ? '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_b2b/trb2breturn_edit.php?id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Edit</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Edit</a>';
    $delete = $allow_delete ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=delete&id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Delete</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Delete</a>';

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      $line['id'],
      $line['b2breturn_num'],
      $line['tgl_return'],
      number_format($line['total']),
      $line['kategori'],
      $line['keterangan'],
      $post,
      $edit,
      $delete
    );
    $i++;
  }

  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
} else if(isset($_GET['action']) && strtolower($_GET['action']) == 'delete'){
  $delete_b2bretun  = $db->prepare("UPDATE `b2breturn` SET `deleted` = 1 WHERE id='".$_GET['id']."'");

  $delete_b2bretun->execute();
  $affected_rows = $delete_b2bretun->rowCount();

  if($affected_rows > 0){
    $r['stat'] = 1; $r['message'] = 'Succes';
  }
  else{
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
else if(isset($_GET['action']) && strtolower($_GET['action']) == 'post'){
  $delete_b2bretun  = $db->prepare("UPDATE `b2breturn` SET `post` = 1 WHERE id='".$_GET['id']."'");

  $delete_b2bretun->execute();
  $affected_rows = $delete_b2bretun->rowCount();

  if($affected_rows > 0){
    $r['stat'] = 1; $r['message'] = 'Succes';
  }
  else{
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
else if(isset($_GET['action']) && strtolower($_GET['action']) == 'unpost'){
  $delete_b2bretun  = $db->prepare("UPDATE `b2breturn` SET `post` = 0 WHERE id='".$_GET['id']."'");

  $delete_b2bretun->execute();
  $affected_rows = $delete_b2bretun->rowCount();

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

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_ap" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Tanggal Return B2B</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_b2breturn" name="startdate_b2breturn" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_b2breturn" name="enddate_b2breturn" readonly></td>
            <td> Status <select id="statusvalue_b2breturn" name="statusvalue_b2breturn">
              <option value="posted">Sudah Dipost</option>
              <option value="unposted">Belum Dipost</option>
              <option value="all" selected>Semua</option>
            </select></td>
            <td> Filter <input type="text" id="filtervalue_b2breturn" name="filtervalue_b2breturn" />(Nomor Return B2B)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadB2BReturn()" class="btn" type="button">Cari</button>
        <!-- <button onclick="printInRangeB2BReturn()" class="btn" type="button">Print</button> -->
      </div>
    </form>
  </div>
</div>

<div class="btn_box">
  <?php
  if($allow_add){?>
    <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL?>pages/sales_b2b/trb2breturn_add.php')">Tambah Return</button>
  <?php } ?>
</div>

<table id="table_b2breturn"></table>
<div id="pager_table_b2breturn"></div>

<script type="text/javascript">
  $('#startdate_b2breturn').datepicker({
    dateFormat: "dd-mm-yy"
  });

  $('#enddate_b2breturn').datepicker({
    dateFormat: "dd-mm-yy"
  });

  $( "#startdate_b2breturn" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
	$( "#enddate_b2breturn" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );

  function gridReloadB2BReturn(){
    var startdate   = ($("#startdate_b2breturn").val()).split("-");
		var enddate     = ($("#enddate_b2breturn").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

		var filter      = $("#filtervalue_b2breturn").val();

    var status      = $("#statusvalue_b2breturn").val();

		var v_url       = '<?php echo BASE_URL?>pages/sales_b2b/trb2breturn.php?action=json&startdate_b2breturn='+startdate+'&enddate_b2breturn='+enddate+'&filter='+filter+'&status='+status;
		jQuery("#table_b2breturn").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(()=>{
    $('#table_b2breturn').jqGrid({
      url           : '<?= BASE_URL.'pages/sales_b2b/trb2breturn.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['ID','Nomor B2B Return','Tanggal Return', 'Total Return', 'Type', 'Keterangan', 'Post', 'Edit', 'Delete'],
      colModel      : [
        {name: 'id_b2breturn', index: 'id_b2breturn', align: 'right', width: 10, searchoptions: {sopt: ['cn']}},
        {name: 'b2breturn_num', index: 'b2breturn_num', align: 'center', width: 50, searchoptions:{sopt: ['cn']}},
        {name:'tanggal_b2breturn', index: 'tanggal_b2breturn', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'total_b2breturn', index: 'total_b2breturn', align: 'left', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'type_b2breturn', index: 'type_b2breturn', align: 'center', width: 10, searchoptions:{sopt: ['cn']}},
        {name: 'keterangan_b2breturn', index: 'keterangan_b2breturn', align: 'center', width: 80, searchoptions:{sopt: ['cn']}},
        {name: 'post', index: 'post', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'edit', index: 'edit', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'delete', index: 'delete', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_b2breturn',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "B2B Return",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
    });
    $('#table_b2breturn').jqGrid('navGrid', '#pager_table_b2breturn', {edit:false, add:false, del:false, search:false});
  });
</script>