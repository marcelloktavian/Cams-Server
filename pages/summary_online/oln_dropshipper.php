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
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$id_dp=$_GET['id_dp'];
	
	$sql_title ="SELECT nama,disc FROM mst_dropshipper where id=$id_dp";
	
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
			DROPSHIPPER REPORT (<?php echo"".$rs_title['nama'];?>)</strong></td>
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
            <td width="100%" class="style9b" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Disc:&nbsp;<?php echo"".$rs_title['disc'];?> </td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="11" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="5%" class="style_title"><div align="center">No.web</div></td>
      <th width="30%" class="style_title"><div align="center">Items</div></td>
      <th width="10%" class="style_title"><div align="center">Price</div></td>
      <th width="5%" class="style_title"><div align="center">Disc Item</div></td>
      <th width="5%" class="style_title"><div align="center">Disc DP</div></td>
      <th width="5%" class="style_title"><div align="center">Qty</div></td>
      <th width="20%" class="style_title"><div align="right">Nett Price(+PPN)</div></td>
 	  <th width="10%" class="style_title"><div align="right">Subtotal</div></td>
 	  <th width="10%" class="style_title"><div align="right">Disc Faktur</div></td>
 	  <th width="10%" class="style_title"><div align="right">Total</div></td>
      
    </tr>
    <?
		
	$where = "WHERE (m.deleted=0) and (m.state='1')";
	if($id_dp != null){
	$where .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND m.id_dropshipper=$id_dp";
	}
	else
	{
	$where .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
    
	$sql_detail = "SELECT dt.id_trans,m.lastmodified, m.ref_kode AS id_web,d.nama AS dropshipper,d.disc AS disc_dropshipper,dt.namabrg,dt.jumlah_beli,dt.harga_satuan,dt.disc as disc_item,dt.subtotal,dt.size,m.nama AS pembeli,m.state,m.discount_faktur as disc_faktur,m.total,m.faktur,(SELECT SUM(m.exp_fee) FROM olnso m ".$where.") AS ongkir,(SELECT count(m.id_trans) FROM olnso m ".$where.") AS totalorder FROM olnsodetail dt
	INNER JOIN olnso m ON dt.id_trans = m.id_trans
	LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
	".$where." order by m.id_trans asc";
	
    // var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$nett_price=0;
	$nett_subtotal=0;
	$grand_subtotal=0;
	$biaya=0;
	while($rs2=mysql_fetch_array($sq2))
	{ 
	  $nett_price=(($rs2['harga_satuan']-$rs2['disc_item'])*(1-$rs2['disc_dropshipper']))*1;
	  $nett_subtotal=ceil($nett_price) * $rs2['jumlah_beli'];
	  $disc=$rs2['disc_faktur'];
	  //total merupakan faktur tanpa ongkir - disc faktur
	  $total=$rs2['faktur']-$rs2['disc_faktur'];

	  ?>
      <tr>
      <?
	  $nomer++;
	  //bikin master_ongkir
	  if ($kode!=$rs2['id_trans'])
	  {
	    $grand_ongkir+=$rs2['ongkir'];
		$grand_disc+=$rs2['disc_faktur'];
		$grand_total+=$total;
		
		echo"<td class='style_detail_left'><div align='center'>".$rs2['id_trans']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['harga_satuan']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['disc_item']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['disc_dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($nett_price)."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($nett_subtotal)."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($disc)."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($total)."</div></td>";
		
		$kode=$rs2['id_trans'];
	  }
      else if($kode=$rs2['id_trans'])
	  {
		echo"<td class='style_detail_left'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['harga_satuan']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['disc_item']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['disc_dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($nett_price)."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($nett_subtotal)."</div></td>";
		echo"<td class='style_detail'><div align='right'></div></td>";
	    echo"<td class='style_detail'><div align='right'></div></td>";	    
	  }	  	  
  ?>
  </tr>  
	<?
	$grand_qty+=$rs2['jumlah_beli'];
	$grand_subtotal+=$nett_subtotal;
	$grand_order=$rs2['totalorder'];
	$grand_biaya=$rs2['ongkir'];
	$totaldpp =($grand_total/1.11);
	$totalppn= ($totaldpp*0.11);
  } 	
  ?>
  
       <tr>
            <td class="style_footer"><div align="right">Total Order:</div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_order);?></div></td>
            <td colspan="4" class="style_footer"><div align="right">Grand Total:</div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_qty);?></div></td>
            <td colspan="2" class="style_footer"><div align="right"><?=number_format($grand_subtotal);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_disc);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_total);?></div></td>
       </tr>
	   <tr>
            <td colspan="6" class="style_footer"><div align="right">DPP:</div></td>
            <td colspan="5" class="style_footer"><div align="right"><?=number_format($totaldpp);?></div></td>
       </tr>
	   <tr>
            <td colspan="6" class="style_footer"><div align="right">PPN:</div></td>
            <td colspan="5" class="style_footer"><div align="right"><?=number_format($totalppn);?></div></td>
       </tr>
       <tr>
            <td colspan="6" class="style_footer"><div align="right">Titipan ongkir:</div></td>
            <td colspan="5" class="style_footer"><div align="right"><?=number_format($grand_biaya);?></div></td>
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
