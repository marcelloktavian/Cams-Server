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
	
	//$sql = mysql_query("SELECT sum(a.totalqty) as totalqty,sum(a.totalfaktur) as totalfaktur,sum(a.faktur) as faktur,sum(a.tunai) as tunai,sum(a.transfer) as transfer,sum(a.piutang) as piutang,sum(a.biaya) as biaya FROM trjual a 
    //where DATE_FORMAT(a.tgl_trans,'%Y-%m-%d') between '".$tglstart."' AND '" .$tglend."'");
	//$rs = mysql_fetch_array($sql);
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
		  <td width="19%" class="style9"><a href="report_jualxl.php?start=<?php echo"".$tglstart;?>&end=<?php echo"".$tglend;?>&id=<?php echo"".$id_pelanggan;?>">Export ke Excell</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          <tr>
            <td colspan="12" class="style9"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="10%" class="style9b"><div align="left">Tanggal</div></td>
 	  <td width="6%" class="style9b"><div align="center">No.Invoice</div></td>
      <td width="6%" class="style9b"><div align="center">Kode</div></td>
      <td width="10%" class="style9b"><div align="left">Pelanggan</div></td>
      <td width="8%" class="style9b"><div align="right">Total Qty</div></td>
      <td width="10%" class="style9b"><div align="right">Faktur</div></td>
      <td width="6%" class="style9b"><div align="right">Ongkos</div></td>
      <td width="10%" class="style9b"><div align="right">Total Faktur</div></td>
      <td width="10%" class="style9b"><div align="right">Tunai</div></td>
      <td width="10%" class="style9b"><div align="right">Transfer</div></td>
      <td width="10%" class="style9b"><div align="right">Saldo</div></td>
    </tr>
    <tr>
      <td colspan="12" class="style16"><hr /></td>
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
	
	$sq2 = mysql_query("select b.namaperusahaan,a.id_trans,a.kode, date_format(a.tgl_trans,'%d-%m-%Y') as tgl_trans, a.totalqty,a.faktur, a.totalfaktur,b.id_cust, a.tunai,a.transfer,a.piutang,a.biaya from trjual a left join tblpelanggan b on b.id = a.id_customer ".$where." order by a.id_trans asc");
	
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;

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
      <td class="style9"><div align="center"><?=$rs2['id_cust'];?></div></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['totalqty'],0);?>
      &nbsp;&nbsp;pcs</div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['faktur'],0);?>
      </div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['biaya'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['totalfaktur'],0);?>
      </div></td>
      <td class="style9"><div align="right">
          <?=number_format($rs2['tunai'],0);?>
      </div></td>
       <td class="style9"><div align="right">
          <?=number_format($rs2['transfer'],0);?>
      </div></td>
      <td class="style9"><div align="right">
          <?=number_format($rs2['piutang'],0);?>
      </div></td>
    </tr>  <?
	$totqty+=$rs2['totalqty'];
	$grand_totalfaktur+=$rs2['totalfaktur'];
	$grand_faktur+=$rs2['faktur'];
	$grand_biaya+=$rs2['biaya'];
	$grand_tunai+=$rs2['tunai'];
	$grand_transfer+=$rs2['transfer'];
	$grand_piutang+=$rs2['piutang'];
	
  }
  ?>
    <tr>
      <td height="2" colspan="12" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="5"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($totqty,0);?>&nbsp;&nbsp;pcs
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
	<td class="style9"><div align="right">
          <?=number_format($grand_tunai,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_transfer,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_piutang,0);?>
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
