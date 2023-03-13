<html><head><script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<?php
if($_GET['action']=='excel'){
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=JURNAL TRANSAKSI ".$_GET['start']." - ".$_GET['end'].".xls");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
}

?>
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
$startdate = $_GET['start'];
$enddate = $_GET['end'];

$akun = $_GET['akun'];
if($akun == ''){
    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%d/%m/%Y') AS tglawal, DATE_FORMAT(STR_TO_DATE('$enddate','%d/%m/%Y'),'%d/%m/%Y') AS tglakhir FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, no_jurnal ASC, det.id ASC"; 
}else{
    $sql="SELECT det.`no_akun`, det.`nama_akun`, det.`debet`, det.`kredit`, DATE_FORMAT(mst.`tgl`,'%d/%m/%Y') AS tanggal, mst.`no_jurnal` ,DATE_FORMAT(STR_TO_DATE('$startdate','%d/%m/%Y'),'%d/%m/%Y') AS tglawal, DATE_FORMAT(STR_TO_DATE('$enddate','%d/%m/%Y'),'%d/%m/%Y') AS tglakhir FROM jurnal_detail det
    LEFT JOIN jurnal mst ON mst.id=det.id_parent
    WHERE det.deleted=0 AND mst.deleted=0 AND det.`no_akun`='$akun' AND mst.tgl BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') ORDER BY mst.tgl ASC, no_jurnal ASC, det.id ASC";
}

$result= mysql_query($sql);
$data = mysql_fetch_array($result);
?>
</head><body><table width="100%" border="<?=$_GET['action']=='excel'?'1':'0'?>" align="center" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <td colspan="5" class="judul" align="center">
            PT. AGUNG KEMUNINGWIJAYA<br>
            JURNAL TRANSAKSI<br>
            PERIODE <?php echo strtoupper($data['tglawal']) ?> - <?php echo strtoupper($data['tglakhir']) ?><br>
            <br>
        </td>
    </tr>
    <tr>
        <td class="header text center" width="15%">
            <center><b>TANGGAL</b></center>
        </td>
        <td class="header text center" width="15%">
            <center><b>NO JURNAL</b></center>
        </td>
        <td class="header text center" width="40%">
            <center><b>DESKRIPSI</b></center>
        </td>
        <td class="header text center" width="15%">
            <center><b>DEBET</b></center>
        </td>
        <td class="header2 text center" width="15%">
            <center><b>CREDIT</b></center>
        </td>
    </tr>
    <?php
    $no = 1;
    $totaldebet = 0;
    $totalkredit = 0;
    $nojurnal = '';

    $result2= mysql_query($sql);
    while($data2 = mysql_fetch_array($result2)){
        if($nojurnal == $data2['no_jurnal']){
            echo "<tr>";
            echo "<td class='detail3 text center'></td>";
            echo "<td class='detail3 text center'></td>";
            echo "<td class='detail3 text'>(".$data2['no_akun'].") ".$data2['nama_akun']."</td>";
            echo "<td class='detail3 text right'>".number_format($data2['debet'],0,',','.')."</td>";
            echo "<td class='detail2 text right'>".number_format($data2['kredit'],0,',','.')."</td>";
            echo "</tr>";
        }else{
            echo "<tr>";
            echo "<td class='detail text center'>".$data2['tanggal']."</td>";
            echo "<td class='detail text center'>".$data2['no_jurnal']."</td>";
            echo "<td class='detail text'>(".$data2['no_akun'].") ".$data2['nama_akun']."</td>";
            echo "<td class='detail text right'>".number_format($data2['debet'],0,',','.')."</td>";
            echo "<td class='detail4 text right'>".number_format($data2['kredit'],0,',','.')."</td>";
            echo "</tr>";
            
        }

        $no++;
        $totaldebet += $data2['debet'];
        $totalkredit += $data2['kredit'];
        $nojurnal = $data2['no_jurnal'];
    }
    ?>
    <tr>
        <td class="footer text" align="right" colspan="3"><b>TOTAL</b></td>
        <td class="footer2 text" align="right"><?= number_format($totaldebet,0,',','.') ?></td>
        <td class="footer3 text" align="right"><?= number_format($totalkredit,0,',','.') ?></td>
    </tr>

</tbody></table><table>
<script>
     window.print();
</script>
</table></body></html>