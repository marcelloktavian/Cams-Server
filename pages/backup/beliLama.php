<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

        $where = "WHERE TRUE AND p.deleted = 0 ";
        $q = $db->query("SELECT * FROM `beli` p ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT *
							 FROM `beli` p Left Join `tblpelanggan` j on (p.id_supplier=j.id) 
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
				if($line['is_start'] == 0) {
					$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=edit&id='.$line['project_id'].'\',\'table_beli\')" href="javascript:;">Edit</a>';
				}
				else {
					$edit = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}				
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				if($line['is_start'] == 0) {
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/beli.php?action=delete&id='.$line['project_id'].'\',\'table_beli\')" href="javascript:;">Delete</a>';
				}
				else {
					$delete = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
        	$responce['rows'][$i]['id']   = $line['project_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['kode'],
                $line['namaperusahaan'],                
                $line['tgl_trans'],
                $line['tgl_ship'],
                number_format($line['total_qty'],0),
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
		
		$where = "WHERE pd.closed=0 AND pd.project_id = '".$id."' ";
        $q = $db->query("SELECT * FROM `beli_det` pd INNER JOIN `city` c ON pd.city_id=c.city_id INNER JOIN `province` p ON pd.province_id=p.province_id ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT pd.*, c.city_name, p.province_name FROM `beli_det` pd INNER JOIN `city` c ON pd.city_id=c.city_id INNER JOIN `province` p ON pd.province_id=p.province_id ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['project_detail_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['province_name'],
                $line['city_name'],
                $line['jumlah_angket'],                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	//auto select pilih barang--------
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'get_harga') {
		//$select = $db->prepare("SELECT * FROM `city` c WHERE c.province_id=?");
		$select = $db->prepare("SELECT * FROM `barang` b WHERE b.id=?");
		$select->execute(array($_GET['pid']));
		$rows = $select->fetchAll(PDO::FETCH_ASSOC);
		$echo = '<select name="harga_id[]" id="harga_id" class="harga_id required" size="0">';
		//$echo = '<select name="city_id[]" id="city_id" class="city_id required">';
		//$echo = '<select name="city_id" id="city_id" class="city_id required">';
		//$echo .= '<option value="">--Choose--</option>';
		foreach($rows as $r) 
		{
			//$echo .= '<option value="'.$r['city_id'].'">'.$r['city_name'].'</option>';
			$echo .= '<option value="'.$r['id'].'">'.$r['hrg_beli'].'</option>';
			//$echo .= '<option value="'.$rows['id'].'">'.$rows['hrg_beli'].'</option>';
		   // $echo ='<input type="text" id="harga" size="10" value="'.$r['hrg_beli'].'" name="harga[]">';
		    //$echo ='<input type="text" id="harga" size="10" name="harga[]" value='.$r["hrg_beli"]. ' >';
			
		}
		$echo .= '</select>';
		$res['status'] = 1;
		$res['resp'] = $echo;
		echo json_encode($res);
		exit;
	}
	
	//----------------------
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'beli_formB.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'beli_form_edit.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE beli SET deleted=? WHERE project_id=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processadd2') {
        
		mysql_query("insert into beli(id_supplier,kode,tgl_trans,keterangan) values('".$_POST['id_supplier']."','".$_POST['kode']."','".$_POST['tgl_trans']."','".$_POST['keterangan']."')") or die ("error head".mysql_error());
		
		/*
		$stmt = $db->prepare("INSERT INTO beli(`kode`,`id_supplier`,`keterangan`,`tgl_trans`,`user_create`,`create_date`) VALUES(?, ?, ?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['kode'],$_POST['id_supplier'],$_POST['keterangan'],$_POST['tgl_trans'],$_SESSION['user']['user_id']))) {
			
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
		*/
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
?>
<table id="table_beli"></table>
<div id="pager_table_beli"></div>
<div class="btn_box">
<a href="javascript: void(0)" 
   onclick="window.open('pages/beli_detail.php', 
  'windowname1', 
  'width=800, height=250,scrollbars=yes'); 
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

        $("#table_beli").jqGrid({
            url:'<?php echo BASE_URL.'pages/beli.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['Kode','Supplier','Tanggal Transaksi','Tanggal Kirim','Total Qty (pcs)','Edit','Delete'],
            colModel:[
                {name:'kode',index:'kode', width:200, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:300, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:150, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"m/d/Y"}, align:'center'},
                {name:'tgl_ship',index:'tgl_ship', width:150, searchoptions: {sopt:['cn']}},
                {name:'total_qty',index:'total_qty', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_beli',
            sortname: 'kode',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Data Pembelian",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/beli.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Province','City','Qty(pcs)'], 
			            		width : [40,300,300,100],
			            		align : ['right','left','left','right'],
			            	} 
			            ],
            
        });
        //$("#table_beli").jqGrid('filterToolbar',{stringResult: true});
		$("#table_beli").jqGrid('navGrid','#pager_table_beli',{edit:false,add:false,del:false});
    })
</script>