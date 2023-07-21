<?php 

require_once '../../include/config.php';
include "../../include/koneksi.php";

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post   = is_show_menu(POST_POLICY, arb2b, $group_acess);
$allow_add    = is_show_menu(ADD_POLICY, arb2b, $group_acess);
$allow_edit   = is_show_menu(EDIT_POLICY, arb2b, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, arb2b, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json'){
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  $startdate  = isset($_GET['startdate_arb2b'])?$_GET['startdate_arb2b']:date('Y-m-d');
  $enddate    = isset($_GET['enddate_arb2b'])?$_GET['enddate_arb2b']:date('Y-m-d'); 
  $filter     = $_GET['filter_arb2b'];

  $page       = isset($_GET['page'])?$_GET['page']:1;
  $limit      = isset($_GET['rows'])?$_GET['rows']:10;
  $sidx       = isset($_GET['sidx'])?$_GET['sidx']:'tgl_arb2b';
  $sord       = isset($_GET['sord'])?$_GET['sord']:''; 

  $where      = " WHERE mst.deleted=0 ";

  if($startdate != null && $startdate != ""){
    $where .= " AND tgl_ar BETWEEN '$startdate' AND '$enddate' ";
  }

  if($filter != null && $filter != ""){
    $where .= " AND tgl_ar LIKE '".$filter."' ";
  }

  $sql = "SELECT *, date_format(tgl_ar, '%d-%m-%Y') AS tanggal_ar FROM b2bar mst ";

  $q = $db->query($sql.$where);
  $count = $q->rowCount();

  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $q = $db->query($sql.$where."
    ORDER BY `".$sidx."` ".$sord."
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach($data1 as $line){
    $edit = $allow_edit? '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_b2b/arb2b_edit.php?id='.$line['id'].'\',\'table_b2bar\')" href="javascript:void(0);">Edit</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Edit</a>';
    $delete = $allow_delete ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/arb2b.php?action=delete&id='.$line['id'].'\',\'table_b2bar\')" href="javascript:void(0);">Delete</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Delete</a>';
    $post = $allow_post ? ($line['post'] == '0' ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/arb2b.php?action=post&postval=1&id='.$line['id'].'\',\'table_b2bar\')" href="javascript:void(0);">Post</a>' : '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/arb2b.php?action=post&postval=0&id='.$line['id'].'\',\'table_b2bar\')" href="javascript:void(0);">Unpost</a>') : ($line['post'] == '0' ? '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Post</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Unpost</a>');

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      $line['id'],
      $line['ar_num'],
      $line['tgl_ar'],
      number_format($line['total']),
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
} else if (isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id = $_GET['id'];

  $sql_do = "SELECT a.id_parent, b.id_trans, a.parent, c.nama, date_format(b.tgl_trans, '%d/%m/%Y') AS tgl_trans, b.totalfaktur FROM b2bar_detail a LEFT JOIN b2bdo b ON a.id_b2b=b.id LEFT JOIN mst_b2bcustomer c ON b.id_customer=c.id  WHERE a.parent = 'DO' AND a.id_parent='$id' AND a.deleted=0";

  $q1 = $db->query($sql_do);

  $sql_ret = "SELECT a.id_parent, b.b2breturn_num, a.parent, c.nama, date_format(b.tgl_return, '%d/%m/%Y') AS tgl_return , b.total FROM b2bar_detail a LEFT JOIN b2breturn b ON a.id_b2b=b.id LEFT JOIN mst_b2bcustomer c ON b.b2bcust_id=c.id WHERE a.parent = 'RETUR' AND a.id_parent='$id' AND a.deleted=0";

  $q2 = $db->query($sql_ret);

  $data1 = $q1->fetchAll(PDO::FETCH_ASSOC);
  $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);

  $i=0;
  $responce = '';

  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id_parent'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $line['id_trans'],
      $line['parent'],
      $line['nama'],
      $line['tgl_trans'],
      number_format($line['totalfaktur'],0)
    );
    $i++;
  }

  foreach($data2 as $line){
    $responce->rows[$i]['id']   = $line['id_parent'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $line['b2breturn_num'],
      $line['parent'],
      $line['nama'],
      $line['tgl_return'],
      number_format($line['total'],0)
    );
    $i++;
  }

  echo json_encode($responce);
  exit;
} else if (isset($_GET['action']) && strtolower($_GET['action']) == 'delete'){
  $delete_b2bretun  = $db->prepare("UPDATE `b2bar` SET `deleted` = 1 WHERE id='".$_GET['id']."'");

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
} else if (isset($_GET['action']) && strtolower($_GET['action']) == 'post'){
  $delete_b2bretun  = $db->prepare("UPDATE `b2bar` SET `post` = '".$_GET['postval']."' WHERE id='".$_GET['id']."'");

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
      <label for="" class="ui-helper-reset label-control">Tanggal AR B2B</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_arb2b" name="startdate_arb2b" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_arb2b" name="enddate_arb2b" readonly></td>
            <td> Filter <input type="text" id="filtervalue_b2bar" name="filtervalue_b2bar" />(Nomor Return B2B)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadB2BAr()" class="btn" type="button">Cari</button>
        <!-- <button onclick="printInRangeB2BAr()" class="btn" type="button">Print</button> -->
      </div>
    </form>
  </div>
</div>

<div class="btn_box">
  <?php
  if($allow_add){?>
    <a href="javascript: void(0)">
      <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL?>pages/sales_b2b/arb2b_add.php')">Tambah</button>
    </a>
  <?php } ?>
</div>

<table id="table_b2bar"></table>
<div id="pager_table_b2bar"></div>

<script>
  $('#startdate_arb2b').datepicker({
    dateFormat: "dd-mm-yy"
  });

  $('#enddate_arb2b').datepicker({
    dateFormat: "dd-mm-yy"
  });

  $( "#startdate_arb2b" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
	$( "#enddate_arb2b" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );

  function gridReloadB2BAr(){
    var startdate   = ($("#startdate_arb2b").val()).split("-");
		var enddate     = ($("#enddate_arb2b").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

		var filter      = $("#filtervalue_b2bar").val();

		var v_url       = '<?php echo BASE_URL?>pages/sales_b2b/arb2b.php?action=json&startdate_arb2b='+startdate+'&enddate_arb2b='+enddate+'&filter_arb2b='+filter;
		jQuery("#table_b2bar").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  $(document).ready(()=>{
    $('#table_b2bar').jqGrid({
      url           : '<?= BASE_URL.'pages/sales_b2b/arb2b.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['ID','Nomor AR B2B', 'Tanggal AR', 'Total AR', 'Keterangan', 'Post', 'Edit', 'Delete'],
      colModel      : [
        {name: 'id_b2bar', index: 'id_b2bar', align: 'right', width: 10, searchoptions: {sopt: ['cn']}},
        {name: 'b2brar_num', index: 'b2brar_num', align: 'left', width: 50, searchoptions:{sopt: ['cn']}},
        {name:'tanggal_b2bar', index: 'tanggal_b2bar', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'total_b2bar', index: 'total_b2bar', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'keterangan_b2bar', index: 'keterangan_b2bar', align: 'left', width: 80, searchoptions:{sopt: ['cn']}},
        {name: 'post', index: 'post', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'edit', index: 'edit', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'delete', index: 'delete', align: 'center', width: 20, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_b2bar',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "AR B2B",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid : true,
      subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/arb2b.php?action=json_sub'; ?>',
      subGridModel: [
          { 
            name : ['No','ID B2B','DO/RET','Customer','Tanggal','Total'], 
            width : [40,200,50,200,100,100],
            align : ['right','left','left','left','center','right'],
          } 
        ],
    });
    $('#table_b2bar').jqGrid('navGrid', '#pager_table_b2bar', {edit:false, add:false, del:false, search:false});
  });
</script>