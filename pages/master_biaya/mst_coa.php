<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, mst_COA, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, mst_COA, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, mst_COA, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = " b.deleted=0 ";
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
	  $where = sprintf(" AND b.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";

		if(isset($_GET['filter'])){
            $filter = $_GET['filter'];
            if($_GET['filter'] != null){
			    $where .= " AND ((a.noakun like '%$filter%' OR a.nama like '%$filter%') OR (b.noakun like '%$filter%' OR b.nama like '%$filter%')) ";
            }
		}

        $q = $db->query("SELECT DISTINCT b.* FROM det_coa a RIGHT JOIN mst_coa b ON b.id=a.id_parent WHERE ".$where." GROUP BY b.id ");

		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT DISTINCT b.* FROM det_coa a RIGHT JOIN mst_coa b ON b.id=a.id_parent WHERE ".$where." GROUP BY b.id ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
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
                $editdetail = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit Detail</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			if($allow_edit){
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_biaya/mst_coa.php?action=edit&id='.$line['id'].'\',\'table_mst_coa\')" href="javascript:;">Edit</a>';
                $editdetail = '<a onclick="window.open(\''.BASE_URL.'pages/master_biaya/coa_detail_edit.php?ids='.$line['id'].'\',\'table_mst_coa\')" href="javascript:;">Edit Detail</a>';
			}else{
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
				$editdetail = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit Detail</a>';
        	}
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_biaya/mst_coa.php?action=delete&id='.$line['id'].'\',\'table_mst_coa\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['noakun'],
                $line['nama'],
                $line['jenis'],
                $edit,
                $editdetail,
				// $delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'mst_coa_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'mst_coa_form.php';exit();
		exit;
	}
    elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$where = "WHERE mst.deleted=0 AND mst.id = '".$id."' ORDER by det.noakun ASC";
        $sql_sub ="SELECT det.id, det.noakun, det.nama FROM `det_coa` det LEFT JOIN `mst_coa` mst ON mst.id=det.id_parent ".$where;
		$q = $db->query($sql_sub);
		
		$count = $q->rowCount();
		
		$q = $db->query($sql_sub);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['noakun'],
                $line['nama'],   
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE account_balance SET deleted=? WHERE id=?");
		$stmt->execute(array(1, $_GET['id']));

        $stmt = $db->prepare("UPDATE mst_coa SET deleted=? WHERE id=?");
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
            $stmt = $db->prepare("UPDATE account_balance SET noakun=?,nama=?,jenis=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['noakun'],$_POST['nama'],$_POST['jenis'],$_SESSION['user']['username'], $_POST['id']));

            $stmt = $db->prepare("UPDATE jurnal_detail SET nama_akun=?,user=?, lastmodified = NOW() WHERE no_akun=?");
			$stmt->execute(array($_POST['nama'],$_SESSION['user']['username'], $_POST['noakun']));
            
			$stmt = $db->prepare("UPDATE mst_coa SET noakun=?,nama=?,jenis=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['noakun'],$_POST['nama'],$_POST['jenis'],$_SESSION['user']['username'], $_POST['id']));
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
			$stmt = $db->prepare("INSERT INTO  account_balance(`noakun`,`nama`,`jenis`,`user`,`lastmodified`) VALUES( ?,?,?,?,NOW())");
			//var_dump($stmt);die;
			if($stmt->execute(array($_POST['noakun'],$_POST['nama'],$_POST['jenis'],$_SESSION['user']['username']))) {

				$stmt = $db->prepare("INSERT INTO  mst_coa(`noakun`,`nama`,`jenis`,`user`,`lastmodified`) VALUES( ?,?,?,?,NOW())");
                if($stmt->execute(array($_POST['noakun'],$_POST['nama'],$_POST['jenis'],$_SESSION['user']['username']))) {
                    $r['stat'] = 1;
                    $r['message'] = 'Success';
                }
                else {
                    $r['stat'] = 0;
                    $r['message'] = 'Failed';
                }
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
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Nomor / Nama Akun</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required"   id="filter_coa" name="filter_coa">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadCOA()" class="btn" type="button">Cari</button>
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
            // $id = $stats['id'];
        $statusToko = $stats['status'];
    }   
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_biaya/mst_coa.php?action=add\',\'table_mst_coa\')" class="btn">Tambah</button>';
	}}
?>
</div>
<table id="table_mst_coa"></table>
<div id="pager_table_mst_coa"></div>

<script type="text/javascript">
    function gridReloadCOA(){
        var filter = $("#filter_coa").val();
        
        var v_url ='<?php echo BASE_URL?>pages/master_biaya/mst_coa.php?action=json&filter='+filter;
        jQuery("#table_mst_coa").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
    }
    
    $(document).ready(function(){

        $("#table_mst_coa").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_biaya/mst_coa.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Account Number','Account Name','Type','Edit','Edit Detail'],
            colModel:[
                {name:'ID',index:'id', width:1, hidden: true , searchoptions: {sopt:['cn']}},
                {name:'noakun',index:'noakun', width:170, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', width:250, searchoptions: {sopt:['cn']}},
                {name:'jenis',index:'jenis', align:'center',width:70, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'EditDetail',index:'EditDetail', align:'center', width:50, sortable: false, search: false},
                // {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_mst_coa',
            sortname: 'noakun',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Chart Of Account",
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/master_biaya/mst_coa.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Account Number','Account Name'], 
			            		width : [40,100,300],
			            		align : ['center','left','left'],
			            	} 
			            ],
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_mst_coa").jqGrid('navGrid','#pager_table_mst_coa',{edit:false,add:false,del:false, search: false});
    })
</script>