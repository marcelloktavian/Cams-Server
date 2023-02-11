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
.style6 {color: #000000;
	font-size: 9pt;
	font-weight: bold;
	font-family: Tahoma;
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
	
	$where_title=" deleted=0 ";	
	$where_title .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	
	
	$sql_title ="SELECT (SELECT id_trans FROM olnso WHERE $where_title ORDER BY id_trans ASC LIMIT 1) AS first_order,
    (SELECT id_trans FROM olnso WHERE  $where_title ORDER BY id_trans DESC LIMIT 1) AS last_order ,
    (SELECT COUNT(id_trans) FROM olnso WHERE  $where_title) AS jumlah_order";
	
	//var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	
	
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			ONLINE SALES REPORT </strong></td>
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Order:&nbsp;<?php echo"".$rs_title['jumlah_order'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_awal:&nbsp;<?php echo"".$rs_title['first_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_akhir:&nbsp;<?php echo"".$rs_title['last_order'];?></td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="9" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="5%" class="style_title"><div align="center">No.web</div></td>
      <th width="30%" class="style_title"><div align="center">Dropshipper</div></td>
      <th width="20%" class="style_title"><div align="center">Item</div></td>
      <th width="5%" class="style_title"><div align="center">UK</div></td>
      <th width="5%" class="style_title"><div align="center">Qty</div></td>
      <th width="10%" class="style_title"><div align="center">Price</div></td>
 	  <th width="10%" class="style_title"><div align="center">Subtotal</div></td>
 	  <th width="10%" class="style_title"><div align="center">Ongkir</div></td>
      
    </tr>
    <?
		
	$where = "";
	$where .= " WHERE (m.deleted=0) AND DATE(m.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	
	
    
	$sql_detail = "SELECT dt.id_trans,m.tgl_trans, m.ref_kode AS id_web,m.exp_fee as ongkir,d.nama AS dropshipper,dt.namabrg,dt.jumlah_beli,dt.size,dt.harga_satuan,dt.subtotal,m.nama AS pembeli,e.nama AS expedition,m.state FROM olnsodetail dt
    INNER JOIN olnso m ON dt.id_trans = m.id_trans
    LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
    LEFT JOIN mst_expedition e ON m.id_expedition = e.id ".$where." order by m.id_trans asc";
    //var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$grand_subtotal=0;
	$grand_qty=0;
	$totaldpp=0;
	$totalppn=0;
	$grand_ongkir=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 	    
  ?>
    <tr>
    <?
	  $nomer++;
	  //bikin master_ongkir
	  if ($kode!=$rs2['id_trans'])
	  {
	    $grand_ongkir+=$rs2['ongkir'];
		echo"<td class='style_detail_left'><div align='center'>".$rs2['id_trans']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['id_web']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['subtotal'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['ongkir'])."</div></td>";
		
		$kode=$rs2['id_trans'];
	  }
      else if($kode=$rs2['id_trans'])
	  {
		echo"<td class='style_detail_left'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['subtotal'])."</div></td>";
		echo"<td class='style_detail'><div align='right'></div></td>";
	  
	  }
	?>
    </tr>  
	<?
	$grand_qty+=$rs2['jumlah_beli'];
	$grand_subtotal+=$rs2['subtotal'];
	$totaldpp =($grand_subtotal/1.1);
	$totalppn= ($totaldpp*0.1);	
  }
  ?>
       <tr>
            <td colspan="5" class="style_title_left"><div align="right">Total:</div></td>
            <td class="style_title"><div align="center"><?=$grand_qty;?></div></td>
            <td colspan="2" class="style_title"><div align="right"><?=number_format($grand_subtotal);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_ongkir);?></div></td>
       </tr>
       <tr>
            <td colspan="5" class="style_title_left"><div align="right">DPP:</div></td>
            <td class="style_title"><div align="center">&nbsp;</div></td>
            <td colspan="2" class="style_title"><div align="right"><?=number_format($totaldpp);?></div></td>
            <td class="style_title"><div align="right">&nbsp;</div></td>
       </tr>
       <tr>
            <td colspan="5" class="style_title_left"><div align="right">PPN:</div></td>
            <td class="style_title"><div align="center">&nbsp;</div></td>
            <td colspan="2" class="style_title"><div align="right"><?=number_format($totalppn);?></div></td>
            <td class="style_title"><div align="right">&nbsp;</div></td>
       </tr>
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
