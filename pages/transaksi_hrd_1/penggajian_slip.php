<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
.style9 {
font-size: 9pt; 
font-family:Tahoma;
}
.style9b {color: #000000;
	font-size: 9pt;
	font-weight: bold;
	font-family: Tahoma;
}.style99 {font-size: 13pt; font-family:Tahoma; padding-bottom: 5px;}
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
.style_title {	color: #000000;
	font-size: 10pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	
	
	padding: 2px;
}
.style_title2 {	color: red;
	font-size: 10pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	
	
	padding: 2px;
}
.style_title3 {	color: #000000;
	font-size: 10pt;	
	font-family: Tahoma;
	
	padding: 2px;
}
.style_title4 {	color: #000000;
	font-size: 10pt;	
	font-family: Tahoma;
	border-bottom: 1px solid black;
	
	padding: 2px;
}
.style_title_left {	color: #000000;
	font-size: 10pt;	
	font-family: Tahoma;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	border-left: 1px solid black;
	
	padding: 3px;
}
.style_detail {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-bottom: 1px solid black;
	border-right: 1px solid black;
	padding: 2px;
}
.style_detail_left {	color: #000000;
	font-size: 9pt;	
	font-family: Tahoma;
	border-bottom: 1px solid black;
	border-left: 1px solid black;
	border-right: 1px solid black;
	padding: 2px;
}
@page {
        size: A4;
        margin: 15px;
    }
    body { -webkit-print-color-adjust: exact; }
#container{width:100%;}
#timestamp{font-size: 9pt;  
  font-family: Tahoma;}
#left{float:left;width:50%;}
#right{float:right;width:50%;}
</style>
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	$id=$_GET['penggajian'];
    $karyawan=$_GET['karyawan'];
	
    $sql_title = "SELECT a.id_karyawan, a.nama_karyawan, c.nama_dept FROM hrd_karyawan a LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan = '$karyawan' ";
	
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 


	$sql_title2 = "SELECT date_format(last_day(tgl_upah_end),'%d %M %Y') as lastday,date_format(tgl_upah_end,'%M %Y') as monthnow, date_format(tgl_pembayaran,'%d %M %Y') as tanggal_pembayaran  FROM hrd_penggajian a WHERE penggajian_id = '$id' ";

	$data_title2=mysql_query($sql_title2);
	$rs_title2 = mysql_fetch_array($data_title2); 
	
	$Month = STRTOUPPER($rs_title2['monthnow']);
	$lastDayThisMonth = STRTOUPPER($rs_title2['lastday']);
	$tanggal_pembayaran = STRTOUPPER($rs_title2['tanggal_pembayaran']);

	$totalpendapatan=0;
	$totalpotongan=0;
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			PT AGUNG KEMUNINGWIJAYA - Slip Gaji <?=$Month?> </strong></td>
          </tr>
          <tr>
            <td width="100%" class="style9b">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=$rs_title['nama_karyawan']?></td>
		  </tr>
          <tr>
            <td width="100%" class="style9b">Kode Divisi &nbsp;: <?=$rs_title['nama_dept']?></td>
		  </tr>
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  	<td width="46%" style='vertical-align:top'>
  		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  			<tr>
		       <th colspan="3" class="style9"><div align="center">Pendapatan</div></th>
		    </tr>
		   	<tr>
		   		<th width="5%" class="style_title_left" style='background-color: #E9ECEB;'><div align="center">No</div></th>
			    <th width="25%" class="style_title" style='background-color: #E9ECEB;'><div align="center">Jenis Pendapatan</div></th>
			    <th width="17%" class="style_title" style='background-color: #E9ECEB;'><div align="center">Nilai Pendapatan</div></th>
		   	</tr>
		   	<?php
		   	$i = 1;
		   	$sql_detail = "SELECT e.nama_penpot as pendapatan, IFNULL(d.subtotal,0) as totalpendapatan FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan=$karyawan AND e.type='pendapatan'";
		   	$sq2 = mysql_query($sql_detail);
		   	while($rs2=mysql_fetch_array($sq2))
			{ 	
				if($rs2['totalpendapatan'] != '0'){
				?>
				<tr>
			    	<td width="5%" class="style_detail_left"><div align="center"><?=$i?></div></td>
			      	<td width="25%" class="style_detail"><div align="left"><?=$rs2['pendapatan']?></div></td>
			      	<td width="17%" class="style_detail"><div align="right"><?=number_format($rs2['totalpendapatan'],0)?></div></td>
			     </tr>
				<?php
				$i++;
				$totalpendapatan += $rs2['totalpendapatan'];
				}
			}
			for($j=$i;$j<=8;$j++){
		  	  ?>
			    <tr>
			    	<td width="5%" class="style_detail_left">&nbsp;</td>
			      	<td width="25%" class="style_detail"></td>
			      	<td width="17%" class="style_detail"></td>
			    </tr>
			<?php
		  }
		   	?>
		   	<tr>
		   		<td width="5%" class="style_detail_left" colspan=2 style='background-color: #E9ECEB;'><div align="center">TOTAL :</div></td>
	      		<td width="17%" class="style_detail"><div align="right"><?=number_format($totalpendapatan,0)?></div></td>
		   	</tr>
  		</table>
  	</td>
  	<td width="5%">&nbsp;</td>
  	<td width="46%" style='vertical-align:top'>
  		<table width="100%" border="0	" align="center" cellpadding="0" cellspacing="0">
  			<tr>
		       <th colspan="3" class="style9"><div align="center">Potongan</div></th>
		    </tr>
		   	<tr>
		   		<th width="5%" class="style_title_left" style='background-color: #E9ECEB;'><div align="center">No</div></th>
			    <th width="25%" class="style_title" style='background-color: #E9ECEB;'><div align="center">Jenis Potongan</div></th>
			    <th width="17%" class="style_title" style='background-color: #E9ECEB;'><div align="center">Nilai Potongan</div></th>
		   	</tr>
		   	<?php
		   	$i = 1;
		   	$sql_detail = "SELECT e.nama_penpot as potongan, IFNULL(d.subtotal,0) as totalpotongan FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan=$karyawan AND e.type='potongan'";
		   	$sq2 = mysql_query($sql_detail);
		   	while($rs2=mysql_fetch_array($sq2))
			{ 	
				if($rs2['totalpotongan'] != '0'){
				?>
				<tr>
			    	<td width="5%" class="style_detail_left"><div align="center"><?=$i?></div></td>
			      	<td width="25%" class="style_detail"><div align="left"><?=$rs2['potongan']?></div></td>
			      	<td width="17%" class="style_detail"><div align="right"><?=number_format($rs2['totalpotongan'],0)?></div></td>
			     </tr>
				<?php
				$i++;
				$totalpotongan += $rs2['totalpotongan'];
			}
			}
			for($j=$i;$j<=8;$j++){
		  	  ?>
			    <tr>
			    	<td width="5%" class="style_detail_left">&nbsp;</td>
			      	<td width="25%" class="style_detail"></td>
			      	<td width="17%" class="style_detail"></td>
			    </tr>
			<?php
		  }
		   	?>
		   	<tr>
		   		<td width="5%" class="style_detail_left" colspan=2 style='background-color: #E9ECEB;'><div align="center">TOTAL :</div></td>
	      		<td width="17%" class="style_detail"><div align="right"><?=number_format($totalpotongan,0)?></div></td>
		   	</tr>
  		</table>
  	</td>
  </tr>

<tr>
	<td colspan=2 class="style_title3">&nbsp;</td>
	<td class="style_title3">
		<div id="container">
			<div id="left">Total Pendapatan</div><div id="right" align="right"><?=number_format($totalpendapatan,0)?></div>
		</div>
	</td>
</tr>
<tr>
	<th rowspan="3" class="style_title2"><div align="center">Pembayaran dilakukan pada tanggal
		<br>
	<?=$tanggal_pembayaran?></div></th>
	<td class="style_title3">&nbsp;</td>
	<td class="style_title4">
		<div id="container">
			<div id="left">Total Potongan</div><div id="right" align="right"><?=number_format($totalpotongan,0)?></div>
		</div>
	</td>
</tr>
<tr>
	<td class="style_title3">&nbsp;</td>
	<td class="style_title3">
		<div id="container">
			<div id="left">Gaji Bersih</div><div id="right" align="right"><?=number_format($totalpendapatan-$totalpotongan,0)?></div>
		</div>
	</td>
</tr>
<tr>
	<td class="style_title3">&nbsp;</td>
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

window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>