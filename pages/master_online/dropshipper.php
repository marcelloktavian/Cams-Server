<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, Dropshipper, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, Dropshipper, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, Dropshipper, $group_acess);
$allow_post = is_show_menu(POST_POLICY, Dropshipper, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
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
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $q = $db->query("SELECT * FROM `mst_dropshipper` a ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT a.*  
							 FROM `mst_dropshipper` a
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
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
            $chat = '<a onclick="window_open(\'http://wa.me/'.$line['hp'].'\')" style="cursor: pointer;">Chat</a>';

            if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
                $post = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Apply Disc To All Transaction</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/dropshipper.php?action=edit&id='.$line['id'].'\',\'table_dropshipper\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_online/dropshipper.php?action=delete&id='.$line['id'].'\',\'table_dropshipper\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            if($allow_post)
                $post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_online/dropshipper.php?action=apply&disc='.$line['disc'].'&id='.$line['id'].'\',\'table_dropshipper\')" href="javascript:;">Apply Disc To All Transaction</a>';
            else
                $post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Apply Disc To All Transaction</a>';
            }

            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
				$line['oln_customer_id'],
				$line['nama'],
                $line['alamat'],                
                $line['hp'],
                $chat,
                $line['disc'],        
                $line['type'],                
				$edit,
				$delete,
                $post,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'dropshipper_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'dropshipper_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
        $stmt = $db->prepare("DELETE FROM det_coa WHERE noakun=(SELECT no_akun FROM `mst_dropshipper` WHERE id=?) ");
		$stmt->execute(array($_GET['id']));

		$stmt = $db->prepare("UPDATE mst_dropshipper SET deleted=? WHERE id=?");
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
    elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'apply') {
        $stmt = $db->prepare("UPDATE olnso SET `discount`=? WHERE `id_dropshipper`=?");
        $stmt->execute(array($_GET['disc'], $_GET['id']));
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
        if (trim($_POST['no_telp'])=='') {
            $telp = '0';
        } else {
           $telp = $_POST['no_telp'];
        }
        if (trim($_POST['hp'])=='') {
           $r['stat'] = 0;
           $r['message'] = 'Phone Harus Diisi';
        } else {      
		if(isset($_POST['id'])) {
            $query = $db->prepare("SELECT * FROM `mst_dropshipper` WHERE hp=? AND id<>? AND hp<>'0'");
            $query->execute(array($_POST['hp'],$_POST['id']));
            if ($query->rowCount() == 0) {
                if (preg_match('/^62[0-9]+$/', $_POST['hp'])) {
                    $stmt = $db->prepare("UPDATE mst_dropshipper SET nama=?,oln_customer_id=?,alamat=?,no_telp=?,hp=?,disc=?,type=?,user=?, lastmodified = NOW() WHERE id=?");
                    $stmt->execute(array($_POST['nama'],$_POST['oln_customer_id'],$_POST['alamat'],$telp,$_POST['hp'],$_POST['disc'],$_POST['tipe'], $_SESSION['user']['username'], $_POST['id']));
                    //var_dump($stmt);die;
                    $affected_rows = $stmt->rowCount();
                    if($affected_rows > 0) {
                        $r['stat'] = 1;
                        $r['message'] = 'Success';
                    }
                    else {
                        $r['stat'] = 0;
                        $r['message'] = 'Failed';
                    }
                }else {
                    $r['stat']= 0;
                    $r['message']='Format nomor salah';
                }
            } else {
                $r['stat'] = 0;
                $r['message'] = 'Phone Sudah Terdaftar';
            }
		}
		else {
            $query = $db->prepare("SELECT * FROM `mst_dropshipper` WHERE hp=? AND hp<>'0'");
            $query->execute(array($_POST['hp']));
            if ($query->rowCount() == 0) {
                if (preg_match('/^62[0-9]+$/', $_POST['hp'])) {
			    $stmt = $db->prepare("INSERT INTO  mst_dropshipper(`nama`,`oln_customer_id`,`alamat`,`no_telp`,`hp`,`disc`,`type`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?,NOW())");
			    if($stmt->execute(array($_POST['nama'],$_POST['oln_customer_id'],$_POST['alamat'], $telp, $_POST['hp'], $_POST['disc'], $_POST['tipe'],$_SESSION['user']['username']))) {
                    $id = $db->lastInsertId();
                    $akun = '';
                    $namaakun = '';
                    $idakun = '';
                    
                    $q = $db->query("SELECT CONCAT(SUBSTR(noakun,1,6), IF(LENGTH('$id')=1,'0000',IF(LENGTH('$id')=2,'000',IF(LENGTH('$id')=3,'00',IF(LENGTH('$id')=4,'0','')))), '$id') AS akun, noakun, nama, id FROM mst_coa WHERE (noakun = '02.02.00000' OR noakun = '01.04.00000' OR noakun = '04.01.00000' OR noakun = '04.02.00000')  AND deleted=0");
                    $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

                    foreach($data1 as $line) {
                        $akun = $line['akun'];
                        $namaakun = $line['nama'].' - '.$_POST['nama'];
                        // if($line['noakun'] == '01.04.00000'){
                        //     // Piutang OLN
                        //     $namaakun = $line['nama'].' - '.$_POST['nama'];
                        // }else if($line['noakun'] == '02.02.00000'){
                        //     // Saldo Titipan Dropshipper
                        //     $namaakun = $line['nama'].' - '.$_POST['nama'];
                        // }else if($line['noakun'] == '04.01.00000'){
                        //     // Saldo Titipan Dropshipper
                        //     $namaakun = $line['nama'].' - '.$_POST['nama'];
                        // }else if($line['noakun'] == '04.02.00000'){
                        //     // Saldo Titipan Dropshipper
                        //     $namaakun = $line['nama'].' - '.$_POST['nama'];
                        // }
                        $idakun = $line['id'];

                        $stmt = $db->prepare("INSERT INTO det_coa VALUES(NULL, ?,?,?,?,NOW())");
                        $stmt->execute(array($idakun,$akun,$namaakun,$_SESSION['user']['username']));
                    }
                    
				    $r['stat'] = 1;
				    $r['message'] = 'Success';
			    }
			    else {
				    $r['stat'] = 0;
				    $r['message'] = 'Failed';
			    }
            }else {
                $r['stat']= 0;
                $r['message']='Format nomor salah';
            }
            } else {
                $r['stat'] = 0;
                $r['message'] = 'Phone Sudah Terdaftar';
            }
		}
        }		
		echo json_encode($r);
		exit;
	}
?>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/kirim/sodetail.php');">
   <button class="btn btn-success">Tambah</button></a>
-->

<button type="button" onclick="window.open('pages/master_online/dropshipper_xls.php')" class="btn">Excell</button>
<?php



    $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
            // $id = $stats['id'];
        $statusToko = $stats['status'];
    }   
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/dropshipper.php?action=add\',\'table_dropshipper\')" class="btn">Tambah</button>';
	}}
	
?>
    
</div>
<table id="table_dropshipper"></table>
<div id="pager_table_dropshipper"></div>

<script type="text/javascript">

    $(document).ready(function(){

        $("#table_dropshipper").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_online/dropshipper.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Oln_id','Name','Address','Phone','Whatsapp','Disc','Type','Edit','Delete','Apply Disc'],
            colModel:[
                {name:'id',index:'id', align:'right',width:30, searchoptions: {sopt:['cn']}},
                {name:'oln_customer_id',index:'oln_customer_id', width:30, searchoptions: {sopt:['cn']}},                
                {name:'nama',index:'nama', width:300, searchoptions: {sopt:['cn']}},                
                {name:'alamat',index:'alamat', width:150, searchoptions: {sopt:['cn']}},                
                {name:'hp',index:'hp', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'hp',index:'hp', align:'center', width:100, searchoptions: {sopt:['cn']}},                
                {name:'disc',index:'disc', align:'right', width:40, searchoptions: {sopt:['cn']}},                
                {name:'type',index:'type', align:'center', width:40, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
                {name:'Apply Disc',index:'post', align:'center', width:140, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_dropshipper',
            sortname: 'nama',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Dropshipper",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_dropshipper").jqGrid('navGrid','#pager_table_dropshipper',{edit:false,add:false,del:false});
    })
</script>