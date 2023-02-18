<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Dropshipper Sales Statistics
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="dpsum_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="dpsumstartdate" name="dpsumstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="dpsumenddate" name="dpsumenddate">
				</td>
				</tr>
				</table>
            </div>
			<!--
			<label for="lblcust_jual" class="ui-helper-reset label-control">Expedition</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="exp_list" id="exp_list">
                	<option value="">-pilih-</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_expedition where deleted=0 ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
						
                	?>
                </select>
            </div>
			-->
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_online/dp_sum.php?action=preview&start='+$('#dpsumstartdate').val()+'&end='+$('#dpsumenddate').val())" class="btn" type="button">Print</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#dpsumstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#dpsumenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#dpsumstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#dpsumenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
