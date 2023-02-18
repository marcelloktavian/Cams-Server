<?php 
error_reporting(0);
require_once '../../include/config.php'; ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add=is_show_menu(ADD_POLICY, UserGroup, $group_acess);
$allow_edit=is_show_menu(EDIT_POLICY, UserGroup, $group_acess);
$allow_delete=is_show_menu(DELETE_POLICY, UserGroup, $group_acess);

$sql_data="SELECT * FROM `group` ";
$where =' where deleted=0';

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	if(!$sidx) $sidx=1;

	$q = $db->query("SELECT * FROM `group` ".$where);
	$count = $q->rowCount();

	$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

	$q = $db->query("SELECT * 
		FROM `group` 
		".$where."
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
	$kode='';
	foreach($data1 as $line) {
		if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
		if($allow_edit) {
			$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/setting/userGroup.php?action=edit&id='.$line['id'].'\',\'table_dataUserGroup\')" href="javascript:;">Edit</a>';
		}else{
			$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
		}

		if($allow_delete) {
			$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/setting/userGroup.php?action=delete&id='.$line['id'].'\',\'table_dataUserGroup\')" href="javascript:;">Delete</a>';
		}else{
			$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
		}
	}
		$responce['rows'][$i]['id']   = $line['id'];
		$responce['rows'][$i]['cell'] = array(
			$line['id'],
			$line['nama'],
			$line['desc'],
			$edit,
			$delete,
		);
		$i++;
	}
	echo json_encode($responce);
	exit;
}

//untuk buka form add data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
	include 'userGroup_form.php';exit();
	exit;
}

//utuk buka form edit data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
	include 'userGroup_form.php';exit();
	exit;
}

//untuk delete data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
	$stmt = $db->prepare("UPDATE `group` SET deleted=? WHERE id=?");
	$stmt->execute(array(1, $_GET['id']));
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

//untuk process data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
	if(isset($_POST['id_user'])) {
		//update data

		//cek apakah nama sudah ada 
		$query = $db->prepare("SELECT * FROM `group` WHERE nama = '".$_POST['nama']."' and id NOT IN('".$_POST['id_user']."') ");
		$query->execute();
		$jum = $query->rowCount();
		if ($jum==0) {
			$q = $db->query("SELECT * FROM `group` WHERE id = '".$_POST['id_user']."'");
			$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($data1 as $line) {
				$nama = $line['nama'];
				$desc = $line['desc'];
			}

			//cek nama sama 
			if ($_POST['nama']==$nama&&$_POST['desc']==$desc) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			} else {
				$stmt = $db->prepare("UPDATE `group` SET nama=?, `desc`=? WHERE id=?");
				$stmt->execute(array($_POST['nama'],$_POST['desc'],$_POST['id_user']));
				$affected_rows = $stmt->rowCount();
				if($affected_rows > 0) {
					$r['stat'] = 1;
					$r['message'] = 'Success';
				}
				else {
					$r['stat'] = 0;
					$r['message'] = 'Failed';
				}
			}
		} else {
			$r['stat'] = 0;
			$r['message'] = 'User Group sudah ada';
		}
	}
	else {
		//insert data
		$query = $db->prepare("SELECT * FROM `group` WHERE nama ='".$_POST['nama']."' ");
		$query->execute();
		$jum = $query->rowCount();
		if ($jum==0) {
			$stmt = $db->prepare("INSERT INTO `group` SET nama=?, `desc`=?, deleted=?");
			if($stmt->execute(array($_POST['nama'], $_POST['desc'],'0'))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		} else {
			$r['stat'] = 0;
			$r['message'] = 'User Group sudah ada';
		}
	}	
	echo json_encode($r);
	exit;
}
?>
<table id="table_dataUserGroup"></table>
<div id="pager_tabledataUserGroup"></div>
<div class="btn_box">
	<?php
	$statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/setting/userGroup.php?action=add\',\'table_dataUserGroup\')" class="btn">Tambah</button>';
	}}
	?>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		$("#table_dataUserGroup").jqGrid({
			url:'<?php echo BASE_URL.'pages/setting/userGroup.php?action=json'; ?>',
			datatype: "json",
			colNames:['ID','Nama Group','Keterangan','Edit','Delete'],
			colModel:[
			{name:'id',index:'id', width:2, searchoptions: {sopt:['cn']}},
			{name:'nama',index:'nama', width:5, searchoptions: {sopt:['cn']}},
			{name:'desc',index:'desc', width:3, searchoptions: {sopt:['cn']}},
			{name:'Edit',index:'edit', align:'center', width:2, sortable: false, search: false},
			{name:'Delete',index:'delete', align:'center', width:2, sortable: false, search: false},
			],
			rowNum:20,
			rowList:[20,30,40],
			pager: '#pager_tabledataUserGroup',
			sortname: 'id',
			autowidth: true,
			height: '230',
			viewrecords: true,
			rownumbers: true,
			sortorder: "asc",
			caption:"Master User Group",
			ondblClickRow: function(rowid) {
				alert(rowid);
			}
		});
		$("#table_dataUserGroup").jqGrid('navGrid','#pager_tabledataUserGroup',{edit:false,add:false,del:false});
	})
</script>