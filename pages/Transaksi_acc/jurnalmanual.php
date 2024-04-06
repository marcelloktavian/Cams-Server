<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE" . $_SESSION['user']['group_id']));
// $allow_add = is_show_menu(ADD_POLICY, JurnalTransaction, $group_acess);
// $allow_edit = is_show_menu(EDIT_POLICY, JurnalTransaction, $group_acess);
// $allow_delete = is_show_menu(DELETE_POLICY, JurnalTransaction, $group_acess);

$allow_add = is_show_menu(ADD_POLICY, BiayaOperasional, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, BiayaOperasional, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, BiayaOperasional, $group_acess);

if (isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	$startdate = isset($_GET['start_jurnal']) ? $_GET['start_jurnal'] : date('Y-m-d');
	$enddate = isset($_GET['end_jurnal']) ? $_GET['end_jurnal'] : date('Y-m-d');

	$page = isset($_GET['page']) ? $_GET['page'] : 1; // get the requested page
	$limit = isset($_GET['rows']) ? $_GET['rows'] : 10; // get how many rows we want to have into the grid
	$sidx = isset($_GET['sidx']) ? $_GET['sidx'] : 'tgl_trans'; // get index row - i.e. user click to sort
	$sord = isset($_GET['sord']) ? $_GET['sord'] : '';

	$filter = $_GET['filter'];
	$status = $_GET['status_jurnal'];

	$where = "WHERE TRUE AND deleted=0 ";

	if ($startdate != null) {
		$where .= " AND DATE(tgl) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
	}
	if ($filter != null) {
		$where .= " AND (no_jurnal like '%$filter%' OR keterangan  like '%$filter%') ";
	}
	if ($status != '' && $status != 'ALL') {
		$where .= " AND (`status`='$status') ";
	}
	$sql = "SELECT * FROM `jurnal` " . $where;

	$q = $db->query($sql);
	$count = $q->rowCount();

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

	$grand_totaldebet = 0;
	$grand_totalkredit = 0;
	foreach ($data1 as $line) {
		// $allowEdit = array(1,2,3);
		// $allowDelete = array(1,2,3);
		if ($statusToko == 'Tutup') {
			$edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
			$delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
		} else {
			if ($allow_edit) {
				if ($line['state_edit'] == '0') {
					$edit = '<a onclick="javascript:popup_form(\'' . BASE_URL . 'pages/Transaksi_acc/jurnalmanual.php?action=passedit&id=' . $line['id'] . '\',\'table_jurnal\')" href="javascript:;">Edit</a>';
				} else {
					$edit = '<a onclick="javascript:window.open(\'' . BASE_URL . 'pages/Transaksi_acc/EditjurnalmanualDet.php?&id=' . $line['id'] . '\',\'table_jurnal\')" href="javascript:;"  style="color:blue;">Edit</a>';
				}
			} else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
			if ($allow_delete)
				// $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/Transaksi_acc/jurnalmanual.php?action=delete&id='.$line['id'].'\',\'table_jurnal\')" href="javascript:;">Delete</a>';
				if ($line['state_edit'] == '0') {
					$delete = '<a onclick="javascript:popup_form(\'' . BASE_URL . 'pages/Transaksi_acc/jurnalmanual.php?action=pass&id=' . $line['id'] . '\',\'table_jurnal\')" href="javascript:;">Delete</a>';
				} else {
					$delete = '<a onclick="javascript:popup_form(\'' . BASE_URL . 'pages/Transaksi_acc/jurnalmanual.php?action=pass&id=' . $line['id'] . '\',\'table_jurnal\')" href="javascript:;" style="color:blue;">Delete</a>';
				}

			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
		}
		$responce['rows'][$i]['id']   = $line['id'];
		$responce['rows'][$i]['cell'] = array(
			$line['id'],
			$line['no_jurnal'],
			$line['tgl'],
			number_format($line['total_debet'], 2),
			number_format($line['total_kredit'], 2),
			$line['keterangan'],
			$edit,
			$delete,
			$line['state_edit'],
		);
		$grand_totaldebet += $line['total_debet'];
		$grand_totalkredit += $line['total_kredit'];

		$i++;
	}
	$responce['userdata']['totaldebet'] = number_format($grand_totaldebet, 0);
	$responce['userdata']['totalkredit'] = number_format($grand_totalkredit, 0);

	echo json_encode($responce);
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'pass') {
	//tgl beda
	include 'jurnalmanual_form.php';
	exit();
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'passedit') {
	//tgl beda
	include 'jurnalmanual_formedit.php';
	exit();
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'process_pass') {
	//cek apakah pass sama atau tidak
	$stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('" . $_POST['pass'] . "') AND (user_id=17 OR user_id=3 OR user_id=13)");
	$stmt->execute();

	$affected_rows = $stmt->rowCount();
	if ($affected_rows > 0) {
		$user = $_SESSION['user']['username'];

		$stmt = $db->prepare("UPDATE jurnal_detail SET deleted=1, user=?, lastmodified=NOW() WHERE id_parent=?");
		$stmt->execute(array($user, $_POST['id']));

		$stmt = $db->prepare("UPDATE jurnal SET deleted=1, user=?, lastmodified=NOW() WHERE id=?");
		$stmt->execute(array($user, $_POST['id']));

		$r['stat'] = 1;
		$r['message'] = 'Success';
	} else {
		$r['stat'] = 0;
		$r['message'] = 'Failed';
	}
	echo json_encode($r);
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'process_passedit') {
	//cek apakah pass sama atau tidak
	$stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('" . $_POST['pass_jm_edit'] . "') AND (user_id=17 OR user_id=3 OR user_id=13 OR user_id=10)");
	$stmt->execute();

	$affected_rows = $stmt->rowCount();
	if ($affected_rows > 0) {
		$r['stat'] = 1;
		$r['message'] = 'Success';
		$stmt = $db->prepare("UPDATE `jurnal` SET `state_edit`=1 WHERE id=" . $_POST['id'] . "");
		$stmt->execute();
	} else {
		$r['stat'] = 0;
		$r['message'] = 'Failed';
	}
	echo json_encode($r);
	exit;
} elseif (isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
	$user = $_SESSION['user']['username'];
	$stmt = $db->prepare("UPDATE jurnal_detail SET deleted=1, user=?, lastmodified=NOW() WHERE id_parent=?");
	$stmt->execute(array($user, $_GET['id']));

	$stmt = $db->prepare("UPDATE jurnal SET deleted=1, user=?, lastmodified=NOW() WHERE id=?");
	$stmt->execute(array($user, $_GET['id']));
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
	error_reporting(0);
	$id = $_GET["id"];
	$where = " AND id_parent = '" . $id . "' AND deleted=0";
	$q = $db->query("SELECT DISTINCT x.* FROM ( (SELECT * FROM jurnal_detail WHERE debet>0 " . $where . ") UNION ALL (SELECT * FROM jurnal_detail WHERE kredit>0 " . $where . ") ) x");

	$count = $q->rowCount();

	$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

	$i = 0;
	$responce = '';
	foreach ($data1 as $line) {
		$responce->rows[$i]['id']   = $line['id'];
		$responce->rows[$i]['cell'] = array(
			$i + 1,
			$line['no_akun'],
			$line['nama_akun'],
			number_format($line['debet'], 2),
			number_format($line['kredit'], 2),
			$line['keterangan'],
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
							<input value="" type="text" class="required datepicker" id="start_jurnal" name="start_jurnal">
						</td>
						<td> s.d.
							<input value="" type="text" class="required datepicker" id="end_jurnal" name="end_jurnal">
						</td>
						<td> Filter
							<input value="" type="text" id="filter_jurnal" name="filter_jurnal">(No Jurnal, Keterangan)
						</td>
					</tr>
				</table>
			</div>
			<label for="project_id" class="ui-helper-reset label-control">Status</label>
			<div class="ui-corner-all form-control">
				<table>
					<tr>
						<td>
							<select id="status_jurnal" name="status_jurnal">
								<option value="ALL">ALL</option>
								<option value="MANUAL">MANUAL</option>
								<option value="OLN">OLN</option>
								<option value="B2B">B2B</option>
								<option value="RETUR">RETUR OLN</option>
								<option value="RETURB2B">RETUR B2B</option>
								<option value="B2B PAY">B2B PAY</option>
								<option value="B2B ATUR KOMISI">B2B ATUR KOMISI</option>
								<option value="AP">AP</option>
								<option value="AR">AR OLN</option>
								<option value="B2B AR">AR B2B</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<label for="" class="ui-helper-reset label-control">&nbsp;</label>
			<div class="ui-corner-all form-control">
				<button onclick="gridReloadJurnal()" class="btn" type="button">Cari</button>
			</div>
		</form>
	</div>
</div>

<div class="btn_box">
	<?php

	// $allow = array(1,2,3);
	$statusToko = '';
	$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
	$getStat->execute();
	$stat = $getStat->fetchAll();
	foreach ($stat as $stats) {
		$statusToko = $stats['status'];
	}

	if ($statusToko == 'Tutup') {
		echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
	} else {
		// if($allow_add) {
		echo '<button type="button" onclick="javascript:window.open(\'' . BASE_URL . 'pages/Transaksi_acc/jurnalmanualDet.php?action=add\',\'table_jurnal\')" class="btn">Tambah</button>';
		// }
	}

	?>
</div>
<table id="table_jurnal"></table>
<div id="pager_table_jurnal"></div>

<script type="text/javascript">
	$('#start_jurnal').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_jurnal').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$("#start_jurnal").datepicker('setDate', '<?php echo date('d/m/Y') ?>');
	$("#end_jurnal").datepicker('setDate', '<?php echo date('d/m/Y') ?>');

	function gridReloadJurnal() {
		var start_jurnal = $("#start_jurnal").val();
		var end_jurnal = $("#end_jurnal").val();
		var filter = $("#filter_jurnal").val();
		var status_jurnal = $("#status_jurnal").val();

		var v_url = '<?php echo BASE_URL ?>pages/Transaksi_acc/jurnalmanual.php?action=json&start_jurnal=' + start_jurnal + '&end_jurnal=' + end_jurnal + '&filter=' + filter + '&status_jurnal=' + status_jurnal;
		jQuery("#table_jurnal").setGridParam({
			url: v_url,
			page: 1
		}).trigger("reloadGrid");
	}

	$(document).ready(function() {

		$("#table_jurnal").jqGrid({
			url: '<?php echo BASE_URL . 'pages/Transaksi_acc/jurnalmanual.php?action=json'; ?>',

			datatype: "json",
			colNames: ['ID', 'No Jurnal', 'Tgl.Jurnal', 'Total Debet', 'Total Kredit', 'Keterangan', 'Edit', 'Delete', 'State'],
			colModel: [{
					name: 'id',
					index: 'id',
					align: 'right',
					width: 20,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'no_jurnal',
					index: 'no_jurnal',
					width: 100,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'tgl',
					index: 'tgl',
					width: 60,
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
					name: 'total_debet',
					index: 'total_debet',
					align: 'right',
					width: 70,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'total_kredit',
					index: 'total_kredit',
					align: 'right',
					width: 70,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'keterangan',
					index: 'keterangan',
					width: 200,
					searchoptions: {
						sopt: ['cn']
					}
				},
				{
					name: 'Edit',
					index: 'edit',
					align: 'center',
					width: 30,
					sortable: false,
					search: false
				},
				{
					name: 'Delete',
					index: 'delete',
					align: 'center',
					width: 30,
					sortable: false,
					search: false
				},
				{
					name: 'State',
					index: 'state',
					hidden: true
				},
			],
			rowNum: 20,
			rowList: [20, 30, 40],
			rowattr: function(rowData) {
				if (rowData.State == "1") {
					console.log(rowData.State);
					return {
						"style": "color:blue;"
					};
				}
			},
			pager: '#pager_table_jurnal',
			sortname: 'id',
			autowidth: true,
			height: '460',
			viewrecords: true,
			rownumbers: true,
			sortorder: "desc",
			caption: "Transaksi Jurnal Manual",
			ondblClickRow: function(rowid) {
				alert(rowid);
			},
			footerrow: true,
			userDataOnFooter: true,
			subGrid: true,
			subGridUrl: '<?php echo BASE_URL . 'pages/Transaksi_acc/jurnalmanual.php?action=json_sub'; ?>',
			subGridModel: [{
				name: ['No', 'No Akun', 'Nama Akun', 'Debet', 'Kredit', 'Keterangan'],
				width: [40, 100, 200, 70, 70, 200],
				align: ['center', 'left', 'left', 'right', 'right', 'left'],
			}],
		});
		$("#table_jurnal").jqGrid('navGrid', '#pager_table_jurnal', {
			edit: false,
			add: false,
			del: false
		});
	})
</script>