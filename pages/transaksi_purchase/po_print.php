<?php

  require_once '../../include/config.php';

  include '../../include/koneksi.php';
  
  function intToIDR($val) {
    return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
  }

  $sql_mst    = "SELECT a.*,b.npwp,b.alamat,b.`pic`,b.`telp`,b.`email` FROM `mst_po` a LEFT JOIN `mst_supplier` b ON a.`id_supplier`=b.`id` WHERE a.`id`='".$_GET['id']."'";
  $get_mst    = mysql_query($sql_mst) or die(mysql_error());
  $mst_po     = mysql_fetch_array($get_mst);
    $id_mst         = $mst_po['id'];
    $no_dokumen     = $mst_po['dokumen'];
    $id_supplier    = $mst_po['id_supplier'];
    $nama_supplier  = $mst_po['nama_supplier'];
    $npwp_supplier  = $mst_po['npwp'];
    $alamat_mst     = $mst_po['alamat'];
    $no_telepon     = $mst_po['telp'];
    $pic_mst        = $mst_po['pic'];
    $email_mst      = $mst_po['email'];
    $tgl_po         = $mst_po['tgl_po'];
    $eta_pengiriman = $mst_po['eta_pengiriman'];
    $id_pemohon     = $mst_po['id_pemohon'];
    $nama_pemohon   = $mst_po['nama_pemohon'];
    $total_dpp      = $mst_po['total_dpp'];
    $total_qty      = $mst_po['total_qty'];
    $ppn            = $mst_po['ppn'];
    $grand_total    = $mst_po['grand_total'];
    $pengiriman     = $mst_po['pengiriman'];
    $catatan        = $mst_po['catatan'];

  $sql_det    = "SELECT *,date_format(tgl_quotation, '%d/%m/%Y') as tanggal_quotation_formatted FROM `det_po` WHERE `id_po`='".$_GET['id']."' AND `deleted` = 0";
  $get_det    = mysql_query($sql_det) or die(mysql_error());

?>

<style>
  body{
    font-family:arial;
    zoom: 0.85;
  }
</style>

<head>
  <title>Purchase Order <?= $no_dokumen ?></title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css?version=2" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<body>

<center>

  <table cellpadding=0 cellspacing=0 style="width:97%;">
    <tr>
      <td rowspan="4"><img src="../../files/po.png"><span style="margin-right:65%;"></span></td><td colspan="4" class="title-big text-right" style="vertical-align:bottom; width:16em">PURCHASE ORDER</td>
    </tr>
    <tr>
      <td style="width:6em;"></td><td class="text-left" style="vertical-align:bottom; width: 1em">PO.NO&nbsp;</td><td class="" style="vertical-align:bottom;" style="width:1em;">:</td><td class="text-right" style="vertical-align:bottom; width:9em;"><?= $no_dokumen ;?></td>
    <tr>
      <td></td><td class="text-left">PO.DATE&nbsp;</td><td>:</td><td class="text-right"><?= date_format(date_create($tgl_po), "d M Y") ;?></td>
    </tr>
    <tr>
      <td></td><td class="text-left" style="vertical-align:top;">STATUS&nbsp;</td><td style="vertical-align:top;">:</td><td class="text-right" style="vertical-align:top;"><u style="color:green;">APPROVED</u></td>
    </tr>
  </table>

  <table cellpadding=0 cellspacing=0 style="width:97%;">
    <tr>
      <td colspan="7" style="height: 0.5em !important;"></td>
    </tr>
    <tr>
      <td colspan="3" class="title-md" style="width:50%;">SUPPLIER</td><td style="width: 10%;"></td><td colspan="3" class="title-md" style="width:40%;">TUJUAN PENGIRIMAN</td>
    </tr>
    <tr>
      <td colspan="7"><span style="padding:2em;"></span></td>
    </tr>
    <tr style="font-size:1em;">
      <td colspan="3" class="title-sm"><?= $nama_supplier ;?></td><td></td><td colspan="3" class="title-sm">PT. AGUNG KEMUNINGWIJAYA</td>
    </tr>
    <tr style="font-size:0.85em;">
      <td colspan="3"><?= $alamat_mst ;?></td><td></td><td colspan="3">Taman Kopo Indah I, Kompleks Industri No.6, Kec.  Margahayu, Kab. Bandung, Jawa Barat</td>
    </tr>
    <tr>
      <td style="width:60%;" colspan=3><span style="padding:1em;"></span></td><td></td><td style="width:40%;" colspan=3><span style="padding:1em;"></span></td>
    </tr>
    <tr style="font-size:0.85em;">
      <td class="title-sm" style="width:1%;">PIC&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td><?= $pic_mst ;?></td><td></td>
      <td class="title-sm">NPWP&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td>01.448.609.6-445.000</td>
    </tr>
    <tr style="font-size:0.85em;">
      <td class="title-sm">NPWP&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td><?= $npwp_supplier ;?></td><td></td><td class="title-sm" style="width:1%;">TEL&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td>(022) 540-1972</td></td>
    </tr>

    <tr style="font-size:0.9em;">
      <td class="title-sm">TEL&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td><?= $no_telepon ;?></td>
      <td></td><td class="title-sm">EMAIL&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td>contact@akwoutsole.com</td>
    </tr>
    
    <tr style="font-size:0.9em;">
      <td class="title-sm">EMAIL&nbsp;</td><td class="title-sm" style="width:1%;">:&nbsp;</td><td><?= $email_mst ;?></td><td colspan="4"></td>
    </tr>
    <tr>
      <td colspan="7"><span style="padding:2em;"></span></td>
    </tr>
  </table>

  <table cellpadding=0 cellspacing=0 style="width:97%;" class="detail_table">
    <tr>
      <td colspan="3" class="title-sm td-title text-center td-border">CATATAN TAMBAHAN</td><td class="title-sm td-title text-center td-border" style="width:13%">DELIVERY SCHEDULE</td><td class="title-sm td-title text-center td-border" style="width:15%">REQUISITIONER</td>
    </tr>
    <tr>
      <td colspan="3" class="text-left td-border" style="height: 4em; padding : 10px !important;"><?= substr($catatan,0,420) ;?></td><td class="text-center td-border" style="width:12%"><?= date_format(date_create($eta_pengiriman), "d M Y") ;?></td><td class="text-center td-border" style="width:15%;  padding : 10px !important;"><?= $nama_pemohon?></td>
    </tr>
  </table>

  <table cellpadding=0 cellspacing=0 style="width:97%;">
    <tr>
      <td colspan="6"><span style="padding:0.5em;"></span></td>
    </tr>
  </table>

  <table cellpadding=0 cellspacing=0 style="width:97%;" class="detail_table">
    <tr>
      <td class="title-sm td-title text-center td-border" style="width:3%;">NO.</td><td class="title-sm td-title text-center td-border">PRODUK/JASA</td><td class="title-sm td-title text-center td-border" style="width:10%">QTY</td><td class="title-sm td-title text-center td-border" style="width:13%">DPP/UNIT</td><td class="title-sm td-title text-center td-border" style="width:15%">TOTAL</td>
    </tr>
    <?php
    $i = 0;
    while($row = mysql_fetch_array($get_det)){
      $i ++;
      
      echo '
      <tr>
        <td class="text-center td-border" style="width:3%;">'.$i.'</td><td class="text-left td-border" style="padding-left:15px; padding-right:15px;">'.$row['nama_produk'].'</td><td class="text-center td-border" style="width:10%">'.$row['qty'].'&nbsp;&nbsp;'.$row['satuan'].'</td><td class="text-right td-border" style="width:13%; padding-left:15px; padding-right:10px;">'.intToIDR($row['price']).'</td><td class="text-right td-border" style="width:15%; padding-left:15px; padding-right:10px;">'.intToIDR($row['subtotal']).'</td>
      </tr>';
    }
    while($i <= 30){
      echo '
      <tr>
        <td class="text-center td-border" style="width:3%;"><td class="text-left td-border"></td><td class="text-center td-border" style="width:10%"></td><td class="text-right td-border" style="width:13%"></td><td class="text-right td-border" style="width:15%"></td>
      </tr>';
      $i ++;
    }
    ;?>
    <tr style="">
      <td colspan=2 class="text-right td-border title-sm td-title" style="width:3%; border-top: 2px solid black !important;">TOTAL QTY :</td><td class="text-center td-border" style="width:10%; border-top: 2px solid black !important; padding-left:15px; padding-right:15px;"><?= $total_qty ;?></td><td class="text-right td-border td-title title-sm" style="width:13%; border-top: 2px solid black !important;">DPP :</td><td class="text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-left:15px; padding-right:10px;"><?= intToIDR($total_dpp) ;?></td>
    </tr>
    <tr>
      <td colspan=3 rowspan=3>
        <ul>
          <li>Harap Kirimkan invoice yang sesuai dengan spesifikasi pesanan ini ke email perusahaan.</li>
          <li>Untuk invoice fisik, harap kirimkan 2 salinan.</li>
          <li>Harap untuk segera berkabar jika terjadi kendala dalam pemenuhan order.</li>
        </ul>
      </td><td class="text-right td-border td-title title-sm" style="width:12%;">PPN :</td><td class="text-right td-border" style="width:15%; padding-left:15px; padding-right:10px;"><?= intToIDR($ppn) ;?></td>
    </tr>
    <tr hidden>
      <td class="text-right td-border td-title title-sm" style="width:12%;">PENGIRIMAN :</td><td class="text-right td-border" style="width:15%; padding-left:15px; padding-right:10px;"><?= intToIDR($pengiriman) ?></td>
    </tr>
    <tr>
      <td class="text-right td-border td-title title-sm" style="width:12%; border-top: 2px solid black !important; padding-top: 1em; padding-bottom: 1em;">GRAND TOTAL :</td><td class="title-sm text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-top: 0.5em; padding-bottom: 1em; padding-left:15px; padding-right:10px; vertical-align:center;"><?= intToIDR($grand_total) ;?></td>
    </tr>
  </table>

  <table cellpadding=0 cellspacing=0 style="width:97%;">
    <tr>
      <td style="width:20%;" class="text-center title-sm">Purchase Authorization,</td><td></td>
    </tr>
    <tr><td class="text-center title-sm">Director</td><td></td></tr>
    <tr><td style="padding-top: 5em;"></td><td></td></tr>
    <tr><td><hr/></td><td></td></tr>
    <tr><td class="text-center title-sm">ENRICO TJANDRA</td><td></td></tr>
  </table>
</body>

<script>
  window.print();
</script>