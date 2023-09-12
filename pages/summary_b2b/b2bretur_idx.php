<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_rptb2breturn'])?$_GET['startdate_rptb2breturn']:date('Y-m-d');
		$enddate = isset($_GET['enddate_rptb2breturn'])?$_GET['enddate_rptb2breturn']:date('Y-m-d'); 
        $filter=$_GET['filter'];
        
		$page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        $where = "WHERE TRUE and p.post='1' and p.deleted=0 ";
		/*
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		*/
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((p.b2breturn_num like '%$filter%') or (c.nama like '%$filter%') or (k.nama like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		
		$sql = "SELECT p.*,c.nama as customer,k.nama as kategori FROM `b2breturn` p Left Join `mst_b2bcustomer` c on (c.id=p.b2bcust_id) Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) ".$where;
		
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
		$grand_qty=0;$grand_total=0;
        foreach($data1 as $line) {
        	
        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['b2breturn_num'],
                $line['customer'],                
                $line['tgl_return'],
                $line['kategori'],
                number_format($line['qty'],0),
				number_format($line['total'],0),
            );
			$grand_qty+=$line['qty'];
			$grand_total+=$line['total'];
			
            $i++;
        }
		
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['total'] 			= number_format($grand_total,0);
        
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_parent = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `b2breturn_detail` pd ".$where);
    
        $count = $q->rowCount();
        
        $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        
        $i=0;
        $responce = '';
        $barangnya='';
        foreach($data1 as $line){
            $sizenew='';
            $count=1;
            
            if ($barangnya != $line['id_product']) {
            $count=1;
            }

            if ($line['qty31'] != '0') {
            if ($count == 1) {
                $sizenew = '31'.'('.$line['qty31'].')';
            }else{					
                $sizenew = $sizenew.', 31('.$line['qty31'].')';
            }
            $count++;
            }

            if ($line['qty32'] != '0') {
            if ($count == 1) {
                $sizenew = '32'.'('.$line['qty32'].')';
            }else{
                $sizenew = $sizenew.', 32('.$line['qty32'].')';
            }
            $count++;
            }

            if ($line['qty33'] != '0') {
            if ($count == 1) {
                $sizenew = '33'.'('.$line['qty33'].')';
            }else{
                $sizenew = $sizenew.', 33('.$line['qty33'].')';
            }
            $count++;
            }

            if ($line['qty34'] != '0') {
            if ($count == 1) {
                $sizenew = '34'.'('.$line['qty34'].')';
            }else{
                $sizenew = $sizenew.', 34('.$line['qty34'].')';
            }
            $count++;
            }

            if ($line['qty35'] != '0') {
            if ($count == 1) {
                $sizenew = '35'.'('.$line['qty35'].')';
            }else{
                $sizenew = $sizenew.', 35('.$line['qty35'].')';
            }
            $count++;
            }

            if ($line['qty36'] != '0') {
            if ($count == 1) {
                $sizenew = '36'.'('.$line['qty36'].')';
            }else{
                $sizenew = $sizenew.', 36('.$line['qty36'].')';
            }
            $count++;
            }

            if ($line['qty37'] != '0') {
            if ($count == 1) {
                $sizenew = '37'.'('.$line['qty37'].')';
            }else{
                $sizenew = $sizenew.', 37('.$line['qty37'].')';
            }
            $count++;
            }

            if ($line['qty38'] != '0') {
            if ($count == 1) {
                $sizenew = '38'.'('.$line['qty38'].')';
            }else{
                $sizenew = $sizenew.', 38('.$line['qty38'].')';
            }
            $count++;
            }

            if ($line['qty39'] != '0') {
            if ($count == 1) {
                $sizenew = '39'.'('.$line['qty39'].')';
            }else{
                $sizenew = $sizenew.', 39('.$line['qty39'].')';
            }
            $count++;
            }

            if ($line['qty40'] != '0') {
            if ($count == 1) {
                $sizenew = '40'.'('.$line['qty40'].')';
            }else{
                $sizenew = $sizenew.', 40('.$line['qty40'].')';
            }
            $count++;
            }

            if ($line['qty41'] != '0') {
            if ($count == 1) {
                $sizenew = '41'.'('.$line['qty41'].')';
            }else{
                $sizenew = $sizenew.', 41('.$line['qty41'].')';
            }
            $count++;
            }

            if ($line['qty42'] != '0') {
            if ($count == 1) {
                $sizenew = '42'.'('.$line['qty42'].')';
            }else{
                $sizenew = $sizenew.', 42('.$line['qty42'].')';
            }
            $count++;
            }

            if ($line['qty43'] != '0') {
            if ($count == 1) {
                $sizenew = '43'.'('.$line['qty43'].')';
            }else{
                $sizenew = $sizenew.', 43('.$line['qty43'].')';
            }
            $count++;
            }

            if ($line['qty44'] != '0') {
            if ($count == 1) {
                $sizenew = '44'.'('.$line['qty44'].')';
            }else{
                $sizenew = $sizenew.', 44('.$line['qty44'].')';
            }
            $count++;
            }

            if ($line['qty45'] != '0') {
            if ($count == 1) {
                $sizenew = '45'.'('.$line['qty45'].')';
            }else{
                $sizenew = $sizenew.', 45('.$line['qty45'].')';
            }
            $count++;
            }

            if ($line['qty46'] != '0') {
            if ($count == 1) {
                $sizenew = '46'.'('.$line['qty46'].')';
            }else{
                $sizenew = $sizenew.', 46('.$line['qty46'].')';
            }
            $count++;
            }

            $totalqty = $line['qty31'] + $line['qty32'] + $line['qty33'] + $line['qty34'] + $line['qty35'] + $line['qty36'] + $line['qty37'] + $line['qty38'] + $line['qty39'] + $line['qty40'] + $line['qty41'] + $line['qty42'] + $line['qty43'] + $line['qty44'] + $line['qty45'] + $line['qty46'];

            $responce->rows[$i]['id']   = $line['id_parent'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['b2bdo_num'],
                $line['namabrg'],
                $sizenew,
                number_format($line['harga_satuan'],0),
                number_format($totalqty,0),                
                number_format(($line['harga_satuan']*$totalqty),0),                
            );
            $barangnya = $line['id_product'];
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
        	<label for="project_id" class="ui-helper-reset label-control">Return Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_rptb2breturn" name="startdate_rptb2breturn">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_rptb2breturn" name="enddate_rptb2breturn">
				</td>
				<td> Filter
				 <input value="" type="text" id="filter_rptb2breturn" name="filter_rptb2breturn">(B2B Return Num, Customer, Category)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadB2BReturn()" class="btn" type="button">Cari</button>
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_b2b/b2bretur_rpt.php?action=preview&start='+$('#startdate_rptb2breturn').val()+'&end='+$('#enddate_rptb2breturn').val()+'&filter='+$('#filter_rptb2breturn').val())" class="btn" type="button">Print</button>
		    <?php } ?>
            </div>
       	</form>
   	</div>
</div>
	
<table id="table_rptb2breturn"></table>
<div id="pager_table_rptb2breturn"></div>
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
	 
	$('#startdate_rptb2breturn').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_rptb2breturn').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_rptb2breturn" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_rptb2breturn" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadB2BReturn(){
		var startdate = $("#startdate_rptb2breturn").val();
		var enddate = $("#enddate_rptb2breturn").val();
		var filter = $("#filter_rptb2breturn").val();
		
		var v_url ='<?php echo BASE_URL?>pages/summary_b2b/b2bretur_idx.php?action=json&startdate_rptb2breturn='+startdate+'&enddate_rptb2breturn='+enddate+'&filter='+filter ;
		jQuery("#table_rptb2breturn").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_rptb2breturn").jqGrid({
            url:'<?php echo BASE_URL.'pages/summary_b2b/b2bretur_idx.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['Code','Customer','Date','Category','Qty','Total'],
            colModel:[
                {name:'b2breturn_num',index:'b2breturn_num', width:50, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'customer',index:'c.nama', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_return',index:'tgl_return', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'kategori',index:'k.nama', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'totalqty',index:'qty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:80, searchoptions: {sopt:['cn']}},
            ],
            rowNum:3000,
            rowList:[1000,2000,3000],
            pager: '#pager_table_rptb2breturn',
            sortname: 'b2breturn_num',
            autowidth: true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"B2B Laporan Retur",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/summary_b2b/b2bretur_idx.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','B2BDO Num','Barang','Size','Harga (inc PPN)','Qty(pcs)','Subtotal'], 
                                width : [40,100,300,300,50,50,50,50],
                                align : ['right','center','left','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_rptb2breturn").jqGrid('navGrid','#pager_table_rptb2breturn',{edit:false,add:false,del:false,search:false});
    })
</script>