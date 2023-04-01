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

$ex1 = explode('/',$_GET['start']);
$date = "$ex1[1]-$ex1[0]-01";
$startdatelast = date('Y-m-d', strtotime($date. ' - 1 months'));
$date = "$ex1[1]-$ex1[0]-31";
$enddatelast = date('Y-m-d', strtotime($date. ' - 1 months'));

$arrNamaBulan = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"Desember");

$akun = $_GET['akun'];

if($akun == ''){
    $sql_products ="SELECT a.* FROM `mst_coa` a ";

    $query = '';
    $countnya = 0;
    $sql1 = mysql_query($sql_products." where a.deleted=0 ORDER BY noakun ASC ");
    while($r1 = mysql_fetch_array($sql1)) {
        if ($countnya == 0) {
            $query .= "(select id, noakun, nama, jenis from mst_coa where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' ORDER BY noakun ASC) ";
        } else {
            $query .= " UNION ALL (select id, noakun, nama, jenis from mst_coa  where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' ORDER BY noakun ASC) ";
        }
        $countnya++;
        $sql2 = mysql_query("SELECT * FROM det_coa WHERE id_parent='".$r1['id']."' ORDER by noakun ASC");
        while($r2 = mysql_fetch_array($sql2)) {
            $query .= " UNION ALL (select id, noakun, nama, '' as jenis from det_coa where id='".$r2['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' ORDER BY noakun ASC) ";
        }
    }

    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%m') AS bulan, DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%Y') AS tahun FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') GROUP BY det.no_akun ORDER BY det.no_akun ASC LIMIT 5"; 
}else{
    $sql_products ="SELECT a.* FROM `mst_coa` a ";

    $query = '';
    $countnya = 0;
    $sql1 = mysql_query($sql_products." where a.deleted=0 ");

    while($r1 = mysql_fetch_array($sql1)) {
        if ($countnya == 0) {
            $query .= "select id, noakun, nama, jenis from mst_coa where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' AND (noakun = '$akun') ";
        } else {
            $query .= " UNION ALL (select id, noakun, nama, jenis from mst_coa  where id='".$r1['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000' AND (noakun = '$akun')) ";
        }
        $countnya++;
        $sql2 = mysql_query("SELECT * FROM det_coa WHERE id_parent='".$r1['id']."' ORDER by noakun ASC");
        while($r2 = mysql_fetch_array($sql2)) {
            $query .= " UNION ALL (select id, noakun, nama, '' as jenis from det_coa where id='".$r2['id']."' AND SUBSTR(noakun,4,2)<>'00' AND SUBSTR(noakun,7,5)<>'00000'  AND (noakun = '$akun')) ";
        }
    }

    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%m') AS bulan, DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%Y') AS tahun FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND det.`no_akun`='$akun' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') GROUP BY det.no_akun ORDER BY det.no_akun ASC";
}

$result= mysql_query($sql);
$data = mysql_fetch_array($result); 

if($_GET['action']=='excel'){
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=BUKU BESAR ".STRTOUPPER($arrNamaBulan[$data['bulan']])." ".$data['tahun'].".xls");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
}

?>
</head><body><table width="100%" border="<?=$_GET['action']=='excel'?'1':'0'?>" align="center" cellpadding="0" cellspacing="0">
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
    $totalsaldodb = 0;
    $totalsaldocr = 0;
    $result2= mysql_query($query);
    while($data2 = mysql_fetch_array($result2)){
        if($akun != $data2['noakun']){
            $totalsaldodb = 0;
            $totalsaldocr = 0;  
    ?>
    <tr>
        <td class="header text center" width="10%" colspan=5></td>
        <td class="header2 text left" width="10%" colspan=4>
            <b>NOMOR AKUN <?=$data2['noakun']?></b>
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
    <?php
        if($akun == ''){
            $sqlsaldo="SELECT det.`no_akun`, det.`nama_akun`, SUM(det.`debet`) as totdebet, SUM(det.`kredit`) as totkredit, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
            LEFT JOIN jurnal mst ON mst.id=det.id_parent
            WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['noakun']."' AND mst.tgl < STR_TO_DATE('$startdate','%d/%m/%Y') ORDER BY mst.tgl ASC, no_jurnal ASC, det.id ASC "; 
        }else{
            $sqlsaldo="SELECT det.`no_akun`, det.`nama_akun`, SUM(det.`debet`) as totdebet, SUM(det.`kredit`) as totkredit, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
            LEFT JOIN jurnal mst ON mst.id=det.id_parent
            WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['noakun']."' AND mst.tgl < STR_TO_DATE('$startdate','%d/%m/%Y') ORDER BY mst.tgl ASC, det.no_akun ASC, no_jurnal ASC, det.id ASC ";
        }
        $saldodebet = 0;
        $saldokredit = 0;
        $resultsaldo = mysql_query($sqlsaldo);
        while($datasaldo = mysql_fetch_array($resultsaldo)){
            $saldodebet = $datasaldo['totdebet'];
            $saldokredit = $datasaldo['totkredit'];
        }

        $totalsaldodb += $saldodebet;
        $totalsaldocr += $saldokredit;  
    ?>
    <tr>
        <td class='detail text'></td>
        <td class='detail text'></td>
        <td class='detail text'><?=$data2['noakun']?></td>
        <td class='detail text'><?=$data2['nama']?></td>
        <td class='detail text'>SALDO AWAL</td>
        <td class='detail text right' align='right'><?=number_format($saldodebet,0,',','.')?></td>
        <td class='detail text right' align='right'><?=number_format($saldokredit,0,',','.')?></td>
        <?php
        if(($saldodebet-$saldokredit) < 0){
            ?>
                <td class='detail text right' align='right'><?=number_format(0,0,',','.')?></td>
                <td class='detail4 text right' align='right'><?=number_format(abs($saldodebet-$saldokredit),0,',','.')?></td>
            <?php
            $db = 0;
            $cr = abs($saldodebet-$saldokredit);
        }else{
            ?>
                <td class='detail text right' align='right'><?=number_format(abs($saldodebet-$saldokredit),0,',','.')?></td>
                <td class='detail4 text right' align='right'><?=number_format(0,0,',','.')?></td>
            <?php
            $db = abs($saldodebet-$saldokredit);
            $cr = 0;
        }
        ?>
        

    </tr>
    <?php } 

    if($akun == ''){
        $sql3="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
        LEFT JOIN jurnal mst ON mst.id=det.id_parent
        WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['noakun']."' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, no_jurnal ASC, det.id ASC "; 
    }else{
        $sql3="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal`, mst.keterangan FROM jurnal_detail det
        LEFT JOIN jurnal mst ON mst.id=det.id_parent
        WHERE det.deleted=0 AND mst.deleted=0 AND det.no_akun = '".$data2['noakun']."' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, det.no_akun ASC, no_jurnal ASC, det.id ASC ";
    }
    $debet = 0;
    $kredit = 0;
    $totaldebet = 0;
    $totalkredit = 0;
    
    $debet += $saldodebet;
    $kredit += $saldokredit;

    $totaldebet += $db;
    $totalkredit += $cr;

    $result3= mysql_query($sql3);
    while($data3 = mysql_fetch_array($result3)){
        $saldo = $data3['debet']-$data3['kredit'];
        ?>
        <tr>
            <td class='detail text center'><?=$data3['tanggal']?></td>
            <td class='detail text center'><?=$data3['no_jurnal']?></td>
            <td class='detail text'><?=$data3['no_akun']?></td>
            <td class='detail text'><?=$data3['nama_akun']?></td>
            <td class='detail text'><?=$data3['keterangan']?></td>
            <td class='detail text right' align='right'><?=number_format($data3['debet'],0,',','.')?></td>
            <td class='detail text right' align='right'><?=number_format($data3['kredit'],0,',','.')?></td>
            <?php
            if((($totaldebet - $totalkredit)+($saldo))>0){
                $totaldebet = (($totaldebet - $totalkredit)+($saldo));
                $totalkredit = 0;
            }else{
                $totalkredit = abs(($totaldebet - $totalkredit)+($saldo));
                $totaldebet = 0;
            }
            ?>
            <td class='detail text right' align='right'><?=number_format($totaldebet,0,',','.')?></td>
            <td class='detail4 text right' align='right'><?=number_format($totalkredit,0,',','.')?></td>
        </tr>
        <?php
        $debet += $data3['debet'];
        $kredit += $data3['kredit'];
        
    }

        if($akun != $data2['noakun']){
    ?>
    <tr>
        <td class="footer text" align="right" colspan="5"><b>TOTAL</b></td>
        <td class="footer2 text" align="right"><?= number_format($debet,0,',','.') ?></td>
        <td class="footer2 text" align="right"><?= number_format($kredit,0,',','.') ?></td>
        <?php
        if(($debet-$kredit) < 0){
            ?>
                <td class="footer2 text" align="right"><?= number_format(0,0,',','.') ?></td>
                <td class="footer3 text" align="right"><?= number_format(abs($debet-$kredit),0,',','.') ?></td>
            <?php
        }else{
            ?>
                <td class="footer2 text" align="right"><?= number_format(abs($debet-$kredit),0,',','.') ?></td>
                <td class="footer3 text" align="right"><?= number_format(0,0,',','.') ?></td>
            <?php
        }
        ?>
        
    </tr>
    <tr>
        <td colspan=9>&nbsp;<br>&nbsp;<br>&nbsp;</td>
    </tr>
  
    <?php 
            $akun = $data2['noakun'];
        }
    }
    ?>

</tbody></table><table>
<script>
     window.print();
</script>
</table></body></html>