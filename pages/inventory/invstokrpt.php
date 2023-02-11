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
.style_detail_null {	
	background-color: rgba(128,128,128, 0.5);
	border-bottom: 1px dashed black;
	border-right: 1px solid black;
	padding: 3px;
}
.style_detail_limit {	
	background-color: rgba(255,0,0, 0.5);
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
    @media print { body { -webkit-print-color-adjust: exact; } }
</style>
<?php
    error_reporting(0);
	include("../../include/koneksi.php");
	$tglstart=$_GET['start'];

	$where_title  ="";
	$where_title  =" WHERE TRUE AND p.deleted=0  AND p.size IS NOT NULL AND p.size <> ''  AND DATE(p.lastmodified) <= STR_TO_DATE('$tglstart','%d/%m/%Y') ";
	if ($_GET['filter']!='' || $_GET['filter']!=null) {
    	$where_title .= " AND (id like '%".$_GET['filter']."%' or kode like '%".$_GET['filter']."%' or nama like '%".$_GET['filter']."%')";
    } 

    $where_detail = "";
	$where_detail =" AND TRUE AND det.deleted=0 AND det.size IS NOT NULL AND det.size <> ''  AND DATE(det.lastmodified) <= STR_TO_DATE('$tglstart','%d/%m/%Y')";
	
	
	$sql_title = "SELECT sum(stok) as stok FROM `inventory_balance` p ".$where_title;
    // var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	
	
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			INVENTORY REPORT</strong></td>
          </tr>
          <tr>
            <td width="100%" class="style_tgl" colspan="7">
            	Tanggal s.d. : <?= $tglstart ?>
            &nbsp;&nbsp;Total Stok:<?php echo"  ".number_format($rs_title['stok']);?></td>
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
      <th width="10%" class="style_title"><div align="center">Total Stok</div></td>
      <th width="10%" class="style_title"><div align="center">%</div></td>
      
    </tr>
    <?
	
	
	// $sql_detail = "SELECT dt.nama,SUM(dt.stok) AS totalqty
	// ,SUM(IF((dt.size) = '36', dt.stok, 0) ) AS s36 
	// ,SUM(IF((dt.size) = '37', dt.stok, 0) ) AS s37 
	// ,SUM(IF((dt.size) = '38', dt.stok, 0) ) AS s38 
	// ,SUM(IF((dt.size) = '39', dt.stok, 0) ) AS s39 
	// ,SUM(IF((dt.size) = '40', dt.stok, 0) ) AS s40 
	// ,SUM(IF((dt.size) = '41', dt.stok, 0) ) AS s41 
	// ,SUM(IF((dt.size) = '42', dt.stok, 0) ) AS s42 
	// ,SUM(IF((dt.size) = '43', dt.stok, 0) ) AS s43 
	// ,SUM(IF((dt.size) = '44', dt.stok, 0) ) AS s44 
	// FROM inventory_balance dt GROUP BY dt.namabrg ASC";

    // var_dump($sql_detail);die;
    if ($_GET['filter']=='' || $_GET['filter']==null) {
    	$sql = "SELECT id,nama FROM inventory_balance WHERE (size='' OR size IS NULL) AND id_category IS NOT NULL AND deleted=0 ORDER BY nama ASC";
    } else {
    	$sql = "SELECT id,nama FROM inventory_balance WHERE (id like '%".$_GET['filter']."%' or kode like '%".$_GET['filter']."%' or nama like '%".$_GET['filter']."%') and (size='' OR size IS NULL) AND id_category IS NOT NULL AND deleted=0 ORDER BY nama ASC";
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
	  
		// $nomer++;
		// 		echo"<td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
		// echo"<td class='style_detail'><div align='left'>".str_replace('- ','',$rs2['nama'])."</div></td>";

		$sql_detail = "SELECT 
		SUM(IF((det.size) = '36', det.stok, NULL)) AS s36, 
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='36') AS limit36,
		SUM(IF((det.size) = '37', det.stok, NULL)) AS s37,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='37') AS limit37,
		SUM(IF((det.size) = '38', det.stok, NULL)) AS s38,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='38') AS limit38,
		SUM(IF((det.size) = '39', det.stok, NULL)) AS s39,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='39') AS limit39,
		SUM(IF((det.size) = '40', det.stok, NULL)) AS s40,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='40') AS limit40,
		SUM(IF((det.size) = '41', det.stok, NULL)) AS s41,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='41') AS limit41,
		SUM(IF((det.size) = '42', det.stok, NULL)) AS s42,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='42') AS limit42,
		SUM(IF((det.size) = '43', det.stok, NULL)) AS s43,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='43') AS limit43,
		SUM(IF((det.size) = '44', det.stok, NULL)) AS s44,
		(select limit_stok from inventory_balance ib WHERE ib.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' and ib.size='44') AS limit44,
		(IFNULL(SUM(IF((det.size) = '36', det.stok, 0)),0) + 
		IFNULL(SUM(IF((det.size) = '37', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '38', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '39', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '40', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '41', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '42', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '43', det.stok, 0)),0) +
		IFNULL(SUM(IF((det.size) = '44', det.stok, 0)),0) ) AS subtotal FROM inventory_balance det WHERE det.nama LIKE '".str_replace('- ','',$rs2['nama'])."%' ";
		// var_dump($sql_detail);die;
		$sqdet = mysql_query($sql_detail);
		while($rs3=mysql_fetch_array($sqdet))
		{ 
			// if ($rs3['subtotal']>0) {
				$nomer++;
				echo"<td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
		echo"<td class='style_detail'><div align='left'>".str_replace('- ','',$rs2['nama'])."</div></td>";

		if ($rs3['s36'] == null) {
				echo"<td class='style_detail_null'><div align='center'>".$rs3['s36']."</div></td>";
		} else {
			if ($rs3['s36']<$rs3['limit36']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s36']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s36']."</div></td>";
			}
		}
		
		if ($rs3['s37'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s37']."</div></td>";
		} else {
			if ($rs3['s37']<$rs3['limit37']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s37']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s37']."</div></td>";
			}
		}

		if ($rs3['s38'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s38']."</div></td>";
		} else {
			if ($rs3['s38']<$rs3['limit38']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s38']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s38']."</div></td>";
			}
		}

		if ($rs3['s39'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s39']."</div></td>";
		} else {
			if ($rs3['s39']<$rs3['limit39']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s39']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s39']."</div></td>";
			}
		}

		if ($rs3['s40'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s40']."</div></td>";
		} else {
			if ($rs3['s40']<$rs3['limit40']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s40']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s40']."</div></td>";
			}
		}

		if ($rs3['s41'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s41']."</div></td>";
		} else {
			if ($rs3['s41']<$rs3['limit41']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s41']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s41']."</div></td>";
			}
		}

		if ($rs3['s42'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s42']."</div></td>";
		} else {
			if ($rs3['s42']<$rs3['limit42']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s42']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s42']."</div></td>";
			}
		}

		if ($rs3['s43'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s43']."</div></td>";
		} else {
			if ($rs3['s43']<$rs3['limit43']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s43']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s43']."</div></td>";
			}
		}

		if ($rs3['s44'] == null) {
			echo"<td class='style_detail_null'><div align='center'>".$rs3['s44']."</div></td>";
		} else {
			if ($rs3['s44']<$rs3['limit44']) {
				echo"<td class='style_detail_limit'><div align='center'>".$rs3['s44']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='center'>".$rs3['s44']."</div></td>";
			}
		}

		echo"<td class='style_detail'><div align='right'>".$rs3['subtotal']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".number_format(($rs3['subtotal']/$rs_title['stok'])*100,2)."</div></td>";

		$grand_qty += $rs3['subtotal'];
			// }
		
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
window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>