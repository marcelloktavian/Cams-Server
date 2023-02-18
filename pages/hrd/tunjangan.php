<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, settingtunjangan, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, settingtunjangan, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, settingtunjangan, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		if ($_REQUEST["_search"] == "false") {
			$where = "WHERE deleted=0 ";
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
		   $where = sprintf(" where deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
		 
		  }

        $q = $db->query("SELECT * FROM `tabel_tunjangan` c ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT c.tun_id, c.nama_tun, c.b_kesehatan_per, c.b_per, c.b_kesehatan, c.b_kecelakaan, c.b_haritua, c.b_kematian, c.b_pensiun, c.as_default, c.aktif 
							 FROM `tabel_tunjangan` c 
							 ".$where."
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
        	
			if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
				if($allow_edit)
					$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/hrd/tunjangan.php?action=edit&id='.$line['tun_id'].'\',\'table_tabel_tunjangan\')" href="javascript:;">Edit</a>';				
				else
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
				
				if($allow_delete)
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/hrd/tunjangan.php?action=delete&id='.$line['tun_id'].'\',\'table_tabel_tunjangan\')" href="javascript:;">Delete</a>';
				else
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $status='';
			if($line['aktif'] == 'Y' ){
				$status = 'Aktif';
			}else {
				$status = 'Tidak Aktif';
			}

			$set='';
			if($line['as_default'] == 'Y' ){
				$set = 'Ya';
			}else {
				$set = 'Tidak';
			}

			
			$responce['rows'][$i]['id']   = $line['tun_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['tun_id'],
                $line['nama_tun'],
				$line['b_kesehatan_per'].'%',
				$line['b_per'].'%',
				$line['b_kesehatan'].'%',
				$line['b_kecelakaan'].'%',
				$line['b_haritua'].'%',
				$line['b_kematian'].'%',
				$line['b_pensiun'].'%',
                $set,
                $status,            
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'tunjangan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'tunjangan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE tabel_tunjangan SET deleted=? WHERE tun_id=?");
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
		$default = $_POST['as_default'];
			if($default == 'Y'){
				
					$stmt = $db->prepare("UPDATE tabel_tunjangan SET as_default=? ");
					$stmt->execute(array("T"));
				
			}
			
		if(isset($_POST['tun_id'])) {
			$stmt = $db->prepare("UPDATE tabel_tunjangan SET nama_tun=?, b_kesehatan_per=?, b_per=?, b_kesehatan=?, b_kecelakaan=?, b_haritua=?, b_kematian=?, b_pensiun=?, as_default=?, aktif=?, last_modified = NOW() WHERE tun_id=?");
			$stmt->execute(array($_POST['nama_tun'], $_POST['b_kesehatan_per'], $_POST['b_per'], $_POST['b_kesehatan'], $_POST['b_kecelakaan'], $_POST['b_haritua'], $_POST['b_kematian'], $_POST['b_pensiun'], $_POST['as_default'], $_POST['aktif'], $_POST['tun_id']));
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
			$stmt = $db->prepare("INSERT INTO tabel_tunjangan(`nama_tun`,`b_kesehatan_per`,`b_per`,`b_kesehatan`,`b_kecelakaan`,`b_haritua`,`b_kematian`,`b_pensiun`,`as_default`,`aktif`,`last_modified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['nama_tun'], $_POST['b_kesehatan_per'], $_POST['b_per'], $_POST['b_kesehatan'], $_POST['b_kecelakaan'], $_POST['b_haritua'], $_POST['b_kematian'], $_POST['b_pensiun'], $_POST['as_default'], $_POST['aktif']))) {
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
<table id="table_tabel_tunjangan"></table>
<div id="pager_table_tabel_tunjangan"></div>
<div class="btn_box">
<?php
	// $allow = array(1,2,3);
	$statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }

	if ($statusToko == 'Tutup') {
		echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
	} else {
		if($allow_add) {
			echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/hrd/tunjangan.php?action=add\',\'table_tabel_tunjangan\')" class="btn">Tambah</button>';
		}
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_tabel_tunjangan").jqGrid({
            url:'<?php echo BASE_URL.'pages/hrd/tunjangan.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Nama Tunjangan','Kesehatan Perusahaan','Perusahaan','Kesehatan','Kecelakaan','Hari Tua','Kematian','Pensiun','Default','Status Aktif','Edit','Delete'],
            colModel:[
                {name:'tun_id',index:'tun_id', width:15, searchoptions: {sopt:['cn']}},
                {name:'nama_tun',index:'nama_tun', width:80, searchoptions: {sopt:['cn']}},
				{name:'b_kesehatan_per',align:'center',index:'b_kesehatan_per', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_per',align:'center',index:'b_per', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_kesehatan',align:'center',index:'b_kesehatan', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_kecelakaan',align:'center',index:'b_kecelakaan', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_haritua',align:'center',index:'b_haritua', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_kematian',align:'center',index:'b_kematian', width:35, searchoptions: {sopt:['cn']}},
				{name:'b_pensiun',align:'center',index:'b_pensiun', width:35, searchoptions: {sopt:['cn']}},
                {name:'as_default',align:'center',index:'as_default', width:30, searchoptions: {sopt:['cn']}},
                {name:'aktif',align:'center',index:'aktif', width:30, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_tabel_tunjangan',
            sortname: 'tun_id',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Setting Tunjangan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_tabel_tunjangan").jqGrid('navGrid','#pager_table_tabel_tunjangan',{edit:false,add:false,del:false});
    })
</script>