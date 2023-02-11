<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' ADDRESS (REGION)';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_online/alamat.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_address WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="kecamatan" class="ui-helper-reset label-control">Subdistrict(Kecamatan)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="kecamatan" name="kecamatan"><?php echo isset($row['kecamatan']) ? $row['kecamatan'] : ''; ?></textarea>	
            </div>
			
			<label for="kabupaten" class="ui-helper-reset label-control">District(Kabupaten)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="kabupaten" name="kabupaten"><?php echo isset($row['kabupaten']) ? $row['kabupaten'] : ''; ?></textarea>	
            </div>
			
			<label for="provinsi" class="ui-helper-reset label-control">State(Provinsi)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="provinsi" name="provinsi"><?php echo isset($row['provinsi']) ? $row['provinsi'] : ''; ?></textarea>	
            </div>
			
			<label for="jumlah_desa" class="ui-helper-reset label-control">Jumlah Desa</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['jumlah_desa']) ? $row['jumlah_desa'] : ''; ?>" type="text" class="required" id="jumlah_desa" name="jumlah_desa">	
            </div>
			
			<label for="kode_pos" class="ui-helper-reset label-control">Kode Pos</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode_pos']) ? $row['kode_pos'] : ''; ?>" type="text" class="required" id="kode_pos" name="kode_pos">	
            </div>
			
        </form>
    </div>
</div>