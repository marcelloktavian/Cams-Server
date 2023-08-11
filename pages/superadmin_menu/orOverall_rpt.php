<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">

    @page {
        size: A4;
        margin: 15px;
        -webkit-print-color-adjust:exact;
    }

    .header{
        border-top : 1px solid black;
        border-bottom : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .footer{
        border-top : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .right{
        text-align : right;
    }

     .center{
        text-align: center;
    }

    .judul{
        font-size: 14pt;
        font-family: "Times New Roman";
        font-weight: bold;
    }

    .judul2{
        font-size: 16pt;
        font-family: "Times New Roman";
        font-weight: bold;
    }

    .text{
        font-size: 11pt;
        font-family: "Times New Roman";
        padding: 4px;

    }
    @page {
        size: 8.5in 5.5in;
        size: landscape;
    }
</style>
<?php

function tanggal($year){
    $id = explode('-', $year);
	$month = $id[0];
	$year = $id[1];
    
    $bulan = '';
    if($month == '1'){
        $bulan = 'JANUARY';
    }else if($month == '2'){
        $bulan = 'FEBRUARY';
    }else if($month == '3'){
        $bulan = 'MARCH';
    }else if($month == '4'){
        $bulan = 'APRIL';
    }else if($month == '5'){
        $bulan = 'MAY';
    }else if($month == '6'){
        $bulan = 'JUNE';
    }else if($month == '7'){
        $bulan = 'JULY';
    }else if($month == '8'){
        $bulan = 'AUGUST';
    }else if($month == '9'){
        $bulan = 'SEPTEMBER';
    }else if($month == '10'){
        $bulan = 'OCTOBER';
    }else if($month == '11'){
        $bulan = 'NOVEMBER';
    }else if($month == '12'){
        $bulan = 'DECEMBER';
    }

    $yearnya = $bulan.' '.$year;
	return $yearnya;
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "Minus ". trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }     		
    return $hasil;
}
include("../../include/koneksi.php");
$year = $_GET['start'];
?>
<head>
  <title>OUTSTANDING RECEIVABLE REPORT</title>
</head>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan=17 class="judul" align='center'>
            <b>OUTSTANDING RECEIVABLE REPORT<br>
            PERIOD <?=$year?>
            <br><br>
        </td>
    </tr>
    <tr>
        <td class="header text" width='2%' align='left'>
            <b>No.
        </td>
        <td class="header text" width='25%' align='left'>
            <b>Customer
        </td>
        <td class="header text" width='5%' align='right'>
            <b>Saldo Piutang
        </td>
        <?php
        $arrbulan = array("January","February","March","April","May","June","July","August","September","October","November","December");
            $bulan = date("m");
            $selisih = date("Y") - $year;
            $total = (($bulan-1)*30)+(($selisih*12)*30);
            $j = 0;
            for($i=12;$i>0;$i--){
                ?>
                    <td class="header text" width='5%' align='right'>
                        <b><?=$arrbulan[$j]?><br>N <?=$total?>
                    </td>
                <?php
                if($total > 0){
                    $total -= 30;
                }
                $j++;
            }
        ?>
        <td class="header text" width='5%' align='right'>
            <b>Total
        </td>
        <td class="header text" width='5%' align='right'>
            <b>Total + Saldo Piutang
        </td>
    </tr>
    <?php
    $sql = "SELECT 
        id_akun_piutang, 
        no_akun_piutang, 
        nama_akun_piutang,
        keterangan_piutang,
        REPLACE(SUBSTR(no_akun_piutang, 7),0, '') AS id_customer,
        SUBSTR(nama_akun_piutang, 15) AS nama_customer, 
        total_piutang,
        COALESCE(total_piutang-total_pembayaran,0) AS pelunasan,
        COALESCE(saldo_pembayaran, 0) AS saldo_pembayaran,
        COALESCE(saldo_piutang, 0) AS saldo_piutang,
        (januari_piutang) AS januari,
        (februari_piutang) AS februari,
        (maret_piutang) AS maret,
        (april_piutang) AS april,
        (mei_piutang) AS mei,
        (juni_piutang) AS juni,
        (juli_piutang) AS juli,
        (agustus_piutang) AS agustus,
        (september_piutang) AS september,
        (oktober_piutang) AS oktober,
        (november_piutang) AS november,
        (december_piutang) AS december
        FROM (
        SELECT 
            a.id_akun AS id_akun_piutang, 
            a.no_akun AS no_akun_piutang, 
            a.nama_akun AS nama_akun_piutang, 
            b.keterangan AS keterangan_piutang,
            SUM(a.debet) AS total_piutang,
            SUM(CASE WHEN YEAR(a.lastmodified) < '$year' THEN a.debet ELSE 0 END) AS saldo_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 1 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS januari_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 2 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS februari_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 3 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS maret_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 4 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS april_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 5 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS mei_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 6 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS juni_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 7 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS juli_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 8 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS agustus_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 9 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS september_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 10 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS oktober_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 11 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS november_piutang,
            SUM(CASE WHEN MONTH(a.lastmodified) = 12 AND YEAR(a.lastmodified) >= '$year' THEN a.debet ELSE 0 END) AS december_piutang
        FROM jurnal_detail a LEFT JOIN jurnal b ON a.id_parent=b.id
        WHERE a.nama_akun LIKE 'Piutang%' 
            AND a.kredit = 0 
            AND a.deleted = 0
            AND b.deleted = 0 
        GROUP BY a.id_akun
        ) AS a LEFT JOIN(
        SELECT 
            a.id_akun AS id_akun_pembayaran, 
            a.no_akun AS no_akun_pembayaran, 
            a.nama_akun AS nama_akun_pembayaran, 
            b.keterangan AS keterangan_pembayaran,
            SUM(a.kredit) AS total_pembayaran,
            SUM(CASE WHEN YEAR(a.lastmodified) < '$year' THEN a.kredit ELSE 0 END) AS saldo_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 1 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS januari_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 2 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS februari_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 3 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS maret_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 4 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS april_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 5 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS mei_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 6 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS juni_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 7 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS juli_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 8 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS agustus_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 9 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS september_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 10 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS oktober_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 11 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS november_pembayaran,
            SUM(CASE WHEN MONTH(a.lastmodified) = 12 AND YEAR(a.lastmodified) >= '$year' THEN a.kredit ELSE 0 END) AS december_pembayaran
        FROM jurnal_detail a LEFT JOIN jurnal b ON a.id_parent=b.id
        WHERE a.nama_akun LIKE 'Piutang%' 
            AND a.debet = 0 
            AND a.deleted = 0 
            AND b.deleted = 0
        GROUP BY a.id_akun
        ) AS b ON a.id_akun_piutang = b.id_akun_pembayaran
        ORDER BY pelunasan DESC";
    $no = 1;
    $total_saldopiutang = 0;
    $total_januari = 0;
    $total_febuari = 0;
    $total_maret = 0;
    $total_april = 0;
    $total_mei = 0;
    $total_juni = 0;
    $total_juli = 0;
    $total_agustus = 0;
    $total_september = 0;
    $total_oktober = 0;
    $total_november = 0;
    $total_desember = 0;
    $total_total = 0;
    $sq = mysql_query($sql);
    while($rs=mysql_fetch_array($sq))
    { 
        ?>
        <tr>
            <td class="text" align="center">
                <?=number_format($no,0,',','.')?>
            </td>
            <td class="text" align='left'>
                <?=$rs['nama_customer']?>
            </td>
             <td class="text" align='right'>
                <?=number_format($rs['saldo_piutang'],0,",",".")?>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('01','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['januari'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('02','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['februari'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('03','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['maret'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('04','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['april'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('05','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['mei'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('06','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['juni'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('07','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['juli'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('08','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['agustus'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('09','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['september'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('10','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['oktober'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('11','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['november'],0,",",".")?>
                </div>
            </td>
            <td class="text" align='right'>
                <div style='cursor:pointer;' onclick="opendetail('12','<?=$year?>','<?=$rs['id_customer']?>','<?= substr($rs['keterangan_piutang'],10, 4) ?>')"><?=number_format($rs['december'],0,",",".")?>
                </div>
            </td>
            <?php
                $total = $rs['januari'] + $rs['februari'] + $rs['maret'] + $rs['april'] + $rs['mei'] + $rs['juni'] + $rs['juli'] + $rs['agustus'] + $rs['september'] + $rs['oktober'] + $rs['november'] + $rs['december'];
            ?>
            <td class="text" align='right'>
                <?=number_format($total,0,",",".")?>
            </td>
            <td class="text" align='right'>
                <?=number_format(($total+$rs['saldo_piutang']),0,",",".")?>
            </td>
        </tr>
        <?php
         $total_saldopiutang += $rs['saldo_piutang'];
         $total_januari += $rs['januari'];
         $total_febuari += $rs['februari'];
         $total_maret += $rs['maret'];
         $total_april += $rs['april'];
         $total_mei += $rs['mei'];
         $total_juni += $rs['juni'];
         $total_juli += $rs['juli'];
         $total_agustus += $rs['agustus'];
         $total_september += $rs['september'];
         $total_oktober += $rs['oktober'];
         $total_november += $rs['november'];
         $total_desember += $rs['december'];
         $total_total += $total;
        $no++;
    }
    ?>
    <tr>
        <td class="footer text" width='2%' align="right" colspan=2>
            <b>Subtotal</b>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_saldopiutang,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_januari,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_febuari,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_maret,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_april,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_mei,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_juni,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_juli,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_agustus,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_september,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_oktober,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_november,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_desember,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format($total_total,0,",",".")?>
        </td>
        <td class="footer text" width='5%' align='right'>
            <b><?=number_format(($total_total+$total_saldopiutang),0,",",".")?>
        </td>
    </tr>
<table>
<script>
    function opendetail(bulan, tahun, cust, tipe){
        var awal = tahun+"-"+bulan+"-01";
        var akhir = tahun+"-"+bulan+"-31";
        window.open('orOverall_rpt_detail.php?awal='+awal+'&akhir='+akhir+'&cust='+cust+'&tipe='+tipe);
    }
    //  window.print();
</script>
