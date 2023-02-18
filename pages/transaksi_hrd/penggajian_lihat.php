<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
            $action = strtoupper($_GET['action']);
            echo 'DETAIL PERHITUNGAN PPH21';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
            <?php
                $id = $_GET['id'];
                $karyawan = $_GET['karyawan'];

                $select = $db->prepare('SELECT a.*,DATE_FORMAT(tgl_upah_start, "%d/%m/%Y") as awal,DATE_FORMAT(tgl_upah_end, "%d/%m/%Y") as akhir, DATE_FORMAT(tgl_pembayaran, "%d/%m/%Y") as tanggal_pembayaran FROM `hrd_penggajian` a WHERE penggajian_id=?');
                $select->execute(array($id));
                $row = $select->fetch(PDO::FETCH_ASSOC);
                
                $select2 = $db->prepare('SELECT a.*, b.nama_karyawan, d.nama_dept, e.value as ptkp, e.nama_ptkp FROM hrd_pph21_transaksidet2 a LEFT JOIN hrd_karyawan b ON b.id_karyawan=a.id_karyawan LEFT JOIN hrd_jabatan c ON c.id_jabatan=b.id_jabatan LEFT JOIN hrd_departemen d ON d.id_dept=c.id_dept LEFT JOIN hrd_ptkp e ON e.id=a.id_ptkp WHERE a.id_penggajian=? AND a.id_karyawan=?');
                $select2->execute(array($id, $karyawan));
                $row2 = $select2->fetch(PDO::FETCH_ASSOC);
            ?>
           
           <label for="tgl_upah" class="ui-helper-reset label-control">Nama Periode Penggajian</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="nama_periode" id="nama_periode" class="required" value="<?php echo isset($row['nama_periode']) ? $row['nama_periode'] : ''; ?>" readonly>
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">Tipe Periode</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="nama_periode" id="nama_periode" class="required" value="<?php echo isset($row['tipe_periode']) ? $row['tipe_periode'] : ''; ?>" readonly>
            </div>

            <label for="tgl_upah" class="ui-helper-reset label-control">Periode Kerja</label>
            <div class="ui-corner-all form-control">
                <input type="text" name="tgl_upah_start" id="tgl_upah_start" class="required " value="<?php echo isset($row['awal']) ? $row['awal'] : ''; ?>" readonly> s/d
                <input type="text" name="tgl_upah_end" id="tgl_upah_end" class="required " value="<?php echo isset($row['awal']) ? $row['awal'] : ''; ?>" readonly> 
            </div>
            
            <label for="jml_periode" class="ui-helper-reset label-control">Nama Karyawan</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="nama" id="nama" value="<?php echo isset($row2['nama_karyawan']) ? $row2['nama_karyawan'] : ''; ?>"  readonly>
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">Departemen</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="departemen" id="departemen" value="<?php echo isset($row2['nama_dept']) ? $row2['nama_dept'] : ''; ?>"  readonly>
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">PTKP</label>
            <div class="ui-corner-all form-control">
                <input class="required" type="text" name="departemen" id="departemen" value="<?php echo isset($row2['nama_ptkp']) ? $row2['nama_ptkp'] : ''; ?>"  readonly>
            </div>

            <label for="jml_periode" class="ui-helper-reset label-control">Perhitungan</label>
            <div class="ui-corner-all form-control">
                <?php
                    $hasiltext = '';
                    $hasiltext2 = '';

            
                    $data = mysql_query("SELECT * FROM hrd_biayajabatan");
                    $jabatan = '';
                    while($d = mysql_fetch_array($data)){
                        $jabatan = $d['persentase'].'|'.$d['maxbiaya'];
                    }

                    $op = $row2['op'];
                    $ptkp = $row2['ptkp'];

                    $the = 0;

                    $data3 = mysql_query("SELECT id_penggajiandet, hrd_penggajiandet.id_penggajian, hrd_penggajiandet.id_karyawan, kar.id_ptkp, hrd_penggajiandet.id_penpot, hrd_penggajiandet.`value`, 
                    SUM(IF(penpot.objek_pph21<>'Tidak Berpengaruh' AND penpot.sifat='Tidak Tetap',
                    IF(penpot.metode_pethitungan='Per Hari Hadir',IF(penpot.objek_pph21='Mengurangi',-(jml_kehadiran*hrd_penggajiandet.value),(jml_kehadiran*hrd_penggajiandet.value)),
                    IF(penpot.objek_pph21='Mengurangi',-(hrd_penggajiandet.subtotal),hrd_penggajiandet.subtotal)),0)) AS op, hrd_penggajiandet.subtotal, subtotal_variabel, jml_kehadiran, wa, `status` 
                    FROM hrd_penggajiandet LEFT JOIN hrd_karyawan kar ON kar.id_karyawan=hrd_penggajiandet.id_karyawan 
                    LEFT JOIN hrd_karyawandet kardet ON kardet.id_penpot=hrd_penggajiandet.id_penpot AND kardet.id_karyawan=hrd_penggajiandet.id_karyawan
                    LEFT JOIN hrd_pendapatan_potongan penpot ON penpot.id_penpot=kardet.id_penpot
                    WHERE id_penggajian='".$id."' AND hrd_penggajiandet.id_karyawan='".$karyawan."' GROUP BY id_karyawan");

                    $thr = '0';
                    while($d3 = mysql_fetch_array($data3)){
                        $thr = $d3['op'];
                    }

                    // if($row['tipe_periode'] == 'Regular'){
                        $opx12 = ($op * 12);
                    // }
                    // else{
                    //     $opx12 = ($op * 12) + $thr;
                    // }

                    $ex = explode("|",$jabatan);
                    $persenjabatan = $ex[0];
                    $biayajabatan = $ex[1];

                    $hasiljabatan = ($persenjabatan/100) * $opx12;
                    if($hasiljabatan > $biayajabatan){
                        $hasiljabatan = $biayajabatan;
                    }

                    $pkp = $opx12 - $ptkp - $hasiljabatan;

                    if(strlen($pkp)==3){
                        $pkp = $pkp;
                    }else{
                        $pkp = roundDown($pkp,-3);
                    }   

                    $hasiltext .= "REGULAR";
                    $hasiltext .= "\n\n";
                    $hasiltext .= "Objek Pajak = ".number_format($op,2);
                    $hasiltext .= "\n";
                    // if($row['tipe_periode'] == 'Regular'){
                        $hasiltext .= "Objek Pajak x 12 = ".number_format($opx12,2);
                    // }
                    // else{
                    //     $hasiltext .= "Objek Pajak x 12 + THR = ".number_format($opx12,2);
                    // }
                    $hasiltext .= "\n";
                    $hasiltext .= "Biaya PTKP = ".number_format($ptkp,2);
                    $hasiltext .= "\n";
                    $hasiltext .= "Biaya Jabatan = ".number_format($hasiljabatan,2);
                    $hasiltext .= "\n";
                    $hasiltext .= "PKP = ".number_format($pkp,2);
                    $hasiltext .= "\n\n";

                    $pph21 = 0;

                    $data = mysql_query("SELECT * FROM hrd_pkp WHERE $pkp>(minvalue*1000000) AND $pkp<=(maksvalue*1000000)");
                    while($d = mysql_fetch_array($data)){
                        if($d['id']==1){
                            //< 60 jt
                            $hasiltext .= $d['persen1'].'% x '.number_format($pkp,2).' = '.number_format($pkp * ($d['persen1']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= "\n";

                            $pph21 = $pkp * ($d['persen1']/100);
                            // echo "1";
                        }else if($d['id']==2){
                            //60 jt - 250 jt
                            $hasiltext .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen2'].'% x '.number_format(($pkp-($d['maksvalue1'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + ($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100);
                            // echo "2";
                        }else if($d['id']==3){
                            //250 jt - 500 jt
                            $hasiltext .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen3'].'% x '.number_format(($pkp-($d['maksvalue2'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + ($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100);
                            // echo "3";
                        }else if($d['id']==4){
                            //500 jt - 5 M
                            $hasiltext .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000),2).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen4'].'% x '.number_format(($pkp-($d['maksvalue3'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + ($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100);
                            // echo "4";
                        }else{
                            //> 5 M
                            $hasiltext .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000),2).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen4'].'% x '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000),2).' = '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= $d['persen5'].'% x '.number_format(($pkp-($d['maksvalue4'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100),2);
                            $hasiltext .= "\n";
                            $hasiltext .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + (($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100) + ($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100);
                            // echo "5";
                        }
                    }

                    $hasiltext .= "Tarif PPH21 / THN= ".number_format($pph21,2);
                    $hasiltext .= "\n";
                    $pph21bulan = $pph21/12;
                    $hasiltext .= "Tarif PPH21 / BLN = ".number_format($pph21bulan,2);

                    $pph21bulankurang = $pph21bulan;
                    $pph21tahunkurang = $pph21;

                if($row['tipe_periode'] == 'THR'){
                    $data = mysql_query("SELECT * FROM hrd_biayajabatan");
                    $jabatan = '';
                    while($d = mysql_fetch_array($data)){
                        $jabatan = $d['persentase'].'|'.$d['maxbiaya'];
                    }

                    $op = $row2['op'];
                    $ptkp = $row2['ptkp'];

                    $the = 0;

                    $data3 = mysql_query("SELECT id_penggajiandet, hrd_penggajiandet.id_penggajian, hrd_penggajiandet.id_karyawan, kar.id_ptkp, hrd_penggajiandet.id_penpot, hrd_penggajiandet.`value`, 
                    SUM(IF(penpot.objek_pph21<>'Tidak Berpengaruh' AND penpot.sifat='Tidak Tetap',
                    IF(penpot.metode_pethitungan='Per Hari Hadir',IF(penpot.objek_pph21='Mengurangi',-(jml_kehadiran*hrd_penggajiandet.value),(jml_kehadiran*hrd_penggajiandet.value)),
                    IF(penpot.objek_pph21='Mengurangi',-(hrd_penggajiandet.subtotal),hrd_penggajiandet.subtotal)),0)) AS op, hrd_penggajiandet.subtotal, subtotal_variabel, jml_kehadiran, wa, `status` 
                    FROM hrd_penggajiandet LEFT JOIN hrd_karyawan kar ON kar.id_karyawan=hrd_penggajiandet.id_karyawan 
                    LEFT JOIN hrd_karyawandet kardet ON kardet.id_penpot=hrd_penggajiandet.id_penpot AND kardet.id_karyawan=hrd_penggajiandet.id_karyawan
                    LEFT JOIN hrd_pendapatan_potongan penpot ON penpot.id_penpot=kardet.id_penpot
                    WHERE id_penggajian='".$id."' AND hrd_penggajiandet.id_karyawan='".$karyawan."' GROUP BY id_karyawan");

                    $thr = '0';
                    while($d3 = mysql_fetch_array($data3)){
                        $thr = $d3['op'];
                    }

                    if($row['tipe_periode'] == 'Regular'){
                        $opx12 = ($op * 12);
                    }
                    else{
                        $opx12 = ($op * 12) + $thr;
                    }

                    $ex = explode("|",$jabatan);
                    $persenjabatan = $ex[0];
                    $biayajabatan = $ex[1];

                    $hasiljabatan = ($persenjabatan/100) * $opx12;
                    if($hasiljabatan > $biayajabatan){
                        $hasiljabatan = $biayajabatan;
                    }

                    $pkp = $opx12 - $ptkp - $hasiljabatan;

                    if(strlen($pkp)==3){
                        $pkp = $pkp;
                    }else{
                        $pkp = roundDown($pkp,-3);
                    }   

                    $hasiltext2 .= "THR";
                    $hasiltext2 .= "\n\n";
                    $hasiltext2 .= "Objek Pajak = ".number_format($op,2);
                    $hasiltext2 .= "\n";
                    // if($row['tipe_periode'] == 'Regular'){
                    //     $hasiltext2 .= "Objek Pajak x 12 = ".number_format($opx12,2);
                    // }
                    // else{
                        $hasiltext2 .= "Objek Pajak x 12 + THR = ".number_format($opx12,2);
                    // }
                    $hasiltext2 .= "\n";
                    $hasiltext2 .= "Biaya PTKP = ".number_format($ptkp,2);
                    $hasiltext2 .= "\n";
                    $hasiltext2 .= "Biaya Jabatan = ".number_format($hasiljabatan,2);
                    $hasiltext2 .= "\n";
                    $hasiltext2 .= "PKP = ".number_format($pkp,2);
                    $hasiltext2 .= "\n\n";

                    $pph21 = 0;

                    $data = mysql_query("SELECT * FROM hrd_pkp WHERE $pkp>(minvalue*1000000) AND $pkp<=(maksvalue*1000000)");
                    while($d = mysql_fetch_array($data)){
                        if($d['id']==1){
                            //< 60 jt
                            $hasiltext2 .= $d['persen1'].'% x '.number_format($pkp,2).' = '.number_format($pkp * ($d['persen1']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= "\n";

                            $pph21 = $pkp * ($d['persen1']/100);
                            // echo "1";
                        }else if($d['id']==2){
                            //60 jt - 250 jt
                            $hasiltext2 .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen2'].'% x '.number_format(($pkp-($d['maksvalue1'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + ($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100);
                            // echo "2";
                        }else if($d['id']==3){
                            //250 jt - 500 jt
                            $hasiltext2 .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen3'].'% x '.number_format(($pkp-($d['maksvalue2'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + ($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100);
                            // echo "3";
                        }else if($d['id']==4){
                            //500 jt - 5 M
                            $hasiltext2 .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000),2).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen4'].'% x '.number_format(($pkp-($d['maksvalue3'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + ($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100);
                            // echo "4";
                        }else{
                            //> 5 M
                            $hasiltext2 .= $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000,2).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000),2).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000),2).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen4'].'% x '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000),2).' = '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= $d['persen5'].'% x '.number_format(($pkp-($d['maksvalue4'] * 1000000)),2).' = '.number_format(($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100),2);
                            $hasiltext2 .= "\n";
                            $hasiltext2 .= "\n";

                            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + (($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100) + ($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100);
                            // echo "5";
                        }
                    }

                    $hasiltext2 .= "Tarif PPH21 THR / THN = ".number_format($pph21,2);
                    $hasiltext2 .= "\n";
                    $pph21bulan = $pph21 - $pph21tahunkurang + $pph21bulankurang;
                    if($pph21bulan != '0'){
                        $hasiltext2 .= "\n";
                        $hasiltext2 .=  number_format($pph21,2) .' - '. number_format($pph21tahunkurang,2) .' + '. number_format($pph21bulankurang,2) .' = '. number_format($pph21bulan,2);
                        $hasiltext2 .= "\n\n";

                    }
                    $hasiltext2 .= "Tarif PPH21 THR / BLN = ".number_format($pph21bulan,2);
                }
                ?>
                <textarea rows=17 readonly><?=$hasiltext?></textarea>
                <?php
                if($row['tipe_periode'] == 'THR'){
                    ?><textarea rows=17 readonly><?=$hasiltext2?></textarea><?php
                }
                ?>
            </div>

           
    </div>
</div>

<script>
    var hari = 0;


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