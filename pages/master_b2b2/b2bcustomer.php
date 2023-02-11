<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, B2BCustomer, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, B2BCustomer, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, B2BCustomer, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE a.deleted=0 ";
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
	  $where = sprintf(" where a.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $sql_b2bcustomer ="SELECT a.* FROM `mst_b2bcustomer` a ";
		
		$q = $db->query($sql_b2bcustomer.$where);
		//var_dump($sql_b2bcustomer);die;
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql_b2bcustomer.$where."
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
				 $edit = '<a onclick="window.open(\''.BASE_URL.'pages/master_b2b/b2bcustomer_edit.php?ids='.$line['id'].'\',\'table_b2bcustomer\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_b2b/b2bcustomer.php?action=delete&id='.$line['id'].'\',\'table_b2bcustomer\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
				$line['nama'],
				$line['alamat'],
				$line['no_telp'],
				$line['hp'],
				$line['totalqty'],
                $edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'b2bcustomer_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'b2bcustomer_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_b2bcustomer SET deleted=? WHERE id=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.b2bcustomer_id = '".$id."' ";
        $sql_detail="SELECT pd.*,b.nama as produk FROM `mst_b2bcustomer_product` pd INNER JOIN `mst_b2bcustomer` bc ON (pd.b2bcustomer_id=bc.id) LEFT JOIN `mst_b2bproducts` b ON pd.products_id=b.id ".$where;
		
		$q = $db->query($sql_detail);
		$count = $q->rowCount();
		$q = $db->query($sql_detail);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['b2bcustomer_detail_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['products_id'],
                $line['nama_produk'],
                number_format($line['price'],0),                
                number_format($line['disc'],0),                
                number_format($line['nett_price'],0),                
                            
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
?>
<table id="table_b2bcustomer"></table>
<div id="pager_table_b2bcustomer"></div>
<div class="btn_box">
<?php
if ($allow_add) {
   ?>
    <a href="javascript: void(0)" onclick="window.open('pages/master_b2b/b2bcustomer_detail.php');">
   <button class="btn btn-success">Tambah</button></a>
   <?php
}
?>


 <?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_b2b/b2bcustomer.php?action=add\',\'table_b2bcustomer\')" class="btn">Tambah</button>';
	}
	*/
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_b2bcustomer").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_b2b/b2bcustomer.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Name','Address','telp','HP','Products','Edit','Delete'],
            colModel:[
                {name:'id',index:'id', align:'right',width:30, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', width:100, searchoptions: {sopt:['cn']}},                
                {name:'alamat',index:'alamat', align:'center', width:350, searchoptions: {sopt:['cn']}},                
                {name:'no_telp',index:'no_telp', align:'center', width:100, searchoptions: {sopt:['cn']}},                
                {name:'hp',index:'hp', align:'center', width:100, searchoptions: {sopt:['cn']}},                
                {name:'totalqty',index:'totalqty', align:'center', width:100, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_b2bcustomer',
            sortname: 'nama',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"B2B Customers Data",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/master_b2b/b2bcustomer.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','ID','Product','Price','Disc','NettPrice'], 
			            		width : [40,40,300,100,100,100],
			            		align : ['right','right','left','right','right','right'],
			            	} 
			            ],
        });
        $("#table_b2bcustomer").jqGrid('navGrid','#pager_table_b2bcustomer',{edit:false,add:false,del:false});
    })
</script>