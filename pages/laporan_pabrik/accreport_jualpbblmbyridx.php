<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Pabrik Belum Lunas
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
		<label for="project_id" class="ui-helper-reset label-control">s.d.Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="accenddateblmbyrpb" name="accenddateblmbyrpb">
				</td>
				</tr>
				</table>
            </div>

        	<label for="lblcust_jualpelanggan" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="id_pelanggan" id="id_pelangganpbblmlunaspb">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblsupplier where type=2 ORDER BY namaperusahaan ASC");
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan_pabrik/accreport_jualpbblmbyr.php?action=preview&id='+$('#id_pelangganpbblmlunaspb').val()+'&end='+$('#accenddateblmbyrpb').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
    /*
	$('#startdatejualblmlunaspb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	*/
	$('#accenddateblmbyrpb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	/*
	$( "#startdatejualblmlunaspb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	*/
	$( "#accenddateblmbyrpb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	</script>
	
</div>
