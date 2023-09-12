<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' MASTER CHART OF ACCOUNT ';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_biaya/mst_coa.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_coa WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="nama" class="ui-helper-reset label-control">Account Number</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['noakun']) ? $row['noakun'] : ''; ?>" type="text" class="required" id="noakun" name="noakun" <?php if(strtolower($_GET['action']) == 'edit') {echo 'style="background-color:#D3D3D3" readonly';}?>>	
            </div>
			<label for="nama" class="ui-helper-reset label-control">Account Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>
            <label for="nama" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
                <select class="required" id='jenis' name='jenis'>
                    <option value='Debet' <?php if(isset($row['jenis'])&&$row['jenis']=='Debet'){echo "selected";} ?> >Debet</option>
                    <option value='Kredit' <?php if(isset($row['jenis'])&&$row['jenis']=='Kredit'){echo "selected";} ?>>Kredit</option>
                </select>
            </div>
        </form>
    </div>
</div>