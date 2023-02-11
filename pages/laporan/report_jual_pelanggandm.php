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
			LAPORAN PENJUALAN PER PELANGGAN</strong></td>
          </tr>
          <tr>
            <td width="19%" class="style9"><a href="report_jual_pelangganxl.php?start=<?php echo"".$tglstart;?>&end=<?php echo"".$tglend;?>&id=<?php echo"".$id_pelanggan;?>">Export ke Excell</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dari</td>
            <td class="style9" width="1%"><div align="center">:</div></td>
            <td class="style9" width="30%"><?php echo"".$tglstart;?></td>
            <td class="style9" width="19%">Sampai</td>           
            <td class="style9" width="1%"><div align="center">:</div></td>           
            <td class="style9" width="30%"class="style9"><?php echo"".$tglend;?></td>           
		  </tr>
          <tr>
            <td colspan="10" class="style9"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="5%" class="style9b"><div align="left">Code</div></td>
      <td width="10%" class="style9b"><div align="left">Nama Customer</div></td>
      <td width="7%" class="style9b"><div align="left">NIK</div></td>    	  
	  <td width="7%" class="style9b"><div align="left">Nama Barang</div></td>
      <td width="2%" class="style9b"><div align="right">Qty</div></td>
      <td width="5%" class="style9b"><div align="right">Satuan</div></td>
      <td width="5%" class="style9b"><div align="right">Harga Satuan</div></td>
      <td width="8%" class="style9b"><div align="right">Jumlah Harga</div></td>
      <td width="8%" class="style9b"><div align="right">Pot.Penjualan</div></td>
      <td width="5%" class="style9b"><div align="right">Retur</div></td>
      <td width="5%" class="style9b"><div align="right">DPP</div></td>
      <td width="5%" class="style9b"><div align="right">PPN</div></td>
      <td width="8%" class="style9b"><div align="right">DPP+PPN</div></td>
      <td width="8%" class="style9b"><div align="right">Biaya Kuli</div></td>
      <td width="10%" class="style9b"><div align="right">Total Piutang</div></td>
    </tr>
    <tr>
      <td colspan="16" class="style16"><hr /></td>
    </tr>
    <?
	
	$where = " where TRUE ";
	if($id_pelanggan != null){
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y') AND b.id=$id_pelanggan";
	}
	else
	{
	$where .= " AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')   AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	} 
			
	/*
    $sq2 = mysql_query("Select b.id_cust,b.namaperusahaan,b.keterangan
	,(select ifnull(sum(a.totalqty),0) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as totalqty 
	,(select sum(ifnull(a.faktur,0)) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as faktur
	,(select sum(ifnull(a.totalfaktur,0)) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as totalfaktur
	,(select round(sum(ifnull(a.faktur,0))/sum(ifnull(a.totalqty,0)),0) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as harga_satuan
	,(select round(sum(ifnull(a.faktur,0)/1.11),0) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as DPP
	,(select round(sum(ifnull(a.faktur,0)/1.11)*0.11,0) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as PPN
	,(select sum(ifnull(a.piutang,0)) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as piutang
	,(select sum(ifnull(a.biaya,0)) from trjual a where b.id=a.id_customer ".$where." group by a.id_customer) as biaya
	 from tblpelanggan b where b.type=2 order by b.id_cust asc");
    */
	$sql_data="Select b.id_cust,b.namaperusahaan,b.keterangan,jual.total_qty,jual.faktur,jual.totalfaktur
	,jual.harga_satuan,jual.dpp,jual.ppn,jual.piutang,jual.biaya from tblpelanggan b 
	left join 
	(select id_customer,sum(ifnull(totalqty,0)) as total_qty
	,sum(ifnull(faktur,0)) as faktur 
	,sum(ifnull(totalfaktur,0)) as totalfaktur 
	,round(sum(ifnull(faktur,0))/sum(ifnull(totalqty,0)),0) as harga_satuan
	,round(sum(ifnull(faktur,0)/1.11),0)as DPP
	,round(sum(ifnull(faktur,0)/1.11)*0.11,0) as PPN
	,sum(ifnull(piutang,0)) as piutang
	,sum(ifnull(biaya,0)) as biaya 
	from trjual 
	" .$where. "
	group by id_customer ) as jual on b.id = jual.id_customer 
	where b.type = 2 
	order by b.id_cust asc";
	//var_dump($sql_data);die;
	
	$sq2 = mysql_query($sql_data);
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;

  ?>
    <tr>
      <td class="style9"><span class="style9">
        <?=$nomer;?>
      </span></td>
      <td class="style9"><?=$rs2['id_cust'];?></td>
      <td class="style9"><?=$rs2['namaperusahaan'];?></td>
      <td class="style9"><?=$rs2['keterangan'];?></td>
      
	  
	  <td class="style9"><div align="center">ARROW
	  </div></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['totalqty'],0);?>
      </div></td>
      <td class="style9"><div align="right">
	  PCS
      </div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['harga_satuan'],0);?>
      </div></td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['faktur'],0);?>
      </div></td>
      <td class="style9">&nbsp;</td>
      <td class="style9">&nbsp;</td>
      <td class="style9"><div align="right">
	  <?=number_format($rs2['DPP'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['PPN'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['faktur'],0);//DPP+PPN?>
      </div></td>
	  <td class="style9"><div align="right">
	  <?=number_format($rs2['biaya'],0);?>
      </div></td>
	  <td class="style9"><div align="right">
          <?=number_format($rs2['totalfaktur'],0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['subtotal'];
	$ongkir=$rs['biaya'];
	$tunai_vw=$rs['tunai'];
	$transfer_vw=$rs['transfer'];
	$totqty+=$rs2['totalqty'];
	$totaldpp+=$rs2['DPP'];
	$totalppn+=$rs2['PPN'];
	$grand_faktur+=$rs2['faktur'];
	$totalbiaya+=$rs2['biaya'];
	$totalpiutang+=$rs2['piutang'];
  }
  ?>
    <tr>
      <td height="2" colspan="16" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="5"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($totqty,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">PCS</div>
	</td>
	<td>&nbsp;
	</td>
	<td class="style9"><div align="right">
          <?=number_format($grand_faktur,0);?>
    </div>
	</td>
	<td class="style9">&nbsp;</td>
    <td class="style9">&nbsp;</td>
    <td class="style9"><div align="right">
          <?=number_format($totaldpp,0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($totalppn,0);?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=number_format($grand_faktur,0);//dpp+ppn?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=number_format($totalbiaya,0);?>
    </div>
	</td>
	
	<td class="style9"><div align="right">
          <?=$totalpiutang;?>
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
