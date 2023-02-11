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

.style11btl_camou {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	padding: 0px;
}

.style11btlr_kirim {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-left: 1px solid black;
	border-right: 1px solid black;
}

.style11btblr_note {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-left: 1px solid black;
	border-right: 1px solid black;
	
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
	padding: 3px;
}
.style11btr {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-right: 1px solid black;
	padding: 3px;
}
.style11btr_barcode {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	font-weight: normal;
	border-top: 1px solid black;
	border-right: 1px solid black;
	padding: 1px;
}

.style11btrb {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	padding: 1px;
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
.style_barcode {	
    color: #000000;
	font-weight: normal;
    font-family: 'Bar-Code 39';
	font-size: 30px;
	padding: 1px;
}


.style11btlr_alamat {	color: #000000;
	font-size: 8pt;	
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
	$sql_jual="SELECT a.*,b.nama AS dropshipper,c.nama as exp,c.kode_warna,c.logo,d.kecamatan,d.kabupaten,d.provinsi,i.id as id_kirim FROM olnso a 
	left join mst_dropshipper b on a.id_dropshipper = b.id
	left join mst_expedition c on a.id_expedition = c.id
    left join mst_address d on a.id_address = d.id
    left join olnso_id i on a.id_trans = i.id_trans
    where a.id_trans='".$id_faktur."'";
	$sql = mysql_query($sql_jual);
	//var_dump($sql_jual); die;
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0"   align="center" cellpadding="0" cellspacing="0">
          <!-- 1 -->
		  
		  <tr> 
		  <th rowspan="2" colspan="2" class="style11btl_camou"><img class="resize" src="../../files/Camou.png"></th> 
		  <th colspan="6" class="style11btlr"><div align="left">Pengirim : <?=$rs['dropshipper'];?></div></th>
          <!--<td >&nbsp;</td>-->		  
          <th rowspan="1" colspan="2" class="style11btr"><div align="center">(<?=$rs['id_kirim'];?>)<?=$rs['id_trans'];?>/<?=$rs['ref_kode'];?></div></th> 
          </tr> 
		  <!-- 2 -->
		  
		  <tr>
           		  
		  <th colspan="6" class="style11btlr"><div align="left">Penerima :<?=$rs['nama'];?>/<?=$rs['telp'];?><br><? echo "".$rs['alamat']." ".$rs['kecamatan']." ".$rs['kabupaten']." ".$rs['provinsi'];?></div></th>
          <th colspan="2" class="style11btr_barcode"><div align="center" class="style_barcode"><?=$rs['id_trans'];?></div></th> 
          
		  </tr>
          		  
		  <!-- 3 -->
		  <!-- JUDUL -->
		  
		  <tr>
		  <th width="10%"  class="style11btl_title"><div align="center">No</div></th>
		  <th width="70%" class="style11btl_title"  colspan="7"><div align="left">Nama Produk</div></th>
		  <th width="10%" colspan="1" class="style11btl_title"><div align="center">Size</div></td>
		  <th width="10%" colspan="1" class="style11btlr_title"><div align="center">Qty</div></th>
		  </tr>
          
		  <!-- Isi detail -->
	<?
		
	$sql_detail = "select d.namabrg,d.jumlah_beli, d.harga_satuan,d.size, d.subtotal from olnsodetail d where d.id_trans ='".$id_faktur."'";
	//var_dump($sql_detail); die;
	$sq2 = mysql_query($sql_detail);
	
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;
      
  ?>	  
		  <tr>
		  <td width="10%"  class="style11btl_detail"><div align="center"><?=$nomer;?></div></td>
		  <td width="70%" colspan="7" class="style11btl_detail" ><div align="left"><?=$rs2['namabrg'];?></div></td>
		  <td width="10%" class="style11btl_detail"><div align="center"><?=$rs2['size'];?></div></td>
		  <td width="10%" class="style11btlr_detail"><div align="center"><?=$rs2['jumlah_beli'];?></div></td>
		  <!--<td>&nbsp;</td>-->
		  <!--<td>&nbsp;</td>-->
		  </tr>
		  
  <?
  }
  ?>
  
    <tr>
		  <td width="50%" class="style11btblr_note" rowspan="2" colspan="6"><div align="left">&nbsp;&nbsp;Note &nbsp;=</div></td>
		  <td width="10%" class="style11btr" colspan="2" rowspan="1" ><div align="center"><img class="resize" src="../../files/expedition/<?=$rs['logo'];?>.png"></div></td>
          		 
		  <td rowspan="1" class="style11btl_kirim" colspan="2" style="color: <?=$rs['kode_warna'];?>;font-weight: bold; border-style: solid; border-color: <?=$rs['kode_warna'];?>;"><div class="style_barcode" align="center" ><?=$rs['exp_code'];?></div></td>
          		  
          		  
	</tr>
	<tr>
		<td colspan="2" class="style11btrb" ><div align="left">Ongkir=<?=$rs['exp_fee'];?></div></td>	
		<td colspan="2" class="style11btb" style="color: <?=$rs['kode_warna'];?>;font-weight: bold; border-style: solid; border-color: <?=$rs['kode_warna'];?>;"><div align="center"><?=$rs['exp_code'];?></div></td>	
						
    </tr>
	
	
	
  </table>  
  
  
  
  <div align="center"></div>
</form>

<script language="javascript">
window.print();
</script>
  
