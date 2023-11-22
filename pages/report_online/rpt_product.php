<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
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
			size: landscape;
		}
	@media print{@page {size: landscape}}
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
	$where_detail =" (olnso.state='1') AND (olnso.deleted=0)";
	
	// if($id_product != null) 
	// {
	// $where_detail .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND (dt.id_product = '$id_product') ";
	// }
	// else
	// {
	$where_detail .= "AND DATE(olnso.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    // }
	$sql_title ="SELECT SUM(dt.jumlah_beli) AS grandtotalqty FROM olnsodetail dt INNER JOIN olnso m ON dt.id_trans=m.id_trans WHERE (m.state='1') AND (m.deleted=0) AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    //var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	$grand_dpp = 0;
	$grand_ppn = 0;
	
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			PRODUCT REPORT</strong></td>
			<td style="text-align:right">
                <div id="timestamp">
                <?php
                    date_default_timezone_set('Asia/Jakarta');
                    echo $timestamp = date('d/m/Y H:i:s');
                ?>
                </div>  
                
            </td>
            
          </tr>
          <tr>
            <td width="100%" class="style_tgl" colspan="7"><div id='totalqty'>Dari: <?= $tglstart;?>&nbsp;-&nbsp;<?= $tglend ?>&nbsp;&nbsp; Total Product: <?= $rs_title['grandtotalqty'];?>
            </div></td>           
		  </tr>
          		  
  </table>  
     
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      <th width="3%" class="style_title_left"><div align="center">No.</div></td>
      <th width="15%" class="style_title"><div align="left">Nama Barang</div></td>
	  <th width="3%" class="style_title"><div align="center">31</div></td>
	  <th width="3%" class="style_title"><div align="center">32</div></td>
	  <th width="3%" class="style_title"><div align="center">33</div></td>
	  <th width="3%" class="style_title"><div align="center">34</div></td>
	  <th width="3%" class="style_title"><div align="center">35</div></td>
      <th width="3%" class="style_title"><div align="center">36</div></td>
      <th width="3%" class="style_title"><div align="center">37</div></td>
      <th width="3%" class="style_title"><div align="center">38</div></td>
      <th width="3%" class="style_title"><div align="center">39</div></td>
      <th width="3%" class="style_title"><div align="center">40</div></td>
      <th width="3%" class="style_title"><div align="center">41</div></td>
      <th width="3%" class="style_title"><div align="center">42</div></td>
      <th width="3%" class="style_title"><div align="center">43</div></td>
      <th width="3%" class="style_title"><div align="center">44</div></td>
      <th width="3%" class="style_title"><div align="center">45</div></td>
      <th width="3%" class="style_title"><div align="center">46</div></td>
      <th width="3%" class="style_title"><div align="center">S</div></td>
      <th width="3%" class="style_title"><div align="center">M</div></td>
      <th width="3%" class="style_title"><div align="center">L</div></td>
      <th width="3%" class="style_title"><div align="center">XL</div></td>
      <th width="3%" class="style_title"><div align="center">XXL</div></td>
      <th width="3%" class="style_title"><div align="center">Totalqty</div></td>
      <th width="5%" class="style_title"><div align="center">%</div></td>
      <th width="5%" class="style_title"><div align="center">DPP (unit)</div></td>
      <th width="10%" class="style_title"><div align="center">DPP TOTAL (disc)</div></td>
      <th width="10%" class="style_title"><div align="center">PPN</div></td>
      
    </tr>
    <?php

    if ($_GET['id_product']=='' || $_GET['id_product']==null) {
    	$sql = "(SELECT id,nama FROM mst_products WHERE (size='' OR size IS NULL) AND deleted=0 ORDER BY nama ASC)";
    } else {
    	$sql = "(SELECT id,nama FROM mst_products WHERE id='".$_GET['id_product']."' and (size='' OR size IS NULL) AND deleted=0 ORDER BY nama ASC)";
    }
	
  ?>
    <!-- <tr> -->
    <?php
		$sql_detail = "SELECT
		TRIM( TRAILING SUBSTRING_INDEX( TRIM(namabrg), ' ',- 1 ) FROM TRIM( namabrg ) ) AS namabarang,
		SUM( CASE WHEN size = '31' THEN jumlah_beli ELSE 0 END ) AS s31,
		SUM( CASE WHEN size = '32' THEN jumlah_beli ELSE 0 END ) AS s32,
		SUM( CASE WHEN size = '33' THEN jumlah_beli ELSE 0 END ) AS s33,
		SUM( CASE WHEN size = '34' THEN jumlah_beli ELSE 0 END ) AS s34,
		SUM( CASE WHEN size = '35' THEN jumlah_beli ELSE 0 END ) AS s35,
		SUM( CASE WHEN size = '36' THEN jumlah_beli ELSE 0 END ) AS s36,
		SUM( CASE WHEN size = '37' THEN jumlah_beli ELSE 0 END ) AS s37,
		SUM( CASE WHEN size = '38' THEN jumlah_beli ELSE 0 END ) AS s38,
		SUM( CASE WHEN size = '39' THEN jumlah_beli ELSE 0 END ) AS s39,
		SUM( CASE WHEN size = '40' THEN jumlah_beli ELSE 0 END ) AS s40,
		SUM( CASE WHEN size = '41' THEN jumlah_beli ELSE 0 END ) AS s41,
		SUM( CASE WHEN size = '42' THEN jumlah_beli ELSE 0 END ) AS s42,
		SUM( CASE WHEN size = '43' THEN jumlah_beli ELSE 0 END ) AS s43,
		SUM( CASE WHEN size = '44' THEN jumlah_beli ELSE 0 END ) AS s44,
		SUM( CASE WHEN size = '45' THEN jumlah_beli ELSE 0 END ) AS s45,
		SUM( CASE WHEN size = '46' THEN jumlah_beli ELSE 0 END ) AS s46,
		SUM( CASE WHEN size = 'S' THEN jumlah_beli ELSE 0 END ) AS sS,
		SUM( CASE WHEN size = 'M' OR namabrg LIKE '%hi protect%' THEN jumlah_beli ELSE 0 END ) AS sM,
		SUM( CASE WHEN size = 'L' THEN jumlah_beli ELSE 0 END ) AS sL,
		SUM( CASE WHEN size = 'XL' THEN jumlah_beli ELSE 0 END ) AS sXL,
		SUM( CASE WHEN size = 'XXL' THEN jumlah_beli ELSE 0 END ) AS sXXL,
		SUM( jumlah_beli ) AS subtotal,
		harga_satuan * ( 1-0.11 ) AS dpp,
		SUM(
		IF
			(
				olnso.discount = 0 
				OR olnso.discount IS NULL,
				( harga_satuan * jumlah_beli * (1 - 0.11) ),(
					harga_satuan * jumlah_beli 
				) * ( 1-olnso.discount ) * (1-0.11) 
			) 
		) AS dpp_total_disc,
		SUM( IF(DATE(lastmodified) < '2022-04-01' ,((harga_satuan * (1-0.11)) * 0.1 * jumlah_beli ) ,(harga_satuan * ( 0.11 ) * jumlah_beli))  ) AS ppn 
	FROM
		olnsodetail det
		LEFT JOIN olnso ON det.id_trans = olnso.id_trans 
	WHERE ".$where_detail." GROUP BY TRIM( TRAILING SUBSTRING_INDEX( TRIM(namabrg), ' ',- 1 ) FROM TRIM( namabrg ) )";
		$sql2= "SELECT IFNULL(SUM(IF((det.size) = '', det.jumlah_beli, 0)),0) as subtotal FROM olnso m LEFT JOIN olnsodetail det ON det.id_trans=m.id_trans WHERE det.namabrg LIKE '".addslashes($rs2['nama'])."%' ".$where_detail;

		$sqdet = mysql_query($sql_detail);

		// echo $sql_detail;

		while($rs3=mysql_fetch_array($sqdet))
		{ 
			if ($rs3['subtotal']>0) {
				$nomer++;
				echo"<tr><td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
				echo"<td class='style_detail'><div align='left'>".$rs3['namabarang']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s31']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s32']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s33']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s34']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s35']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s36']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s37']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s38']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s39']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s40']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s41']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s42']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s43']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s44']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s45']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['s46']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['sS']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['sM']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['sL']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['sXL']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".$rs3['sXXL']."</div></td>";
				echo"<td class='style_detail'><div align='right'>".$rs3['subtotal']."</div></td>";
				echo"<td class='style_detail'><div align='center'>".number_format(($rs3['subtotal']/$rs_title['grandtotalqty'])*100,2)."</div></td>";
				echo"<td class='style_detail'><div align='center'>".number_format($rs3['dpp'])."</div></td>";
				echo"<td class='style_detail'><div align='center'>".number_format($rs3['dpp_total_disc'])."</div></td>";
				echo"<td class='style_detail'><div align='center'>".number_format($rs3['ppn'])."</div></td></tr>";

				$grand_qty += $rs3['subtotal'];
				$grand_dpp += $rs3['dpp_total_disc'];
				$grand_ppn += $rs3['ppn'];
			}else{
				// $sqdet2 = mysql_query($sql2);
				// while($rs4=mysql_fetch_array($sqdet2))
				// { 
				// 	if ($rs4['subtotal']>0) {
				// 	$nomer++;
				// 	echo"<td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
				// 	echo"<td class='style_detail'><div align='left'>".$rs2['nama']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s31']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s32']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s33']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s34']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s35']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s36']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s37']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s38']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s39']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s40']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s41']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s42']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s43']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s44']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s45']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['s46']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['sS']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['sM']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['sL']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['sXL']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".$rs3['sXXL']."</div></td>";
				// 	echo"<td class='style_detail'><div align='right'>".$rs4['subtotal']."</div></td>";
				// 	echo"<td class='style_detail'><div align='center'>".number_format(($rs4['subtotal']/$rs_title['grandtotalqty'])*100,2)."</div></td>";

				// 	$grand_qty += $rs4['subtotal'];
				// 	}
				// }
			}
		
		}
	?>
    <!-- </tr>   -->
	<?php
//   }
  ?>
    <tr>
    	<td class="style9" colspan="23"><div align="right">Total</div></td>
		<td class="style9" colspan="4"><div align="right">
          <?= $rs_title['grandtotalqty'];?>
		  &nbsp;pcs
    	</div>
		</td>
	</tr>
	<tr>
		<td class="style9" colspan="23"><div align="right">DPP</div></td>
		<td class="style9" colspan="4"><div align="right"><?= number_format($grand_dpp); ?></div></td>
	</tr>
	<tr>
		<td class="style9" colspan="23"><div align="right">PPN</div></td>
		<td class="style9" colspan="4"><div align="right"><?= number_format($grand_ppn); ?></div></td>
	</tr>
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
			$(document).ready(function() {
    	setInterval(timestamp, 1000);
});

function timestamp() {
    $.ajax({
        url: '../timestamp.php',
        success: function(data) {
            $('#timestamp').html(data);
        },
    });
}
//window.print();
<?php
	// echo 'document.getElementById("totalqty").innerHTML="Dari: '.$tglstart.
 //            '&nbsp;-&nbsp;'.$tglend.'&nbsp;&nbsp; Total Product: '.number_format($grand_qty,0).'";';
?>
</script>
  <div align="center"><span class="style20">
   
  </span> </div>