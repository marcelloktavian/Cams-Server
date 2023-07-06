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
	$category=$_GET['category'];
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];

    if($category != '') 
	{
        $sql_title ="SELECT (SUM(dt.qty31)+SUM(dt.qty32)+SUM(dt.qty33)+SUM(dt.qty34)+SUM(dt.qty35)+SUM(dt.qty36)+SUM(dt.qty37)+SUM(dt.qty38)+SUM(dt.qty39)+SUM(dt.qty40)+SUM(dt.qty41)+SUM(dt.qty42)+SUM(dt.qty43)+SUM(dt.qty44)+SUM(dt.qty45)+SUM(dt.qty46)) AS grandtotalqty FROM b2breturn_detail dt INNER JOIN b2breturn m ON dt.id_parent=m.id WHERE (m.post='1') AND (m.deleted=0) AND (m.id_kategori = '$category') AND DATE(m.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    }else{
        $sql_title ="SELECT (SUM(dt.qty31)+SUM(dt.qty32)+SUM(dt.qty33)+SUM(dt.qty34)+SUM(dt.qty35)+SUM(dt.qty36)+SUM(dt.qty37)+SUM(dt.qty38)+SUM(dt.qty39)+SUM(dt.qty40)+SUM(dt.qty41)+SUM(dt.qty42)+SUM(dt.qty43)+SUM(dt.qty44)+SUM(dt.qty45)+SUM(dt.qty46)) AS grandtotalqty FROM b2breturn_detail dt INNER JOIN b2breturn m ON dt.id_parent=m.id WHERE (m.post='1') AND (m.deleted=0) AND DATE(m.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    }

    $where_detail = "";
	$where_detail =" AND (m.post='1') AND (m.deleted=0) ";
	
	if($category != '') 
	{
	    $where_detail .= " AND (m.id_kategori = '$category') AND DATE(m.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
	}
	else
	{
	    $where_detail .= "AND DATE(m.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";
    }
	
    // var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	
	if(isset($rs_title['grandtotalqty'])){
        $totalqty = $rs_title['grandtotalqty'];
    }else{
        $totalqty = 0;
    }
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			B2B RETURN PRODUCT REPORT</strong></td>
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
            <td width="100%" class="style_tgl" colspan="7"><div id='totalqty'>Dari: <?= $tglstart;?>&nbsp;-&nbsp;<?= $tglend ?>&nbsp;&nbsp; Total Product: <?= $totalqty;?>
            </div></td>           
		  </tr>
          		  
  </table>  
     
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.</div></td>
      <th width="31%" class="style_title"><div align="left">Nama Barang</div></td>
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
      <th width="5%" class="style_title"><div align="center">Totalqty</div></td>
      <th width="5%" class="style_title"><div align="center">%</div></td>
      
    </tr>
    <?
	;
    // if ($_GET['category']=='' || $_GET['category']==null) {
    	$sql = "(SELECT id,nama FROM mst_b2bproductsgrp WHERE deleted=0 ORDER BY nama ASC)";
    // } else {
    // 	$sql = "(SELECT id,nama FROM mst_b2bproductsgrp WHERE id_category='".$_GET['category']."' AND deleted=0 ORDER BY nama ASC)";
    // }

    // var_dump($sql);die;
    
	$sq2 = mysql_query($sql);
	$i=1;
	$nomer=0;
	$grand_qty=0;
	while($rs2=mysql_fetch_array($sq2))
	{ 	
  ?>
    <tr>
    <?
		$sql_detail = "SELECT count(*), 
		IFNULL(SUM(IF((det.qty31) > 0, det.qty31, 0)),0) AS s31,
		IFNULL(SUM(IF((det.qty32) > 0, det.qty32, 0)),0) AS s32,
		IFNULL(SUM(IF((det.qty33) > 0, det.qty33, 0)),0) AS s33,
		IFNULL(SUM(IF((det.qty34) > 0, det.qty34, 0)),0) AS s34,
		IFNULL(SUM(IF((det.qty35) > 0, det.qty35, 0)),0) AS s35,
		IFNULL(SUM(IF((det.qty36) > 0, det.qty36, 0)),0) AS s36,
		IFNULL(SUM(IF((det.qty37) > 0, det.qty37, 0)),0) AS s37,
		IFNULL(SUM(IF((det.qty38) > 0, det.qty38, 0)),0) AS s38,
		IFNULL(SUM(IF((det.qty39) > 0, det.qty39, 0)),0) AS s39,
		IFNULL(SUM(IF((det.qty40) > 0, det.qty40, 0)),0) AS s40,
		IFNULL(SUM(IF((det.qty41) > 0, det.qty41, 0)),0) AS s41,
		IFNULL(SUM(IF((det.qty42) > 0, det.qty42, 0)),0) AS s42,
		IFNULL(SUM(IF((det.qty43) > 0, det.qty43, 0)),0) AS s43,
		IFNULL(SUM(IF((det.qty44) > 0, det.qty44, 0)),0) AS s44,
		IFNULL(SUM(IF((det.qty45) > 0, det.qty45, 0)),0) AS s45,
		IFNULL(SUM(IF((det.qty46) > 0, det.qty46, 0)),0) AS s46,

		(IFNULL(SUM(IF((det.qty31) > 0, det.qty31, 0)),0) +
		IFNULL(SUM(IF((det.qty32) > 0, det.qty32, 0)),0) +
		IFNULL(SUM(IF((det.qty33) > 0, det.qty33, 0)),0) +
		IFNULL(SUM(IF((det.qty34) > 0, det.qty34, 0)),0) +
		IFNULL(SUM(IF((det.qty35) > 0, det.qty35, 0)),0) +
		IFNULL(SUM(IF((det.qty36) > 0, det.qty36, 0)),0) + 
		IFNULL(SUM(IF((det.qty37) > 0, det.qty37, 0)),0) +
		IFNULL(SUM(IF((det.qty38) > 0, det.qty38, 0)),0) +
		IFNULL(SUM(IF((det.qty39) > 0, det.qty39, 0)),0) +
		IFNULL(SUM(IF((det.qty40) > 0, det.qty40, 0)),0) +
		IFNULL(SUM(IF((det.qty41) > 0, det.qty41, 0)),0) +
		IFNULL(SUM(IF((det.qty42) > 0, det.qty42, 0)),0) +
		IFNULL(SUM(IF((det.qty43) > 0, det.qty43, 0)),0) +
		IFNULL(SUM(IF((det.qty44) > 0, det.qty44, 0)),0) +
		IFNULL(SUM(IF((det.qty45) > 0, det.qty45, 0)),0) +
		IFNULL(SUM(IF((det.qty46) > 0, det.qty46, 0)),0) ) AS subtotal FROM b2breturn m LEFT JOIN b2breturn_detail det ON det.id_parent=m.id WHERE det.id_product = '".$rs2['id']."' ".$where_detail;

		$sqdet = mysql_query($sql_detail);
		while($rs3=mysql_fetch_array($sqdet))
		{ 
			if ($rs3['subtotal']>0) {
				$nomer++;
				echo"<td class='style_detail_left'><div align='right'>".$nomer."</div></td>";
				echo"<td class='style_detail'><div align='left'>".$rs2['nama']."</div></td>";
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
    <td class="style9" colspan="18"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <!-- <? echo"".$grand_qty;?> -->
          <?= $totalqty;?>
    </div>
	</td>
	<td class="style9">
	&nbsp;&nbsp;&nbsp;pcs
	</td>
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