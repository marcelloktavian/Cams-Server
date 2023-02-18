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
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			Dropshipper Sales Statistics REPORT </strong></td>
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?></td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="8" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="30%" class="style_title_left"><div align="center">Dropshipper</div></td>
      <th width="10%" class="style_title"><div align="center">TotalQty</div></td>
      <th width="5%" class="style_title"><div align="center">Jumlah Order</div></td>
      <th width="20%" class="style_title"><div align="right">Total Faktur</div></td>
      <th width="15%" class="style_title"><div align="right">Total Ongkir</div></td>
 	  <th width="20%" class="style_title"><div align="right">Faktur+Ongkir</div></td>
      
    </tr>
    <?
		
	$where = " WHERE m.deleted=0 ";
	$where .= " AND DATE(m.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	
	$sql_detail = "SELECT d.nama AS dropshipper,COUNT(m.id_trans) AS jum_order,SUM(m.`totalqty`) AS totalqty,SUM(m.tunai) AS tunai ,SUM(m.transfer) AS transfer,SUM(m.deposit) AS deposit,SUM(m.faktur) AS faktur,SUM(m.total) AS total,SUM(m.piutang) AS piutang,SUM(m.simpan_deposit) as simpan_deposit,SUM(m.exp_fee) as ongkir FROM olnso m
	LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id".$where." 
	GROUP BY m.id_dropshipper
	ORDER BY d.nama ASC";
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
      <td class="style_detail_left"><div align="left"><?=$rs2['dropshipper'];?>
	  </div></td>
      <td class="style_detail"><div align="center"><?=number_format($rs2['totalqty']);?></div></td>
      <td class="style_detail"><div align="center"><?=number_format($rs2['jum_order']);?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['faktur']);?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['ongkir']);?>
      <td class="style_detail"><div align="right"><?=number_format($rs2['total']);?>
	  </div></td>
    </tr>  <?
	$grand_qty+=$rs2['totalqty'];
	$grand_faktur+=$rs2['faktur'];
	$grand_order+=$rs2['jum_order'];
	$grand_ongkir+=$rs2['ongkir'];
	$grand_total+=$rs2['total'];	
  }
 	
  ?>
       <tr>
            <td class="style_footer"><div align="right">GrandTotal :</div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_qty);?></div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_order);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_faktur);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_ongkir);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_total);?></div></td>
       </tr>
	   
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
