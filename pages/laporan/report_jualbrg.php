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
    //$id_faktur=$_GET['id_trans'];
    //$id_faktur=TSO18020021;
	//$tglstart='2018-03-20';
    //$tglend='2018-03-20';
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$sql = mysql_query("SELECT sum(a.totalqty) as totalqty,sum(a.totalfaktur) as totalfaktur,sum(a.faktur) as faktur,sum(a.tunai) as tunai,sum(a.transfer) as transfer,sum(a.piutang) as piutang,sum(a.biaya) as biaya FROM trjual a 
    where DATE_FORMAT(a.tgl_trans,'%Y-%m-%d') between '".$tglstart."' AND '" .$tglend."'");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="6"><strong>
			<a href="javascript:window.print()">CETAK</a> LAPORAN PENJUALAN BARANG</strong></td>
            
          </tr>
          <tr>
            <td width="19%" class="style9">Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          <tr>
            <td colspan="7" class="style9"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="20%" class="style9b"><div align="left">Keterangan</div></td>
 	  <td width="15%" class="style9b"><div align="right">ARROW KELIR</div></td>
      <td width="15%" class="style9b"><div align="right">ARROW PUTIH</div></td>
      <td width="15%" class="style9b"><div align="right">TC KELIR</div></td>
      <td width="15%" class="style9b"><div align="right">TC PUTIH</div></td>
      <td width="18%" class="style9b"><div align="right">TOTAL</div></td>
    </tr>
    <tr>
      <td colspan="7" class="style16"><hr /></td>
    </tr>
	<tr>
      <td colspan="7" class="style9">&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" class="style9">&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" class="style9">&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" class="style9"><hr /></td>
    </tr>
                
    <?
		
	//$sq2 = mysql_query("select a.id_detail,a.id_barang, b.nm_barang, a.qty, a.harga, (a.qty * a.harga) as subtotal from trjual_detail a, barang b where a.id_barang=b.id_barang and a.id_trans ='".$_GET['id_trans']."'");
	
	//$id_faktur=TSO18020021;
	//$sq2 = mysql_query("select b.namaperusahaan,a.id_trans, date_format(a.tgl_trans,'%d-%m-%Y') as tgl_trans, a.totalqty,a.faktur, a.totalfaktur, a.tunai,a.transfer,a.piutang,a.biaya from trjual a left join tblpelanggan b on b.id = a.id_customer
	//where DATE_FORMAT(a.tgl_trans,'%Y-%m-%d') between '".$tglstart."' AND '" .$tglend."'");
//	"2017-06-15", "%M %d %Y"
	
	$sq2 ="SELECT c.namaperusahaan,SUM(j.piutang) AS piutang,SUM(j.totalfaktur) AS totalfaktur,SUM(j.totalqty) AS totalqty,SUM(j.tunai) AS tunai,SUM(j.transfer) AS transfer,SUM( IF( (p.id_jenis) = 1, kuantum, 0) ) AS ARROW_KELIR,SUM( IF((p.id_jenis) = 2, kuantum, 0) ) AS ARROW_PUTIH,SUM( IF( (p.id_jenis) = 3, kuantum, 0) ) AS TC_KELIR,SUM( IF( (p.id_jenis) = 4, kuantum, 0) ) AS TC_PUTIH,SUM(p.kuantum) AS total FROM trjual_print p LEFT JOIN trjual j ON p.id_trans = j.id_trans LEFT JOIN tblpelanggan c ON j.id_customer = c.id where DATE_FORMAT(j.tgl_trans,'%Y-%m-%d') between '".$tglstart."' AND '" .$tglend."' GROUP BY p.id_trans asc"; 
    $data =  mysql_query($sq2);
	
	$i=1;
	$nomer=0;
	 
	while($rs2=mysql_fetch_array($data))
	{ $nomer++;

  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['ARROW_KELIR'],0);?>
      </div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['ARROW_PUTIH'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['TC_KELIR'],0);?>
      </div></td>
      <td class="style9"><div align="right">
          <?=number_format($rs2['TC_PUTIH'],0);?>
      </div></td>
       <td class="style9"><div align="right">
          <?=number_format($rs2['total'],0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['total'];
	$ak+=$rs2['ARROW_KELIR'];
	$ap+=$rs2['ARROW_PUTIH'];
	$tk+=$rs2['TC_KELIR'];
	$tp+=$rs2['TC_PUTIH'];
	//$totqty+=$rs2['kuantum'];
  }
  ?>
    <tr>
      <td height="2" colspan="7" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="2"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <? echo"".$ak;?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <? echo"".$ap;?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <? echo"".$tk;?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <? echo"".$tp;?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <? echo"".$total2;?>&nbsp;&nbsp;pcs
    </div>
	</td>
	
	</tr>
	
</table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
//var total=getNumber(document.getElementById("total").innerHTML);
//document.getElementById("terbilang").innerHTML = terbilang(total);

//dimatikan agar bisa view dulu...window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
