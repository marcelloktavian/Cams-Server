<?php require_once '../../include/config.php' ?>
<?php
//-----------inisialisasi sql-----------------------------------------------
$sql_data="SELECT `trgudang`.id_trans,`trgudang`.tgl_trans,`trgudang`.totalqty,`trgudang`.totalfaktur, `mst_gudang`.namaperusahaan,`trgudang`.faktur FROM `trgudang` Left Join `mst_gudang` on (`trgudang`.id_gudang=`mst_gudang`.id)";
//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE TRUE AND `trgudang`.deleted = 0 AND `trgudang`.faktur >= 0 ";
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
	  $where = sprintf(" where (`trgudang`.deleted=0) AND (`trgudang`.faktur >= 0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 }
//---------------------------------------------------------------------------------

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;             
	    //$q = $db->query("SELECT `trbeli`.*, `tblsupplier`.namaperusahaan FROM `trbeli` Left Join `tblsupplier` on (`trbeli`.id_supplier=`tblsupplier`.id)".$where);
	    $q = $db->query("$sql_data".$where);
        $count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        //$q = $db->query("SELECT `trbeli`.*, `tblsupplier`.namaperusahaan FROM `trbeli` Left Join `tblsupplier` on (`trbeli`.id_supplier=`tblsupplier`.id)".$where." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
        $q = $db->query("$sql_data".$where." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			$allowStock = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				if($line['is_start'] == 0) {
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/gudang/gudang_detail_edit.php?ids='.$line['id_trans'].'\',\'table_gudang\')" href="javascript:;">Edit</a>';
				}
				else {
					$edit = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}				
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($line['faktur'] == 2) {
					$stock = 'STOCKED';
			}
			else {
				$allowStock = array(1,2,3);
				if(in_array($_SESSION['user']['access'], $allowStock)) {
				$stock = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/gudang/gudang.php?action=stock&id='.$line['id_trans'].'\',\'table_gudang\')" href="javascript:;">STOCK</a>';
				}
				else {
				$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">STOCK</a>';
				}
			}
			
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				//bila fakturnya belum stocked maka bisa didelete
				if($line['faktur'] == 0) {
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/gudang/gudang.php?action=delete&id='.$line['id_trans'].'\',\'table_gudang\')" href="javascript:;">Delete</a>';
				}
				//bila fakturnya sudah stocked maka tidak bisa didelete
				else {
					$delete = '<a onclick="javascript:custom_alert(\'Faktur sudah terposting!\')" href="javascript:;">STOCKED</a>';
				}
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['namaperusahaan'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['totalfaktur'],0),
				$edit,
				$stock,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'preview') {
	    require_once '../../include/print.php';
		$p = new Printing;
		$p->customSql("$sql_data ");
		$p->lbField('ID','Supplier','Tgl.Transaksi','Total Qty','Total Faktur');
		$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">Laporan Pengiriman Barang</div>');
		$p->align('id_trans','center');
		$p->width('id_trans','150');
		$p->align('id_supplier','left');
		$p->align('tgl_trans','center');
		$p->width('totalqty','150');
		$p->align('totalqty','right');
		$p->width('totalfaktur','150');
		$p->align('totalfaktur','right');
		
		if(isset($_GET['type']) && $_GET['type'] == 'pdf') {
		    echo $p->draw('pdf');
		}
		elseif(isset($_GET['type']) && $_GET['type'] == 'xls') {
		    echo $p->draw('xls');
		}
		else {
		    echo $p->draw('html');
		}		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trgudang_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trgudang_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) ".$where);
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
		include 'gudang_formB.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'gudang_form_edit.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'stock') {
		$stmt = $db->prepare("call insertToGudang(?,?,now())");
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
		$stmt = $db->prepare("UPDATE trgudang SET deleted=? WHERE id_trans=?");
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
     
		$stmt = $db->prepare("INSERT INTO gudang(`kode`,`id_supplier`,`keterangan`,`tgl_trans`,`tgl_ship`,`total_qty`,`user_create`,`create_date`) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['kode'],$_POST['id_supplier'],$_POST['keterangan'],$_POST['tgl_trans'],$_POST['tgl_ship'],$_POST['total_qty'],$_SESSION['user']['user_id']))) {
			$projectId = $db->lastInsertId();
			foreach($_POST['KODE'] as $k => $v) {
			//foreach($_POST['city_id'] as $k => $v) {
				$pd = $db->prepare("INSERT INTO gudang_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
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
		$stmt = $db->prepare("DELETE FROM gudang_det WHERE project_id=:id");
		$stmt->bindValue(':id', $_POST['project_id'], PDO::PARAM_INT);
		$stmt->execute();
		$affected_rows = $stmt->rowCount();
				
		$stmt = $db->prepare("UPDATE gudang SET kode=?,id_supplier=?,keterangan=?, tgl_trans=?, tgl_ship=?, total_qty=?, user_update=?, update_date = NOW() WHERE project_id=?");
		$stmt->execute(array($_POST['kode'],$_POST['id_supplier'], $_POST['keterangan'], $_POST['tgl_trans'], $_POST['tgl_ship'], $_POST['total_qty'], $_SESSION['user']['user_id'], $_POST['project_id']));
		$affected_rows = $stmt->rowCount();
		
		//$stmt = $db->prepare("UPDATE project SET ");
		$projectId = $_POST['project_id'];
		foreach($_POST['city_id'] as $k => $v) {
			$pd = $db->prepare("INSERT INTO trgudang_det(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
			$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
		}
		$r['stat'] = 1;
		$r['message'] = 'Success';
		
		
		echo json_encode($r);
		exit;
	}
?>
<table id="table_gudang"></table>
<div id="pager_table_gudang"></div>
<div class="btn_box">
<a href="javascript: void(0)" 
   onclick="window.open('pages/gudang/gudang_detail.php', 
  'windowname1', 
  'width=1000, height=400,scrollbars=yes'); 
   return false;">
   <button class="btn btn-success">Tambah</button></a>
<a href="javascript: void(0)" 
   onclick="window_open('<?php echo BASE_URL ?>pages/gudang/gudang.php?action=preview&id='+$('#project_id').val())"><button class="btn btn-success">Print Preview</button></a>
</br>
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

        $("#table_gudang").jqGrid({
            url:'<?php echo BASE_URL.'pages/gudang/gudang.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Gudang Pengirim','Tanggal Transaksi','Total Qty','Total Faktur','Edit','Stock','Delete'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:200, search:true, stype:'text', searchoptions:{sopt:['cn','bw']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:300, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:150, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'stock',index:'stock', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_gudang',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penerimaan Barang dari Gudang",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/gudang/gudang.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Barang','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,300,50,50,50],
			            		align : ['right','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        //$("#table_beli").jqGrid('filterToolbar',{stringResult: true});
		$("#table_gudang").jqGrid('navGrid','#pager_table_gudang',{edit:false,add:false,del:false});
    })
</script>