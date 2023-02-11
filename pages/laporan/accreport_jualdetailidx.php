<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Detail Toko versi Accounting
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="jualdetailstartdate" name="jualdetailstartdate">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="jualdetailenddate" name="jualdetailenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lblcust_jual" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="jualdetail_pelanggan" id="jualdetail_pelanggan">
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/accreport_jualdetail.php?action=preview&start='+$('#jualdetailstartdate').val()+'&end='+$('#jualdetailenddate').val()+'&id='+$('#jualdetail_pelanggan').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#jualdetailstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#jualdetailenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#jualdetailstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#jualdetailenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
