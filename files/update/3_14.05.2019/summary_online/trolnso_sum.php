<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_jualsm'])?$_GET['startdate_jualsm']:date('Y-m-d');
		$enddate = isset($_GET['enddate_jualsm'])?$_GET['enddate_jualsm']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
      // $value = $_REQUEST["searchString"];
	  // $where = sprintf(" where (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     // }
        //$where = "WHERE TRUE AND p.deleted = 0 ";
        $where = "WHERE TRUE ";
		
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		
		$sql = "SELECT p.*,j.nama as dropshipper FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
    //    var_dump($sql);
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
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
		    if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_nota.php?id_trans='.$line['id_trans'].'\',\'table_jualsm\')" href="javascript:;">Nota</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolnso.php?action=delete&id='.$line['id_trans'].'\',\'table_jualsm\')" href="javascript:;">BATAL</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bole dibatalkan\')" href="javascript:;">Delete</a>';
				
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['kode'],                
                $line['dropshipper'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['faktur'],0),
				number_format($line['exp_fee'],0),
				number_format($line['total'],0),
				number_format($line['tunai'],0),
				number_format($line['transfer'],0),
				number_format($line['piutang'],0),
				$edit,
				$delete,
            );
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['total'];
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['exp_fee'];
            $i++;
        }
		
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 			= number_format($grand_biaya,0);
        
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//update trjual agar jadi nol krn void invoice
		$stmt = $db->prepare("Update trjual set totalfaktur=0,biaya=0,faktur=0,totalqty=0,tunai=0,transfer=0,kartu=0,deposit=0,piutang=0,pelunasan=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//update trjual_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update trjual_detail set qty=0,harga=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		/*
		$stmt = $db->prepare("delete from trjual_detail WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		*/
		//update trjual_print agar jadi nol krn void invoice
		$stmt = $db->prepare("Update trjual_print set kuantum=0,harga=0,harga_plus_ppn=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		/*
		$stmt = $db->prepare("delete from trjual_print WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		*/
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
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `olnsodetail` pd ".$where);
		
		$count = $q->rowCount();
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_so_d'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
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
				 <input value="" type="text" class="required datepicker"   id="startdate_jualsm" name="startdate_jualsm">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jualsm" name="enddate_jualsm">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadJual()" class="btn" type="button">Cari</button>
            	
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_online/trolnso_sumrpt.php?action=preview&start='+$('#startdate_jualsm').val()+'&end='+$('#enddate_jualsm').val())" class="btn" type="button">Print</button>
		    
            </div>
       	</form>
   	</div>
</div>
	
<table id="table_jualsm"></table>
<div id="pager_table_jualsm"></div>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Tambah</button></a>   
</br>

<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';		
	}	
	*/
?>
-->
</div>
<script type="text/javascript">
	 
	$('#startdate_jualsm').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jualsm').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_jualsm" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_jualsm" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJual(){
		var startdate_jual = $("#startdate_jualsm").val();
		var enddate_jual = $("#enddate_jualsm").val();
		var v_url ='<?php echo BASE_URL?>pages/summary_online/trolnso_sum.php?action=json&startdate_jualsm='+startdate_jual+'&enddate_jualsm='+enddate_jual ;
		jQuery("#table_jualsm").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_jualsm").jqGrid({
            url:'<?php echo BASE_URL.'pages/summary_online/trolnso_sum.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Code','Dropshipper','Date','Qty','Value','Exp.Fee','Total Value','Cash','Transfer','Receivables','Print','Cancel'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:50, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'kode',index:'kode', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'dropshipper',index:'dropshipper', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_jualsm',
            sortname: 'id_trans',
            autowidth: true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penjualan Online",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/trolnso.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50],
			            		align : ['right','center','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jualsm").jqGrid('navGrid','#pager_table_jualsm',{edit:false,add:false,del:false,search:false});
    })
</script>