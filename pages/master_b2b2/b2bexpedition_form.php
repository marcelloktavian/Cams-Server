<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' B2B EXPEDITION ';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="b2bexpedition_form" method="post" action="<?php echo BASE_URL ?>pages/master_b2b/b2bexpedition.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_b2bexpedition WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="kode" class="ui-helper-reset label-control">No Kendaraan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode']) ? $row['kode'] : ''; ?>" type="text" class="required" id="kode" name="kode">	
            </div>
			<label for="nama" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required"  id="nama" name="nama">	
            </div>
            <label for="nama" class="ui-helper-reset label-control">Operator</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['operator']) ? $row['operator'] : ''; ?>" type="text" id="operator" name="operator">	
            </div>
			<label for="kode_warna" class="ui-helper-reset label-control">Colour Code</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode_warna']) ? $row['kode_warna'] : ''; ?>" type="text" class="required" id="kode_warna" name="kode_warna">	
            </div>
        </form>
    </div>
</div>