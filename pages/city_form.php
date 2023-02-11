<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' CITY';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="province_form" method="post" action="<?php echo BASE_URL ?>pages/city.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="city_id" name="city_id">';
					$select = $db->prepare('SELECT * FROM city WHERE city_id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="city_name" class="ui-helper-reset label-control">City Name</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['city_name']) ? $row['city_name'] : ''; ?>" type="text" class="required" id="city_name" name="city_name">
            </div>
            <label for="province_id" class="ui-helper-reset label-control">Province</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="province_id" id="province_id">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM province ORDER BY province_id ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['province_id']) && $row['province_id'] == $r['province_id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['province_id'].'">'.$r['province_name'].'</option>';
						}
                	?>
                </select>
            </div>
        </form>
    </div>
</div>