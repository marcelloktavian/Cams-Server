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
	
	$sql = mysql_query("SELECT sum(a.totalqty) as totalqty,sum(a.totalfaktur) as totalfaktur,sum(a.faktur) as faktur,sum(a.tunai) as tunai,sum(a.transfer) as transfer,sum(a.piutang) as piutang,sum(a.biaya) as biaya, sum(a.piutang-a.pelunasan) as saldo FROM trjual a 
    where ((a.piutang-a.pelunasan) > 0)");
	$rs = mysql_fetch_array($sql);
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="50%" class="style99" colspan="6"><strong>
			<a href="javascript:window.print()">CETAK</a> LAPORAN STOK GUDANG TC KELIR dan TC PUTIH</strong></td>
            
          </tr>
          <tr>
            <td width="19%" class="style9"><a href="report_gudangtc_printxl.php?start=<?php echo"".$tglstart;?>&end=<?php echo"".$tglend;?>&id=<?php echo"".$id_pelanggan;?>">Export ke Excell</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
                       
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>   
            <td class="style9" width="30%"class="style9">&nbsp;</td>           
		  </tr>
          <tr>
            <td colspan="4" class="style16"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10%" class="style9b"><div align="right">No.</div></td>
      <td width="30%" class="style9b"><div align="center">ID</div></td>
 	  <td width="30%" class="style9b"><div align="center">Nama Barang</div></td>
      <td width="30%" class="style9b"><div align="right">Stok</div></td>   
    </tr>
    <tr>
      <td colspan="4" class="style16"><hr /></td>
    </tr>
    <?
		
	$where = " where (p.deleted=0) and (p.id_jenis=3 or p.id_jenis=4) ";
	if($tglstart != null){
	$where .= " AND DATE(tgl_trans) <= STR_TO_DATE('$tglstart','%d/%m/%Y') ";
			
	}
	else
	{
	$where .= "";
	}
	
	$sq2 = mysql_query("SELECT p.id_barang,j.id as nomor,j.nm_barang, j.hrg_jual,sum(p.stok) as stok FROM `stok_gudang` p Left Join `barang` j on (p.id_barang=j.id_barang)  
    ".$where." GROUP BY p.id_barang ORDER BY j.id");
	
	$i=1;
	$nomer=0;
	while($rs2=mysql_fetch_array($sq2))
	{ $nomer++;

  ?>
    <tr>
      <td class="style9"><div align="right">
	  <?=$rs2['nomor'];?>
	  </div>
	  </td>
      <td class="style9"><div align="center"><?=$rs2['id_barang'];?>
	  </div></td>
      <td class="style9"><div align="center"><?=$rs2['nm_barang'];?>
	  </div></td>
      <td class="style9"><div align="right">
        <?=number_format($rs2['stok'],0);?>
      &nbsp;&nbsp;pcs</div></td>
      
    </tr>  <?
	$grand_qty+=$rs2['stok'];
	
  }
  ?>
    <tr>
      <td height="2" colspan="4" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="3"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($grand_qty,0);?>&nbsp;&nbsp;pcs
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
