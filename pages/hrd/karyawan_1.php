<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, karyawan, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, karyawan, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, karyawan, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
	   //all transaction kecuali yang batal
	   $where = "WHERE TRUE AND a.deleted='0' ";
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
		$where = sprintf(" where TRUE AND (a.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
		}
		//0= SALES,1=DO,3=ARCHIVE_DO
		//MENAMPILKAN PENJUALAN YANG BARU INPUT STATE=0 DAN TOTALQTY<>0 KRN BUKAN TRANSAKSI CANCEL dan TRANSAKSI YANG BLM LUNAS /Credit(PIUTANG>0)
   	
		$sql = "SELECT a.*, b.nama_jabatan FROM hrd_karyawan a LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan  ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
        //var_dump($sql);
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql."
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
        	if ($statusToko == 'Tutup') {
                $duplicate = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Duplicate</a>';
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
		    if($allow_edit){
				$duplicate = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/hrd/karyawan.php?action=duplicate&id='.$line['id_karyawan'].'\',\'table_karyawan\')" href="javascript:;">Duplicate</a>';
				$edit = '<a onclick="window.open(\''.BASE_URL.'pages/hrd/karyawan_detail_edit.php?id='.$line['id_karyawan'].'\',\'table_karyawan\')" href="javascript:;">Edit</a>';	
			}
			else{
				$duplicate = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Duplicate</a>';
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
			}
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/hrd/karyawan.php?action=delete&id='.$line['id_karyawan'].'\',\'table_karyawan\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}
        	$responce['rows'][$i]['id']   = $line['id_karyawan'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_karyawan'],
                $line['no_karyawan'],                
                $line['nama_karyawan'],                
                $line['nama_jabatan'],
                $line['tipe'],
				$duplicate,
				$edit,
				$delete,
			//	$select,
            );
            $i++;
        }
		/*
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 			= number_format($grand_biaya,0);
        */
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("update hrd_karyawan set deleted=1 WHERE id_karyawan=?");
		$stmt->execute(array($_GET['id']));
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
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'duplicate') {
		$stmt1 = $db->prepare("INSERT INTO `hrd_karyawan` (`no_karyawan`,`id_jabatan`,`nama_karyawan`,`nik`,`npwp`,`jkn_kis`,`bpjs_tk`,`no_telp`,`periode`,`tipe`,`email`,`alamat`,`rekening`,`upah_tetap`,`up_bpjs`,`up_bpjs_tk`,`tanggungan_tambahan`,`total_pendapatan`,`total_potongan`,`total`,`user`,`lastmodified`
		  ) 
			(SELECT no_karyawan,id_jabatan,nama_karyawan,nik,npwp,jkn_kis,bpjs_tk,no_telp,periode,tipe,email,alamat,rekening,upah_tetap,up_bpjs,up_bpjs_tk,tanggungan_tambahan,total_pendapatan,total_potongan,total,'".$_SESSION['user']['username']."',lastmodified FROM hrd_karyawan WHERE id_karyawan =? ) ");
		$stmt1->execute(array($_GET['id']));

		$id = $db->lastInsertId();

		$stmt = $db->prepare("INSERT INTO `hrd_karyawandet` (`id_karyawan`,`id_penpot`,`persen`,`value`,`dikali_per_hadir`,`persen_hadir`,`objek_pajak`,`subtotal`,`keterangan`
		  ) 
			(SELECT $id,id_penpot,persen,`value`,dikali_per_hadir,persen_hadir,objek_pajak,subtotal,keterangan FROM hrd_karyawandet WHERE id_karyawan =? ) ");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		
		$affected_rows = $stmt1->rowCount();
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		//$id = $line['id_trans'];
		$where = "WHERE a.id_karyawan = '".$id."' ";
        $q = $db->query("SELECT a.*, b.nama_penpot, b.type FROM `hrd_karyawandet` a LEFT JOIN hrd_pendapatan_potongan b ON b.id_penpot = a.id_penpot ".$where);
		
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
			
			if($line['dikali_per_hadir'] == '1'){
				$dikali_per_hadir="✓";
			}else{
				$dikali_per_hadir="☓";
			}
			if($line['persen_hadir'] == '1'){
				$persen_hadir="✓";
			}else{
				$persen_hadir="☓";
			}
			if($line['objek_pajak'] == '1'){
				$objek_pajak="✓";
			}else{
				$objek_pajak="☓";
			}

            $responce->rows[$i]['id']   = $line['id_det_karyawan'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nama_penpot'],
                $line['type'],
				number_format($line['persen'],2),
				number_format($line['value'],0),
				$dikali_per_hadir,
				$persen_hadir,
				$objek_pajak,
                 number_format($line['subtotal'],2),                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	 
	 
?> 
<table id="table_karyawan"></table>
<div id="pager_table_karyawan"></div>
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
				?><a href="javascript: void(0)" 
	   onclick="window.open('pages/hrd/karyawan_detail.php');">
	   <button class="btn btn-success">Tambah</button></a><?php
		}
	}
	?>
   
 <!-- <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span> 
<button id="btn-print"  class="btn btn-success">Print</button>
-->
</div>

<!--
<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';		
	}	
	*/
?>
-->

<script type="text/javascript"> 
	
    $(document).ready(function(){
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_karyawan").jqGrid({
            url:'<?php echo BASE_URL.'pages/hrd/karyawan.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Nomor Karyawan','Nama Karyawan','Jabatan & Departemen','Tipe','Duplicate','Edit','Delete'],
            colModel:[
                {name:'id_karyawan',index:'id_karyawan', width:30, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'no_karyawan',index:'no_karyawan', width:70, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'nama_karyawan',index:'nama_karyawan', width:300, searchoptions: {sopt:['cn']}},                
                {name:'b.nama_jabatan',index:'b.nama_jabatan',align:'center', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tipe',index:'tipe',align:'center', width:100, searchoptions: {sopt:['cn']}},   
                {name:'duplicate',index:'duplicate', align:'center', width:30, sortable: false, search: false},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:30, sortable: false, search: false},
              //  {name:'select',index:'select', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_karyawan',
            sortname: 'nama_karyawan',
            autowidth: true,
			//multiselect:true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Karyawan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/hrd/karyawan.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Pendapatan/Potongan','Tipe','%','Value','Dikali Hari Kehadiran','Berdasarkan % Kehadiran','Mempengaruhi Objek Pajak','Subtotal'], 
			            		width : [30,250,100,50,50,50,50,50,100],
			            		align : ['right','center','center','center','right','center','center','center','right'],
			            	} 
			            ],
						
            
        });
        $("#table_karyawan").jqGrid('navGrid','#pager_table_karyawan',{edit:false,add:false,del:false,search:true});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>