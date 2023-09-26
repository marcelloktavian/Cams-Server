<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' B2BSALESMAN';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="b2bsalesman_form" method="post" action="<?php echo BASE_URL ?>pages/master_b2b/b2bsalesman.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_b2bsalesman WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="name" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="nama" name="nama"><?php echo isset($row['nama']) ? $row['nama'] : ''; ?></textarea>	
            </div>
			
			<label for="kabupaten" class="ui-helper-reset label-control">Address(Alamat)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="alamat" name="alamat"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>	
            </div>
			
			<label for="phone" class="ui-helper-reset label-control">Phone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['no_telp']) ? $row['no_telp'] : ''; ?>" type="text" class="required" id="no_telp" name="no_telp">
            </div>
			
			<label for="disc" class="ui-helper-reset label-control">Disc</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['disc']) ? $row['disc'] : ''; ?>" type="text" class="required" id="disc" name="disc">	
            </div>
			
			<label for="tipe" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['type']) ? $row['type'] : ''; ?>" type="text" class="required" id="tipe" name="tipe">	
            </div>

            <label for="tipe" class="ui-helper-reset label-control">Category Sale</label>
            <div class="ui-corner-all form-control">
            <select class="js-example-basic-multiple" id="category" name="category[]"  style="width:30%"; multiple="multiple">
                <?php
                		$query = $db->query("SELECT * FROM mst_b2bcategory_sale  where deleted=0 ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
                            $select='';
                            if(isset($row['category'])){
                                $ex = explode(",", $row['category']);
                                
                                for ($a=0; $a < COUNT($ex); $a++) {
                                    if($r['id']==$ex[$a]){
                                        $select = 'selected';
                                    }
                                } 
                            }
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
                ?>
            </select>
            </div>

            <label for="tipe" class="ui-helper-reset label-control">Komisi</label>
            <div class="ui-corner-all form-control">
            <select id="komisi" name="komisi" id="komisi">
                <option value="Y" <?php if(isset($row['komisi'])){if($row['komisi']=='Y'){echo "selected";}}?>>Ya</option>
                <option value="N" <?php if(isset($row['komisi'])){if($row['komisi']=='N'){echo "selected";}}?>>Tidak</option>
            </select>
            </div>
			
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>