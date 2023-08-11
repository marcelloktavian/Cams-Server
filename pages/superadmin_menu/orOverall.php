<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Outstanding Receivable Overall
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_printorder_form" method="" action="" class="ui-helper-clearfix">
        	<label for="product_date" class="ui-helper-reset label-control">Year</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="year_oroverall" name="year_oroverall">
				</td>
				</tr>
				</table>
            </div>
			<label for="lbl_product" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="customer_list" id="customer_list">
                	<option value="all">All</option>
                	<?php
					    
                		$query = $db->query("SELECT * FROM mst_b2bcustomer WHERE deleted=0 ORDER BY nama ASC");
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
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/superadmin_menu/orOverall_rpt.php?action=preview&start='+$('#year_oroverall').val()+'&cust='+$('#customer_list').val())" class="btn" type="button">Print</button>
            	<?php } ?>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#year_oroverall').datepicker({
        dateFormat: 'yy',
	});
	// $( "#year_oroverall" ).datepicker( 'setDate', '<?=date('Y')?>'-7 );
    $( "#year_oroverall" ).val('<?=date('Y')?>');
    </script>
	
</div>