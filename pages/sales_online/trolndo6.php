<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, OnlineDelivery, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, OnlineDelivery, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_jualdo'])?$_GET['startdate_jualdo']:date('Y-m-d');
		$enddate = isset($_GET['enddate_jualdo'])?$_GET['enddate_jualdo']:date('Y-m-d'); 
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
				$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%') or (p.id_trans like '%$filter%') or (p.ref_kode like '%$filter%'))";
			}
			
				
		}	
		else
		{
		$where .=" AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		//((p.ref_code like '%$filter%') or (j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%'))
		$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition,i.id as id_kirim FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) Left Join `olnso_id` i on (p.id_trans=i.id_trans) ".$where;
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
        	
			$allowInvoice = array(1,2,3);
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			$allowPack = array(1,2,3);
			if ($statusToko == 'Tutup') {
				$invoice = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Invoice</a>';
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Posting</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
                $state_pack = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">PACKED</a>';
            } else {
		    if(in_array($_SESSION['user']['access'], $allowEdit)){
			$invoice = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_invoice.php?id_trans='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">Invoice</a>';
			}
			else
				$invoice = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			if(in_array($_SESSION['user']['access'], $allowInvoice)){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_nota.php?id_trans='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">Label</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Invoice\')" href="javascript:;">Edit</a>';
			
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolndo.php?action=delete&id='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">UnPost</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">UnPost</a>';
			
			    $select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
			if($line['stkirim'] == '1') {
				if($allow_post) {
				$state_pack = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolndo.php?action=unpack&id='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">PACKED</a>';
				}
				else {
				$state_pack = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">PACKED</a>';
				}
			}
			else {
				//$allowPack = array(1,2,3);

				if($allow_post) {
				$state_pack = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolndo.php?action=pack&id='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">UNPACKED</a>';
				}
				else {
				$state_pack = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">UNPACKED</a>';
				}
			}
			}

        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_kirim'],
                $line['id_trans'],
                $line['ref_kode'],                
                $line['dropshipper'],                
                //$line['tgl_trans'],
                $line['lastmodified'],
                $line['nama'],
                $line['alamat'],
                number_format($line['exp_fee'],0),
                $line['expedition'],
                $line['exp_code'],
                number_format($line['totalqty'],0),
				$invoice,
				$state_pack,
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'pack') {
		$stmt = $db->prepare("UPDATE olnso SET stkirim=1 WHERE id_trans=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("update trpacking_detail set deleted=1 WHERE id_oln=?");
		$stmt->execute(array($_GET['id']));
		
		//update trolnso agar state jadi nol dan dikembalikan ke sales_order dan stkirim jadi 0 lagi
		$stmt = $db->prepare("update olnso set state='0',stkirim=0 WHERE id_trans=?");
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
        	<label for="project_id" class="ui-helper-reset label-control">Posting Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_jualdo" name="startdate_jualdo">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jualdo" name="enddate_jualdo">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterdo" name="filterdo">(Dropshipper,Receiver,Expedition,Exp_Code,PACKED/UNPACKED,ID OLN,ID WEB)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadJualDO()" class="btn" type="button">Cari</button>
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
<button id="btn-xlsdo"  class="btn btn-success">XLS Selected</button>
<button id="btn-print"  class="btn btn-success">Print Selected Label</button>
<button id="btn-barcode"  class="btn btn-success">Print Selected BARCODE</button>
<button id="btn-1by1"  class="btn btn-success">Print Selected 1 By 1</button>
</div>
 
<table id="table_jualdo"></table>
<div id="pager_table_jualdo"></div>

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
	 
	$('#startdate_jualdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jualdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_jualdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_jualdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJualDO(){
		var startdate_jualdo = $("#startdate_jualdo").val();
		var enddate_jualdo = $("#enddate_jualdo").val();
		var filter_do = $("#filterdo").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_online/trolndo.php?action=json&startdate_jualdo='+startdate_jualdo+'&enddate_jualdo='+enddate_jualdo+'&filter='+filter_do;
		jQuery("#table_jualdo").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
	
	$("#btn-barcode").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
				window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_3nota_bd.php?ids='+ids,'_blank');
	});
	
	$("#btn-1by1").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
				window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_4_1by1.php?ids='+ids,'_blank');
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
        $("#table_jualdo").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/trolndo.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID_ship','ID_oln','ID_web','Dropshipper','Post.Date','Receiver','Address','Exp_fee','Expedition','Exp.Code','Qty','Invoice','StPack','Print','Cancel'],
            colModel:[
                {name:'id_kirim',index:'id_kirim', align:'right', width:20, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'ref_kode',index:'ref_kode', align:'right', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'dropshipper',index:'dropshipper', width:60, searchoptions: {sopt:['cn']}},                
                {name:'lastmodified',index:'lastmodified', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'nama',index:'nama', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'alamat',index:'alamat', align:'left', width:80, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'expedition',index:'expedition', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'exp_code',index:'exp_code', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'totalqty',index:'totalqty', align:'right', width:18, searchoptions: {sopt:['cn']}},
                {name:'invoice',index:'invoice', align:'center', width:25, sortable: false, search: false},
				{name:'state_pack',index:'state_pack', align:'center', width:25, sortable: false, search: false},
				{name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_jualdo',
            sortname: 'id_trans',
            autowidth: true,
			multiselect:true,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Online Siap Kirim",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/trolndo.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jualdo").jqGrid('navGrid','#pager_table_jualdo',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>