<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_b2bcomp'])?$_GET['startdate_b2bcomp']:date('Y-m-d');
		$enddate = isset($_GET['enddate_b2bcomp'])?$_GET['enddate_b2bcomp']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = " WHERE TRUE AND b.state = '1'";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(b.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((bc.nama like '%$filter%') or (bd.namabrg like '%$filter%') or (c.nama like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(b.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = "SELECT bd.id_trans,bc.nama AS customer,bs.nama AS salesman,b.tgl_trans,bd.id_product,bd.namabrg,bd.size,bd.jumlah_beli,bd.harga_satuan,bpd.composition_id,c.id,c.nama AS composition,bpd.qty,c.cost FROM b2bso_detail bd 
		INNER JOIN mst_b2bproducts_detail bpd ON bd.id_product=bpd.products_id 
		LEFT JOIN mst_composition c ON bpd.composition_id=c.id 
		LEFT JOIN b2bso b ON bd.id_trans=b.id_trans
		LEFT JOIN mst_b2bcustomer bc ON b.id_customer=bc.id 
		LEFT JOIN mst_b2bsalesman bs ON b.id_salesman=bs.id".$where;
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
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		$grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
			$allowInvoice = array(1,2,3);
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
		    if(in_array($_SESSION['user']['access'], $allowInvoice)){
			$invoice = '<a onclick="javascript:custom_alert(\'Under Construction\')" href="javascript:;">Invoice</a>';
			//$invoice = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_invoice.php?id_trans='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">Invoice</a>';
			}
			else
				$invoice = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="javascript:custom_alert(\'Under Construction\')" href="javascript:;">Label</a>';
			//$edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_nota.php?id_trans='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">Label</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Invoice\')" href="javascript:;">Edit</a>';
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2bso_comp.php?action=delete&id='.$line['id_trans'].'\',\'table_b2bso_comp\')" href="javascript:;">UnPost</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">UnPost</a>';
			
			    $select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['tgl_trans'],                
                $line['composition'],                
                number_format($line['qty'],0),
                $line['namabrg'],
                $line['size'],
                number_format($line['jumlah_beli'],0),
                $line['customer'],
                $line['salesman'],
                //$invoice,
				//$edit,
				//$delete,
			);
			/*
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['total'];
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['exp_fee']; 
			*/
			
		   $i++;
        }
		/*
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//update trolnso agar state jadi nol dan dikembalikan ke sales_order
		$stmt = $db->prepare("update b2bso set state='0' WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		
		//update olnso agar jadi nol krn void invoice
		//$stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0 WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update trjual_detail agar jadi nol krn void invoice
		//$stmt = $db->prepare("update olnsodetail set jumlah_beli=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//delete olndeposit krn void invoice
		
		//$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		
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
        $q = $db->query("SELECT pd.* FROM `b2bso_detail` pd ".$where);
		
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['b2bso_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
                $line['size'],
                 number_format($line['harga_satuan'],0),
                 number_format($line['jumlah_beli'],0),                
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
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_b2bcomp" name="startdate_b2bcomp">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_b2bcomp" name="enddate_b2bcomp">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterb2bcomp" name="filterb2bcomp">(Customer,Product,Composition)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadB2Bcomp()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>
	<div class="btn_box">
	<!--
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Add</button></a>   
   -->
 <!-- <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span>
<button id="btn-xlsdo"  class="btn btn-success">XLS Selected</button>
<button id="btn-print"  class="btn btn-success">Print Selected Label</button>
 -->
</div>
 
<table id="table_b2bso_comp"></table>
<div id="pager_table_b2bso_comp"></div>

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
	 
	$('#startdate_b2bcomp').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_b2bcomp').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_b2bcomp" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_b2bcomp" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadB2Bcomp(){
		var startdate_b2bcomp = $("#startdate_b2bcomp").val();
		var enddate_b2bcomp = $("#enddate_b2bcomp").val();
		var filter_b2bcomp = $("#filterb2bcomp").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_b2b/trb2bso_composition.php?action=json&startdate_b2bcomp='+startdate_b2bcomp+'&enddate_b2bcomp='+enddate_b2bcomp+'&filter='+filter_b2bcomp;
		jQuery("#table_b2bso_comp").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
            var grid = $("#table_jualdo");
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
		$("#table_b2bso_comp").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_b2b/trb2bso_composition.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Date','Composition','Qty Comp','Products','Size','Qty Product','Customer','Salesman'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:30, search:true, stype:'text', align:'center', searchoptions:{sopt:['cn']}},
                {name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'c.nama',index:'c.nama', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'qty',index:'qty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'namabrg',index:'namabrg', width:60, searchoptions: {sopt:['cn']}},                
                {name:'size',index:'size', align:'left', width:20, searchoptions: {sopt:['cn']}},
                {name:'jumlah_beli',index:'jumlah_beli', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'bc.nama',index:'bc.nama', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'bs.nama',index:'bs.nama', align:'left', width:50, searchoptions: {sopt:['cn']}},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_b2bso_comp',
            sortname: 'id_trans',
            autowidth: true,
			//multiselect:true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"COMPOSITION PRODUCTS",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            //subGrid : true,
            //subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2bdo.php?action=json_sub'; ?>',
            /*subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],*/
						
            
        });
        $("#table_b2bso_comp").jqGrid('navGrid','#pager_table_b2bso_comp',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>