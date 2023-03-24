<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, B2BProductsGroup, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, B2BProductsGroup, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, B2BProductsGroup, $group_acess);

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
	  $where = sprintf(" where a.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], "a.".$_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $sql_products ="SELECT a.*,a.nama as pd,c.nama as kategori FROM `mst_b2bproductsgrp` a left join mst_category c on a.id_category=c.id ";
		//var_dump($sql_products.$where);
		//die;
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
				//$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_b2b/b2bproducts.php?action=edit&id='.$line['id'].'\',\'table_b2bproducts\')" href="javascript:;">Edit</a>';
				$edit = '<a onclick="window.open(\''.BASE_URL.'pages/master_b2b/b2bproductsgrp_detail_edit.php?ids='.$line['id'].'\',\'table_b2bproductsgrp\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_b2b/b2bproductsgrp.php?action=delete&id='.$line['id'].'\',\'table_b2bproductsgrp\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
				$line['kode'],
				$line['nama'],
                number_format($line['harga']),                
                number_format($line['totalqty']),                
                $line['note'],                
                $line['kategori'],                
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$where = "WHERE pd.deleted=0 AND pd.id_productsgrp = '".$id."' ";
        $sql_sub ="SELECT pd.*,p.nama as produk FROM `mst_b2bproductsgrp_detail` pd INNER JOIN `mst_b2bproductsgrp` grp ON pd.id_productsgrp=grp.id LEFT JOIN `mst_b2bproducts` p ON pd.id_product=p.id ".$where;
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
                $line['produk'],
                $line['size'],                                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'b2bproductsgrp_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'b2bproductsgrp_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_b2bproductsgrp SET deleted=? WHERE id=?");
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
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah Product</button>';
    }else{
    if ($allow_add) {
        ?>
        <a href="javascript: void(0)" onclick="window.open('pages/master_b2b/b2bproductsgrp_detail.php');">
        <button class="btn btn-success">Tambah Product</button></a>
        <?php
    }}
    ?>

<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_b2b/b2bproducts.php?action=add\',\'table_b2bproductskat\')" class="btn">Tambah</button>';
	}
	*/
	
?>
</div>
<table id="table_b2bproductsgrp"></table>
<div id="pager_table_b2bproductsgrp"></div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#table_b2bproductsgrp").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_b2b/b2bproductsgrp.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Code','Name','Price','Total Product','Note','Category','Edit','Delete'],
            colModel:[
                {name:'id',index:'id', align:'right',width:30, searchoptions: {sopt:['cn']}},
                {name:'kode',index:'kode', align:'right', width:30, searchoptions: {sopt:['cn']}},                
                {name:'nama',index:'nama', width:300, searchoptions: {sopt:['cn']}},                
                {name:'harga',index:'harga', align:'center', width:70, searchoptions: {sopt:['cn']}},                
                {name:'totalqty',index:'totalqty', align:'center', width:55, searchoptions: {sopt:['cn']}},                
                {name:'note',index:'note', align:'center', width:130, searchoptions: {sopt:['cn']}},                
                {name:'c.nama',index:'c.nama', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_b2bproductsgrp',
            sortname: 'id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Master B2B Products Groups",
			subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/master_b2b/b2bproductsgrp.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Nama','Size'], 
			            		width : [40,300,100],
			            		align : ['right','left','right'],
			            	} 
			            ],
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_b2bproductsgrp").jqGrid('navGrid','#pager_table_b2bproductsgrp',{edit:false,add:false,del:false});
    })
</script>