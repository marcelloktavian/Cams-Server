<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' PROVINCE';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="province_form" method="post" action="<?php echo BASE_URL ?>pages/province.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="province_id" name="province_id">';
					$select = $db->prepare('SELECT * FROM province WHERE province_id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="province_name" class="ui-helper-reset label-control">Province Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['province_name']) ? $row['province_name'] : ''; ?>" type="text" class="required" id="province_name" name="province_name">
            </div>
        </form>
    </div>
</div>