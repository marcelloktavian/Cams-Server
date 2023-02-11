<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Toko versi Accounting
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="accstartdate" name="accstartdate">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="accenddate" name="accenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lblcust_jual" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="id_pelangganjual" id="accid_pelangganjual">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblpelanggan where type=2 ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
                	?>
                </select>
            </div>
			
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/accreport_jualdm.php?action=preview&start='+$('#accstartdate').val()+'&end='+$('#accenddate').val()+'&id='+$('#accid_pelangganjual').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#accstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#accenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#accstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#accenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
