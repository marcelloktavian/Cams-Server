<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' ARMADA';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/armada.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id_ar" name="id_ar">';
					$select = $db->prepare('SELECT * FROM tblarmada WHERE id_ar = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="nama" class="ui-helper-reset label-control">Nama</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['Nama']) ? $row['Nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">	
            </div>
			
			<label for="nopol" class="ui-helper-reset label-control">No.Polisi</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['NoPOL']) ? $row['NoPOL'] : ''; ?>" type="text" class="required" id="nopol" name="nopol">	
            </div>
			<label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="keterangan" name="keterangan"><?php echo isset($row['Keterangan']) ? $row['Keterangan'] : ''; ?></textarea>	
            </div>
			
        </form>
    </div>
</div>