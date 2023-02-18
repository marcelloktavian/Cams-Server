<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, potongan, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, potongan, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, potongan, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		if ($_REQUEST["_search"] == "false") {
			$where = "WHERE deleted=0 AND `type`='potongan' ";
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
		   $where = sprintf(" where deleted=0 AND `type`='potongan' AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
		 
		  }

        $q = $db->query("SELECT * FROM `hrd_pendapatan_potongan` c ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT * 
							 FROM `hrd_pendapatan_potongan` c 
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
				if($allow_edit){
					$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/hrd/potongan.php?action=edit&id='.$line['id_penpot'].'\',\'table_tabel_potongan\')" href="javascript:;">Edit</a>';				
				}else{
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';	
				}
        	
				if($allow_delete){
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/hrd/potongan.php?action=delete&id='.$line['id_penpot'].'\',\'table_tabel_potongan\')" href="javascript:;">Delete</a>';
				}else{
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';	
				}
			}
            
            $persentase = '';
            if($line['persentase_kehadiran'] == 1){
                $persentase = 'Ya';
            }else{
                $persentase = 'Tidak';
            }

			$totalpendapatan = '';
            if($line['total_pendapatan'] == 1){
                $totalpendapatan = 'Ya';
            }else{
                $totalpendapatan = 'Tidak';
            }
			
            $responce['rows'][$i]['id']   = $line['id_penpot'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_penpot'],
                $line['kode_penpot'],
                $line['nama_penpot'],
                $line['metode_pethitungan'],
                $persentase,
                $totalpendapatan,
                $line['objek_pph21'],
                $line['sifat'],
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'potongan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'potongan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE hrd_pendapatan_potongan SET deleted=? WHERE id_penpot=?");
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
		if(isset($_POST['id_penpot'])) {
			$stmt = $db->prepare("UPDATE hrd_pendapatan_potongan SET kode_penpot=?, nama_penpot=?, metode_pethitungan=?, persentase_kehadiran=?, total_pendapatan=?, objek_pph21=?, sifat=?, type_pengaruh=?,`user`=?, lastmodified = NOW() WHERE id_penpot=?");
			$stmt->execute(array($_POST['kode_penpot'], $_POST['nama_penpot'], $_POST['metode_pethitungan'], $_POST['persentase_kehadiran'],$_POST['total_pendapatan'], $_POST['objek_pph21'],$_POST['sifat'], $_POST['tipe'],$_SESSION['user']['username'], $_POST['id_penpot']));
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
			$stmt = $db->prepare("INSERT INTO hrd_pendapatan_potongan(`kode_penpot`,`nama_penpot`,`metode_pethitungan`,`persentase_kehadiran`,`total_pendapatan`,`objek_pph21`,`sifat`,`type`,`type_pengaruh`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, 'potongan',?,?, NOW())");
			if($stmt->execute(array($_POST['kode_penpot'], $_POST['nama_penpot'], $_POST['metode_pethitungan'], $_POST['persentase_kehadiran'],$_POST['total_pendapatan'], $_POST['objek_pph21'],$_POST['sifat'],$_POST['tipe'],$_SESSION['user']['username']))) {
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
<table id="table_tabel_potongan"></table>
<div id="pager_table_tabel_potongan"></div>
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
			echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/hrd/potongan.php?action=add\',\'table_tabel_potongan\')" class="btn">Tambah</button>';
		}
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_tabel_potongan").jqGrid({
            url:'<?php echo BASE_URL.'pages/hrd/potongan.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_aktif': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Kode Potongan','Nama Potongan','Metode Perhitungan','Dipengaruhi Persentase Kehadiran','Mempengaruhi Total Potongan','Objek Pajak PPh21','Sifat','Edit','Delete'],
            colModel:[
                {name:'id_penpot',index:'id_penpot', width:20, searchoptions: {sopt:['cn']}},
                {name:'kode_penpot',index:'kode_penpot', width:70, searchoptions: {sopt:['cn']}},                
                {name:'nama_penpot',index:'nama_penpot', width:250, searchoptions: {sopt:['cn']}},  
                {name:'metode_pethitungan',align:'center',index:'metode_pethitungan', width:100, searchoptions: {sopt:['cn']}},  
                {name:'persentase_kehadiran',align:'center',index:'persentase_kehadiran', width:150, searchoptions: {sopt:['cn']}},                
				{name:'total_pendapatan',align:'center',index:'total_pendapatan', width:150, searchoptions: {sopt:['cn']}},                
                {name:'objek_pph21',align:'center',index:'objek_pph21', width:100, searchoptions: {sopt:['cn']}},                
                {name:'sifat',align:'center',index:'sifat', width:100, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_tabel_potongan',
            sortname: 'nama_penpot',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Potongan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_tabel_potongan").jqGrid('navGrid','#pager_table_tabel_potongan',{edit:false,add:false,del:false});
    })
</script>