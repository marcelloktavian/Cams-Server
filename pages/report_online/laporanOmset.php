<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Laporan Omset
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="reportOmset" method="" action="" class="ui-helper-clearfix">
        	<label for="startOmset" class="ui-helper-reset label-control">Tahun</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <select name="startOmset" id="startOmset">
				 <!-- isinya pakai js -->
				 </select>
				</td>
				</tr>
				</table>
            </div>
			
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/report_online/laporanOmsetPrint.php?action=preview&year='+$('#startOmset').val())" class="btn" type="button">PRINT</button>
            </div>
       	</form>
   	</div>
	  <script>
  $('#startOmset').each(function() {

var year = (new Date()).getFullYear();
var current = year;
year -= 10;
for (var i = 0; i < 20; i++) {
  if ((year+i) == current)
    $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
  else
    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
}

});
</script>
</div>
