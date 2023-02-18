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
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
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
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
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
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	border-bottom: 1px solid black;
	/* background : #E0DAD8 ; */
	/* background : grey ; */
    padding: 3px;	
}
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Tahoma;
}
@page {
        size: A4;
        margin: 15px;
    }
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
	$sql_jual="SELECT do.*,(so.ref_kode) as no_po,DATE_FORMAT(so.tgl_trans,'%d/%m/%Y')AS tglsales,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman,DATE_FORMAT(do.tgl_trans,'%d/%m/%Y')as tgl FROM `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id)
    where do.id_trans='".$id_faktur."'";
	$sql = mysql_query($sql_jual);
	//var_dump($sql_jual); die;
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="1"   align="center" cellpadding="0" cellspacing="0">
          <!-- 1 -->
		  <tr> 
		  <th rowspan="2" colspan="2" class="style11btl"><img class="resize" src="../../files/Camou.png"></th> 
		  <th colspan="4" class="style11btlr"><div align="left"><?=$rs['id_trans'];?>(<?=$rs['tgl'];?>)</div></th>
          
 		  </tr> 
		  <!-- 2 -->
		  
		  <tr> 
		  <th colspan="4" class="style11btlr"><div align="left"><?=$rs['customer'];?><br/><?=$rs['alamat'];?></div></th>
          
		 
		  </tr>
          	
		  <tr>
		  <th width="5%"  class="style11btl_title"><div align="center">No</div></th>
		  <th width="40%" class="style11btl_title"  colspan="1"><div align="left">Nama Produk</div></th>
		  
		  <th width="5%" class="style11btl_title"><div align="center">Qty</div></th>
		  <th width="10%" class="style11btl_title"><div align="center">Harga</div></th>
		  <th width="5%" class="style11btl_title"><div align="center">Disc(%)</div></th>
		  <th width="15%" class="style11btlr_title"><div align="center">SubTotal</div></th>
		  
		  
		  </tr>
          
		  <!-- Isi detail -->
	<?
		
	$sql_detail = "SELECT dt.id_product,dt.harga_satuan,dt.subtotal,dt.disc,dt.namabrg,SUM(dt.jumlah_kirim) AS totalqty
	,SUM(IF((dt.size) = '36', dt.jumlah_beli, 0) ) AS s36 
	,SUM(IF((dt.size) = '37', dt.jumlah_beli, 0) ) AS s37 
	,SUM(IF((dt.size) = '38', dt.jumlah_beli, 0) ) AS s38 
	,SUM(IF((dt.size) = '39', dt.jumlah_beli, 0) ) AS s39 
	,SUM(IF((dt.size) = '40', dt.jumlah_beli, 0) ) AS s40 
	,SUM(IF((dt.size) = '41', dt.jumlah_beli, 0) ) AS s41 
	,SUM(IF((dt.size) = '42', dt.jumlah_beli, 0) ) AS s42 
	,SUM(IF((dt.size) = '43', dt.jumlah_beli, 0) ) AS s43 
	,SUM(IF((dt.size) = '44', dt.jumlah_beli, 0) ) AS s44 
	FROM b2bdo_detail dt WHERE dt.id_trans ='".$id_faktur."' GROUP BY dt.id_product ASC";    
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
      //$harga_satuan=($rs2['harga_satuan']-$rs2['disc'])*(1-$rs['discdp']);
	  $subtotal=$rs2['totalqty'] * $rs2['harga_satuan'];
	  $total+=$subtotal;	  
	  $totalqty+=$rs2['totalqty'];	  
	  ?>	  
	  <tr>		  
      <?
		echo"<td class='style11btlr_detail'><div align='right'>".$nomer."</div></td>";
		echo"<td class='style11btl_detail'><div align='left'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style11btl_detail'><div align='right'>".$rs2['totalqty']."</div></td>";
		echo"<td class='style11btl_detail'><div align='center'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style11btl_detail'><div align='center'>".number_format($rs2['disc'])."</div></td>";
		echo"<td class='style11btlr_detail'><div align='right'>".number_format($subtotal)."</div></td>";
		?>
		</tr>
		<?
  }
  ?>
  
    <tr>
		  <td width="50%" class="style11btl"  colspan="2">Jumlah Dus:<?=$rs['note'];?></td>
		  <td class="style11btlr_total"><div align="right"><?=number_format($totalqty);?>
		  <td class="style11btlr_total" colspan="3"><div align="right"><?=number_format($total);?></div></td>
	</tr>
	<tr>
		  <td width="50%" class="style11btlr"  colspan="6">No.PO:<?=$rs['no_po'];?></td>
		  		  		  
	</tr>
	<tr>
		  <td width="50%" class="style11btlr"  colspan="6">Tgl.PO:<?=$rs['tglsales'];?></td>	  
	</tr>
	
  </table>  
  
  
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  
