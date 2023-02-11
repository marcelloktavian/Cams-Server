<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' Jenis Barang';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="jenisbarang_form" method="post" action="<?php echo BASE_URL ?>pages/master/jenisbarang.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM jenis_barang WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="jenisbarang_name" class="ui-helper-reset label-control">Jenis Barang</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nm_jenis']) ? $row['nm_jenis'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>
            
        </form>
    </div>
</div>