<div class="ui-widget ui-form">
	<div class="ui-widget-header ui-corner-top padding5">
		<?php
		error_reporting(0);
		$action = strtoupper($_GET['action']);
		echo $action .' Data User Group';
		?>
	</div>
	<div class="ui-widget-content ui-corner-bottom">
		<form id="dataSatuan_Form" method="post" action="<?php echo BASE_URL ?>pages/setting/userGroup.php?action=process" class="ui-helper-clearfix">
			<?php
			if(strtolower($_GET['action']) == 'edit') {
				echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id_user" name="id_user">';
				$select = $db->prepare('SELECT * from `group` WHERE id = :id');
				$select->execute(array(':id' => $_GET['id']));
				$row = $select->fetch(PDO::FETCH_ASSOC);
			}
			?>

			<!-- Nama Group -->
			<label for="nama" class="ui-helper-reset label-control">Nama Group :</label>
			<div class="ui-corner-all form-control">
				<input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">
			</div>

			<!-- Keterangan -->
			<label for="desc" class="ui-helper-reset label-control">Keterangan :</label>
			<div class="ui-corner-all form-control">
				<textarea name="desc" id="desc" cols="30" rows="10"><?php echo isset($row['desc']) ? $row['desc'] : ''; ?></textarea>
			</div>

		</form>
	</div>
</div>