<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>

<style type="text/css">
.style9 {
font-size: 7pt; 
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
	font-family: Tahoma;
	font-style:italic;
}
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
@page {
        size: A4;
        margin: 0px;
    }
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	//include("koneksi/koneksi.php");
    $id_faktur=$_GET['id_trans'];
    //$id_faktur=TSO18020021;
	
	$sql = mysql_query("SELECT * FROM trjual a 
	left join tblpelanggan b on b.id = a.id_customer
    where a.id_trans='".$id_faktur."'");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  
  <table width="100%" border="0" align="center">
    <tr>
      <td width="100%" class="style9"><div align="center" class="style99"><strong>FAKTUR PENJUALAN</strong></div></td>     
    </tr>
    <tr>
      <td colspan="7" class="style9"><hr /></td>
    </tr>
	<table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="19%" class="style9">PKP</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%">CV.ASIANTEX</td>
            <td class="style9" width="19%">Kepada Yth.</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?=$rs['namaperusahaan'];?></td>
          </tr>
          <tr>
            <td width="19%" class="style9">NPWP</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%">&nbsp;</td>
            <td class="style9" width="19%">Alamat</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?=$rs['alamat'];?></td>   
		  </tr>
          <tr>
		  <td width="19%" class="style9">&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
            <td class="style9" width="30%">&nbsp;</td>
            <td class="style9" width="19%">NIK</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"></td>
		  </tr>
          <tr>
		    <td width="14%" class="style9">Tanggal</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="35%"><?=date_format(date_create($rs['tgl_trans']),'d-m-Y');?></td>
            <td width="13%" class="style9">No.Invoice</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="35%"><?=$id_faktur?></td>           			
          </tr>		  
  </table>  
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="19%" class="style21"></td>
    </tr>
  </table>
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="20%" class="style9b"><div align="center">Jenis Barang</div></td>
 	  <td width="40%" class="style9b"><div align="left">Nama Barang</div></td>
      <td width="9%" class="style9b"><div align="center">Qty</div></td>
      <td width="9%" class="style9b"><div align="right">Harga @</div></td>
      <td width="20%" class="style9b"><div align="right">Subtotal</div></td>
    </tr>
    <tr>
      <td colspan="6" class="style16"><hr /></td>
    </tr>
    <?
		
	//$sq2 = mysql_query("select a.id_detail,a.id_barang, b.nm_barang, a.qty, a.harga, (a.qty * a.harga) as subtotal from trjual_detail a, barang b where a.id_barang=b.id_barang and a.id_trans ='".$_GET['id_trans']."'");
	
	//$id_faktur=TSO18020021;
	$sq2 = mysql_query("select a.nama_barang,a.id_jenis, b.nm_jenis, a.kuantum, a.harga, (a.kuantum * a.harga) as subtotal from trjual_print a, jenis_barang b where a.id_jenis=b.id_jenis and a.id_trans ='".$id_faktur."'");
	
	//$sq2 = mysql_query("SELECT GROUP_CONCAT(kode_brg)as kode_group,harga,sum(qty) as qty_sub,(harga * sum(qty)) as subtotal FROM trjual_detail where id_trans='TSO18020013' GROUP BY harga");
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;

  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><div align="center">
	  <?=$rs2['nm_jenis'];?>
	  </div>
	  </td>
      <td class="style9"><?=$rs2['nama_barang'];?></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['kuantum'],0);?>
      &nbsp;&nbsp;pcs</div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['harga'],0);?>
      </div></td>
      <td class="style9"><div align="right">
          <?=number_format($rs2['subtotal'],0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['subtotal'];
	$ongkir=$rs['biaya'];
	$tunai_vw=$rs['tunai'];
	$transfer_vw=$rs['transfer'];
	$totqty+=$rs2['kuantum'];
  }
  ?>
    <tr>
      <td height="2" colspan="6" class="style9"><hr /></td>
    </tr>
	
  </table>
   
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%">&nbsp;</td>
      <td width="10%"><div align="right" class="style9">Total Qty</div></td>
      <td width="7%">
	  <div id="jmljasa">
        <div align="right" class="style9">
          <?  echo number_format($totqty,0);?>
        </div>
      </div>
	  </td>
	  
      <td width="3%">&nbsp;</td>    
      <td width="20%"><!--<div align="right" class="style9"> Total Faktur</div>--></td>
      <td width="2%">&nbsp;</td>
	  <td width="8%"><!--<div id="jmljasa">
        <div align="right" class="style9">
          <? // echo number_format($total2,0);?>
        </div>
        </div>
		-->
	  </td>
	  
    </tr>
  </table> 
 
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="65%" height="27"><table width="99%" border="0">
          <tr>
            <td width="14%" class="style19">Terbilang </td>
            <td width="2%"><div align="center">:</div></td>
            <td width="84%"><div class="style11" id="terbilang"></div></td>
          </tr>
      </table></td>
      <td width="23%" class="style9"><div align="right"><strong>Grand Total</strong></div></td>
	  <td width="2%">&nbsp;</td>
      <td width="10%" class="style10"><div id="total" class="style21"><? echo number_format($total2+$ongkir,0);?></div> </td>
    </tr>
	<tr>
	<td width="75%">&nbsp;</td>
	<td width="14" ><div align="right" class="style9">Tunai</div></td>
	<td width="2%" class="style9">:</td>
	<td width="9%"><div align="right" class="style9">
          <?  echo number_format($tunai_vw,0);?>
        </div>
	<td>
	</tr>
    <tr>
	<td width="75%">&nbsp;</td>
	<td width="14" ><div align="right" class="style9">Transfer</div></td>
	<td width="2%" class="style9">:</td>
	<td width="9%"><div align="right" class="style9">
          <?  echo number_format($transfer_vw,0);?>
        </div>
	<td>
	</tr>
    <tr>
      <td height="2" colspan="3"><hr /></td>
    </tr>
    
    <tr>
      <td height="27" colspan="3"><p>
          <span class="style20">Lembar 1 : UNTUK PEMBELI</span><br />
          <span class="style20">Lembar 2 : UNTUK PENJUAL</span></p>
      </td>
    </tr>
    <tr>
      <td height="2" colspan="3"><hr /></td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="159"><div align="center" class="style9">Penerima,</div></td>
      <td width="600">&nbsp;</td>
      <td colspan="3"><div align="center" class="style9">Terima Kasih,</div></td>
    </tr>
    <tr>
      <td height="10">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="5">&nbsp;</td>
      <td width="167" valign="top">  </td>
      <td width="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="10"><div align="center">(.....................................)</div></td>
      <td><p align="center" class="style20">&nbsp;</p>
      </td>
      <td>(</td>
      <td><div align="center">admin toko</div></td>
      <td>)</td>
    </tr>
  </table>
  <div align="center"></div>
</form>

<script language="javascript">
var total=getNumber(document.getElementById("total").innerHTML);
document.getElementById("terbilang").innerHTML = terbilang(total);

window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
