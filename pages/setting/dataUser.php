<?php 
error_reporting(0);
require_once '../../include/config.php'; ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add=is_show_menu(ADD_POLICY, DataUser, $group_acess);
$allow_edit=is_show_menu(EDIT_POLICY, DataUser, $group_acess);
$allow_delete=is_show_menu(DELETE_POLICY, DataUser, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
	if ($_REQUEST["_search"] == "false") {
		$where = "WHERE  du.deleted=0 ";
	} else {
		$operations = array(
			 'eq' => "= '%s'",            // Equal
			 'ne' => "<> '%s'",           // Not equal
			 'lt' => "< '%s'",            // Less than
			 'le' => "<= '%s'",           // Less than or equal
			 'gt' => "> '%s'",            // Greater than
			 'ge' => ">= '%s'",           // Greater or equal
			 'bw' => "like '%s%%'",       // Begins With
			 'bn' => "not like '%s%%'",   // Does not begin with
			 'in' => "in ('%s')",         // In
			 'ni' => "not in ('%s')",     // Not in
			 'ew' => "like '%%%s'",       // Ends with
			 'en' => "not like '%%%s'",   // Does not end with
			 'cn' => "like '%%%s%%'",     // Contains
			 'nc' => "not like '%%%s%%'", // Does not contain
			 'nu' => "is null",           // Is null
			 'nn' => "is not null"        // Is not null
			); 

		$value = $_REQUEST["searchString"];
		$where = sprintf(" where du.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);

	}
	 //--------------end of searching--------------------

	$q = $db->query("SELECT du.user_id, du.nama,g.nama AS groups  FROM `user` du INNER JOIN `group` g ON g.id=du.group_id ".$where);
	$count = $q->rowCount();

	$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

	$q = $db->query("SELECT du.user_id, du.nama,g.nama AS groups  FROM `user` du INNER JOIN `group` g ON g.id=du.group_id 
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
		// $allowEdit = array(1,2,3);
		// $allowDelete = array(1,2,3);

		if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
		if($allow_edit){
			$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/setting/dataUser.php?action=edit&id='.$line['user_id'].'\',\'table_dataUser\')" href="javascript:;">Edit</a>';
		}else{
			$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
		}

		if($allow_delete){
			$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/setting/dataUser.php?action=delete&id='.$line['user_id'].'\',\'table_dataUser\')" href="javascript:;">Delete</a>';
		}else{
			$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
		}
	}
		$responce['rows'][$i]['id']   = $line['user_id'];
		$responce['rows'][$i]['cell'] = array(
			$line['user_id'],
			$line['nama'],
			$line['groups'],
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
	include 'dataUser_Form.php';exit();
	exit;
}

//utuk buka form edit data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
	include 'dataUser_Form.php';exit();
	exit;
}

//untuk delete data
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
	$stmt = $db->prepare("UPDATE user SET deleted=? WHERE user_id=?");
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
	if (isset($_POST['user_id'])) {
		//update data
		$query = $db->prepare("SELECT * FROM user WHERE username ='".$_POST['username']."' and user_id NOT IN('".$_POST['user_id']."') ");
		$query->execute();
		$jum = $query->rowCount();
		if ($jum==0) {
			$stmt = $db->prepare("UPDATE user SET username=?, nama=?, group_id=?, alamat=?, email=?, update_at=NOW() WHERE user_id=?");
			$stmt->execute(array($_POST['username'], $_POST['nama'],$_POST['posisi'],$_POST['alamat'],$_POST['email'],$_POST['user_id']));
			$affected_rows = $stmt->rowCount();

			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		} else {
			$r['stat'] = 0;
			$r['message'] = 'User sudah ada';
		}
	} else {
		//insert data
		$query = $db->prepare("SELECT * FROM user WHERE username ='".$_POST['username']."' ");
		$query->execute();
		$jum = $query->rowCount();
		if ($jum==0) {
			$stmt = $db->prepare("INSERT INTO user SET username=?, nama=?, password=md5(?), group_id=?, alamat=?, email=?, create_at=NOW(), update_at=NOW(), deleted=?");
			if($stmt->execute(array($_POST['username'], $_POST['nama'],$_POST['password'],$_POST['posisi'],$_POST['alamat'],$_POST['email'],'0'))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		} else {
			$r['stat'] = 0;
			$r['message'] = 'User sudah ada';
		}
	}
	echo json_encode($r);
	exit;
}
?>
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
	if($allow_add){
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/setting/dataUser.php?action=add\',\'table_dataUser\')" class="btn">Tambah</button>';
	}
}
	?>
</div>

<table id="table_dataUser"></table>
<div id="pager_tableDataUser"></div>

<script type="text/javascript">
	$(document).ready(function(){

		$("#table_dataUser").jqGrid({
			url:'<?php echo BASE_URL.'pages/setting/dataUser.php?action=json'; ?>',
			datatype: "json",
			colNames:['ID','Nama','Group','Edit','Delete'],
			colModel:[
			{name:'id',index:'id', width:1, searchoptions: {sopt:['cn']}},
			{name:'nama',index:'nama', width:5, searchoptions: {sopt:['cn']}},
			{name:'groups',index:'groups', width:3, searchoptions: {sopt:['cn']}},
			{name:'Edit',index:'edit', align:'center', width:2, sortable: false, search: false},
			{name:'Delete',index:'delete', align:'center', width:2, sortable: false, search: false},
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: '#pager_tableDataUser',
			sortname: 'id',
			autowidth: true,
			height: '230',
			viewrecords: true,
			rownumbers: true,
			sortorder: "asc",
			caption:"Master Pengguna",
			ondblClickRow: function(rowid) {
				alert(rowid);
			}
		});
		$("#table_dataUser").jqGrid('navGrid','#pager_tableDataUser',{edit:false,add:false,del:false});
	})
</script>