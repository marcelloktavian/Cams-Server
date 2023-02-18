<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, Products, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, Products, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, Products, $group_acess);

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
        $sql_products ="SELECT a.*,c.nama as kategori FROM `mst_products` a left join mst_category c on a.id_category=c.id ";
		//$q = $db->query("SELECT * FROM `mst_products` a ".$where);
		$q = $db->query($sql_products.$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql_products.$where."
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
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/products.php?action=edit&id='.$line['id'].'\',\'table_products\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_online/products.php?action=delete&id='.$line['id'].'\',\'table_products\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
				$line['oln_product_id'],
				$line['nama'],
                number_format($line['harga']),                
                $line['size'],                
                $line['type'],                
                $line['kategori'],                
                $line['aktif'],                
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'products_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'products_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_products SET deleted=?,aktif='N' WHERE id=?");
		$stmt->execute(array(1, $_GET['id']));
		
        $stmt = $db->prepare("UPDATE inventory_balance SET deleted=? WHERE id=?");
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


			$stmt = $db->prepare("UPDATE mst_products SET kode=?,oln_product_id=?,nama=?,harga=?,size=?,type=?,id_category=?,aktif=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array(strtoupper($_POST['kode']),$_POST['oln_product_id'],strtoupper($_POST['nama']),$_POST['harga'],$_POST['size'],strtoupper($_POST['type']),$_POST['id_category'],strtoupper($_POST['aktif']), $_SESSION['user']['username'], $_POST['id']));


            $stmt = $db->prepare("UPDATE inventory_balance SET kode=?,oln_product_id=?,nama=?,harga=?,size=?,type=?,id_category=?,user=?, lastmodified = NOW() WHERE kode=?");
            $stmt->execute(array(strtoupper($_POST['kode']),$_POST['oln_product_id'],strtoupper($_POST['nama']),$_POST['harga'],$_POST['size'],strtoupper($_POST['type']),$_POST['id_category'], $_SESSION['user']['username'], $_POST['kode2']));


			// var_dump($_POST['id']);die;
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
			$stmt = $db->prepare("INSERT INTO  mst_products(`kode`,`oln_product_id`,`nama`,`harga`,`size`,`type`,`id_category`,`aktif`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?,NOW())");
			if($stmt->execute(array(strtoupper($_POST['kode']),$_POST['oln_product_id'],strtoupper($_POST['nama']), $_POST['harga'],$_POST['size'],strtoupper($_POST['type']),$_POST['id_category'],strtoupper($_POST['aktif']),$_SESSION['user']['username']))) {

                //insert inventory_balance
                $stmt = $db->prepare("INSERT INTO  inventory_balance(`oln_product_id`,`kode`,`nama`,`harga`,`type`,`size`,`id_category`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?,NOW())");

                $stmt->execute(array($_POST['oln_product_id'],strtoupper($_POST['kode']),strtoupper($_POST['nama']), $_POST['harga'],strtoupper($_POST['type']),$_POST['size'],$_POST['id_category'],$_SESSION['user']['username']));


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
<table id="table_products"></table>
<div id="pager_table_products"></div>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/kirim/sodetail.php');">
   <button class="btn btn-success">Tambah</button></a>
-->
<?php
	
	// $allow = array(1,2,3);

	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/products.php?action=add\',\'table_products\')" class="btn">Tambah</button>';
	}
	
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_products").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_online/products.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','ID OLN','Name','Price','Size','Type','Category','Active','Edit','Delete'],
            colModel:[
                {name:'id',index:'id', align:'right',width:30, searchoptions: {sopt:['cn']}},
                {name:'a.oln_product_id',index:'a.oln_product_id', align:'right', width:30, searchoptions: {sopt:['cn']}},                
                {name:'a.nama',index:'a.nama', width:300, searchoptions: {sopt:['cn']}},                
                {name:'harga',index:'harga', align:'center', width:70, searchoptions: {sopt:['cn']}},                
                {name:'size',index:'size', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'type',index:'type', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'kategori',index:'kategori', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'aktif',index:'aktif', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_products',
            sortname: 'nama',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Products",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_products").jqGrid('navGrid','#pager_table_products',{edit:false,add:false,del:false});
    })
</script>