<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_jualsm'])?$_GET['startdate_jualsm']:date('Y-m-d');
		$enddate = isset($_GET['enddate_jualsm'])?$_GET['enddate_jualsm']:date('Y-m-d'); 
        $filter=$_GET['filter'];
        
		$page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        $where = "WHERE TRUE and p.state='1' and p.deleted=0 ";
		/*
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		*/
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		
		$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) ".$where;
		
		//$sql = "SELECT p.*,j.nama as dropshipper FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) ".$where;
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
		$grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_disc=0;$grand_value=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
			// $allowInvoice = array(1,2,3);
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
			
			if ($statusToko == 'Tutup') {
			 	$invoice = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Invoice</a>';
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Label</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">BATAL</a>';
            } else {
			// if(in_array($_SESSION['user']['access'], $allowInvoice)){
			$invoice = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_invoice.php?id_trans='.$line['id_trans'].'\',\'table_jualsm\')" href="javascript:;">Invoice</a>';
			// }
			// else
			// $invoice = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Invoice</a>';
			
			
		    // if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_nota.php?id_trans='.$line['id_trans'].'\',\'table_jualsm\')" href="javascript:;">Label</a>';
			// }
			// else
				// $edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Label</a>';
			
			// if(in_array($_SESSION['user']['access'], $allowDelete))
			// 	$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolnso.php?action=delete&id='.$line['id_trans'].'\',\'table_jualsm\')" href="javascript:;">BATAL</a>';
			// else
			// 	$delete = '<a onclick="javascript:custom_alert(\'Tidak Bole dibatalkan\')" href="javascript:;">BATAL</a>';
			}	
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['kode'],                
                $line['dropshipper'],                
                $line['tgl_trans'],
                $line['lastmodified'],
                number_format($line['totalqty'],0),
				number_format($line['faktur'],0),
				number_format($line['discount_faktur'],0),
				number_format($line['exp_fee'],0),
				number_format($line['total'],0),
				number_format($line['tunai'],0),
				number_format($line['transfer'],0),
				number_format($line['piutang'],0),
				number_format($line['payment'],0),
				$line['nama'],
				$line['expedition'],
				$line['exp_code'],
				$invoice,
				$edit,
				//$delete,
            );
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur'];
			$grand_biaya+=$line['exp_fee'];
			$grand_totalfaktur+=$line['total'];
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_disc+=$line['discount_faktur'];
			$grand_value+=$line['total'];
			
            $i++;
        }
		
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['disc'] 			= number_format($grand_disc,0);
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['total'] 			= number_format($grand_value,0);
		$responce['userdata']['totalfaktur'] 	= number_format($grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 		= number_format($grand_biaya,0);
        
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete olndeposit krn void invoice		
		$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		
		//update olnso agar jadi nol krn void invoice
		$stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1,state='0' WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update olnsodetail agar jadi nol krn void invoice
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $sql_detail= "SELECT pd.*,d.disc as discdp,((pd.harga_satuan)*(1-d.disc)) as nett_price,(((pd.harga_satuan)*(1-d.disc))*(pd.jumlah_beli)) as subtotal_nett FROM `olnsodetail` pd inner join olnso p on pd.id_trans=p.id_trans left join mst_dropshipper d on p.id_dropshipper=d.id ".$where;
		//var_dump($sql_detail);die;
		//$q = $db->query("SELECT pd.* FROM `olnsodetail` pd ".$where);
		$q = $db->query($sql_detail);
		
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
                //  number_format($line['harga_satuan'],0),
                //  number_format($line['discdp'],2),
                 number_format($line['nett_price'],0),
                 number_format($line['jumlah_beli'],0),                
                 number_format($line['subtotal_nett'],0),                
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
        	<label for="project_id" class="ui-helper-reset label-control">Post Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_jualsm" name="startdate_jualsm">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jualsm" name="enddate_jualsm">
				</td>
				<td> Filter
				 <input value="" type="text" id="filter_sosum" name="filter_sosum">(Dropshipper,Receiver,Expedition,Exp_Code)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadJual()" class="btn" type="button">Cari</button>
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_online/trolnso_sumrpt.php?action=preview&start='+$('#startdate_jualsm').val()+'&end='+$('#enddate_jualsm').val()+'&filter='+$('#filter_sosum').val())" class="btn" type="button">Print</button>
		    <?php } ?>
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
		var filter_sosum = $("#filter_sosum").val();
		
		var v_url ='<?php echo BASE_URL?>pages/summary_online/trolnso_sum.php?action=json&startdate_jualsm='+startdate_jual+'&enddate_jualsm='+enddate_jual+'&filter='+filter_sosum ;
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
            colNames:['ID','Code','Dropshipper','Oln.Date','Post.Date','Qty','Value','Disc','Exp.Fee','Total Value','Cash','Transfer','Receivables','Payment','Receiver','Expedition','Exp_code','Inv.','Print'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:50, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'kode',index:'kode', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'dropshipper',index:'dropshipper', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'lastmodified',index:'lastmodified', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'discount_faktur',index:'discount_faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'payment',index:'payment', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'expedition',index:'expedition', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'exp_code',index:'exp_code', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'invoice',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                //{name:'delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:3000,
            rowList:[1000,2000,3000],
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
            subGridUrl : '<?php echo BASE_URL.'pages/summary_online/trolnso_sum.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Code','Barang','Price (inc PPN)','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50],
			            		align : ['right','center','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jualsm").jqGrid('navGrid','#pager_table_jualsm',{edit:false,add:false,del:false,search:false});
    })
</script>