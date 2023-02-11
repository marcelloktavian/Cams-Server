<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' PRODUCTS';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_online/products.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_products WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="kode" class="ui-helper-reset label-control">Code</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode']) ? $row['kode'] : ''; ?>" type="hidden"  id="kode2" name="kode2">
                <input value="<?php echo isset($row['kode']) ? $row['kode'] : ''; ?>" type="text"  id="kode" name="kode">	
            </div>
			
			<label for="id_oln" class="ui-helper-reset label-control">ID Product OLN</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['oln_product_id']) ? $row['oln_product_id'] : ''; ?>" type="text"  id="oln_product_id" name="oln_product_id">	
            </div>
			
			<label for="name" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="nama" name="nama"><?php echo isset($row['nama']) ? $row['nama'] : ''; ?></textarea>	
            </div>
			
			<label for="price" class="ui-helper-reset label-control">Price(Harga)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['harga']) ? $row['harga'] : ''; ?>" type="text" class="required" id="harga" name="harga">
            </div>
			
           <label for="size" class="ui-helper-reset label-control">Size</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['size']) ? $row['size'] : ''; ?>" type="text" class="required" id="size" name="size">
            </div>
						
			<label for="tipe" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['type']) ? $row['type'] : ''; ?>" type="text" class="required" id="type" name="type">	
            </div>
            
			<label for="province_id" class="ui-helper-reset label-control">Category</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_category" id="id_category">
                	<option value="">-choose(pilih)-</option>
                	<?php
                		$query = $db->query("SELECT * FROM mst_category ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_category']) && $row['id_category'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
                	?>
                </select>
            </div>
			<label for="tipe" class="ui-helper-reset label-control">Active(Y/N)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['aktif']) ? $row['aktif'] : ''; ?>" type="text" class="required" id="aktif" name="aktif">	
            </div>
            
            			
        </form>
    </div>
</div>