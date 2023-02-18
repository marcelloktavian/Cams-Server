<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));

$allow_add = is_show_menu(ADD_POLICY, cashReceipt, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, cashReceipt, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, cashReceipt, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_cashReceipt'])?$_GET['startdate_cashReceipt']:date('Y-m-d');
		$enddate = isset($_GET['enddate_cashReceipt'])?$_GET['enddate_cashReceipt']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tanggal'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND deleted=0";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(a.tanggal) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (b.namabiaya like '%$filter%' OR a.subtotal like '%$filter%' OR a.ppn like '%$filter%' OR a.total like '%$filter%') ";	
		}	
		else
		{
			$where .=" AND DATE(a.tanggal) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = " SELECT a.* FROM cashreceipt a LEFT JOIN cashreceipt_det b ON b.id_parent=a.id ".$where." GROUP BY a.id";
		// var_dump($sql);
		// die;	
		$q = $db->query($sql);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
							//  var_dump($q);
							//  die;
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		// footer
		$total_debet=0;
		$total_kredit=0;
		// ./././
        foreach($data1 as $line) {
        	
			// $allowInvoice = array(1,2,3);
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
		    // if(in_array($_SESSION['user']['access'], $allowEdit)){
				if($allow_edit){
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/Transaksi_Operasional/EditCashReceiptDet.php?action=edit&id='.$line['id'].'\',\'tablecashreceipt\')" href="javascript:;">Edit</a>';
				}else{
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
				}

				if($allow_delete){
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/cashReceipt.php?action=delete&id='.$line['id'].'\',\'tablecashreceipt\')" href="javascript:;">Delete</a>';
				}else{
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
				}
			

        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['tanggal'],                	
                number_format($line['total_debet'],0),
                number_format($line['total_kredit'],0),

                //number_format($line['grandtotal'],0),
				$edit,
				$delete,
			);
			$total_debet+=$line['total_debet'];
			$total_kredit+=$line['total_kredit'];
            $i++;
		}
		$responce['userdata']['total_debet'] = number_format($total_debet,2);
		$responce['userdata']['total_kredit'] = number_format($total_kredit,2);

		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//detail
		$stmt = $db->prepare("UPDATE cashreceipt set deleted=1 WHERE id=?");
		$stmt->execute(array($_GET['id']));
		// master
		$stmt = $db->prepare("DELETE FROM cashreceipt_det WHERE id_parent=?");
		$stmt->execute(array($_GET['id']));
		//detail
		$stmt = $db->prepare("UPDATE account_balance set deleted=1 WHERE id_receipt=? AND noakun='1.01.00.00' ");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'unpost') {
		$stmt = $db->prepare("UPDATE transaksi_penjualan SET st=? WHERE id=?");
		$stmt->execute(array(1, $_GET['id']));
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
		error_reporting(0);
		$id = $_GET["id"];
		$where = "WHERE id_parent = '".$id."' ";
        $q = $db->query("SELECT id,no_akun,nama_akun,uraian,buktikas,debet,kredit FROM cashreceipt_det  ".$where);
		
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
		$responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['no_akun'],
                $line['uraian'],
                $line['buktikas'],
				number_format($line['debet'],0),
				number_format($line['kredit'],0),
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
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_cashReceipt" name="startdate_cashReceipt">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_cashReceipt" name="enddate_cashReceipt">
				</td>
				<!-- <td> Filter -->
				 <input value="" type="hidden" id="filtercashReceipt" name="filtercashReceipt">
				 <!-- (Nama Biaya,Tanggal,Subtotal,PPN,Total) -->
				<!-- </td> -->
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridcashReceipt()" class="btn" type="button">Cari</button>
				<button onclick="printrptCashReceipt()" class="btn" type="button">Cetak Harian</button>
				<button onclick="printrptCashReceiptBulanan()" class="btn" type="button">Cetak Bulanan</button>
            </div>
       	</form>
   	</div>
</div>
	<div class="btn_box">
	<?php
	// $allow = array(1,2,3);
	if($allow_add) {
		echo '<button type="button" onclick="javascript:window.open(\''.BASE_URL.'pages/Transaksi_Operasional/cashReceiptDet.php?action=add\',\'table_biayaOperasional\')" class="btn">Tambah</button>';
	}
?>
	<!--
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Add</button></a>   
   -->
 <!-- <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span> -->
<!-- <button id="btn-xlsdo"  class="btn btn-success">XLS Selected</button>
<button id="btn-print"  class="btn btn-success">Print Selected Label</button> -->
</div>
 
<table id="tablecashreceipt"></table>
<div id="pager_cashreceipt"></div>

<!--
<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';		
	}	
	*/
?>
-->

<script type="text/javascript">
	 
	$('#startdate_cashReceipt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_cashReceipt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_cashReceipt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_cashReceipt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printrptCashReceipt() {
		var filter = $('#filtercashReceipt').val();
		var startdate = $('#startdate_cashReceipt').val();
		var enddate = $('#enddate_cashReceipt').val();
		// console.log(filter+' '+lokasi_list);

		if(startdate != enddate){
			alert('Tanggal Mulai dan Tanggal Selesai Harus Sama');
		}else{
			window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/rpt_cashReceipt.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		}

		
	}

	function printrptCashReceiptBulanan() {
		var filter = $('#filtercashReceipt').val();
		var startdate = $('#startdate_cashReceipt').val();
		var enddate = $('#enddate_cashReceipt').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/rpt_cashReceipt_bulan.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		
	}
	
	function gridcashReceipt(){
		var startdate_cashReceipt = $("#startdate_cashReceipt").val();
		var enddate_cashReceipt = $("#enddate_cashReceipt").val();
		var filter_do = $("#filtercashReceipt").val();
		var v_url ='<?php echo BASE_URL?>pages/Transaksi_Operasional/cashReceipt.php?action=json&startdate_cashReceipt='+startdate_cashReceipt+'&enddate_cashReceipt='+enddate_cashReceipt+'&filter='+filter_do;
		jQuery("#tablecashreceipt").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
	$("#btn-xlsdo").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
				window.open('<?php echo BASE_URL?>pages/sales_online/trolndo_xls.php?ids='+ids,'_blank');
	});
	
	$("#btn-print").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
				window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_3nota_new.php?ids='+ids,'_blank');
	});
	
	 function getSelectedRows() {
            var grid = $("#tablecashreceipt");
            var rowKey = grid.getGridParam("selrow");

            if (!rowKey){
                alert("No rows are selected");
				return '';
			}
            else {
                var selectedIDs = grid.getGridParam("selarrrow");
                var result = "";
                for (var i = 0; i < selectedIDs.length; i++) {
                    result += "'"+selectedIDs[i]+"'" + ",";
                }

               return result;
            }                
        }
		 

    $(document).ready(function(){
        $("#tablecashreceipt").jqGrid({
            url:'<?php echo BASE_URL.'pages/Transaksi_Operasional/cashReceipt.php?action=json'; ?>',
            datatype: "json",
            colNames:['Date','Total Debet','Total Kredit','Edit','Delete'],
            colModel:[
                {name:'tanggal',index:'tanggal', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
				{name:'total_debet',index:'total_debet', align:'right', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'total_kredit',align:'right',index:'total_kredit', width:40, searchoptions: {sopt:['cn']}},                
				{name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_cashreceipt',
            sortname: 'tanggal',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Cash Receipt",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/Transaksi_Operasional/cashReceipt.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Account','Uraian','Bukti Kas','Debet','Kredit'], 
			            		width : [40,150,250,100,70,70],
			            		align : ['left','center','center','center','right','right'],
			            	} 
			            ],
						
            
        });
        $("#tablecashreceipt").jqGrid('navGrid','#pager_cashreceipt',{edit:false,add:false,del:false,search:false});
    })
</script>