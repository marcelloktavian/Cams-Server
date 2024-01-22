<title>Laporan OLN + B2B</title>
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
  .style9 {
    font-size: 9pt;
    font-family: Tahoma;
  }

  .style9b {
    color: #000000;
    font-size: 9pt;
    font-weight: bold;
    font-family: Tahoma;
  }

  .style99 {
    font-size: 13pt;
    font-family: Tahoma
  }

  .style10 {
    font-size: 10pt;
    font-family: Tahoma;
    text-align: right
  }

  .style19 {
    font-size: 10pt;
    font-weight: bold;
    font-family: Tahoma;
    font-style: italic
  }

  .style11 {
    color: #000000;
    font-size: 8pt;
    font-weight: normal;
    font-family: MS Reference Sans Serif;

  }

  .style20b {
    font-size: 8pt;
    font-weight: bold;
    font-family: Tahoma
  }

  .style20 {
    font-size: 8pt;
    font-family: Tahoma
  }

  .style16 {
    font-size: 9pt;
    font-family: Tahoma
  }

  .style21 {
    color: #000000;
    font-size: 10pt;
    font-weight: bold;
    font-family: Tahoma;
  }

  .style18 {
    color: #000000;
    font-size: 9pt;
    font-weight: normal;
    font-family: Tahoma;
  }

  .style_footer {
    color: #000000;
    font-size: 11pt;
    font-family: Tahoma;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;

  }

  .style19b {
    color: #000000;
    font-size: 11pt;
    font-weight: bold;
    font-family: Tahoma;
  }

  .style_title {
    color: #000000;
    font-size: 11pt;
    font-family: Tahoma;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-right: 1px solid black;


    padding: 3px;
  }

  .style_title_left {
    color: #000000;
    font-size: 11pt;
    font-family: Tahoma;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;

    padding: 3px;
  }

  .style_detail {
    color: #000000;
    font-size: 9pt;
    font-family: Tahoma;
    border-bottom: 1px dashed black;
    border-right: 1px solid black;
    padding: 3px;
  }

  .style_detail_left {
    color: #000000;
    font-size: 9pt;
    font-family: Tahoma;
    border-bottom: 1px dashed black;
    border-left: 1px solid black;
    border-right: 1px solid black;
    padding: 3px;
  }

  .td_fit {
    width: fit-content;
  }

  .transInput {
    font-size: 9pt;
    font-weight: bold;
    font-family: Tahoma;
    border: 0;
  }

  .transInput:focus {
    outline: none;
  }

  .flex-row {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    column-gap: 1em;
  }

  @page {
    size: A4;
    margin: 15px;
  }
</style>
<?php
include("../../include/koneksi.php");
include "../../include/config.php";
$tglstart = $_GET['start'];
$tglend = $_GET['end'];
$type = $_GET['type'];



$query = "SELECT net.* FROM (
SELECT d.nama,IFNULL(o.faktur,0) as bruto,IFNULL(o.qty,0) as qty,IFNULL(o.`order`,0) as `order`,IFNULL(r.retur,0) as retur,
IFNULL(o.faktur - IFNULL( r.retur, 0 ),0) AS netto,IFNULL(ROUND(( o.faktur - IFNULL( r.retur, 0 )) / 1.11 ),0) AS dpp,IFNULL(ROUND((( o.faktur - IFNULL( r.retur, 0 )) / 1.11 ) * 0.11 ),0) AS ppn,'OLN' AS tipe
FROM mst_dropshipper d LEFT JOIN (
SELECT SUM(o.faktur - IFNULL(o.discount_faktur,0)) AS faktur,SUM(o.totalqty) as qty, COUNT(o.id_trans) as `order`,o.id_dropshipper as id FROM olnso o WHERE o.deleted = 0
AND o.state = '1' AND DATE ( o.lastmodified ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) GROUP BY o.id_dropshipper) o ON d.id = o.id LEFT JOIN (
SELECT SUM(IFNULL( r.faktur, 0 )) AS retur,r.id_dropshipper FROM olnsoreturn r WHERE r.deleted = 0 AND r.state = '1' 
AND DATE ( r.lastmodified ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) AND ( r.totalqty <> 0 ) GROUP BY r.id_dropshipper ) r ON r.id_dropshipper = d.id

UNION 

SELECT
c.nama,IFNULL(d.bruto,0) as bruto,IFNULL(d.qty,0) as qty,IFNULL(d.`order`,0) as `order`,IFNULL( e.total_retur, 0 ) AS retur,IFNULL((d.bruto - IFNULL( e.total_retur, 0 )),0) AS netto,
IFNULL(ROUND((d.bruto - IFNULL( e.total_retur, 0 )) / 1.11),0) as dpp,IFNULL(ROUND(((d.bruto - IFNULL( e.total_retur, 0 )) / 1.11) * 0.11),0) as ppn,'B2B' AS tipe 
FROM mst_b2bcustomer c LEFT JOIN (SELECT c.id,SUM(IF( c.id = b.id_customer, b.faktur, 0 )) AS bruto,SUM(IF( c.id = b.id_customer, b.totalkirim, 0 )) AS qty,COUNT( b.id ) AS `order` FROM b2bdo b
LEFT JOIN mst_b2bcustomer c ON b.id_customer = c.id
WHERE b.deleted = 0 AND DATE ( b.tgl_trans ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) GROUP BY c.id ) d ON c.id = d.id LEFT JOIN (
SELECT SUM( total ) AS total_retur,b2bcust_id AS id_dropshipper 
FROM b2breturn WHERE deleted = 0 AND post = '1' AND DATE ( lastmodified ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) GROUP BY b2bcust_id 
) e ON c.id = e.id_dropshipper) net WHERE net.bruto > 0 or net.retur > 0 ";

if ($type == 1) {
  $query .= "ORDER BY nama ASC";
} else {
  $query .= "ORDER BY netto DESC";
}

$cat = [
  [
    "name" => "PENJUALAN OLN",
    "qty" => 0,
    "order" => 0,
    "bruto" => 0,
    "retur" => 0,
    "netto" => 0,
    "dpp" => 0,
    "ppn" => 0,
  ],
  [
    "name" => "PENJUALAN B2B",
    "qty" => 0,
    "order" => 0,
    "bruto" => 0,
    "retur" => 0,
    "netto" => 0,
    "dpp" => 0,
    "ppn" => 0,
  ]
];

$total_netto = 0;
$qty = 0;
$order = 0;
$bruto = 0;
$netto = 0;
$retur = 0;
$dpp = 0;
$ppn = 0;

$result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $value) {
  if ($value['tipe'] == "OLN") {
    $cat[0]['qty'] += $value['qty'];
    $cat[0]['order'] += $value['order'];
    $cat[0]['bruto'] += $value['bruto'];
    $cat[0]['netto'] += $value['netto'];
    $cat[0]['retur'] += $value['retur'];
    $cat[0]['dpp'] += $value['dpp'];
    $cat[0]['ppn'] += $value['ppn'];
  } else {
    $cat[1]['qty'] += $value['qty'];
    $cat[1]['order'] += $value['order'];
    $cat[1]['bruto'] += $value['bruto'];
    $cat[1]['netto'] += $value['netto'];
    $cat[1]['retur'] += $value['retur'];
    $cat[1]['dpp'] += $value['dpp'];
    $cat[1]['ppn'] += $value['ppn'];
  }

  $total_netto += $value['netto'];
  $qty += $value['qty'];
  $order += $value['order'];
  $bruto += $value['bruto'];
  $netto += $value['netto'];
  $retur += $value['retur'];
  $dpp += $value['dpp'];
  $ppn += $value['ppn'];
}

?>


<form id="form2" name="form2" action="" method="post" onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
                OLN + B2B REPORT </strong></td>
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
            <td width="100%" class="style9b flex-row" colspan="7" style="white-space: no-wrap;">
              <p class="transInput">Periode : <?php echo "" . $tglstart; ?> - <?php echo "" . $tglend; ?></p>
            </td>
          </tr>

        </table>

        <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td width="10%" class="style_title_left" style="text-align: center; font-size: medium; font-weight: 600;">Kategori Penjualan</td>
            <td width="5%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Total QTY</td>
            <td width="5%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Order QTY</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Penjualan Bruto</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600; color: red;">Retur</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Penjualan Netto</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">DPP</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">PPN</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Kontribusi Penjualan</td>
          </tr>
          <?php foreach ($cat as $c) : ?>
            <tr>
              <td class="style_detail_left" style=""><?= $c['name'] ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['qty']) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['order']) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['bruto'], 2) ?></td>
              <td class="style_detail" style="text-align: right;color: red;"><?= number_format($c['retur'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['netto'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['dpp'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($c['ppn'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format(($c['netto'] / $total_netto) * 100, 2) . "%" ?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td width="10%" class="style_title_left" style="text-align: center; font-size: small; font-weight: 600;">Total</td>
            <td width="5%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($qty) ?></td>
            <td width="5%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($order) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($bruto, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600; color: red;"><?= number_format($retur, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($netto, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($dpp, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($ppn, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($total_netto / $netto * 100, 2) . "%" ?></td>
          </tr>
        </table>

        <br>
        <br>

        <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td width="5%" class="style_title_left" style="text-align: center; font-size: medium; font-weight: 600;">NO</td>
            <td class="style_title td_fit" style="text-align: center; font-size: medium; font-weight: 600;">Nama Customer</td>
            <td width="5%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Total QTY</td>
            <td width="5%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Order QTY</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Penjualan Bruto</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600; color: red;">Retur</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Penjualan Netto</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">DPP</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">PPN</td>
            <td width="10%" class="style_title" style="text-align: center; font-size: medium; font-weight: 600;">Kontribusi Penjualan</td>
          </tr>
          <?php $num = 1;
          foreach ($result as $value) : ?>
            <tr>
              <td class="style_detail_left" style="text-align: center;"><?= $num ?></td>
              <td class="style_detail" style="text-align: left;"><?= $value['nama'] ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['qty']) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['order']) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['bruto'], 2) ?></td>
              <td class="style_detail" style="text-align: right;color: red;"><?= number_format($value['retur'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['netto'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['dpp'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format($value['ppn'], 2) ?></td>
              <td class="style_detail" style="text-align: right;"><?= number_format((($value['netto'] / $total_netto) * 100), 3) . "%" ?></td>
            </tr>
          <?php $num++;
          endforeach ?>
          <tr>
            <td width="20%" class="style_title_left" style="text-align: center; font-size: small; font-weight: 600;" colspan="2">Total</td>
            <td width="5%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($qty) ?></td>
            <td width="5%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($order) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($bruto, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600; color: red;"><?= number_format($retur, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($netto, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($dpp, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($ppn, 2) ?></td>
            <td width="10%" class="style_title" style="text-align: right; font-size: small; font-weight: 600;"><?= number_format($total_netto / $netto * 100, 2) . "%" ?></td>
          </tr>
        </table>
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

  window.print();
</script>
<div align="center"><span class="style20">

  </span> </div>