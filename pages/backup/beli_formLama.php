<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        ADD PEMBELIAN
    </div>
    <div class="ui-widget-content ui-corner-bottom">
	
        <form id="beli_form" method="post" action="<?php echo BASE_URL ?>pages/beli.php?action=processadd" class="ui-helper-clearfix">
	    <table>
		<tr>
		<td>
		    <label for="supplier" class="ui-helper-reset label-control">Supplier</label>
		</td>
		<td>
		<div class="ui-corner-all form-control">
                <select class="required" name="id_supplier" id="id_supplier">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblpelanggan ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
                	?>
                </select>
            </div>
		</td>
		
		<td><label for="kode" class="ui-helper-reset label-control">Kode.Transaksi</label></td>
		<td><div class="ui-corner-all form-control">
                <input value="" type="text" class="" size="10" id="kode" name="kode">
            </div>
		</td>
		</tr>
		<tr>
		<td>
		<label for="tgl_trans" class="ui-helper-reset label-control">Tgl.Transaksi</label>
		</td>
		<td>
		<div class="ui-corner-all form-control">
                <input value="" type="text" class="required datepicker" id="tgl_trans" name="tgl_trans">
            </div>	
		</td>
		<td>
		<label for="tgl_ship" class="ui-helper-reset label-control">Tanggal.Penerimaan</label>
		</td>
		<td>
		<div class="ui-corner-all form-control">
                <input value="" type="text" class="required datepicker" id="tgl_ship" name="tgl_ship">
        </div>
		</td>
		</tr>
        <tr>
		<td>
		<label for="keterangan" class="ui-helper-reset label-control">Keterangan </label>
        </td>
		<td colspan="3">
		    <div class="ui-corner-all form-control">
                <textarea class="" id="keterangan" name="keterangan"></textarea>
            </div>
		</td>
		</tr>
		</table>	
            <label for="detail" class="ui-helper-reset label-control">Detail</label>
            <div class="ui-corner-all form-control">
                <table class="jtable" id="tblItem" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr id="header_cart">
                        <th align="center">No</th>
                        <th align="center"><label for="province_id">Nama Barang</label></th>
                        <th align="center" ><label for="city_id">Harga</label></th>
                        <th align="center"><label for="jumlah_angket">Jumlah </label></th>
						<th align="center">Subtotal</th>
                        <th align="center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="">
                        <td align="center">
                            <span class="tblItem_num">1</span>
                        </td>
                        <!--
                        
						<td align="center">
                        	<?php
                        	
							//$prows = $db->query("SELECT * FROM province p WHERE p.deleted = 0 ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	
							?>
                        	<select name="province_id[]" id="province_id" class="province_id_project required">
                        		<option value="">--Choose--</option>
                        		<?php
                        		/*
  								 foreach($prows as $r) {
                        				echo '<option value="'.$r['id_jenis'].'">'.$r['nm_barang'].'</option>';
                        			}
                        		*/
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
						-->
						
						<td align="center">
                        	<?php
                        		$prows = $db->query("SELECT * FROM barang p WHERE p.deleted = 0 ORDER BY nm_barang ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="barang[]" id="barang_id" class="barang_id required">
                        		<option value="">--Pilih--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				echo '<option value="'.$r['id'].'">'.$r['nm_barang'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>
						
						<td align="center">
                            <span class="harga_id_box">     						
                        	<select name="harga_id[]" id="harga_id[]" class="harga_id required">
                        		<option value="">--Pilih--</option>                        		
                        	</select>
                        	</span>
						</td>
						
						<td align="center">
                            <input type="text" id="qty" class="number required qty" size="10" value="" id="qty[]">
                        </td> 
                        <td align="center">
                            <input type="text" id="subtotal" class="number required subtotal" size="10" value="" id="subtotal[]">
                        </td> 
                        <td align="center">
                            <span class="delete_btn">
                            	<a class="tblItem_del" onclick="del_row(this, 'tblItem_del')" href="javascript:;" style="font-weight: normal">[Delete]</a>
                            </span>                            
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td><label for="total_qty">Total</label>
						</td>
						<td align="center">
						<div class="ui-corner-all form-control">
						<!--<input value="" type="text" class="required number" id="total_qty" name="total_qty">
						-->
						<input type="text" class="required number" id="total_qty" name="total_qty">
						</div>
						</td>
						<td>
						<div class="ui-corner-all form-control">
						<input type="text" class="required number" id="total_subtotal" name="total_subtotal">
						</div>
						</td>
						
						
						<td style="text-align: center">
                            <!--<button class="btn-add2" type="button" onclick="javascript:add_row('tblItem')">Tambah</button>-->
                            <!--<a href="javascript:;" onclick="javascript:add_row('tblItem')">Tambah</a>
							fungsi yang dipanggil adalah fungsi add_row_purchase
							-->
                            <a href="javascript:;" onclick="javascript:add_row_purchase('tblItem')">Tambah</a>
                        </td>
                    </tr>
					<tr>
					<td colspan="4" align="right">Discount</td>
					<td colspan="2">
						<div class="ui-corner-all form-control">
						<input type="text" size="10" id="discount" name="discount">
						</div>
					</td>
						
					</tr>
                    <tr>
					<td colspan="4" align="right">Total Faktur</td>
					<td colspan="2">
						<div class="ui-corner-all form-control">
						<input type="text" size="10" id="totalfaktur" name="totalfaktur">
						</div>
					</td>
						
					</tr>
                    
					</tfoot>
                </table>
            </div>
        </form>
    </div>
	
</div>