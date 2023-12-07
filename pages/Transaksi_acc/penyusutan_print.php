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
	.style_title {	color: #000000;
		font-size: 11pt;	
		font-family: Tahoma;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		border-right: 1px solid black;
		padding: 3px;
	}
	.style_title_left {	color: #000000;
		font-size: 11pt;	
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
		border-bottom: 1px dashed black;
		border-right: 1px solid black;
		padding: 3px;
	}
	.style_detail_left {	color: #000000;
		font-size: 9pt;	
		font-family: Tahoma;
		border-bottom: 1px dashed black;
		border-left: 1px solid black;
		border-right: 1px solid black;
		padding: 3px;
	}
	@page {
			size: A4;
			margin: 15px;
			size: landscape;
		}
	@media print{@page {size: landscape}}
</style>

<?php 
error_reporting(0);
include("../../include/koneksi.php");
include("../../include/config.php");
$tglstart = $_GET['startdate'];
$tglend = $_GET['enddate'];
$tipe = $_GET['tipe'];

$query = "SELECT *,IF(acc = '05.07.00000','Biaya Langsung','Biaya Tidak Langsung') as tipe,COALESCE ( total_aset - total_penyusutan, 0 ) AS nilai_sisa_aset 
FROM ( SELECT a.tgl AS tanggal_jurnal,SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' disusutkan ', - 1 ), ' durasi', 1 ) AS nama_aset,SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' penyusutan ', - 1 ), ' Bulan', 1 ) AS durasi_penyusutan,b.nama_akun AS nama_akun_aset,b.debet AS total_aset FROM cron_jurnal a LEFT JOIN cron_jurnal_detail b ON a.`id` = b.`id_parent` WHERE a.`status` = 'PEMBELIAN ASET' AND b.nama_akun LIKE 'Aset Tetap - %' AND a.deleted = 0 ) 
AS a LEFT JOIN ( SELECT SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, 'Penyusutan ', - 1 ), ' ke ', 1 ) AS nama_aset_penyusutan,b.nama_akun,SUM( IF(b.nama_akun like 'Akumulasi Depresiasi & Amortisasi - %',b.kredit,0)) AS total_penyusutan ,IF(b.nama_akun not like 'Akumulasi Depresiasi & Amortisasi - %',b.no_akun,'') AS acc,SUM(IF( b.nama_akun LIKE 'Akumulasi Depresiasi & Amortisasi - %', 1, 0 )) as penyusutan_berjalan FROM cron_jurnal a LEFT JOIN cron_jurnal_detail b ON a.`id` = b.`id_parent` WHERE a.`status` = 'PENYUSUTAN ASET' AND DATE ( tgl ) <= CURDATE() AND a.deleted = 0 GROUP BY SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, 'Penyusutan ', - 1 ), ' ke ', 1 ) ) AS b ON a.nama_aset = b.nama_aset_penyusutan 
LEFT JOIN ( SELECT SUBSTRING_INDEX( SUBSTRING_INDEX( a.keterangan, ' Aset - ', - 1 ), ' Penyusutan ', 1 ) AS nama_aset_pemberhentian FROM jurnal a WHERE a.`status` = 'LIKUIDITAS ASET' AND a.keterangan LIKE 'Likuidasi Aset - %' AND a.deleted = 0 ) AS c ON TRIM( a.nama_aset )= TRIM( c.nama_aset_pemberhentian ) ";
$where = "WHERE nama_aset_pemberhentian IS NULL AND tanggal_jurnal BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') ";

if ($tipe) {
    $where .= "AND acc = '$tipe'";
}

?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			AKTIVA REPORT</strong></td>
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
            <td width="100%" class="style_tgl" colspan="7"><div id='totalqty'>Dari: <?= $tglstart;?>&nbsp;-&nbsp;<?= $tglend ?>
            </div></td>           
		  </tr>          		  
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th width="3%" class="style_title_left"><div align="center">No.</div></td>
      <th width="15%" class="style_title"><div align="left">Nama Aset</div></td>
	  <th width="5%" class="style_title"><div align="center">Tanggal Pembelian</div></td>
	  <th width="3%" class="style_title"><div align="center">Durasi Penyusutan</div></td>
	  <th width="7%%" class="style_title"><div align="center">Nilai Beli DPP</div></td>
	  <th width="7%" class="style_title"><div align="center">Total Penyusutan</div></td>
	  <th width="10%" class="style_title"><div align="center">Nilai Sisa Aset</div></td>
	  <th width="10%" class="style_title"><div align="center">Tipe Biaya</div></td>
      <th width="3%" class="style_title"><div align="center">Total Penyusutan Saat Ini</div></td>
    </tr>

    <?php

    $data = $db->query($query.$where)->fetchAll(PDO::FETCH_ASSOC);

    $index = 1;
    $dpp = 0;
    $penyusutan = 0;
    $sisa = 0;

    foreach ($data as $line) {
        echo "<tr>";
            echo "<td class='style_detail_left'><div align='left'>$index</div></td>";
            echo "<td class='style_detail'><div align='left'>".$line['nama_aset']."</div></td>";
            echo "<td class='style_detail'><div align='left'>".$line['tanggal_jurnal']."</div></td>";
            echo "<td class='style_detail'><div align='left'>".$line['durasi_penyusutan']."</div></td>";
            echo "<td class='style_detail'><div align='left'>".number_format($line['total_aset'])."</div></td>";
            echo "<td class='style_detail'><div align='left'>".number_format($line['total_penyusutan'])."</div></td>";
            echo "<td class='style_detail'><div align='left'>".number_format($line['total_aset']-$line['total_penyusutan'])."</div></td>";
            echo "<td class='style_detail'><div align='left'>".$line['tipe']."</div></td>";
            echo "<td class='style_detail'><div align='left'>".$line['penyusutan_berjalan']."</div></td>";
        echo "</tr>";

        $index ++;
        $dpp += $line['total_aset'];
        $penyusutan += $line['total_penyusutan'];
        $sisa += ($line['total_aset'] - $line['total_penyusutan'] ); 
    }
    ?>
	<tr>
		<td class="style9" colspan="7"><div align="right">Total DPP</div></td>
		<td class="style9" colspan="2"><div align="right">&nbsp;&nbsp;<?= number_format($dpp); ?></div></td>
	</tr>
	<tr>
		<td class="style9" colspan="7"><div align="right">Total Penyusutan</div></td>
		<td class="style9" colspan="2"><div align="right">&nbsp;&nbsp;<?= number_format($penyusutan); ?></div></td>
	</tr>
	<tr>
		<td class="style9" colspan="7"><div align="right">Total Sisa Aset</div></td>
		<td class="style9" colspan="2"><div align="right">&nbsp;&nbsp;<?= number_format($sisa); ?></div></td>
	</tr>
</table>

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