<style>
  .nomor{
    text-align: center;
  }
  .harga{
    width: calc(100% - 2em);
    display: flex;
    flex-direction: row;
    justify-content: space-between;
  }
  .header{
    border-top: 2px solid black !important;
    border-bottom: 2px solid black !important;
  }
  table{
    width: 90%;
    border-collapse: collapse;
  }
  table tr td{
    padding: 0.5em 1em;
    border: 1px dotted lightgrey;
  }
  @page {
        size: 8.5in 5.5in;
        size: landscape;
    }
  .title{
    margin: 1em 5%;
    font-weight: bold;
    font-size: large;
    text-decoration: underline;
  }
</style>

<?php
include '../../include/koneksi.php';

$tgl_jto = $_GET['jto'];
$ids = $_GET['ids'];
$filter = "";

$sql_aplist = "SELECT * FROM (
  SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM
  (
      SELECT * FROM (
          SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
          LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
              SELECT DISTINCT a.id_invoice FROM det_ap a
              LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
          ) AND a.id_supplier IN (".substr($ids,0, -1).") AND a.deleted = 0 GROUP BY a.id_supplier
      ) AS x 
      LEFT JOIN (
          SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
      ) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
      LEFT JOIN (
          SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
      ) AS z ON x.supplier = z.nama_supplier
  ) AS R1 
  LEFT JOIN (
      SELECT * FROM (
          SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
          LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
              SELECT DISTINCT a.id_invoice FROM det_ap a
              LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
          ) AND a.id_supplier IN (".substr($ids,0, -1).") AND a.deleted = 0 GROUP BY a.id_supplier
      ) AS x 
      LEFT JOIN (
          SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
      ) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
      LEFT JOIN (
          SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
      ) AS z ON x.supplier = z.nama_supplier
  ) AS R2 ON R1.id_supplier = R2.id_supplier
  UNION
  SELECT R1.id_supplier AS x_id_supplier, R1.supplier AS x_supplier, R1.telp AS x_telp, R1.no_akun AS x_no_akun, R1.bank AS x_bank, R1.rekening AS x_rekening, R1.total_hutang_jto AS x_total_hutang, R1.total_payment AS x_total_payment, R2.id_supplier AS y_id_supplier, R2.supplier AS y_supplier, R2.telp AS y_telp, R2.no_akun AS y_no_akun, R2.bank AS y_bank, R2.rekening AS y_rekening, R2.total_hutang_jto AS y_total_hutang, R2.total_payment AS y_total_payment FROM (
      SELECT * FROM (
          SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
          LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
              SELECT DISTINCT a.id_invoice FROM det_ap a
              LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` <= '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
          ) AND a.id_supplier IN (".substr($ids,0, -1).") AND a.deleted = 0 GROUP BY a.id_supplier
      ) AS x 
      LEFT JOIN (
          SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
      ) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
      LEFT JOIN (
          SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
      ) AS z ON x.supplier = z.nama_supplier
  ) AS R1 
  RIGHT JOIN (
      SELECT * FROM (
          SELECT a.id_supplier, a.supplier, b.telp, b.bank, b.rekening, SUM(a.qty) AS total_qty_jto, SUM(a.total) AS total_hutang_jto FROM mst_invoice a
          LEFT JOIN mst_supplier b ON a.id_supplier = b.id WHERE a.id IN (
              SELECT DISTINCT a.id_invoice FROM det_ap a
              LEFT JOIN mst_ap b ON a.id_ap = b.id WHERE a.`tanggal_jatuh_tempo` > '".$tgl_jto."' AND posting = 1 GROUP BY a.id_ap
          ) AND a.id_supplier IN (".substr($ids,0, -1).") AND a.deleted = 0 GROUP BY a.id_supplier
      ) AS x 
      LEFT JOIN (
          SELECT a.keterangan, SUM(a.total_kredit) AS total_payment FROM jurnal a WHERE a.status = 'AP' GROUP BY a.keterangan
      ) AS y ON y.keterangan LIKE CONCAT('Pembayaran Hutang Dagang - ', x.supplier, '%') 
      LEFT JOIN (
          SELECT DISTINCT no_akun, nama_akun, nama_supplier FROM mst_ap
      ) AS z ON x.supplier = z.nama_supplier
  ) AS R2 ON R1.id_supplier = R2.id_supplier
) AS DerivedTableAlias
HAVING (COALESCE(x_total_hutang,0)+COALESCE(y_total_hutang,0)) <> COALESCE(x_total_payment,0) ";

$query = mysql_query($sql_aplist);

?>
<div class="title">PERMINTAAN PEMBAYARAN HUTANG</div>
<center>
<table>
  <tr class="header">
    <td width="5%">NO</th>
    <td width="35%">SUPPLIER</th>
    <td width="30%">BANK</th>
    <td width="15%">REKENING</th>
    <td width="20%">TOTAL SISA</th>
  </tr>
<?php

$total_jto = 0; $total_belum_jto = 0; $grand_total_ap = 0; $total_remaining = 0; $total_payment = 0;

$i = 1;
while($line = mysql_fetch_array($query)){

  $row_payment = (isset($line['x_total_payment']) && $line['x_total_payment'] != null ? $line['x_total_payment'] : $line['y_total_payment']);
  $row_total =  (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']))+(!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
  $row_sisa = $row_total-$row_payment;

  if($row_sisa == -0){
    $row_sisa = 0;
  }

  if($row_sisa > 0){
    $nomor_akun = (isset($line['x_no_akun']) && $line['x_no_akun'] != null ? $line['x_no_akun'] : $line['y_no_akun']);

    $no_telp = (isset($line['y_telp']) && $line['y_telp'] != null ? $line['y_telp'] : $line['x_telp']);
    ?>
      <tr>
        <td class="nomor" width="5%"><?= $i++ ?>.</td>
        <td width="35%"><?= isset($line['x_supplier']) && $line['x_supplier'] != null ? $line['x_supplier'] : $line['y_supplier'] ?></td>
        <td width="30%"><?= isset($line['x_bank']) && $line['x_bank'] != null ? $line['x_bank'] : $line['y_bank'] ?></td>
        <td width="15%"><?= isset($line['x_rekening']) && $line['x_rekening'] != null ? $line['x_rekening'] : $line['y_rekening'] ?></td>
        <td class="harga" width="20%"><div>Rp.</div><div><?= number_format($row_sisa) ?></div></td>
      </tr>
    <?php

    $total_jto += (!isset($line['x_total_hutang']) ? 0 : ($line['x_total_hutang']==null ? 0 : $line['x_total_hutang']));
    $total_belum_jto += (!isset($line['y_total_hutang']) ? 0 : ($line['y_total_hutang']==null ? 0 : $line['y_total_hutang']));
    $total_remaining += $row_sisa;
    $total_payment += $row_payment;
  }
}
?>
<tr>
    <td colspan="4">TOTAL SISA</th>
    <td class="harga header"><div>Rp.</div><div><?= number_format($total_remaining) ?></div></th>
</tr>
</table>
</center>

<script>
  window.print();
</script>