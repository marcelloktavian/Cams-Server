<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE" . $_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, ReturnConfirmed, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, ReturnConfirmed, $group_acess);

if (isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	$startdate = isset($_GET['startdate_jualrn']) ? $_GET['startdate_jualrn'] : date('Y-m-d');
	$enddate = isset($_GET['enddate_jualrn']) ? $_GET['enddate_jualrn'] : date('Y-m-d');

	$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

	$page = isset($_GET['page']) ? $_GET['page'] : 1; // get the requested page
	$limit = isset($_GET['rows']) ? $_GET['rows'] : 10; // get how many rows we want to have into the grid
	$sidx = isset($_GET['sidx']) ? $_GET['sidx'] : 'tgl_trans'; // get index row - i.e. user click to sort
	$sord = isset($_GET['sord']) ? $_GET['sord'] : '';

	//0= RETURN,1=RETURN_CONFIRMED
	//MENAMPILKAN RETURNS STATE=1 DAN TOTALQTY<>0 KRN BUKAN TRANSAKSI CANCEL 
	$where = "WHERE TRUE AND p.state='1' AND (p.totalqty <> 0)";

	if (($startdate != null) && ($filter != null)) {
		$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND p.state='1' AND (p.totalqty <> 0) AND ((j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%'))";
	} else {
		$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND p.state='1' AND (p.totalqty <> 0) ";
	}

	$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition FROM `olnsoreturn` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) " . $where;
	$q = $db->query($sql);
	$count = $q->rowCount();
	//var_dump($sql);
	$count > 0 ? $total_pages = ceil($count / $limit) : $total_pages = 0;
	if ($page > $total_pages) $page = $total_pages;
	$start = $limit * $page - $limit;
	if ($start < 0) $start = 0;

	$q = $db->query($sql . "
							 ORDER BY `" . $sidx . "` " . $sord . "
							 LIMIT " . $start . ", " . $limit);
	$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

	$statusToko = '';
	$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
	$getStat->execute();
	$stat = $getStat->fetchAll();
	foreach ($stat as $stats) {
		$statusToko = $stats['status'];
	}

	$responce['page'] = $page;
	$responce['total'] = $total_pages;
	$responce['records'] = $count;
	$i = 0;
	$grand_qty = 0;
	$grand_faktur = 0;
	$grand_totalfaktur = 0;
	$grand_piutang = 0;
	$grand_tunai = 0;
	$grand_transfer = 0;
	$grand_biaya = 0;
	foreach ($data1 as $line) {

		// $allowEdit = array(1,2,3);
		// $allowDelete = array(1,2,3);
		if ($statusToko == 'Tutup') {
			$unpost = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">UnPosting</a>';
			$delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
		} else {
			if ($allow_post) {
				$unpost = '<a onclick="javascript:link_ajax(\'' . BASE_URL . 'pages/sales_online/troln_return_confirmed.php?action=posting&id=' . $line['id_trans'] . '\',\'table_olnreturcf\')" href="javascript:;">UnPosting</a>';
			} else
				$unpost = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">UnPosting</a>';

			if ($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\'' . BASE_URL . 'pages/sales_online/troln_return_confirmed.php?action=delete&id=' . $line['id_trans'] . '\',\'table_olnreturcf\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';

			//$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
		}
		$responce['rows'][$i]['id']   = $line['id_trans'];
		$responce['rows'][$i]['cell'] = array(
			$line['id_trans'],
			$line['ref_kode'],
			$line['id_oln'],
			$line['dropshipper'],
			$line['tgl_trans'],
			$line['lastmodified'],
			$line['nama'],
			$line['alamat'],
			number_format($line['totalqty'], 0),
			number_format($line['faktur'], 0),
			number_format($line['exp_fee'], 0),
			number_format($line['total'], 0),
			$line['expedition'],
			$line['exp_code'],
			$unpost,
			//	$select,
		);
		$grand_qty += $line['totalqty'];
		$grand_faktur += $line['faktur'];
		$grand_totalfaktur += $line['total'];
		$grand_tunai += $line['tunai'];
		$grand_transfer += $line['transfer'];
		$grand_biaya += $line['exp_fee'];
		$i++;
	}

	$responce['userdata']['totalqty'] 		= number_format($grand_qty, 0);
	$responce['userdata']['faktur'] 		= number_format($grand_faktur, 0);
	$responce['userdata']['total']     	    = number_format($grand_totalfaktur, 0);
	$responce['userdata']['piutang'] 		= number_format($grand_piutang, 0);
	$responce['userdata']['tunai'] 			= number_format($grand_tunai, 0);
	$responce['userdata']['transfer']		= number_format($grand_transfer, 0);
	$responce['userdata']['exp_fee'] 	    = number_format($grand_biaya, 0);

	echo json_encode($responce);

	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
	//update olnsoreturn agar jadi 0 krn diunpost string='0' krn tipe datanya enum
	$stmt = $db->prepare("Update olnsoreturn set state='0',lastmodified=now() WHERE id_trans=?");
	$stmt->execute(array($_GET['id']));

	$affected_rows = $stmt->rowCount();
	if ($affected_rows > 0) {
		$r['stat'] = 1;
		$r['message'] = 'Success';
	} else {
		$r['stat'] = 0;
		$r['message'] = 'Failed';
	}
	echo json_encode($r);
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {

	//update olnso agar jadi nol krn void invoice
	$stmt = $db->prepare("Update olnsoreturn set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deleted=1,state='0' WHERE id_trans=?");
	$stmt->execute(array($_GET['id']));
	var_dump($stmt);
	die;
	//update trjual_detail agar jadi nol krn void invoice
	$stmt = $db->prepare("update olnsoreturn_detail set jumlah_beli=0,jumlah_return=0,harga_satuan=0,subtotal=0,subtotal_return=0 WHERE id_trans=?");
	$stmt->execute(array($_GET['id']));
	//var_dump($stmt);die;

	$affected_rows = $stmt->rowCount();
	if ($affected_rows > 0) {
		$r['stat'] = 1;
		$r['message'] = 'Success';
	} else {
		$r['stat'] = 0;
		$r['message'] = 'Failed';
	}
	echo json_encode($r);
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {

	$id = $_GET['id'];
	//$id = $line['id_trans'];
	$where = "WHERE pd.id_trans = '" . $id . "' ";
	$q = $db->query("SELECT pd.* FROM `olnsoreturn_detail` pd " . $where);

	$count = $q->rowCount();

	//$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
	$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

	$i = 0;
	$responce = '';
	foreach ($data1 as $line) {
		$responce->rows[$i]['id']   = $line['id_rn_d'];
		$responce->rows[$i]['cell'] = array(
			$i + 1,
			$line['id_product'],
			$line['namabrg'],
			number_format($line['harga_satuan'], 0),
			number_format($line['jumlah_beli'], 0),
			number_format($line['jumlah_return'], 0),
			number_format($line['subtotal'], 0),
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
			<label for="project_id" class="ui-helper-reset label-control">Post.Date</label>
			<div class="ui-corner-all form-control">
				<table>
					<tr>
						<td>
							<input value="" type="text" class="required datepicker" id="startdate_jualrn" name="startdate_jualrn">
						</td>
						<td> s.d.
							<input value="" type="text" class="required datepicker" id="enddate_jualrn" name="enddate_jualrn">
						</td>

						<td>
							<label for="lbldropshipper" class="ui-helper-reset label-control">Filter</label>
						</td>
						<td>
							<input value="" type="text" id="filter" name="filter">(Dropshipper,Receiver,Expedition,Exp_Code)
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
				} else {
				?>
					<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/sales_online/troln_return_confirmed_rpt.php?action=preview&start='+$('#startdate_jualrn').val()+'&end='+$('#enddate_jualrn').val()+'&filter_rn='+$('#filter').val())" class="btn" type="button">Print</button>
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
   
 <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span> 
<button id="btn-print"  class="btn btn-success">Print</button>
-->
</div>

<table id="table_olnreturcf"></table>
<div id="pager_table_olnreturcf"></div>

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
	$('#startdate_jualrn').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jualrn').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$("#startdate_jualrn").datepicker('setDate', '<?php echo date('d/m/Y') ?>');
	$("#enddate_jualrn").datepicker('setDate', '<?php echo date('d/m/Y') ?>');


	function gridReloadJual() {
		var startdate_jualrn = $("#startdate_jualrn").val();
		var enddate_jualrn = $("#enddate_jualrn").val();
		var filter = $("#filter").val();
		var v_url = '<?php echo BASE_URL ?>pages/sales_online/troln_return_confirmed.php?action=json&startdate_jualrn=' + startdate_jualrn + '&enddate_jualrn=' + enddate_jualrn + '&filter=' + filter;
		jQuery("#table_olnreturcf").setGridParam({
			url: v_url,
			page: 1
		}).trigger("reloadGrid");
	}

	$("#btn-print").on('click', function() {
		var ids = getSelectedRows();
		if (ids !== '')
			window.open('<?php echo BASE_URL ?>pages/sales_online/trolnso_3nota_new.php?ids=' + ids, '_blank');
	});

	function getSelectedRows() {
		var grid = $("#table_olnreturcf");
		var rowKey = grid.getGridParam("selrow");

		if (!rowKey) {
			alert("No rows are selected");
			return '';
		} else {

			var selectedIDs = grid.getGridParam("selarrrow");
			var result = "";
			for (var i = 0; i < selectedIDs.length; i++) {
				result += "'" + selectedIDs[i] + "'" + ",";
			}

			return result;

		}
	}


	$(document).ready(function() {

		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
		$("#table_olnreturcf").jqGrid({
			url: '<?php echo BASE_URL . 'pages/sales_online/troln_return_confirmed.php?action=json'; ?>',
			/*postData: {
			    'title': function() {return $('#sJudul').val(); },
			    'sales_id': function() {return $('#sSales_id').val(); },
			    'Name': function() {return $('#sCustomFer').val(); },
			    'summary_status': function() {return $('#sStatus').val(); },
			},*/
			datatype: "json",
			//colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
			colNames: ['ID', 'ID_web', 'ID_oln', 'Dropshipper', 'Date', 'Post.Date', 'Receiver', 'Address', 'Qty', 'Faktur', 'Exp.Fee', 'Total', 'Expedition', 'Exp.Code', 'Unpost'],
			colModel: [{
					name: 'id_trans',
					index: 'id_trans',
					width: 10,
					search: true,
					stype: 'text',
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'ref_kode',
					index: 'ref_kode',
					width: 20,
					search: true,
					stype: 'text',
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'id_oln',
					index: 'id_oln',
					width: 25,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'dropshipper',
					index: 'dropshipper',
					width: 35,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'tgl_trans',
					index: 'tgl_trans',
					width: 30,
					searchoptions: {
						sopt: ['cn']
					},
					formatter: "date",
					formatoptions: {
						srcformat: "Y-m-d",
						newformat: "d/m/Y"
					},
					align: 'center'
				},
				{
					name: 'lastmodified',
					index: 'lastmodified',
					width: 30,
					searchoptions: {
						sopt: ['cn']
					},
					formatter: "date",
					formatoptions: {
						srcformat: "Y-m-d",
						newformat: "d/m/Y"
					},
					align: 'center'
				},
				{
					name: 'nama',
					index: 'nama',
					align: 'left',
					width: 60,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'alamat',
					index: 'alamat',
					align: 'left',
					width: 100,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'totalqty',
					index: 'totalqty',
					align: 'right',
					width: 15,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'faktur',
					index: 'faktur',
					align: 'right',
					width: 30,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'exp_fee',
					index: 'exp_fee',
					align: 'right',
					width: 20,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'total',
					index: 'total',
					align: 'right',
					width: 30,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'expedition',
					index: 'expedition',
					align: 'left',
					width: 35,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'exp_code',
					index: 'exp.code',
					align: 'left',
					width: 35,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'unpost',
					index: 'unpost',
					align: 'center',
					width: 25,
					sortable: false,
					search: false
				},
				//  {name:'select',index:'select', align:'center', width:30, sortable: false, search: false},
			],
			rowNum: 2000,
			rowList: [1000, 2000, 3000],
			pager: '#pager_table_olnreturcf',
			sortname: 'id_trans',
			autowidth: true,
			//multiselect:true,
			height: '300',
			viewrecords: true,
			rownumbers: true,
			sortorder: "desc",
			caption: "OLN Rincian Retur",
			ondblClickRow: function(rowid) {
				alert(rowid);
			},
			footerrow: true,
			userDataOnFooter: true,
			subGrid: true,
			subGridUrl: '<?php echo BASE_URL . 'pages/sales_online/troln_return.php?action=json_sub'; ?>',
			subGridModel: [{
				name: ['No', 'Kode', 'Barang', 'Harga (inc PPN)', 'Disc', 'Qty(return)', 'Subtotal'],
				width: [40, 40, 300, 30, 50, 50, 50],
				align: ['right', 'center', 'left', 'right', 'right', 'right', 'right'],
			}],


		});
		$("#table_olnreturcf").jqGrid('navGrid', '#pager_table_olnreturcf', {
			edit: false,
			add: false,
			del: false,
			search: false
		});



		// $("#checkAll").click(function () {
		// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
	})
</script>