<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' EXPEDITION ';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_online/expedition.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_expedition WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="id_oln" class="ui-helper-reset label-control">ID online</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['id_oln']) ? $row['id_oln'] : ''; ?>" type="text" class="required" id="id_oln" name="id_oln">	
            </div>
			
			<label for="kode" class="ui-helper-reset label-control">Code</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode']) ? $row['kode'] : ''; ?>" type="text" class="required" id="kode" name="kode">	
            </div>
			
			<label for="nama" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">	
            </div>
			<label for="category_id" class="ui-helper-reset label-control">Category Expedition</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_expeditioncat" id="id_expeditioncat">
                	<option value="">-choose(pilih)-</option>
                	<?php
                		$query = $db->query("SELECT * FROM mst_expeditioncat ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_expeditioncat']) && $row['id_expeditioncat'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
                	?>
                </select>
            </div>
			<label for="kode_warna" class="ui-helper-reset label-control">Colour Code/Logo Name(*.png)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode_warna']) ? $row['kode_warna'] : ''; ?>" type="text" class="required" id="kode_warna" name="kode_warna">
				<input value="<?php echo isset($row['logo']) ? $row['logo'] : ''; ?>" type="text" class="required" placeholder="Nama Logo Expedisi"id="logo" name="logo">				
            </div>
        </form>
    </div>
</div>