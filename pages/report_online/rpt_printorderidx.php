<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Print Order Report
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_printorder_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="printorderstartdate" name="printorderstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="printorderenddate" name="printorderenddate">
				</td>
				</tr>
				</table>
            </div>
			
			<!--
			<label for="oln_id" class="ui-helper-reset label-control">Online ID</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" id="id_start" name="id_start">
				</td>
				<td> until
				<input value="" type="text" id="id_end" name="id_end">
				(example 00001-00010)
				</td>
				</tr>
				</table>
            </div>
			-->
			<label for="oln_id" class="ui-helper-reset label-control">Shipping ID</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" id="ship_start" name="ship_start">
				</td>
				<td> until
				<input value="" type="text" id="ship_end" name="ship_end">
				(example 1-10)
				</td>
				</tr>
				</table>
				
				
            </div>
            <!--
			<label for="" class="ui-helper-reset label-control">&nbsp;</label>
            
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/report_online/rpt_printorder.php?action=preview&start='+$('#printorderstartdate').val()+'&end='+$('#printorderenddate').val()+'&ship_start='+$('#ship_start').val()+'&ship_end='+$('#ship_end').val()+'&id_exp='+$('#exp_list').val())" class="btn" type="button">Print Regular</button>
			(Untuk tombol ini,Filter yang bekerja adalah tanggal dan shipping ID)	
            </div>
			-->
			<label for="exp_list" class="ui-helper-reset label-control">Expedition Category</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="exp_list" id="exp_list">
                	<option value="">-pilih-</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_expeditioncat where deleted=0 ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
						
                	?>
                </select>
            </div>     
			
			<label for="print_list" class="ui-helper-reset label-control">Print Type</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="print_list" id="print_list">
                	<option value="2" selected>Absensi</option>
                	<option value="4">By Barang</option>
                	<option value="1">By Expedition</option>
                	<option value="3">UNPACKED</option>
                </select>
				<!-- <input value="" type="checkbox" id="no_resi" name="no_resi"><label for="no_resi">Nomor Resi</label><br> -->
            </div> 

			<label class="ui-helper-reset label-control">.</label>
			<div class="ui-corner-all form-control">
			<?php
				$statusToko = '';
				$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
				$getStat->execute();
				$stat = $getStat->fetchAll();
				foreach ($stat as $stats) {
					$statusToko = $stats['status'];
				}
				
				if ($statusToko == 'Tutup') {
					echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Print</button>';
				}else{
            	?>
				<button onclick="printLaporan()" class="btn" type="button">Print</button>
				<?php } ?>
				<!-- (Untuk tombol ini,filter yang bekerja adalah tanggal dan shipping ID, expedition, serta nomor resi) -->
            </div> 
       	</form>
   	</div>
	<script type="text/javascript">
	$('#printorderstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#printorderenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#printorderstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#printorderenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );

	function printLaporan(){
		var type = $("#print_list").val();

		if(type == 1){
			window_open('<?php echo BASE_URL ?>pages/report_online/rpt_printorder_exp.php?action=preview&start='+$('#printorderstartdate').val()+'&end='+$('#printorderenddate').val()+'&ship_start='+$('#ship_start').val()+'&ship_end='+$('#ship_end').val()+'&id_exp='+$('#exp_list').val()+'&resi='+$('#no_resi').is(":checked"));
		}else if(type == 2){
			window_open('<?php echo BASE_URL ?>pages/report_online/rpt_printorder_abs.php?action=preview&start='+$('#printorderstartdate').val()+'&end='+$('#printorderenddate').val()+'&ship_start='+$('#ship_start').val()+'&ship_end='+$('#ship_end').val()+'&id_exp='+$('#exp_list').val()+'&resi='+$('#no_resi').is(":checked"));
		}else if(type == 3){
			window_open('<?php echo BASE_URL ?>pages/report_online/rpt_printorder_unpacked.php?action=preview&start='+$('#printorderstartdate').val()+'&end='+$('#printorderenddate').val()+'&ship_start='+$('#ship_start').val()+'&ship_end='+$('#ship_end').val()+'&id_exp='+$('#exp_list').val()+'&resi='+$('#no_resi').is(":checked"));
		}else if(type == 4){
			window_open('<?php echo BASE_URL ?>pages/report_online/rpt_printorder_brg.php?action=preview&start='+$('#printorderstartdate').val()+'&end='+$('#printorderenddate').val()+'&ship_start='+$('#ship_start').val()+'&ship_end='+$('#ship_end').val()+'&id_exp='+$('#exp_list').val()+'&resi='+$('#no_resi').is(":checked"));
		}
	}
	</script>
	
</div>
