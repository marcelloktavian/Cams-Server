<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       //$where = "WHERE p.deleted=0 ";
       $where = "WHERE TRUE AND p.deleted = 0 AND p.faktur > 0 ";
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
	  $where = sprintf(" where (p.deleted=0) AND (p.faktur = 1) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     }
//---------------------------------------------------------------------------------
        //$where = "WHERE TRUE AND p.deleted = 0 AND p.faktur = 1 ";
        //$where = "WHERE TRUE ";
        $q = $db->query("SELECT p.*, j.namaperusahaan as supplier 
							 FROM `trbeli` p Left Join `tblsupplier` j on (p.id_supplier=j.id)".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT p.*, j.namaperusahaan as supplier 
							 FROM `trbeli` p Left Join `tblsupplier` j on (p.id_supplier=j.id) 
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			$allowDelete = array(1,2,3);
			if($line['faktur'] == 2) {
					$stock = 'STOCKED';
			}
			else {
				$allowStock = array(1,2,3);
				if(in_array($_SESSION['user']['access'], $allowStock)) {
				$stock = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/terima_barang/terima_barang.php?action=stock&id='.$line['id_trans'].'\',\'table_terimabrg\')" href="javascript:;">STOCK</a>';
				}
				else {
				$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">STOCK</a>';
				}
			}				
			
			
			if(in_array($_SESSION['user']['access'], $allowDelete)){
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/terima_barang/terima_barang.php?action=delete&id='.$line['id_trans'].'\',\'table_terimabrg\')" href="javascript:;">Delete</a>';
			}
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['supplier'],                
                $line['tgl_trans'],
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['totalfaktur'],0),
				$stock,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trbeli_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trbeli_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_detail'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nm_barang'],
                $line['harga'],
                $line['qty'],                
                $line['subtotal'],                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'trbeli_formB.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'trbeli_form_edit.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'stock') {
		$stmt = $db->prepare(" call insertToStok(?,?,now())");
		$stmt->execute(array($_GET['id'],$_SESSION['user']['user_id']));
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("DELETE FROM stok_barang WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		$stmt = $db->prepare("UPDATE trbeli set faktur=1 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		$affected_rows = $stmt->rowCount();
		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed  (Barang belum masuk stok)';
		}
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processadd') {
     
		$stmt = $db->prepare("INSERT INTO trbeli(`kode`,`id_supplier`,`keterangan`,`tgl_trans`,`tgl_ship`,`total_qty`,`user_create`,`create_date`) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['kode'],$_POST['id_supplier'],$_POST['keterangan'],$_POST['tgl_trans'],$_POST['tgl_ship'],$_POST['total_qty'],$_SESSION['user']['user_id']))) {
			$projectId = $db->lastInsertId();
			foreach($_POST['KODE'] as $k => $v) {
			//foreach($_POST['city_id'] as $k => $v) {
				$pd = $db->prepare("INSERT INTO trbeli_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
				$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
			}
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
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processedit') {
		$stmt = $db->prepare("DELETE FROM trbeli_det WHERE project_id=:id");
		$stmt->bindValue(':id', $_POST['project_id'], PDO::PARAM_INT);
		$stmt->execute();
		$affected_rows = $stmt->rowCount();
				
		$stmt = $db->prepare("UPDATE trbeli SET kode=?,id_supplier=?,keterangan=?, tgl_trans=?, tgl_ship=?, total_qty=?, user_update=?, update_date = NOW() WHERE project_id=?");
		$stmt->execute(array($_POST['kode'],$_POST['id_supplier'], $_POST['keterangan'], $_POST['tgl_trans'], $_POST['tgl_ship'], $_POST['total_qty'], $_SESSION['user']['user_id'], $_POST['project_id']));
		$affected_rows = $stmt->rowCount();
		
		//$stmt = $db->prepare("UPDATE project SET ");
		$projectId = $_POST['project_id'];
		foreach($_POST['city_id'] as $k => $v) {
			$pd = $db->prepare("INSERT INTO trbeli_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
			$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
		}
		$r['stat'] = 1;
		$r['message'] = 'Success';
		
		
		echo json_encode($r);
		exit;
	}
?>
<table id="table_terimabrg"></table>
<div id="pager_table_terimabrg"></div>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/terima_barang/terima_barang_detail.php', 
  'windowname1', 
  'width=800, height=400,scrollbars=yes'); 
   return false;">
   <button class="btn btn-success">Tambah</button></a></br>
-->
<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';
		
	}
	*/
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_terimabrg").jqGrid({
            url:'<?php echo BASE_URL.'pages/terima_barang/terima_barang.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Supplier','Tanggal Pengiriman','Tanggal Penerimaan','Total Qty','Total Faktur','Stock','Delete'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:100, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:300, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:150, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'tgl_terima',index:'tgl_terima', width:150, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'stock',index:'stock', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_terimabrg',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penerimaan Barang",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/terima_barang/terima_barang.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Barang','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,300,50,50,50],
			            		align : ['right','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        //$("#table_beli").jqGrid('filterToolbar',{stringResult: true});
		$("#table_terimabrg").jqGrid('navGrid','#pager_table_terimabrg',{edit:false,add:false,del:false});
    })
</script>