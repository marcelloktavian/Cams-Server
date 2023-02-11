<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Online Sales Per Dropshipper Report
		(baik yang sudah kirim atau belum kirim)
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="olndropshipper_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="olndropshipperstartdate" name="olndropshipperstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="olndropshipperenddate" name="olndropshipperenddate">
				</td>
				</tr>
				</table>
            </div>
			
			<label for="lblcust_jual" class="ui-helper-reset label-control">Dropshipper</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="dp_list" id="dp_list">
                	<option value="">-pilih-</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_dropshipper where deleted=0 ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
						
                	?>
                </select>
            </div>
			
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
			<!--
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_online/oln_dropshipper.php?action=preview&start='+$('#olndropshipperstartdate').val()+'&end='+$('#olndropshipperenddate').val()+'&id_dp='+$('#dp_list').val())" class="btn" type="button">Print</button>
		    -->
			<button onclick="cek_pelanggan()" class="btn" type="button">PRINT</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#olndropshipperstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#olndropshipperenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#olndropshipperstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#olndropshipperenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function cek_pelanggan(){
	var pelanggan= document.getElementById("dp_list").value;
		if (pelanggan == ''){	
		alert("Maaf Dropshipper Belum dipilih!!");	
		}
		else
		{
		window_open('<?php echo BASE_URL ?>pages/summary_online/oln_dropshipper.php?action=preview&start='+$('#olndropshipperstartdate').val()+'&end='+$('#olndropshipperenddate').val()+'&id_dp='+$('#dp_list').val());	
		}
	}	
	</script>
	
</div>
