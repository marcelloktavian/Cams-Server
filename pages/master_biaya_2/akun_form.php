<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' ACCOUNT ';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_biaya/akun.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_akun WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="nama" class="ui-helper-reset label-control">Number</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['no_akun']) ? $row['no_akun'] : ''; ?>" type="text" class="required" id="noakun" name="noakun">	
            </div>
			<label for="nama" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama_akun']) ? $row['nama_akun'] : ''; ?>" type="text" class="required" id="nama" name="nama">	
            </div>
			<label for="nama" class="ui-helper-reset label-control">Note</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan" rows="5"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea> 
            </div>
        </form>
    </div>
</div>