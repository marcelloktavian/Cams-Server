<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Belum Lunas
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
		<label for="project_id" class="ui-helper-reset label-control">s.d.Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="accenddateblmbyr" name="accenddateblmbyr">
				</td>
				</tr>
				</table>
            </div>
        	<label for="lblcust_jualpelanggan" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="id_pelanggan" id="id_pelangganblmlunas">
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/accreport_jualblmbyr.php?action=preview&id='+$('#id_pelangganblmlunas').val()+'&end='+$('#accenddateblmbyr').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
    /*
	$('#accstartdateblmbyr').datepicker({
		dateFormat: "dd/mm/yy"
	});
	*/
	$('#accenddateblmbyr').datepicker({
		dateFormat: "dd/mm/yy"
	});
	/*
	$( "#accstartdateblmbyr" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	*/
	$( "#accenddateblmbyr" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
