<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, penggajian, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, penggajian, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, penggajian, $group_acess);
$allow_post = is_show_menu(POST_POLICY, penggajian, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_penggajian'])?$_GET['start_penggajian']:date('Y-m-d');
		$enddate = isset($_GET['end_penggajian'])?$_GET['end_penggajian']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	      
        $where = "WHERE TRUE AND deleted=0 ";
		
		if($startdate != null){
			$where .= " AND (DATE(tgl_upah_start) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')) AND (DATE(tgl_upah_end)BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y'))";
		}

        $sql = "SELECT a.*,DATE_FORMAT(tgl_upah_start, '%d/%m/%Y') as awal,DATE_FORMAT(tgl_upah_end, '%d/%m/%Y') as akhir, DATE_FORMAT(tgl_pembayaran, '%d/%m/%Y') as tanggal_pembayaran   FROM `hrd_penggajian` a ".$where;
        
		//var_dump($sql);
        
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

		$totalpendapatan = 0;
		$totalpotongan = 0;
		$grandtotal = 0;

        $i=0;
		$grand_totalfaktur=0;$grand_tunai=0;$grand_transfer=0;
        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
        	if ($statusToko == 'Tutup') {
				if($line['posting']=='T'){
					$export = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Export</a>';
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
					$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
				}else{
					$export = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
				}
            } else {
				if($line['posting']=='T'){
					$export = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">NOT POSTED</a>';	
				}else{
					$export = '<a onclick="window.open(\''.BASE_URL.'pages/transaksi_hrd/penggajian_export.php?id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Export</a>';	
				}

				if($allow_edit){
					if($line['posting']=='T'){
						$edit = '<a onclick="window.open(\''.BASE_URL.'pages/transaksi_hrd/penggajian_detail.php?id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Edit</a>';	
					}else{
						$edit = '<a onclick="window.open(\''.BASE_URL.'pages/transaksi_hrd/penggajian_detail.php?id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Slip</a>';	
					}
				}else{
					if($line['posting']=='T'){
						$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
					}else{
						$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					}
				}

				if($allow_delete){
					if($line['posting']=='T'){
						$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_hrd/penggajian.php?action=delete&id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Delete</a>';
					}else{
						$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					}
				}else{
					if($line['posting']=='T'){
						$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
					}else{
						$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">POSTED</a>';
					}
				}

				if($allow_post){
					if($line['posting']=='T'){
						$post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_hrd/penggajian.php?action=post&id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Post</a>';
					}else{
						$post = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_hrd/penggajian.php?action=unpost&id='.$line['penggajian_id'].'\',\'table_penggajian\')" href="javascript:;">Unpost</a>';
					}
				}else{
					if($line['posting']=='T'){
						$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Post</a>';
					}else{
						$post = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Unpost</a>';
					}
				}
			}
			
            $responce['rows'][$i]['id']   = $line['penggajian_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['penggajian_id'],
                $line['nama_periode'],
                $line['tipe_periode'],
                $line['awal'].' - '.$line['akhir'],
                number_format($line['jml_periode'],0),  
                $line['type_karyawan'],
                $line['tanggal_pembayaran'],
                number_format($line['total_pendapatan'],0),               
                number_format($line['total_potongan'],0),               
                number_format($line['total_pendapatan']-$line['total_potongan'],0),               
				$edit,
				$post,
				$export,
				$delete,
            );

			$totalpendapatan += $line['total_pendapatan'];
			$totalpotongan += $line['total_potongan'];
			$grandtotal += ($line['total_pendapatan']-$line['total_potongan']);

            $i++;
        }

		$responce['userdata']['pendapatan'] 	= number_format($totalpendapatan,0);
		$responce['userdata']['potongan'] 		= number_format($totalpotongan,0);
		$responce['userdata']['total'] 			= number_format($grandtotal,0);
		
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$q = $db->query("SELECT a.`id_penggajiandet`, a.`id_karyawan`, b.`nama_karyawan`, d.`nama_dept`, 
		IFNULL((SELECT SUM(subtotal) FROM hrd_penggajiandet WHERE `id_penggajian`='$id' AND hrd_penggajiandet.`id_karyawan`=a.`id_karyawan` AND `status`='pendapatan'),0) AS pendapatan, 
		IFNULL((SELECT SUM(subtotal) FROM hrd_penggajiandet WHERE `id_penggajian`='$id' AND hrd_penggajiandet.`id_karyawan`=a.`id_karyawan` AND `status`='potongan'),0) AS potongan, a.status FROM hrd_penggajiandet a
		LEFT JOIN hrd_karyawan b ON b.`id_karyawan`=a.`id_karyawan`
		LEFT JOIN `hrd_jabatan` c ON c.`id_jabatan`=b.`id_jabatan`
		LEFT JOIN hrd_departemen d ON c.id_dept=d.`id_dept`
		WHERE a.`id_penggajian`='$id' 
		GROUP BY a.`id_karyawan`
		ORDER BY b.`nama_karyawan` ASC");

		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
			
			$print = "<a href='#' target='_blank'>Print</a>";
			$sendwa = "<a href='#' target='_blank'>Send WA</a>";

            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nama_karyawan'],
                $line['nama_dept'],
				number_format($line['pendapatan'],0),
				number_format($line['potongan'],0),
				number_format(($line['pendapatan'] - $line['potongan']),0),

            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'penggajian_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'penggajian_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'post') {
		$stmt = $db->prepare("UPDATE hrd_penggajian SET posting=?, lastmodified=NOW() WHERE penggajian_id=?");
		$stmt->execute(array('Y', $_GET['id']));
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'unpost') {
		$stmt = $db->prepare("UPDATE hrd_penggajian SET posting=?, lastmodified=NOW() WHERE penggajian_id=?");
		$stmt->execute(array('T', $_GET['id']));
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
		$stmt = $db->prepare("UPDATE hrd_penggajian SET deleted=?, lastmodified=NOW() WHERE penggajian_id=?");
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
		if(isset($_POST['penggajian_id'])) {
			$stmt = $db->prepare("UPDATE hrd_penggajian SET  nama_periode=?, tipe_periode=?, tgl_upah_start=STR_TO_DATE(?,'%d/%m/%Y'), tgl_upah_end=STR_TO_DATE(?,'%d/%m/%Y'), jml_periode=?, `user`=?,lastmodified = NOW() WHERE penggajian_id=?");
			$stmt->execute(array($_POST['nama_periode'],$_POST['tipeperiode'],$_POST['tgl_upah_start'],$_POST['tgl_upah_end'],$_POST['jml_periode'],$_SESSION['user']['username'], $_POST['penggajian_id']));
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
			$stmt = $db->prepare("INSERT INTO hrd_penggajian (`nama_periode`,`tipe_periode`,`tgl_upah_start`,`tgl_upah_end`,`jml_periode`,`type_karyawan`,`tgl_pembayaran`,`user`,`lastmodified`) VALUES(?, ?, STR_TO_DATE(?,'%d/%m/%Y'), STR_TO_DATE(?,'%d/%m/%Y'), ?, ?, STR_TO_DATE(?,'%d/%m/%Y'), ?, NOW())");
			$stmt->execute(array($_POST['nama_periode'],$_POST['tipeperiode'],$_POST['tgl_upah_start'],$_POST['tgl_upah_end'],$_POST['jml_periode'],$_POST['tipe'],$_POST['tgl_pembayaran'],$_SESSION['user']['username']));

			$lastinsertid = $db->lastInsertId();

			$stmt1 = $db->prepare("INSERT INTO hrd_penggajiandet (`id_penggajian`,`id_karyawan`,`subtotal`,`subtotal_variabel`,`status`) (SELECT '".$lastinsertid."',a.id_karyawan, IFNULL(a.total_pendapatan,0) as total,
			
			IFNULL((SELECT sum(dd.subtotal) as subtotal_variabel
			FROM hrd_karyawan aa
			LEFT JOIN hrd_karyawandet dd ON dd.id_karyawan=aa.id_karyawan 
			LEFT JOIN hrd_pendapatan_potongan ee ON ee.id_penpot=dd.id_penpot AND ee.type='pendapatan'
			WHERE aa.periode='".$_POST['tipe']."' AND aa.deleted=0  and ee.metode_pethitungan='Manual Input' and aa.id_karyawan=a.id_karyawan),0) 
			
			as total_variabel,'pendapatan' FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan LEFT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='pendapatan' LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.periode='".$_POST['tipe']."' AND a.deleted=0 GROUP BY a.id_karyawan)");
			$stmt1->execute();

			$stmt2 = $db->prepare("INSERT INTO hrd_penggajiandet (`id_penggajian`,`id_karyawan`,`subtotal`,`subtotal_variabel`,`status`) (SELECT '".$lastinsertid."',a.id_karyawan, IFNULL(a.total_potongan,0) as total,
			
			IFNULL((SELECT sum(dd.subtotal) as subtotal_variabel
			FROM hrd_karyawan aa
			LEFT JOIN hrd_karyawandet dd ON dd.id_karyawan=aa.id_karyawan 
			LEFT JOIN hrd_pendapatan_potongan ee ON ee.id_penpot=dd.id_penpot AND ee.type='potongan'
			WHERE aa.periode='".$_POST['tipe']."' AND aa.deleted=0  and ee.metode_pethitungan='Manual Input' and aa.id_karyawan=a.id_karyawan),0)
			
			as total_variabel,'potongan' FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan LEFT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='potongan' LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.periode='".$_POST['tipe']."' AND a.deleted=0 GROUP BY a.id_karyawan)");
			$stmt2->execute();

			$stmt3 = $db->prepare("UPDATE `hrd_penggajian` SET total_pendapatan=(select SUM(subtotal) from hrd_penggajiandet where status='pendapatan' AND id_penggajian = '$lastinsertid'), total_potongan=(select SUM(subtotal) from hrd_penggajiandet where status='potongan' AND id_penggajian = '$lastinsertid'),total_pendapatan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet where status='pendapatan' AND id_penggajian = '$lastinsertid'), total_potongan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet where status='potongan' AND id_penggajian = '$lastinsertid')  WHERE penggajian_id='$lastinsertid' ");

			$stmt3->execute();
			
			if($stmt) {
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

<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
        Filter Data
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="start_penggajian" name="start_penggajian" >
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_penggajian" name="end_penggajian" >
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadPenggajian()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>

<table id="table_penggajian"></table>
<div id="pager_table_penggajian"></div>
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
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_hrd/penggajian.php?action=add\',\'table_penggajian\')" class="btn">Tambah</button>';
	}
}
	
?>
</div>
<script type="text/javascript">
    $('#start_penggajian').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_penggajian').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_penggajian" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_penggajian" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadPenggajian(){
		var start_penggajian = $("#start_penggajian").val();
		var end_penggajian = $("#end_penggajian").val();
		var v_url ='<?php echo BASE_URL?>pages/transaksi_hrd/penggajian.php?action=json&start_penggajian='+start_penggajian+'&end_penggajian='+end_penggajian ;
		jQuery("#table_penggajian").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){
        $("#table_penggajian").jqGrid({
            url:'<?php echo BASE_URL.'pages/transaksi_hrd/penggajian.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Nama Periode', 'Tipe Periode','Periode Kerja','Jumlah Hari Kerja', 'Tipe Karyawan','Tanggal Pembayaran','Total Pendapatan', 'Total Potongan', 'Gaji Bersih', 'Edit','Post','Export','Delete'],
            colModel:[
                {name:'penggajian_id',index:'penggajian_id',align:'left', width:20, searchoptions: {sopt:['cn']}},
                {name:'nama_periode',index:'nama_periode', align:'left', width:200, searchoptions: {sopt:['cn']}},
                {name:'tipeperiode',index:'tipeperiode', align:'center', width:70, searchoptions: {sopt:['cn']}},
                {name:'tgl_penggajian',index:'tgl_penggajian', align:'center', width:100, searchoptions: {sopt:['cn']}},               
                {name:'jml_periode',index:'jml_periode', align:'center', width:70, searchoptions: {sopt:['cn']}},
                {name:'tipe',index:'tipe', align:'center', width:70, searchoptions: {sopt:['cn']}},
                {name:'tanggal_pembayaran',index:'tanggal_pembayaran', align:'center', width:70, searchoptions: {sopt:['cn']}},
                {name:'pendapatan',index:'pendapatan', align:'right', width:70, searchoptions: {sopt:['cn']}},
                {name:'potongan',index:'potongan', align:'right', width:70, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:70, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:30, sortable: false, search: false},
                {name:'Post',index:'post', align:'center', width:30, sortable: false, search: false},
                {name:'Export',index:'Export', align:'center', width:40, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_penggajian',
            sortname: 'penggajian_id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"HRD Penggajian",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/transaksi_hrd/penggajian.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Nama Karyawan','Departemen','Pendapatan','Potongan','Gaji Bersih'], 
			            		width : [40,250,100,100,100,100],
			            		align : ['center','left','center','right','right','right'],
			            	} 
			            ],
        });
        $("#table_penggajian").jqGrid('navGrid','#pager_table_penggajian',{search:false,edit:false,add:false,del:false});
    })
</script>