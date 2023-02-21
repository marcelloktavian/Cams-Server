<?php

require_once '../../include/config.php';

include '../../include/koneksi.php';

function intToIDR($val) {
  return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
}

if(isset($_GET['filter']) && $_GET['filter'] != ''){
  $filter_value = " AND (`ap_num` LIKE '%".$_GET['filter']."%' OR `nama_supplier` LIKE '%".$_GET['filter']."%' OR `nama_akun` LIKE '%".$_GET['filter']."%' OR `catatan` LIKE '%".$_GET['filter']."%') ";
}
else{
  $filter_value = '';
}

if((!isset($_GET['startdate_ap']) && !isset($_GET['enddate_ap']))||($_GET['startdate_ap'] == '' && $_GET['enddate_ap'] == '')){
  $startdate = date("Y-m-d"); $enddate = date("Y-m-d");
  $where = " WHERE a.deleted=0 AND a.ap_date>='".date("Y-m-d")."' AND a.ap_date<='".date("Y-m-d")."'".$filter_value;
}else if($_GET['startdate_ap'] != '' && $_GET['enddate_ap'] == ''){
  $startdate = date($_GET['startdate_ap']); $enddate = date("Y-m-d");
  $where = " WHERE a.deleted=0 AND a.ap_date>='".$_GET['startdate_ap']."'".$filter_value;
}
else if($_GET['startdate_ap'] == '' && $_GET['enddate_ap'] != ''){
  $startdate = date("Y-m-d"); $enddate = date($_GET['enddate_ap']);
  $where = " WHERE a.deleted=0 AND a.ap_date<='".$_GET['enddate_ap']."'".$filter_value;
}
else{
  $startdate = date($_GET['startdate_ap']); $enddate = date($_GET['enddate_ap']);
  $where = " WHERE a.deleted=0 AND a.ap_date>='".$_GET['startdate_ap']."' AND a.ap_date<='".$_GET['enddate_ap']."'".$filter_value;
}

$unposted = " AND (`posting`=0 OR `posting` IS NULL) ";
$posted = " AND (`posting`=1) ";

$sql_po  = "SELECT a.*, date_format(a.ap_date, '%d-%m-%Y') as ap_date_formatted,b.bank, b.rekening FROM `mst_ap` a LEFT JOIN `mst_supplier` b ON a.id_supplier=b.id "; 

if(isset($_GET['status'])){
  if($_GET['status'] == 'posted'){
    $q = mysql_query($sql_po.$where.$posted) or die(mysql_error());
  }
  else if($_GET['status'] == 'unposted'){
    $q = mysql_query($sql_po.$where.$unposted) or die(mysql_error());
  }
  else{
    $q = mysql_query($sql_po.$where) or die(mysql_error());
  }
}
?>

<style>
  body{
    font-family:arial;
  }
</style>

<head>
  <title>PRINT PO DETAIL</title>

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
    <td rowspan="4"><img src="../../files/po.png"><span style="margin-right:65%;"></span></td><td colspan="4" class="title-big text-right" style="vertical-align:bottom; width:16em">ACCOUNT PAYABLE</td>
  </tr>
  <tr>
    <td></td><td class="text-left">TANGGAL AP&nbsp;</td><td></td><td class="text-right"><?= date_format(date_create($startdate), "d M Y") ;?> - <?= date_format(date_create($enddate), "d M Y") ;?></td>
  </tr>
  <tr>
    <td></td><td class="text-left" style="vertical-align:top;">STATUS&nbsp;</td><td style="vertical-align:top;"></td><td class="text-right" style="vertical-align:top;"><u style="color:<?= strtoupper($_GET['status'])=='UNPOSTED' ?  'red' : 'green' ?>;"><?= strtoupper($_GET['status']) ?></u></td>
  </tr>
</table>

<table cellpadding=0 cellspacing=0 style="width:97%; zoom:0.85;">
  <tr>
    <td class="title-sm td-title text-center td-border" style="width:5%; padding: 0.7em;">NO.</td>
    <td class="title-sm td-title text-center td-border" style="width:13%">NOMOR AP</td>
    <td class="title-sm td-title text-center td-border" style="width:11%">TANGGAL</td>
    <td class="title-sm td-title text-center td-border" style="width:25%">SUPPLIER</td>
    <td class="title-sm td-title text-center td-border" style="width:17%">TUJUAN PEMBAYARAN</td>
    <td class="title-sm td-title text-center td-border" style="width:8%">STATUS</td>
    <td class="title-sm td-title text-center td-border" style="width:15%">PEMBAYARAN</td>
  </tr>
  <?php
  $i = 0;
  $totalQty = 0;
  $totalPembayaran = 0;
  while($row = mysql_fetch_array($q)){
    $i++;

    if($row['posting']==0 || $row['posting']==null){
      echo '
      <tr>
        <td class="text-center td-border" style="width:3%; padding: 0.1em;">'.$i.'</td><td class="text-center td-border" style="padding-left:15px; padding-right:15px;">'.$row['ap_num'].'</td><td class="text-center td-border" style="width:10%">'.$row['ap_date_formatted'].'</td><td class="text-left td-border" style="width:13%; padding-left:15px; padding-right:10px;">'.$row['nama_supplier'].'</td><td class="text-left td-border" style="width:15%; padding-left:15px; padding-right:10px;">'.$row['bank'].' - '.$row['rekening'].'</td><td class="text-center td-border" style="width:8%; padding-left:15px; padding-right:10px; color:red; text-decoration: underline;">UNPOSTED</td><td class="text-right td-border" style="width:15%; padding-left:15px; padding-right:10px;">'.intToIDR($row['grand_total']).'</td>
      </tr>';
    }
    else if($row['posting']==1){
      echo '
      <tr>
        <td class="text-center td-border" style="width:3%; padding: 0.1em;">'.$i.'</td><td class="text-center td-border" style="padding-left:15px; padding-right:15px;">'.$row['ap_num'].'</td><td class="text-center td-border" style="width:10%">'.$row['ap_date_formatted'].'</td><td class="text-left td-border" style="width:13%; padding-left:15px; padding-right:10px;">'.$row['nama_supplier'].'</td><td class="text-left td-border" style="width:15%; padding-left:15px; padding-right:10px;">'.$row['bank'].' - '.$row['rekening'].'</td><td class="text-center td-border" style="width:8%; padding-left:15px; padding-right:10px; color:green; text-decoration: underline;">POSTED</td><td class="text-right td-border" style="width:15%; padding-left:15px; padding-right:10px;">'.intToIDR($row['grand_total']).'</td>
      </tr>';
    }

    $totalQty += $row['total_qty']; $totalPembayaran += $row['grand_total'];
  }
  ?>

  <tr>
    <td class="title-sm text-right td-border" colspan=6 align="right">TOTAL : </td><td class="title-sm text-right td-border" style="padding-left:15px; padding-right:10px;"><?= intToIDR($totalPembayaran) ?></td>
  </tr>

</table>

</body>

<script>
  window.print();
</script>