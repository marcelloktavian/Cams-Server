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
$startdate = $_GET['start'];
$enddate = $_GET['end'];
$sql="SELECT dr.id,dr.nama, SUM(jr.total_debet) as totaldebet, SUM(jr.total_kredit) as totalkredit, date_format(STR_TO_DATE('$startdate','%d/%m/%Y'),'%d %M %Y') as tglawal,date_format(STR_TO_DATE('$enddate','%d/%m/%Y'),'%d %M %Y') as tglakhir FROM journal_transaction jr LEFT JOIN `mst_dropshipper` dr ON dr.id=jr.`iddropcust` WHERE DATE(jr.lastmodified) BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y') GROUP BY dr.id ORDER BY dr.nama ASC";
$result= mysql_query($sql);
$data = mysql_fetch_array($result);
?>
</head><body><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <td colspan="12" class="judul" align="center">
            PT. AGUNG KEMUNINGWIJAYA<br>
            JOURNAL TRANSACTION<br>
            PERIODE <?php echo strtoupper($data['tglawal']) ?> - <?php echo strtoupper($data['tglakhir']) ?><br>
            <br>
        </td>
    </tr>
    <tr>
        <td class="header text center" width="3%">
            NO
        </td>
        <td class="header text center" width="25%">
            DROPSHIPPER / CUSTOMER
        </td>
        <td class="header text center" width="10%">
            DEBET
        </td>
        <td class="header2 text center" width="10%">
            CREDIT
        </td>
    </tr>
    <?php
    $no = 1;
    $totaldebet = 0;
    $totalkredit = 0;

    $result2= mysql_query($sql);
    while($data2 = mysql_fetch_array($result2)){
        echo "<tr>";
        echo "<td class='detail text' rowspan=2>".number_format($no)."</td>";
        echo "<td class='detail text'>(1.01.00.00) Kas</td>";
        echo "<td class='detail text right'>".number_format($data2['totaldebet'],0,',','.')."</td>";
        echo "<td class='detail4 text right'>0</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td class='detail3 text'>Penjualan ".$data2['nama']."</td>";
        echo "<td class='detail5 text right'>0</td>";
        echo "<td class='detail2 text right'>".number_format($data2['totalkredit'],0,',','.')."</td>";
        echo "</tr>";
        $no++;
        $totaldebet += $data2['totaldebet'];
        $totalkredit += $data2['totalkredit'];
    }
    ?>
    <tr>
        <td class="footer text" align="right" colspan="2">TOTAL</td>
        <td class="footer2 text" align="right"><?= number_format($totaldebet,0) ?></td>
        <td class="footer3 text" align="right"><?= number_format($totalkredit,0) ?></td>
    </tr>

</tbody></table><table>
<script>
     window.print();
</script>
</table></body></html>