<!-- <script language="javascript">
$().ready(function() {	
		$("#iddep").autocomplete("/angket/pages/namakar.php", {
		width: 158
  });
   $("#iddep").result(function(event, data, formatted) {
	var nama = document.getElementById("iddep").value;
	$.ajax({
		url : '/angket/pages/ambildatakar.php?nama='+nama,
		dataType: 'json',
		data: "iddep="+formatted,
		success: function(data) {
		var id  = data.ID_Card;
			$('#ID_Card').val(id);
			
        }
	});	
			
	});
  });

</script> -->
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' Master Wages';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="karyawan_form" method="post" action="<?php echo BASE_URL ?>pages/transaksi_hrd/wages.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="upah_id" name="upah_id">';
					$select = $db->prepare('SELECT *,DATE_FORMAT(tgl_upah, "%d/%m/%Y") as tglupah FROM pengupahan WHERE upah_id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
           
            <label for="tgl_upah" class="ui-helper-reset label-control">Tanggal Kerja</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="tgl_upah" id="tgl_upah" class="required datepicker" value="<?php echo isset($row['tglupah']) ? $row['tglupah'] : ''; ?>">
            </div>
            
             <?php
	        	if(strtolower($_GET['action']) != 'edit') {
                    ?>
                    <label for="tipe_kar" class="ui-helper-reset label-control">Tipe Karyawan</label>
                    <div class="ui-corner-all form-control">
                    <select class="required" id="tipe_kar" name="tipe_kar" placeholder="TYPE">
                        <option value=''>--PILIH--</option>
                        <option value="Monthly" id="tipe_kar" name="tipe_kar" <?php if(isset($row['tipe_kar']) && $row['tipe_kar'] == 'Monthly'){echo "selected";} ?>>Monthly</option>
                        <option value="Daily" id="tipe_kar" name="tipe_kar" <?php if(isset($row['tipe_kar']) && $row['tipe_kar'] == 'Daily'){echo "selected";} ?>>Daily</option>
                    </select>
                    </div>
            <?php }
	        ?>
            <?php
	        	if(strtolower($_GET['action']) != 'edit') {
                    ?>
			<label for="jml_periode" class="ui-helper-reset label-control">Jumlah Periode</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="number" name="jml_periode" id="jml_periode" value="<?php echo isset($row['jml_periode']) ? $row['jml_periode'] : ''; ?>">
            </div>
            <?php }
	        ?>
        </form>
    </div>
</div>

<script>
    $('#tgl_upah').datepicker({
		dateFormat: "dd/mm/yy"
	});
</script>