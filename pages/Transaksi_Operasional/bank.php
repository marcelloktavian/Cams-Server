<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_bank'])?$_GET['startdate_bank']:date('Y-m-d');
		$enddate = isset($_GET['enddate_bank'])?$_GET['enddate_bank']:date('Y-m-d'); 
        $filter=$_GET['filter'];
        
		$page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tanggal_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        $where = "WHERE TRUE  and p.deleted=0 ";
		/*
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		*/
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (dr.nama like '%$filter%' OR p.id_trans like '%$filter%') ";	
		}	
		else
		{
		$where .=" AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		
		$sql = "SELECT p.*, dr.nama as dropshipper FROM `acc_bank` p left join olnso so ON so.id_trans=p.id_trans left join mst_dropshipper dr on dr.id = so.id_dropshipper  ".$where;
		
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
		$grand_total = 0;
        foreach($data1 as $line) {
        	
			$allowInvoice = array(1,2,3);
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			
			if ($statusToko == 'Tutup') {
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Unpost</a>';
            } else {
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/bank.php?action=delete&id='.$line['id'].'\',\'table_bank\')" href="javascript:;">Unpost</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Unpost</a>';
			}	
        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['periode'],                
                $line['id_trans'],                
                $line['dropshipper'],                
                $line['lastmodified'],                
                $line['tanggal_trans'],
                $line['keterangan'],
                $line['cabang'],
                number_format($line['jumlah'],0),
				$delete,
            );
			$grand_total+=$line['jumlah'];
			
            $i++;
        }
		
		$responce['userdata']['total'] 		= number_format($grand_total,0);
        
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("INSERT INTO `acc_prebank`(`norek`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, `jumlah`, `saldoawal`, `mutasikredit`) SELECT `norek`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, `jumlah`, `saldoawal`, `mutasikredit` FROM `acc_bank` WHERE `id`=? ");
		$stmt->execute(array($_GET['id']));

		$stmt = $db->prepare("UPDATE olnso SET stbank=0 WHERE id_trans=(SELECT id_trans FROM acc_bank WHERE id=?)");
		$stmt->execute(array($_GET['id']));

		$stmt = $db->prepare("update acc_bank set deleted=1 WHERE id=?");
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
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $sql_detail= "SELECT pd.*,d.disc as discdp,((pd.harga_satuan-pd.disc)*(1-d.disc)) as nett_price,((pd.harga_satuan-pd.disc)*(1-d.disc)*(pd.jumlah_beli)) as subtotal_nett FROM `olnsodetail` pd inner join olnso p on pd.id_trans=p.id_trans left join mst_dropshipper d on p.id_dropshipper=d.id ".$where;
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
                 number_format($line['harga_satuan'],0),
                 number_format($line['disc'],0),
                 number_format($line['discdp'],2),
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
				 <input value="" type="text" class="required datepicker"   id="startdate_bank" name="startdate_bank">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_bank" name="enddate_bank">
				</td>
                <td> Filter
				 <input value="" type="text" id="filter_bank" name="filter_bank">(Dropshipper, ID OLN)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadBank()" class="btn" type="button">Cari</button>
</div>
       	</form>
   	</div>
</div>
	
<table id="table_bank"></table>
<div id="pager_table_bank"></div>
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
	 
	$('#startdate_bank').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_bank').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_bank" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_bank" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadBank(){
		var startdate_jual = $("#startdate_bank").val();
		var enddate_jual = $("#enddate_bank").val();
		var filter_bank = $("#filter_bank").val();
		
		var v_url ='<?php echo BASE_URL?>pages/Transaksi_Operasional/bank.php?action=json&startdate_bank='+startdate_jual+'&enddate_bank='+enddate_jual+'&filter='+filter_bank ;
		jQuery("#table_bank").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_bank").jqGrid({
            url:'<?php echo BASE_URL.'pages/Transaksi_Operasional/bank.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Periode','ID Trans','Dropshipper','Post Date','Trans Date','Keterangan','Cabang','Total','Cancel'],
            colModel:[
                {name:'id',index:'id', width:10, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'periode',index:'periode', width:50, search:false, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'id_trans',index:'id_trans', width:35, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'dropshipper',index:'dropshipper', align:'left', width:100, searchoptions: {sopt:['cn']}},
                {name:'postdate',index:'postdate', width:40, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'tanggal_trans',index:'tanggal_trans', width:35, search:false, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'keterangan',index:'keterangan', align:'left', width:150, searchoptions: {sopt:['cn']}},
                {name:'cabang',index:'cabang', width:35, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'total',index:'total', align:'right', width:50, searchoptions: {sopt:['cn']}},
                {name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
            ],
            rowNum:3000,
            rowList:[1000,2000,3000],
            pager: '#pager_table_bank',
            sortname: 'id',
            autowidth: true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Bank",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : false,
            subGridUrl : '<?php echo BASE_URL.'pages/summary_online/trolnso_sum.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Code','Barang','Price List','Disc Item','Disc Dropship','Nett Price','Qty(pcs)','Subtotal Nett'], 
			            		width : [40,40,300,50,50,50,50,50,50],
			            		align : ['right','center','left','right','right','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_bank").jqGrid('navGrid','#pager_table_bank',{edit:false,add:false,del:false,search:false});
    })
</script>