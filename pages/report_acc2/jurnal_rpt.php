<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
if(isset($_GET['action']) && strtolower($_GET['action']) == 'get_pelanggan') {
    $sql_products ="SELECT a.* FROM `mst_coa` a ";
    $query = '';
    $countnya = 0;
    $q = $db->query($sql_products.' where a.deleted=0');
    $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
    foreach($data1 as $line) {
        if ($countnya == 0) {
            $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
            
        } else {
            $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
        }
        $countnya++;
        $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
        $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
        foreach($data2 as $line2) {
            $query .= " UNION ALL select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
        }
        
    }
    $p = $db->query($query);
    $rows = $p->fetchAll(PDO::FETCH_ASSOC);
    $response = array();
    $response[] = array('key'=>'','value'=>'--Pilih No Akun--');
    foreach ($rows as $r) { 
        $response[] = array('key'=>$r['noakun'],'value'=>$r['noakun'].' ('.$r['nama'].')');
    }
    echo json_encode($response);
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
				 <input value="" type="text" class="required datepicker"   id="startdate_jurnalrpt" name="startdate_jurnalrpt">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jurnalrpt" name="enddate_jurnalrpt">
				</td>
				<td> No Akun
                <select name="noakun_id" id="noakun_id" class="my-select">
                           
                </select>
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
				<button onclick="printjurnal()" class="btn" type="button">Cetak</button>
				<!-- <button onclick="printrptharian()" class="btn" type="button">Cetak Harian</button> -->
			</div>
       	</form>
   	</div>
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
	 
	$('#startdate_jurnalrpt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jurnalrpt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_jurnalrpt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_jurnalrpt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printjurnal() {
		var startdate = $('#startdate_jurnalrpt').val();
		var enddate = $('#enddate_jurnalrpt').val();
		var akun = $('#noakun_id').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_jurnal.php?action=preview&start='+startdate+'&end='+enddate+'&akun='+akun);
		
	}

    load_pelanggan = function (){
      
      list_pelanggan = document.getElementById('noakun_id');
      $.ajax({
         url:'<?=BASE_URL?>pages/report_acc/jurnal_rpt.php?action=get_pelanggan',
         success:function(result) {
            $("#noakun_id").empty();
            result = JSON.parse(result);

            for (a in result) {					 
               $('#noakun_id').append(new Option(result[a].value,result[a].key));
           }
           $('#noakun_id').select2().trigger('change');
       }
   });
  }
  
  
  $(document).ready(function() { 
    load_pelanggan();
    $("#noakun_id").select2({
        width: '397px', 
        dropdownAutoWidth : true
    });           
});
</script>