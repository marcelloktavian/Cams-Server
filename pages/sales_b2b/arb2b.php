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

  $startdate = isset($_GET['startdate_arb2b'])?$_GET['startdate_arb2b']:date('Y-m-d');
  $enddate = isset($_GET['enddate_arb2b'])?$_GET['enddate_arb2b']:date('Y-m-d'); 
  $filter=$_GET['filter_arb2b'];

  $page = isset($_GET['page'])?$_GET['page']:1;
  $limit = isset($_GET['rows'])?$_GET['rows']:10;
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_arb2b';
  $sord = isset($_GET['sord'])?$_GET['sord']:''; 

  $where = " WHERE mst.deleted=0 ";

  if($startdate != null && $startdate != ""){
    $where .= " AND tgl_ar BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
  }

  if($filter != null && $filter != ""){
    $where .= " AND tgl_ar LIKE '".$filter."' ";
  }

  $sql = "SELECT *, date_format(tgl_ar, '%d-%m-%Y') AS tanggal_ar FROM b2bar mst ";

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
    $edit = $allow_delete ? '<a href="javascript:void(0);">Edit</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Edit</a>';
    $delete = $allow_delete ? '<a href="javascript:void(0);">Delete</a>' : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Delete</a>';

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      $line['id'],
      $line['ar_num'],
      $line['tgl_ar'],
      number_format($line['total']),
      $line['keterangan'],
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
      colNames      : ['ID','Nomor AR B2B', 'Tanggal AR', 'Total AR', 'Keterangan', 'Edit', 'Delete'],
      colModel      : [
        {name: 'id_b2bar', index: 'id_b2bar', align: 'right', width: 10, searchoptions: {sopt: ['cn']}},
        {name: 'b2brar_num', index: 'b2brar_num', align: 'left', width: 50, searchoptions:{sopt: ['cn']}},
        {name:'tanggal_b2bar', index: 'tanggal_b2bar', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'total_b2bar', index: 'total_b2bar', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'keterangan_b2bar', index: 'keterangan_b2bar', align: 'left', width: 80, searchoptions:{sopt: ['cn']}},
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
    });
    $('#table_b2bar').jqGrid('navGrid', '#pager_table_b2bar', {edit:false, add:false, del:false, search:false});
  });
</script>