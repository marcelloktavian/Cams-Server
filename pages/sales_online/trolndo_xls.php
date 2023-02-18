<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=do.xls");
?>
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
	/*
	border-bottom: 1px dashed black;
	border-right: 1px solid black;
	*/
	padding: 3px;
}
.style_detail_left {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	/*
	border-bottom: 1px dashed black;
	border-left: 1px solid black;
	border-right: 1px solid black;
	*/
	padding: 3px;
}
@page {
        size: A4;
        margin: 15px;
    }
</style>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
   
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
    
      <tr>
      <th width="5%" class="style_title_left"><div align="center">Tgl.Trans</div></td>
      <th width="5%" class="style_title"><div align="center">No.Internal</div></td>
      <th width="5%" class="style_title"><div align="center">No.Co.ID</div></td>
      <th width="5%" class="style_title"><div align="center">Status</div></td>
      <th width="5%" class="style_title"><div align="center">Nama Toko</div></td>
      <th width="5%" class="style_title"><div align="center">Produk</div></td>
      <th width="5%" class="style_title"><div align="center">Size</div></td>
      <th width="5%" class="style_title"><div align="center">Qty</div></td>
      <th width="5%" class="style_title"><div align="center">Nama Penerima</div></td>
      <th width="5%" class="style_title"><div align="center">No.Telp</div></td>
 	  <th width="5%" class="style_title"><div align="center">Kode Pos</div></td>
 	  <th width="10%" class="style_title"><div align="center">Alamat</div></td>
 	  <th width="5%" class="style_title"><div align="center">Kecamatan</div></td>
 	  <th width="5%" class="style_title"><div align="center">Kabupaten</div></td>
 	  <th width="5%" class="style_title"><div align="center">Provinsi</div></td>
 	  <th width="5%" class="style_title"><div align="center">Jasa Pengiriman</div></td>
 	  <th width="5%" class="style_title"><div align="center">Kode Pengiriman</div></td>
 	  <th width="5%" class="style_title"><div align="center">Ongkir</div></td>
      
    </tr>
    <?
	include("../../include/koneksi.php");
	$ids=$_GET['ids'];
	$ids=substr($ids,0,-1);   
	$where = "";
	$where = " where m.id_trans IN (".$ids.")";
	
	$sql_detail = "SELECT date_format(m.tgl_trans,'%d-%m-%Y') as tgl_trans,dt.id_trans, m.ref_kode AS id_web,m.exp_fee as ongkir,d.nama AS dropshipper,dt.namabrg,dt.jumlah_beli,dt.size,dt.harga_satuan,dt.subtotal,m.nama AS pembeli,m.telp,m.alamat,a.kecamatan,a.kabupaten,a.provinsi,a.kode_pos,e.nama AS expedition,m.state,m.exp_code,m.exp_fee,d.disc as discdp FROM olnsodetail dt
    INNER JOIN olnso m ON dt.id_trans = m.id_trans
    LEFT JOIN mst_address a ON m.id_address = a.id 
    LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
    LEFT JOIN mst_expedition e ON m.id_expedition = e.id ".$where." order by m.id_trans asc";
    //var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$grand_subtotal=0;
	$grand_qty=0;
	$totaldpp=0;
	$totalppn=0;
	$grand_ongkir=0;
	$nett_price=0;
	$nett_subtotal=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 	
     $nett_price=$rs2['harga_satuan'] *(1-$rs2['discdp']);
	 $nett_subtotal=$rs2['subtotal'] *(1-$rs2['discdp']);
  ?>
    <tr>
    <?
	  $nomer++;
	  //bikin master_ongkir
	  if ($kode!=$rs2['id_trans'])
	  {
	    $grand_ongkir+=$rs2['ongkir'];
		echo"<td class='style_detail_left'><div align='center'>".$rs2['tgl_trans']."</div></td>";
		echo"<td class='style_detail_left'><div align='center'>".$rs2['id_trans']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['id_web']."</div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['pembeli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['telp']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kode_pos']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['alamat']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kecamatan']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kabupaten']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['provinsi']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['expedition']."</div></td>";
	    echo"<td class='style_detail'><div align='center'>".$rs2['exp_code']."</div></td>";
	    echo"<td class='style_detail'><div align='center'>".$rs2['exp_fee']."</div></td>";
	
		$kode=$rs2['id_trans'];
	  }
      else if($kode=$rs2['id_trans'])
	  {
		echo"<td class='style_detail_left'><div align='center'></div></td>";
		echo"<td class='style_detail_left'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['id_web']."</div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['dropshipper']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['size']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['pembeli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['telp']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kode_pos']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['alamat']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kecamatan']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['kabupaten']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['provinsi']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['expedition']."</div></td>";
	    echo"<td class='style_detail'><div align='center'>".$rs2['exp_code']."</div></td>";
	    echo"<td class='style_detail'><div align='center'>".$rs2['exp_fee']."</div></td>";
	
	  }
	?>
    </tr>  
	<?
	$grand_qty+=$rs2['jumlah_beli'];
	//$grand_subtotal+=$rs2['subtotal'];
	$grand_subtotal+=$nett_subtotal;
	$totaldpp =($grand_subtotal/1.11);
	$totalppn= ($totaldpp*0.11);	
  }
  ?>
       
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
//window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
