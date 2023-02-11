<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>

<style type="text/css">
.style9 {
font-size: 8pt; 
font-family:MS Reference Sans Serif;
}
.style9b {color: #000000;
	font-size: 8pt;
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
.style11btl {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	padding: 3px;
}
.style11btl_kirim {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	
}
.style11btl_detail {	color: #000000;
	font-size: 7pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	padding: 3px;
}
.style11btl_title {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	/* background : #E0DAD8 ; */
	border-top: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	padding: 3px;
}
.style11btr {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	padding: 3px;
}
.style11btr_title {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
	/* background : #E0DAD8 ;*/
	padding: 3px;
	
}
.style11br {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-right: 1px solid black;
	padding: 3px;
}

.style11bt {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	padding: 3px;
}

.style11btlr {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	padding: 3px;
}
.style11rl {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-right: 1px solid black;
	border-left: 1px solid black;
	padding: 3px;
}
.style11btlr_alamat {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	padding: 3px;
}
.style11btlr_detail {	color: #000000;
	font-size: 7pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;	
	padding: 3px;
}
.style11btlr_total {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-style:double;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	padding: 3px;
}
.style11btlr_title {color: #000000;
	font-size: 7pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	/* background : #E0DAD8 ; */
	/* background : grey ; */
    padding: 3px;	
}
.style9_title {color: #000000;
	font-size: 7pt;	
	font-family: Tahoma;
	padding: 3px;	
}
.style9_detail {	color: #000000;
	font-size: 7pt;	
	font-family: Tahoma;
	padding: 3px;
}
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Tahoma;
}
.style12_footer {	color: #000000;
	font-size: 12pt;	
	font-family: Tahoma;
	padding: 3px;
}
/*
@page {
        size: A4;
        margin: 15px;
    }
*/	
img.resize {
  max-width:70%;
  max-height:70%;
  align :left;
}	
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	//include("koneksi/koneksi.php");
    $id_faktur=$_GET['id_trans'];
    //$id_faktur=TSO18020021;
	$sql_jual="SELECT do.*,(so.ref_kode) as no_po,DATE_FORMAT(so.tgl_trans,'%d/%m/%Y')AS tglsales,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman,DATE_FORMAT(do.tgl_trans,'%d/%m/%Y')as tgl,ad.kecamatan,ad.kabupaten,ad.provinsi FROM `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id) LEFT JOIN `mst_address` ad ON (do.id_address=ad.id)
    where do.id_trans='".$id_faktur."'";
	$sql = mysql_query($sql_jual);
	//var_dump($sql_jual); die;
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0"   align="center" cellpadding="0" cellspacing="0">
          <!-- 1 -->
		  <tr> 
		  <th rowspan="2" colspan="13" class="style11btlr_noborder">
		  <!--
		  <img class="resize" src="../../files/Camou.png">
		  -->
		  </th> 
		  <th colspan="3" class="style11btlr"><div align="left"><?=$rs['id_trans'];?>(<?=$rs['tgl'];?>)</div></th>
          
 		  </tr> 
		  <!-- 2 -->
		  
		  <tr> 
		  <th colspan="3" class="style11btlr"><div align="left"><?=$rs['customer'];?><br/><?=$rs['alamat'];?><br/><?=$rs['kabupaten'];?></div></th>
          
		 
		  </tr>
		  <!--
          <tr><th colspan="16" class="style11rl"></th></tr>
          <tr><th colspan="16" class="style11rl"></th></tr>
          <tr><th colspan="16" class="style11rl"></th></tr>
          -->
		  
		  <tr><td colspan="16">.</td></tr>
		  <tr><td colspan="16">.</td></tr>
		  <tr><td colspan="16">.</td></tr>
		  <tr><td colspan="16">.</td></tr>
		  <tr><td colspan="16">.</td></tr>
		  <tr><td colspan="16">.</td></tr>
		  
		  <tr>
		  <th width="2%"  class="style9_title"><div align="center"></div></th>
		  <th width="21%" class="style9_title"  colspan="1"><div align="left"></div></th>
		  
		  <th width="2.5%" class="style11btlr_title"><div align="center">36</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">37</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">38</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">39</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">40</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">41</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">42</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">43</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">44</div></th>
		  <th width="2.5%" class="style11btlr_title"><div align="center">45</div></th>
		  <th width="16%" class="style9_title"><div align="left"></div></th>
		  <th width="12%" class="style9_title"><div align="left"></div></th>
		  <th width="5%" class="style9_title"><div align="center"></div></th>
		  <th width="25%" class="style9_title"><div align="center"></div></th>
		  
		  
		  </tr>
          
		  <!-- Isi detail -->
	<?
		
	$sql_detail = "SELECT m.note,dt.id_trans,dt.*, dt.id_product,dt.harga_satuan,dt.subtotal,dt.disc,dt.namabrg,SUM(dt.jumlah_kirim) AS totalqty
	,(dt.qty36) AS s36 
	,(dt.qty37) AS s37 
	,(dt.qty38) AS s38 
	,(dt.qty39) AS s39 
	,(dt.qty40) AS s40 
	,(dt.qty41) AS s41 
	,(dt.qty42) AS s42 
	,(dt.qty43) AS s43 
	,(dt.qty44) AS s44 
	,(dt.qty45) AS s45 
	FROM b2bdo_detail dt INNER JOIN b2bdo m on dt.id_trans=m.id_trans WHERE dt.id_trans ='".$id_faktur."' GROUP BY dt.id_product ASC";    
	//var_dump($sql_detail); die;
	$sq2 = mysql_query($sql_detail);
	
	$i=1;
	$nomer=0;
	$harga_satuan=0;
	$subtotal=0;
	$total=0;
	$totalqty=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;
	  //harga satuan merupakan harga nett
      $harga_nett=($rs2['harga_satuan']) *((100-$rs2['disc'])*0.01);
	  $subtotal=$rs2['totalqty'] * $harga_nett;
	  $total+=$subtotal;	  
	  $totalqty+=$rs2['totalqty'];	  
	  ?>	  
	  <tr>		  
      <?
	  //bikin master_printorder
	  if ($kode!=$rs2['id_trans'])
	  {
		echo"<td class='style9_detail'><div align='center'>".$rs2['note']."</div></td>";
		echo"<td class='style9_detail'><div align='right'>".$rs2['namabrg']."&nbsp;</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s36']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s37']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s38']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s39']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s40']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s41']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s42']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s43']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s44']."</div></td>";
		echo"<td class='style11btlr_detail'><div align='right'>".$rs2['s45']."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".$rs2['totalqty']."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".number_format($rs2['disc'],2)."%</div></td>";
		echo"<td class='style9_detail'><div align='right'>".number_format($subtotal)."</div></td>";
		
		$kode=$rs2['id_trans'];
	 }
	 else if($kode=$rs2['id_trans'])
	 {
	    echo"<td class='style9_detail'><div align='center'></div></td>";
		echo"<td class='style9_detail'><div align='right'>".$rs2['namabrg']."&nbsp;</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s36']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s37']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s38']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s39']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s40']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s41']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s42']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s43']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['s44']."</div></td>";
		echo"<td class='style11btlr_detail'><div align='right'>".$rs2['s45']."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".$rs2['totalqty']."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style9_detail'><div align='center'>".number_format($rs2['disc'],2)."%</div></td>";
		echo"<td class='style9_detail'><div align='right'>".number_format($subtotal)."</div></td>";
	  }
		?>
		</tr>
		<?
  }
  ?>
  
    
	<tr>
				 <td colspan="2"><div align="right"></div></td>
		  		 <td class="style9_detail" colspan="14">No.PO:<?=$rs['no_po'];?></td>
		  		  		  
	</tr>
	<tr>
			<td colspan="2"><div align="right"></div></td>	 
			<td class="style9_detail" colspan="14">Tgl.PO:<?=$rs['tglsales'];?></td>	  
	</tr>

	<tr>
			<td ></td>	
				 
			<td colspan="16"><hr></td>
			
	
	<tr>
			<td colspan="2"><div align="right"></div></td>	 
			<td class="style12_footer" colspan="10">GRAND TOTAL</td>
			<td class="style12_footer" colspan="1"><div align="center"><?=number_format($totalqty);?></div></td>
			<td class="style12_footer" colspan="3"><div align="right"><?=number_format($total);?></div></td>	  
	</tr>
	
  </table>  
   
  
  
</form>


<script language="javascript">
window.print();
</script>
  
