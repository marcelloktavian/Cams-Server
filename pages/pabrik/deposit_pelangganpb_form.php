<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' DATA DEPOSIT PELANGGAN PABRIK';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="pelanggan_toko_form" method="post" action="<?php echo BASE_URL ?>pages/pabrik/deposit_pelangganpb.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM tblsupplier WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
					$kode = isset($row['id_cust']) ? $row['id_cust'] : '';
				}
				else if(strtolower($_GET['action']) == 'add') {
				//max id for kode pelanggan--------------------
					$select2 = $db->prepare("Select max(substring(id_cust,3,3)+1) as kode from tblsupplier where id_cust like 'TK%' and type=2");
					$select2->execute();
					$row2  = $select2->fetch(PDO::FETCH_ASSOC);
					$kode  = "TK".sprintf("%03d", $row2['kode']);
				//----------------------------------------------
				}
				//var_dump($kode);die;
	        ?>
			<label for="id_cust" class="ui-helper-reset label-control">KODE PELANGGAN</label>	
            <div class="ui-corner-all form-control">
			    <input value="<?php echo $kode; ?>" type="text" type="text" id="idcust" name="idcust" readonly>	
            </div>
			
            <label for="pelanggan_name" class="ui-helper-reset label-control">Nama (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['namaperusahaan']) ? $row['namaperusahaan'] : ''; ?>" type="text" style="text-transform: uppercase" class="required" id="nama" name="nama">
            </div>
			
			<label for="deposit_name" class="ui-helper-reset label-control">DEPOSIT</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['deposit']) ? $row['deposit'] : ''; ?>" type="text" style="text-transform: uppercase" id="deposit" name="deposit">
            </div>
			
			<label for="keterangan" class="ui-helper-reset label-control">NPWP / NIK</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan"style="text-transform: uppercase"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea>
            </div>
			
			<label for="alamat" class="ui-helper-reset label-control">Alamat</label>
            <div class="ui-corner-all form-control">
                <textarea id="alamat" name="alamat"style="text-transform: uppercase"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>
            </div>
			
			<label for="telp1" class="ui-helper-reset label-control">Telepon 1</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp1']) ? $row['telp1'] : ''; ?>" type="text" style="text-transform: uppercase" id="telp1" name="telp1">
            </div>
			
			<label for="telp2" class="ui-helper-reset label-control">Telepon 2</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp2']) ? $row['telp2'] : ''; ?>" style="text-transform: uppercase" type="text" id="telp2" name="telp2">
            </div>
			
			<label for="fax" class="ui-helper-reset label-control">FAX</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['fax']) ? $row['fax'] : ''; ?>" style="text-transform: uppercase" type="text" id="fax" name="fax">
            </div>
			
			<label for="contactperson" class="ui-helper-reset label-control">Contact Person </label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['contactperson']) ? $row['contactperson'] : ''; ?>" type="text" style="text-transform: uppercase"  id="contactperson" name="contactperson">
            </div>
			
			<label for="hp" class="ui-helper-reset label-control">Handphone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['HP']) ? $row['HP'] : ''; ?>" style="text-transform: uppercase" type="text" class="" id="hp" name="hp">
            </div>
			
			<label for="email" class="ui-helper-reset label-control">Email</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" style="text-transform: uppercase" type="text" class="" id="email" name="email">
            </div>
			
        </form>
		(*) wajib diisi
    </div>
</div>