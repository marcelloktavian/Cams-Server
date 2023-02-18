<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
            $action = strtoupper($_GET['action']);
            echo $action .' Penggajian';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="karyawan_form" method="post" action="<?php echo BASE_URL ?>pages/transaksi_hrd/penggajian.php?action=process" class="ui-helper-clearfix">
            <?php
                if(strtolower($_GET['action']) == 'edit') {
                    echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="penggajian_id" name="penggajian_id">';
                    $select = $db->prepare('SELECT *,DATE_FORMAT(tgl_upah_start, "%d/%m/%Y") as tglupah_start,DATE_FORMAT(tgl_upah_end, "%d/%m/%Y") as tglupah_end FROM penggajian WHERE penggajian_id = :id');
                    $select->execute(array(':id' => $_GET['id']));
                    $row = $select->fetch(PDO::FETCH_ASSOC);
                }
            ?>
           
           <label for="tgl_upah" class="ui-helper-reset label-control">Nama Periode Penggajian</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="nama_periode" id="nama_periode" class="required" value="<?php echo isset($row['nama_periode']) ? $row['nama_periode'] : ''; ?>">
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">Tipe Periode</label>
            <div class="ui-corner-all form-control">
                <select  class="required"  id='tipeperiode' name='tipeperiode'>
                    <option value=''>-choose(pilih)-</option>
                    <option value='Regular'>Regular</option>
                    <option value='THR'>THR</option>
                </select>
            </div>

            <label for="tgl_upah" class="ui-helper-reset label-control">Periode Kerja</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="tgl_upah_start" id="tgl_upah_start" class="required datepicker" value="<?php echo isset($row['tglupah_start']) ? $row['tglupah_start'] : ''; ?>" onchange="hitunghari()"> s/d
                <input type="text" name="tgl_upah_end" id="tgl_upah_end" class="required datepicker" value="<?php echo isset($row['tglupah_end']) ? $row['tglupah_end'] : ''; ?>" onchange="hitunghari()"> 
            </div>
            
            <label for="jml_periode" class="ui-helper-reset label-control">Jumlah Hari Kerja</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="number" name="jml_periode" id="jml_periode" value="<?php echo isset($row['jml_periode']) ? $row['jml_periode'] : ''; ?>" onkeyup='validasi()'>
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">Tipe Karyawan</label>
            <div class="ui-corner-all form-control">
                <select  class="required"  id='tipe' name='tipe'>
                    <option value=''>-choose(pilih)-</option>
                    <option value='Mingguan'>Mingguan</option>
                    <option value='Bulanan'>Bulanan</option>
                </select>
            </div>

            <label for="tgl_upah" class="ui-helper-reset label-control">Tanggal Pembayaran</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="tgl_pembayaran" id="tgl_pembayaran" class="required datepicker" value="<?php echo isset($row['tgl_pembayaran']) ? $row['tgl_pembayaran'] : ''; ?>">
            </div>
        </form>
    </div>
</div>

<script>
    var hari = 0;

    $('#tgl_upah_start').datepicker({
        dateFormat: "dd/mm/yy"
    });
    $('#tgl_upah_end').datepicker({
        dateFormat: "dd/mm/yy"
    });
    $('#tgl_pembayaran').datepicker({
        dateFormat: "dd/mm/yy"
    });

    function hitunghari(){
        var start = new Date($("#tgl_upah_start").val().substring(3, 5)+'/'+$("#tgl_upah_start").val().substring(0, 2)+'/'+$("#tgl_upah_start").val().substring(6, 10));
        var end = new Date($("#tgl_upah_end").val().substring(3, 5)+'/'+$("#tgl_upah_end").val().substring(0, 2)+'/'+$("#tgl_upah_end").val().substring(6, 10));

        var Difference_In_Time = end.getTime() - start.getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

        hari = Difference_In_Days;
    }

    function validasi(){
        var jumlah = $("#jml_periode").val();
        console.log(jumlah);
        if(jumlah !== ''){
            if(jumlah > hari){
                $("#jml_periode").val(hari);
            }else if(jumlah < 0){
                $("#jml_periode").val('0');
            }
        }
    }
</script>