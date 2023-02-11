<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_edit = is_show_menu(EDIT_POLICY, deduction, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_deduction'])?$_GET['start_deduction']:date('Y-m-d');
		$enddate = isset($_GET['end_deduction'])?$_GET['end_deduction']:date('Y-m-d'); 
		$filter = isset($_GET['filter'])?$_GET['filter']:''; 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	      
        $where = "WHERE TRUE AND deleted=0 ";
		if($filter != '' ){
			$where .= " AND tipe_kar = '$filter' ";
		}

		if($startdate != null){
			$where .= " AND DATE(tgl_upah) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}

        $sql = "SELECT a.*, SUM(b.total_deduction) as deduction FROM `pengupahan` a left join pengupahan_detail b ON b.upah_id=a.upah_id ".$where." GROUP BY a.upah_id";
        
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
		$grand_totalfaktur=0;$grand_tunai=0;$grand_transfer=0;
        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
        	if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="window.open(\''.BASE_URL.'pages/transaksi_hrd/deduction.php?action=edit&id='.$line['upah_id'].'\',\'table_deduction\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
			}

            $responce['rows'][$i]['id']   = $line['upah_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['upah_id'],
                $line['tgl_upah'],
                $line['tipe_kar'],
                $line['jml_periode'],    
                number_format($line['deduction'],0),               
				$edit,
            );

            $i++;
        }
		
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$where = "WHERE det.upah_id = '".$id."' ";
		$q = $db->query("SELECT det.*, kar.nama_kar FROM `pengupahan_detail` det LEFT JOIN tabel_karyawan kar ON kar.kar_id=det.kar_id ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nama_kar'],
				number_format($line['up_b_kesehatan'],0),
				number_format($line['up_b_tk'],0),
				number_format($line['simpanan_kop'],0),
				number_format($line['pinjaman_kop'],0),
				number_format($line['pph21'],0),
				number_format($line['pph21_thr'],0),
				number_format($line['potongan'],0),
				number_format($line['total_deduction'],0),

            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'deduction_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'deduction_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE pengupahan SET deleted=?, lastmodified=NOW() WHERE upah_id=?");
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
		if(isset($_POST['upah_id'])) {
			$stmt = $db->prepare("UPDATE pengupahan SET  tgl_upah=STR_TO_DATE(?,'%d/%m/%Y'), tipe_kar=?, jml_periode=?, lastmodified = NOW() WHERE upah_id=?");
			$stmt->execute(array($_POST['tgl_upah'], $_POST['tipe_kar'], $_POST['jml_periode'], $_POST['upah_id']));
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
            
			$stmt = $db->prepare("INSERT INTO pengupahan (`tgl_upah`,`tipe_kar`,`jml_periode`,`lastmodified`) VALUES(STR_TO_DATE(?,'%d/%m/%Y'), ?, ?, NOW())");
			$stmt->execute(array($_POST['tgl_upah'],$_POST['tipe_kar'],$_POST['jml_periode']));

			$lastid = $db->lastInsertId();
			$tipekar = $_POST['tipe_kar'];
			
			$sql_detail = "INSERT INTO pengupahan_detail (pengupahan_detail.upah_id, pengupahan_detail.kar_id) SELECT '$lastid', tk.kar_id FROM tabel_karyawan AS tk WHERE tk.tipe_kar = '$tipekar' AND deleted=0 ";
			$insert = $db->prepare($sql_detail);

			if($insert->execute()) {
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
				 <input value="" type="text" class="required datepicker"   id="start_deduction" name="start_deduction">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_deduction" name="end_deduction">
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td>Tipe Karyawan</td>
				<td><select name='filter_deduction' id='filter_deduction'>
					<option value=''>Semua</option>
					<option value='Monthly'>Monthly</option>
					<option value='Daily'>Daily</option>
				</select></td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadDeduction()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>

<table id="table_deduction"></table>
<div id="pager_table_deduction"></div>
<script type="text/javascript">
    $('#start_deduction').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_deduction').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_deduction" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_deduction" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadDeduction(){
		var start_deduction = $("#start_deduction").val();
		var end_deduction = $("#end_deduction").val();
		var filter_deduction = $("#filter_deduction").val();
		var v_url ='<?php echo BASE_URL?>pages/transaksi_hrd/deduction.php?action=json&start_deduction='+start_deduction+'&end_deduction='+end_deduction+'&filter='+filter_deduction ;
		jQuery("#table_deduction").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){

        $("#table_deduction").jqGrid({
            url:'<?php echo BASE_URL.'pages/transaksi_hrd/deduction.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Tanggal Pengupahan','Tipe Karyawan','Jumlah Periode','Total Deduction','Edit'],
            colModel:[
                {name:'upah_id',index:'upah_id',align:'left', width:10, searchoptions: {sopt:['cn']}},
                {name:'tgl_upah',index:'tgl_upah', width:100, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"},align:'center'},                
				{name:'tipe_kar',index:'tipe_kar', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'jml_periode',index:'jml_periode', align:'center', width:100, searchoptions: {sopt:['cn']}},
				{name:'total',index:'total', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_deduction',
            sortname: 'upah_id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Deduction",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/transaksi_hrd/deduction.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Nama Karyawan','Value BPJS Kesehatan','Value BPJS TK','Simpanan Koperasi','Pinjaman Koperasi','PPH 21','PPH 21 THR','Potongan Lain Lain','Total'], 
			            		width : [40,200,100,100,100,100,100,100,100,100],
			            		align : ['center','left','right','right','right','right','right','right','right','right'],
			            	} 
			            ],
        });
        $("#table_deduction").jqGrid('navGrid','#pager_table_deduction',{edit:false,add:false,del:false});
    })
</script>