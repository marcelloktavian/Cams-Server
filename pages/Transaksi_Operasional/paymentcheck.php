<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));

$allow_add = is_show_menu(ADD_POLICY, PaymentCheck, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, PaymentCheck, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, PaymentCheck, $group_acess);
$allow_post = is_show_menu(POST_POLICY, PaymentCheck, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_Paymentcheck'])?$_GET['startdate_Paymentcheck']:date('Y-m-d');
		$enddate = isset($_GET['enddate_Paymentcheck'])?$_GET['enddate_Paymentcheck']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tanggal'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND deleted=0 ";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(a.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (a.id_check like '%$filter%' OR a.note like '%$filter%' OR (IF(b.stat_dropcust = 'Dropshipper' ,(SELECT nama from mst_dropshipper where id=b.id_dropcust) , IF(b.stat_dropcust='Customer',(SELECT nama from mst_b2bcustomer where id=b.id_dropcust),'') ) ) like '%$filter%'  )";	
		}	
		else
		{
			$where .=" AND DATE(a.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = " SELECT a.*,(SELECT bn.periode FROM trpaymentcheck_detail det LEFT JOIN acc_prebank bn ON bn.id=det.id_import WHERE det.id_parent=a.id AND det.`id_import` <> '0' LIMIT 1) as periode,(SELECT IFNULL(SUM(koreksi),0) FROM trpaymentcheck_detail det WHERE det.id_parent=2 AND det.`id_olnb2b` = 'KOREKSI' LIMIT 1) as koreksi FROM trpaymentcheck a LEFT JOIN trpaymentcheck_detail b ON a.id=b.id_parent ".$where." GROUP BY a.id ";
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
        foreach($data1 as $line) {
			if ($statusToko == 'Tutup') {
				if($line['posting'] == '0'){
					$edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
				}else{
					$edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Print</a>';
				}
            }else{
				if($allow_edit){
					if($line['posting'] == '0'){
						$edit = '<a onclick="window.open(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheck_edit.php?action=edit&id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">Edit</a>';
					}else{
						$edit = '<a onclick="window.open(\''.BASE_URL.'pages/Transaksi_Operasional/rpt_paymentcheck.php?id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">Print</a>';
					}
				}else{
					if($line['posting'] == '0'){
						$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
					}else{
						$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Print</a>';
					}
				}
			}

			if ($statusToko == 'Tutup') {
				if($line['posting'] == '0'){
					$post = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Post</a>';
				}else{
					$post = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">POSTED</a>';
				}	
				$detail = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Detail</a>';
            }else{
				if($allow_post){
					if($line['posting'] == '0'){
						$post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=posting&id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">Post</a>';
					}else{
						$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					}	
					$detail = '<a onclick="window.open(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheck_view.php?id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">Detail</a>';
				}else{
					if($line['posting'] == '0'){
						$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';
					}else{
						$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					}	
					$detail = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Detail</a>';
				}
			}

			if ($statusToko == 'Tutup') {
				if($line['posting'] == '0'){
					$delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
				}else{
					$delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">UNPOST</a>';
				}
            }else{
				if($allow_delete){
					if($line['posting'] == '0'){
						$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=delete&id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">Delete</a>';
					}else{
						$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=unpost&id='.$line['id'].'\',\'tbllPaymentcheck\')" href="javascript:;">UNPOST</a>';
					}	
				}else{
					if($line['posting'] == '0'){
						$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
					}else{
						$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">UNPOST</a>';
					}
				}
			}

        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
				$line['id_check'],   
                $line['periode'],                	
                $line['lastmodified'],                	
                number_format($line['total_csv'],0),
                number_format($line['total_oln'],0),
                number_format($line['koreksi'],0),
                $line['note'],
				$detail,
				$edit,
				$post,
				$delete,
			);
            $i++;
		}
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE trpaymentcheck set deleted=1 WHERE id=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
		$id = $_GET['id'];
		$stmt = $db->prepare("UPDATE trpaymentcheck set posting=1 WHERE id=?");
		$stmt->execute(array($id));

		$where = "WHERE pd.id_parent = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `trpaymentcheck_detail` pd ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        foreach($data1 as $line){
			if($line['id_import']!='' && $line['id_olnb2b']==''){
				//update payment import
				$stmt1 = $db->prepare("UPDATE acc_prebank set payment=payment+? WHERE id=?");
				$stmt1->execute(array($line['payment_value'],$line['id_import']));
			}else if($line['id_import']=='' && $line['id_olnb2b']!=''){
				//update payment oln/b2b
				if(substr($line['id_olnb2b'],0,3) == 'OLN'){
					$stmt1 = $db->prepare("UPDATE olnso set payment=payment+? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}else{
					$stmt1 = $db->prepare("UPDATE b2bdo set payment=payment+? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}
			}else if($line['id_import']!='' && $line['id_olnb2b']!=''){
				//update payment import
				$stmt1 = $db->prepare("UPDATE acc_prebank set payment=payment+? WHERE id=?");
				$stmt1->execute(array($line['payment_value'],$line['id_import']));

				//update payment oln/b2b
				if(substr($line['id_olnb2b'],0,3) == 'OLN'){
					$stmt1 = $db->prepare("UPDATE olnso set payment=payment+? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}else{
					$stmt1 = $db->prepare("UPDATE b2bdo set payment=payment+? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}
			}
        }

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
		$id = $_GET['id'];
		$stmt = $db->prepare("UPDATE trpaymentcheck set posting=0 WHERE id=?");
		$stmt->execute(array($id));

		$where = "WHERE pd.id_parent = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `trpaymentcheck_detail` pd ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        foreach($data1 as $line){
			if($line['id_import']!='' && $line['id_olnb2b']==''){
				//update payment import
				$stmt1 = $db->prepare("UPDATE acc_prebank set payment=payment-? WHERE id=?");
				$stmt1->execute(array($line['payment_value'],$line['id_import']));
			}else if($line['id_import']=='' && $line['id_olnb2b']!=''){
				//update payment oln/b2b
				if(substr($line['id_olnb2b'],0,3) == 'OLN'){
					$stmt1 = $db->prepare("UPDATE olnso set payment=payment-? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}else{
					$stmt1 = $db->prepare("UPDATE b2bdo set payment=payment-? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}
			}else if($line['id_import']!='' && $line['id_olnb2b']!=''){
				//update payment import
				$stmt1 = $db->prepare("UPDATE acc_prebank set payment=payment-? WHERE id=?");
				$stmt1->execute(array($line['payment_value'],$line['id_import']));

				//update payment oln/b2b
				if(substr($line['id_olnb2b'],0,3) == 'OLN'){
					$stmt1 = $db->prepare("UPDATE olnso set payment=payment-? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}else{
					$stmt1 = $db->prepare("UPDATE b2bdo set payment=payment-? WHERE id_trans=?");
					$stmt1->execute(array($line['subtotal'],$line['id_olnb2b']));
				}
			}
        }

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
		// error_reporting(0);
		// $id = $_GET["id"];
        // $q1 = $db->query("SELECT id_import FROM trpaymentcheck_detail det WHERE det.id_parent='".$id."' AND id_import<>'0'");
		// $data1 = $q1->fetchAll(PDO::FETCH_ASSOC);
		
        // $i=0;
		// $responce = '';
        // foreach($data1 as $line1){
		// 	$idimport = $line['id_import'];
		// 	$q2 = $db->query("SELECT id_import FROM trpaymentcheck_detail det WHERE det.id_parent='".$id."' AND id_import<>'0'");
		// 	$data2 = $q2->fetchAll(PDO::FETCH_ASSOC);



        //     $responce->rows[$i]['id']   = $line['id'];
        //     $responce->rows[$i]['cell'] = array(
        //         $i+1,
        //         $line['keterangan'],
        //         $line['periode'],
		// 		number_format($line['payment_value'],0),
        //         $line['id_trans'],
        //         '',
        //         $line['tgl_trans'],
		// 		number_format($line['subtotal'],0),
		// 	);
        //     $i++;
        // }
        // echo json_encode($responce);
		exit;
	}
	 
	 
?>
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Check Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_Paymentcheck" name="startdate_Paymentcheck">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_Paymentcheck" name="enddate_Paymentcheck">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterPaymentcheck" name="filterPaymentcheck">
				 (ID Check,Note,Dropshipper/Customer)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridPaymentcheck()" class="btn" type="button">Cari</button>
            	<button onclick="print()" class="btn" type="button">Print</button>
				<!-- <button onclick="printrptOperasional()" class="btn" type="button">Cetak</button> -->
				<!-- <button onclick="printrptharian()" class="btn" type="button">Cetak Harian</button> -->
			</div>
       	</form>
   	</div>
</div>
	<div class="btn_box">
	<?php
	// $allow = array(1,2,3);
	if($allow_add) {
		echo '<button type="button" onclick="javascript:window.open(\''.BASE_URL.'pages/Transaksi_Operasional/paymentcheckDet.php?action=add\',\'tbllPaymentcheck\')" class="btn">Tambah</button>';
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
 
<table id="tbllPaymentcheck"></table>
<div id="pager_tbllPaymentcheck"></div>

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
	 
	$('#startdate_Paymentcheck').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_Paymentcheck').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_Paymentcheck" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_Paymentcheck" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printrptOperasional() {
		var filter = $('#filterPaymentcheck').val();
		var startdate = $('#startdate_Paymentcheck').val();
		var enddate = $('#enddate_Paymentcheck').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/rpt_biayaOperasional.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		
	}
	
	function gridPaymentcheck(){
		var startdate_Paymentcheck = $("#startdate_Paymentcheck").val();
		var enddate_Paymentcheck = $("#enddate_Paymentcheck").val();
		var filter_do = $("#filterPaymentcheck").val();
		var v_url ='<?php echo BASE_URL?>pages/Transaksi_Operasional/paymentcheck.php?action=json&startdate_Paymentcheck='+startdate_Paymentcheck+'&enddate_Paymentcheck='+enddate_Paymentcheck+'&filter='+filter_do;
		jQuery("#tbllPaymentcheck").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
	
	function print() {
		var filter = $('#filterPaymentcheck').val();
		var startdate = $('#startdate_Paymentcheck').val();
		var enddate = $('#enddate_Paymentcheck').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/rpt_harianpaymentcheck.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		
	}

	 function getSelectedRows() {
            var grid = $("#tbllPaymentcheck");
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
        $("#tbllPaymentcheck").jqGrid({
            url:'<?php echo BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=json'; ?>',
            datatype: "json",
            colNames:['Code','Periode CSV','Check Date','Total Payment','Total Sales','Total Adjustment','Note','Detail','Edit','Post','Delete'],
            colModel:[
				{name:'id_check',align:'left',index:'id_check', width:40, searchoptions: {sopt:['cn']}},   
				{name:'periode',align:'center',index:'periode', width:80, searchoptions: {sopt:['cn']}}, 
                {name:'lastmodified',index:'lastmodified', width:40, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
				{name:'payment',index:'payment', align:'right', width:60, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'sales',index:'sales', align:'right', width:60, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'adjustment',index:'adjustment', align:'right', width:60, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'note',align:'center',index:'note', width:100, searchoptions: {sopt:['cn']}},                
                //{name:'grandtotal',index:'grandtotal', align:'left', width:60, searchoptions: {sopt:['cn']}},
				{name:'detail',index:'detail', align:'center', width:25, sortable: false, search: false},
				{name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
				{name:'post',index:'post', align:'center', width:25, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_tbllPaymentcheck',
            sortname: 'id_check',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Payment Check",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : false,
            subGridUrl : '<?php echo BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Keterangan','Tgl Payment','Payment','Order ID','Dropshipper/Customer','Tgl Sales','Value',], 
			            		width : [40,250,100,100,100,250,100,100],
			            		align : ['right','center','center','right','center','center','center','right'],
			            	} 
			            ],
						
            
        });
        $("#tbllPaymentcheck").jqGrid('navGrid','#pager_tbllPaymentcheck',{edit:false,add:false,del:false,search:false});
    })
</script>