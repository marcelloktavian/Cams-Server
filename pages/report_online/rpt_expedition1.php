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
	$id_exp=$_GET['id_exp'];
	
	$where_title="";
	if($id_exp != null){
	$where_title .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND id_expedition=$id_exp";
	}
	else
	{
	$where_title .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
	$sql_title ="SELECT (SELECT id_trans FROM olnso WHERE (state='1') $where_title ORDER BY id_trans ASC LIMIT 1) AS first_order,
    (SELECT id_trans FROM olnso WHERE (state='1') $where_title ORDER BY id_trans DESC LIMIT 1) AS last_order ,
    (SELECT COUNT(id_trans) FROM olnso WHERE (state='1') $where_title) AS jumlah_order,
    (SELECT nama FROM mst_expedition WHERE id=$id_exp) AS ekspedisi";
	
	/*
	$sql_title ="SELECT (SELECT id_trans FROM olnso WHERE (state='1') AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ORDER BY id_trans ASC LIMIT 1) AS first_order,
    (SELECT id_trans FROM olnso WHERE (state='1') AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ORDER BY id_trans DESC LIMIT 1) AS last_order ,
    (SELECT COUNT(id_trans) FROM olnso WHERE (state='1') AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y')) AS jumlah_order";
	*/
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
			EXPEDITION REPORT (<?php echo"".$rs_title['ekspedisi'];?>)</strong></td>
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Order:&nbsp;<?php echo"".$rs_title['jumlah_order'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_awal:&nbsp;<?php echo"".$rs_title['first_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_akhir:&nbsp;<?php echo"".$rs_title['last_order'];?></td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="8" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="5%" class="style_title"><div align="center">No.web</div></td>
      <th width="30%" class="style_title"><div align="center">Dropshipper</div></td>
      <th width="20%" class="style_title"><div align="center">Item</div></td>
      <th width="5%" class="style_title"><div align="center">UK</div></td>
      <th width="5%" class="style_title"><div align="center">Qty</div></td>
      <th width="20%" class="style_title"><div align="center">Pembeli</div></td>
 	  <th width="10%" class="style_title"><div align="center">Pengiriman</div></td>
      
    </tr>
    <?
		
	$where = "WHERE m.state='1' ";
	if($id_exp != null){
	$where .= " AND DATE(m.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND m.id_expedition=$id_exp";
	}
	else
	{
	$where .= " AND DATE(m.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
    
	$sql_detail = "SELECT dt.id_trans,m.tgl_trans, m.ref_kode AS id_web,d.nama AS dropshipper,dt.namabrg,dt.jumlah_beli,dt.size,m.nama AS pembeli,e.nama AS expedition,m.state FROM olnsodetail dt
    INNER JOIN olnso m ON dt.id_trans = m.id_trans
    LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
    LEFT JOIN mst_expedition e ON m.id_expedition = e.id ".$where." order by m.id_trans asc";
    //var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$totalfaktur=0;
	$grand_totalfaktur=0;
	$grand_bayar=0;
	$grand_biaya=0;
	$biaya=0;
	$bayar=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 
	  $nomer++;
  ?>
    <tr>
      <td class="style_detail_left"><div align="center"><?=$rs2['id_trans'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['id_web'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['dropshipper'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['namabrg'];?>
	  </div></td>
      <td class="style_detail"><div align="center"><?=$rs2['size'];?>
      <td class="style_detail"><div align="center"><?=$rs2['jumlah_beli'];?>
	  </div></td>
      <td class="style_detail"><div align="center"><?=$rs2['pembeli'];?></div></td>
      <td class="style_detail"><div align="center"><?=$rs2['expedition'];?></div></td>
      
    </tr>  <?
	$totqty+=$rs2['jumlah_beli'];
	//$grand_totalfaktur+=$rs2['totalfaktur'];
	//$grand_totalfaktur=$rs['totalfaktur'];
	$grand_faktur+=$rs2['faktur'];
	$grand_faktur+=$rs2['harga_plus_ppn'];
	//$grand_biaya+=$rs2['biaya'];
	$grand_biaya+=$biaya;
	$totaldpp+=$rs2['DPP'];
	$totalppn+=$rs2['PPN'];
	$grand_piutang+=$rs2['piutang'];
	//$grand_bayar+=$rs2['bayar'];	
  }
  ?>
       <tr>
            <td colspan="5" class="style_title">Jumlah barang:</td>
            <td colspan="3" class="style_title"><?=$totqty;?></td>
       </tr>
   
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
