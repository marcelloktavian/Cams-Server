<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Penjualan Barang
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="startbrg" name="startbrg">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="endbrg" name="endbrg">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/report_jualbrg.php?action=preview&start='+$('#startbrg').val()+'&end='+$('#endbrg').val())" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
</div>
