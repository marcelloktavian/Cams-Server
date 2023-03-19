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
	
    $start = $_GET['startdate_olnsoar'];
    $end = $_GET['enddate_olnsoar'];
    $id = $_GET['id'];

    $sql_detail = "SELECT p.*,j.nama AS dropshipper, j.type AS dsType, e.nama AS expedition FROM `olnso` p LEFT JOIN `mst_dropshipper` j ON (p.id_dropshipper=j.id) LEFT JOIN `mst_expedition` e ON (p.id_expedition=e.id) WHERE j.deleted=0 AND p.tgl_trans>='$start' AND p.tgl_trans<='$end' AND p.stkirim='1' AND (p.totalqty <> 0) AND (p.piutang> 0) AND `id_dropshipper` = '$id' ";
	$sq2 = mysql_query($sql_detail);
	$sq3 = mysql_query($sql_detail);
    $dropshipper = '';
    while($rs3=mysql_fetch_array($sq3))
	{ 
        $dropshipper=strtoupper($rs3['dropshipper']);
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=ar_oln_credit_".$dropshipper.".xls");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="4"><strong>
			AR OLN CREDIT DETAIL 
        <br><?=$dropshipper?></strong></td>
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
            <td colspan="5" class="style9">&nbsp;</td>
          </tr>
          		  
  </table>  
    
    
  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
  
        
      <tr>
      <th width="30%" class="style_title_left"><div align="center">OLN</div></td>
      <th width="10%" class="style_title"><div align="center">Qty</div></td>
      <th width="15%" class="style_title"><div align="center">Faktur</div></td>
      <th width="15%" class="style_title"><div align="right">Total Ongkir</div></td>
 	  <th width="15%" class="style_title"><div align="right">Total Faktur</div></td>
      
    </tr>
    <?php
	$i=1;
	$nomer=0;
	$grand_qty=0;
	$grand_faktur=0;
	$grand_order=0;
	$grand_ongkir=0;
	$sisa=0;
	$grand_total=0;
	$biaya=0;
	while($rs2=mysql_fetch_array($sq2))
	{ 
	  $nomer++;

  ?>
    <tr>
      <td class="style_detail_left"><div align="left"><?=$rs2['id_trans'];?>
	  </div></td>
      <td class="style_detail"><div align="center"><?=number_format($rs2['totalqty'],0,',','.');?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['faktur'],0,',','.');?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['exp_fee'],0,',','.');?>
      <td class="style_detail"><div align="right"><?=number_format($rs2['total'],0,',','.');?></div></td>
	  </div></td>
    </tr>  <?
	$grand_qty+=$rs2['totalqty'];
	$grand_faktur+=$rs2['faktur'];
	$grand_ongkir+=$rs2['exp_fee'];
	$grand_total+=$rs2['total'];
  }
 	
  ?>
       <tr>
            <td class="style_footer"><div align="right">GrandTotal :</div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_qty,0,',','.');?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_faktur,0,',','.');?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_ongkir,0,',','.');?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_total,0,',','.');?></div></td>
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

window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
