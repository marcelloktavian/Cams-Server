<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Detail Pabrik
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="pbjualdetailstartdate" name="pbjualdetailstartdate">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="pbjualdetailenddate" name="pbjualdetailenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lblcust_jual" class="ui-helper-reset label-control">Customer Pabrik</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="pbjualdetail_pelanggan" id="pbjualdetail_pelanggan">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblsupplier ORDER BY namaperusahaan ASC");
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan_pabrik/pbaccreport_jualdetail.php?action=preview&start='+$('#pbjualdetailstartdate').val()+'&end='+$('#pbjualdetailenddate').val()+'&id='+$('#pbjualdetail_pelanggan').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#pbjualdetailstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#pbjualdetailenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#pbjualdetailstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#pbjualdetailenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
