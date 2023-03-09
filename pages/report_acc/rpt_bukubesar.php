<html><head><script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<style type="text/css">

@page {
        size: A4;
        margin: 15px;
        /* -webkit-print-color-adjust:exact; */
    }

    .header{
        border-top : 1px solid black;
        border-left : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .header2{
        border-top : 1px solid black;
        border-left : 1px solid black;
        border-right : 1px solid black;
        padding: 3px;
        font-weight: bold;

    }

    .footer{
        border-top : 1px solid black;
        border-bottom : 1px solid black;
        border-left : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .footer2{
        border-top : 1px solid black;
        border-bottom : 1px solid black;
        border-left : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .footer3{
        border-top : 1px solid black;
        border-bottom : 1px solid black;
        border-left : 1px solid black;
        border-right : 1px solid black;
        padding: 3px;
        font-weight: bold;
    }

    .detail{
        border-top : 1px solid black;
        border-left : 1px solid black;
        padding: 3px;
    }

    .detail2{
        border-left : 1px solid black;
        border-right : 1px solid black;
        padding: 3px;
    }

    .detail3{
        border-left : 1px solid black;
        padding: 3px;
    }

    .detail4{
        border-top : 1px solid black;
        border-left : 1px solid black;
        border-right : 1px solid black;
        padding: 3px;
    }

    .detail5{
        border-left : 1px solid black;
        padding: 3px;
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
    /* @page {
        size: 8.5in 5.5in;
        size: landscape;
    } */
</style>
<?php
require "../../include/koneksi.php";
$startdate = '01/'.$_GET['start'];
$enddate = '31/'.$_GET['start'];

$arrNamaBulan = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"Desember");

$akun = $_GET['akun'];
if($akun == ''){
    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%m') AS bulan, DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%Y') AS tahun FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') GROUP BY det.no_akun ORDER BY det.no_akun ASC"; 
}else{
    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%m') AS bulan, DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%Y') AS tahun FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND det.`no_akun`='$akun' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') GROUP BY det.no_akun ORDER BY det.no_akun ASC";
}

$result= mysql_query($sql);
$data = mysql_fetch_array($result); 

// header("Content-type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; filename=BUKU BESAR ".STRTOUPPER($arrNamaBulan[$data['bulan']])." ".$data['tahun'].".xls");
// header("Cache-Control: no-cache, must-revalidate");
// header("Pragma: no-cache");

?>
</head><body><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <td colspan="9" class="judul" align="center">
            PT. AGUNG KEMUNINGWIJAYA<br>
            BUKU BESAR<br>
            PERIODE <?= STRTOUPPER($arrNamaBulan[$data['bulan']])?> <?=$data['tahun']?><br>
            <br>
        </td>
    </tr>
    <?php
    $akun = '';
    $result2= mysql_query($sql);
    while($data2 = mysql_fetch_array($result2)){
        if($akun != $data2['no_akun']){
    ?>
    <tr>
        <td class="header text center" width="10%" colspan=5></td>
        <td class="header2 text left" width="10%" colspan=4>
            <b>NOMOR AKUN <?=$data2['no_akun']?></b>
        </td>
    </tr>
    <tr>
        <td class="header text center" width="10%" >
            <center><b>TANGGAL</b></center>
        </td>
        <td class="header text center" width="10%" >
            <center><b>NO JURNAL</b></center>
        </td>
        <td class="header text center" width="10%" >
            <center><b>NO AKUN</b></center>
        </td>
        <td class="header text center" width="13%" >
            <center><b>NAMA AKUN</b></center>
        </td>
        <td class="header text center" width="17%" >
            <center><b>DESKRIPSI</b></center>
        </td>
        <td class="header text center" width="10%" >
            <center><b>DEBET</b></center>
        </td>
        <td class="header text center" width="10%" >
            <center><b>KREDIT</b></center>
        </td>
        <td class="header text center" width="10%">
            <center><b>SALDO DEBET</b></center>
        </td>
        <td class="header2 text center" width="10%">
            <center><b>SALDO KREDIT</b></center>
        </td>
    </tr>
    <?php } 

    if($akun == ''){
        $sql3="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
        LEFT JOIN jurnal mst ON mst.id=det.id_parent
        WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['no_akun']."' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, no_jurnal ASC, det.id ASC "; 
    }else{
        $sql3="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
        LEFT JOIN jurnal mst ON mst.id=det.id_parent
        WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['no_akun']."' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, det.no_akun ASC, no_jurnal ASC, det.id ASC ";
    }
    $debet = 0;
    $kredit = 0;
    $result3= mysql_query($sql3);
    while($data3 = mysql_fetch_array($result3)){
        ?>
        <tr>
            <td class='detail text center'><?=$data3['tanggal']?></td>
            <td class='detail text center'><?=$data3['no_jurnal']?></td>
            <td class='detail text'><?=$data3['no_akun']?></td>
            <td class='detail text'><?=$data3['nama_akun']?></td>
            <td class='detail text'><?=$data3['keterangan']?></td>
            <td class='detail text right'><?=number_format($data3['debet'],0,',','.')?></td>
            <td class='detail text right'><?=number_format($data3['kredit'],0,',','.')?></td>
            <td class='detail text right'><?=number_format(0,0,',','.')?></td>
            <td class='detail4 text right'><?=number_format(0,0,',','.')?></td>
        </tr>
        <?php
        $debet += $data3['debet'];
        $kredit += $data3['kredit'];
    }

        if($akun != $data2['no_akun']){
    ?>
    <tr>
        <td class="footer text" align="right" colspan="5"><b>TOTAL</b></td>
        <td class="footer2 text" align="right"><?= number_format($debet,0,',','.') ?></td>
        <td class="footer2 text" align="right"><?= number_format($kredit,0,',','.') ?></td>
        <td class="footer2 text" align="right"><?= number_format(0,0,',','.') ?></td>
        <td class="footer3 text" align="right"><?= number_format(0,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan=9>&nbsp;<br>&nbsp;<br>&nbsp;</td>
    </tr>
  
    <?php 
            $akun = $data2['no_akun'];
        }
    }
    ?>

</tbody></table><table>
<script>
     window.print();
</script>
</table></body></html>