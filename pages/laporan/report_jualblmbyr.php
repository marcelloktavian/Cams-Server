<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>

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
			<a href="javascript:window.print()">CETAK</a> LAPORAN PENJUALAN BELUM LUNAS</strong></td>
			<td style="text-align:right">
				<div id="timestamp">
				<?php
					date_default_timezone_set('Asia/Jakarta');
					echo $timestamp = date('d/m/Y H:i:s');
				?>
				</div>	
				
			</td>
            
          </tr>
          <tr>
            <td width="19%" class="style9">&nbsp;</td>
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>
            <td class="style9" width="30%">&nbsp;</td>
            <td class="style9" width="19%">&nbsp;</td>           
            <td class="style9" width="1%"><div align="center">&nbsp;</div></td>   
            <td class="style9" width="30%"class="style9">&nbsp;</td>           
		  </tr>
          <tr>
            <!--<td colspan="10" class="style9"><hr /></td>-->
            <td colspan="9" class="style9"><hr /></td>
          </tr>
          		  
  </table>  
  
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="2%" class="style9b"><div align="left">No.</div></td>
      <td width="10%" class="style9b"><div align="left">Tanggal</div></td>
 	  <td width="8%" class="style9b"><div align="center">No.Invoice</div></td>
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
      <!--<td colspan="11" class="style16"><hr /></td>-->
      <td colspan="9" class="style16"><hr /></td>
    </tr>
    <?
		
	//$sq2 = mysql_query("select a.id_detail,a.id_barang, b.nm_barang, a.qty, a.harga, (a.qty * a.harga) as subtotal from trjual_detail a, barang b where a.id_barang=b.id_barang and a.id_trans ='".$_GET['id_trans']."'");
	
	//$id_faktur=TSO18020021;
	$sq2 = mysql_query("select b.namaperusahaan,a.id_trans,a.kode, date_format(a.tgl_trans,'%d-%m-%Y') as tgl_trans, a.totalqty,a.faktur, a.totalfaktur, a.tunai,a.transfer,a.piutang,(a.piutang-a.pelunasan) as saldo,a.biaya from trjual a left join tblpelanggan b on b.id = a.id_customer
	where ((a.piutang-a.pelunasan) > 0)");
//	"2017-06-15", "%M %d %Y"
	
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
      <td class="style9"><div align="center"><?=$rs2['id_trans'];?>
	  </div></td>
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
	  <!--
      <td class="style9"><div align="right">
          <?//=number_format($rs2['tunai'],0);?>
      </div></td>
       <td class="style9"><div align="right">
          <?//=number_format($rs2['transfer'],0);?>
      </div></td>
	  -->
      <td class="style9"><div align="right">
          <?=number_format($rs2['saldo'],0);?>
      </div></td>
    </tr>  <?
	$total2+=$rs2['subtotal'];
	$ongkir=$rs['biaya'];
	$tunai_vw=$rs['tunai'];
	$transfer_vw=$rs['transfer'];
	$piutang_vw=$rs['piutang'];
	//$totqty+=$rs2['kuantum'];
  }
  ?>
    <tr>
      <!--<td height="2" colspan="11" class="style9"><hr /></td>-->
      <td height="2" colspan="9" class="style9"><hr /></td>
    </tr>
	<tr>
	<td class="style9" colspan="4"><div align="right">Total</div></td>
	<td class="style9"><div align="right">
          <?=number_format($rs['totalqty'],0);?>&nbsp;&nbsp;pcs
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($rs['faktur'],0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($rs['biaya'],0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?=number_format($rs['totalfaktur'],0);?>
    </div>
	</td>
	<!--
	<td class="style9"><div align="right">
          <?//=number_format($rs['tunai'],0);?>
    </div>
	</td>
	<td class="style9"><div align="right">
          <?//=number_format($rs['transfer'],0);?>
    </div>
	</td>
	-->
	<td class="style9"><div align="right">
          <?=number_format($rs['saldo'],0);?>
    </div>
	</td>
	</tr>
	
  </table>
  
  <div align="center"></div>
</form>

<script language="javascript">
		$(document).ready(function() {
    	setInterval(timestamp, 1000);
});

function timestamp() {
    $.ajax({
        url: '../timestamp.php',
        success: function(data) {
            $('#timestamp').html(data);
        },
    });
}

//var total=getNumber(document.getElementById("total").innerHTML);
//document.getElementById("terbilang").innerHTML = terbilang(total);

//dimatikan agar bisa view dulu...window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
