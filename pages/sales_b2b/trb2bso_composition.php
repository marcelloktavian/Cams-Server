<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        B2B Product Report
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_printorder_form" method="" action="" class="ui-helper-clearfix">
        	<label for="product_date" class="ui-helper-reset label-control">Date</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="productb2bstartdate" name="productb2bstartdate">
				</td>
				<td> until
				<input value="" type="text" class="required datepicker" id="productb2benddate" name="productb2benddate">
				</td>
				</tr>
				</table>
            </div>
			<label for="lbl_product" class="ui-helper-reset label-control">Category</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="categoryb2b_list" id="categoryb2b_list">
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/sales_b2b/rpt_productb2b.php?action=preview&start='+$('#productb2bstartdate').val()+'&end='+$('#productb2benddate').val()+'&category='+$('#categoryb2b_list').val())" class="btn" type="button">Print</button>
            	<?php } ?>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#productb2bstartdate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#productb2benddate').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#productb2bstartdate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#productb2benddate" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	</script>
	
</div>
