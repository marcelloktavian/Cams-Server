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
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Tahoma;
}
@page {
        size: A4;
        margin: 15px;
    }
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	//include("koneksi/koneksi.php");
    $id_faktur=$_GET['id_trans'];
    //$id_faktur=TPB18030006;
	
	$id_faktur=$_GET['id_trans'];
	$sql = mysql_query("SELECT * FROM trbelipiutang a 
	left join tblsupplier b on a.id_ = a.id_customer
    where a.id_trans='".$id_faktur."'

");
	$rs = mysql_fetch_array($sql);
?>



<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="3" ><strong>KWITANSI</strong></td>
            <td class="style9" width="19%">No.Invoice</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?=$id_faktur?></td>
          </tr>
          <tr>
		    <td width="19%" class="style9">&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
            <td class="style9" width="30%">&nbsp;</td>
            <td class="style9" width="19%">Tanggal:</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?=date_format(date_create($rs['tgl_trans']),'d-m-Y');?></td>
            </td>
          </tr>
          <tr>
            <td width="19%" class="style9">PKP</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%">CV.ASIANTEX</td>
            <td class="style9" width="19%">Kepada Yth.</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?=$rs['namaperusahaan'];?></td>           
		  </tr>
          <tr>
		  <td width="19%" class="style9">NPWP</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%">01.1104.696.8.421.000</td>
            <td class="style9" width="19%">NIK</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9">&nbsp;</td>
		  </tr>
          <tr>
		    <td width="14%" class="style9">&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
            <td class="style9" width="35%">&nbsp;</td>
            <td width="13%" class="style9">Alamat</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?=$rs['alamat'];?></td>           			
          </tr>
          <tr>
            <td colspan="7" class="style9"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="5%" class="style9b"><div align="left">No.</div></td>
      <td width="25%" class="style9b"><div align="left">Keterangan</div></td>
 	  <td width="25%" class="style9b"><div align="left">No.Faktur</div></td>
 	  <td width="25%" class="style9b"><div align="left">Tgl.Faktur</div></td>
 	  <td width="20%" class="style9b"><div align="right">Jumlah</div></td>
    </tr>
    <tr>
      <td colspan="6" class="style16"><hr /></td>
    </tr>
    <?
	//$id_faktur=TSO18020021;	
	//DATE_FORMAT(b.tgl_trans,'%d-%m-%Y')
	$sq2 = mysql_query("select a.id_transjual,DATE_FORMAT(b.tgl_trans,'%d-%m-%Y') as tgl_trans,a.faktur,b.kode, a.bayar,a.bank,a.piutang,a.piutang_update,(a.piutang-a.piutang_update) as totalbyr from trbelipiutang_detail a left join trbeli b on a.id_transjual = b.id_trans where a.id_trans ='".$id_faktur."'");
	//--------------------end trpiutangdetail---------------------------------
	
	$i=1;
	$nomer=0;
	//$konversi=30; 
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;

  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><div align="left">Terima Pembayaran 
	  </div>
	  </td>
      <td class="style9"><div align="left">
          <?=$rs2['kode'];?>
      </div>
	  </td>
	  <td class="style9"><div align="left">
          <?=$rs2['tgl_trans'];?>
      </div>
	  </td>
	  <td class="style9"><div align="right">
          <?=number_format(($rs2['bayar']+$rs2['bank']) ,0);?>
      </div>
	  </td>
    </tr>  <?
	//total hutang faktur yang disave di trpiutang_detail;
	$bank_byr+=$rs2['bank'];
	$tunai_byr+=$rs2['bayar'];
	$jumlahbyr=($rs2['bayar'] + $rs2['bank']);
	$piutangdt+=$rs2['piutang'];
	$totalbyrdt+=$rs2['totalbyr'];
	$sisapiutangdt+=$rs2['piutang_update'];
	//$id_transjual=$rs2['id_transjual'];
   }
    ?>
	<?php
	//untuk hitung rata2 total faktur dan total bayar	
	/*
	$sqlgroup="select * from trpiutang where a.id_trans='".$id_faktur."'";
	
	//$sqlgroup = "select a.id_transjual,avg(a.faktur) as faktur, lsum(a.bayar) as totalbayar,(avg(a.faktur)-sum(a.bayar)) as sisa_hutang from trpiutang_detail a where a.id_transjual='".$id_transjual."' group by a.id_transjual ";
	
	mysql_query($sqlgroup);
	$rs3 = mysql_fetch_array($sqlgroup);
	echo"$sqlgroup";
	
	$hutang=$rs3['faktur'];
	//total bayar yang disum digroup by id_transjual di trpiutang_detail; 
	$angsuran=$rs3['totalbayar'];
	//datanya disave di field piutang trpiutang
	$sisa_hutang=$hutang-$angsuran;
	
	echo"<br/>hutang=".$hutang;
	echo"<br/>angsuran=".$angsuran;
	echo"<bt/>sisa=".$sisa_hutang;
	*/
	//----------------end group trpiutang detail---------------------------------	
    ?>
    <tr>
      <td height="2" colspan="6" class="style9"><hr /></td>
    </tr>
	
	
	
  </table>
   
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%"><div class="style11" id="terbilang"></div></td>
      <td width="5%">&nbsp;</td>
      <td width="8%">&nbsp;</td>
      <td width="2%" class="style9">
	  &nbsp;</td> 
	 <td width="5%">&nbsp;</td> 
     <td width="10%" class="style9" align="right"><strong>Grand Total</strong></td>
     <td width="10%">
		<div id="total" align="right" class="style20">
		<? echo number_format($jumlahbyr,0);?>
		</div>
	 </td>	 
    </tr>
	
	<tr>
    <td width="40%">&nbsp;</td>
      <td width="5%">&nbsp;</td>
      <td width="8%">&nbsp;</td>
      <td width="2%">&nbsp;</td> 
      <td width="5%">&nbsp;</td> 
      
	  <td width="10%" class="style9" align="right"><strong>TUNAI</strong></td>
      <td width="10%">
		<div id="total" align="right" class="style20">
		<? echo number_format($tunai_byr,0);?>
		</div>
	  </td>	 
    </tr>
	<tr>
	   
      <td width="40%">&nbsp;</td>
      <td width="5%">&nbsp;</td>
      <td width="8%">&nbsp;</td>
      <td width="2%">&nbsp;</td> 
      <td width="5%">&nbsp;</td>
      	  
      <td width="10%" class="style9" align="right"><strong>BANK</strong></td>
      <td width="10%">
		<div id="total" align="right" class="style20">
		<? echo number_format($bank_byr,0);?>
		</div>
	  </td>	 
    </tr>
	
  </table> 
  <table>
    <tr>
	  <td width="120" class="style9" align="left"><strong>TOTAL PIUTANG</strong></td>
      <td width="100">
		<div id="total" align="right" class="style20">
		<? echo number_format($piutangdt,0);?>
		</div>
	  </td>	 
    </tr>
    <tr>
	  <td width="120" class="style9" align="left"><strong>TOTAL ANGSURAN</strong></td>
      <td width="100">
		<div id="total" align="right" class="style20">
		<? echo number_format($totalbyrdt,0);?>
		</div>
	  </td>	 
    </tr>
	<tr>
	  <td width="120" class="style9" align="left"><strong>SISA PIUTANG</strong></td>
      <td width="100">
		<div id="total" align="right" class="style20">
		<? echo number_format($sisapiutangdt,0);?>
		</div>
	  </td>	 
    </tr>
  </table>
  
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="3" colspan="5"><p>
          <span class="style9">Lembar 1 : UNTUK PEMBELI</span>&nbsp;&nbsp;&nbsp;
          <span class="style9">Lembar 2 : UNTUK PENJUAL</span></p>
      </td>
    </tr>   
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="159"><div align="center" class="style9">Penerima,</div></td>
      <td width="600">&nbsp;</td>
      <td colspan="3"><div align="center" class="style9">&nbsp;</div></td>
    </tr>
    <tr>
      <td height="16">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="5">&nbsp;</td>
      <td width="167" valign="top">  </td>
      <td width="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="16"><div align="center">(.....................................)</div></td>
      <td><p align="center" class="style20">&nbsp;</p>
      </td>
      <td>(</td>
      <td><div align="center"class="style9">admin toko</div></td>
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
