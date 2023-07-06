<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        B2B Return Product Report
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_printorder_form" method="" action="" class="ui-helper-clearfix">
        	<label for="product_date" class="ui-helper-reset label-control">Return Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="productb2breturnstartdate" name="productb2breturnstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="productb2breturnenddate" name="productb2breturnenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lbl_product" class="ui-helper-reset label-control">Category</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="categoryb2breturn_list" id="categoryb2breturn_list">
                	<option value="">-pilih-</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_b2bcategory_sale WHERE deleted=0 ORDER BY id ASC");
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/summary_b2b/b2bretur_composition_rpt.php?action=preview&start='+$('#productb2breturnstartdate').val()+'&end='+$('#productb2breturnenddate').val()+'&category='+$('#categoryb2breturn_list').val())" class="btn" type="button">Print</button>
            	<?php } ?>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#productb2breturnstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#productb2breturnenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#productb2breturnstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#productb2breturnenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
