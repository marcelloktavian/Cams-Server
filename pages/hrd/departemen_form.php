<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' Master Departemen';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="departemen_form" method="post" action="<?php echo BASE_URL ?>pages/hrd/departemen.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id_dept" name="id_dept">';
					$select = $db->prepare('SELECT * FROM hrd_departemen WHERE id_dept = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
			  <label for="kode_dept" class="ui-helper-reset label-control">Kode Department</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="kode_dept" id="kode_dept" value="<?php echo isset($row['kode_dept']) ? $row['kode_dept'] : ''; ?>">
            </div>

            <label for="nama_dept" class="ui-helper-reset label-control">Nama Department</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="nama_dept" id="nama_dept" value="<?php echo isset($row['nama_dept']) ? $row['nama_dept'] : ''; ?>">
            </div>
            
        </form>
    </div>
</div>