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
include("../../include/koneksi.php");

if(isset($_GET['awal']) && $_GET['awal'] != ''){
    $awal = $_GET['awal'];
} else {
    $awal = date('Y-m-d');
}

if(isset($_GET['akhir']) && $_GET['akhir'] != ''){
    $akhir = $_GET['akhir'];
} else {
    $akhir = date('Y-m-d');
}

if(isset($_GET['cust']) && $_GET['cust'] != ''){
    $cust = $_GET['cust'];
} else {
    $cust = "";
}

if(isset($_GET['tipe']) && $_GET['tipe'] != ''){
    $tipe = $_GET['tipe'];
} else {
    $tipe = "";
}

function tanggal($tgl){
    $id = explode('-', $tgl);
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

    $tglnya = $bulan.' '.$year;
	return $tglnya;
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

$sql_customer = "";
if($_GET['tipe']=='B2B'){
    $sql_customer = "SELECT nama, no_telp FROM mst_b2bcustomer WHERE id='".$cust."'";
} else {
    $sql_customer = "SELECT nama, hp AS no_telp FROM mst_dropshipper WHERE id='".$cust."'";
}

$sq_customer = mysql_query($sql_customer);
$rs_customer = mysql_fetch_array($sq_customer);

?>
<head>
  <title>OUTSTANDING RECEIVABLE DETAIL REPORT</title>
</head>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan=8 class="judul" align='center'>
            <b>OUTSTANDING RECEIVABLE REPORT<br>
            <?= strToUpper($rs_customer['nama']) ?>
            <br><br>
        </td>
    </tr>
    <tr>
    <td class="header text" width='3%' align='left'>
            <b>No
        </td>
        <td class="header text" width='5%' align='left'>
            <b>Nomor Jurnal
        </td>
        <td class="header text" width='5%' align='left'>
            <b>Tanggal Jurnal
        </td>
        <td class="header text" width='10%' align='right'>
            <b>Total Piutang</div>
        </td>
        <td class="header text" width='50%' align='right'>
            <b>Keterangan</div>
        </td>
    </tr>
    <?php
    $no = 1;
    $total = 0;
    $payment = 0;
    $remaining = 0; 

    $sql = "SELECT * FROM jurnal WHERE keterangan LIKE CONCAT('Piutang %".$tipe."% %".$rs_customer['nama']."%') OR keterangan LIKE CONCAT('Penjualan %".$tipe."% %".$rs_customer['nama']."%') AND deleted=0 AND tgl BETWEEN '".$awal."' AND '".$akhir."'";

    $sq = mysql_query($sql);
    $no = 1;
    while($rs=mysql_fetch_array($sq)){ 
        ?>
        <tr>
            <td class="text" align="left">
                <?=number_format($no,0,',','.')?>
            </td>
            <td class="text" align="left">
                <?=$rs['no_jurnal']?>
            </td>
            <td class="text" align="left">
                <?=$rs['tgl']?>
            </td>
            <td class="text" align="right">
                <?=number_format($rs['total_debet'],0,",",".")?>
            </td>
            <td class="text" align="right">
                <?=$rs['keterangan']?>
            </td>
        </tr>
    <?php
        $total += $rs['total_debet'];
        $no ++;
    }
    ?>
    <tr>
        <td class="footer text" align='right' colspan=3>Grand Total Piutang</td>
        <td class="footer text" width='2%' align="right">
            <b><?=number_format($total,0,",",".")?>
        </td>
        <td class="footer text">
        </td>
    </tr>
<table>
<script>
    //  window.print();
</script>
