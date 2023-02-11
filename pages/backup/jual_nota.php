<script src="../assets/js/jsbilangan.js" type="text/javascript"></script>

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
	include("koneksi/koneksi.php");
    $id_faktur=$_GET['id_trans'];
	$sql = mysql_query("SELECT * FROM trjual a 
	left join tblpelanggan b on b.id = a.id_customer
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
          <?=$id_faktur?>
      </div></td>
      <td width="30%" class="style9"><div align="center" class="style99"><strong>FAKTUR PENJUALAN</strong></div></td>
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
      <td width="13%" class="style21">Kode Barang</td>
      <td width="39%" class="style21"><div align="left">Nama Barang</div></td>
      <td width="9%" class="style21"><div align="right">Harga</div></td>
      <td width="9%" class="style21"><div align="right">Qty</div></td>
      <td width="20%" class="style21"><div align="right">Jumlah(Rp)</div></td>
    </tr>
    <tr>
      <td colspan="6" class="style16"><hr /></td>
    </tr>
    <?
		
	$sq2 = mysql_query("select a.id_detail,a.id_barang, b.nm_barang, a.qty, a.harga, (a.qty * a.harga) as subtotal from trjual_detail a, barang b where a.id_barang=b.id_barang and a.id_trans ='".$_GET['id_trans']."'");
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2)){$nomer++;

  ?>
    <tr>
      <td class="style9"><span class="style16">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><?=$rs2['id_barang'];?></td>
      <td class="style9"><?=$rs2['nm_barang'];?></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['harga'],0);?>
      </div></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['qty'],0);?>
      </div></td>
      <td class="style9"><div align="right">
          <?=number_format($rs2['subtotal'],0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['subtotal'];
  }
  ?>
    <tr>
      <td height="2" colspan="6" class="style9"><hr /></td>
      </tr>
  </table>
   
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="67%">&nbsp;</td>
      <td width="22%"><div align="right" class="style9"> Total Rp</div></td>
      <td width="11%"><div id="jmljasa">
        <div align="right" class="style9">
          <?  echo number_format($total2,0);?>
          </span></div>
      </div></td>
    </tr>
  </table> 
 
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="75%" height="27"><table width="99%" border="0">
          <tr>
            <td width="14%" class="style19">Terbilang </td>
            <td width="2%"><div align="center">:</div></td>
            <td width="84%"><div class="style11" id="terbilang"></div></td>
          </tr>
      </table></td>
      <td width="15%" class="style9"><div align="right"><strong>Grand Total</strong></div></td>
      <td width="10%" class="style10"><div id="total" class="style21"><? echo number_format($total2+$total,0);?></div> </td>
    </tr>
    <tr>
      <td height="2" colspan="3"><hr /></td>
    </tr>
    
    <tr>
      <td height="27" colspan="3"><p><span class="style20">Ketentuan </span> :<br />
          <span class="style20">1. Pembayarandengan giro/cek dianggap sah setelah dapat dicairkan/dikliringkan</span>. <br />
          <span class="style20">2. Kami tidak bertanggung jawab atas barang yang tidak diambil dalam waktu 2 hari sejak tanggal pelunasan.</span><br />
           <span class="style20">3. Barang yang  sudah dibeli tidak dapat ditukar/dikembalikan.</span></p>
       <span class="style20">Pembayaran ditransfer ke:</span>
        <table width="647" border="0" align="left" cellpadding="0" cellspacing="0" id="tbl_dtl2">
          <tr>
            <td colspan="7" class="style6"></td>
          </tr>
          <tr>
            <td width="115" class="hd_tbl" id="td2"><div align="left"><span class="style20">Nama Bank</span></div></td>
            <td width="145" class="hd_tbl" id="td2"><span class="style20">Cabang</font>
                </div></td>
            <td width="134" class="hd_tbl" id="td2"><div align="left"><span class="style20">No Rekening</span></div>
                </div></td>
            <td width="235" class="hd_tbl" id="td2"><div align="left"><span class="style20">Atas Nama</span></div>
                </div></td>
          </tr>
          <tr>
            <td colspan="7" class="style6"></td>
          </tr>
   
          <tr>
            <td width="115" class="hd_tbl" id="td2"><div align="left">
              <span class="style20">  </span>
            </div></td>
            <td width="145" class="hd_tbl" id="td2"><div align="left">
              <span class="style20">  </span>
            </div></td>
            <td width="134" class="hd_tbl" id="td2"><div align="left">
                <span class="style20"></span>
            </div></td>
            <td width="235" class="hd_tbl" id="td2"><div align="left">
              <span class="style20"> </span>
            </div></td>
          </tr>
        
          <tr>
            <td colspan="7" class="style6"></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td height="2" colspan="3"><hr /></td>
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
