<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' SUPPLIER';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_biaya/supplier.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_supplier WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="name" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="nama" name="nama"><?php echo isset($row['nama']) ? $row['nama'] : ''; ?></textarea>	
            </div>
			
			<label for="kabupaten" class="ui-helper-reset label-control">Address(Alamat)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="alamat" name="alamat"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>	
            </div>
			
			<label for="telp" class="ui-helper-reset label-control">No Telp</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['no_telp']) ? $row['no_telp'] : ''; ?>" type="text" id="no_telp" name="no_telp">
            </div>

            <label for="phone" class="ui-helper-reset label-control">Phone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['hp']) ? $row['hp'] : ''; ?>" type="text" class="required" id="hp" name="hp">
                * Wajib Diisi
            </div>
			
        </form>
    </div>
</div>