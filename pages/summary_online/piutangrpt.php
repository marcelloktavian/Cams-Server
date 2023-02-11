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
    }
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	
	$where_title=" deleted=0 ";	
	$where_title .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	
	
	$sql_title ="SELECT (SELECT id_trans FROM trpiutang WHERE $where_title ORDER BY id_trans ASC LIMIT 1) AS first_order,
    (SELECT id_trans FROM trpiutang WHERE  $where_title ORDER BY id_trans DESC LIMIT 1) AS last_order ,
    (SELECT COUNT(id_trans) FROM trpiutang WHERE  $where_title) AS jumlah_order";
	
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
			ONLINE BILL REPORT </strong></td>
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
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Order:&nbsp;<?php echo"".$rs_title['jumlah_order'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_awal:&nbsp;<?php echo"".$rs_title['first_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_akhir:&nbsp;<?php echo"".$rs_title['last_order'];?></td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="9" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="10%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="10%" class="style_title"><div align="center">ID.Sales</div></td>
      <th width="15%" class="style_title"><div align="center">Dropshipper</div></td>
      <th width="15%" class="style_title"><div align="center">Piutang</div></td>
 	  <th width="10%" class="style_title"><div align="center">Tgl.Bayar</div></td>
      <th width="15%" class="style_title"><div align="center">TotalBayar</div></td>
      <th width="15%" class="style_title"><div align="center">Tunai</div></td>
      <th width="15%" class="style_title"><div align="center">Transfer</div></td>
 	  <th width="10%" class="style_title"><div align="center">Keterangan</div></td>
      
    </tr>
    <?
		
	$where = "";
	$where .= " WHERE (p.deleted=0) AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	
	$sql_detail = "SELECT p.id_trans,p.id_transjual,date_format(p.tgl_trans,'%d-%m-%Y') as tgl_trans, p.totalfaktur,p.faktur,p.tunai,p.transfer,d.nama AS dropshipper,o.total as totaljual,o.piutang,p.keterangan,p.info FROM trpiutang p
    LEFT JOIN olnso o ON p.id_transjual = o.id_trans
    LEFT JOIN mst_dropshipper d ON o.id_dropshipper = d.id ".$where." order by p.id_trans asc";
    //var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$grand_totalfaktur=0;
	$grand_tunai=0;
	$grand_transfer=0;
	$grand_jual=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 	    
  ?>
    <tr>
    <?
	  $nomer++;
	  //bikin master_ongkir
	   
		echo"<td class='style_detail_left'><div align='center'>".$rs2['id_trans']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['id_transjual']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['piutang'])."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['tgl_trans']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['totalfaktur'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['tunai'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['transfer'])."</div></td>";
		echo"<td class='style_detail'><div align='left'>".$rs2['keterangan']."-".$rs2['info']."</div></td>";
		
		
	?>
    </tr>  
	<?
	$grand_totalfaktur+=$rs2['totalfaktur'];
	$grand_tunai+=$rs2['tunai'];
	$grand_transfer+=$rs2['transfer'];
	$grand_jual +=$rs2['totaljual'];
		
  }
  ?>
       <tr>
            <td colspan="4" class="style_title_left"><div align="right">Total:</div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_totalfaktur);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_tunai);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_transfer);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_jual);?></div></td>
            <td class="style_title"><div align="right">&nbsp;</div></td>
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
