<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' PRODUCT COMPOSITIONS';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="composition_form" method="post" action="<?php echo BASE_URL ?>pages/master_b2b/composition.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_composition WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="lblnama" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>
			
			<label for="lblcatatan" class="ui-helper-reset label-control">Catatan</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="catatan" name="catatan"><?php echo isset($row['catatan']) ? $row['catatan'] : ''; ?></textarea>	
            </div>
			
           <label for="lblcost" class="ui-helper-reset label-control">Cost</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['cost']) ? $row['cost'] : ''; ?>" type="text" class="required" id="cost" name="cost">
            </div>
						
			
            			
        </form>
    </div>
</div>