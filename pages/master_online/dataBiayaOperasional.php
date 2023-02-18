<?php 
error_reporting(0);
require_once '../../include/config.php'; 
?>

<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));

$allow_add = is_show_menu(ADD_POLICY, DataBiayaOperasional, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, DataBiayaOperasional, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, DataBiayaOperasional, $group_acess);

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

        // $where = "WHERE deleted=0 ";
        $q = $db->query("SELECT * FROM mst_operasional ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT * FROM mst_operasional
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/dataBiayaOperasional_Form.php?action=edit&id='.$line['id'].'\',\'table_biayarOperasional\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_online/dataBiayaOperasional.php?action=delete&id='.$line['id'].'\',\'table_biayarOperasional\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
				$line['id'],
				$line['namaoperasional'],
				$line['keterangan'],
				$edit,
				$delete,
            );
            $i++;
        }
		echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'dataBiayaOperasional_Form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'dataBiayaOperasional_Form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_operasional SET deleted=?,lastmodified=now() WHERE id=?");
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
			$stmt = $db->prepare("UPDATE mst_operasional SET namaoperasional=?,keterangan=?,lastmodified=now() WHERE id=?");
            $stmt->execute(array($_POST['nama'],$_POST['keterangan'],$_POST['id']));
            // var_dump($stmt);die;
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
			$stmt = $db->prepare("INSERT INTO mst_operasional(namaoperasional,keterangan,lastmodified) VALUES(?,?,now())");
			$stmt->execute(array($_POST['nama'],$_POST['keterangan']));
			// var_dump($stmt);die;
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
		echo json_encode($r);
		exit;
	}
?>
<table id="table_biayarOperasional"></table>
<div id="pagertable_biayaOperasional"></div>
<div class="btn_box">
<?php
	// $allow = array(1,2,3);
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/dataBiayaOperasional_Form.php?action=add\',\'table_biayarOperasional\')" class="btn">Tambah</button>';
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_biayarOperasional").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_online/dataBiayaOperasional.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Nama Biaya Operasional','Keterangan','Edit','Delete'],
            colModel:[
                {name:'id',index:'id', width:2, searchoptions: {sopt:['cn']}},
				{name:'namaoperasional',index:'namaoperasional', width:7, searchoptions: {sopt:['cn']}},            
				{name:'keterangan',index:'keterangan', width:10, searchoptions: {sopt:['cn']}},            
                {name:'Edit',index:'edit', align:'center', width:3, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:3, sortable: false, search: false},
            ],
            rowNum:50,
            rowList:[10,20,30,50,60],
            pager: '#pagertable_biayaOperasional',
            sortname: 'id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Data Operasional",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_biayarOperasional").jqGrid('navGrid','#pagertable_biayaOperasional',{edit:false,add:false,del:false});
    })
</script>