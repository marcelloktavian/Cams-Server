<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=lapjualpabrik.xls");
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
			<a href="javascript:window.print()">CETAK</a> LAPORAN PENJUALAN DETAIL PABRIK</strong></td>
            
          </tr>
          <tr>
            <td width="19%" class="style9">Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="27" class="style9"><hr /></td>
          </tr>
      <tr>
      <td width="2%" class="style9b" rowspan="2"><div align="left">No.</div></td>
      <td width="3%" class="style9b" rowspan="2"><div align="left">Code</div></td>
      <td width="6%" class="style9b" rowspan="2"><div align="left">Nama Customer</div></td>
      <td width="3%" class="style9b" rowspan="2"><div align="left">NIK</div></td>
      <td width="3%" class="style9b" colspan="2"><div align="center">Surat Jalan</div></td>
      <td width="4%" class="style9b" colspan="2"><div align="center">Invoice</div></td>
 	  <td width="3%" class="style9b" colspan="2"><div align="center">Faktur Pajak</div></td>
 	  <td width="5%" class="style9b" rowspan="2"><div align="center">Nama Barang</div></td>
      <td width="2%" class="style9b" colspan="3"><div align="center">Qty dlm PCS</div></td>
      </div></td>
      <td width="2%" class="style9b" colspan="3"><div align="center">Qty dlm Yard</div></td>
      <td width="6%" class="style9b" rowspan="2"><div align="center">Jumlah Harga</div></td>
      <td width="3%" class="style9b" colspan="2"><div align="center">Potongan</div></td>
      <td width="6%" class="style9b" rowspan="2"><div align="right">DPP</div></td>
      <td width="4%" class="style9b" rowspan="2"><div align="right">PPN</div></td>
      <td width="6%" class="style9b" rowspan="2"><div align="right">DPP+PPN</div></td>
      <td width="3%" class="style9b" rowspan="2"><div align="right">Biaya Kuli</div></td>
      <td width="6%" class="style9b" rowspan="2"><div align="right">Total Penjualan</div></td>
	  <td width="6%" class="style9b" rowspan="2"><div align="right">Pembayaran</div></td>
	  <td width="5%" class="style9b" rowspan="2"><div align="right">Keterangan Pembayaran</div></td>
	  
    </tr>
	<tr>
      <td width="3%" class="style9b"><div align="center">Tgl.SJ</div></td>
      <td width="3%" class="style9b"><div align="center">No.SJ</div></td>
      <td width="4%" class="style9b"><div align="center">Tgl.Inv</div></td>
 	  <td width="3%" class="style9b"><div align="center">No.Inv</div></td>
      <td width="3%" class="style9b"><div align="center">Tgl.FP</div></td>
 	  <td width="3%" class="style9b"><div align="center">No.FP</div></td>
      <td width="2%" class="style9b"><div align="right">Qty</div></td>
      <td width="2%" class="style9b"><div align="center">Sat.</div></td>
      <td width="3%" class="style9b"><div align="right">Harga Sat.</div></td>
      <td width="2%" class="style9b"><div align="right">Qty</div></td>
      <td width="2%" class="style9b"><div align="center">Sat.</div></td>
      <td width="3%" class="style9b"><div align="right">Harga Sat.
	  </div></td>
      <td width="3%" class="style9b"><div align="right">Pot.Penj</div></td>
      <td width="3%" class="style9b"><div align="right">Retur</div></td>
      
    </tr>
    <tr>
      <td colspan="27" class="style16"><hr /></td>
    </tr>
    <?
		
	$where = "WHERE j.faktur=1 and j.deleted=0 and j.id_supplier<> 0 ";
	if($id_pelanggan != null){
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y') AND b.id=$id_pelanggan";
	}
	else
	{
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
	$sql_data = "Select p.id_trans,date_format(j.tgl_trans,'%d-%m-%Y') as tgl_trans,j.kode,j.id_supplier,c.namaperusahaan,c.id_cust,p.id_jenis,jb.nm_jenis,sum(p.qty)as qty,p.harga,p.harga_yard ,round(sum((p.harga_yard)*p.qty),0) as DPP,round(sum((p.harga_yard)*0.11*p.qty),0) as PPN,round(sum(p.harga_yard*p.qty)) as subtotal,j.totalfaktur,j.piutang,j.biaya,(j.totalfaktur-j.piutang) as bayar from trbeli_detail p inner join trbeli j on p.id_trans = j.id_trans left join tblsupplier c on j.id_supplier = c.id left join jenis_barang jb on p.id_jenis = jb.id ".$where." group by id_trans,id_jenis order by j.id_trans asc";
    //var_dump($sql_data);die;
	$sq2=mysql_query($sql_data);
	$i=1;
	$nomer=0;
	$konversi_yard=30;
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
      <td class="style9"><?=$rs2['id_cust'];?></td><!--kode-->
      <td class="style9"><?=$rs2['namaperusahaan'];?></td><!--customer-->
      <td class="style9"><?=$rs2['keterangan'];?></td><!--NIK-->
      <td class="style9"><div align="right">&nbsp;</div></td><!--TglSJ-->
      <td class="style9"><div align="right">&nbsp;</div></td><!--NoSJ-->
      <td class="style9"><div align="left"><!--Tgl Inv-->
	  <?=$rs2['tgl_trans'];?>
	  </div>
	  </td>
      <td class="style9"><div align="left"><?=$rs2['kode'];?><!--No.Inv-->
	  </div></td>
      <td class="style9"><div align="right">&nbsp;</div></td><!--Tgl.FP-->
      <td class="style9"><div align="right">&nbsp;</div></td><!--No.FP-->
      <td class="style9"><div align="center"><?=$rs2['nm_jenis'];?>
	  </div></td><!--Nama Barang-->
      <td class="style9"><div align="right">
        <?=number_format($rs2['qty'],0);?>
      </div></td><!--qty pcs-->
      <td class="style9"><div align="center">PCS</div></td>
      <td class="style9"><div align="right">
	  <?//=$rs2['harga'];?>
      </div></td><!--harga sat pcs-->
      <td class="style9"><div align="right">
	  <?=number_format($rs2['qty']*$konversi_yard,0);?>
      </div></td><!--qty yard-->
	  <td class="style9"><div align="center">YARD</div></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['harga_yard'],0);?>
      </div></td><!--harga sat yard-->
      <td class="style9"><div align="right">
	  <?=number_format($rs2['subtotal']*$konversi_yard,0);?>
      </div></td><!--jumlah harga-->
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right">&nbsp;</div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['DPP']*$konversi_yard,0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['DPP']*$konversi_yard*0.11,0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['subtotal']*$konversi_yard*1.11,0);//DPP+PPN?>
      </div></td>
	  <?
	  //bikin master
	  if ($kode!=$rs2['id_trans'])
	  {
		$grand_biaya+=$rs2['biaya'];
		$grand_totalfaktur+=$rs2['totalfaktur']*1.11;
		$grand_bayar+=$rs2['bayar'];
		echo"<td class='style9'><div align='right'>".$rs2['biaya']."</div></td>";
		echo"<td class='style9'><div align='right'>".number_format($rs2['totalfaktur']*$konversi_yard,0)."</div></td>";
		echo"<td class='style9'><div align='right'>".number_format($rs2['bayar']*$konversi_yard,0)."</div></td>";
		echo"<td class='style9'><div align='right'>".$keterangan."</div></td>";
		$kode=$rs2['id_trans'];
	  }
      else if($kode=$rs2['id_trans'])
	  {
	  echo"<td class='style9'><div align='right'></div></td>";
	  echo"<td class='style9'><div align='right'></div></td>";
	  echo"<td class='style9'><div align='right'></div></td>";
	  echo"<td class='style9'><div align='right'></div></td>";
	  }
	  
	  ?>
	  
    </tr>  <?
	$totqty+=$rs2['qty'];
	$grand_faktur+=($rs2['subtotal']);
	$grand_biaya+=$biaya;
	$totaldpp+=$rs2['DPP'];
	$totalppn+=$rs2['PPN'];
	$grand_piutang+=($rs2['piutang']*1.11);
	//$grand_bayar+=$rs2['bayar'];	
  }
  ?>
    <tr>
      <td height="2" colspan="27" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="11"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($totqty,0);?>
    </div>
	</td>
	<td class="style9"><div align="center">PCS</div>
	</td>
	<td>&nbsp;</td>
	<td class="style9"><div align="right">
          <?=number_format($totqty*$konversi_yard,0);?>
    </div>
	</td>
	<td class="style9"><div align="center">YARD</div>
	</td>
	<td>&nbsp;</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_faktur*$konversi_yard,0);//jumlah_harga?>
    </div>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	
	<td class="style9"><div align="right">
          <?=number_format($totaldpp*$konversi_yard,0);//totaldpp?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($totalppn*$konversi_yard*0.11,0);//totalppn?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=number_format($grand_faktur*$konversi_yard*1.11,0);//dpp+ppn?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$grand_biaya;?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=number_format($grand_totalfaktur*$konversi_yard,0);?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=number_format($grand_bayar*$konversi_yard,0);?>
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
