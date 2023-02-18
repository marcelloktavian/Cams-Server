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

.style_title_a {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  
  
  padding: 3px;
}

.style_title_a_left {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}

.style_title_left { color: #000000;
  font-size: 10pt; 
  font-weight: bold;
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}
.style_detail { color: #000000;
  font-size: 8pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
  border-bottom: 1px solid black;
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
.style_font {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
}
.style_detail_left {  color: #000000;
  font-size: 8pt; 
  font-family: Tahoma;
  border-left: 1px solid black;
  border-right: 1px solid black;
  border-bottom: 1px solid black;
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

<?php
    require "../../include/koneksi.php";
    $id = $_GET['id'];

    $sql = "SELECT DATE_FORMAT(check_date,'%d %b %Y') as tgl, id_check FROM trpaymentcheck WHERE id=$id  ";
    $result= mysql_query($sql);
    $tgl='';
    $id_check='';
    while ($data = mysql_fetch_array($result)){
        $tgl = $data['tgl'];
        $id_check = $data['id_check'];
    }
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
        <td colspan=5 class="style19" align="center">LAPORAN PAYMENT CHECK (<?=$id_check?>)</td>
    </tr>
    <tr>
        <td colspan=5 class="style10" align="center"><div style="text-transform:uppercase">TANGGAL : <?=$tgl?></div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td width='50%' style='vertical-align:top'>
            <table width='100%' cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan=4 ><div class="style_title_left" align='center'>CSV Payment List</div></td>
                </tr>
                <tr>
                    <td width='5%'><div class="style_title_a_left" align='center'>No</div></td>
                    <td width='60%'><div class="style_title_a" align='center'>Keterangan</div></td>
                    <td width='20%'><div class="style_title_a" align='center'>Tanggal</div></td>
                    <td width='15%'><div class="style_title_a" align='center'>Value</div></td>
                </tr>
                <?php
                    $sqlcsv = "SELECT det.id_import AS id, bank.id_import, bank.keterangan, bank.periode,det.payment_value FROM trpaymentcheck_detail det LEFT JOIN acc_prebank bank ON bank.id=det.id_import WHERE det.id_parent=$id AND det.id_import<>'0'; ";
                    $no = 1;
                    $resultcsv= mysql_query($sqlcsv);
                    while ($datacsv = mysql_fetch_array($resultcsv)){
                        echo "<tr>";
                        echo "<td <div class='style_detail_left'>$no</td>";   
                        echo "<td <div class='style_detail'>".$datacsv['keterangan']."</td>";   
                        echo "<td <div class='style_detail' align='center'>".$datacsv['periode']."</td>";  
                        echo "<td <div class='style_detail' align='right'>".number_format($datacsv['payment_value'],0)."</td>";  
                        echo "</tr>";
                        $no++;
                    }
                ?>
            </table>
        </td>
        <td width='50%' style='vertical-align:top'>
        <table  width='100%' cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan=5><div class="style_title_left" align='center'>OLN Sales /B2B ORDER DATA</div></td>
                </tr>
                <tr>
                    <td width='5%'><div class="style_title_a_left" align='center'>No</div></td>
                    <td width='20%'><div class="style_title_a" align='center'>OLN/B2B</div></td>
                    <td width='45%'><div class="style_title_a" align='center'>Dropshipper/Customer</div></td>
                    <td width='15%'><div class="style_title_a" align='center'>Tanggal</div></td>
                    <td width='15%'><div class="style_title_a" align='center'>Value</div></td>
                </tr>
                <?php
                    $sqloln = "SELECT det.id_olnb2b, IF(det.`stat_dropcust`='Dropshipper',(SELECT nama FROM mst_dropshipper WHERE id=det.`id_dropcust`),(SELECT nama FROM mst_b2bcustomer WHERE id=det.`id_dropcust`)) AS dropcust,  IF(det.`stat_dropcust`='Dropshipper',(SELECT DATE_FORMAT(tgl_trans, '%d/%m/%Y') AS tgl FROM olnso WHERE id_trans=det.id_olnb2b),(SELECT DATE_FORMAT(tgl_trans, '%d/%m/%Y') AS tgl FROM b2bdo WHERE id_trans=det.id_olnb2b)) AS tgl,det.subtotal FROM trpaymentcheck_detail det WHERE det.id_parent=$id AND det.id_olnb2b<>'';";
                    $no = 1;
                    $resultoln= mysql_query($sqloln);
                    while ($dataoln = mysql_fetch_array($resultoln)){
                        echo "<tr>";
                        echo "<td <div class='style_detail_left'>$no</td>";   
                        echo "<td <div class='style_detail'>".$dataoln['id_olnb2b']."</td>";   
                        echo "<td <div class='style_detail' align='left'>".$dataoln['dropcust']."</td>";  
                        echo "<td <div class='style_detail' align='center'>".$dataoln['tgl']."</td>";  
                        echo "<td <div class='style_detail' align='right'>".number_format($dataoln['subtotal'],0)."</td>";  
                        echo "</tr>";
                        $no++;
                    }
                ?>
            </table>
        </td>
       <tr>
       <td width='50%' style='vertical-align:top' align='center' ><div style='font-size: 10pt; font-family: Tahoma;'><br><br>DIPERIKSA OLEH :<br><br><br><br><br><br>(_______________________)</div></td>
        <td width='50%' style='vertical-align:top' align='center' ><div style='font-size: 10pt; font-family: Tahoma;'><br><br>DIKETAHUI OLEH :<br><br><br><br><br><br>(_______________________)</div></td>
       </tr>
    </tr>

</table>
<script>
    window.print();
</script>