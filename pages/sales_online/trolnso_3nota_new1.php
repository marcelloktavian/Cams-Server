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
	font-size: 8pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	
}
.style11btl_detail {	color: #000000;
	font-size: 8pt;	
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
	padding: 1px;
}
.style11btlr_detail {	color: #000000;
	font-size: 8pt;	
	font-family: Tahoma;
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
  max-width:65%;
  max-height:65%;
  align :left;
}	

@media print {
  .page_break {page-break-before: always;}
 
}
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	//include("koneksi/koneksi.php");
	$ids=$_GET['ids'];
	$ids=substr($ids,0,-1);
    $id_faktur1=$_GET['id_trans1'];
    //$id_faktur=TSO18020021;
	$sql_m1="SELECT a.*,b.nama AS dropshipper,c.nama as exp,c.kode_warna,d.kecamatan,d.kabupaten,d.provinsi FROM olnso a 
	left join mst_dropshipper b on a.id_dropshipper = b.id
	left join mst_expedition c on a.id_expedition = c.id
	left join mst_address d on a.id_address = d.id
    where a.id_trans IN (".$ids.")";
	$sql1 = mysql_query($sql_m1);
	//var_dump($sql_m1); die;
//	$master = mysql_fetch_array($sql1);
	$limit_row=8;
	//var_dump($master);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
<!-- table 1---->
 <?php 
     $faktur_no = 1;
 while($m1=mysql_fetch_array($sql1)) {
	  //var_dump($m1);
	  
	  ?>
  <table width="100%" border="0"   align="center" cellpadding="0" cellspacing="0">
          <!-- 1 -->
		  <tr> 
		  <th rowspan="2" colspan="2" class="style11btl"><img class="resize" src="../../files/Camou.png"></th> 
		  <th colspan="4" class="style11btlr"><div align="left"><?=$m1['id_trans'];?></div></th>
          <td>&nbsp;</td>		  
		  <th colspan="4" class="style11btlr"><div align="left"><?=$m1['id_trans'];?></div></th> 
 		  </tr> 
		  <!-- 2 -->
		  <tr> 
		  <th colspan="4" class="style11btlr"><div align="left">Pengirim : <?=$m1['dropshipper'];?></div></th>
          <td >&nbsp;</td>		  
		  <th colspan="4" class="style11btlr"><div align="left">Pengirim : <?=$m1['dropshipper'];?></div></th> 
		  </tr>
		  <!-- 3 -->
		  <tr>
          <!--		  
		  <th class="style11btl_title"><?=$m1['kode'];?></th> 
		  -->
		  <th class="style11btl" colspan="2"> <div align="left">Alamat pengiriman</div></th> 
		  
		  <th colspan="4" class="style11btlr"><div align="left">Penerima : <?=$m1['nama']?></div></th>
		  
          <td>&nbsp;</td>		  
		  <th colspan="4" rowspan="2" class="style11btlr"><div align="left">Penerima : <?=$m1['nama'];?></div></th> 
		  </tr> 
		   <!-- 4 -->
           <tr>
		   <td  colspan="6" class="style11btlr_alamat" height="42.5" ><div align="left"><? echo "".$m1['alamat']." ".$m1['kecamatan']." ".$m1['kabupaten']." ".$m1['provinsi'];?></div></td>  
		   <td>&nbsp;</td>
		  </tr>
		  
		  <tr>
		  <th width="5%"  class="style11btl_title"><div align="center">No</div></th>
		  <th width="50%" class="style11btl_title"  colspan="2"><div align="left">Nama Produk</div></th>
		  <th width="5%" colspan="2" class="style11btl_title"><div align="center">Size</div></td>
		  <th width="7%" class="style11btlr_title"><div align="center">Qty</div></th>
		  <td>&nbsp;</td>
		  <th width="3%" class="style11btl_title"><div align="center">No</div></th>
          <th width="23%" class="style11btl_title"><div align="left">Nama Produk</div></th>
          <th width="3%" class="style11btl_title"><div align="center">Size</div></th>
          <th width="3%" class="style11btlr_title"><div align="center">Qty</div></th>
		  </tr>
          
		  <!-- Isi detail -->
	<?
		
	$sql_detail1 = "select d.namabrg,d.jumlah_beli, d.harga_satuan,d.size, d.subtotal from olnsodetail d where d.id_trans ='".$m1['id_trans']."'";
	//var_dump($sql_detail); die;
	$sq11 = mysql_query($sql_detail1);
	$row = mysql_num_rows($sq11);
	//var_dump($row); die;
	$i=1;
	
	if ($row >= $limit_row){
	$more = "....".($row -$limit_row)." more data";
	$more_small = "..";
	}
    else $more = "";
		
	  while (($rs=mysql_fetch_array($sq11))||($i<=$limit_row))  { 
		if ( ($i>$limit_row)) break;
     ?>	  
		  <tr>
		  <td width="5%"  class="style11btl_detail"><div align="center"><?=$i?></div></td>
		  <td width="50%" class="style11btl_detail"  colspan="2"><div align="left"><?=$rs['namabrg'].($i==$limit_row?$more:'');?></div></td>
		  <td width="5%" colspan="2" class="style11btl_detail"><div align="center"><?=$rs['size'];?></div></td>
		  <td width="10%" class="style11btlr_detail"><div align="center"><?=$rs['jumlah_beli'];?></div></td>
		  <td>&nbsp;</td>
		  <td width="3%" class="style11btl_detail"><div align="center"><?=$i?></div></td>
          <td width="20%" class="style11btl_detail"><div align="left"><?=$rs['namabrg'];?></div></td>
          <td width="3%" class="style11btl_detail"><div align="center"><?=$rs['size'];?></div></td>
          <td width="3%" class="style11btlr_detail"><div align="center"><?=$rs['jumlah_beli'];?></div></td>
		  </tr>
	  <? $i++;
	  }?>   
    <tr>
		  <td width="50%" class="style11btl_kirim"  colspan="3"><div align="left" style="color: <?=$rs['kode_warna'];?>;font-weight: bold; border-style: solid; border-color: <?=$rs['kode_warna'];?>;">&nbsp;&nbsp;Jasa Pengiriman :<?=$rs['exp'];?></div></td>
		  <!--
		  <td class="style11bt">&nbsp;</td>
		  -->
		  <td class="style11btr" colspan="3"><div align="left">Ongkir :<?=number_format($m1['exp_fee']);?></div></td>
		  <!--
		  <td class="style11btr">&nbsp;</td>
		  -->
		  <td rowspan ="2">&nbsp;</td>
		  <td rowspan ="2" class="style11btl">&nbsp;</td>
		  <td rowspan ="2" class="style11bt">&nbsp;</td>
		  <td rowspan ="2" class="style11bt">&nbsp;</td>
		  <td rowspan ="2" class="style11btr">&nbsp;</td>
	</tr>
	<tr>
		  <td width="50%" class="style11btl"  colspan="3"><div align="left" >&nbsp;&nbsp;Kode Pengiriman :<?=$m1['exp_code'];?></div></td>
		  <td class="style11bt">&nbsp;</td>
		  <td class="style11bt">&nbsp;</td>
		  <td class="style11btr">&nbsp;</td>
	</tr>
	<tr>
		<td class="style11bt"  colspan="6"></td>
		<td>&nbsp;</td>
		<td class="style11bt"  colspan="4"></td>
    </tr>
	
  </table>   
 <?php 
    if (($faktur_no % 3)==0) {
		//echo '<tr>&nbsp;</tr>';
		echo '<span class="page_break"></span>';
		
	}
   $faktur_no++;
 } ?>
   
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>