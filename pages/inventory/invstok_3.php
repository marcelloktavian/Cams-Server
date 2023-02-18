<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, INVENTORY, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];
	   /*
	   if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
	   $where = "WHERE TRUE AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang= 0) and (p.deleted=0) ";
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
		$where = sprintf(" where TRUE AND (p.totalqty <> 0) AND (p.state ='0') AND (p.piutang= 0) and (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
		}
        */
		$startdate = isset($_GET['startdated'])?$_GET['startdated']:date('Y-m-d');
		$filter=$_GET['filter'];
		
		$page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'lastmodified'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 

        //$where = " WHERE TRUE ";
        $where = " WHERE TRUE AND p.deleted=0 AND p.size is not null AND p.size <> '' ";
		/*
		if($startdate != null){
			//$where .= " AND DATE(inv.lastmodified) <= STR_TO_DATE('$startdate','%d/%m/%Y') ";	
			$where .= " AND DATE(p.lastmodified) <= ('$startdate')  ";	
		}
		*/
		if(($startdate != null) && ($filter != null)){
			//$where .= " AND DATE(inv.lastmodified) <= STR_TO_DATE('$startdate','%d/%m/%Y') ";	
			$where .= " AND DATE(p.lastmodified) <= STR_TO_DATE('$startdate','%d/%m/%Y') AND ((p.nama like '%$filter%') or (p.id like '%$filter%') or (p.kode like '%$filter%')) ";
			// $where .= " AND DATE(p.lastmodified) <= ('$startdate')  ";			
		}
		else if(($startdate != null)){
			$where .= " AND DATE(p.lastmodified) <= STR_TO_DATE('$startdate','%d/%m/%Y') ";	
			// $where .= " AND DATE(p.lastmodified) <= ('$startdate')  ";	
		}
		/*
		else
		{
		$where .= " AND DATE(p.lastmodified) <= ('$startdate') ";
		}
		*/
		
		
		// $sql= "SELECT p.*,inv.stok,inv.lastmodified FROM mst_products p LEFT JOIN (SELECT i.id_product,i.lastmodified,SUM(i.qty) AS stok FROM inventory i".$where_date. "GROUP BY i.id_product) AS inv ON p.id=inv.id_product". $where;
		
		$sql = "SELECT p.* FROM `inventory_balance` p ".$where; 
		// var_dump($sql);die;      
		$q = $db->query($sql);
		$count = $q->rowCount();
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;

		$q = $db->query($sql."
			ORDER BY `".$sidx."` ".$sord."
			LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);


		$responce['page'] = $page;
		$responce['total'] = $total_pages;
		$responce['records'] = $count;
		$i=0;
		$grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
		foreach($data1 as $line) {
			$stmt3 = $db->query("SELECT * FROM inventory i  WHERE i.id_product='".$line['id']."' and `update`=0");
			if ($stmt3->rowCount() > 0) {
				if ($allow_post) {
					$edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invstok.php?action=update_stok&id='.$line['id'].'\',\'table_inventory\')" href="javascript:;">UPDATE</a>';
				}else{
					$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Update Data\')" href="javascript:;">UPDATED</a>';	
				}

			}else{
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Update Data\')" href="javascript:;">UPDATED</a>';
			}
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);

			// if($allow_post){
			// 	$edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invstok.php?action=update_stok&id='.$line['id'].'\',\'table_inventory\')" href="javascript:;">UPDATE</a>';
			// }
			// else
			// 	$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">UPDATED</a>';
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invstok.php?action=delete&id='.$line['id'].'\',\'table_jual\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';
			
			    //$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			$totalqty=0;

			$responce['rows'][$i]['id']   = $line['id'];
			$responce['rows'][$i]['cell'] = array(
				$line['id'],
				$line['nama'],                
				$line['size'],
				number_format($line['stok'],0),
				number_format($line['limit_stok'],0),
				$line['lastmodified'],
				$edit,
			);
			$grand_qty+=$line['stok'];
			$i++;
		}
		
		$responce['userdata']['stok'] = number_format($grand_qty,0);
		
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'update_stok') {
		//update nilai stok,krn reload grid
		$idprod = isset($_GET['id'])?$_GET['id']:'';

		$stmt1 = $db->query("SELECT * FROM inventory i  WHERE i.id_product='".$idprod."' and `update`=0");
		if ($stmt1->rowCount() > 0) {
			$stmt = $db->query("UPDATE inventory_balance ib SET ib.stok=IFNULL(ib.stok,0)+(SELECT SUM(i.qty) FROM inventory i  WHERE i.id_product='".$idprod."' and `update`=0),ib.lastmodified=NOW() where ib.id='".$idprod."'");
			$stmt2 = $db->query("UPDATE inventory set `update`=1 where id_product='".$idprod."'");	

			$affected_rows = $stmt->rowCount();
			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}else{
			$r['stat'] = 0;
			$r['message'] = 'Barang Sudah di Update';
		}
		
		
		
		echo json_encode($r);

		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'update_stok_all') {
		//update nilai stok,krn reload grid
		$stmt1 = $db->query("SELECT id_product FROM inventory i  WHERE `update`=0");
		if ($stmt1->rowCount() > 0) {
			// $data0 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

			// foreach($data0 as $line0){
				$stmt = $db->query("UPDATE inventory_balance ib SET ib.stok=IFNULL(ib.stok,0)+IFNULL((SELECT SUM(i.qty) FROM inventory i  WHERE i.id_product=ib.id and `update`=0),0),ib.lastmodified=NOW() ");
			// }

			$stmt2 = $db->query("UPDATE inventory set `update`=1");

			$affected_rows = $stmt2->rowCount();
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		// $startdate = isset($_GET['startdated'])?$_GET['startdated']:'';
		$id = $_GET['id'];
		//$id = $line['id_trans'];
		$where = "WHERE pd.id_product = '".$id."'";
		$q = $db->query("SELECT pd.* FROM `inventory` pd ".$where);
		// var_dump("SELECT pd.* FROM `inventory` pd ".$where);
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
		$i=0;
		$responce = '';
		foreach($data1 as $line){
			$responce->rows[$i]['id']   = $line['id_product'];
			$responce->rows[$i]['cell'] = array(
				$i+1,
				$line['id_trans'],
				$line['lastmodified'],
				$line['namabrg'],
				$line['size'],
				number_format($line['qty'],0),                
			);
			$i++;
		}
		echo json_encode($responce);
		exit;
	}	  
	?>

	<div class="ui-widget ui-form" style="margin-bottom:5px">
		<div class="ui-widget-header ui-corner-top padding5">
			Filter Data Stok
		</div>
		<div class="ui-widget-content ui-corner-bottom">
			<form id="report_project_form" method="" action="" class="ui-helper-clearfix">
				<label for="project_id" class="ui-helper-reset label-control">Tanggal s.d.</label>
				<div class="ui-corner-all form-control">

					<input value="" type="text" class="required datepicker"   id="startdateinv" name="startdateinv">
					Filter
					<input value="" type="text" id="filter_stok" name="filter_stok">(ID/Kode Barang,Nama_Barang)

					<button onclick="gridReload()" class="btn" type="button">Cari</button>
					<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/inventory/invstokrpt.php?action=preview&start='+$('#startdateinv').val()+'&filter='+$('#filter_stok').val())" class="btn" type="button">Print</button>
					<?php
					// echo '<button type="button" onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invstok.php?action=update_stok_all\',\'table_inventory\')" class="btn">Update Stok</button>';
		/* dimatikan krn update stok all dipindah ke nilai barang masing masing
		$allow = array(1,2,3);
		if(in_array($_SESSION['user']['access'], $allow)) {
			echo '<button type="button" onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invstok.php?action=update_stok\',\'table_inventory\')" class="btn">Update Stok</button>';
		}
       */		
		?>   
	</div>
</form>
</div>
</div> 

<table id="table_inventory"></table>
<div id="pager_table_inventory"></div>



<script type="text/javascript">
	$('#startdateinv').datepicker({
		dateFormat: "dd/mm/yy"
		//dateFormat: "yy-mm-dd"
	});
	
	$( "#startdateinv" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );

	function gridReload(){
		var v_startdate = $("#startdateinv").val();
		var v_filter = $("#filter_stok").val();
		var v_url ='<?php echo BASE_URL?>pages/inventory/invstok.php?action=json&startdated='+v_startdate+'&filter='+v_filter;

		// console.log(v_url);
		jQuery("#table_inventory").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
	$(document).ready(function(){
		$("#table_inventory").jqGrid({
			url:'<?php echo BASE_URL.'pages/inventory/invstok.php?action=json&filter='; ?>',
			datatype: "json",
			colNames:['ID','Nama Barang','Size','Stok','Limit Stok','Lastmodified','UPDATE'],
			colModel:[
			{name:'id_product',index:'id_product', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
			{name:'namabrg',index:'namabrg', width:125, search:true, stype:'text', align:'center', searchoptions:{sopt:['cn']}},
			{name:'size',index:'size', width:40, align:'center', searchoptions: {sopt:['cn']}},         
			{name:'stok',index:'stok', align:'center', width:40,searchoptions: {sopt:['cn']}},
			{name:'limit_stok',index:'limit_stok', align:'center', width:40,searchoptions: {sopt:['cn']}},
			{name:'lastmodified',index:'lastmodified', align:'center', width:80,searchoptions: {sopt:['cn']}},
			{name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
			],
			rowNum:20,
			rowList:[10,20,30],
			pager: '#pager_table_inventory',
			sortname: 'nama',
			autowidth: true,
			height: '500',
			viewrecords: true,
			rownumbers: true,
			sortorder: "asc",
			caption:"DATA STOK INVENTORY",
			ondblClickRow: function(rowid) {
				alert(rowid);
			},
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/inventory/invstok.php?action=json_sub'; ?>',
			subGridModel: [
			{ 
				name : ['No','ID_trans','Tanggal','Nama Barang','Size','Qty(pcs)'], 
				width : [30,80,160,300,50,50],
				align : ['right','center','center','center','center','right'],
			} 
			],
		});
		$("#table_inventory").jqGrid('navGrid','#pager_table_inventory',{edit:false,add:false,del:false,search:false});

	})
</script>