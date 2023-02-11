<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' INPUT DATA DEPOSIT PABRIK';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trdepositpb_form" method="post" action="<?php echo BASE_URL ?>pages/pabrik/trdepositpb.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM trdepositpb WHERE id_trans = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
					$kode = isset($row['kode']) ? $row['kode'] : '';
				}
				else if(strtolower($_GET['action']) == 'add') {
				//max id for kode pelanggan--------------------
					$select2 = $db->prepare("Select max(substring(kode,3,3)+1) as kode_id from trdepositpb where kode like 'TD%'");
					$select2->execute();
					$row2  = $select2->fetch(PDO::FETCH_ASSOC);
					$kode  = "TD".sprintf("%03d", $row2['kode_id']);
				//----------------------------------------------
				}
				//var_dump($kode);die;
	        ?>
			<label for="id_tr" class="ui-helper-reset label-control">NO.TRANSAKSI</label>	
            <div class="ui-corner-all form-control">
			    <input value="<?php echo $kode; ?>" type="text" type="text" id="kode" name="kode" readonly>	
            </div>
			
            <label for="pelanggan_name" class="ui-helper-reset label-control">Nama Pelanggan (*)</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_cust" id="id_cust">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblsupplier where deleted=0 and type=2 ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_customer']) && $row['id_customer'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
                	?>
                </select>
            </div>
			<label for="telp1" class="ui-helper-reset label-control">Tgl.Deposit</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['tgl_trans']) ? $row['tgl_trans'] : ''; ?>" type="text" class="required datepicker" id="tgl_trans" name="tgl_trans">
            </div>
            
			<label for="deposit_name" class="ui-helper-reset label-control">DEPOSIT TUNAI</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['tunai']) ? $row['tunai'] : ''; ?>" type="text" style="text-transform: uppercase" id="tunai" name="tunai">
            </div>
			
			<label for="alamat" class="ui-helper-reset label-control">DEPOSIT TRANSFER</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['transfer']) ? $row['transfer'] : ''; ?>" type="text" style="text-transform: uppercase" id="transfer" name="transfer">
            </div>
			
			<label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan"style="text-transform: uppercase"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea>
            </div>
			
        </form>
		(*) wajib diisi
    </div>
</div>