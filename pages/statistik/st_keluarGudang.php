<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       $where = "WHERE TRUE AND sg.deleted = 0 ";
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
	  $where = sprintf(" where (p.deleted=0) AND id_trans like 'PKB%' AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     }
	 
	 $q = $db->query("SELECT * FROM `stok_gudang` sg Left Join `barang` b on (sg.id_barang=b.id_barang) ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("Select b.id,sg.id_barang,sum(sg.stok) FROM `stok_gudang` sg Left Join `barang` b on (sg.id_barang=b.id_barang) ".$where." GROUP BY b.id ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				if($line['is_start'] == 0) {
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/statistik/statistik_outgudang.php?ids='.$line['id_trans'].'\',\'table_statistikout\')" href="javascript:;">Nota</a>';
				}
				else {
					$edit = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}				
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				if($line['is_start'] == 0) {
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/laporan/report_gudang.php?action=delete&id='.$line['id_trans'].'\',\'table_stokgudang\')" href="javascript:;">Delete</a>';
				}
				else {
					$delete = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
        	$responce['rows'][$i]['id']   = $line['id_barang'];
            $responce['rows'][$i]['cell'] = array(
                $line['nomor'],
                $line['id_barang'],
                $line['nm_barang'],                
                number_format($line['stok'],0),
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
		
		$where = "WHERE sb.id_barang = '".$id."' order by sb.id desc";
        $q = $db->query("SELECT  sb.id_barang,b.nm_barang,sb.tgl_trans,sb.stok,sb.id_trans FROM `stok_gudang` sb INNER JOIN `barang` b ON (sb.id_barang=b.id_barang) ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT  sb.id_barang,b.nm_barang,sb.tgl_trans,sb.stok,sb.id_trans FROM `stok_gudang` sb INNER JOIN `barang` b ON (sb.id_barang=b.id_barang) ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_barang'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nm_barang'],
                $line['tgl_trans'],
                $line['stok'],                
                $line['id_trans'],                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	/* tidak dipakai karena menggunakan form detail
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'beli_formB.php';exit();
		exit;
		
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'beli_form_edit.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE jual SET deleted=? WHERE project_id=?");
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
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processadd') {
     
		$stmt = $db->prepare("INSERT INTO beli(`kode`,`id_supplier`,`keterangan`,`tgl_trans`,`tgl_ship`,`total_qty`,`user_create`,`create_date`) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['kode'],$_POST['id_supplier'],$_POST['keterangan'],$_POST['tgl_trans'],$_POST['tgl_ship'],$_POST['total_qty'],$_SESSION['user']['user_id']))) {
			$projectId = $db->lastInsertId();
			foreach($_POST['KODE'] as $k => $v) {
			//foreach($_POST['city_id'] as $k => $v) {
				$pd = $db->prepare("INSERT INTO beli_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
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
		$stmt = $db->prepare("DELETE FROM beli_det WHERE project_id=:id");
		$stmt->bindValue(':id', $_POST['project_id'], PDO::PARAM_INT);
		$stmt->execute();
		$affected_rows = $stmt->rowCount();
				
		$stmt = $db->prepare("UPDATE beli SET kode=?,id_supplier=?,keterangan=?, tgl_trans=?, tgl_ship=?, total_qty=?, user_update=?, update_date = NOW() WHERE project_id=?");
		$stmt->execute(array($_POST['kode'],$_POST['id_supplier'], $_POST['keterangan'], $_POST['tgl_trans'], $_POST['tgl_ship'], $_POST['total_qty'], $_SESSION['user']['user_id'], $_POST['project_id']));
		$affected_rows = $stmt->rowCount();
		
		//$stmt = $db->prepare("UPDATE project SET ");
		$projectId = $_POST['project_id'];
		foreach($_POST['city_id'] as $k => $v) {
			$pd = $db->prepare("INSERT INTO beli_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
			$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
		}
		$r['stat'] = 1;
		$r['message'] = 'Success';
		
		
		echo json_encode($r);
		exit;
	}
	*/
?>
<table id="table_stokgudang"></table>
<div id="pager_table_stokgudang"></div>
<div class="btn_box">
<a href="javascript: void(0)" 
   onclick="window.open('pages/laporan/report_gudang.php', 
  'windowname1', 
  'width=800, height=400,scrollbars=yes'); 
   return false;">
   <button class="btn btn-success">Tambah</button></a></br>
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

        $("#table_stokgudang").jqGrid({
            url:'<?php echo BASE_URL.'pages/laporan/report_gudang.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['Nomor','ID','Nama Barang','Stok'],
            colModel:[
                {name:'nomor',index:'j.id', width:100, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'id_barang',index:'p.id_barang', width:100, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'nm_barang',index:'j.nm_barang', width:300, searchoptions: {sopt:['cn']}},                
                {name:'stok',index:'(p.stok)', align:'right', width:100, searchoptions: {sopt:['cn']}},
                
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_stokgudang',
            sortname: 'nomor',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Laporan Stok GUDANG",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/laporan/report_gudang.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Barang','Tgl_Trans','Qty(pcs)','ID_trans'], 
			            		width : [40,300,50,50,50],
			            		align : ['right','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_stokgudang").jqGrid('navGrid','#pager_table_stokgudang',{edit:false,add:false,del:false});
    })
</script>