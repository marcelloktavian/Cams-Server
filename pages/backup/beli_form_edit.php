<?php
	$select = $db->prepare("SELECT * FROM `beli` p WHERE p.project_id=?");
	$select->execute(array($_GET['id']));
	$row = $select->fetch(PDO::FETCH_ASSOC);
?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        EDIT PEMBELIAN
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="beli_form" method="post" action="<?php echo BASE_URL ?>pages/beli.php?action=processedit" class="ui-helper-clearfix">
        	<label for="kode" class="ui-helper-reset label-control">Kode</label>
            <div class="ui-corner-all form-control">
            	<input value="<?php echo $_GET['id']; ?>" type="hidden" class="" id="project_id" name="project_id">
                <input value="<?php echo $row['kode']; ?>" type="text" class="required" id="kode" name="kode">
            </div>
			
			<label for="supplier" class="ui-helper-reset label-control">Supplier</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_supplier" id="id_supplier">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblpelanggan ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_supplier']) && $row['id_supplier'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
                	?>
                </select>
            </div>
			
			<label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="keterangan" name="keterangan"><?php echo $row['keterangan']; ?></textarea>
            </div>
			
            <label for="tgl_trans" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['tgl_trans']; ?>" type="text" class="required datepicker" id="tgl_trans" name="tgl_trans">
            </div>
			
            <label for="tgl_ship" class="ui-helper-reset label-control">Tanggal.Penerimaan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['tgl_ship']; ?>" type="text" class="required datepicker" id="tgl_ship" name="tgl_ship">
            </div>
			
            <label for="detail" class="ui-helper-reset label-control">Detail</label>
            <div class="ui-corner-all form-control">
                <table class="jtable" id="tblItem" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr id="header_cart">
                        <th align="center">No</th>
                        <th align="center"><label for="province_id">Nama Barang</label></th>
                        <th align="center"><label for="city_id">Harga</label></th>
                        <th align="center"><label for="jumlah_angket">Jumlah</label></th>
                        <th align="center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                	<?php
                		$dselect = $db->prepare("SELECT * FROM `beli_det` pd WHERE closed = 0 AND pd.project_id=?");
						$dselect->execute(array($row['project_id']));
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
                        		$prows = $db->query("SELECT * FROM province p WHERE p.deleted = 0 ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="province_id[]" id="province_id" class="province_id_project required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				$selected = $rd['province_id'] == $r['province_id'] ? 'selected' : '';
                        				echo '<option '.$selected.' value="'.$r['province_id'].'">'.$r['province_name'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>                        
                        <td align="center">
                        	<?php
                        		$cities = $db->prepare("SELECT * FROM `city` c WHERE c.province_id =? ORDER BY c.city_name ASC");
								$cities->execute(array($rd['province_id']));
								$city = $cities->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<span class="city_id_box">                        		
                            <select name="city_id[]" id="city_id" class="city_id required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($city as $r) {
                        				$selected = $rd['city_id'] == $r['city_id'] ? 'selected' : '';
                        				echo '<option '.$selected.' value="'.$r['city_id'].'">'.$r['city_name'].'</option>';
                        			}
                        		?>                        		
                        	</select>
                        	</span>
                        </td>
						<td align="center">
                            <input type="text" value="<?php echo $rd['jumlah_angket']; ?>" id="jumlah_angket" class="number required jumlah_angket" size="10" name="jumlah_angket[]">
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
                        		$prows = $db->query("SELECT * FROM province p WHERE p.deleted = 0 ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="province_id[]" id="province_id" class="province_id_project required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				echo '<option value="'.$r['province_id'].'">'.$r['province_name'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>                        
                        <td align="center">
                        	<span class="city_id_box">
                            <select name="city_id[]" id="city_id" class="city_id required">
                        		<option value="">--Choose--</option>                        		
                        	</select>
                        	</span>
                        </td>
						<td align="center">
                            <input type="text" id="jumlah_angket" class="number required jumlah_angket" size="10" value="" name="jumlah_angket[]">
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
                        <td colspan="2">&nbsp;</td>
                        <td><label for="total_qty" class="ui-helper-reset label-control">Total Qty</label>
                        </td>
                        <td align="center"><div class="ui-corner-all form-control">
                <input value="<?php echo $row['total_qty']; ?>" type="text" class="required number" id="total_qty" name="total_qty">
                       </div>
			            </td>
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