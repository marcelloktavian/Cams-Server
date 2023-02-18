<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));

$allow_add = is_show_menu(ADD_POLICY, CRTertunda, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, CRTertunda, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_crtertunda'])?$_GET['startdate_crtertunda']:date('Y-m-d');
		$enddate = isset($_GET['enddate_crtertunda'])?$_GET['enddate_crtertunda']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tanggal'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND ((jumlah-payment)<>0) ";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(lastmodified) between STR_TO_DATE('$startdate','%d/%m/%Y') AND  STR_TO_DATE('$enddate','%d/%m/%Y') AND (a.keterangan like '%$filter%' OR a.cabang like '%$filter%')";	
		}	
		else
		{
			$where .=" AND DATE(lastmodified) between STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ";
		}
		$sql = " SELECT a.* FROM acc_prebank a ".$where;
		// var_dump($sql);
		// die;	
		$q = $db->query($sql);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
							//  var_dump($q);
							//  die;
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
        foreach($data1 as $line) {
            if ($statusToko == 'Tutup') {
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
            }else{
				if($allow_delete){
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_Operasional/crtertunda.php?action=delete&id='.$line['id'].'\',\'tbllCRTertunda\')" href="javascript:;">Cancel</a>';
				}else{
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Cancel</a>';
				}
            }

        	$responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
				$line['id'],
                $line['periode'],                
                $line['tanggal_trans'],
                $line['lastmodified'],
                $line['keterangan'],
                $line['cabang'],
                number_format($line['jumlah'],0),
                number_format($line['payment'],0),
				$delete,
			);
            $i++;
		}
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("DELETE FROM acc_prebank WHERE id=?");
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
		// error_reporting(0);
		// $id = $_GET["id"];
        // $q1 = $db->query("SELECT id_import FROM trpaymentcheck_detail det WHERE det.id_parent='".$id."' AND id_import<>'0'");
		// $data1 = $q1->fetchAll(PDO::FETCH_ASSOC);
		
        // $i=0;
		// $responce = '';
        // foreach($data1 as $line1){
		// 	$idimport = $line['id_import'];
		// 	$q2 = $db->query("SELECT id_import FROM trpaymentcheck_detail det WHERE det.id_parent='".$id."' AND id_import<>'0'");
		// 	$data2 = $q2->fetchAll(PDO::FETCH_ASSOC);



        //     $responce->rows[$i]['id']   = $line['id'];
        //     $responce->rows[$i]['cell'] = array(
        //         $i+1,
        //         $line['keterangan'],
        //         $line['periode'],
		// 		number_format($line['payment_value'],0),
        //         $line['id_trans'],
        //         '',
        //         $line['tgl_trans'],
		// 		number_format($line['subtotal'],0),
		// 	);
        //     $i++;
        // }
        // echo json_encode($responce);
		exit;
	}
	 
	 
?>
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal Import</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_crtertunda" name="startdate_crtertunda">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_crtertunda" name="enddate_crtertunda">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterCRTertunda" name="filterCRTertunda">
				 (Keterangan/Cabang)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridCRTertunda()" class="btn" type="button">Cari</button>
				<!-- <button onclick="printrptOperasional()" class="btn" type="button">Cetak</button> -->
				<!-- <button onclick="printrptharian()" class="btn" type="button">Cetak Harian</button> -->
			</div>
       	</form>
   	</div>
</div>
<table id="tbllCRTertunda"></table>
<div id="pager_tbllCRTertunda"></div>

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
	 
	$('#startdate_crtertunda').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_crtertunda').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_crtertunda" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_crtertunda" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printrptOperasional() {
		var filter = $('#filterCRTertunda').val();
		var startdate = $('#startdate_crtertunda').val();
		var enddate = $('#enddate_crtertunda').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/rpt_biayaOperasional.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		
	}
	
	function gridCRTertunda(){
		var startdate_crtertunda = $("#startdate_crtertunda").val();
		var enddate_crtertunda = $("#enddate_crtertunda").val();
		var filter_do = $("#filterCRTertunda").val();
		var v_url ='<?php echo BASE_URL?>pages/Transaksi_Operasional/crtertunda.php?action=json&startdate_crtertunda='+startdate_crtertunda+'&enddate_crtertunda='+enddate_crtertunda+'&filter='+filter_do;
		jQuery("#tbllCRTertunda").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
	
	function print() {
		var filter = $('#filterCRTertunda').val();
		var startdate = $('#startdate_crtertunda').val();
		var enddate = $('#enddate_crtertunda').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/Transaksi_Operasional/crtertunda.php?action=preview&filter='+filter+'&start='+startdate+'&end='+enddate);
		
	}

	 function getSelectedRows() {
            var grid = $("#tbllCRTertunda");
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
        $("#tbllCRTertunda").jqGrid({
            url:'<?php echo BASE_URL.'pages/Transaksi_Operasional/crtertunda.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Periode','Date','Date Import','Keterangan','Cabang','Total','Payment','Cancel'],
            colModel:[
                {name:'id',index:'id', width:10, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'periode',align:'center',index:'periode', width:35, search:false, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'tanggal_trans',index:'tanggal_trans', width:25, search:false, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'lastmodified',index:'lastmodified', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
				{name:'keterangan',index:'keterangan', align:'left', width:100, searchoptions: {sopt:['cn']}},
                {name:'cabang',index:'cabang', width:35, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'total',index:'total', align:'right', width:30, searchoptions: {sopt:['cn']}},
				{name:'payment',index:'payment', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'delete',index:'delete', align:'center', width:15, sortable: false, search: false},
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_tbllCRTertunda',
            sortname: 'id',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data CR Kas Besar Tertunda",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : false,
            subGridUrl : '<?php echo BASE_URL.'pages/Transaksi_Operasional/paymentcheck.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Keterangan','Tgl Payment','Payment','Order ID','Dropshipper/Customer','Tgl Sales','Value',], 
			            		width : [40,250,100,100,100,250,100,100],
			            		align : ['right','center','center','right','center','center','center','right'],
			            	} 
			            ],
						
            
        });
        $("#tbllCRTertunda").jqGrid('navGrid','#pager_tbllCRTertunda',{edit:false,add:false,del:false,search:false});
    })
</script>