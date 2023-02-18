<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
        //$f_nama  ="and ".$_GET['f_nama'];

        if(!$sidx) $sidx=1;
//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE p.deleted=0 ";
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
	  $where = sprintf(" where p.deleted=0 AND p.%s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		

        //$where = "WHERE p.deleted=0 ";
        //$where = " ";
        $q = $db->query("SELECT * FROM `barang` p ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT p.id,p.id_barang, p.nm_barang,j.nm_jenis,p.hrg_beli,p.hrg_jual,p.stok FROM `barang` p Left Join `jenis_barang` j on (p.id_jenis=j.id_jenis) 
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master/barang.php?action=edit&id='.$line['id'].'\',\'table_barang\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master/barang.php?action=delete&id='.$line['id'].'\',\'table_barang\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['id_barang'],                
                $line['nm_barang'],                
                $line['hrg_beli'],                
                $line['hrg_jual'],                
                $line['stok'],                
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'barang_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'barang_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE barang SET deleted=? WHERE id=?");
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
			$stmt = $db->prepare("UPDATE barang SET nm_barang=?,id_barang=?,id_jenis=?,hrg_jual=?,hrg_beli=?,stok=?, user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['nama'],$_POST['idbarang'],$_POST['id_jenis'],$_POST['hjual'],$_POST['hbeli'],$_POST['stok'], $_SESSION['user']['username'], $_POST['id']));
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
			$stmt = $db->prepare("INSERT INTO barang(`nm_barang`,`id_barang`,`id_jenis`,`hrg_beli`,`hrg_jual`,`stok`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['nama'],$_POST['idbarang'],$_POST['id_jenis'],$_POST['hbeli'],$_POST['hjual'],$_POST['stok'],$_SESSION['user']['username']))) {
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
<table id="table_barang"></table>
<div id="pager_table_barang"></div>
<div class="btn_box">
<?php
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master/barang.php?action=add\',\'table_barang\')" class="btn">Tambah</button>';
	}
?>
</div>


<script type="text/javascript">
    $(document).ready(function(){

        $("#table_barang").jqGrid({
            url:'<?php echo BASE_URL.'pages/master/barang.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Kode Barang','Nama Barang','Harga Beli','Harga Jual','Stok','Edit','Delete'],
            colModel:[
                {name:'ID',index:'id', width:50, searchoptions: {sopt:['cn']}},
                
				{name:'id_barang',index:'id_barang', width:70, searchoptions: {sopt:['cn']}},                
                {name:'nm_barang',index:'nm_barang', width:200, searchoptions: {sopt:['cn']}},                
                {name:'hrg_beli',index:'hrg_beli', width:100, searchoptions: {sopt:['cn']}},                
                {name:'hrg_jual',index:'hrg_jual', width:100, searchoptions: {sopt:['cn']}},                
                {name:'stok',index:'stok', width:80, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_barang',
            sortname: 'id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Barang",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
			
			
        });
        $("#table_barang").jqGrid('navGrid','#pager_table_barang',{edit:false,add:false,del:false});
		
    })
</script>