<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
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
				 <input value="" type="text" class="required datepicker"   id="startdate_taxesrpt" name="startdate_taxesrpt">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_taxesrpt" name="enddate_taxesrpt">
				</td>
				<!-- <td> Filter -->
				 <!-- <input value="" type="hidden" id="filteroperasional" name="filteroperasional"> -->
				 <!-- (Nama Biaya,Tanggal,Subtotal,PPN,Total) -->
				<!-- </td> -->
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
				<button onclick="printrpt()" class="btn" type="button">Cetak</button>
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
	 
	$('#startdate_taxesrpt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_taxesrpt').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_taxesrpt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_taxesrpt" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printrpt() {
		var startdate = $('#startdate_taxesrpt').val();
		var enddate = $('#enddate_taxesrpt').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_taxes.php?action=preview&start='+startdate+'&end='+enddate);
		
	}
</script>