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

	}
	.style_tgl {color: #000000;
		font-size: 8pt;
		font-weight: bold;
		font-family: Tahoma;
	}
	.style99 {font-size: 13pt; font-family:Tahoma}
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
		font-size: 9pt;	
		font-family: Tahoma;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		border-right: 1px solid black;
		padding: 3px;
	}
	.style_title_left {	color: #000000;
		font-size: 9pt;	
		font-family: Tahoma;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		border-right: 1px solid black;
		border-left: 1px solid black;
		
		padding: 3px;
	}

	.cut-off th:nth-child(1) {
		width: 5%;
	}
	.cut-off td:nth-child(1) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(2) {
		width: 5%;
	}
	.cut-off td:nth-child(2) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(3) {
		width: 15%;
	}
	.cut-off td:nth-child(3) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(4) {
		width: 20%;
	}
	.cut-off td:nth-child(4) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(5) {
		width: 5%;
	}
	.cut-off td:nth-child(5) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(6) {
		width: 5%;
	}
	.cut-off td:nth-child(6) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(7) {
		width: 15%;
	}
	.cut-off td:nth-child(7) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(8) {
		width: 10%;
	}
	.cut-off td:nth-child(8) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(9) {
		width: 15%;
	}
	.cut-off td:nth-child(9) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cut-off th:nth-child(10) {
		width: 5%;
	}
	.cut-off td:nth-child(10) {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}


	.style_detail {	color: #000000;
		font-size: 8pt;	
		font-family: Tahoma;
		border-bottom: 1px dashed black;
		border-right: 1px solid black;
		padding: 3px;
		max-width:50px; 
	}
	.style_detail_left {	color: #000000;
		font-size: 8pt;	
		font-family: Tahoma;
		border-bottom: 1px dashed black;
		border-left: 1px solid black;
		border-right: 1px solid black;
		padding: 3px;
	}

	.style_detail_unpacked {	color: #000000;
		font-size: 7pt;	
		font-family: Tahoma;
		border-bottom: 1px dashed black;
		border-right: 1px solid black;
		padding: 3px;
		max-width:50px; 
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
$ship_start=$_GET['ship_start'];
$id_start=$_GET['id_start'];
$ship_end=$_GET['ship_end'];
$id_end=$_GET['id_end'];
$id_pelanggan=$_GET['id'];
$id_exp=$_GET['id_exp'];
$resi=$_GET['resi'];
$filter_title="";
	/*
	if(($id_start != null) and ($id_end != null))
	{
	$filter_title=" AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND SUBSTRING(so.id_trans,8,6) BETWEEN '$id_start' AND '$id_end'";
	}
	else 
	*/
	$filter_title =" AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
	
	if(($ship_start != null) and ($ship_end != null))
	{
		$filter_title.=" AND i.id_ship BETWEEN '$ship_start' AND '$ship_end'";
	}
	
	if ($id_exp != null)
	{
		$filter_title.=" AND e.id_expeditioncat=$id_exp ";
	}
	
	$sql_title ="SELECT (SELECT i.id_ship as id_kirim FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id) WHERE (so.state='1') and (so.deleted=0) $filter_title ORDER BY i.id ASC LIMIT 1) AS first_ship,(SELECT i.id_ship as id_kirim FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id)  WHERE (so.state='1') and (so.deleted=0) $filter_title ORDER BY i.id DESC LIMIT 1) AS last_ship,(SELECT so.id_trans FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id)  WHERE (so.state='1') and (so.deleted=0) and (so.stkirim=0) $filter_title ORDER BY so.id_trans ASC LIMIT 1) AS first_order,
	(SELECT so.id_trans FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id) WHERE (so.state='1') and (so.deleted=0) and (so.stkirim=0) $filter_title ORDER BY so.id_trans DESC LIMIT 1) AS last_order ,
	(SELECT COUNT(so.id_trans) FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id) WHERE (so.state='1') and (so.deleted=0) and (so.stkirim=0) $filter_title) AS jumlah_order,
	(SELECT SUM(so.totalqty) FROM olnso so LEFT JOIN `olnso_id` i on (so.id_trans=i.id_trans) LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id) WHERE (so.state='1') and (so.deleted=0) and (so.stkirim=0) $filter_title) AS jumlah_qty";
    //var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	
	
	?>


	<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)" style="margin-left:6px">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					
					<tr>
						<td width="100%" class="style99" colspan="7"><strong>
						PRINT ORDER</strong></td>
					</tr>
					<tr>
						<td width="100%" class="style_tgl" colspan="7">Dari:
							<?php echo"".$tglstart;?>
							&nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Order:&nbsp;<?php echo"".$rs_title['jumlah_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qty:&nbsp;<?php echo"".$rs_title['jumlah_qty'];?> &nbsp;&nbsp;ID_OLN:&nbsp;<?php echo"".$rs_title['first_order'];?>&nbsp-&nbsp;<?php echo"".$rs_title['last_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID_Ship:&nbsp;<?php echo"".$rs_title['first_ship'];?>&nbsp;-&nbsp;<?php echo"".$rs_title['last_ship'];?></td>           
						</tr>
						
					</table>  
					<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<?
							
							$sql_exp = "SELECT e.`nama`,e.kode_warna,jual.totalexp FROM mst_expedition e LEFT JOIN (SELECT so.id_expedition,COUNT(so.id_expedition)AS totalexp FROM olnso so LEFT JOIN olnso_id i ON so.id_trans=i.id_trans LEFT JOIN `mst_expedition` e on (so.id_expedition=e.id) WHERE (so.state='1') and (so.stkirim=0)  $filter_title GROUP BY so.id_expedition) AS jual ON e.id=jual.id_expedition where e.deleted=0";
							
	//$sql_exp = "SELECT e.`nama`,e.kode_warna,COUNT(so.id_expedition) AS totalexp FROM olnso so LEFT JOIN olnso_id i ON so.id_trans=i.id_trans LEFT JOIN mst_expedition e ON so.id_expedition=e.id WHERE e.deleted=0 $filter_title GROUP BY so.id_expedition ";
	//var_dump($sql_exp);die;
							
							$data=mysql_query($sql_exp);
	//$rs_exp = mysql_fetch_array($data);
							$record_count = 0;
							while ($row=mysql_fetch_array($data))
							{
    //Check to see if it is time to start a new row
    //Note: the first time through when
    //$record_count==0, don't start a new row
								if ($record_count % 5==0 && $record_count != 0)
								{
									echo "</tr><tr>";
								}
								
    //Echo out the entire record in one table cell:
								echo "<td class='style9'><div align='center' style='color:$row[kode_warna]; font-weight:bold;'>";
								echo $row['nama'].'&nbsp;&nbsp';
								echo "</div></td>";
								echo "<td><div align='center' style='color:$row[kode_warna]; font-weight:bold;'>";
								echo number_format($row['totalexp'],0).'&nbsp;&nbsp';
								echo "</div></td>";
    //Indicate another record has been echoed:
								$record_count++;
							}
							?>
						</tr>
					</table>  
					
					<table class="cut-off "width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						
						<tr>
							<td colspan="10" class="style9"><hr /></td>
						</tr>
						<tr>
							<th width="5%" class="style_title_left"><div align="center">ID.ship</div></td>
								<th width="5%" class="style_title"><div align="center">ID.oln</div></td>
									<th width="15%" class="style_title"><div align="left">Dropshipper</div></td>
										<th width="15%" class="style_title"><div align="left">Item</div></td>
											<th width="5%" class="style_title"><div align="center">UK</div></td>
												<th width="5%" class="style_title"><div align="center">Qty</div></td>
													<th width="15%" class="style_title"><div align="center">Penerima</div></td>
														<th width="10%" class="style_title"><div align="center">Pengiriman</div></td>
															<th width="10%" class="style_title"><div align="center">No Resi</div></td>
															<th width="10%" class="style_title"><div align="center">Status</div></td>
															</tr>
															<?
															
															$where = "WHERE m.state='1' and m.stkirim=0 and m.deleted=0 AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
	/*
	if(($id_start != null) and ($id_end != null))
	{
	$where .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y') AND SUBSTRING(m.id_trans,8,6) BETWEEN '$id_start' AND '$id_end'";
	}
	else 
	*/
	if(($ship_start != null) and ($ship_end != null))
	{
		$where .=" AND i.id_ship BETWEEN '$ship_start' AND '$ship_end'";
	}
	
	if ($id_exp != null)
	{
		$where .=" AND e.id_expeditioncat=$id_exp ";
	}
	
	$sql_detail = "SELECT dt.id_trans,SUBSTRING(dt.id_trans,8,5) AS alias_id,m.tgl_trans, m.ref_kode AS id_web,d.nama AS dropshipper,dt.namabrg,dt.jumlah_beli,dt.size,m.nama AS pembeli,e.nama AS expedition,m.state,i.id_ship as id_kirim,m.stkirim,m.exp_code FROM olnsodetail dt
	INNER JOIN olnso m ON dt.id_trans = m.id_trans
	LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
	LEFT JOIN olnso_id i ON m.id_trans = i.id_trans 
	LEFT JOIN mst_expedition e ON m.id_expedition = e.id ".$where." order by (i.id),m.id_trans,dt.id_so_d asc";
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
		echo"<tr>";	  	
    //bikin master_printorder
		if (($kode!=$rs2['id_trans']))
		{
			$nomer++;
			echo"<td class='style_detail_left'><div align='center'>".$rs2['id_kirim']."</div></td>";
			echo"<td class='style_detail'><div align='center'>".$rs2['alias_id']."</div></td>";
		//echo"<td class='style_detail'><div align='center'>".$rs2['id_web']."</div></td>";
			echo"<td class='style_detail'><div align='left'>".$rs2['dropshipper']."</div></td>";
			echo"<td class='style_detail'><div align='left'>&nbsp;".$rs2['namabrg']."</div></td>";
			echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
			echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
			echo"<td class='style_detail'><div align='left'>".$rs2['pembeli']."</div></td>";
			if($rs2['exp_code'] == '' || $resi == 'false'){
				echo"<td class='style_detail'><div align='left'>".$rs2['expedition']."</div></td>";
			}else{
				echo"<td class='style_detail'><div align='left'>".$rs2['expedition']."<br>(".$rs2['exp_code'].")</div></td>";
			}
			echo"<td class='style_detail'><div align='left'>".$rs2['exp_code']."</div></td>";
			if ($rs2['stkirim']=='0') {
				echo"<td class='style_detail_unpacked'><div align='left'>UNPACKED</div></td>";
			} else {
				echo"<td class='style_detail_unpacked'><div align='left'>PACKED</div></td>";
			}
			
			$kode=$rs2['id_trans'];
		}
		else if($kode=$rs2['id_trans'])
		{
			echo"<td class='style_detail_left'><div align='center'></div></td>";
			echo"<td class='style_detail'><div align='center'></div></td>";
			echo"<td class='style_detail'><div align='left'></div></td>";
			echo"<td class='style_detail'><div align='left'>&nbsp;".$rs2['namabrg']."</div></td>";
			echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
			echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
			echo"<td class='style_detail'><div align='left'></div></td>";
			echo"<td class='style_detail'><div align='left'></div></td>";
			echo"<td class='style_detail'><div align='left'></div></td>";
			if ($rs2['stkirim']=='0') {
				echo"<td class='style_detail_unpacked'><div align='left'>UNPACKED</div></td>";
			} else {
				echo"<td class='style_detail_unpacked'><div align='left'>PACKED</div></td>";
			}		
		}
		echo"</tr>";  
		
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
	
	
	
</table>




<div align="center"></div>
</form>

<script language="javascript">
	window.print();
</script>
<div align="center"><span class="style20">
	
</span> </div>
