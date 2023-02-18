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
	$id_product=$_GET['id_product'];
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$where_title  ="";
	$where_title  =" WHERE (m.state='1') AND (m.deleted=0) AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y')";
    $where_detail = "";
	$where_detail =" AND (m.state='1') AND (m.deleted=0)";
	
	// if($id_product != null) 
	// {
	// $where_detail .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND (dt.id_product = '$id_product') ";
	// }
	// else
	// {
	$where_detail .= "AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    // }
	
	$sql_title ="SELECT SUM(dt.jumlah_beli) AS grandtotalqty FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans=m.id_trans $where_title";
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
			PRODUCT REPORT</strong></td>
          </tr>
          <tr>
            <td width="100%" class="style_tgl" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp; Total Product:<?php echo"".$rs_title['grandtotalqty'];?></td>           
		  </tr>
          		  
  </table>  
     
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.</div></td>
      <th width="35%" class="style_title"><div align="left">Nama Barang</div></td>
      <th width="5%" class="style_title"><div align="center">36</div></td>
      <th width="5%" class="style_title"><div align="center">37</div></td>
      <th width="5%" class="style_title"><div align="center">38</div></td>
      <th width="5%" class="style_title"><div align="center">39</div></td>
      <th width="5%" class="style_title"><div align="center">40</div></td>
      <th width="5%" class="style_title"><div align="center">41</div></td>
      <th width="5%" class="style_title"><div align="center">42</div></td>
      <th width="5%" class="style_title"><div align="center">43</div></td>
      <th width="5%" class="style_title"><div align="center">44</div></td>
      <th width="10%" class="style_title"><div align="center">Totalqty</div></td>
      <th width="10%" class="style_title"><div align="center">%</div></td>
      
    </tr>
    <?
	
	
	// $sql_detail = "SELECT dt.namabrg,SUM(dt.jumlah_beli) AS totalqty
	// ,SUM(IF((dt.size) = '36', dt.jumlah_beli, 0) ) AS s36 
	// ,SUM(IF((dt.size) = '37', dt.jumlah_beli, 0) ) AS s37 
	// ,SUM(IF((dt.size) = '38', dt.jumlah_beli, 0) ) AS s38 
	// ,SUM(IF((dt.size) = '39', dt.jumlah_beli, 0) ) AS s39 
	// ,SUM(IF((dt.size) = '40', dt.jumlah_beli, 0) ) AS s40 
	// ,SUM(IF((dt.size) = '41', dt.jumlah_beli, 0) ) AS s41 
	// ,SUM(IF((dt.size) = '42', dt.jumlah_beli, 0) ) AS s42 
	// ,SUM(IF((dt.size) = '43', dt.jumlah_beli, 0) ) AS s43 
	// ,SUM(IF((dt.size) = '44', dt.jumlah_beli, 0) ) AS s44 
	// FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans=m.id_trans". $where_detail." GROUP BY dt.namabrg ASC";
    // var_dump($sql_detail);die;
    if ($_GET['id_product']=='' || $_GET['id_product']==null) {
    	$sql = "SELECT id,nama FROM mst_products WHERE (size='' OR size IS NULL) AND deleted=0 ORDER BY nama ASC";
    } else {
    	$sql = "SELECT id,nama FROM mst_products WHERE id='".$_GET['id_product']."' and (size='' OR size IS NULL) AND deleted=0 ORDER BY nama ASC";
    }
    
	$sq2 = mysql_query($sql);
	$i=1;
	$nomer=0;
	$grand_qty=0;
	while($rs2=mysql_fetch_array($sq2))
	{ 	
		
    // $grand_qty+=$rs2['totalqty'];
	
  ?>
    <tr>
    <?
	  
		

		$sql_detail = "SELECT IFNULL(SUM(IF((det.size) = '36', det.jumlah_beli, 0)),0) AS s36, 
		IFNULL(SUM(IF((det.size) = '37', det.jumlah_beli, 0)),0) AS s37,
		IFNULL(SUM(IF((det.size) = '38', det.jumlah_beli, 0)),0) AS s38,
		IFNULL(SUM(IF((det.size) = '39', det.jumlah_beli, 0)),0) AS s39,
		IFNULL(SUM(IF((det.size) = '40', det.jumlah_beli, 0)),0) AS s40,
		IFNULL(SUM(IF((det.size) = '41', det.jumlah_beli, 0)),0) AS s41,
		IFNULL(SUM(IF((det.size) = '42', det.jumlah_beli, 0)),0) AS s42,
		IFNULL(SUM(IF((det.size) = '43', det.jumlah_beli, 0)),0) AS s43,
		IFNULL(SUM(IF((det.size) = '44', det.jumlah_beli, 0)),0) AS s44,
		(IFNULL(SUM(IF((det.size) = '36', det.jumlah_beli, 0)),0) + 
		IFNULL(SUM(IF((det.size) = '37', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '38', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '39', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '40', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '41', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '42', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '43', det.jumlah_beli, 0)),0) +
		IFNULL(SUM(IF((det.size) = '44', det.jumlah_beli, 0)),0) ) AS subtotal FROM olnso m LEFT JOIN olnsodetail det ON det.id_trans=m.id_trans WHERE det.namabrg LIKE '".$rs2['nama']."%' ".$where_detail;
		$sqdet = mysql_query($sql_detail);
		while($rs3=mysql_fetch_array($sqdet))
		{ 
			if ($rs3['subtotal']>0) {
				$nomer++;
				echo"<td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
		echo"<td class='style_detail'><div align='left'>".$rs2['nama']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s36']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s37']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s38']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s39']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s40']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s41']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s42']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s43']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs3['s44']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".$rs3['subtotal']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".number_format(($rs3['subtotal']/$rs_title['grandtotalqty'])*100,2)."</div></td>";

		$grand_qty += $rs3['subtotal'];
			}
		
		}
		
		
	
	?>
    </tr>  
	<?		
  }
  ?>
    <tr>
    <td class="style9" colspan="11"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <? echo"".$grand_qty;?>
    </div>
	</td>
	<td>
	&nbsp;&nbsp;&nbsp;pcs
	</td>
	</tr>
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
//window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
