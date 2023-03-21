<?php

require_once '../../include/config.php';
include "../../include/koneksi.php";

$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, OnlineCredit, $group_acess);
$allow_post = is_show_menu(POST_POLICY, OnlineCredit, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, OnlineCredit, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];
  
  if(!$sidx) $sidx=1;

  // << searching _filter ------------------------------
  if(isset($_GET['filter']) && $_GET['filter'] != ''){
    $filter_value = " AND  (p.`id_trans` LIKE '%".$_GET['filter']."%' OR p.`nama` LIKE '%".$_GET['filter']."%' OR j.`nama` LIKE '%".$_GET['filter']."%') ";
  }
  else{
    $filter_value = "";
  }

  if((!isset($_GET['startdate_olnsoar']) && !isset($_GET['enddate_olnsoar']))||($_GET['startdate_olnsoar'] == '' && $_GET['enddate_olnsoar'] == '')){
    $where = " WHERE j.deleted=0 AND p.tgl_trans>='".date("Y-m-d")."' AND p.tgl_trans<='".date("Y-m-d")."'".$filter_value;
  }else if($_GET['startdate_olnsoar'] != '' && $_GET['enddate_olnsoar'] == ''){
    $where = " WHERE j.deleted=0 AND p.tgl_trans>='".$_GET['startdate_olnsoar']."'".$filter_value;
  }
  else if($_GET['startdate_olnsoar'] == '' && $_GET['enddate_olnsoar'] != ''){
    $where = " WHERE j.deleted=0 AND p.tgl_trans<='".$_GET['enddate_olnsoar']."'".$filter_value;
  }
  else{
    $where = " WHERE j.deleted=0 AND p.tgl_trans>='".$_GET['startdate_olnsoar']."' AND p.tgl_trans<='".$_GET['enddate_olnsoar']."'".$filter_value;
  }
  // -------------------- end of searching _filter >>

  if ($_REQUEST["_search"] == "false") {
    //all transaction kecuali yang batal
    $where_state = " AND p.stkirim='1' AND (p.totalqty <> 0) AND (p.piutang> 0)";
  } else {
    $operations = array(
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
  $value = $_REQUEST["searchString"];
  $where_state = sprintf(" AND (p.totalqty <> 0) AND (p.stkirim='1') AND (p.piutang> 0) and (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
  }
  $sql = "SELECT p.*,j.nama as dropshipper, j.type as dsType, e.nama as expedition, SUM(p.total) as sumTotal, SUM(p.totalqty) as sumTotalQty, id_dropshipper FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) ".$where.$where_state." GROUP BY `id_dropshipper`";
  $q = $db->query($sql);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;

  if ($page > $total_pages) $page=$total_pages;

  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $q = $db->query($sql."ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $statusToko = '';

  $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
  $getStat->execute();
  $stat = $getStat->fetchAll();

  foreach ($stat as $stats) {
    $statusToko = $stats['status'];
  }

  $responce['page'] = $page;
  $responce['total'] = $total_pages;
  $responce['records'] = $count;
  $i=0;

  $grand_qty=0;$grand_faktur=0;$grand_total=0;$grand_remaining=0;$grand_payment=0;

  foreach($data1 as $line) {
    if ($statusToko == 'Tutup') {
      $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Posting</a>';
      $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
    } else {
      if($allow_post){
        $edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolnsocr.php?action=posting&id='.$line['id_trans'].'\',\'table_jualcrAR\')" href="javascript:;">Posting</a>';
      }
      else
        $edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Posting</a>';

        $pay = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/sales_online/trolnarcredit_lunasi.php?start_date='.$_GET['startdate_olnsoar'].'&end_date='.$_GET['enddate_olnsoar'].'&ids='.$line['id_trans'].'\',\'table_jualcrAR\')" href="javascript:;">Pay</a>';

        $detail = '<a href="javascript:;" onclick="opendetail('.$line['id_dropshipper'].')">Detail</a>';
    }

    $responce['rows'][$i]['id']   = $line['id_trans'];
    $responce['rows'][$i]['cell'] = array(
    $line['dropshipper'],
    $line['dsType'],
    number_format($line['sumTotalQty'],0),
    number_format($line['sumTotal'],0),
    number_format(0,0),
    number_format($line['sumTotal'],0),
    $pay,
    $detail,
    // $edit,
    );

    $grand_qty+=$line['sumTotalQty']; $grand_total+=$line['sumTotal'];$grand_remaining+=$line['sumTotal'];$grand_payment+=0;

    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  $responce['userdata']['totalqty']     = number_format($grand_qty, 0);
  $responce['userdata']['sumtotal']     = number_format($grand_total, 0);
  $responce['userdata']['payment']     = number_format($grand_payment, 0);
  $responce['userdata']['remaining']     = number_format($grand_remaining, 0);
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
  $id_user=$_SESSION['user']['username'];

  //posting data untuk oln_id
  $stmt = $db->prepare("INSERT INTO olnso_id(`nomor`,`id_trans`,`user_id`,`lastmodified`) SELECT IFNULL((MAX(nomor)+1),0),?,?,NOW() FROM olnso_id WHERE DATE(lastmodified)=DATE(NOW())"); 
  $stmt->execute(array($_GET['id'],$_SESSION['user']['user_id']));
  $id = $db->lastInsertId();
  // $idnext = ($id+2)/3 + 246630;

  $idnext='';
  $query = mysql_query("(SELECT (a.id_ship+1) AS idbaru FROM olnso_id a WHERE a.id < $id ORDER BY a.id DESC LIMIT 1)");
  while($q = mysql_fetch_array($query)){
    $idnext = $q['idbaru'];
  }

  $stmt = $db->prepare("UPDATE olnso_id SET id_ship=? WHERE id=?"); 
  $stmt->execute(array($idnext,$id));
  
  $idtrans=$_GET['id'];
  
  $total='';
  $dropshipper='';
  $namadropshipper='';
  $q = mysql_fetch_array( mysql_query("SELECT olnso.id_trans,olnso.total,olnso.id_dropshipper,mst_dropshipper.nama FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE id_trans='".$_GET['id']."' LIMIT 1"));
  $idtrans=$q['id_trans'];
  $total=$q['total'];
  $dropshipper=$q['id_dropshipper'];
  $namadropshipper=$q['nama'];

  //insert
    $masterNo = '';
    $q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
    FROM jurnal ORDER BY id DESC LIMIT 1"));
    $masterNo=$q['nomor'];

    // execute for master
    $sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo',NOW(),'Penjualan OLN Kredit - $namadropshipper - $idtrans','$total','$total','0','$id_user',NOW(),'OLN') ";
    mysql_query($sql_master) or die (mysql_error());

    //get master id terakhir
    $q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
    $idparent=$q['id'];
  
  $dpp = round($total / 1.11);
  $ppn = round($total / 1.11 * 0.11);

  $query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('01.04.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
  while($akun1 = mysql_fetch_array($query1)){
    // piutang oln 
    $sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
    mysql_query($sqlakun1) or die (mysql_error());
  }

  $query2=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
  while($akun2 = mysql_fetch_array($query2)){
    // penjualan oln credit
    $sqlakun2="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun2['id']."','".$akun2['noakun']."','".$akun2['nama']."','".$akun2['status']."','0','$dpp','','0', '$id_user',NOW()) ";
    mysql_query($sqlakun2) or die (mysql_error());
  }

  $query3=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.01.00000'");
  while($akun3 = mysql_fetch_array($query3)){
    // ppn
    $sqlakun3="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun3['id']."','".$akun3['noakun']."','".$akun3['nama']."','".$akun3['status']."','0','$ppn','','0', '$id_user',NOW()) ";
    mysql_query($sqlakun3) or die (mysql_error());
  }

  //update olnso agar jadi 1 krn siap kirim,tapi statenya dikasih string='1' krn tipe datanya enum
  $stmt = $db->prepare("Update olnso set state='1',lastmodified=now() WHERE id_trans=?");
  $stmt->execute(array($_GET['id']));
  
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
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
  //delete olndeposit krn void invoice		
  $stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
  $stmt->execute(array($_GET['id']));
  
  //update trjualcr agar jadi nol krn void invoice 
  $stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,ref_kode='',exp_code='',deleted=1 WHERE id_trans=?");
  $stmt->execute(array($_GET['id']));
  //var_dump($stmt);die;
  //update trjual_detail agar jadi nol krn void invoice
  $stmt = $db->prepare("update olnsodetail set jumlah_beli=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
  $stmt->execute(array($_GET['id']));
  //var_dump($stmt);die;
  
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
?>

<div class="ui-widget ui-form" style="margin-bottom: 5px;">
  <div class="ui-widget-header ui-corner-top padding5">
    Filter Data
  </div>

  <div class="ui-widget-content ui-conrer-bottom">
    <form id="filter_ap" method="" action="" class="ui-helper-clearfix">
      <label for="" class="ui-helper-reset label-control">Tanggal AR Order Credit</label>
      <div class="ui-corner-all form-control">
        <table>
          <tr>
            <td><input type="text" class="required datepicker" id="startdate_olnsoar" name="startdate_olnsoar" readonly></td>
            <td> s.d <input type="text" class="required datepicker" id="enddate_olnsoar" name="enddate_olnsoar" readonly></td>
            <td> Filter <input type="text" id="filtervalue_olnsoar" name="filtervalue_olnsoar" />(Dropshipper)</td>
          </tr>
        </table>
      </div>
      <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="gridReloadTRAR()" class="btn" type="button">Cari</button>
      </div>
    </form>
  </div>
</div>

<table id="table_jualcrAR"></table>
<div id="pager_table_jualcrAR"></div>

<script type="text/javascript">
  $('#startdate_olnsoar').datepicker({dateFormat: "dd-mm-yy"});
	$('#enddate_olnsoar').datepicker({dateFormat: "dd-mm-yy"});

	$("#startdate_olnsoar").datepicker('setDate', '<?php echo date('d-m-Y')?>');
	$("#enddate_olnsoar").datepicker('setDate', '<?php echo date('d-m-Y')?>');

  function gridReloadTRAR(){
    var startdate     = ($("#startdate_olnsoar").val()).split("-");
    var enddate       = ($("#enddate_olnsoar").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

    var filter      = $("#filtervalue_olnsoar").val();

		var v_url       = '<?php echo BASE_URL?>pages/sales_online/trolnarcredit.php?action=json&startdate_olnsoar='+startdate+'&enddate_olnsoar='+enddate+'&filter='+filter;
		jQuery("#table_jualcrAR").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
  }

  function opendetail(id){
    var startdate     = ($("#startdate_olnsoar").val()).split("-");
    var enddate       = ($("#enddate_olnsoar").val()).split("-");

    var startdate   = startdate[2]+"-"+startdate[1]+"-"+startdate[0];
    var enddate     = enddate[2]+"-"+enddate[1]+"-"+enddate[0];

		var v_url       = 'pages/sales_online/trolnarcredit_excel.php?action=json&startdate_olnsoar='+startdate+'&enddate_olnsoar='+enddate+'&id='+id;

    window.open(v_url);
  }

  $(document).ready(function(){
    $("#table_jualcrAR").jqGrid({
			url: '<?php echo BASE_URL.'pages/sales_online/trolnarcredit.php?action=json'; ?>',
      datatype : "json",
      colNames: ['Dropshipper', 'Type', 'Total Qty', 'Total Faktur', 'Payment', 'Remaining', 'Pay', 'Detail'],
      colModel: [
				{name: 'j.nama', index: 'j.nama', width: 100, searchoptions: {sopt: ['cn']}},
        {name: 'type', index: 'type', align: 'center', width: 10, searchoptions: {sopt: ['cn']}},
				{name: 'totalqty', index: 'totalqty', align: 'right', width: 20, searchoptions: {sopt: ['cn']}},
        {name: 'sumtotal', index: 'sumtotal', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'payment', index: 'payment', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'remaining', index: 'remaining', align: 'right', width: 30, searchoptions: {sopt: ['cn']}},
        {name: 'add', index: 'add', align: 'center', width: 20, sortable: false, search: false},
        {name: 'detail', index: 'detail', align: 'center', width: 20, sortable: false, search: false},
				// {name: 'edit', index: 'edit', align: 'center', width: 20, sortable: false, search: false},
			],
      rowNum: 20,
			rowList: [10, 20, 30],
			pager: '#pager_table_jualcrAR',
			sortname: 'id_trans',
			autowidth: true,
      height: '300',
			viewrecords: true,
			rownumbers: true,
			sortorder: "desc",
			caption: "Data AR Online Credit",
			ondblClickRow: function (rowid) {
				alert(rowid);
			},
			footerrow: true,
			userDataOnFooter: true,
			subGrid: false,
    });
    $("#table_jualcrAR").jqGrid('navGrid', '#pager_table_jualcrAR', {edit: false, add: false, del: false, search: true});
  });
</script>