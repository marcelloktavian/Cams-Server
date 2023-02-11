<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
?>
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
		B2B Sales Statistic Report
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_statb2bdo" name="startdate_statb2bdo">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_statb2bdo" name="enddate_statb2bdo">
				</td>
				<!-- <td> Filter -->
				 <!-- <input value="" type="hidden" id="filteroperasional" name="filteroperasional"> -->
				 <!-- (Nama Biaya,Tanggal,Subtotal,PPN,Total) -->
				<!-- </td> -->
				</tr>
				</table>
            </div>
			<label for="project_id" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <select name='type_statb2bdo' id='type_statb2bdo'>
					 <option value='1'>Normal (Alphabetical)</option>
					 <option value='2'>Best Seller</option>
				 </select>
				</td>
				</tr>
				<tr>
					<td></td>
				</tr>
				</table>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
				<button onclick="printrptstatb2bdo()" class="btn" type="button">Cetak</button>
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
	 
	$('#startdate_statb2bdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_statb2bdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_statb2bdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_statb2bdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function printrptstatb2bdo() {
		var startdate = $('#startdate_statb2bdo').val();
		var enddate = $('#enddate_statb2bdo').val();
		// console.log(filter+' '+lokasi_list);

		window_open('<?php echo BASE_URL ?>pages/summary_b2b/stat_b2bdo_rpt.php?action=preview&type='+$('#type_statb2bdo').val()+'&start='+startdate+'&end='+enddate);
		
	}
</script>