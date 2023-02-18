<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_trdepositpb'])?$_GET['start_trdepositpb']:date('Y-m-d');
		$enddate = isset($_GET['end_trdepositpb'])?$_GET['end_trdepositpb']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	      
        $where = "WHERE TRUE ";
		
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}
        $sql = "SELECT p.*,p.keterangan as info,j.* FROM `trdepositpb` p Left Join `tblsupplier` j on (p.id_customer=j.id) ".$where;
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
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		$grand_totalfaktur=0;$grand_tunai=0;$grand_transfer=0;
        foreach($data1 as $line) {
        	$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/pabrik/trdepositpb.php?action=edit&id='.$line['id_trans'].'\',\'table_trdepositpb\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/pabrik/trdepositpb.php?action=delete&id='.$line['id_trans'].'\',\'table_trdepositpb\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['kode'],
                $line['namaperusahaan'],                
                $line['tgl_trans'],                
                number_format($line['totalfaktur'],0),                
				number_format($line['tunai'],0),                
				number_format($line['transfer'],0),                
				$line['info'],                
				$line['catatan'],                
                $edit,
				$delete,
            );
			$grand_totalfaktur+=$line['totalfaktur'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			
            $i++;
        }
		$responce['userdata']['totalfaktur'] = number_format( $grand_totalfaktur,0);
		$responce['userdata']['tunai'] 		 = number_format($grand_tunai,0);
		$responce['userdata']['transfer'] 	 = number_format($grand_transfer,0);
		
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'trdepositpb_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'trdepositpb_form.php';exit();
		exit; 
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("DELETE FROM trdepositpb WHERE id_trans=?");
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
		//var_dump($stmt);die;
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE trdepositpb SET id_trans=?,kode=?,id_customer=?,tgl_trans=?,totalfaktur=?,tunai=?,transfer=?,deposit=?,keterangan=?,catatan=?,user=?, lastmodified = NOW() WHERE id_trans=?");
			$stmt->execute(array($_POST['kode'],$_POST['kode'],$_POST['id_cust'],$_POST['tgl_trans'],$_POST['tunai']+$_POST['transfer'],$_POST['tunai'],$_POST['transfer'],$_POST['tunai']+$_POST['transfer'],strtoupper($_POST['keterangan']),'DEPOSIT',$_SESSION['user']['username'],$_POST['kode']));
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
			$stmt = $db->prepare("INSERT INTO trdepositpb(`id_trans`,`kode`,`id_customer`,`tgl_trans`,`totalfaktur`,`tunai`,`transfer`,`deposit`,`keterangan`,`catatan`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['kode'],$_POST['kode'],$_POST['id_cust'],$_POST['tgl_trans'],$_POST['tunai']+$_POST['transfer'],$_POST['tunai'],$_POST['transfer'],$_POST['tunai']+$_POST['transfer'],strtoupper($_POST['keterangan']),'DEPOSIT',$_SESSION['user']['username']))) {
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
				 <input value="" type="text" class="required datepicker"   id="start_trdepositpb" name="start_trdepositpb">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_trdepositpb" name="end_trdepositpb">
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

<table id="table_trdepositpb"></table>
<div id="pager_table_trdepositpb"></div>
<div class="btn_box">
<?php

	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/pabrik/trdepositpb.php?action=add\',\'table_trdepositpb\')" class="btn">Tambah</button>';
	}
	
?>
</div>
<script type="text/javascript">
    $('#start_trdepositpb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_trdepositpb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_trdepositpb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_trdepositpb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJual(){
		var start_trdepositpb = $("#start_trdepositpb").val();
		var end_trdepositpb = $("#end_trdepositpb").val();
		var v_url ='<?php echo BASE_URL?>pages/pabrik/trdepositpb.php?action=json&start_trdepositpb='+start_trdepositpb+'&end_trdepositpb='+end_trdepositpb ;
		jQuery("#table_trdepositpb").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){

        $("#table_trdepositpb").jqGrid({
            url:'<?php echo BASE_URL.'pages/pabrik/trdepositpb.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Kode','Nama','Tgl.Trans','TotalFaktur','Tunai','Transfer','Keterangan','Catatan','Edit','Delete'],
            colModel:[
                {name:'id_trans',index:'id_trans', align:'right', width:10, searchoptions: {sopt:['cn']}},
                {name:'id_cust',index:'id_cust', width:55, searchoptions: {sopt:['cn']}},  
				{name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:60, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},                
                {name:'totalfaktur',index:'totalfaktur',align:'right', width:70, searchoptions: {sopt:['cn']}},                
                {name:'tunai',index:'tunai',align:'right', width:60, searchoptions: {sopt:['cn']}},                
				{name:'transfer',index:'transfer',align:'right', width:60, searchoptions: {sopt:['cn']}},             
                {name:'keterangan',align:'right',index:'info', width:60, searchoptions: {sopt:['cn']}},
				{name:'catatan',index:'catatan', align:'center', width:100, sortable: false, search: false},
				{name:'Edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_trdepositpb',
            sortname: 'id_trans',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Transaksi Deposit Pabrik",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
        });
        $("#table_trdepositpb").jqGrid('navGrid','#pager_table_trdepositpb',{edit:false,add:false,del:false});
    })
</script>