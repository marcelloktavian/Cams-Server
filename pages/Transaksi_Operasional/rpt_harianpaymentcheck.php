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
    $startdate = $_GET['start'];
    $ex1 = explode("/",$startdate);
    $start = $ex1[2].'-'.$ex1[1].'-'.$ex1[0];
    $enddate = $_GET['end'];
    $ex2 = explode("/",$enddate);
    $end = $ex2[2].'-'.$ex2[1].'-'.$ex2[0];
    $filter = $_GET['filter'];

    $where = "WHERE TRUE AND deleted=0 AND posting=1 ";
	if(($startdate != null) && ($filter != null)) {
		$where .= " AND DATE(a.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND (a.id_check like '%$filter%' OR a.note like '%$filter%' OR (IF(b.stat_dropcust = 'Dropshipper' ,(SELECT nama from mst_dropshipper where id=b.id_dropcust) , IF(b.stat_dropcust='Customer',(SELECT nama from mst_b2bcustomer where id=b.id_dropcust),'') ) ) like '%$filter%'  )";	
	}else{
		$where .=" AND DATE(a.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
	}
	$sql = " SELECT a.*,DATE_FORMAT(check_date, '%d/%m/%Y') as tglpayment,DATE_FORMAT(a.lastmodified, '%d/%m/%Y') as tglcheck,(SELECT bn.periode FROM trpaymentcheck_detail det LEFT JOIN acc_prebank bn ON bn.id=det.id_import WHERE det.id_parent=a.id AND det.`id_import` <> '0' LIMIT 1) as periode FROM trpaymentcheck a LEFT JOIN trpaymentcheck_detail b ON a.id=b.id_parent ".$where." GROUP BY a.id ";
    $result= mysql_query($sql);
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan=7 class="style9b" >PT. AGUNG KEMUNINGWIJAYA</td>
    </tr>
    <tr>
        <td colspan=7 class="style99" >Taman Kopo Indah 1 No.6 Bandung - Indonesia</td>
    </tr>
    <tr>
        <td colspan=7 class="style99" >TEL : 022 - 5401972 &nbsp;&nbsp; FAX : 022 - 55407084</td>
    </tr>
    <tr>
        <td colspan=7>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=7 class="style19" align="center">LAPORAN PAYMENT CHECK</td>
    </tr>
    <tr>
        <td colspan=7 class="style10" align="center"><div style="text-transform:uppercase">TANGGAL : <?php $date1=date_create($start);
echo date_format($date1,"d M Y");$date2=date_create($end);echo ' - '.date_format($date2,"d M Y");?> </div></td>
    </tr>
    <tr>
        <td colspan=7>&nbsp;</td>
    </tr>
    <tr>
        <td width='5%' class="style_title_left"><div align='center'>No</div></td>
        <td width='10%' class="style_title"><div align='center'>Code</div></td>
        <td width='10%' class="style_title"><div align='center'>Periode CSV</div></td>
        <td width='10%' class="style_title"><div align='center'>Check Date</div></td>
        <td width='10%' class="style_title"><div align='center'>Total Payment</div></td>
        <td width='10%' class="style_title"><div align='center'>Total Sales</div></td>
        <td width='10%' class="style_title"><div align='center'>Total Adjustment</div></td>
        <td width='45%' class="style_title"><div align='center'>Order Data</div></td>
    </tr>
    <?php
         $no = 1;
         $totalcsv = 0;
         $totaloln=0;
         while ($data = mysql_fetch_array($result)){
             $isi = '';
             echo "<tr>";
             echo "<td class='style_detail_left'>$no</td>";
             echo "<td class='style_detail'>".$data['id_check']."</td>";
             echo "<td class='style_detail' align='center'>".$data['periode']."</td>";
             echo "<td class='style_detail' align='center'>".$data['tglcheck']."</td>";
             echo "<td class='style_detail' align='right'>".number_format($data['total_csv'],0)."</td>";
             echo "<td class='style_detail' align='right'>".number_format($data['total_oln'],0)."</td>";
             echo "<td class='style_detail' align='right'>".number_format(0,0)."</td>";
            
             $sql2 = "select * from trpaymentcheck_detail WHERE id_parent='".$data['id']."' ";
             $result2= mysql_query($sql2);
             $j = 1;
             while ($data2 = mysql_fetch_array($result2)){
                 if($data2['id_olnb2b'] != ''){
                    if($j == 1){
                        $isi .= $data2['id_olnb2b'];
                     }else{
                        $isi .= ", ".$data2['id_olnb2b'];
                     }
                     $j++;
                 }
                 
             }

             echo "<td class='style_detail' align='left'>".$isi."</td>";
             echo "</tr>";
             $no++;
             $totalcsv += $data['total_csv'];
             $totaloln += $data['total_oln'];
        }
    ?>
    <tr>
        <td class='style_detail_left' colspan=4 align='right'>Total:</td>
        <td class='style_detail' align='right'><?=number_format($totalcsv,0)?></td>
        <td class='style_detail' align='right'><?=number_format($totaloln,0)?></td>
        <td class='style_detail' align='right'><?=number_format(0,0)?></td>
        <td class='style_detail'></td>
    </tr>
</table>
<script>
    window.print();
</script>