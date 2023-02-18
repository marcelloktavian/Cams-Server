<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<style type="text/css">
.style9 {
font-size: 9pt; 
font-family:Tahoma;
}
.style9b {color: #000000;
	font-size: 9pt;
	font-weight: bold;
	font-family: Tahoma;
}.style99 {font-size: 13pt; font-family:Tahoma}
.style10 {font-size: 10pt; font-family:Tahoma; text-align:right}
.style19 {font-size: 10pt; font-weight: bold; font-family:Tahoma; font-style:italic}
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
.style_footer {color: #000000;
	font-size: 11pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	
}
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Tahoma;
}
.style_title {	color: #000000;
	font-size: 11pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	
	
	padding: 3px;
}
.style_title_left {	color: #000000;
	font-size: 11pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	
	padding: 3px;
}
.style_detail {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-bottom: 1px dashed black;
	border-right: 1px solid black;
	padding: 3px;
}
.style_detail_left {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-bottom: 1px dashed black;
	border-left: 1px solid black;
	border-right: 1px solid black;
	padding: 3px;
}
@page {
        size: A4;
        margin: 15px;
    }
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	$startdate=$_GET['start'];
    $enddate=$_GET['end'];
	$filter=$_GET['filter'];
	$where = "WHERE TRUE AND do.state = '0'";		
		if($filter != null) {
			$where .= " AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((c.nama like '%$filter%') or (e.nama like '%$filter%') or (s.nama like '%$filter%') or (do.exp_code like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		
		$sql_title = "SELECT count(do.id_trans) as jumlah_order,sum(do.totalkirim) as totalqty,sum(do.totalfaktur) as totalfaktur from `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id) ".$where." group by do.id_trans";
	
	//var_dump($sql_title);die;
	
	$sql = mysql_query($sql_title);
	$rs_title = mysql_fetch_array($sql);
	
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			B2B Sales REPORTS </strong></td>
          </tr>
          <tr>
            <td width="50%" class="style9b" colspan="7">Dari:
            <?php echo"".$startdate;?>
            &nbsp;-&nbsp;<?php echo"".$enddate;?>
			</td>
			<td width="50%" class="style9b" colspan="7">Filter:
            <?php echo"".$filter;?>
			</td>
			
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="7" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No</div></td>
      <th width="10%" class="style_title"><div align="center">No.Faktur</div></td>
      <th width="30%" class="style_title"><div align="center">Nama Pembeli</div></td>
      <th width="15%" class="style_title"><div align="center">Tgl.Kirim</div></td>
      <th width="15%" class="style_title"><div align="center">Tgl.Jatuh Tempo</div></td>
 	  <th width="10%" class="style_title"><div align="right">Qty</div></td>
 	  <th width="15%" class="style_title"><div align="right">Nilai Faktur</div></td>
      
    </tr>
    <?
		
	$sql_detail = "SELECT do.*,date_format(so.tgl_trans,'%d-%m-%Y')as tgl_trans,so.tgl_trans as tgl_order,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman FROM `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id)".$where; 
    //var_dump($sql_detail);die;
	
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$grand_qty=0;
	$grand_faktur=0;
	$grand_order=0;
	$grand_ongkir=0;
	$grand_total=0;
	$biaya=0;
	while($rs2=mysql_fetch_array($sq2))
	{ 
	  $nomer++;
	  
  ?>
    <tr>
      <td class="style_detail_left"><div align="left"><?=$nomer;?>
	  </div></td>
      <td class="style_detail"><div align="center"><?=$rs2['id_trans'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['customer'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['tgl_trans'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['tgl_trans'];?></div></td>
	  <td class="style_detail"><div align="right"><?=number_format($rs2['totalkirim']);?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['totalfaktur']);?>
	  </div></td>
    </tr>  <?
	$grand_qty+=$rs2['totalkirim'];
	$grand_faktur+=$rs2['totalfaktur'];
	$grand_order+=$rs2['jum_order'];
	$grand_ongkir+=$rs2['ongkir'];
	$grand_disc+=$rs2['discount_faktur'];
	$grand_total+=$rs2['total'];
    $nett_faktur=$grand_faktur-$grand_disc;	
    $dpp=($nett_faktur/1.11);	
  }
 	
  ?>
       <tr>
            <td class="style_footer" colspan=4><div align="right">GrandTotal :</div></td>
            <td class="style_footer"><div align="right"><?=number_format($nomer);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_qty);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_faktur);?></div></td>
            
       </tr>
	   
	   
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
