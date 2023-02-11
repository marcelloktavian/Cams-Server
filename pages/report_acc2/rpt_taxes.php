<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
.style9 {
font-size: 9pt; 
font-family:Tahoma;
}
.style9b {
  font-size: 14pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style99 {font-size: 10pt; font-family:Tahoma}
.style10 {font-size: 10pt; font-weight: bold; font-family:Tahoma;}
.style19 {font-size: 13pt; font-weight: bold; font-family:Tahoma;}
.style11 {
  color: #000000;
  font-size: 8pt;
  font-weight: normal;
  font-family: MS Reference Sans Serif;
  
}
.style20b {font-size: 8pt;font-weight: bold; font-family:Tahoma}
.style20 {font-size: 8pt; font-family:Tahoma}
.style16 {font-size: 9pt; font-family:Tahoma}
.style21 {color: #000000;
  font-size: 10pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style18 {color: #000000;
  font-size: 9pt;
  font-weight: normal;
  font-family: Tahoma;
}
.style6 {color: #000000;
  font-size: 9pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style19b { color: #000000;
  font-size: 11pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style_title {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  
  
  padding: 3px;
}
.style_title_left { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}
.style_detail { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
  border-bottom: 1px dashed black;
  padding: 3px;
}
.style_detail2 { color: #000000;
  font-size: 6pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
}
.style_detail3 { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-right: 1px solid black;
  border-bottom: 1px solid black;
}
.style_detail_left {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-left: 1px solid black;
  border-right: 1px solid black;
  border-bottom: 1px dashed black;
  padding: 3px;
}
.font{
    font-size: 10pt; 
    font-family: Tahoma;
}
#container{width:100%;}
#timestamp{font-size: 9pt;  
  font-family: Tahoma;}
#left{float:left;width:30%;}
#right{float:right;width:70%;}
#left2{float:left;width:70%;}
#right2{float:right;width:30%;}
#left3{float:left;width:93%;}
#right3{float:right;width:7%;}
@page {
        size: A4;
        margin: 15px;
    }
</style>

<!-- php disini -->
<?php
  error_reporting(0);

  function rupiah($angka){
    $hasil_rupiah = number_format($angka,0,'.',',');
    return $hasil_rupiah;
  }

// koneksi dengan database
  require "../../include/koneksi.php";
  $startdate = $_GET['start'];
  $enddate = $_GET['end'];

  $sql="(SELECT op.kode, op.tanggal, det.nama_biaya, det.ppn AS debet, '' AS kredit FROM biayaoperasional_det det LEFT JOIN biayaoperasional op ON op.id=det.id_parent AND op.deleted=0
  WHERE det.ppn<>0 AND op.tanggal BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')) UNION ALL(SELECT '&nbsp;' AS kode, '' AS tanggal, 'PPN PENJUALAN ONLINE' AS nama_biaya, '' AS debet, (SUM(total-exp_fee)/1.11)*((SELECT `value` FROM `mst_taxes` WHERE deleted=0 AND nama='PPN')/100) AS kredit FROM olnso so WHERE deleted=0 AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')) UNION ALL (SELECT '&nbsp;' AS kode, '' AS tanggal, cus.nama AS nama_biaya, '' AS debet, (SUM(totalfaktur) / 1.11)*((SELECT `value` FROM `mst_taxes` WHERE deleted=0 AND nama='PPN')/100) AS kredit FROM b2bdo `do` LEFT JOIN mst_b2bcustomer cus ON cus.id=`do`.id_customer WHERE do.deleted=0 AND tgl_trans BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y') GROUP BY do.id_customer ORDER BY cus.nama ASC) ";

  $result= mysql_query($sql);
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan=5 class="style9b" >PT. AGUNG KEMUNINGWIJAYA</td>
    </tr>
    <tr>
        <td colspan=5 class="style99" >Taman Kopo Indah 1 No.6 Bandung - Indonesia</td>
    </tr>
    <tr>
        <td colspan=5 class="style99" >TEL : 022 - 5401972 &nbsp;&nbsp; FAX : 022 - 55407084</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=6 class="style19" align="center">PERIODE : <?=$startdate?> - <?=$enddate?></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td width='1%'><div class="style_title_left" align='left' >NO</div></td>
        <td width='20%'><div class="style_title" align='center' >KODE TRANSAKSI</div></td>
        <td width='15%'><div class="style_title" align='center' >TANGGAL</div></td>
        <td width='35%'><div class="style_title" align='center' >KETERANGAN</div></td>
        <td width='15%'><div class="style_title" align='center' >DEBET (Rp.)</div></td>
        <td width='15%'><div class="style_title" align='center' >KREDIT (Rp.)</div></td>
    </tr>
    <?php
        $no=1;
        $totdebet=0;
        $totkredit=0;
        while ($data = mysql_fetch_array($result)){
            ?>
            <tr>
                <td><div class='style_detail_left' align="right"><?=$no?></div></td>
                <td><div class='style_detail'><?=$data['kode']?></div></td>
                <td><div class='style_detail' align='center'><?php $date=date_create($data['tanggal']); if($data['tanggal']==''){echo '&nbsp;';}else{echo date_format($date,"d/m/Y");}?></div></td>
                <td><div class='style_detail' align="left" style='margin-left:5px;'><?=$data['nama_biaya']?></div></td>
                <td><div class='style_detail' align='right' style='padding-right:5px;'><?php if($data['debet']==''){echo '&nbsp;';}else{echo number_format($data['debet'],0);}?></div></td>
                <td><div class='style_detail' align='right' style='padding-right:5px;'><?php if($data['kredit']==''){echo '&nbsp;';}else{echo number_format($data['kredit'],0);}?></div></td>
            </tr>
            <?php
            $no++;
            $totdebet = $totdebet + $data['debet'];
            $totkredit = $totkredit + $data['kredit'];
        }
    ?>
     <!-- <tr>
        <td><div class='style_detail_left'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
    </tr>
    <tr>
        <td><div class='style_detail_left'></div></td>
        <td><div class='style_detail'></div></td>
        <td><div class='style_detail'></div></td>
        <td><div class='style_detail'></div></td>
        <td><div class='style_detail3'></div></td>
        <td><div class='style_detail3'></div></td>
    </tr> -->
    <tr>
        <td><div class='style_title_left'style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' align='center' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'><?=number_format($totdebet,0)?></div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'><?=number_format($totkredit ,0)?></div></td>
    </tr>
    <tr>
        <td><div class='style_title_left'style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' align='center' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'><?=number_format($totkredit-$totdebet,0)?></div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'>0</div></td>
    </tr>
    <tr>
        <td><div class='style_title_left'style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_title' align='center' style='border-bottom: 1px solid black;'>J U M L A H :</div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'><?=number_format($totdebet+($totkredit-$totdebet),0)?></div></td>
        <td><div class='style_title' style='border-bottom: 1px solid black;' align='right'><?=number_format($totkredit ,0)?></div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <!-- <tr>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center'>DIKETAHUI OLEH :</div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center'>DIBUAT OLEH :</div></td>
    </tr> -->
    <!-- <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center'><u>ENRICO TJANDRA</u></div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center'><u>NENDEN N</u></div></td>
    </tr> -->
    <!-- <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td><div class='font' align='left'><u>CATATAN SALDO :</u></div></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> -->
</table>