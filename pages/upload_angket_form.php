<?php
	$select = $db->prepare("SELECT * FROM `project` p WHERE p.project_id=?");
	$select->execute(array($_GET['id']));
	$row = $select->fetch(PDO::FETCH_ASSOC);
?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        UPLOAD ANGKET
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="project_form" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL ?>pages/upload_angket.php?action=process" class="ui-helper-clearfix">
        	<label for="project_name" class="ui-helper-reset label-control">Project Name</label>
            <div class="ui-corner-all form-control">
            	<input value="<?php echo $_GET['id']; ?>" type="hidden" class="" id="project_id" name="project_id">
                <input disabled="disabled" value="<?php echo $row['project_name']; ?>" type="text" class="required" id="project_name" name="project_name">
            </div>
            <label for="project_description" class="ui-helper-reset label-control">Project Description</label>
            <div class="ui-corner-all form-control">
                <textarea disabled="disabled"  class="" id="project_description" name="project_description"><?php echo $row['project_description']; ?></textarea>
            </div>
            <label for="project_start" class="ui-helper-reset label-control">Project Start</label>
            <div class="ui-corner-all form-control">
                <input disabled="disabled" style="width: 70px;" value="<?php echo $row['project_start']; ?>" type="text" class="required" id="project_start" name="project_start">
            </div>
            <label for="project_end" class="ui-helper-reset label-control">Project End</label>
            <div class="ui-corner-all form-control">
                <input disabled="disabled" style="width: 70px;" value="<?php echo $row['project_end']; ?>" type="text" class="required" id="project_end" name="project_end">
            </div>
            <label for="total_angket" class="ui-helper-reset label-control">Total Angket</label>
            <div class="ui-corner-all form-control">
                <input disabled="disabled" value="<?php echo $row['total_angket']; ?>" type="text" class="required number" id="total_angket" name="total_angket">
            </div>
            <label for="project_detail_id" class="ui-helper-reset label-control">Daerah</label>
            <div class="ui-corner-all form-control">
                <?php
                	$s = $db->prepare("SELECT p.province_name, c.city_name, pd.project_detail_id FROM project_detail pd INNER JOIN province p ON pd.province_id = p.province_id INNER JOIN city c ON pd.city_id = c.city_id WHERE pd.project_id = ? ORDER BY c.city_name ");
					$s->execute(array($_GET['id']));
					$rowd = $s->fetchAll(PDO::FETCH_ASSOC);					
                ?>
                <select class="required" name="project_detail_id" id="project_detail_id">
                	<option value="">--Pilih--</option>
                	<?php
                		foreach($rowd as $r) {
                			echo '<option value="'.$r['project_detail_id'].'">'.$r['city_name'].' ('.$r['province_name'].')</option>';
                		}
                	?>
                </select>
            </div>
            <label for="file_upload" class="ui-helper-reset label-control">Angket Upload</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="file" name="file_upload" id="file_upload" />
            </div>
        </form>
    </div>
</div>