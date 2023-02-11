<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>

<style type="text/css">
<!--
.style9 {font-size: 10pt; font-family:Arial}
.style99 {font-size: 13pt; font-family:Arial}
.style10 {font-size: 10pt; font-family:Arial; text-align:right}
.style19 {font-size: 10pt; font-weight: bold; font-family:Arial; font-style:italic}
.style11 {
	color: #000000;
	font-size: 8pt;
	font-weight: normal;
	font-family: Arial;
	font-style:italic;
}
.style20 {font-size: 8pt; font-family:Arial}
.style16 {font-size: 9pt; font-family:Arial}
.style21 {color: #000000;
	font-size: 10pt;
	font-weight: bold;
	font-family: Arial;
}
.style18 {color: #000000;
	font-size: 9pt;
	font-weight: normal;
	font-family: Arial;
}
.style6 {color: #000000;
	font-size: 9pt;
	font-weight: bold;
	font-family: Arial;
}
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Arial;
}
-->
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
    $id_faktur=$_GET['id_trans'];
	$sql = mysql_query("SELECT * FROM trbeli a 
	left join tblsupplier b on b.id = a.id_supplier
    where a.id_trans='".$id_faktur."'

");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  
  <table width="100%" border="0" align="center">
    <tr>
      <td width="15%" class="style9">No </td>
      <td width="2%" class="style9"><div align="center">:</div></td>
      <td width="18%" class="style9"><div align="left">
          <?=$rs['kode'];?>
      </div></td>
      <td width="30%" class="style9"><div align="center" class="style99"><strong>INVOICE</strong></div></td>
      <td width="11%" class="style9">Tanggal </td>
      <td width="2%" class="style9"><div align="center">:</div></td>
      <td width="22%" class="style9"><?=date_format(date_create($rs['tgl_trans']),'d-m-Y');?></td>
    </tr>
    <tr>
      <td colspan="7" class="style9"><hr /></td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
            <td class="style9">Nama</td>
            <td class="style9"><div align="center">:</div></td>
            <td class="style9"><?=$rs['namaperusahaan'];?></td>
            <td width="2%" class="style9">&nbsp;</td>
            <td width="13%" class="style9">No.Telp</td>
            <td width="2%" class="style9"><div align="center">:</div></td>
            <td width="29%" class="style21"><?=$rs['telp1'];?></td>
          </tr>
          <tr>
            <td class="style9">Alamat</td>
            <td class="style9"><div align="center">:</div></td>
            <td class="style9"><?=$rs['alamat'];?></td>
            <td class="style9">&nbsp;</td>
            <td width="13%" class="style9">NIK/NPWP</td>
            <td width="2%" class="style9"><div align="center">:</div></td>
            <td width="29%" class="style21"><?=$rs['keterangan'];?></td>
          </tr>
          
          <tr>
            <td colspan="7" class="style9"><hr /></td>
          </tr>
    
  </table>
   

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <div align="right">
            <tr>
              <td width="95%" class="style21">DETAIL BARANG</td>
              <td width="5%">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class="style21"><hr /></td>
            </tr>
     </div>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="19%" class="style21"></td>
    </tr>
  </table>
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style21"><div align="left">No.</div></td>
      <td width="8%" class="style21">No.Brg</td>
      <td width="25%" class="style21"><div align="left">Nama Barang</div></td>
      <td width="15%" class="style21"><div align="left">Jenis Barang</div></td>
      <td width="10%" class="style21"><div align="right">Qty(pcs)
	  <td width="10%" class="style21"><div align="right">Qty(yard)
	  <td width="10%" class="style21"><div align="right">Harga</div></td>
	  <td width="20%" class="style21"><div align="right">Subtotal(yard)</div></td>
      </div></td>
	  </div></td>
    </tr>
    <tr>
      <td colspan="8" class="style16"><hr /></td>
    </tr>
    <?
	$sql_data = "select a.id_detail,a.id_barang,j.hrg_yard, b.nm_barang,j.nm_jenis, a.qty,b.kode_brg,a.harga,(a.qty * a.harga) as subtotal from trbeli_detail a left join barang b on b.id_barang = a.id_barang left join jenis_barang j on j.id = a.id_jenis where a.id_trans ='".$_GET['id_trans']."' order by a.qty asc,b.kode_brg + 0 asc";	
	//var_dump($sql_data);die;
	$sq2=mysql_query($sql_data);
	$i=1;
	$nomer=0;
	$konversi_yard=30;
	while($rs2=mysql_fetch_array($sq2)){$nomer++;
    
  ?>
    <tr>
      <td class="style9"><span class="style16">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><div align="center"><?=$rs2['kode_brg'];?></div>
	  </td>
      <td class="style9"><div align="left"><?=$rs2['nm_barang'];?></div></td>     
	  
	  <td class="style9"><div align="left">
        <?=$rs2['nm_jenis']; ?>
      </div></td>
      
	  <td class="style9"><div align="right">
          <?=number_format($rs2['qty'],0);?>
      </div></td>
	  
	  <td class="style9"><div align="right">
          <?=number_format($rs2['qty']*$konversi_yard,0);?>
      </div></td>
	  
	  <td class="style9"><div align="right">
          <?=number_format($rs2['hrg_yard'],0);?>
      </div></td>
	  
	  <td class="style9"><div align="right">
          <?=number_format(($rs2['hrg_yard']*$rs2['qty']*$konversi_yard),0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['qty'];
	$totalyard+=($rs2['qty']*30);
	$grandtotal+=($rs2['hrg_yard']*$rs2['qty']*$konversi_yard);
  }
  ?>
    <tr>
      <td height="2" colspan="8" class="style9"><hr /></td>
      </tr>
  </table>
  <!-- 
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="67%">&nbsp;</td>
      <td width="22%"><div align="right" class="style9"> Total (pcs)</div></td>
      <td width="11%"><div id="jmljasa">
        <div align="right" class="style9">
          <?  //echo number_format($total2,0);?>
          </span></div>
      </div></td>
    </tr>
  </table> 
 -->
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" height="27"></td>
      <td width="20%" class="style9"><div align="right"><strong> TotalQty</strong></div></td>
      <td width="5%" class="style10"><div id="totalpcs" align="right" class="style21"><? echo number_format($total2,0);?></div> </td>
      <td width="10%" class="style10"><div id="totalyard" align="right" class="style21"><? echo number_format($totalyard,0);?></div> </td>
      <td width="10%" class="style9"><div align="right"><strong>Total</strong></div></td>
      <td width="10%" class="style10"><div id="totalmurni" align="right" class="style21"><? echo number_format($grandtotal,0);?></div> </td>
    </tr>
    <tr>
            <td colspan="5" width="90%"><div align="right"><strong>Grand Total(+ppn 10%)</strong></div></td>
			<td width="10%" class="style10"><div id="total" align="right" class="style21"><? echo number_format(1.11*$grandtotal,0);?></div></td>
    </tr>
	<tr>
            <td colspan="6" width="95%"><div class="style11" id="terbilang" align="right"></div></td>
    </tr>
    <tr>
      <td height="2" colspan="7"><hr /></td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="159"><div align="center" class="style9">Penerima,</div></td>
      <td width="600">&nbsp;</td>
      <td colspan="3"><div align="center" class="style9">Hormat Kami,</div></td>
    </tr>
    <tr>
      <td height="56">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="5">&nbsp;</td>
      <td width="167" valign="top">  </td>
      <td width="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="34"><div align="center">(.....................................)</div></td>
      <td><p align="center" class="style20">&nbsp;</p>
      </td>
      <td>(</td>
      <td><div align="center"></div></td>
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
