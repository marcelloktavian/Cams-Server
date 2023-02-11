<?php
	$select = $db->prepare("SELECT * FROM `project` p WHERE p.project_id=?");
	$select->execute(array($_GET['id']));
	$row = $select->fetch(PDO::FETCH_ASSOC);
?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        EDIT PROJECT
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="project_form" method="post" action="<?php echo BASE_URL ?>pages/project.php?action=processedit" class="ui-helper-clearfix">
        	<label for="project_name" class="ui-helper-reset label-control">Project Name</label>
            <div class="ui-corner-all form-control">
            	<input value="<?php echo $_GET['id']; ?>" type="hidden" class="" id="project_id" name="project_id">
                <input value="<?php echo $row['project_name']; ?>" type="text" class="required" id="project_name" name="project_name">
            </div>
            <label for="project_description" class="ui-helper-reset label-control">Project Description</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="project_description" name="project_description"><?php echo $row['project_description']; ?></textarea>
            </div>
            <label for="project_start" class="ui-helper-reset label-control">Project Start</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['project_start']; ?>" type="text" class="required datepicker" id="project_start" name="project_start">
            </div>
            <label for="project_end" class="ui-helper-reset label-control">Project End</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['project_end']; ?>" type="text" class="required datepicker" id="project_end" name="project_end">
            </div>
            <label for="total_angket" class="ui-helper-reset label-control">Total Angket</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['total_angket']; ?>" type="text" class="required number" id="total_angket" name="total_angket">
            </div>
            <label for="detail" class="ui-helper-reset label-control">Detail</label>
            <div class="ui-corner-all form-control">
                <table class="jtable" id="tblItem" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr id="header_cart">
                        <th align="center">No</th>
                        <th align="center"><label for="province_id">Province</label></th>
                        <th align="center"><label for="city_id">City</label></th>
                        <th align="center"><label for="jumlah_angket">Jumlah Angket</label></th>
                        <th align="center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                	<?php
                		$dselect = $db->prepare("SELECT * FROM `project_detail` pd WHERE closed = 0 AND pd.project_id=?");
						$dselect->execute(array($row['project_id']));
						$rows = $dselect->fetchAll(PDO::FETCH_ASSOC);
                		$no=1;
						if(count($rows) > 0) {
                		foreach($rows as $rd) {
                	?>
                    <tr id="">
                        <td align="center">
                            <span class="tblItem_num"><?php echo $no; ?></span>
                        </td>
                        <td align="center">
                        	<?php
                        		$prows = $db->query("SELECT * FROM province p WHERE p.deleted = 0 ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="province_id[]" id="province_id" class="province_id_project required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				$selected = $rd['province_id'] == $r['province_id'] ? 'selected' : '';
                        				echo '<option '.$selected.' value="'.$r['province_id'].'">'.$r['province_name'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>                        
                        <td align="center">
                        	<?php
                        		$cities = $db->prepare("SELECT * FROM `city` c WHERE c.province_id =? ORDER BY c.city_name ASC");
								$cities->execute(array($rd['province_id']));
								$city = $cities->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<span class="city_id_box">                        		
                            <select name="city_id[]" id="city_id" class="city_id required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($city as $r) {
                        				$selected = $rd['city_id'] == $r['city_id'] ? 'selected' : '';
                        				echo '<option '.$selected.' value="'.$r['city_id'].'">'.$r['city_name'].'</option>';
                        			}
                        		?>                        		
                        	</select>
                        	</span>
                        </td>
						<td align="center">
                            <input type="text" value="<?php echo $rd['jumlah_angket']; ?>" id="jumlah_angket" class="number required jumlah_angket" size="10" name="jumlah_angket[]">
                        </td> 
                        <td align="center">
                            <span class="delete_btn">
                            	<a class="tblItem_del" onclick="del_row(this, 'tblItem_del')" href="javascript:;" style="font-weight: normal">[Delete]</a>
                            </span>                            
                        </td>
                    </tr>
                    <?php
                    		$no++;
                    	}
                	}
					else {
					?>
					<tr id="">
                        <td align="center">
                            <span class="tblItem_num">1</span>
                        </td>
                        <td align="center">
                        	<?php
                        		$prows = $db->query("SELECT * FROM province p WHERE p.deleted = 0 ORDER BY province_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        	?>
                        	<select name="province_id[]" id="province_id" class="province_id_project required">
                        		<option value="">--Choose--</option>
                        		<?php
                        			foreach($prows as $r) {
                        				echo '<option value="'.$r['province_id'].'">'.$r['province_name'].'</option>';
                        			}
                        		?>
                        	</select>                            
                        </td>                        
                        <td align="center">
                        	<span class="city_id_box">
                            <select name="city_id[]" id="city_id" class="city_id required">
                        		<option value="">--Choose--</option>                        		
                        	</select>
                        	</span>
                        </td>
						<td align="center">
                            <input type="text" id="jumlah_angket" class="number required jumlah_angket" size="10" value="" name="jumlah_angket[]">
                        </td> 
                        <td align="center">
                            <span class="delete_btn">
                            	<a class="tblItem_del" onclick="del_row(this, 'tblItem_del')" href="javascript:;" style="font-weight: normal">[Delete]</a>
                            </span>                            
                        </td>
                    </tr>
					<?php	
					}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td style="text-align: center">
                            <!--<button class="btn-add2" type="button" onclick="javascript:add_row('tblItem')">Tambah</button>-->
                            <a href="javascript:;" onclick="javascript:add_row('tblItem')">Tambah</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</div>