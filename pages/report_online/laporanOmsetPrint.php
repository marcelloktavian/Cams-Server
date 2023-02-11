<style type="text/css">
.style9 {font-size: 10pt; font-family:Arial}
.style99 {font-size: 13pt; font-family:Arial}
.style10 {font-size: 10pt; font-family:Arial; text-align:right}
.style19 {font-size: 10pt; font-weight: bold; font-family:Arial; font-style:italic}
.style11 {
	color: #000000;
	font-size: 8pt;
	font-weight: normal;
	font-family: Arial;
	font-style:italic;
}
.style20 {font-size: 8pt; font-family:Arial}
.style16 {font-size: 9pt; font-family:Arial}
.style21 {color: #000000;
	font-size: 10pt;
	font-weight: bold;
	font-family: Arial;
}
.style18 {color: #000000;
	font-size: 9pt;
	font-weight: normal;
	font-family: Arial;
}
.style6 {color: #000000;
	font-size: 9pt;
	font-weight: bold;
	font-family: Arial;
}
.style19b {	color: #000000;
	font-size: 11pt;
	font-weight: bold;
	font-family: Arial;
}
.style19h {	color: #000000;
	font-size: 15pt;
	font-weight: bold;
	font-family: Arial;
}
@page {
        size: lanscape;
        margin: 15px;
    }
</style>

<!-- php disini -->
<?php
  error_reporting(0);
// koneksi dengan database
  require "../../include/koneksi.php";
// variable  
$jual ='';
$beli ='';
$operasional ='';
$laba_rugi ='';
 $year = "";
 $year = $_GET['year'];
// 
  $sql="SELECT 'JANUARI' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=1 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=1 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=1 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=1 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL 
  SELECT 'FEBUARI' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=2 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=2 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=2 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=2 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL 
  SELECT 'MARET' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=3 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=3 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=3 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=3 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL 
  SELECT 'APRIL' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=4 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=4 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=4 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=4 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL 
  SELECT 'MEI' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=5 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=5 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=5 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=5 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL 
  SELECT 'JUNI' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=6 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=6 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=6 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=6 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'JULI' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=7 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=7 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=7 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=7 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'AGUSTUS' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=8 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=8 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=8 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=8 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'SEPTEMBER' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=9 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=9 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=9 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=9 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'OKTOBER' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=10 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=10 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=10 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=10 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'NOVEMBER' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=11 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=11 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=11 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=11 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi
  UNION ALL
  SELECT 'DECEMBER' AS bulan,
  ( SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=12 AND YEAR(lastmodified)=$year ) AS jual,
  ( '0' ) AS beli,
  ('0' ) as biayaPajak,
  ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=12 AND YEAR(m.tanggal)=$year ) AS operasional,
  ( (SELECT SUM(total) FROM olnso WHERE deleted=0 AND MONTH(lastmodified)=12 AND YEAR(lastmodified)=$year) - ( SELECT SUM(d.jumlah) FROM biayaoperasional_det d LEFT JOIN biayaoperasional m ON d.id_parent=m.id WHERE MONTH(m.tanggal)=12 AND YEAR(m.tanggal)=$year ) ) AS laba_rugi";
    // eksekusi query
    // var_dump($sql);die;
    $result= mysql_query($sql);
?>
<form id="form" name="form" action="" method="post"  onSubmit="return validasi(this)">
<div align='left' class='style19h'>LAPORAN LABA/RUGI</div>
<div align='left' class='style18'>Tahun <strong> <?= $year ?> </strong></div>
    <table border=1 width="100%" height="100%">
    <tr>
    <!-- 1 -->
        <td colspan='3'><div class="style19b" align='left'>BULAN</div></td>
        <td colspan='3'><div class="style19b" align='center'>JUAL</div></td>
        <td colspan='3'><div class="style19b" align='center'>BELI</div></td>
        <td colspan='3'><div class="style19b" align='center'>BIAYA PAJAK</div></td>
        <td colspan='3'><div class="style19b" align='center'>OPERASIONAL</div></td>
        <td colspan='3'><div class="style19b" align='center'>LABA/RUGI</div></td>
    </tr>
    <tr>
    <?php
    // pengulangan data pajak
    while ($data = mysql_fetch_array($result)):
    ?>
    <!-- 2 -->
        <!-- bulan -->
        <td colspan='3'><div class='style9'> <?= $data['bulan'] ?> </div></td>
        <!-- ############## -->
        <!-- JUAL -->
        <td colspan='3'><div class='style9' align='right'><?=  number_format($jual=$data['jual'],0); ?></div></td>
        <!-- ############## -->
        <!-- BELI -->
        <td colspan='3'><div class='style9' align='right'><?=   number_format($beli=$data['beli'],0); ?></div></td>
        <!-- ############## -->
        <!-- BIAYA PAJAK -->
        <td colspan='3'><div class='style9' align='right'><?=  number_format($pajak =$data['biayaPajak'],0); ?></div></td>
        <!-- ############## -->
        <!-- OPERASIONAL -->
        <td colspan='3'><div class='style9' align='right'><?=  number_format($opr =$data['operasional'],0); ?></div></td>
        <!-- ############## -->
        <!-- LABA RUGI -->
        <td colspan='3'><div class='style9' align='right'><?=   number_format($labarugi=$jual-$beli-$pajak-$opr,0); ?></div><?= $labarugi>0?'(+)':'(-)' ?></td>
        <!-- ############# -->
    </tr>
    <?php
    // define untuk total
    $jualm += $data['jual'];
    $belim += $data['beli'];
    $pajakm += $data['biayaPajak'];
    $operasional += $data['operasional'];
    $laba_rugi += $labarugi;
    endwhile;
    ?>
    <!-- 3 -->
    <tr>
        <td colspan='3' align='right'><div class='style19b'>TOTAL</div></td>
        <td colspan='3' align='right'><div class='style19b'><?= number_format($jualm,2) ?></div></td>
        <td colspan='3' align='right'><div class='style19b'><?= number_format($belim,2) ?></div></td>
        <td colspan='3' align='right'><div class='style19b'><?= number_format($pajakm,2) ?></div></td>
        <td colspan='3' align='right'><div class='style19b'><?= number_format($operasional,2) ?></div></td>
        <td colspan='3' align='right'><div class='style19b'><?= number_format($laba_rugi,2) ?></div></td>
    </tr>
    </table>
</form>
<script language="javascript">
 window.print();
</script>
