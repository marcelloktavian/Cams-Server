<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' CATATAN';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/sales_b2b/trb2bdo.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM b2bdo WHERE id_trans = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
			
			<label for="kabupaten" class="ui-helper-reset label-control">Catatan</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="keterangan" name="keterangan" rows=4><?php echo isset($row['note']) ? $row['note'] : ''; ?></textarea>	
            </div>
			
        </form>
    </div>
</div>