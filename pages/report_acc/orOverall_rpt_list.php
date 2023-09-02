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
    tr:not(:nth-last-child(2)) .child-row{
        border-bottom: 1px dashed lightgrey;
    }

    .child-row{
        border-left: 1px dotted lightgrey;
        border-right: 1px dotted lightgrey;
    }
</style>
<?php
include("../../include/koneksi.php");

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

if(isset($_GET['tahun']) && $_GET['tahun'] != ''){
    $tahun = $_GET['tahun'];
} else {
    $tahun = "";
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
        <td class="header text" width='7%' align='left'>
            <b>Nomor Jurnal
        </td>
        <td class="header text" width='10%' align='left'>
            <b>Tanggal Jurnal
        </td>
        <td class="header text" width='10%' align='right'>
            <b>Total Piutang</div>
        </td>
        <td class="header text" width='10%' align='right'>
            <b>Total Pembayaran</div>
        </td>
        <td class="header text" width='40%' align='center'>
            <b>Keterangan</div>
        </td>
    </tr>
    <?php
    $no = 1;
    $total_debet = 0;
    $total_kredit = 0;

    $sql = "SELECT a.*,b.keterangan as `desc`,b.tgl,b.no_jurnal FROM jurnal_detail a LEFT JOIN jurnal b ON a.id_parent=b.id WHERE a.nama_akun LIKE CONCAT('Piutang %".$tipe."% %".$rs_customer['nama']."%') AND b.deleted=0 AND a.deleted=0 AND YEAR(tgl) <= '".$tahun."'";

    $sq = mysql_query($sql);
    $no = 1;
    while($rs=mysql_fetch_array($sq)){ 
        ?>
        <tr>
            <td class="text child-row" align="left">
                <?=number_format($no,0,',','.')?>
            </td>
            <td class="text child-row" align="left">
                <?=$rs['no_jurnal']?>
            </td>
            <td class="text child-row" align="left">
                <?=date_format(date_create($rs['tgl']),"d/m/Y")?>
            </td>
            <td class="text child-row" align="right">
                <?=number_format($rs['debet'],0,",",".")?>
            </td>
            <td class="text child-row" align="right">
                <?=number_format($rs['kredit'],0,",",".")?>
            </td>
            <td class="text child-row" align="center">
                <?=$rs['desc']?>
            </td>
        </tr>
    <?php
        $total_debet += $rs['debet'];
        $total_kredit += $rs['kredit'];
        $no ++;
    }
    ?>
    <tr>
        <td class="footer text" align='right' colspan=3>Grand Total Piutang</td>
        <td class="footer text" width='2%' align="right">
            <b><?=number_format($total_debet,0,",",".")?></b>
        </td>
        <td class="footer text" width='2%' align="right">
            <b><?=number_format($total_kredit,0,",",".")?></b>
        </td>
        <td class="footer text">Sisa Piutang <b><?=number_format($total_debet-$total_kredit,0,",",".")?></b>
        </td>
    </tr>
<table>
<script>
    //  window.print();
</script>
