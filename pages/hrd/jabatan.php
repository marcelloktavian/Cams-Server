<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, jabatan, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, jabatan, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, jabatan, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		if ($_REQUEST["_search"] == "false") {
			$where = "WHERE c.deleted=0 ";
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
		   $where = sprintf(" where c.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
		 
		  }

        $q = $db->query("SELECT c.*, d.nama_dept  FROM `hrd_jabatan` c LEFT JOIN hrd_departemen d ON d.id_dept=c.id_dept  ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT c.*, d.nama_dept 
							 FROM `hrd_jabatan` c 
                             LEFT JOIN hrd_departemen d ON d.id_dept=c.id_dept 
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
                $print = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Print</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
				if($allow_edit){
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/hrd/jabatan_detail_edit.php?id='.$line['id_jabatan'].'\',\'table_tabel_job\')" href="javascript:;">Edit</a>';	
					$print = '<a onclick="window.open(\''.BASE_URL.'pages/hrd/jabatan_print.php?id='.$line['id_jabatan'].'\',\'table_tabel_job\')" href="javascript:;">Print</a>';
				}else{
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';	
					$print = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Print</a>';	
				}
        	
				if($allow_delete){
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/hrd/jabatan.php?action=delete&id='.$line['id_jabatan'].'\',\'table_tabel_job\')" href="javascript:;">Delete</a>';
				}else{
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';	
				}
			}
			
            $responce['rows'][$i]['id']   = $line['id_jabatan'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_jabatan'],
                $line['kode_jabatan'],
                $line['nama_jabatan'],
                $line['nama_dept'],
				$edit,
				$print,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'jabatan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'jabatan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE hrd_jabatan SET deleted=? WHERE id_jabatan=?");
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
		if(isset($_POST['id_jabatan'])) {
			$stmt = $db->prepare("UPDATE hrd_jabatan SET kode_jabatan=?, nama_jabatan=?, id_dept=?, ringkasan=?, lokasi_kerja=?, melapor_ke=?, kualifikasi=?, tanggung_jawab=?, kondisi_pekerjaan=?, `user`=?, lastmodified = NOW() WHERE id_jabatan=?");
			$stmt->execute(array($_POST['kode_jabatan'], $_POST['nama_jabatan'], $_POST['id_dept'], $_POST['ringkasan'], $_POST['lokasi_kerja'], $_POST['melapor_ke'], $_POST['kualifikasi'], $_POST['tanggung_jawab'], $_POST['kondisi_pekerjaan'], $_SESSION['user']['username'], $_POST['id_jabatan']));
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
			$stmt = $db->prepare("INSERT INTO hrd_jabatan(`kode_jabatan`, `nama_jabatan`, `id_dept`, `ringkasan`, `lokasi_kerja`, `melapor_ke`, `kualifikasi`, `tanggung_jawab`, `kondisi_pekerjaan`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['kode_jabatan'], $_POST['nama_jabatan'], $_POST['id_dept'], $_POST['ringkasan'], $_POST['lokasi_kerja'], $_POST['melapor_ke'], $_POST['kualifikasi'], $_POST['tanggung_jawab'], $_POST['kondisi_pekerjaan'],$_SESSION['user']['username']))) {
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
<table id="table_tabel_job"></table>
<div id="pager_table_tabel_job"></div>
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
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
		if($allow_add) {
			?>
				<a href="javascript: void(0)" 
   onclick="window.open('pages/hrd/jabatan_detail.php');">
   <button class="btn btn-success">Tambah</button></a>
			<?php
		}
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    
        $("#table_tabel_job").jqGrid({
            url:'<?php echo BASE_URL.'pages/hrd/jabatan.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_aktif': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Kode Jabatan','Nama Jabatan','Nama Departemen','Edit','Print','Delete'],
            colModel:[
                {name:'id_jabatan',index:'id_jabatan', width:20, searchoptions: {sopt:['cn']}},
                {name:'kode_jabatan',index:'kode_jabatan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'nama_jabatan',index:'nama_jabatan', width:270, searchoptions: {sopt:['cn']}},                
                {name:'nama_dept',align:'center',index:'nama_dept', width:150, searchoptions: {sopt:['cn']}},    
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Print',index:'print', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_tabel_job',
            sortname: 'nama_jabatan',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Jabatan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_tabel_job").jqGrid('navGrid','#pager_table_tabel_job',{edit:false,add:false,del:false});
    })
</script>