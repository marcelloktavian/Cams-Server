<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' B2B_PRODUCTS';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_b2b/b2bproducts.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_b2bproducts WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
				else{
				$row = 0;
				}
	        ?>
            <label for="kode" class="ui-helper-reset label-control">Code</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['kode']) ? $row['kode'] : ''; ?>" type="text"  id="kode" name="kode">	
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
            
            <label for="detail" class="ui-helper-reset label-control">Detail Composition</label>
            <div class="ui-corner-all form-control">
                <table class="jtable" id="tblItem" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr id="header_cart">
                        <th align="center">No</th>
                        <th align="center"><label for="province_id">Compositions</label></th>
                        <th align="center"><label for="qty">Qty</label></th>
                        <th align="center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
                		$dselect = $db->prepare("SELECT * FROM `mst_b2bproducts_detail` pd WHERE pd.closed = 0 AND pd.products_id=?");
						$dselect->execute(array($row['id']));
						$rows = $dselect->fetchAll(PDO::FETCH_ASSOC);
                		$no=1;
						if(count($rows) > 0) {
                		foreach($rows as $rd) {
                	?>
					<tr id="">
                        <td align="center">
                            <span class="tblItem_num"><?php echo $no; ?></span>
                        </td>
                        <td align="center">
                        	<?php
                        		$prows = $db->query("SELECT * FROM mst_composition c WHERE c.deleted = 0 ORDER BY c.nama ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="composition_id[]" id="composition_id" class="composition_id required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				$selected = $rd['composition_id'] == $r['id'] ? 'selected' : '';
                        				echo '<option '.$selected.' value="'.$r['id'].'">'.$r['nama'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>                        
                        
						<td align="center">
                            <input type="text" value="<?php echo $rd['qty']; ?>" id="qty" class="number required qty" size="10" name="qty[]">
                        </td> 
                        <td align="center">
                            <span class="delete_btn">
                            	<a class="tblItem_del" onclick="del_row(this, 'tblItem_del')" href="javascript:;" style="font-weight: normal">[Delete]</a>
                            </span>                            
                        </td>
                    </tr>
                    <?php
                    		$no++;
                    	}
                	}
					else {
					?>

                    <tr id="">
                        <td align="center">
                            <span class="tblItem_num">1</span>
                        </td>
                        <td align="center">
                        	<?php
                        		$prows = $db->query("SELECT * FROM mst_composition c WHERE c.deleted = 0 ORDER BY c.nama ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="composition_id[]" id="composition_id" class="composition_id required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				echo '<option value="'.$r['id'].'">'.$r['nama'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td> 
                        
						<td align="center">
                            <input type="text" id="qty" class="number required qty" size="10" value="" name="qty[]">
                        </td> 
                        <td align="center">
                            <span class="delete_btn">
                            	<a class="tblItem_del" onclick="del_row(this, 'tblItem_del')" href="javascript:;" style="font-weight: normal">[Delete]</a>
                            </span>                            
                        </td>
                    </tr>
					<?php	
					}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td style="text-align: center">
                            <!--<button class="btn-add2" type="button" onclick="javascript:add_row('tblItem')">Tambah</button>-->
                            <a href="javascript:;" onclick="javascript:add_row('tblItem')">Tambah</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>			
        </form>
    </div>
</div>