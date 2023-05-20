<?php

require_once '../../include/config.php';
include "../../include/koneksi.php";

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

  $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
  $limit = isset($_GET['rows'])?$_GET['rows']:20; // get how many rows we want to have into the grid
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; // get index row - i.e. user click to sort
  $sord = isset($_GET['sord'])?$_GET['sord']:'';

  if(!$sidx) $sidx = 1;

  // << searching _filter ------------------------------
  if(isset($_GET['filter']) && $_GET['filter'] != ''){
    $filter_value = " AND (`ap_num` LIKE '%".$_GET['filter']."%' OR `nama_supplier` LIKE '%".$_GET['filter']."%' OR `nama_akun` LIKE '%".$_GET['filter']."%' OR `catatan` LIKE '%".$_GET['filter']."%') ";
  }
  else{
    $filter_value = '';
  }

  if(isset($_GET['status']) && $_GET['status'] != ''){
    if($_GET['status'] == "posted"){
      $status_value = " AND `posting`=1 ";
    }
    else if($_GET['status'] == "unposted"){
      $status_value = " AND (`posting`=0 OR `posting` IS NULL) ";
    }
    else{
      $status_value = "";
    }
  }
  else{
    $status_value = "";
  }

  if((!isset($_GET['startdate_ap']) && !isset($_GET['enddate_ap']))||($_GET['startdate_ap'] == '' && $_GET['enddate_ap'] == '')){
    $where = " WHERE a.deleted=0 AND a.ap_date>='".date("Y-m-d")."' AND a.ap_date<='".date("Y-m-d")."'".$filter_value;
  }else if($_GET['startdate_ap'] != '' && $_GET['enddate_ap'] == ''){
    $where = " WHERE a.deleted=0 AND a.ap_date>='".$_GET['startdate_ap']."'".$filter_value;
  }
  else if($_GET['startdate_ap'] == '' && $_GET['enddate_ap'] != ''){
    $where = " WHERE a.deleted=0 AND a.ap_date<='".$_GET['enddate_ap']."'".$filter_value;
  }
  else{
    $where = " WHERE a.deleted=0 AND a.ap_date>='".$_GET['startdate_ap']."' AND a.ap_date<='".$_GET['enddate_ap']."'".$filter_value.$status_value;
  }
  // -------------------- end of searching _filter >>

  $sql_po  = "SELECT a.*, date_format(ap_date, '%d-%m-%Y') as ap_date_formatted FROM `mst_ap` a "; 
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

  $responce['page']     = $page;
  $responce['total']    = $total_pages;
  $responce['records']  = $count;
  
  // $responce = array();
  $i=0;

  foreach($data1 as $line){
    if($allow_edit){
      if($line['posting'] == '1'){
        $edit = '<a onclick="javascript:custom_alert(\'AP yang sudah dipost tidak dapat diubah.\')" href="javascript:void(0);" disabled>Edit</a>';
      }
      else{
        $edit = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/transaksi_purchase/ap_edit.php?id='.$line['id'].'\',\'table_ap\')" href="javascript:void(0);">Edit</a>';
      }
    }
    else{
      $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Edit</a>';
    }

    if($allow_post){
      if($line['posting'] == '1'){
        $postAP = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_archive.php?action=postap&val=0&id='.$line['id'].'\',\'table_ap\')" href="javascript:void(0);">Unpost</a>';
      }
      else{
        $postAP = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_archive.php?action=postap&val=1&id='.$line['id'].'\',\'table_ap\')" href="javascript:void(0);">Post</a>';
      }
    }
    else{
      $postAP = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Post</a>';
    }

    if($allow_delete){
      if($line['posting'] == '1'){
        $delete = '<a onclick="javascript:custom_alert(\'AP yang sudah dipost tidak dapat dihapus.\')" href="javascript:void(0);" disabled>Delete</a>';
      }
      else{
        $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_purchase/po_archive.php?action=delete&id='.$line['id'].'\',\'table_ap\')" href="javascript:void(0);">Delete</a>';
      }
    }
    else{
      $delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')">Post</a>';
    }

    $responce['rows'][$i]['id']     = $line['id'];
    $responce['rows'][$i]['cell']   = array(
      $line['id'],
      $line['ap_num'],
      $line['ap_date'],
      $line['nama_supplier'],
      $line['no_akun'],
      $line['nama_akun'],
      $line['total_qty'],
      number_format($line['grand_total']),
      $line['catatan'],
      $postAP,
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
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub'){
  $id       = $_GET['id'];

  $query    = "SELECT *,date_format(tanggal_invoice, '%d-%m-%Y') AS tanggal_invoice_formatted,date_format(tanggal_jatuh_tempo, '%d-%m-%Y') AS tanggal_jatuh_tempo_formatted FROM `det_ap` WHERE `id_ap`='".$id."' AND `deleted`=0 ";

  $query    = $db->query($query);
  $count    = $query->rowCount();

  $data1    = $query->fetchAll(PDO::FETCH_ASSOC);

  $i        = 0;
  $responce = '';
  foreach ($data1 as $line){
    $responce->rows[$i]['id']   = $line['id_detail'];
    $responce->rows[$i]['cell'] = array(
      $i + 1,
      $line['no_invoice'],
      str_replace("-", "/", $line['tanggal_invoice_formatted']),
      str_replace("-", "/", $line['tanggal_jatuh_tempo_formatted']),
      $line['qty'],
      number_format($line['remaining']),
      number_format($line['total']),
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add'){
  include 'ap_add.php'; exit(); exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'postap'){
  $id_user=$_SESSION['user']['username'];

  $nomor_ap = "(SELECT ap_num FROM mst_ap WHERE `id`='".$_GET['id']."')";

  $nomor_ap = mysql_query($nomor_ap);
  $no_ap = mysql_fetch_array($nomor_ap);

  if($_GET['val'] == 1){

    $no_jurnal = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1))))))) AS nomor FROM jurnal ORDER BY id DESC LIMIT 1");

    if(mysql_num_rows($no_jurnal) == '1'){
    }else{
      $no_jurnal = mysql_query("select CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
    }

    $q = mysql_fetch_array($no_jurnal);
      $nomor_jurnal=$q['nomor'];

    $tgl_ap = "(SELECT MAX(tanggal_invoice) FROM det_ap WHERE id_ap='".$_GET['id']."')";

    $vendor_ap  = "(SELECT CONCAT(a.`vendor`,' ',a.`telp`) FROM mst_supplier a INNER JOIN mst_ap b ON b.id_supplier=a.id AND b.`id`=".$_GET['id'].")";

    $keterangan_ap = "(SELECT CONCAT('Hutang Vendor - ',$vendor_ap,' - ','$no_ap[0]'))";

    $total_kredit = "(SELECT grand_total FROM mst_ap WHERE id = '$_GET[id]')";

    $total_debet = "(SELECT SUM(a.subtotal) AS akun_debet FROM det_invoice a INNER JOIN det_ap b ON b.id_invoice = a.id_invoice AND b.id_ap = '$_GET[id]' AND b.`deleted` = 0 AND a.`deleted` = 0)";

    $user_ap = $_SESSION['user']['username'];

    $q_jurnal = "INSERT INTO `jurnal` (`no_jurnal`, `tgl`, `keterangan`, `total_debet`, `total_kredit`, `user`, `lastmodified`,`status`) 
    VALUES (
      $nomor_jurnal, $tgl_ap,$keterangan_ap,$total_debet,$total_kredit,'$user_ap',NOW(),'AP'
    )";

    $q_jurnal = mysql_query($q_jurnal);

    $get_jurnal_id = "(SELECT `id` FROM `jurnal` WHERE `no_jurnal`=($nomor_jurnal))";
    $id_akun = "(SELECT id_akun FROM `mst_ap` WHERE id='".$_GET['id']."')";
    $no_akun = "(SELECT no_akun FROM `mst_ap` WHERE id='".$_GET['id']."')";
    $nama_akun = "(SELECT nama FROM `det_coa` WHERE id=$id_akun)";

    $sql_detail = mysql_query("SELECT a.id,a.id_detail,a.id_akun,a.nomor_akun,a.nama_akun,a.subtotal FROM det_invoice a INNER JOIN det_ap b ON b.id_invoice=a.id_invoice AND b.id_ap=".$_GET['id']." AND b.`deleted`=0 AND a.`deleted`=0");

    $q_jurnal_detail = "INSERT INTO `jurnal_detail` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`user`,`lastmodified`) VALUES ($get_jurnal_id, $id_akun, $no_akun, $nama_akun, 'AP', '0', $total_kredit, '$user_ap', NOW())";

    $q_jurnal_detail = mysql_query($q_jurnal_detail);

    $checkppn = mysql_fetch_array(mysql_query("SELECT pkp FROM mst_supplier WHERE ID = (SELECT id_supplier FROM mst_ap WHERE id='".$_GET['id']."') LIMIT 1"));

    if($checkppn['pkp'] == 1){
      $total_ppn = 0;
      while($rs=mysql_fetch_array($sql_detail)){

        $detailNamaAkun = mysql_fetch_array(mysql_query("SELECT nama FROM det_coa WHERE id='".$rs['id_akun']."'"))['nama'];

        $q_jurnal_detail_debet = "INSERT INTO `jurnal_detail` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`user`,`lastmodified`) VALUES(".$get_jurnal_id.", ".$rs['id_akun'].", '".$rs['nomor_akun']."', '".$detailNamaAkun."', 'AP', ".round($rs['subtotal']-floor($rs['subtotal']/1.11*0.11)).",'0', '$user_ap', NOW())";

        $total_ppn += floor($rs['subtotal']/1.11*0.11);

        $q_jurnal_detail_debet = mysql_query($q_jurnal_detail_debet);
      }

      $q_jurnal_detail_debet = "INSERT INTO `jurnal_detail` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`user`,`lastmodified`) VALUES(".$get_jurnal_id.", '39', '09.01.00000', 'PPN', 'AP', ".$total_ppn.",'0', '$user_ap', NOW())";

      $q_jurnal_detail_debet = mysql_query($q_jurnal_detail_debet);
    }
    else{
      while($rs=mysql_fetch_array($sql_detail)){
        $detailNamaAkun = mysql_fetch_array(mysql_query("SELECT nama FROM det_coa WHERE id='".$rs['id_akun']."'"))['nama'];

        $q_jurnal_detail_debet = "INSERT INTO `jurnal_detail` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`user`,`lastmodified`) VALUES(".$get_jurnal_id.", ".$rs['id_akun'].", '".$rs['nomor_akun']."', '".$detailNamaAkun."', 'AP', ".$rs['subtotal'].",'0', '$user_ap', NOW())";

        $q_jurnal_detail_debet = mysql_query($q_jurnal_detail_debet);
      }
    }
  }
  else if($_GET['val'] == 0){
    $get_jurnal_id = "SELECT id FROM `jurnal` WHERE keterangan LIKE '%$no_ap[0]%'";
    $get_jurnal_id = mysql_query($get_jurnal_id);
    $get_jurnal_id = mysql_fetch_array($get_jurnal_id);

    $sql_master = "DELETE FROM `jurnal` WHERE keterangan LIKE '%$no_ap[0]%'";
    $sql_detail = "DELETE FROM `jurnal_detail` WHERE id_parent = $get_jurnal_id[0]";
    $sql = mysql_query($sql_master); $sql = mysql_query($sql_detail);
  }

  $q_post = $db->prepare("UPDATE `mst_ap` SET `posting`=? WHERE `id`=?");
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
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete'){
  $qty_ap   =  $db->prepare("UPDATE `mst_invoice` SET `mst_invoice`.`total_payment`=`mst_invoice`.`total_payment`-(SELECT `total` FROM `det_ap` WHERE det_ap.id_invoice=mst_invoice.id AND det_ap.id_ap='".$_GET['id']."') WHERE `mst_invoice`.id=(SELECT `id_invoice` FROM `det_ap` WHERE det_ap.id_invoice=mst_invoice.id AND det_ap.id_ap='".$_GET['id']."')");

  $rem_ap   = $db->prepare("UPDATE `mst_invoice` SET `mst_invoice`.`total_remaining`=`mst_invoice`.`total_remaining`+(SELECT `total` FROM `det_ap` WHERE det_ap.id_invoice=mst_invoice.id AND det_ap.id_ap='".$_GET['id']."') WHERE `mst_invoice`.id=(SELECT `id_invoice` FROM `det_ap` WHERE det_ap.id_invoice=mst_invoice.id AND det_ap.id_ap='".$_GET['id']."')");

  $qty_ap->execute(); $rem_ap->execute();

  $q_post   = $db->prepare("UPDATE `mst_ap` SET `deleted`=? WHERE `id`=?");
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
?>

<div class="ui-widget ui-form" style="margin-bottom:5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_ap" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Tanggal AP</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_ap" name="startdate_ap" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_ap" name="enddate_ap" readonly></td>
            <td> Status <select id="statusvalue_ap" name="statusvalue_ap">
              <option value="posted">Sudah Dipost</option>
              <option value="unposted">Belum Dipost</option>
              <option value="all" selected>Semua</option>
            </select></td>
            <td> Filter <input type="text" id="filtervalue_ap" name="filtervalue_ap" />(Nomor AP, Supplier, Nama Akun, Keterangan)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadAP()" class="btn" type="button">Cari</button>
        <button onclick="printInRange()" class="btn" type="button">Print</button>
      </div>
    </form>
  </div>
</div>

<div class="btn_box">
  <?php
  if($allow_add){?>
    <a href="javascript: void(0)">
      <button class="btn btn-success" onclick="javascript:window.open('<?= BASE_URL?>pages/transaksi_purchase/po_archive.php?action=add')">Tambah</button>
    </a>
  <?php } ?>
</div>

<table id="table_ap"></table>
<div id="pager_table_ap"></div>

<script type="text/javascript">
  $('#startdate_ap').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$('#enddate_ap').datepicker({
		dateFormat: "dd-mm-yy"
	});
	$( "#startdate_ap" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
	$( "#enddate_ap" ).datepicker( 'setDate', '<?php echo date('d-m-Y')?>' );
  
  function gridReloadAP(){
    var startdate   = ($("#startdate_ap").val()).split("-");
		var enddate     = ($("#enddate_ap").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

		var filter      = $("#filtervalue_ap").val();

    var status      = $("#statusvalue_ap").val();

		var v_url       = '<?php echo BASE_URL?>pages/transaksi_purchase/po_archive.php?action=json&startdate_ap='+startdate+'&enddate_ap='+enddate+'&filter='+filter+'&status='+status;
		jQuery("#table_ap").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  function printInRange(){
    var startdate   = ($("#startdate_ap").val()).split("-");
		var enddate     = ($("#enddate_ap").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

		var filter      = $("#filtervalue_ap").val();

    var status      = $("#statusvalue_ap").val();

		var v_url       = '<?php echo BASE_URL?>pages/transaksi_purchase/ap_print.php?startdate_ap='+startdate+'&enddate_ap='+enddate+'&filter='+filter+'&status='+status;

    window.open(v_url);
  }

  $(document).ready(function(){
    $('#table_ap').jqGrid({
      url           : '<?= BASE_URL.'pages/transaksi_purchase/po_archive.php?action=json';?>',
      datatype      : 'json',
      colNames      : ['ID','Nomor AP','Tanggal AP','Supplier','Nomor Akun','Nama Akun','Total Qty','Total','Keterangan','Post','Edit','Delete'],
      colModel      : [
        {name: 'id', index: 'id', align: 'right', width: 15, searchoptions: {sopt: ['cn']}},
        {name: 'nomor_ap', index: 'nomor_ap', align: 'left', width: 40, searchoptions:{sopt: ['cn']}},
        {name:'tanggal_ap', index: 'tanggal_ap', align: 'center', width:40, formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, searchoptions: {sopt:['cn']}},
        {name: 'supplier', index: 'supplier', align: 'left', width: 80, searchoptions:{sopt: ['cn']}},
        {name: 'nomor_akun', index: 'nomor_akun', align: 'center', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'nama_akun', index: 'nama_akun', align: 'left', width: 80, searchoptions:{sopt: ['cn']}},
        {name: 'total_qty', index: 'total_qty', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
        {name: 'grand_total', index: 'grand_total', align: 'right', width: 40, searchoptions:{sopt: ['cn']}},
        {name: 'keterangan', index: 'keterangan', align: 'left', searchoptions:{sopt: ['cn']}},
        {name: 'post', index: 'post', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
        {name: 'edit', index: 'edit', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
        {name: 'delete', index: 'delete', align: 'center', width: 25, searchoptions:{sopt: ['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_ap',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Account Payable",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
      subGrid       : true,
      subGridUrl    : '<?= BASE_URL.'pages/transaksi_purchase/po_archive.php?action=json_sub'; ?>',
      subGridModel  : [
        {
          name  : ['No','Nomor Invoice','Tanggal Invoice','Tanggal Jatuh Tempo','Qty','Subtotal Invoice','Subtotal AP'],
          width : [20,100,70,70,70,100,100],
          align : ['right','left','center','center','right','right','right'],
        }
      ]
    });
    $('#table_ap').jqGrid('navGrid', '#pager_table_ap', {edit:false, add:false, del:false, search:false});
  });
</script>