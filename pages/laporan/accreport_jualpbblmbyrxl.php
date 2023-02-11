<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=lapjualpabrikblmbyr.xls");
?>

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
	//$tglstart='2018-01-01';
    //$tglend='2018-12-30';
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$id_pelanggan=$_GET['id'];
	
	$sql = mysql_query("SELECT date_format(max(a.tgl_trans),'%d-%m-%Y %H:%i:%s') as tgl_akhir,sum(a.totalqty) as totalqty,sum(a.totalfaktur) as totalfaktur,sum(a.faktur) as faktur,sum(a.tunai) as tunai,sum(a.transfer) as transfer,sum(a.piutang) as piutang,sum(a.biaya) as biaya, sum(a.piutang-a.pelunasan) as saldo FROM trjual a 
    where ((a.piutang-a.pelunasan) > 0)");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="6"><strong>
			<a href="javascript:window.print()">CETAK</a> LAPORAN PENJUALAN PABRIK BELUM LUNAS</strong></td>
            
          </tr>
          <tr>
            <td width="19%" class="style9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
            <td class="style9" width="30%">Data Transaksi Belum Lunas sd tanggal:<?php echo"".$rs['tgl_akhir'];?></td>
            <td class="style9" width="19%">&nbsp;</td>           
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>   
            <td class="style9" width="30%"class="style9">&nbsp;</td>           
		  </tr>
          <tr>
            <td colspan="10" class="style9"><hr /></td>
            <!--<td colspan="12" class="style9"><hr /></td>-->
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="10%" class="style9b"><div align="left">Tanggal</div></td>
 	  <td width="6%" class="style9b"><div align="center">No.Invoice</div></td>
      <td width="7%" class="style9b"><div align="left">Kode</div></td>
      <td width="7%" class="style9b"><div align="left">Pelanggan</div></td>
      <td width="8%" class="style9b"><div align="right">Total Qty</div></td>
      <td width="8%" class="style9b"><div align="right">Faktur</div></td>
      <td width="8%" class="style9b"><div align="right">Ongkos</div></td>
      <td width="10%" class="style9b"><div align="right">Total Faktur</div></td>
      <!--
	  <td width="10%" class="style9b"><div align="right">Tunai</div></td>
      <td width="10%" class="style9b"><div align="right">Transfer</div></td>
	  -->
      <td width="10%" class="style9b"><div align="right">Saldo</div></td>
    </tr>
    <tr>
      <!--<td colspan="12" class="style16"><hr /></td>-->
      <td colspan="10" class="style16"><hr /></td>
    </tr>
    <?
		
	$where = "";
	if($id_pelanggan != null){
	$where .= " AND b.id=$id_pelanggan";
	}
	else
	{
	$where .= "";
	}
	
	$sql_data= "select b.namaperusahaan,a.id_trans,a.kode,b.id_cust, date_format(a.tgl_trans,'%d-%m-%Y') as tgl_trans, a.totalqty,a.faktur_murni, a.totalfaktur, a.tunai,a.transfer,a.piutang,(a.piutang-a.pelunasan) as saldo,a.biaya from trbeli a left join tblsupplier b on b.id = a.id_supplier
	where ((a.piutang-a.pelunasan) > 0)  and b.type=2 and a.id_supplier <>0 ".$where." order by b.id_cust asc";
	//var_dump($sql_data);die;
	$sq2 =mysql_query($sql_data);
	$i=1;
	$nomer=0;
	$konversi=30;
	
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;
      $harga_satuan=$rs2['harga_satuan']*$konversi;
	  $faktur=$rs2['faktur_murni']*$konversi*1.11;
	  //$dpp=$rs2['dpp']*$konversi;
      $saldo=$rs2['saldo']*$konversi;
      $totalfaktur=$rs2['totalfaktur']*$konversi*1.11;
	  
  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><div align="left">
	  <?=$rs2['tgl_trans'];?>
	  </div>
	  </td>
      <td class="style9"><div align="center"><?=$rs2['kode'];?>
	  </div></td>
      <td class="style9"><?=$rs2['id_cust'];?></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['totalqty'],0);?>
      &nbsp;&nbsp;pcs</div></td>
      <td class="style9"><div align="right">
	  <?=number_format($faktur,0);?>
      </div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['biaya'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($totalfaktur,0);?>
      </div></td>
      <!--
	  <td class="style9"><div align="right">
          <?//=number_format($rs2['tunai'],0);?>
      </div></td>
       <td class="style9"><div align="right">
          <?//=number_format($rs2['transfer'],0);?>
      </div></td>
	  -->
      <td class="style9"><div align="right">
          <?=number_format($saldo,0);?>
      </div></td>
    </tr>  <?
	$grand_qty+=$rs2['totalqty'];
	$grand_faktur+=$faktur;
	$grand_biaya+=$rs2['biaya'];
	$grand_totalfaktur+=$totalfaktur;
	$grand_tunai+=$rs2['tunai'];
	$grand_transfer+=$rs['transfer'];
	$grand_saldo+=$saldo;
  }
  ?>
    <tr>
      <!--<td height="2" colspan="12" class="style9"><hr /></td>-->
      <td height="2" colspan="10" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="5"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($grand_qty,0);?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_faktur,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_biaya,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_totalfaktur,0);?>
    </div>
	</td>
	<!--
	<td class="style9"><div align="right">
          <?//=number_format($grand_tunai,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?//=number_format($grand_transfer,0);?>
    </div>
	</td>
	-->
	<td class="style9"><div align="right">
          <?=number_format($grand_saldo,0);?>
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
