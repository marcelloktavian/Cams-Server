<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' Barang';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="barang_form" method="post" action="<?php echo BASE_URL ?>pages/master/barang.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM barang WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="barang_id" class="ui-helper-reset label-control">Kode Barang</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['id_barang']) ? $row['id_barang'] : ''; ?>" type="text" class="required" id="idbarang" name="idbarang" width="270px">
            </div>
			
			<label for="barang_name" class="ui-helper-reset label-control">Nama Barang</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nm_barang']) ? $row['nm_barang'] : ''; ?>" type="text" class="required" id="nama" name="nama" width="270px">
            </div>
			
			<label for="jenisbarang" class="ui-helper-reset label-control">Jenis Barang</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_jenis" id="id_jenis">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM jenis_barang  where deleted=0 ORDER BY nm_jenis ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_jenis']) && $row['id_jenis'] == $r['id_jenis'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id_jenis'].'">'.$r['nm_jenis'].'</option>';
						}
                	?>
                </select>
            </div>
			
            <label for="harga_beli" class="ui-helper-reset label-control">Harga Beli</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['hrg_beli']) ? $row['hrg_beli'] : ''; ?>" type="text" class="required" id="hbeli" name="hbeli" width="270px">
            </div>
			
            <label for="harga_jual" class="ui-helper-reset label-control">Harga Jual</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['hrg_jual']) ? $row['hrg_jual'] : ''; ?>" type="text" class="required" id="hjual" name="hjual" width="270px">
            </div>
			
            <label for="stok" class="ui-helper-reset label-control">Stok</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['stok']) ? $row['stok'] : ''; ?>" type="text" class="required" id="stok" name="stok" width="270px">
            </div>
            
        </form>
    </div>
</div>