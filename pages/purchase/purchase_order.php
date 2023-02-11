<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, purchaseorder, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, purchaseorder, $group_acess);
$allow_post = is_show_menu(EDIT_POLICY, purchaseorder, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, purchaseorder, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate_po = isset($_GET['startdate_po'])?$_GET['startdate_po']:date('Y-m-d');
		$enddate_po = isset($_GET['enddate_po'])?$_GET['enddate_po']:date('Y-m-d'); 
        $filter=$_GET['filter'];
        
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'po_date'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        $where = "WHERE TRUE AND (p.deleted = 0) and (p.posting='1')";
		/*
		if($startdate_po != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate_po','%d/%m/%Y') AND STR_TO_DATE('$enddate_po','%d/%m/%Y')";
			
		}	
		*/
		if(($startdate_po != null) && ($filter != null)) {
			$where .= " AND DATE(p.po_date) BETWEEN STR_TO_DATE('$startdate_po','%d/%m/%Y') AND STR_TO_DATE('$enddate_po','%d/%m/%Y') AND ((s.nama like '%$filter%') or (p.id_po like '%$filter%') or (p.po_number like '%$filter%'))";
		}	
		else
		{
		    $where .=" AND DATE(p.po_date) BETWEEN STR_TO_DATE('$startdate_po','%d/%m/%Y') AND STR_TO_DATE('$enddate_po','%d/%m/%Y')";
		}
		
		$sql = "SELECT p.*,s.nama as supplier FROM `purchase_order` p Left Join `mst_supplier` s on (s.id_supplier=s.id)  ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
        //var_dump($sql); die;
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
		$grand_qty=0;
        $grand_subtotal=0;
        $grand_disc=0;
        $grand_ppn=0;
        $grand_total=0;
        foreach($data1 as $line) {
        	
			if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $post = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">POSTED</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
            } else {
		    if($allow_post){
                if($line['posting'] == 'Y'){
                    $post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/purchase/purchase_order.php?action=unpost&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Unpost</a>';
                }else{
                    $post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/purchase/purchase_order.php?action=posting&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Posting</a>';
                }
			}
			else
				$post = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">Post</a>';
			
            if($allow_edit){
                $edit = '<a onclick="window.open(\''.BASE_URL.'pages/purchase/purchase_order_edit.php?id='.$line['id'].'\',\'table_po\')" href="javascript:;">Edit</a>';
            }
            else{
                $edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Edit Data\')" href="javascript:;">Edit</a>';
            }
            

			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/purchase/purchase_order.php?action=delete&id='.$line['id'].'\',\'table_po\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
			}

        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['id_po'],                
                $line['po_number'], 
                $line['supplier'],                
                $line['po_date'],
                $line['tgljt'],
                number_format($line['total_qty'],0),
				number_format($line['total_price'],0),
				number_format($line['total_ppn'],0),
				number_format($line['total_disc'],0),
				number_format($line['grand_total'],0),
				$edit,
				$post,
				$delete,
				//$delete,
            );
            $grand_qty+=$line['total_qty'];
            $grand_subtotal+=$line['total_price'];
            $grand_ppn+=$line['total_ppn'];
            $grand_disc+=$line['total_disc'];
            $grand_total+=$line['grand_total'];
			
            $i++;
        }
		
		$responce['userdata']['total_qty'] 		    = number_format($grand_qty,0);
		$responce['userdata']['total_price'] 		= number_format($grand_subtotal,0);
		$responce['userdata']['total_ppn'] 	        = number_format($grand_ppn,0);
		$responce['userdata']['total_disc'] 		= number_format($grand_disc,0);
		$responce['userdata']['grand_total'] 	    = number_format($grand_total,0);
        
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("Update purchase_order set deleted=1 WHERE id=?");
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
		$stmt = $db->prepare("Update purchase_order set posting='Y' WHERE id=?");
		$stmt->execute(array($_GET['id']));

        $stmt = $db->prepare("Update purchase_orderdet set posting='Y' WHERE id_parent=?");
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
		$stmt = $db->prepare("Update purchase_order set posting='N' WHERE id=?");
		$stmt->execute(array($_GET['id']));

        $stmt = $db->prepare("Update purchase_orderdet set posting='N' WHERE id_parent=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_parent = '".$id."' ";
        $sql_detail= "SELECT pd.* FROM `purchase_orderdet` pd ".$where;
		//var_dump($sql_detail);die;
		//$q = $db->query("SELECT pd.* FROM `olnsodetail` pd ".$where);
		$q = $db->query($sql_detail);
		
		$count = $q->rowCount();
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
		$responce = '';
        foreach($data1 as $line){
		    $responce->rows[$i]['id']   = $line['id_detail'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
                 number_format($line['harga'],0),
                 number_format($line['disc'],0),
                 number_format($line['qty'],2),
                 number_format($line['subtotal'],0),                
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
        	<label for="project_id" class="ui-helper-reset label-control">PO Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_po" name="startdate_po">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_po" name="enddate_po">
				</td>
				<td> Filter
				 <input value="" type="text" id="filter_po" name="filter_po">(Supplier,ID PO,No PO)
				</td>
				
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadPO()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>
	
<table id="table_po"></table>
<div id="pager_table_po"></div>
<div class="btn_box">
<?php
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
		if($allow_add) {
            echo '<button  type="button" class="btn" onclick="window.open(\''.BASE_URL.'pages/purchase/purchase_order_detail.php'.'\',\'table_po\')" href="javascript:;">Tambah</button>';
            
		}
	}
?>
</div>
<script type="text/javascript">
	 
	$('#startdate_po').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_po').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_po" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_po" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadPO(){
		var startdate_po = $("#startdate_po").val();
		var enddate_po = $("#enddate_po").val();
		var filter_po = $("#filter_po").val();
		
		var v_url ='<?php echo BASE_URL?>pages/purchase/purchase_order.php?action=json&startdate_po='+startdate_po+'&enddate_po='+enddate_po +'&filter='+filter_po;
		jQuery("#table_po").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_po").jqGrid({
            url:'<?php echo BASE_URL.'pages/purchase/purchase_order.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','ID PO','NO PO','Supplier','PO.Date','Jatuh Tempo','Total Qty','Subtotal','Total PPN','Total Disc','Grand Total','Edit','Post','Delete'],
            colModel:[
                {name:'id',index:'id', width:20, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'id_po',index:'id_po', align:'right', width:70, searchoptions: {sopt:['cn']}},
                {name:'po_number',index:'po_number', align:'left', width:70, searchoptions: {sopt:['cn']}},
                {name:'supplier',index:'supplier', width:100, searchoptions: {sopt:['cn']}},                
                {name:'po_date',index:'po_date', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'tgljt',index:'tgljt', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'total_qty',index:'total_qty', align:'right', width:50, searchoptions: {sopt:['cn']}},
                {name:'total_price',index:'total_qty', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'total_disc',index:'total_disc', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'total_ppn',index:'total_ppn', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'grand_total',index:'grand_total', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'post',index:'post', align:'center', width:30, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:3000,
            rowList:[1000,2000,3000],
            pager: '#pager_table_po',
            sortname: 'id',
            autowidth: true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Purchase Order",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/purchase/purchase_order.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Code','Barang','Price','Disc','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50,50],
			            		align : ['right','center','left','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_po").jqGrid('navGrid','#pager_table_po',{edit:false,add:false,del:false,search:false});
    })
</script>