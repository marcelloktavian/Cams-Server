<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_b2bdo_idx'])?$_GET['startdate_b2bdo_idx']:date('Y-m-d');
		$enddate = isset($_GET['enddate_b2bdo_idx'])?$_GET['enddate_b2bdo_idx']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND do.deleted = '0'";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((c.nama like '%$filter%') or (e.nama like '%$filter%') or (s.nama like '%$filter%') or (do.exp_code like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = "SELECT do.*,so.tgl_trans as tgl_order,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman FROM `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id)".$where;
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
			//$invoice = '<a onclick="javascript:custom_alert(\'Under Construction\')" href="javascript:;">Invoice</a>';
			$invoice = '<a onclick="window.open(\''.BASE_URL.'pages/summary_b2b/trb2b_invoice.php?id_trans='.$line['id_trans'].'\',\'table_b2bdo_idx\')" href="javascript:;">Invoice</a>';
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
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/summary_b2b/trb2bdo_idx.php?action=delete&id='.$line['id_trans'].'\',\'table_b2bdo_idx\')" href="javascript:;">Cancel</a>';
			
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';
			
			    $select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['customer'],                
                $line['salesman'],                
                $line['tgl_order'],
                $line['tgl_trans'],
                $line['nama'],
                $line['alamat'],
                $line['expedition'],
                $line['exp_code'],
                number_format($line['totalkirim'],0),
				number_format($line['exp_fee'],0),
				number_format($line['faktur'],0),
				number_format($line['totalfaktur'],0),
                //$invoice,
				//$delete,
			);
			
			$grand_qty+=$line['totalkirim'];
			$grand_biaya+=$line['exp_fee'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['totalfaktur'];
			/*
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			*/
            $i++;
        }
		
		$responce['userdata']['totalkirim']		= number_format($grand_qty,0);
		$responce['userdata']['exp_fee'] 		= number_format($grand_biaya,0);
        $responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format($grand_totalfaktur,0);
		/*
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		*/
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//update trolnso agar state jadi nol dan dikembalikan ke sales_order
		//$stmt = $db->prepare("update b2bso set state='0' WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		
		//update b2bdo agar jadi nol krn void invoice
		$stmt = $db->prepare("Update b2bdo set tunai=0,transfer=0,giro=0,faktur=0,totalfaktur=0,piutang=0,totalkirim=0,exp_fee=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update b2bdo_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update b2bdo_detail set jumlah_beli=0,jumlah_kirim=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		
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
        $q = $db->query("SELECT pd.* FROM `b2bdo_detail` pd ".$where);
		
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['b2bdo_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
                $line['size'],
                 number_format($line['harga_satuan'],0),
                 number_format($line['jumlah_kirim'],0),                
                 number_format(($line['harga_satuan']*$line['jumlah_kirim']),0),                
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
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal Kirim</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_b2bdo_idx" name="startdate_b2bdo_idx">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_b2bdo_idx" name="enddate_b2bdo_idx">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterb2bdo_idx" name="filterb2bdo_idx">(Customer,Salesman,Expedition,Exp_Code)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadB2BDO_idx()" class="btn" type="button">Cari</button>
            	<?php
            	$statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
    	        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Print</button>';
    }else{
            	?>
				<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_b2b/trb2bdo_rpt.php?action=preview&start='+$('#startdate_b2bdo_idx').val()+'&end='+$('#enddate_b2bdo_idx').val()+'&filter='+$('#filterb2bdo_idx').val())" class="btn" type="button">Print</button>
		    <?php } ?>
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
 
<table id="table_b2bdo_idx"></table>
<div id="pager_table_b2bdo_idx"></div>

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
	 
	$('#startdate_b2bdo_idx').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_b2bdo_idx').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_b2bdo_idx" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_b2bdo_idx" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadB2BDO_idx(){
		var startdate_b2bdo_idx = $("#startdate_b2bdo_idx").val();
		var enddate_b2bdo_idx = $("#enddate_b2bdo_idx").val();
		var filterb2bdo_idx = $("#filterb2bdo_idx").val();
		var v_url ='<?php echo BASE_URL?>pages/summary_b2b/trb2bdo_idx.php?action=json&startdate_b2bdo_idx='+startdate_b2bdo_idx+'&enddate_b2bdo_idx='+enddate_b2bdo_idx+'&filter='+filterb2bdo_idx;
		jQuery("#table_b2bdo_idx").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_b2bdo_idx").jqGrid({
            url:'<?php echo BASE_URL.'pages/summary_b2b/trb2bdo_idx.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Customer','Salesman','SO.Date','DO.Date','Contact','Address','Expedition','Exp.Code','Qty','Exp_fee','Faktur','TotalFaktur',/*'Invoice','Cancel'*/],
            colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'customer',index:'customer', align:'left', width:60, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'salesman',index:'salesman', width:60, searchoptions: {sopt:['cn']}},                
                {name:'so.tgl_trans',index:'so.tgl_trans', width:40, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'tgl_trans',index:'tgl_trans', width:40, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'nama',index:'nama', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'alamat',index:'alamat', align:'left', width:80, searchoptions: {sopt:['cn']}},
                {name:'expedition',index:'expedition', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'exp_code',index:'exp_code', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'totalkirim',index:'totalkirim', align:'right', width:18, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:50, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:55, searchoptions: {sopt:['cn']}},
                /*
				{name:'invoice',index:'invoice', align:'center', width:25, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
				*/
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_b2bdo_idx',
            sortname: 'id_trans',
            autowidth: true,
			multiselect:true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data B2B Siap Kirim",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/summary_b2b/trb2bdo_idx.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_b2bdo_idx").jqGrid('navGrid','#pager_table_b2bdo_idx',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>