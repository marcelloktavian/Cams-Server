<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        ADD PROJECT
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="project_form" method="post" action="<?php echo BASE_URL ?>pages/project.php?action=processadd" class="ui-helper-clearfix">
        	<label for="project_name" class="ui-helper-reset label-control">Project Name</label>
            <div class="ui-corner-all form-control">
                <input value="" type="text" class="required" id="project_name" name="project_name">
            </div>
            <label for="project_description" class="ui-helper-reset label-control">Project Description</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="project_description" name="project_description"></textarea>
            </div>
            <label for="project_start" class="ui-helper-reset label-control">Project Start</label>
            <div class="ui-corner-all form-control">
                <input value="" type="text" class="required datepicker" id="project_start" name="project_start">
            </div>
            <label for="project_end" class="ui-helper-reset label-control">Project End</label>
            <div class="ui-corner-all form-control">
                <input value="" type="text" class="required datepicker" id="project_end" name="project_end">
            </div>
            <label for="total_angket" class="ui-helper-reset label-control">Total Angket</label>
            <div class="ui-corner-all form-control">
                <input value="" type="text" class="required number" id="total_angket" name="total_angket">
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