<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, COA, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, COA, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, COA, $group_acess);

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
        $sql_products ="SELECT a.* FROM `mst_coa` a ";
		// var_dump($sql_products.$where);
		// die;
		//$q = $db->query("SELECT * FROM `mst_products` a ".$where);

        $query = '';
        $countnya = 0;

        $q = $db->query($sql_products.' where a.deleted=0');
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach($data1 as $line) {
            if ($countnya == 0) {
                if ($_REQUEST["_search"] == "false") {
                    $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                }else{
                    $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                    $query .= sprintf(" AND %s ".$operations[$_REQUEST["searchOper"]], "".$_REQUEST["searchField"], $value);
                }
                
            } else {
                if ($_REQUEST["_search"] == "false") {
                    $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                }else{
                    $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                    $query .= sprintf(" AND %s ".$operations[$_REQUEST["searchOper"]], "".$_REQUEST["searchField"], $value);
                }
            }
            $countnya++;
            $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
		    $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
            foreach($data2 as $line2) {
                if ($_REQUEST["_search"] == "false") {
                $query .= " UNION ALL select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                }else{
                    $query .= " UNION ALL select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                    $query .= sprintf(" AND %s ".$operations[$_REQUEST["searchOper"]], "".$_REQUEST["searchField"], $value);
                }
            }
            
        }
        // var_dump($query);die;

        $q = $db->query($query);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;
        

        $q = $db->query($query."
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
			if($allow_edit){
                if ($line['jenis'] != '') {
                    $edit = '<a onclick="window.open(\''.BASE_URL.'pages/master_biaya/coa_detail_edit.php?ids='.$line['id'].'\',\'table_coa\')" href="javascript:;">Edit</a>';
                }else{
                    $edit = '';
                }
				
            }else{
                if ($line['jenis'] != '') {
                    $edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
                }else{
                    $edit = '';
                }
            }

			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_biaya/coa.php?action=delete&id='.$line['id'].'\',\'table_coa\')" href="javascript:;">Delete</a>';
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
            );
            $i++;

            // $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
		    // // $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
            // // foreach($data2 as $line2) {
            //     $noarray = 0;
            //     while($data2 = $q2->fetchAll(PDO::FETCH_ASSOC)){
            //     $responce['rows'][$i]['id']   = $data2[$noarray]['id'];
            //     $responce['rows'][$i]['cell'] = array(
            //     'tot '.$i,
            //     $data2[$noarray]['noakun'],
            //     $data2[$noarray]['nama'], 
            //     '',                
			// 	'',
            //     );
            //     $noarray;
            //     $i++;
            // }

        }
        echo json_encode($responce);
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
		$stmt = $db->prepare("UPDATE mst_jenisbiaya SET deleted=? WHERE id=?");
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
<table id="table_coa"></table>
<div id="pager_table_coa"></div>
<div class="btn_box">
    <?php
    // $statusToko = '';
    // $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    // $getStat->execute();
    // $stat = $getStat->fetchAll();
    // foreach ($stat as $stats) {
    //     $statusToko = $stats['status'];
    // }
    
    // if ($statusToko == 'Tutup') {
    //     echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah Product</button>';
    // }else{
    // if ($allow_add) {
    //     ?>
         <!-- <a href="javascript: void(0)" onclick="window.open('pages/master_biaya/coa.php');">
        <button class="btn btn-success">Tambah</button></a> -->
         <?php
    // }}
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
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_coa").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_biaya/coa.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            olNames:['ID','Account Number','Account Name','Type','Edit'],
            colModel:[
                {name:'ID',index:'id', width:70, searchoptions: {sopt:['cn']}},
                {name:'Account Number',index:'noakun', width:170, searchoptions: {sopt:['cn']}},
                {name:'Account Name',index:'nama', width:250, searchoptions: {sopt:['cn']}},   
                {name:'Type',index:'jenis', width:170, searchoptions: {sopt:['cn']}},            
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_coa',
            sortname: 'noakun',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "ASC",
            caption:"Chart Of Account",
			subGrid : false,
            subGridUrl : '<?php echo BASE_URL.'pages/master_biaya/coa.php?action=json_sub'; ?>',
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
        $("#table_coa").jqGrid('navGrid','#pager_table_coa',{edit:false,add:false,del:false});
    })
</script>