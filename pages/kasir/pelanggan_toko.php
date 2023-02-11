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
       $where = "WHERE p.deleted=0 AND p.type=2 ";
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
	  $where = sprintf(" where p.deleted=0 AND p.type=2 AND p.%s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		

        //$where = "WHERE deleted=0 ";
        $q = $db->query("SELECT * FROM `tblpelanggan` p ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT p.id,p.id_cust, p.namaperusahaan,p.alamat,p.telp1,p.HP, p.contactperson,p.deposit,p.keterangan   
							 FROM `tblpelanggan` p
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
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/kasir/pelanggan_toko.php?action=edit&id='.$line['id'].'\',\'table_pelanggantoko\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/kasir/pelanggan_toko.php?action=delete&id='.$line['id'].'\',\'table_pelanggantoko\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['id_cust'],
                $line['namaperusahaan'],                
                $line['alamat'],                
                $line['telp1'],                
                $line['keterangan'],                
                $line['HP'],                
                $line['deposit'],                
				$edit,
				//$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'pelanggan_toko_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'pelanggan_toko_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE tblpelanggan SET deleted=? WHERE id=?");
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
			$stmt = $db->prepare("UPDATE tblpelanggan SET id_cust=?,namaperusahaan=?,keterangan=?,alamat=?,telp1=?,telp2=?,fax=?,contactperson=?,HP=?,email=?,deposit=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['idcust'],strtoupper($_POST['nama']),strtoupper($_POST['keterangan']),$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_POST['deposit'],$_SESSION['user']['username'], $_POST['id']));
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
			$stmt = $db->prepare("INSERT INTO tblpelanggan(`id_cust`,`namaperusahaan`,`keterangan`,`alamat`,`telp1`,`telp2`,`fax`,`contactperson`,`HP`,`email`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['idcust'],strtoupper($_POST['nama']),strtoupper($_POST['keterangan']),$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username']))) {
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
<table id="table_pelanggantoko"></table>
<div id="pager_table_pelanggantoko"></div>
<div class="btn_box">
<?php
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/kasir/pelanggan_toko.php?action=add\',\'table_pelanggantoko\')" class="btn">Tambah</button>';
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_pelanggantoko").jqGrid({
            url:'<?php echo BASE_URL.'pages/kasir/pelanggan_toko.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Kode','Nama','Alamat','Telepon','NIK/NPWP','HandPhone','Deposit','Edit'],
            colModel:[
                {name:'id',index:'id', align:'right', width:10, searchoptions: {sopt:['cn']}},
                {name:'id_cust',index:'id_cust', width:55, searchoptions: {sopt:['cn']}},  
				{name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'alamat',index:'alamat', width:100, searchoptions: {sopt:['cn']}},                
                {name:'telp1',index:'telp1', width:70, searchoptions: {sopt:['cn']}},                
                {name:'keterangan',index:'keterangan', width:90, searchoptions: {sopt:['cn']}},                
				{name:'hp',index:'hp', width:70, searchoptions: {sopt:['cn']}},             
                {name:'deposit',index:'deposit', width:70, searchoptions: {sopt:['cn']}},
				{name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                //{name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_pelanggantoko',
            sortname: 'id_cust',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Data Pelanggan Toko",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_pelanggantoko").jqGrid('navGrid','#pager_table_pelanggantoko',{edit:false,add:false,del:false});
    })
</script>