<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' Setting Tunjangan';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="tunjangan_form" method="post" action="<?php echo BASE_URL ?>pages/hrd/tunjangan.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="tun_id" name="tun_id">';
					$select = $db->prepare('SELECT * FROM tabel_tunjangan WHERE tun_id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="nama_tun" class="ui-helper-reset label-control">Nama Tunjangan</label>
            <div class="ui-corner-all form-control">
            <input value="<?php echo isset($row['nama_tun']) ? $row['nama_tun'] : ''; ?>" type="text" class="required" id="nama_tun" name="nama_tun" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Kesehatan Perusahaan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_kesehatan_per']) ? $row['b_kesehatan_per'] : ''; ?>" type="number" class="required" id="b_kesehatan_per" name="b_kesehatan_per" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Perusahaan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_per']) ? $row['b_per'] : ''; ?>" type="number" class="required" id="b_per" name="b_per" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Kesehatan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_kesehatan']) ? $row['b_kesehatan'] : ''; ?>" type="number" class="required" id="b_kesehatan" name="b_kesehatan" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Kecelakaan</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_kecelakaan']) ? $row['b_kecelakaan'] : ''; ?>" type="number" class="required" id="b_kecelakaan" name="b_kecelakaan" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Hari Tua</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_haritua']) ? $row['b_haritua'] : ''; ?>" type="number" class="required" id="b_haritua" name="b_haritua" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Kematian</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_kematian']) ? $row['b_kematian'] : ''; ?>" type="number" class="required" id="b_kematian" name="b_kematian" >
            </div>
            <label for="sehat" class="ui-helper-reset label-control">BPJS Pensiun</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['b_pensiun']) ? $row['b_pensiun'] : ''; ?>" type="number" class="required" id="b_pensiun" name="b_pensiun" >
            </div>
            <label for="as_default" class="ui-helper-reset label-control">Set As Default</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="as_default" id="as_default">
                    <option value=''>--PILIH--</option>    
                    <option name="as_default" id="as_default" value="Y" <?php if(isset($row['as_default']) && $row['as_default'] == 'Y'){echo "selected";} ?>>Ya</option>
                    <option name="as_default" id="as_default" value="T" <?php if(isset($row['as_default']) && $row['as_default'] == 'T'){echo "selected";} ?>>Tidak</option>
                </select>
            </div>
            <label for="aktif" class="ui-helper-reset label-control">Aktif / Tidak Aktif</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="aktif" id="aktif">
                    <option value=''>--PILIH--</option>
                    <option name="aktif" id="aktif" value="Y" <?php if(isset($row['aktif']) && $row['aktif'] == 'Y'){echo "selected";} ?>>Ya</option>
                    <option name="aktif" id="aktif" value="T" <?php if(isset($row['aktif']) && $row['aktif'] == 'T'){echo "selected";} ?>>Tidak</option>
                </select>
            </div>
        </form>
    </div>
</div>