<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' SUPPLIER';		
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="supplier_form" method="post" action="<?php echo BASE_URL ?>pages/master/supplier.php?action=process" class="ui-helper-clearfix">
        	<?php
			    /*
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM tblsupplier WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}*/
				if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM tblsupplier WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row  = $select->fetch(PDO::FETCH_ASSOC);
					$kode = isset($row['id_cust']) ? $row['id_cust'] : '';
				}
				else if(strtolower($_GET['action']) == 'add') {
				//max id for kode pelanggan--------------------
					$select2 = $db->prepare("Select max(substring(id_cust,3,3)+1) as kode from tblsupplier where id_cust like 'TB%' and type=2");
					$select2->execute();
					$row2  = $select2->fetch(PDO::FETCH_ASSOC);
					$kode  = "TB".sprintf("%03d", $row2['kode']);
				//----------------------------------------------
				}
	        ?>
			<label for="id_supp" class="ui-helper-reset label-control">KODE SUPPLIER</label>	
            <!--old version
			<div class="ui-corner-all form-control">
			    <input value="<?php //echo isset($row['id_cust']) ? $row['id_cust'] : ''; ?>" type="text" type="text" id="idsupp" name="idsupp">	
            </div>
			-->
            <div class="ui-corner-all form-control">
			    <input value="<?php echo $kode; ?>" type="text" type="text" id="idsupp" name="idsupp">	
            </div>
			<label for="supplier_name" class="ui-helper-reset label-control">Nama Supplier (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['namaperusahaan']) ? $row['namaperusahaan'] : ''; ?>" type="text" class="required" id="nama" name="nama" style="text-transform: uppercase">
            </div>
			<label for="keterangan" class="ui-helper-reset label-control">NPWP / NIK</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan"style="text-transform: uppercase"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea>
            </div>
			
			<label for="alamat" class="ui-helper-reset label-control">Alamat (*)</label>
            <div class="ui-corner-all form-control">
                <textarea class="required" id="alamat" name="alamat"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>
            </div>
			
			<label for="telp1" class="ui-helper-reset label-control">Telepon 1 (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp1']) ? $row['telp1'] : ''; ?>" type="text" class="required number" id="telp1" name="telp1">
            </div>
			
			<label for="telp2" class="ui-helper-reset label-control">Telepon 2</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp2']) ? $row['telp2'] : ''; ?>" type="text" id="telp2" name="telp2">
            </div>
			
			<label for="fax" class="ui-helper-reset label-control">FAX</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['fax']) ? $row['fax'] : ''; ?>" type="text" id="fax" name="fax">
            </div>
			
			<label for="contactperson" class="ui-helper-reset label-control">Contact Person (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['contactperson']) ? $row['contactperson'] : ''; ?>" type="text" class="required"  id="contactperson" name="contactperson">
            </div>
			
			<label for="hp" class="ui-helper-reset label-control">Handphone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['HP']) ? $row['HP'] : ''; ?>" type="text" class="" id="hp" name="hp">
            </div>
			
			<label for="email" class="ui-helper-reset label-control">Email</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" type="text" class="" id="email" name="email">
            </div>
			
        </form>
		(*) wajib diisi
    </div>
</div>