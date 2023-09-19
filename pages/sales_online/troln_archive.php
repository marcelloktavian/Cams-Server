<?php require_once '../../include/config.php';
include "../../include/koneksi.php";?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, ArchiveOrder, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, ArchiveOrder, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_orderretur'])?$_GET['startdate_orderretur']:date('Y-m-d');
		$enddate = isset($_GET['enddate_orderretur'])?$_GET['enddate_orderretur']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        //p.state = '2' artinya belum packing";
        $where = "WHERE TRUE AND p.state >= '1'";
		//filter _tanggalnya berdasarkan tanggal kirim lastmodified
		if(($startdate != null) && ($filter != null)) {
			$statenya = '';
			if ($filter=='packed'||$filter=='PACKED') {
				$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (p.stkirim='1')";
			} else if ($filter=='unpacked'||$filter=='UNPACKED') {
				$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (p.stkirim='0')";
			} else {
				$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((j.nama like '%$filter%') or (p.id_trans like '%$filter%') or (p.ref_kode like '%$filter%'))";
			}
			
				
		}	
		else
		{
		$where .=" AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		//((p.ref_code like '%$filter%') or (j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%'))
		$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition,i.id_ship as id_kirim, date(p.lastmodified) as tanggal FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) Left Join `olnso_id` i on (p.id_trans=i.id_trans) ".$where;
        //var_dump($sql);die;
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
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">POSTED</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">SENT</a>';
                $return = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">RETURN</a>';
		} else {
		if ($line['state']=='0')
		{
			//posting
			if ($allow_post) {
				$edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_archive.php?action=posting&id='.$line['id_trans'].'\',\'table_jualarchive\')" href="javascript:;">Posting</a>';
			} else {
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Bisa Posting\')" href="javascript:;">POSTED</a>';
			}

			//delete
			if ($allow_delete) {
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_archive.php?action=delete&id='.$line['id_trans'].'\',\'table_jualarchive\')" href="javascript:;">Cancel</a>';
			} else {
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bisa Dibatalkan \')" href="javascript:;">SENT</a>';
			}

			//return
			$return = '<a onclick="javascript:custom_alert(\'Tidak Bisa DiRETUR \')" href="javascript:;">Unreturnable</a>';
			/*}
				else if ($line['state']=='"0"'){
				$edit='Posted';
				$delete='Sent';
				*/
			} 
			else
			{
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Bisa Posting\')" href="javascript:;">POSTED</a>';
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bisa Dibatalkan \')" href="javascript:;">SENT</a>';
				//return
				if ($allow_post) {
					$return = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnreturn_detail_edit.php?ids='.$line['id'].'\',\'table_jualarchive\')" href="javascript:;">RETURN</a>';
				} else {
					$return = '<a onclick="javascript:custom_alert(\'Tidak Bisa DiRETUR \')" href="javascript:;">Unreturnable</a>';
				}
				
				
			}
		}

        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['ref_kode'],                
                $line['dropshipper'],                
                $line['lastmodified'],
                number_format($line['totalqty'],0),
                number_format($line['faktur'],0),
                number_format($line['exp_fee'],0),
                number_format($line['total'],0),
                number_format($line['tunai'],0),
                number_format($line['transfer'],0),
                number_format($line['piutang'],0),
				$return,
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'unpack') {
		$stmt = $db->prepare("UPDATE olnso SET stkirim=0 WHERE id_trans=?");
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
		//$id = $line['id_trans'];
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.*,d.disc as discdp,((pd.harga_satuan)*(1-d.disc)) as nett_price,(((pd.harga_satuan)*(1-d.disc))*(pd.jumlah_beli)) as subtotal_nett FROM `olnsodetail` pd inner join olnso p on pd.id_trans=p.id_trans left join mst_dropshipper d on p.id_dropshipper=d.id ".$where);
		
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
                $line['size'],
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
        	<label for="project_id" class="ui-helper-reset label-control">Posting Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_orderretur" name="startdate_orderretur">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_orderretur" name="enddate_orderretur">
				</td>
				<td> Filter
				 <input value="" type="text" id="filter_orderretur" name="filter_orderretur">(Dropshipper,ID OLN,ID WEB)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadOrderRetur()" class="btn" type="button">Cari</button>
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
 <!-- <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span> -->
</div>
 
<table id="table_orderretur"></table>
<div id="pager_table_orderretur"></div>

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
	 
	$('#startdate_orderretur').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_orderretur').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_orderretur" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_orderretur" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadOrderRetur(){
		var startdate_orderretur = $("#startdate_orderretur").val();
		var enddate_orderretur = $("#enddate_orderretur").val();
		var filter_orderretur = $("#filter_orderretur").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_online/troln_archive.php?action=json&startdate_orderretur='+startdate_orderretur+'&enddate_orderretur='+enddate_orderretur+'&filter='+filter_orderretur;
		jQuery("#table_orderretur").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
    $(document).ready(function(){
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_orderretur").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/troln_archive.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID_oln','ID_web','Dropshipper','Post.Date','Qty','Faktur','Exp Fee','Total Faktur','Tunai','Transfer','Piutang','Return'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'ref_kode',index:'ref_kode', align:'right', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'j.nama',index:'j.nama', width:60, searchoptions: {sopt:['cn']}},                
                {name:'lastmodified',index:'lastmodified', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:30, searchoptions: {sopt:['cn']}},
				{name:'exp_fee',index:'exp_fee', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'return',index:'return', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_orderretur',
            sortname: 'id_trans',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Order Retur",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/troln_archive.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga (inc PPN)','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_orderretur").jqGrid('navGrid','#pager_table_orderretur',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>