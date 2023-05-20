<?php require_once '../../include/config.php';
include "../../include/koneksi.php";?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, DepositTransaction, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, DepositTransaction, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, DepositTransaction, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_trdeposit'])?$_GET['start_trdeposit']:date('Y-m-d');
		$enddate = isset($_GET['end_trdeposit'])?$_GET['end_trdeposit']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	    
		$filter=$_GET['filter'];

        $where = "WHERE TRUE ";
		
		if($startdate != null){
			$where .= " AND IF(so.id_trans IS NULL,DATE(p.tgl_trans),DATE(so.lastmodified)) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		if($filter != null){
			$where .= " AND (p.id_trans like '%$filter%' OR j.nama like '%$filter%') ";
		}
        $sql = "SELECT p.*,p.keterangan as info,j.*, IF(so.id_trans IS NULL,DATE(p.tgl_trans),DATE(so.lastmodified)) AS tgl FROM `olndeposit` p LEFT JOIN olnso so ON so.id_trans=p.id_trans Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) ".$where;
        
		//var_dump($sql);
        
		$q = $db->query($sql);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;
        
        $q = $db->query($sql."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

		$statusToko = '';
        $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
        $getStat->execute();
        $stat = $getStat->fetchAll();
        foreach ($stat as $stats) {
            // $id = $stats['id'];
            $statusToko = $stats['status'];
        }

        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		$grand_totalfaktur=0;$grand_tunai=0;$grand_transfer=0;$grand_cashback=0;
        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
        	if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_online/trolndeposit.php?action=edit&id='.$line['id_trans'].'\',\'table_trdeposit\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolndeposit.php?action=delete&id='.$line['id_trans'].'\',\'table_trdeposit\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['kode'],
                $line['nama'],                
                $line['tgl_trans'],                
                number_format($line['totalfaktur'],0),                
				number_format($line['tunai'],0),                
				number_format($line['transfer'],0),                
				number_format($line['cashback'],0),                
				$line['info'],                
				$line['catatan'],                
                // $edit,
				$delete,
            );
			$grand_totalfaktur+=$line['totalfaktur'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_cashback+=$line['cashback'];
			
            $i++;
        }
		$responce['userdata']['totalfaktur'] = number_format( $grand_totalfaktur,0);
		$responce['userdata']['tunai'] 		 = number_format($grand_tunai,0);
		$responce['userdata']['transfer'] 	 = number_format($grand_transfer,0);
		$responce['userdata']['cashback'] 	 = number_format($grand_cashback,0);
		
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'trolndeposit_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'trolndeposit_form.php';exit();
		exit; 
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("DELETE FROM olndeposit WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		$affected_rows = $stmt->rowCount();

		$id_user=$_SESSION['user']['username'];

		// no jurnal
		$masterNo = '';
		$q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
		FROM jurnal ORDER BY id DESC LIMIT 1"));
		$masterNo=$q['nomor'];

		// insert jurnal
		$stmt = $db->prepare("INSERT INTO jurnal (SELECT NULL,'$masterNo',NOW(),REPLACE(keterangan,'Penambahan','CANCELLED'),total_debet,total_kredit,0,'$id_user',NOW(),'DEPOSIT','0' FROM jurnal WHERE jurnal.keterangan LIKE '%".$_GET['id']."%') ");
		$stmt->execute();

		//get master id terakhir
		$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
		$idparent=$q['id'];

		// insert jurnal detail
		$stmt = $db->prepare("INSERT INTO jurnal_detail (SELECT '','$idparent',id_akun,no_akun,nama_akun,jurnal_detail.`status`,kredit,debet,'',0,'$id_user',NOW() FROM jurnal_detail LEFT JOIN jurnal ON jurnal.id=jurnal_detail.id_parent WHERE jurnal.keterangan LIKE '%".$_GET['id']."%') ");
		$stmt->execute();

		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed';
		}
		//var_dump($stmt);die;
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {

		$idAkunKredit = "";
		$nomorAkunKredit = "";
		$namaAkunKredit = "";
		$statusAkunKredit = "";

		if(isset($_POST['akun_kredit']) && $_POST['akun_kredit'] != ""){
			$idAkunKredit = explode(":",$_POST['akun_kredit'])[0];
			$nomorAkunKredit = explode(":",$_POST['akun_kredit'])[1];
			$namaAkunKredit = explode(":",$_POST['akun_kredit'])[2];
			$statusAkunKredit = explode(":",$_POST['akun_kredit'])[3];
		}

		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE olndeposit SET id_trans=?,id_kredit=?,nomor_kredit=?,akun_kredit=?,kode=?,id_dropshipper=?,tgl_trans=?,totalfaktur=?,tunai=?,transfer=?,deposit=?,cashback=?,keterangan=?,catatan=?,user=?, lastmodified = NOW() WHERE id_trans=?");
			$stmt->execute(array($_POST['kode'],$idAkunKredit,$nomorAkunKredit,$namaAkunKredit,$_POST['kode'],$_POST['id_dropshipper'],$_POST['tgl_trans'],$_POST['tunai']+$_POST['transfer']+$_POST['cashback'],$_POST['tunai'],$_POST['transfer'],$_POST['tunai']+$_POST['transfer']+$_POST['cashback'],$_POST['cashback'],strtoupper($_POST['keterangan']),'DEPOSIT',$_SESSION['user']['username'],$_POST['kode']));
			$affected_rows = $stmt->rowCount();
			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
		else {
			$id_user=$_SESSION['user']['username'];

			$stmt = $db->prepare("INSERT INTO olndeposit(`id_trans`,`id_kredit`,`nomor_kredit`,`akun_kredit`,`kode`,`id_dropshipper`,`tgl_trans`,`totalfaktur`,`tunai`,`transfer`,`deposit`,`cashback`,`keterangan`,`catatan`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['kode'],$idAkunKredit,$nomorAkunKredit,$namaAkunKredit,$_POST['kode'],$_POST['id_dropshipper'],$_POST['tgl_trans'],$_POST['tunai']+$_POST['transfer']+$_POST['cashback'],$_POST['tunai'],
			$_POST['transfer'],$_POST['tunai']+$_POST['transfer']+$_POST['cashback'],$_POST['cashback'],strtoupper($_POST['keterangan']),'DEPOSIT',$_SESSION['user']['username']))) {

				$idtrans = $_POST['kode'];

				$transfer='';
				$tunai='';
				$cashback='';
				$total='';
				$dropshipper='';
				$namadropshipper='';
				$q = mysql_fetch_array( mysql_query("SELECT olndeposit.tunai, olndeposit.transfer, olndeposit.cashback, olndeposit.id_dropshipper,mst_dropshipper.nama FROM olndeposit LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olndeposit.id_dropshipper WHERE id_trans='".$idtrans."' LIMIT 1"));
				$transfer=abs($q['transfer']);
				$tunai=abs($q['tunai']);
				$cashback=abs($q['cashback']);
				$total = abs($transfer+$tunai+$cashback);
				$dropshipper=$q['id_dropshipper'];
				$namadropshipper=$q['nama'];

				//insert
				$masterNo = '';
				$q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
				FROM jurnal ORDER BY id DESC LIMIT 1"));
				$masterNo=$q['nomor'];

				// execute for master
				$sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo',NOW(),'Penambahan Saldo Dropshipper - $namadropshipper - $idtrans','$total','$total','0','$id_user',NOW(),'DEPOSIT') ";
				mysql_query($sql_master) or die (mysql_error());

				//get master id terakhir
				$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
				$idparent=$q['id'];
	if($nomorAkunKredit == ""){
				// Insert Detail
				if($tunai > 0){
					//Tunai
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='01.01.00001'");
					while($akun1 = mysql_fetch_array($query1)){
						// Tunai
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$tunai','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}

				if($transfer > 0){
					//BCA
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='01.01.00002'");
					while($akun1 = mysql_fetch_array($query1)){
						// BCA
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$transfer','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}

				if($cashback > 0){
					//Cashback
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='06.01.00001'");
					while($akun1 = mysql_fetch_array($query1)){
						// Cashback
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$cashback','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}

				$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('02.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
				while($akun1 = mysql_fetch_array($query1)){
					// saldo titipan
					$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','0','$total','','0', '$id_user',NOW()) ";
					mysql_query($sqlakun1) or die (mysql_error());
				}
			}else{
				$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('02.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");

				while($akun1 = mysql_fetch_array($query1)){
					// saldo titipan
					$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
					mysql_query($sqlakun1) or die (mysql_error());
				}

				$query2=mysql_query("INSERT INTO jurnal_detail VALUES(NULL, '$idparent','$idAkunKredit','$namaAkunKredit','$nomorAkunKredit','$statusAkunKredit','0','$total','','0','$id_user',NOW())") or die (mysql_error());
			}
				
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
        //var_dump($stmt);die;		
		echo json_encode($r);
		exit;
	}
?>

<script type='text/javascript' src='assets/js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="start_trdeposit" name="start_trdeposit">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_trdeposit" name="end_trdeposit">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterdepo" name="filterdepo">(ID OLN,Dropshipper)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadJual()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>

<div class="btn_box">
<?php

	// $allow = array(1,2,3);
	$statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	// if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_online/trolndeposit.php?action=add\',\'table_trdeposit\')" class="btn">Tambah</button>';
	// }
}
	
?>
</div>
<table id="table_trdeposit"></table>
<div id="pager_table_trdeposit"></div>

<script type="text/javascript">
    $('#start_trdeposit').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_trdeposit').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_trdeposit" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_trdeposit" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJual(){
		var start_trdeposit = $("#start_trdeposit").val();
		var end_trdeposit = $("#end_trdeposit").val();
		var filter_deposit = $("#filterdepo").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_online/trolndeposit.php?action=json&start_trdeposit='+start_trdeposit+'&end_trdeposit='+end_trdeposit+'&filter='+filter_deposit ;
		jQuery("#table_trdeposit").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){

        $("#table_trdeposit").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/trolndeposit.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Kode','Nama','Tgl.Trans','Total Transaksi','Tunai','Transfer','Cashback','Keterangan','Catatan','Delete'],
            colModel:[
                {name:'id_trans',index:'id_trans', align:'right', width:10, searchoptions: {sopt:['cn']}},
                {name:'id_dropshipper',index:'id_dropshipper', width:55, searchoptions: {sopt:['cn']}},  
				{name:'nama',index:'nama', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:60, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},                
                {name:'totalfaktur',index:'totalfaktur',align:'right', width:70, searchoptions: {sopt:['cn']}},                
                {name:'tunai',index:'tunai',align:'right', width:60, searchoptions: {sopt:['cn']}},                
				{name:'transfer',index:'transfer',align:'right', width:60, searchoptions: {sopt:['cn']}},             
				{name:'cashback',index:'cashback',align:'right', width:60, searchoptions: {sopt:['cn']}},             
                {name:'keterangan',align:'center',index:'info', width:60, searchoptions: {sopt:['cn']}},
				{name:'catatan',index:'catatan', align:'center', width:100, sortable: false, search: false},
				// {name:'Edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_trdeposit',
            sortname: 'id_trans',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Transaksi Deposit Toko",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
        });
        $("#table_trdeposit").jqGrid('navGrid','#pager_table_trdeposit',{edit:false,add:false,del:false});
    })
</script>