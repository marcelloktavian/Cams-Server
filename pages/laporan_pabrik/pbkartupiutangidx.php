<?php require_once '../../include/config.php' ?>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Kartu Piutang Pabrik
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="startdatekppb" name="startdatekppb">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="enddatekppb" name="enddatekppb">
				</td>
				</tr>
				</table>
            </div>
			<label for="lblcust_jual" class="ui-helper-reset label-control">Customer</label>
            
			<div class="ui-corner-all form-control">
                <select class="required" name="id_pelangganjualkppb" id="id_pelanggankppb">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM tblsupplier where (type=2) and (id<>0) ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
                	?>
                </select>
            </div>
			
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<!--
				<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/accreport_kartupiutang.php?action=preview&start='+$('#startdatekp').val()+'&end='+$('#enddatekp').val()+'&id='+$('#id_pelanggankp').val())" class="btn" type="button">CETAK</button>
            	-->
				<button onclick="cek_pelanggan()" class="btn" type="button">CETAK</button>
            </div>
       	</form>
   	</div>
	<script type="text/javascript">
	$('#startdatekppb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddatekppb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdatekppb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddatekppb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	function cek_pelanggan(){
	var pelanggan= document.getElementById("id_pelanggankppb").value;
		if (pelanggan == ''){	
		alert("Maaf Pelanggan Belum dipilih!!");	
		}
		else
		{
		window_open('<?php echo BASE_URL ?>pages/laporan_pabrik/pbkartupiutang.php?action=preview&start='+$('#startdatekppb').val()+'&end='+$('#enddatekppb').val()+'&id='+$('#id_pelanggankppb').val());	
		}
	}	
	</script>
	
</div>
