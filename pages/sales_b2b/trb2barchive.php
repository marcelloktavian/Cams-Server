<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
// $allow_delete = is_show_menu(DELETE_POLICY, DeliveryOrderB2B, $group_acess);


	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_b2barc'])?$_GET['startdate_b2barc']:date('Y-m-d');
		$enddate = isset($_GET['enddate_b2barc'])?$_GET['enddate_b2barc']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND p.deleted=0 ";
		
		if(($startdate != null) && ($filter != null)) {
			if($filter == 'PENDING'){
				$where .= " AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang > 0) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'COFIRM'){
				$where .= " AND p.state='1' AND (p.totalqty <> 0) AND (p.piutang > 0) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'DONE'){
				$where .= " AND p.state='1' AND (p.totalqty <> 0) AND (p.piutang > 0) AND (p.totalqty = p.totalkirim) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'DONE'){
				$where .= " AND p.state='1' AND (p.totalqty <> 0) AND (p.piutang > 0) AND (p.totalqty = p.totalkirim) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'SDC'){
				$where .= " AND k.id=2 AND (p.totalqty <> 0) AND (p.piutang > 0) AND (p.totalqty = p.totalkirim) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'SOL'){
				$where .= " AND k.id=1 AND (p.totalqty <> 0) AND (p.piutang > 0) AND (p.totalqty = p.totalkirim) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else if($filter == 'SDL'){
				$where .= " AND k.id=3 AND (p.totalqty <> 0) AND (p.piutang > 0) AND (p.totalqty = p.totalkirim) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
			}else{
				$where .= " AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((j.nama like '%$filter%') or (s.nama like '%$filter%'))";
			}
		}	
		else
		{
		$where .=" AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = "SELECT p.*,k.nama as kategori,j.nama as customer,s.nama as salesman FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) ".$where;
        // var_dump($sql);
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
        	if ($statusToko == 'Tutup') {
            $detail = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Detail</a>';
	        }else{
	            $detail = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed_detail.php?ids='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Detail</a>';
	        }
                $responce['rows'][$i]['id']   = $line['id_trans'];
                $responce['rows'][$i]['cell'] = array(
                    $line['id_trans'],
                    $line['ref_kode'],                
                    $line['customer'],                
                    $line['tgl_trans'],
                    $line['salesman'],
                    $line['alamat'],
                    $line['kategori'],
                    number_format($line['totalqty'],0),
                    number_format($line['totalkirim'],0),
                    $detail,
				);
				$i++;
			}
			echo json_encode($responce);
			exit;
	}
		elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {

			$id = $_GET['id'];

			$where = "WHERE do.id_transb2bso = '".$id."' AND totalkirim <> 0 AND totalfaktur <> 0 ";
			$q = $db->query("SELECT do.*,date_format(do.tgl_trans,'%d-%m-%Y') as tanggal,e.nama as expedition FROM `b2bdo` do left join mst_b2bexpedition e on do.id_expedition=e.id ".$where. " order by id desc");
		//var_dump($q); die;
			$count = $q->rowCount();
			$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

			$i=0;
			$responce = '';
			foreach($data1 as $line){
				$responce->rows[$i]['id']   = $line['id_trans'];
                $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_trans'],
                $line['tanggal'],
                $line['expedition'],
                number_format($line['totalkirim'],0),
                number_format($line['faktur'],0),                
                number_format($line['exp_fee'],0),                
                number_format($line['totalfaktur'],0),                
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
				 <input value="" type="text" class="required datepicker"   id="startdate_b2barc" name="startdate_b2barc">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_b2barc" name="enddate_b2barc">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterb2barc" name="filterb2barc">(Customer,Salesman,PENDING,COFIRM,DONE,Category)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadB2BARC()" class="btn" type="button">Cari</button>
				
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
 
<table id="table_b2barchive"></table>
<div id="pager_table_b2barchive"></div>

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
	 
	$('#startdate_b2barc').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_b2barc').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_b2barc" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_b2barc" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadB2BARC(){
		var startdate_b2barc = $("#startdate_b2barc").val();
		var enddate_b2barc = $("#enddate_b2barc").val();
		var filterb2barc = $("#filterb2barc").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_b2b/trb2barchive.php?action=json&startdate_b2barc='+startdate_b2barc+'&enddate_b2barc='+enddate_b2barc+'&filter='+filterb2barc;
		jQuery("#table_b2barchive").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	

    $(document).ready(function(){
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_b2barchive").jqGrid({
         		url:'<?php echo BASE_URL.'pages/sales_b2b/trb2barchive.php?action=json'; ?>',
				datatype: "json",
				colNames:['ID','Code','Customer','Date','Salesman','Address','Category','Qty','Sent','Detail'],
                colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'ref_kode',index:'ref_kode', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'customer',index:'customer', width:40, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:30, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'salesman',index:'salesman', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'alamat',index:'alamat', align:'left', width:100, searchoptions: {sopt:['cn']}},
                // {name:'exp_fee',index:'exp_fee', align:'right', width:20, searchoptions: {sopt:['cn']}},
                // {name:'expedition',index:'expedition', align:'left', width:35, searchoptions: {sopt:['cn']}},
                {name:'kategori',index:'kategori', align:'left', width:35, searchoptions: {sopt:['cn']}},
                {name:'totalqty',index:'totalqty', align:'right', width:20, searchoptions: {sopt:['cn']}},
                {name:'pelunasan',index:'pelunasan', align:'right', width:20, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_b2barchive',
            sortname: 'id_trans',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"B2B Order Archive",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2barchive.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Tanggal','Expedition','Qty Kirim','Faktur','Exp.Fee','Totalfaktur'], 
			                    width : [40,80,70,100,50,80,80,80],
			                    align : ['right','center','center','center','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_b2barchive").jqGrid('navGrid','#pager_table_b2barchive',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>