<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, wages, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, wages, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, wages, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        $startdate = isset($_GET['start_wages'])?$_GET['start_wages']:date('Y-m-d');
		$enddate = isset($_GET['end_wages'])?$_GET['end_wages']:date('Y-m-d'); 
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

        $sql = "SELECT a.*, SUM(b.total_income) as income, SUM(b.total_deduction) as deduction FROM `pengupahan` a left join pengupahan_detail b ON b.upah_id=a.upah_id ".$where." GROUP BY a.upah_id";
        
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
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_hrd/wages.php?action=edit&id='.$line['upah_id'].'\',\'table_wages\')" href="javascript:;">Edit</a>';	
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/transaksi_hrd/wages.php?action=delete&id='.$line['upah_id'].'\',\'table_wages\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $responce['rows'][$i]['id']   = $line['upah_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['upah_id'],
                $line['tgl_upah'],
                $line['tipe_kar'],
                $line['jml_periode'],               
                number_format($line['income'],0),               
                number_format($line['deduction'],0),               
                number_format(($line['income']+$line['deduction']),0),               
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
		
		$where = "WHERE det.upah_id = '".$id."' ";
		$q = $db->query("SELECT det.total_income, det.total_deduction, kar.nama_kar FROM `pengupahan_detail` det LEFT JOIN tabel_karyawan kar ON kar.kar_id=det.kar_id ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
			
			$print = "<a href='#' target='_blank'>Print</a>";
			$sendwa = "<a href='#' target='_blank'>Send WA</a>";

            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['nama_kar'],
				number_format($line['total_income'],0),
				number_format($line['total_deduction'],0),
				number_format(($line['total_income'] + $line['total_deduction']),0),
				$print,
				$sendwa

            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'wages_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'wages_form.php';exit();
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
			$stmt = $db->prepare("UPDATE pengupahan SET  tgl_upah=STR_TO_DATE(?,'%d/%m/%Y'), jml_periode=?, lastmodified = NOW() WHERE upah_id=?");
			$stmt->execute(array($_POST['tgl_upah'],  $_POST['jml_periode'], $_POST['upah_id']));
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
			$periode = $_POST['jml_periode'];
			
			$sql_detail = "INSERT INTO pengupahan_detail (pengupahan_detail.upah_id, pengupahan_detail.kar_id, pengupahan_detail.kehadiran, pengupahan_detail.up_pokok, pengupahan_detail.tunjangan_tetap, pengupahan_detail.ttl_makan, pengupahan_detail.up_b_kesehatan, pengupahan_detail.up_b_tk, pengupahan_detail.total_income, pengupahan_detail.total_deduction, pengupahan_detail.subtotal) SELECT '$lastid',tk.kar_id,$periode,(total_upah*0.75) AS up_pokok, (total_upah*0.25) AS tunjangan , (uang_mkn*$periode)  AS total_uang_mkn, ((b_kesehatan/100)*up_bpjs)*jml_tanggungan AS bpjs_kesehatan, (((b_kecelakaan/100)*up_bpjs_tk) + ((b_haritua/100)*up_bpjs_tk) + ((b_kematian/100)*up_bpjs_tk) + ((b_pensiun/100)*up_bpjs_tk)) AS bpjs_tk, ((total_upah*0.75) + (total_upah*0.25) + (uang_mkn*$periode)), ((((b_kesehatan/100)*up_bpjs)*jml_tanggungan) + (((b_kecelakaan/100)*up_bpjs_tk) + ((b_haritua/100)*up_bpjs_tk) + ((b_kematian/100)*up_bpjs_tk) + ((b_pensiun/100)*up_bpjs_tk))),((total_upah*0.75) + (total_upah*0.25) + (uang_mkn*$periode)) + ((((b_kesehatan/100)*up_bpjs)*jml_tanggungan) + (((b_kecelakaan/100)*up_bpjs_tk) + ((b_haritua/100)*up_bpjs_tk) + ((b_kematian/100)*up_bpjs_tk) + ((b_pensiun/100)*up_bpjs_tk))) FROM tabel_karyawan tk WHERE tk.tipe_kar = '$tipekar' AND deleted=0 ";
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
				 <input value="" type="text" class="required datepicker"   id="start_wages" name="start_wages">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="end_wages" name="end_wages">
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td>Tipe Karyawan</td>
				<td><select name='filter_wages' id='filter_wages'>
					<option value=''>Semua</option>
					<option value='Monthly'>Monthly</option>
					<option value='Daily'>Daily</option>
				</select></td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadWages()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>

<table id="table_wages"></table>
<div id="pager_table_wages"></div>
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
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/transaksi_hrd/wages.php?action=add\',\'table_wages\')" class="btn">Tambah</button>';
	}
}
	
?>
</div>
<script type="text/javascript">
    $('#start_wages').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#end_wages').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#start_wages" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#end_wages" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadWages(){
		var start_wages = $("#start_wages").val();
		var end_wages = $("#end_wages").val();
		var filter_wages = $("#filter_wages").val();
		var v_url ='<?php echo BASE_URL?>pages/transaksi_hrd/wages.php?action=json&start_wages='+start_wages+'&end_wages='+end_wages+'&filter='+filter_wages ;
		jQuery("#table_wages").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}

    $(document).ready(function(){

        $("#table_wages").jqGrid({
            url:'<?php echo BASE_URL.'pages/transaksi_hrd/wages.php?action=json'; ?>',
            
            datatype: "json",
            colNames:['ID','Tanggal Pengupahan','Tipe Karyawan','Jumlah Periode', 'Total Income','Total Deduction','Total','Edit','Delete'],
            colModel:[
                {name:'upah_id',index:'upah_id',align:'left', width:20, searchoptions: {sopt:['cn']}},
                {name:'tgl_upah',index:'tgl_upah', width:100, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"},align:'center'},                
				{name:'tipe_kar',index:'tipe_kar', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'jml_periode',index:'jml_periode', align:'center', width:100, searchoptions: {sopt:['cn']}},
                {name:'income',index:'income', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'deduction',index:'deduction', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_wages',
            sortname: 'upah_id',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Generate Wages",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/transaksi_hrd/wages.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Nama Karyawan','Income','Deduction','Total','Print','Send WA'], 
			            		width : [40,250,100,100,100,60,60],
			            		align : ['center','left','right','right','right','center','center'],
			            	} 
			            ],
        });
        $("#table_wages").jqGrid('navGrid','#pager_table_wages',{edit:false,add:false,del:false});
    })
</script>