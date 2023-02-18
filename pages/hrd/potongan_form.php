<style>
.hide{
    display: none;
}
</style>

<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
            $action = strtoupper($_GET['action']);
            echo $action .' Master Potongan';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="potongan_form" method="post" action="<?php echo BASE_URL ?>pages/hrd/potongan.php?action=process" class="ui-helper-clearfix">
            <?php
                if(strtolower($_GET['action']) == 'edit') {
                    echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id_penpot" name="id_penpot">';
                    $select = $db->prepare('SELECT * FROM hrd_pendapatan_potongan WHERE id_penpot = :id');
                    $select->execute(array(':id' => $_GET['id']));
                    $row = $select->fetch(PDO::FETCH_ASSOC);
                }
            ?>
              <label for="kode_penpot" class="ui-helper-reset label-control">Kode Potongan</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="kode_penpot" id="kode_penpot" value="<?php echo isset($row['kode_penpot']) ? $row['kode_penpot'] : ''; ?>">
            </div>

            <label for="nama_penpot" class="ui-helper-reset label-control">Nama Potongan</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="nama_penpot" id="nama_penpot" value="<?php echo isset($row['nama_penpot']) ? $row['nama_penpot'] : ''; ?>">
            </div>
            
            <label for="metode_pethitungan" class="ui-helper-reset label-control">Metode Perhitungan</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="metode_pethitungan" id="metode_pethitungan">
                    <option >-choose(pilih)-</option>
                    <option value="Manual Input" <?php if(isset($row['metode_pethitungan']) && $row['metode_pethitungan'] == 'Manual Input'){echo "selected";} ?>>Manual Input</option>
                    <option value="Fixed Nominal" <?php if(isset($row['metode_pethitungan']) && $row['metode_pethitungan'] == 'Fixed Nominal'){echo "selected";} ?>>Fixed Nominal</option>
                    <option value="Per Hari Hadir" <?php if(isset($row['metode_pethitungan']) && $row['metode_pethitungan'] == 'Per Hari Hadir'){echo "selected";} ?>>Per Hari Hadir</option>
                </select>
                <select name="tipe" id="tipe" class="<?php if(isset($row['metode_pethitungan']) && $row['metode_pethitungan'] == 'Fixed Nominal'){echo "";}else{echo "hide";} ?>">
                    <option value="">-choose(pilih)-</option>
                    <option value="Total Upah Tetap" <?php if(isset($row['type_pengaruh']) && $row['type_pengaruh'] == 'Total Upah Tetap'){echo "selected";} ?>>Total Upah Tetap</option>
                    <option value="Upah BPJS Tenaga Kerja" <?php if(isset($row['type_pengaruh']) && $row['type_pengaruh'] == 'Upah BPJS Tenaga Kerja'){echo "selected";} ?>>Upah BPJS Tenaga Kerja</option>
                    <option value="Upah BPJS Kesehatan" <?php if(isset($row['type_pengaruh']) && $row['type_pengaruh'] == 'Upah BPJS Kesehatan'){echo "selected";} ?>>Upah BPJS Kesehatan</option>
                </select>
            </div>

            <label for="persentase_kehadiran" class="ui-helper-reset label-control">Dipengaruhi Persentase Kehadiran</label>
            <div class="ui-corner-all form-control">
                
                <select class="required" name="persentase_kehadiran" id="persentase_kehadiran">
                    <option value="">-choose(pilih)-</option>
                    <option value="1" <?php if(isset($row['persentase_kehadiran']) && $row['persentase_kehadiran'] == '1'){echo "selected";} ?>>Ya</option>
                    <option value="0" <?php if(isset($row['persentase_kehadiran']) && $row['persentase_kehadiran'] == '0'){echo "selected";} ?>>Tidak</option>
                </select>
            </div>

            <label for="objek_pph21" class="ui-helper-reset label-control">Mempengaruhi Total Potongan</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="total_pendapatan" id="total_pendapatan">
                    <option >-choose(pilih)-</option>
                    <option value="1" <?php if(isset($row['total_pendapatan']) && $row['total_pendapatan'] == '1'){echo "selected";} ?>>Ya</option>
                    <option value="0" <?php if(isset($row['total_pendapatan']) && $row['total_pendapatan'] == '0'){echo "selected";} ?>>Tidak</option>
                </select>
            </div>

            <label for="objek_pph21" class="ui-helper-reset label-control">Objek Pajak PPh21</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="objek_pph21" id="objek_pph21">
                    <option value="">-choose(pilih)-</option>
                    <option value="Mengurangi" <?php if(isset($row['objek_pph21']) && $row['objek_pph21'] == 'Mengurangi'){echo "selected";} ?>>Mengurangi</option>
                    <option value="Tidak Berpengaruh" <?php if(isset($row['objek_pph21']) && $row['objek_pph21'] == 'Tidak Berpengaruh'){echo "selected";} ?>>Tidak Berpengaruh</option>
                </select>
            </div>

            <label for="sifat" class="ui-helper-reset label-control">Sifat</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="sifat" id="sifat">
                    <option value="">-choose(pilih)-</option>
                    <option value="Tetap" <?php if(isset($row['sifat']) && $row['sifat'] == 'Tetap'){echo "selected";} ?>>Tetap</option>
                    <option value="Tidak Tetap" <?php if(isset($row['sifat']) && $row['sifat'] == 'Tidak Tetap'){echo "selected";} ?>>Tidak Tetap</option>
                </select>
            </div>
            
        </form>
    </div>
</div>

<script>
     $("#metode_pethitungan").change(function () {
        var index = this.value;
        $('#tipe option[value=]').prop('selected', true);
        if(index == 'Fixed Nominal'){
            $( "#tipe" ).removeClass( "hide" );
        }else{
            $( "#tipe" ).addClass( "hide" );
        }
    });
</script>