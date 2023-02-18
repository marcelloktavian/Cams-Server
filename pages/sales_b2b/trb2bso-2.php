<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, SalesB2B, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, SalesB2B, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, SalesB2B, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];
	
	$startdate = isset($_GET['startdate_b2b'])?$_GET['startdate_b2b']:date('Y-m-d');
	$enddate = isset($_GET['enddate_b2b'])?$_GET['enddate_b2b']:date('Y-m-d'); 
	
	$customer = isset($_GET['customer'])?$_GET['customer']:''; 
	
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
        
		//0=DRAFT SALES,1=CONFIRMED SALES,3=ARCHIVE_DO
		//MENAMPILKAN PENJUALAN YANG BARU INPUT STATE=0 DAN TOTALQTY<>0 KRN BUKAN TRANSAKSI CANCEL dan TRANSAKSI YANG LUNAS (PIUTANG=0)
        $where = "WHERE TRUE AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang > 0)";
        
		/*
		if(($startdate != null) AND ($dropshipper != null)){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND p.state='0' AND (p.totalqty <> 0) AND j.nama like %$dropshipper% ";
		}
		*/
		
		if($startdate != null){
			$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND p.state='0' AND (p.totalqty <> 0)";
		}	
		
		
		
		$sql = "SELECT p.*,k.nama as kategori,j.nama as customer,s.nama as salesman,e.nama as expedition FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where;
		$q = $db->query($sql);
		$count = $q->rowCount();
        // var_dump($sql);
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
			
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
			if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $posting = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Posting</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
            } else {
			if($allow_edit){
				$edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bsogrp_detail_edit.php?ids='.$line['id_trans'].'\',\'table_jualb2b\')" href="javascript:;">Edit</a>';
			}
			else
			{
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Edit Data\')" href="javascript:;">Edit</a>';
			}

			if($allow_post){
				$posting = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2bso.php?action=posting&id='.$line['id_trans'].'\',\'table_jualb2b\')" href="javascript:;">Posting</a>';
			}
			else
			{
				$posting = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">Posting</a>';
			}

			if($allow_delete){
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2bso.php?action=delete&id='.$line['id_trans'].'\',\'table_jualb2b\')" href="javascript:;">Cancel</a>';
			}
			else
			{
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';
			}
		}
			    //$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
			$responce['rows'][$i]['id']   = $line['id_trans'];
			$responce['rows'][$i]['cell'] = array(
				$line['id_trans'],
				$line['ref_kode'],                
				$line['customer'],                
				$line['tgl_trans'],
				$line['salesman'],
				$line['alamat'],
				// number_format($line['exp_fee'],0),
				// $line['expedition'],
				$line['kategori'],
				number_format($line['totalqty'],0),
				$edit,
				$posting,
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
		//posting data untuk oln_id
		//$stmt = $db->prepare("INSERT INTO b2bso_id(`nomor`,`id_trans`,`user_id`,`lastmodified`) SELECT IFNULL((MAX(nomor)+1),0),?,?,NOW() FROM olnso_id WHERE DATE(lastmodified)=DATE(NOW())"); 
		//$stmt->execute(array($_GET['id'],$_SESSION['user']['user_id']));
		
		//$sql_posting="INSERT INTO olnso_id(`nomor`,`id_trans`,`user`) SELECT MAX(nomor) + 1,'".$_GET['id']."','".$_SESSION['user']['user_id']."' FROM olnso_id WHERE DATE(lastmodified)=DATE(NOW())";
		//var_dump($sql_posting);
		//$stmt = $db->query($sql_posting);
		
		//update b2bso agar jadi 1 krn siap kirim,tapi statenya dikasih string='1' krn tipe datanya enum
		$stmt = $db->prepare("Update b2bso set state='1',lastmodified=now() WHERE id_trans=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete olndeposit krn void invoice		
		//$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		
		//update trb2bso agar jadi nol krn void invoice
		$stmt = $db->prepare("Update b2bso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1,state='0' WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update trb2bso_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update b2bso_detail set jumlah_beli=0,harga_satuan=0,qty36=0,qty37=0,qty38=0,qty39=0,qty40=0,qty41=0,qty42=0,qty43=0,qty44=0,qty45=0,qty46=0,subtotal=0 WHERE id_trans=?");
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
				number_format($line['qty31'],0),
				number_format($line['qty32'],0),
				number_format($line['qty33'],0),
				number_format($line['qty34'],0),
				number_format($line['qty35'],0),
				number_format($line['qty36'],0),
				number_format($line['qty37'],0),
				number_format($line['qty38'],0),
				number_format($line['qty39'],0),
				number_format($line['qty40'],0),
				number_format($line['qty41'],0),
				number_format($line['qty42'],0),
				number_format($line['qty43'],0),
				number_format($line['qty44'],0),
				number_format($line['qty45'],0),
				number_format($line['qty46'],0),
				number_format($line['harga_satuan'],0),
				number_format($line['disc'],0),                
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
				<label for="lbl_b2b" class="ui-helper-reset label-control">Tanggal</label>
				<div class="ui-corner-all form-control">
					<table>
						<tr>
							<td>
								<input value="" type="text" class="required datepicker"   id="startdate_b2b" name="startdate_b2b">
							</td>
							<td> s.d.  
								<input value="" type="text" class="required datepicker"  id="enddate_b2b" name="enddate_b2b">
							</td>
				<!--
				<td>
				<label for="lbldropshipper" class="ui-helper-reset label-control">Dropshipper</label>
				</td>
                <td>
				 <input value="" type="text" id="dropshipper" name="dropshipper">
				</td>
			-->
		</tr>
	</table>
</div>

<label for="" class="ui-helper-reset label-control">&nbsp;</label>
<div class="ui-corner-all form-control">
	<button onclick="gridReloadJualb2b()" class="btn" type="button">Cari</button>
</div>
</form>
</div>
</div>
	<div class="btn_box"><!--
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_b2b/trb2bso_add.php');">
   <button class="btn btn-success">Add</button></a>   
   
  <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span> 
<button id="btn-print"  class="btn btn-success">Print</button>
-->
</div>

<table id="table_jualb2b"></table>
<div id="pager_table_jualb2b"></div>

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
	
	$('#startdate_b2b').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_b2b').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_b2b" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_b2b" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJualb2b(){
		var startdate_b2b = $("#startdate_b2b").val();
		var enddate_b2b = $("#enddate_b2b").val();
		var customer = $("#customer").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_b2b/trb2bso.php?action=json&startdate_b2b='+startdate_b2b+'&enddate_b2b='+enddate_b2b+'&customer='+customer ;
		jQuery("#table_jualb2b").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	/*
	$("#btn-print").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
				window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_3nota_new.php?ids='+ids,'_blank');
	});
	 function getSelectedRows() {
            var grid = $("#table_jual");
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
		 
        */
        $(document).ready(function(){
        	
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
		$("#table_jualb2b").jqGrid({
			url:'<?php echo BASE_URL.'pages/sales_b2b/trb2bso.php?action=json'; ?>',
			datatype: "json",
			colNames:['ID','Kode Order','Customer','Date','Salesman','Address','Category','Qty','Edit','Posting','Cancel'],
			colModel:[
			{name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
			{name:'ref_kode',index:'ref_kode', width:30, search:true, stype:'text', searchoptions:{sopt:['cn']}},
			{name:'customer',index:'customer', width:40, searchoptions: {sopt:['cn']}},                
			{name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
			{name:'salesman',index:'salesman', align:'left', width:60, searchoptions: {sopt:['cn']}},
			{name:'alamat',index:'alamat', align:'left', width:100, searchoptions: {sopt:['cn']}},
			// {name:'exp_fee',index:'exp_fee', align:'right', width:20, searchoptions: {sopt:['cn']}},
			// {name:'expedition',index:'expedition', align:'left', width:35, searchoptions: {sopt:['cn']}},
			{name:'kategori',index:'kategori', align:'center', width:35, searchoptions: {sopt:['cn']}},
			{name:'totalqty',index:'totalqty', align:'right', width:20, searchoptions: {sopt:['cn']}},
			{name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
			{name:'posting',index:'posting', align:'center', width:25, sortable: false, search: false},
			{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
              //  {name:'select',index:'select', align:'center', width:30, sortable: false, search: false},
              ],
              rowNum:20,
              rowList:[10,20,30],
              pager: '#pager_table_jualb2b',
              sortname: 'id_trans',
              autowidth: true,
			//multiselect:true,
			height: '300',
			viewrecords: true,
			rownumbers: true,
			sortorder: "desc",
			caption:"Data Penjualan Bisnis",
			ondblClickRow: function(rowid) {
				alert(rowid);
			},
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2bso.php?action=json_sub'; ?>',
			subGridModel: [
			{ 
				name : ['No','Kode','Barang','S31','S32','S33','S34','S35','S36','S37','S38','S39','S40','S41','S42','S43','S44','S45','S46','Harga','Disc','Qty(pcs)','Subtotal'], 
				width : [40,40,300,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,50,50,50,50],
				align : ['right','center','left','center','center','center','center','center','center','center','center','center','center','center','center','center','center','center','center','right','right','right','right'],
			} 
			],
			
			
		});
		$("#table_jualb2b").jqGrid('navGrid','#pager_table_jualb2b',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
	})
</script>