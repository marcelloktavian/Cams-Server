<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, Taxes, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, Taxes, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, Taxes, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE deleted=0 ";
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
	  $where = sprintf(" where deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $q = $db->query("SELECT * FROM `mst_taxes` a ".$where." order by `status` DESC");
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT * FROM `mst_taxes` a ".$where." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
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
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
            if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_biaya/taxes.php?action=edit&id='.$line['id'].'\',\'table_taxes\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_biaya/taxes.php?action=delete&id='.$line['id'].'\',\'table_taxes\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $status='';
            if($line['status'] == 'Y'){
                $status = 'Aktif';
            }else{
                $status = 'Tidak Aktif';
            }

            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['nama'],
                $line['value'],
                $line['keterangan'],   
                $status,              
                $edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'taxes_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'taxes_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_taxes SET deleted=? WHERE id=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE mst_taxes SET value=?,keterangan=?,`status`=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['value'],$_POST['keterangan'],$_POST['status'], $_SESSION['user']['username'], $_POST['id']));
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
		else {
			$stmt = $db->prepare("INSERT INTO  mst_taxes(`nama`,`value`,`keterangan`,`status`,`user`,`lastmodified`) VALUES( ?,?,?,  ?,NOW())");
			//var_dump($stmt);die;
			if($stmt->execute(array($_POST['nama'],$_POST['value'],$_POST['keterangan'],$_POST['status'],$_SESSION['user']['username']))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}	
		echo json_encode($r);
		exit;
	}
?>
<table id="table_taxes"></table>
<div id="pager_table_taxes"></div>
<div class="btn_box">
<?php
	// $allow = array(1,2,3);
    $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
            // $id = $stats['id'];
        $statusToko = $stats['status'];
    }   
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_biaya/taxes.php?action=add\',\'table_taxes\')" class="btn">Tambah</button>';
	}}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_taxes").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_biaya/taxes.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Name','Value (%)','Note','Status','Edit','Delete'],
            colModel:[
                {name:'ID',index:'id', width:30, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', width:200, searchoptions: {sopt:['cn']}},
                {name:'value',index:'value', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'Keterangan',index:'keterangan', width:250, searchoptions: {sopt:['cn']}},
                {name:'status',index:'status', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_taxes',
            sortname: 'nama',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Taxes",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_taxes").jqGrid('navGrid','#pager_table_taxes',{edit:false,add:false,del:false});
    })
</script>