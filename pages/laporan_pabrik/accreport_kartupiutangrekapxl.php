<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=kartupiutangrekap.xls");
?>
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
table#tbd, table#tbd th, table#tbd td {
    border: 1px solid black;
    border-collapse: collapse;
}
table#tbd, table#tbd th, table#tbd td {
    padding: 5px;
    text-align: left;    
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
	$sql = mysql_query("SELECT id,id_cust,namaperusahaan,substring(id_cust,3,3)as nopel FROM tblpelanggan where  id= ".$id_pelanggan);
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="6"><strong>
			<?echo"REKAP KARTU PIUTANG Tahun ". date('Y');?> </strong></td>
          </tr>
          <tr>
            <td class="style9" width="5%"><div align="center"><?=$rs['nopel'];?></div></td>
            <td class="style9" width="7%"><div align="center"><?=$rs['id_cust'];?></div></td>
            <td class="style9" width="18%"><div align="center"><?=$rs['namaperusahaan'];?></div></td>
            <td class="style9" width="19%">Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          		  
  </table>  
  
    
  <table width="100%" id="tbd" border="0" align="center" cellpadding="0" cellspacing="0">
        
     <tr>
      <td width="2%" class="style9b" rowspan="3"><div align="left">No.</div></td>
      <td width="3%" class="style9b" rowspan="3"><div align="center">Code.</div></td>
      <td width="10%" class="style9b" rowspan="3"><div align="center">Customer</div></td>
      <td width="3%" class="style9b" rowspan="3"><div align="center">NIK</div></td>
      <td width="3%" class="style9b" rowspan="3"><div align="center"><? echo"SALDO AKHIR PER 31 DESEMBER Tahun ".date("Y",strtotime("-1 year"));;?> </div></td>
      <td width="8%" class="style9b" colspan="5"><div align="center">Piutang</div></td>
	  <!--colspan=5
      <td width="6%" class="style9b"><div align="right">Ongkos Kuli</div></td>
      <td width="5%" class="style9b"><div align="center">Pot.Penj</div></td>
      <td width="5%" class="style9b"><div align="center">Retur</div></td>
 	  <td width="8%" class="style9b"><div align="right">Jumlah Piutang</div></td>
	  -->
      <td width="8%" class="style9b" colspan="3"><div align="center">PEMBAYARAN</div></td>
 	  <!--colspan=5
	  <td width="8%" class="style9b"><div align="right">BCA</div></td>
      <td width="8%" class="style9b"><div align="right">Total Bayar</div></td>
	  -->
      <td width="8%" class="style9b" rowspan="3"><div align="right">Saldo Akhir</div></td>    
    </tr>
	 <tr>
      <!--rowspan=3<td width="5%" class="style9b"><div align="left">No</div></td>-->
      <!--rowspan=3<td width="5%" class="style9b"><div align="center">Code</div></td>-->
      <!--rowspan=3<td width="6%" class="style9b"><div align="left">Customer</div></td>-->
      <!--rowspan=3<td width="6%" class="style9b"><div align="left">NIK</div></td>-->
      <td width="8%" class="style9b" colspan="2"><div align="center">Piutang</div></td>
      <!--colspan-2<td width="6%" class="style9b"><div align="right">Ongkos Kuli</div></td>-->
      <td width="5%" class="style9b" colspan="2"><div align="center">Potongan</div></td>
      <!--colspan-2<td width="5%" class="style9b"><div align="center">Retur</div></td>-->
 	  <td width="8%" class="style9b" rowspan="2"><div align="right">Jumlah Piutang</div></td>
      <td width="8%" class="style9b" rowspan="2"><div align="right">KAS</div></td>
 	  <td width="8%" class="style9b" rowspan="2"><div align="right">BCA</div></td>
      <td width="8%" class="style9b" rowspan="2"><div align="right">Total Bayar</div></td>
      <!--rowspan=3
	  <td width="8%" class="style9b"><div align="right">Saldo Akhir</div></td>
      -->
    </tr>
	<tr>
      <!--rowspan=3<td width="5%" class="style9b"><div align="left">No</div></td>-->
      <!--rowspan=3<td width="5%" class="style9b"><div align="center">Code</div></td>-->
      <!--rowspan=3<td width="6%" class="style9b"><div align="left">Customer</div></td>-->
      <!--rowspan=3<td width="6%" class="style9b"><div align="left">NIK</div></td>-->
      <td width="8%" class="style9b"><div align="right">Piutang Awal</div></td>
      <td width="6%" class="style9b"><div align="right">Ongkos Kuli</div></td>
      <td width="5%" class="style9b"><div align="center">Pot.Penj</div></td>
      <td width="5%" class="style9b"><div align="center">Retur</div></td>
 	  <!--rowspan=3<td width="8%" class="style9b"><div align="right">Jumlah Piutang</div></td>-->
      <!--rowspan=2
	  <td width="8%" class="style9b"><div align="right">KAS</div></td>
 	  <td width="8%" class="style9b"><div align="right">BCA</div></td>
      <td width="8%" class="style9b"><div align="right">Total Bayar</div></td>
      -->
	  <!--rowspan=3
	  <td width="8%" class="style9b"><div align="right">Saldo Akhir</div></td>
      -->
    </tr>
    <?
		
	$where = " where TRUE";
	if($id_pelanggan != null){
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y') AND id_customer=$id_pelanggan";
	}
	else
	{
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	$sql_union="Select b.id,b.id_cust,b.namaperusahaan,b.keterangan,jual.faktur,jual.biaya,jual.tunai,jual.transfer,jual.totalfaktur,jual.bayar_faktur
	,jual.piutang_faktur,jual.piutang_tunai,jual.piutang_transfer,jual.piutang_bayar from tblpelanggan b 
	left join 
	(Select id_customer,sum(faktur) as faktur,sum(biaya) as biaya,sum(tunai) as tunai,sum(transfer) as transfer,sum(totalfaktur) as totalfaktur
	,sum(bayar_faktur) as bayar_faktur, sum(piutang_faktur) as piutang_faktur, sum(piutang_tunai) as piutang_tunai
	,sum(piutang_transfer) as piutang_transfer,sum(piutang_bayar) as piutang_bayar 
	from (Select tgl_trans,id_customer,id_trans,kode,faktur,biaya,tunai,transfer,totalfaktur,(totalfaktur-piutang) as bayar_faktur,piutang as piutang_faktur,'' as piutang_tunai ,'' as piutang_transfer,'' as piutang_bayar from trjual ".$where."
	union all
	Select tgl_trans,id_customer,id_trans,kode,'','','','','','','',tunai,transfer,totalfaktur from trpiutang ".$where.") temp  
	group by id_customer
	) as jual on b.id = jual.id_customer 
	where b.type = 2 order by b.id_cust asc";
	
	//$sql_union="Select '' as id_transjual,tgl_trans,date_format(tgl_trans,'%d-%m-%Y')as tgl_edit,id_customer,id_trans,kode,faktur,biaya,tunai,transfer,totalfaktur,(totalfaktur-piutang) as bayar_faktur,piutang as piutang_faktur,'' as piutang_tunai ,'' as piutang_transfer,'' as piutang_bayar from trjual ".$where." 
    //union all
    //Select id_transjual,tgl_trans,date_format(tgl_trans,'%d-%m-%Y')as tgl_edit,id_customer,id_trans,kode,'','','','','','','',tunai,transfer,totalfaktur from trpiutang ".$where."  
    //order by tgl_trans asc";
	//var_dump($sql_union);die;
	
	$sq2=mysql_query($sql_union);
	$i=1;
	$nomer=0;
	$totalfaktur=0;
	$grand_totalfaktur=0;
	$grand_bayar=0;
	$grand_biaya=0;
	$piutang_awal=0;
	$ongkos_kuli=0;
	$jumlah_piutang=0;
	$kas=0;
	$bca=0;
	$totalbayar=0;
	$saldoakhir=0;
	//$kode_substring;
	while($rs2=mysql_fetch_array($sq2))
	//while($rs2=mysql_fetch_array($sql_union))
	{ 
	  $nomer++;
      $keterangan="";
	  $kode="";
	
	  //bila penjualan
	  //if (substr($rs2['id_trans'],0,3) != 'TPB' )
	  //{
	  //$kode=$rs2['kode'];
	  //$keterangan="JUAL";
	  $piutang_awal=$rs2['faktur'];
	  $ongkos_kuli=$rs2['biaya'];
	  $jumlah_piutang=$rs2['totalfaktur'];
	  $kas="";//dianggap ke bank semua
	  $bca=$rs2['tunai']+$rs2['transfer']+$rs2['piutang_tunai'] + $rs2['piutang_transfer'];
	  $total_bayar=$rs2['tunai']+$rs2['transfer']+$rs2['piutang_tunai'] + $rs2['piutang_transfer'];
	  $saldo_akhir=$rs2['totalfaktur']-($rs2['tunai']+$rs2['transfer']+$rs2['piutang_tunai'] + $rs2['piutang_transfer']);
	  //var_dump($keterangan);die;
	  //}
	  //bila pelunasan piutang
	  /*else if (substr($rs2['id_trans'],0,3) == 'TPB' ){
	  $kode=$rs2['id_trans']."(".$rs2['id_transjual'].")";
	  $keterangan="BAYAR";
	  $piutang_awal="";
	  $ongkos_kuli="";
	  $jumlah_piutang="";
	  //$kas=$rs2['piutang_tunai'];semua pelunasan dianggap ke bca,baik tunai maupun transfer
	  $kas="";
	  //$bca=$rs2['piutang_transfer'];
	  $bca=$rs2['piutang_tunai'] + $rs2['piutang_transfer'];
	  $total_bayar=$rs2['piutang_bayar'];
	  $saldo_akhir="";
	  }
	  */
  ?>
    <tr>
      <td class="style9"><div align="left"><?=$nomer;?></div></td>
      <td class="style9"><div align="center"><?=$rs2['id_cust'];?></div></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><?=$rs2['keterangan'];?></td>
      <td class="style9">&nbsp;</td>
      <td class="style9"><div align="right"><?=number_format($piutang_awal,0);?></div></td>
      <td class="style9"><div align="right"><?=number_format($ongkos_kuli,0);?>
	  </div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right"><?=number_format($jumlah_piutang,0);?></div></td>
      <td class="style9"><div align="right"><?=number_format($kas,0);?></div></td>
      <td class="style9"><div align="right"><?=number_format($bca,0);?>
	  </div></td>
      <td class="style9"><div align="right"><?=number_format($total_bayar,0);?></div></td>
      <td class="style9"><div align="right">
	  <?=number_format($saldo_akhir,0);?>
      </div></td>
      <?
	  //bikin master
	  /*
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
	  */
	  ?>
    </tr>  <?
	$grandpiutangawal+=$piutang_awal;
	$grandongkoskuli+=$ongkos_kuli;
	$grandjumlahpiutang+=$jumlah_piutang;
	$grandkas+=$kas;
	$grandbca+=$bca;
	$grandtotalbayar+=$total_bayar;
	
	$grandsaldoakhir=$grandjumlahpiutang-$grandtotalbayar;
		
  }
  ?>
    <tr>
	<td class="style9" colspan="5"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($grandpiutangawal,0);?>
    </div>
	</td>
	<td class="style9"><div align="right"><?=number_format($grandongkoskuli,0);?></div></td>
	<td class="style9">&nbsp;</td>
	<td class="style9">&nbsp;</td>
    <td class="style9"><div align="right"><?=number_format($grandjumlahpiutang,0);?></div></td>
    <td class="style9"><div align="right"><?=number_format($grandkas,0);?></div></td>
	<td class="style9"><div align="right"><?=number_format($grandbca,0);?></div></td>
	<td class="style9"><div align="right"><?=number_format($grandtotalbayar,0);?></div></td>
	<td class="style9"><div align="right"><?=number_format($grandsaldoakhir,0);?></div></td>
	
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
