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
    $where .= " AND (b2breturn_num LIKE '%".$filter."%' OR cust.nama LIKE '%".$filter."%') ";
  }

  if($status != null && $status != ""){
    if($status == "posted"){
      $where .= "AND post LIKE '1' ";
    }
    else if($status == "unposted"){
      $where .= "AND post LIKE '0' ";
    }
  }

  $sql = "SELECT ret.*, cat.nama AS kategori, cust.nama as customer FROM b2breturn ret LEFT JOIN mst_b2bcustomer cust ON cust.id=ret.b2bcust_id LEFT JOIN mst_b2bcategory_sale cat ON ret.id_kategori=cat.id ".$where;

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
    $post = $allow_post ? ($line['post'] == '0' ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=post&id='.$line['id'].'&num='.$line['b2breturn_num'].'\',\'table_b2breturn\')" href="javascript:void(0);">Post</a>': '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=unpost&id='.$line['id'].'&num='.$line['b2breturn_num'].'\',\'table_b2breturn\')" href="javascript:void(0);">Unpost</a>') : ($line['post'] == '0' ? '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Post</a>': '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:void(0);">Unpost</a>');
    $edit = $allow_edit ? ($line['post'] == '0' ? '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_b2b/trb2breturn_edit.php?id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Edit</a>' : '<a onclick="javascript:custom_alert(\'Data yang sudah dipost tidak dapat diedit\')" href="javascript:;">Edit</a>' ) : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Edit</a>';
    $delete = $allow_edit ? ($line['post'] == '0' ? '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2breturn.php?action=delete&id='.$line['id'].'\',\'table_b2breturn\')" href="javascript:void(0);">Delete</a>' : '<a onclick="javascript:custom_alert(\'Data yang sudah dipost tidak dapat dihapus\')" href="javascript:;">Delete</a>' ) : '<a onclick="javascript:custom_alert(\'Anda tidak memiliki akses\')" href="javascript:;">Delete</a>';

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      // $line['id'],
      $line['b2breturn_num'],
      $line['customer'],
      $line['tgl_return'],
      $line['kategori'],
      number_format($line['qty']),
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
  $post_b2breturn = $db->prepare("UPDATE b2breturn SET post=1 WHERE id='".$_GET['id']."'");

  $post_b2breturn ->execute();

  $id_user = $_SESSION['user']['username'];

  $masterNo = '';
  $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor FROM jurnal ORDER BY id DESC LIMIT 1");

  if(mysql_num_rows($query) == '1'){
  }else{
    $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
  }

  $q = mysql_fetch_array($query);
  $masterNo=$q['nomor'];

  $master_b2breturn = mysql_fetch_array(mysql_query("SELECT a.*, b.nama AS type_b2breturn, c.nama AS customer_b2breturn FROM b2breturn a LEFT JOIN mst_b2bcategory_sale b ON a.`id_kategori`=b.`id` LEFT JOIN mst_b2bcustomer c ON a.`b2bcust_id`=c.`id` WHERE a.`id`='".$_GET['id']."' LIMIT 1"));

  $jurnal_master = $db->prepare("INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo',CURDATE(),'Retur B2B - ".$master_b2breturn['type_b2breturn']." - ".$master_b2breturn['customer_b2breturn']." - ".$master_b2breturn['b2breturn_num']."','".$master_b2breturn['total']."','".$master_b2breturn['total']."','0','$id_user',NOW(),'RETURB2B')");

  $jurnal_master->execute();

  $parent_id=mysql_fetch_array(mysql_query("SELECT id FROM `jurnal` WHERE `no_jurnal`='$masterNo' LIMIT 1"));
  $idparent=$parent_id['id'];

  $akun_get=mysql_fetch_array( mysql_query("SELECT c.id, c.`noakun`, c.nama FROM b2breturn a LEFT JOIN mst_b2bcustomer b ON a.`b2bcust_id`=b.`id` LEFT JOIN det_coa c ON c.noakun = CONCAT('04.03.', LPAD(b.id, 5, 0)) WHERE a.id='".$_GET['id']."'"));
  $idakun=$akun_get['id'];
  $noakun=$akun_get['noakun'];
  $namaakun=$akun_get['nama'];

  $penjualan = ceil($master_b2breturn['total']/1.11);
  $ppn = round($master_b2breturn['total']/1.11*0.11);
  $total = $master_b2breturn['total'];

  if($penjualan+$ppn > $total){
    $ppn = floor($master_b2breturn['total']/1.11*0.11);
    if($penjualan+$ppn > $total){
      $penjualan = floor($master_b2breturn['total']/1.11);
    }
  } else if($penjualan+$ppn < $total){
    $ppn = ceil($master_b2breturn['total']/1.11*0.11);
  }

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','RETURB2B','".$penjualan."','0','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','39','09.01.00000','PPN','RETURB2B','".$ppn."','0','','0', '$id_user',NOW())";
  mysql_query($sql_detail) or die (mysql_error());

  $akun_get=mysql_fetch_array( mysql_query("SELECT c.id, c.`noakun`, c.nama FROM b2breturn a LEFT JOIN mst_b2bcustomer b ON a.`b2bcust_id`=b.`id` LEFT JOIN det_coa c ON c.noakun = CONCAT('01.05.', LPAD(b.id, 5, 0)) WHERE a.id='".$_GET['id']."'"));
  $idakun_kredit=$akun_get['id'];
  $noakun_kredit=$akun_get['noakun'];
  $namaakun_kredit=$akun_get['nama'];

  $stmt = $db->prepare("INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun_kredit','$noakun_kredit','$namaakun_kredit','RETURB2B','0','".$master_b2breturn['total']."','','0', '$id_user',NOW())");
  $stmt->execute();

  $affected_rows = $stmt->rowCount();
  if($affected_rows > 0) {
    $r['stat'] = 1;
    $r['message'] = 'Success';
  }
  else {
    $r['stat'] = 0;
    $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
else if(isset($_GET['action']) && strtolower($_GET['action']) == 'unpost'){
  $post_b2breturn = $db->prepare("UPDATE b2breturn SET post=0 WHERE id='".$_GET['id']."'");

    $post_b2breturn ->execute();

    $stmt = $db->prepare("UPDATE jurnal SET deleted=1 WHERE keterangan LIKE CONCAT('% - ', '".$_GET['num']."')");

    $stmt ->execute();

    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0) {
      $r['stat'] = 1;
      $r['message'] = 'Success';
    }
    else {
      $r['stat'] = 0;
      $r['message'] = 'Failed';
    }
    echo json_encode($r);
    exit;
}elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
  $id = $_GET['id'];
  //$id = $line['id_trans'];
  $where = "WHERE pd.id_parent = '".$id."' AND deleted=0";
      $q = $db->query("SELECT pd.* FROM `b2breturn_detail` pd ".$where);
  
  $count = $q->rowCount();
  
  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
  
  $i=0;
  $responce = '';
  $barangnya='';
  foreach($data1 as $line){
    $sizenew='';
    $count=1;
    
    if ($barangnya != $line['id_product']) {
      $count=1;
    }

    if ($line['qty31'] != '0') {
      if ($count == 1) {
        $sizenew = '31'.'('.$line['qty31'].')';
      }else{					
        $sizenew = $sizenew.', 31('.$line['qty31'].')';
      }
      $count++;
    }

    if ($line['qty32'] != '0') {
      if ($count == 1) {
        $sizenew = '32'.'('.$line['qty32'].')';
      }else{
        $sizenew = $sizenew.', 32('.$line['qty32'].')';
      }
      $count++;
    }

    if ($line['qty33'] != '0') {
      if ($count == 1) {
        $sizenew = '33'.'('.$line['qty33'].')';
      }else{
        $sizenew = $sizenew.', 33('.$line['qty33'].')';
      }
      $count++;
    }

    if ($line['qty34'] != '0') {
      if ($count == 1) {
        $sizenew = '34'.'('.$line['qty34'].')';
      }else{
        $sizenew = $sizenew.', 34('.$line['qty34'].')';
      }
      $count++;
    }

    if ($line['qty35'] != '0') {
      if ($count == 1) {
        $sizenew = '35'.'('.$line['qty35'].')';
      }else{
        $sizenew = $sizenew.', 35('.$line['qty35'].')';
      }
      $count++;
    }

    if ($line['qty36'] != '0') {
      if ($count == 1) {
        $sizenew = '36'.'('.$line['qty36'].')';
      }else{
        $sizenew = $sizenew.', 36('.$line['qty36'].')';
      }
      $count++;
    }

    if ($line['qty37'] != '0') {
      if ($count == 1) {
        $sizenew = '37'.'('.$line['qty37'].')';
      }else{
        $sizenew = $sizenew.', 37('.$line['qty37'].')';
      }
      $count++;
    }

    if ($line['qty38'] != '0') {
      if ($count == 1) {
        $sizenew = '38'.'('.$line['qty38'].')';
      }else{
        $sizenew = $sizenew.', 38('.$line['qty38'].')';
      }
      $count++;
    }

    if ($line['qty39'] != '0') {
      if ($count == 1) {
        $sizenew = '39'.'('.$line['qty39'].')';
      }else{
        $sizenew = $sizenew.', 39('.$line['qty39'].')';
      }
      $count++;
    }

    if ($line['qty40'] != '0') {
      if ($count == 1) {
        $sizenew = '40'.'('.$line['qty40'].')';
      }else{
        $sizenew = $sizenew.', 40('.$line['qty40'].')';
      }
      $count++;
    }

    if ($line['qty41'] != '0') {
      if ($count == 1) {
        $sizenew = '41'.'('.$line['qty41'].')';
      }else{
        $sizenew = $sizenew.', 41('.$line['qty41'].')';
      }
      $count++;
    }

    if ($line['qty42'] != '0') {
      if ($count == 1) {
        $sizenew = '42'.'('.$line['qty42'].')';
      }else{
        $sizenew = $sizenew.', 42('.$line['qty42'].')';
      }
      $count++;
    }

    if ($line['qty43'] != '0') {
      if ($count == 1) {
        $sizenew = '43'.'('.$line['qty43'].')';
      }else{
        $sizenew = $sizenew.', 43('.$line['qty43'].')';
      }
      $count++;
    }

    if ($line['qty44'] != '0') {
      if ($count == 1) {
        $sizenew = '44'.'('.$line['qty44'].')';
      }else{
        $sizenew = $sizenew.', 44('.$line['qty44'].')';
      }
      $count++;
    }

    if ($line['qty45'] != '0') {
      if ($count == 1) {
        $sizenew = '45'.'('.$line['qty45'].')';
      }else{
        $sizenew = $sizenew.', 45('.$line['qty45'].')';
      }
      $count++;
    }

    if ($line['qty46'] != '0') {
      if ($count == 1) {
        $sizenew = '46'.'('.$line['qty46'].')';
      }else{
        $sizenew = $sizenew.', 46('.$line['qty46'].')';
      }
      $count++;
    }

    $totalqty = $line['qty31'] + $line['qty32'] + $line['qty33'] + $line['qty34'] + $line['qty35'] + $line['qty36'] + $line['qty37'] + $line['qty38'] + $line['qty39'] + $line['qty40'] + $line['qty41'] + $line['qty42'] + $line['qty43'] + $line['qty44'] + $line['qty45'] + $line['qty46'];

      $responce->rows[$i]['id']   = $line['id_parent'];
      $responce->rows[$i]['cell'] = array(
        $i+1,
        $line['b2bdo_num'],
        $line['namabrg'],
        $sizenew,
        number_format($line['harga_satuan'],0),
        number_format($totalqty,0),                
        number_format(($line['harga_satuan']*$totalqty),0),                
      );
      $barangnya = $line['id_product'];
      $i++;
    }
    echo json_encode($responce);
  exit;
}
?>

<script type='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

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
            <td> Filter <input type="text" id="filtervalue_b2breturn" name="filtervalue_b2breturn" />(Nomor Return B2B, Customer)</td>
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
      colNames      : ['Nomor B2B Return','Customer','Tanggal Return', 'Type', 'Qty Return', 'Total Return', 'Keterangan', 'Post', 'Edit', 'Delete'],
      colModel      : [
        {name: 'b2breturn_num', index: 'b2breturn_num', align: 'left', width: 50, searchoptions:{sopt: ['cn']}},
        {name: 'customer', index: 'customer', align: 'left', width: 50, searchoptions:{sopt: ['cn']}},
        {name:'tanggal_b2breturn', index: 'tanggal_b2breturn', align: 'center', width:30, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'type_b2breturn', index: 'type_b2breturn', align: 'center', width: 10, searchoptions:{sopt: ['cn']}},
        {name: 'qty_b2breturn', index: 'qty_b2breturn', align: 'right', width: 20, searchoptions:{sopt: ['cn']}},
        {name: 'total_b2breturn', index: 'total_b2breturn', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'keterangan_b2breturn', index: 'keterangan_b2breturn', align: 'left', width: 80, searchoptions:{sopt: ['cn']}},
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
      subGrid : true,
      subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2breturn.php?action=json_sub'; ?>',
      subGridModel: [
          { 
            name : ['No','B2BDO Num','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
            width : [40,100,300,300,50,50,50,50],
            align : ['right','center','left','left','right','right','right'],
          } 
        ],
    });
    $('#table_b2breturn').jqGrid('navGrid', '#pager_table_b2breturn', {edit:false, add:false, del:false, search:false});
  });
</script>