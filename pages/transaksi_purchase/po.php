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

  if(!$sidx) $sidx = 1;

  // << searching _filter ------------------------------
  if(isset($_GET['filter']) && $_GET['filter'] != ''){
    $filter_value = " AND (`dokumen` LIKE '%".$_GET['filter']."%' OR `nama_supplier` LIKE '%".$_GET['filter']."%' OR `nama_pemohon` LIKE '%".$_GET['filter']."%') ";
  }
  else{
    $filter_value = '';
  }

  if((!isset($_GET['startdate_po']) && !isset($_GET['enddate_po']))||($_GET['startdate_po'] == '' && $_GET['enddate_po'] == '')){
    $where = " WHERE a.deleted=0 AND tgl_po>='".date("Y-m-d")."' AND tgl_po<='".date("Y-m-d")."'".$filter_value;
  }else if($_GET['startdate_po'] != '' && $_GET['enddate_po'] == ''){
    $where = " WHERE a.deleted=0 AND tgl_po>='".$_GET['startdate_po']."'".$filter_value;
  }
  else if($_GET['startdate_po'] == '' && $_GET['enddate_po'] != ''){
    $where = " WHERE a.deleted=0 AND tgl_po<='".$_GET['enddate_po']."'".$filter_value;
  }
  else{
    $where = " WHERE a.deleted=0 AND tgl_po>='".$_GET['startdate_po']."' AND tgl_po<='".$_GET['enddate_po']."'".$filter_value;
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

    if($allow_edit){
      if($line['approval'] == '1'){
        $edit = '<a onclick="javascript:custom_alert(\'PO yang sudah diapprove tidak dapat diedit.\')" href="javascript:;">Edit</a>';
      }
      else{
        $edit = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/transaksi_purchase/po_edit.php?id='.$line['id'].'\',\'table_po\')" href="javascript:void(0);">Edit</a>';
      }
    }

    else
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';

    if($allow_post){
      if($line['approval'] == '1' && $line['proforma'] == '1'){
        $postApproval = '<a onclick="javascript:custom_alert(\'Harap Unpost Invoice Terlebih Dahulu\')" href="javascript:;" disabled>Unpost</a>';
      }
      else if($line['approval'] == '1' && $line['proforma'] == '0'){
        $postApproval = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_purchase/po_post_pass.php?action=approval&val=0&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Unpost</a>';
      }
      else{
        $postApproval = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_purchase/po_post_pass.php?action=approval&val=1&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Post</a>';
      }
    }

    else{
      $postApproval = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';
    }

    if($line['approval'] == '1'){
      $print = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/transaksi_purchase/po_print.php?id='.$line['id'].'\',\'table_po\')" href="javascript:;">Print</a>';
    }
    
    else{
      $print = '<a onclick="javascript:custom_alert(\'PO harus diapprove terlebih dahulu\')" href="javascript:;">Print</a>';
    }

    if($allow_delete){
      if($line['approval'] == '1'){
        $delete = '<a onclick="javascript:custom_alert(\'PO yang telah diapprove tidak dapat dihapus\')" href="javascript:;">Delete</a>';
      }
      else{
        $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po.php?action=delete&id='.$line['id'].'\',\'table_po\')" href="javascript:void(0);">Delete</a>';
      }
    }

    else{
      $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';
    } 

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
      $postApproval,
      $edit,
      $delete,
      $print,
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id = $_GET['id'];

  $query = "SELECT *,date_format(tgl_quotation, '%d/%m/%Y') as tanggal_quotation_formatted FROM `det_po` WHERE `id_po`='".$id."' AND deleted = 0";

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
      $line['tanggal_quotation_formatted'],
      $line['qty'],
      $line['satuan'],
      number_format($line['price'],0),
      number_format($line['subtotal'],0),
      $line['nomor_akun'],
      $line['nama_akun']
    );
    $i++;
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add'){
  include 'po_add.php'; exit(); exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process_pass'){
  $id_user=$_SESSION['user']['username'];

	//cek apakah pass sama atau tidak
  $stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('".$_POST['pass_po']."') AND (user_id=17 OR user_id=3 OR user_id=13 OR user_id=10)");
  $stmt->execute();

  $affected_rows = $stmt->rowCount();
  if($affected_rows > 0){
    $q_post   = $db->prepare("UPDATE `mst_po` SET `approval`=? WHERE `id`=?");
    $q_post->execute(array($_GET['val'], $_POST['id_po']));
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
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'approval'){
  $q_post   = $db->prepare("UPDATE `mst_po` SET `approval`=? WHERE `id`=?");
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
elseif(isset($_GET['action']) && strtolower($_GET['action'] == 'unpost')){
  $q_post   = $db->prepare("UPDATE `mst_po` SET `proforma`=?, `nomor_invoice`=?, `tanggal_invoice`=?, `tanggal_jatuh_tempo`=?, `keterangan_invoice`=? WHERE `id`=?");
  $q_post->execute(array(0,null,null,null,null, $_GET['id']));
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
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'invoice_submit'){
  $q_post   = $db->prepare("UPDATE `mst_po` SET `proforma`=?, `nomor_invoice`=?, `tanggal_invoice`=?, `tanggal_jatuh_tempo`=?, `keterangan_invoice`=? WHERE `id`=?");
  $q_post->execute(array(1,$_POST['nomor_invoice'],$_POST['tanggal_invoice'],$_POST['tanggal_jatuh_tempo'],$_POST['keterangan_invoice'], $_POST['id']));
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

		var v_url ='<?php echo BASE_URL?>pages/transaksi_purchase/po.php?action=json&startdate_po='+startdate+'&enddate_po='+enddate+'&filter='+filterb2bdo_idx;
		jQuery("#table_po").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

  $(document).ready(function(){
    $('#table_po').jqGrid({
      url       : '<?= BASE_URL.'pages/transaksi_purchase/po.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['ID','Dokumen','Pemohon','Supplier','Tgl PO','ETA Pengiriman','Total Qty','Total DPP','PPN','Grand Total','Catatan','Approval','Edit','Delete','Print'],
      colModel  : [
        {name:'id', index: 'id', align: 'right', width:15, searchoptions: {sopt:['cn']}},
        {name:'dokumen', index: 'dokumen', align: 'left', width:40, searchoptions: {sopt:['cn']}},
        {name:'pemohon', index: 'pemohon', align: 'left', width:60, searchoptions: {sopt:['cn']}},
        {name:'supplier', index:'supplier', align: 'left', width:80, searchoptions: {sopt: ['cn']}},
        {name:'tanggal_po', index:'tanggal_po', align: 'center', width:40, searchoptions: {sopt: ['cn']}, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}},
        {name:'eta_pengiriman', index:'eta_pengiriman', align: 'center', width:40, searchoptions: {sopt: ['cn']}, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}},
        {name:'total_qty', index:'total_qty', align:'right', width:25, searchoptions: {sopt: ['cn']}},
        {name:'total_dpp', index:'total_odpp', align: 'right', width:40, searchoptions: {sopt: ['cn']}},
        {name:'ppn', index:'ppn', align:'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name:'grand_total', index:'grand_total', align:'right', width: 40, searchoptions: {sopt: ['cn']}},
        {name:'Catatan', index:'catatan', align: 'left', searchoptions: {sopt: ['cn']}},
        {name: 'Approval', index:'approval', align:'center', width:25, sortable: false},
        {name: 'Delete', index:'delete', align:'center', width:30, sortable: false},
        {name: 'Edit', index:'edit', align:'center', width:30, sortable: false},
        {name: 'Print', index:'print', align:'center', width:30, sortable: false},
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
      caption       : "Purchase Order",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Produk / Jasa','Tanggal Quotation','Qty','Satuan','DPP/Unit','Subtotal Tanpa PPN','Nomor Akun', 'Nama Akun'],
          width : [30,250,70,70,70,70,70,70,200],
          align : ['right','left','center','center','center','right','right','center','left'],
        }
      ],
    });
    $('#table_po').jqGrid('navGrid', '#pager_table_po', {edit:false, add:false, del:false, search:false});
  });
</script>