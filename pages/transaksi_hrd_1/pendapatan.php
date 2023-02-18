<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_edit = is_show_menu(EDIT_POLICY, transpendapatan, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_pendapatan'])?$_GET['start_pendapatan']:date('Y-m-d');
		$enddate = isset($_GET['end_pendapatan'])?$_GET['end_pendapatan']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	      
        $where = "WHERE TRUE AND deleted=0 AND posting='T' ";
		
		if($startdate != null){
			$where .= " AND (DATE(tgl_upah_start) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')) AND (DATE(tgl_upah_end)BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y'))";
		}

        $sql = "SELECT a.*,DATE_FORMAT(tgl_upah_start, '%d/%m/%Y') as awal,DATE_FORMAT(tgl_upah_end, '%d/%m/%Y') as akhir,
		(SELECT IFNULL(SUM(d.subtotal),0) as total FROM hrd_karyawan aa LEFT JOIN hrd_karyawandet d ON d.id_karyawan=aa.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='pendapatan' AND metode_pethitungan='Manual Input' LEFT JOIN hrd_jabatan b ON b.id_jabatan=aa.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE aa.deleted=0) as subtotalmanual 
		
		  FROM `hrd_penggajian` a ".$where;
        
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
        $i=0;
		$grandtotal=0;

        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
        	if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="window.open(\''.BASE_URL.'pages/transaksi_hrd/pendapatan_detail.php?id='.$line['penggajian_id'].'\',\'table_pendapatan\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
			}
			
            $responce['rows'][$i]['id']   = $line['penggajian_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['penggajian_id'],
                $line['nama_periode'],
                $line['type_karyawan'],
                $line['awal'].' - '.$line['akhir'],
                number_format($line['jml_periode'],0),               
                number_format($line['total_pendapatan_variabel'],0),               
				$edit,
            );

			$grandtotal += $line['total_pendapatan_variabel'];

            $i++;
        }
		
		$responce['userdata']['total'] 			= number_format($grandtotal,0);

        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$q = $db->query("SELECT a.`id_penggajiandet`, a.`id_karyawan`, b.`nama_karyawan`, d.`nama_dept`, IFNULL(a.`subtotal_variabel`,0) as subtotal_variabel
		
		-- (SELECT IFNULL(SUM(d.subtotal),0) as total FROM hrd_karyawan aa LEFT JOIN hrd_karyawandet d ON d.id_karyawan=aa.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='pendapatan' AND metode_pethitungan='Manual Input' LEFT JOIN hrd_jabatan b ON b.id_jabatan=aa.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE aa.deleted=0 AND aa.id_karyawan=a.id_karyawan) as subtotalmanual
		
		 FROM hrd_penggajiandet a
		LEFT JOIN hrd_karyawan b ON b.`id_karyawan`=a.`id_karyawan`
		LEFT JOIN `hrd_jabatan` c ON c.`id_jabatan`=b.`id_jabatan`
		LEFT JOIN hrd_departemen d ON c.id_dept=d.`id_dept`
		WHERE a.`status`='pendapatan' AND a.`id_penggajian`='$id' ORDER BY a.`id_penggajiandet` ASC");

		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
			
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nama_karyawan'],
                $line['nama_dept'],
                number_format($line['subtotal_variabel'],0),
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'pendapatan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'pendapatan_form.php';exit();
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
			$stmt = $db->prepare("UPDATE hrd_penggajian SET  nama_periode=?, tgl_upah_start=STR_TO_DATE(?,'%d/%m/%Y'), tgl_upah_end=STR_TO_DATE(?,'%d/%m/%Y'), jml_periode=?, `user`=?,lastmodified = NOW() WHERE penggajian_id=?");
			$stmt->execute(array($_POST['nama_periode'],$_POST['tgl_upah_start'],$_POST['tgl_upah_end'],$_POST['jml_periode'],$_SESSION['user']['username'], $_POST['penggajian_id']));
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
			$stmt = $db->prepare("INSERT INTO hrd_penggajian (`nama_periode`,`tgl_upah_start`,`tgl_upah_end`,`jml_periode`,`user`,`lastmodified`) VALUES(?, STR_TO_DATE(?,'%d/%m/%Y'), STR_TO_DATE(?,'%d/%m/%Y'), ?, ?, NOW())");

			if($stmt->execute(array($_POST['nama_periode'],$_POST['tgl_upah_start'],$_POST['tgl_upah_end'],$_POST['jml_periode'],$_SESSION['user']['username']))) {
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
				 <input value="" type="text" class="required datepicker"   id="start_pendapatan" name="start_pendapatan">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_pendapatan" name="end_pendapatan">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadPendapatan()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>

<table id="table_pendapatan"></table>
<div id="pager_table_pendapatan"></div>

<script type="text/javascript">
    $('#start_pendapatan').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_pendapatan').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_pendapatan" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_pendapatan" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadPendapatan(){
		var start_pendapatan = $("#start_pendapatan").val();
		var end_pendapatan = $("#end_pendapatan").val();
		var v_url ='<?php echo BASE_URL?>pages/transaksi_hrd/pendapatan.php?action=json&start_pendapatan='+start_pendapatan+'&end_pendapatan='+end_pendapatan ;
		jQuery("#table_pendapatan").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){

        $("#table_pendapatan").jqGrid({
            url:'<?php echo BASE_URL.'pages/transaksi_hrd/pendapatan.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Nama Periode','Tipe Karyawan','Tanggal Penggajian','Jumlah Hari Kerja', 'Total Pendapatan Variabel', 'Edit'],
            colModel:[
                {name:'penggajian_id',index:'penggajian_id',align:'left', width:20, searchoptions: {sopt:['cn']}},
                {name:'nama_periode',index:'nama_periode', align:'left', width:200, searchoptions: {sopt:['cn']}},
                {name:'tipe',index:'tipe', align:'center', width:70, searchoptions: {sopt:['cn']}},
                {name:'tgl_penggajian',index:'tgl_penggajian', align:'center', width:100, searchoptions: {sopt:['cn']}},               
                {name:'jml_periode',index:'jml_periode', align:'center', width:70, searchoptions: {sopt:['cn']}},
				{name:'total',index:'total', align:'right', width:70, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_pendapatan',
            sortname: 'penggajian_id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"HRD Pendapatan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/transaksi_hrd/pendapatan.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Nama Karyawan','Departemen','Total Pendapatan Variabel'], 
			            		width : [40,250,100,100],
			            		align : ['center','left','center','right'],
			            	} 
			            ],
        });
        $("#table_pendapatan").jqGrid('navGrid','#pager_table_pendapatan',{search:false,edit:false,add:false,del:false});
    })
</script>