<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Grafik Rata2 Penjualan 
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="<?php echo date('m-Y')?>" type="text" class="required datepicker" id="statjualstartdate" name="statjualstartdate">
				</td>
				<td> s.d.
				<input value="<?php echo date('m-Y')?>" type="text" class="required datepicker" id="statjualenddate" name="statjualenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lblcust_jual" class="ui-helper-reset label-control">Nama Barang</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="statjualbrg" id="statjualbrg">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM barang ORDER BY id_barang ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['id_barang'].'</option>';
						}
                	?>
                </select>
            </div>
			
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/statistik/stat_jual.php?action=preview&start='+$('#statjualstartdate').val()+'&end='+$('#statjualenddate').val()+'&id='+$('#statjualbrg').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#statjualstartdate').datepicker({
		dateFormat: "mm-yy"
	});
	$('#statjualenddate').datepicker({
		dateFormat: "mm-yy"
	});
	/*
	$( "#statjualstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#statjualenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	*/
	</script>
	
</div>
