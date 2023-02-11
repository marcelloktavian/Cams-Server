<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Product Report
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_printorder_form" method="" action="" class="ui-helper-clearfix">
        	<label for="product_date" class="ui-helper-reset label-control">Post.Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="productstartdate" name="productstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="productenddate" name="productenddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lbl_product" class="ui-helper-reset label-control">Product</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="product_list" id="product_list">
                	<option value="">-pilih-</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_products where aktif='Y' ORDER BY nama ASC");
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/report_online/rpt_product.php?action=preview&start='+$('#productstartdate').val()+'&end='+$('#productenddate').val()+'&id_product='+$('#product_list').val())" class="btn" type="button">Print</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#productstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#productenddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#productstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#productenddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
