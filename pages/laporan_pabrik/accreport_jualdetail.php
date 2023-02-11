<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<style type="text/css">
.style9 {
font-size: 7pt; 
font-family:MS Reference Sans Serif;
}
.style9b {color: #000000;
	font-size: 7pt;
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
	//$tglstart='2018-01-01';
    //$tglend='2018-12-30';
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$id_pelanggan=$_GET['id'];
	
	//$sql = mysql_query("SELECT sum(a.totalqty) as totalqty,sum(a.totalfaktur) as totalfaktur,sum(a.faktur) as faktur,sum(a.tunai) as tunai,sum(a.transfer) as transfer,sum(a.piutang) as piutang,sum(a.biaya) as biaya FROM trjual a 
    //where DATE_FORMAT(a.tgl_trans,'%Y-%m-%d') between '".$tglstart."' AND '" .$tglend."'");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="6"><strong>
			<a href="javascript:window.print()">CETAK</a> LAPORAN PENJUALAN TOKO</strong></td>
            
          </tr>
          <tr>
            <td width="19%" class="style9"><a href="accreport_jualdetailxl.php?start=<?php echo"".$tglstart;?>&end=<?php echo"".$tglend;?>&id=<?php echo"".$id_pelanggan;?>">Export ke Excell</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="23" class="style9"><hr /></td>
          </tr>
      <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="5%" class="style9b"><div align="left">Code</div></td>
      <td width="6%" class="style9b"><div align="left">Nama Customer</div></td>
      <td width="5%" class="style9b"><div align="left">NIK</div></td>
      <td width="3%" class="style9b"><div align="center">Tgl.SJ</div></td>
      <td width="3%" class="style9b"><div align="center">No.SJ</div></td>
      <td width="6%" class="style9b"><div align="left">Tgl.Inv</div></td>
 	  <td width="3%" class="style9b"><div align="left">No.Inv</div></td>
      <td width="3%" class="style9b"><div align="left">Tgl.FP</div></td>
 	  <td width="4%" class="style9b"><div align="left">No.FP</div></td>
      <td width="5%" class="style9b"><div align="left">Nama Barang</div></td>
      <td width="2%" class="style9b"><div align="right">Qty</div></td>
      <td width="2%" class="style9b"><div align="left">Satuan</div></td>
      <td width="3%" class="style9b"><div align="right">Harga Satuan</div></td>
      <td width="6%" class="style9b"><div align="right">Jumlah Harga</div></td>
      <td width="3%" class="style9b"><div align="right">Pot.Penj</div></td>
      <td width="3%" class="style9b"><div align="right">Retur</div></td>
      <td width="6%" class="style9b"><div align="right">DPP</div></td>
      <td width="3%" class="style9b"><div align="right">PPN</div></td>
      <td width="6%" class="style9b"><div align="right">DPP+PPN</div></td>
      <td width="4%" class="style9b"><div align="right">Biaya Kuli</div></td>
      <td width="6%" class="style9b"><div align="right">Total Piutang</div></td>
	  <td width="6%" class="style9b"><div align="right">Pembayaran</div></td>
	  <!--
	  <td width="5%" class="style9b"><div align="right">Keterangan Pembayaran</div></td>
	  -->
    </tr>
    <tr>
      <td colspan="23" class="style16"><hr /></td>
    </tr>
    <?
		
	$where = "WHERE TRUE ";
	if($id_pelanggan != null){
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y') AND b.id=$id_pelanggan";
	}
	else
	{
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
	//$sq2 = mysql_query("select b.namaperusahaan,a.id_trans,a.kode, date_format(a.tgl_trans,'%d-%m-%Y') as tgl_trans, a.totalqty,a.faktur, a.totalfaktur,b.id_cust,b.keterangan,round((a.faktur/a.totalqty),0) as harga_satuan,round((a.faktur/1.11),0) as DPP,round((a.faktur/1.11)*0.11,0) as PPN,a.tunai,a.transfer,a.piutang,a.biaya,(a.totalfaktur-a.piutang) as bayar from trjual a left join tblpelanggan b on b.id = a.id_customer ".$where." order by a.id_trans asc");
    
	$sq2 = mysql_query("Select date_format(j.tgl_trans,'%d-%m-%Y') as tgl_trans,j.kode,j.id_customer,c.namaperusahaan,c.id_cust,p.id_jenis,jb.nm_jenis,p.kuantum,p.harga,p.harga_plus_ppn ,round((p.harga_plus_ppn/1.11),0) as DPP,round((p.harga_plus_ppn/1.11)*0.11,0) as PPN,j.totalfaktur,j.piutang,j.biaya,(j.totalfaktur-j.piutang) as bayar from trjual_print p inner join trjual j on p.id_trans = j.id_trans left join tblpelanggan c on j.id_customer = c.id left join jenis_barang jb on p.id_jenis = jb.id ".$where." order by j.id_trans asc");
    //var_dump($sq2);die;
	$i=1;
	$nomer=0;
	$totalfaktur=0;
	$grand_totalfaktur=0;
	$grand_bayar=0;
	$grand_biaya=0;
	$biaya=0;
	$bayar=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 
	  $nomer++;
      $keterangan="";
	  /*
	  if 
	  (($rs2['piutang'] > 0 )
	  and ($rs2['piutang'] < $rs2['totalfaktur'])) 
	  {
	  $keterangan="SETORAN TUNAI KE BCA";
	  }
	  
	  else if (($rs2['piutang'] > 0) and ($rs2['piutang']==$rs2['totalfaktur'])){
	  $keterangan="PIUTANG";
	  }
	  
	  else if (($rs2['piutang']==0) and ($rs2['tunai']>0)){
	  $keterangan="SETORAN TUNAI KE BCA";
	  }
      else if (($rs2['piutang']==0) and ($rs2['transfer']>0)){
      $keterangan="TF BANK BCA";
	  }
	  */
  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><?=$rs2['id_cust'];?></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><?=$rs2['keterangan'];?></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="left">
	  <?=$rs2['tgl_trans'];?>
	  </div>
	  </td>
      <td class="style9"><div align="left"><?=$rs2['kode'];?>
	  </div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="center"><?=$rs2['nm_jenis'];?>
	  </div></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['kuantum'],0);?>
      </div></td>
      <td class="style9">PCS</td>
      <td class="style9"><div align="right">
	  <?=$rs2['harga'];?>
      </div></td>
      <td class="style9"><div align="right">
	  <?=$rs2['harga_plus_ppn'];?>
      </div></td>
      <td class="style9"><div align="center">&nbsp;</div></td>
      <td class="style9"><div align="center">&nbsp;</div></td>
      <td class="style9"><div align="right">
	  <?=$rs2['DPP'];?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=$rs2['PPN'];?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=$rs2['harga_plus_ppn'];//DPP+PPN?>
      </div></td>
	  <?
	  //bikin master
	  if ($kode!=$rs2['kode'])
	  {
		$grand_biaya+=$rs2['biaya'];
		$grand_totalfaktur+=$rs2['totalfaktur'];
		$grand_bayar+=$rs2['bayar'];
		echo"<td class='style9'><div align='right'>".$rs2['biaya']."</div></td>";
		echo"<td class='style9'><div align='right'>".$rs2['totalfaktur']."</div></td>";
		echo"<td class='style9'><div align='right'>".$rs2['bayar']."</div></td>";
		$kode=$rs2['kode'];
	  }
      else if($kode=$rs2['kode'])
	  {
	  echo"<td class='style9'><div align='right'></div></td>";
	  echo"<td class='style9'><div align='right'></div></td>";
	  echo"<td class='style9'><div align='right'></div></td>";
	  }
	  
	  ?>
	  <td class="style9"><div align="right">
          <? //echo$keterangan;?>
      </div></td>
    </tr>  <?
	$totqty+=$rs2['kuantum'];
	//$grand_totalfaktur+=$rs2['totalfaktur'];
	//$grand_totalfaktur=$rs['totalfaktur'];
	$grand_faktur+=$rs2['faktur'];
	$grand_faktur+=$rs2['harga_plus_ppn'];
	//$grand_biaya+=$rs2['biaya'];
	$grand_biaya+=$biaya;
	$totaldpp+=$rs2['DPP'];
	$totalppn+=$rs2['PPN'];
	$grand_piutang+=$rs2['piutang'];
	//$grand_bayar+=$rs2['bayar'];	
  }
  ?>
    <tr>
      <td height="2" colspan="23" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="11"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=$totqty;?>
    </div>
	</td>
	<td class="style9">PCS
	</td>
	<td>&nbsp;
	</td>
	<td class="style9"><div align="right">
          <?=$grand_faktur;?>
    </div>
	</td>
	<td class="style9">&nbsp;</td>
    <td class="style9">&nbsp;</td>
    <td class="style9"><div align="right">
          <?=$totaldpp;?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=$totalppn;?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$grand_faktur;//dpp+ppn?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$grand_biaya;?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$grand_totalfaktur;?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$grand_bayar;?>
    </div>
	</td>
	<!--
	<td class="style9">&nbsp;</td>
    -->
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
