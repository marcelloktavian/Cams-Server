<?php
  include "../../include/koneksi.php";
?>

<head>
  <title>PELUNASAN TRANSAKSI ONLINE AR</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script src="../../assets/js/time.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>

  <style>
    body {background-color:  #FFF8DC;}

    tanggal {color: maroon; margin-left: 40px;}

    #tbl_1{clear: both; border: 1px solid #FF6600; height: 20px; overflow-y:auto; overflow-x:scroll; float:left; width:1200px;} 
  </style>

</head>
<?php
  $sql = mysql_query("SELECT * FROM (SELECT p.*,j.nama AS dropshipper, j.type AS dsType, e.nama AS expedition, SUM(p.total) AS sumTotal FROM `olnso` p LEFT JOIN `mst_dropshipper` j ON (p.id_dropshipper=j.id) LEFT JOIN `mst_expedition` e ON (p.id_expedition=e.id) WHERE j.deleted=0 AND p.tgl_trans>='".$_GET['start_date']."' AND p.tgl_trans<='".$_GET['end_date']."' AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang> 0) GROUP BY `id_dropshipper`) AS x WHERE x.id_trans='".$_GET['ids']."'");

  $line = mysql_fetch_array($sql);
?>

<body>
  <form id="trolnarcredit_lunasi_form" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">DETAIL PELUNASAN TRANSAKSI AR CREDIT</td>
        <td class="fontjudul">TOTAL <input type="text" id="total_pelunasan" name="total_pelunasan" style='text-align:right;font-size: 30px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;'></td>
      </tr>
    </table>

    <hr />

    <table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="fonttext">Tanggal</td>
        <td><div id="clock"></div></td>
      </tr>
      <tr height="5">
        <td colspan="6"></td>
      </tr>
      <tr>
        <td class="fonttext">Nama Dropshipper</td>
        <td><input type="text" class="inputform" name="nama_dropshipper" id="nama_dropshipper" value="<?= $line['dropshipper'] ?>"></td>
        <td class="fonttext">Telp</td>
        <td><input type="text" class="inputForm" name="telp_dropshipper" id="telp_dropshipper" value="<?= $line['telp'] ?>"></td>
      </tr>
      <tr>
        <td class="fonttext">Alamat</td>
        <td><textarea name="alamat_dropshipper" id="alamat_dropshipper" cols='31' rows='2' disabled><?= $line['alamat'] ?></textarea></td>
      </tr>
    </table>

    <table width="70%" id="table_header" style="border: 1px solid red; margin: 20px 0;" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th style="border-right:1px solid red" width="15%" class="fonttext">Kode Transaksi</th>
          <th style="border-right:1px solid red" width="20%" class="fonttext">Tanggal Transaksi</th>
          <th style="border-right:1px solid red" width="15%" class="fonttext">Total Piutang</th>
          <th style="border-right:1px solid red" width="15%" class="fonttext">Sisa Piutang</th>
          <th style="border-right:1px solid red" width="15%" class="fonttext">Bayar Tunai</th>
          <th width="15%" class="fonttext">Bayar Bank</th>
        </tr>
      </thead>
      <tbody>
        <tr class="text-center" style="background-color:white;">
          <td style="border-right:1px solid red"><?= $line['id_trans'] ?></td>
          <td style="border-right:1px solid red"><?= $_GET['start_date'].' - '.$_GET['end_date'] ?></td>
          <td style="border-right:1px solid red"><?= $line['total'] ?></td>
          <td style="border-right:1px solid red"><?= $line['piutang'] ?></td>
          <td style="border-right:1px solid red"><?= $line['tunai'] ?></td>
          <td><?= $line['transfer'] ?></td>
        </tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td class='fonttext' style='width:80px;'>Keterangan</td>
        <td colspan='5'><input type='text' class='inputform' name='keterangan' id='keterangan' style='text-align:left;align=left;width:600px;' ></td>
      </tr>
      <tr>
        <td class='fonttext' style='width:80px;'>Informasi Bank </td>
        <td colspan='5'><input type='text' class='inputform' name='info' id='info' style='text-align:left;width:600px;' ></td>
      </tr>
      <tr>
        <td class='fonttext' style='width:120px;'>Total Bayar</td>
        <td><input type='text' class='inputform' name='faktur' id='faktur' style='text-align:right;align=right;'></td>
        <td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Tunai </td>
        <td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;'></td>
        <td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Bank</td>
        <td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'></td>
      </tr>
    </table>
  </form>

  <hr/>

  <table>
    <tr>
      <td>
        <p align='center'><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
      </td>
      <td>
        <p><input type='image' value='batal' src='../../assets/images/batal.png'  id='baru'  onClick='tutup()'/></p>
      </td>
    </tr>

  </table>
</body>