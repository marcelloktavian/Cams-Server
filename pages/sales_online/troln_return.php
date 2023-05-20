<?php require_once '../../include/config.php';
include "../../include/koneksi.php";?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, OnlineReturn, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, OnlineReturn, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
	   $where = "WHERE TRUE AND p.state='0' AND (p.totalqty <> 0)";
	   } 
	   else 
	   {
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
		$where = sprintf(" where TRUE AND p.state='0' AND (p.totalqty <> 0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	    }
		
		$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition FROM `olnsoreturn` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
        //var_dump($sql);
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
		$grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
        	if ($statusToko == 'Tutup') {
                $posting = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Posting</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
            } else {
		    if($allow_post){
				$posting = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_return.php?action=posting&id='.$line['id_trans'].'\',\'table_olnretur\')" href="javascript:;">Posting</a>';
			}
			else
				$posting = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">Posting</a>';
			
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_return.php?action=delete&id='.$line['id_trans'].'\',\'table_olnretur\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';
			
			    //$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			}

        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['ref_kode'],                
                $line['id_oln'],                
                $line['dropshipper'],                
                $line['tgl_trans'],
                $line['nama'],
                $line['alamat'],
                number_format($line['exp_fee'],0),
                $line['expedition'],
                $line['exp_code'],
				number_format($line['totalqty'],0),
				$posting,
				$delete,
				
            );
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['total'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['exp_fee'];
            $i++;
        }
		
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		/*
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 			= number_format($grand_biaya,0);
        */
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting')
    {	
		$id_user=$_SESSION['user']['username'];

		$ref_kode = '';
		$tgl = '';
		$penalty = '';
		$totalreturn = '';
		$qoln = mysql_fetch_array( mysql_query("SELECT * FROM  olnsoreturn WHERE id_trans='".$_GET['id']."'"));
		$ref_kode = $qoln['ref_kode'];
		$tgl = $qoln['tgl_trans'];
		$idoln=$qoln['id_oln'];
		$penalty=$qoln['penalty'];
		$totalreturn=$qoln['total'];

		$type = '';
		$total='';
		$dropshipper='';
		$namadropshipper='';
		$expfee='';
		$q = mysql_fetch_array( mysql_query("SELECT olnso.id_trans, olnso.total, olnso.transfer, olnso.deposit, olnso.piutang, olnso.id_dropshipper,mst_dropshipper.nama, IFNULL(olnso.exp_fee,0) as exp_fee FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE id_trans='".$idoln."' LIMIT 1"));
		if($q['piutang'] > 0){
			$type='Kredit';
		}else{
			$type='Cash';
		}
		$total=$qoln['total']+$qoln['penalty'];
		$dropshipper=$q['id_dropshipper'];
		$namadropshipper=$q['nama'];
		$expfee=$q['exp_fee'];

		//insert
		$masterNo = '';
		$q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
		FROM jurnal ORDER BY id DESC LIMIT 1"));
		$masterNo=$q['nomor'];

		// execute for master
		$sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo',NOW(),'Retur OLN $type - $namadropshipper - $idoln','$total','$total','0','$id_user',NOW(),'RETUR') ";
		mysql_query($sql_master) or die (mysql_error());

		//get master id terakhir
		$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
		$idparent=$q['id'];
			
		// $dpp = round(($total-$expfee) / 1.11);
		// $ppn = round(($total-$expfee) / 1.11 * 0.11);

		$dpp = round(($total) / 1.11);
		$ppn = round(($total) / 1.11 * 0.11);

		if($type=='Cash'){
			$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.01.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
			while($akun1 = mysql_fetch_array($query1)){
				// penjualan oln cash
				$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$dpp','0','','0', '$id_user',NOW()) ";
				mysql_query($sqlakun1) or die (mysql_error());
			}
		}else{
			$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
			while($akun1 = mysql_fetch_array($query1)){
				// penjualan oln credit
				$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$dpp','0','','0', '$id_user',NOW()) ";
				mysql_query($sqlakun1) or die (mysql_error());
			}
		}

		$query2=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.01.00000'");
		while($akun2 = mysql_fetch_array($query2)){
			// ppn
			$sqlakun2="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun2['id']."','".$akun2['noakun']."','".$akun2['nama']."','".$akun2['status']."','$ppn','0','','0', '$id_user',NOW()) ";
			mysql_query($sqlakun2) or die (mysql_error());
		}
		
		if($penalty != '0'){
			$query3=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.04.00001'");
			while($akun3 = mysql_fetch_array($query3)){
				// ppn
				$sqlakun3="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun3['id']."','".$akun3['noakun']."','".$akun3['nama']."','".$akun3['status']."','0','$penalty','','0', '$id_user',NOW()) ";
				mysql_query($sqlakun3) or die (mysql_error());
			}
		}

		if($type=='Cash'){
			$query4=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('02.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
			while($akun4 = mysql_fetch_array($query4)){
				// saldo dropshipper
				$sqlakun4="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun4['id']."','".$akun4['noakun']."','".$akun4['nama']."','".$akun4['status']."','0','$totalreturn','','0', '$id_user',NOW()) ";
				mysql_query($sqlakun4) or die (mysql_error());
			}
			
			$select2 = $db->prepare("Select max(substring(kode,3,10)+1) as kode_id from olndeposit where kode like 'TD%'");
			$select2->execute();
			$row2  = $select2->fetch(PDO::FETCH_ASSOC);
			$kode  = "TD".sprintf("%03d", $row2['kode_id']);

			// tambah saldo dropshipper
			$sqlsaldo="INSERT INTO `olndeposit` VALUES
				('$kode','$kode','$tgl','$dropshipper','','REFUND ORDER #$ref_kode','DEPOSIT','0','0',NULL,'$totalreturn','$totalreturn','0','0', '0','$totalreturn','0','0','$id_user',NOW());";
			mysql_query($sqlsaldo) or die (mysql_error());
		}else{
			$query4=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('01.04.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
			while($akun4 = mysql_fetch_array($query4)){
				// piutang dropshipper
				$sqlakun4="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun4['id']."','".$akun4['noakun']."','".$akun4['nama']."','".$akun4['status']."','0','$totalreturn','','0', '$id_user',NOW()) ";
				mysql_query($sqlakun4) or die (mysql_error());
			}
		}

		//update olnsoreturn agar jadi 1 krn return confirmed,tapi statenya dikasih string='1' krn tipe datanya enum
		$stmt = $db->prepare("Update olnsoreturn set state='1',lastmodified=now() WHERE id_trans=?");
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
		
		//delete olnsoreturn_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("DELETE FROM olnsoreturn_detail WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//delete olnsoreturn
		$stmt = $db->prepare("DELETE FROM olnsoreturn WHERE id_trans=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		//$id = $line['id_trans'];
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `olnsoreturn_detail` pd ".$where);
		
		$count = $q->rowCount();
		
		//$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_rn_d'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
                 number_format($line['harga_nett'],0),
                 number_format($line['disc_return'],0),                
                 number_format($line['jumlah_return'],0),                
                 number_format($line['subtotal_return'],0),                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	 
	 
?>
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<div class="ui-corner-all form-control">
        		<?php
	$statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Print All Return</button>';
    }else{
    	?>
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/sales_online/troln_returnrpt.php?action=preview&start='+$('#startdate_jualsm').val()+'&end='+$('#enddate_jualsm').val()+'&filter='+$('#filter_sosum').val())" class="btn" type="button">Print All Return</button>
            <?php } ?>
            </div>
       	</form>
   	</div>
</div>

 
<table id="table_olnretur"></table>
<div id="pager_table_olnretur"></div>



<script type="text/javascript">
    function gridReloadJual(){
		var startdate_jual = $("#startdate_jualsm").val();
		var enddate_jual = $("#enddate_jualsm").val();
		var filter_sosum = $("#filter_sosum").val();
		
		var v_url ='<?php echo BASE_URL?>pages/sales_online/troln_return.php?action=json&filter='+filter_sosum ;
		jQuery("#table_olnretur").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_olnretur").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/troln_return.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','ID_web','ID_oln','Dropshipper','Date','Receiver','Address','Exp.Fee','Expedition','Exp.Code','Qty','Posting','Cancel'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'ref_kode',index:'ref_kode', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'id_oln',index:'id_oln', width:40, searchoptions: {sopt:['cn']}},                
                {name:'dropshipper',index:'dropshipper', width:40, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'nama',index:'nama', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'alamat',index:'alamat', align:'left', width:100, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:20, searchoptions: {sopt:['cn']}},
				{name:'expedition',index:'expedition', align:'left', width:35, searchoptions: {sopt:['cn']}},
				{name:'exp_code',index:'exp.code', align:'left', width:35, searchoptions: {sopt:['cn']}},
                {name:'totalqty',index:'totalqty', align:'right', width:20, searchoptions: {sopt:['cn']}},
                {name:'posting',index:'posting', align:'center', width:30, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},                
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_olnretur',
            sortname: 'id_trans',
            autowidth: true,
			//multiselect:true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Retur Online",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/troln_return.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Harga','Pinalty','Qty(return)','Subtotal'], 
			            		width : [40,40,300,30,50,50,50],
			            		align : ['right','center','left','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_olnretur").jqGrid('navGrid','#pager_table_olnretur',{edit:false,add:false,del:false,search:true});
		
    })
</script>