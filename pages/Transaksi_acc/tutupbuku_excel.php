<?php
require_once '../../include/config.php';
require_once '../../include/koneksi.php';

function intToIDR($val) {
	return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
}
?>

<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>

<style>
.fontsmall {
  color:#777777;
  font-family:arial;
  font-size:10px;
}
.fonttext {
	color:#777777;
	font-family:arial;
	font-size:14px;
	
}
.fontjudul {
	color:#777777;
	font-family:arial;
	font-size:20px;
	text-align:center;
}
hr {
	border-color:#E0E0E0;
	margin-bottom:10px;
}

.inputform {
	text-align:left;
	height:25px;
	width:245px;
	font-size:14px;
	color:#777777;
	padding:3px;
	padding-left:8px;
}

.Nama_Jasa {
	text-align:left;
	height:25px;
	width:5px;
	font-size:14px;
	color:#777777;
	padding:3px;
	padding-left:8px;
}

.fixed-table{
	table-layout: fixed;
	width : 100%;
}

.fit-column{
	width				: 1%;
	white-space	: nowrap;
}

.title-big{
	font-family : 'Arial';
	font-weight : bold;
	font-size   : 1.8em;
}

.title-md{
	font-weight : bold;
	font-size		: 1.3em;
}

.title-sm{
	font-weight : bold;
}

.text-left{
	text-align : left;
}

.text-center{
	text-align: center;
}

.text-right{
	text-align: right;
}

table.detail_table tr td{
	padding: 0.2em; height: 1.5em;
}

table.detail_table tr:not(:first-child) td{
	font-size : 0.8em;
}

tr td.td-border{
	border-left : 1px solid black;
	border-bottom : 1px solid black;
}

tr td.td-border:last-child{
	border-left : 1px solid black;
	border-bottom : 1px solid black;
	border-right : 1px solid black;
}

tr:first-child td.td-border{
	border-top : 1px solid black;
	border-left : 1px solid black;
	border-bottom : 1px solid black;
}

tr:first-child td.td-border:last-child{
	border-top : 1px solid black;
	border-left : 1px solid black;
	border-right : 1px solid black;
	border-bottom : 1px solid black;
}

tr:last-child td.td-border:last-child{
	border-left : 1px solid black;
	border-right : 1px solid black;
	border-bottom : 1px solid black;
}

.td-title{
	background-color : #efefef;
}

#form2 textarea {
	font-size:14px;
	color:#777777;
	font-family:arial;
	padding:3px;
	padding-left:8px;
}

#form2 input[type="submit"] {
	width: 135px;
	background-color: #2b6597;
	border:0px;
	color:#FFF;
	font-size:14px;
	padding:8px 0px;
	font-weight:bold;
	cursor:pointer;
}

#form2 input[type="submit"]:hover {
	background-color:#5084b1;

}

#New2 {
	width: 135px;
	background-color: #2c963a;
	border:0px;
	color:#FFF;
	font-size:14px;
	padding:4px 0px;
	font-weight:bold;
	cursor:pointer;
}

#New2:hover {
	background-color: #5baf66;
}

thead {
	background-color:#eaeaea;
	text-align:center;
	color:c1c1c1;
	height:35px;
}
</style>

<?php

// ! -------------------- Excel Create ------------------- !

// ! -------------------- Data Master ------------------- !
$data_master = 'SELECT a.*, DAY(a.lastmodified) AS hari, MONTHNAME(a.lastmodified) AS bulan, YEAR(a.lastmodified) AS tahun, TIME(a.lastmodified) AS waktu, b.nama AS nama_pic FROM `tbl_logyec` a LEFT JOIN `user` b ON a.pic=b.user_id AND a.id='.$_GET['id'].'';

$get_data_master = mysql_query($data_master);
$string_data_master = mysql_fetch_array($get_data_master);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=tutup_buku_".$string_data_master['month']."_".$string_data_master['year'].".xls");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

// ! -------------------- Data Detail ------------------- !


// ! -------------------- Data Construction ------------------- !


?>

<table cellpadding=0 cellspacing=0 style="width:97%;">
  <tr>
  <td colspan="6" class="text-center" style="vertical-align:bottom; width: 5em"><b>TUTUP BUKU</b></td>
  </tr>
  <tr>
    <td colspan="6" class="text-center" style="vertical-align:bottom; width: 5em"><b>PERIODE</b>&nbsp;:<?= date_format(date_create($string_data_master['year'].'/'.$string_data_master['month'].'/01'), "M Y");?></td>
  <tr>
    <td colspan="6" class="text-center">PIC&nbsp;:<?= $string_data_master['nama_pic'] ;?></td>
  </tr>
  <tr>
    <td colspan="6" class="text-center" style="vertical-align:top;">TANGGAL&nbsp;:<?= date_format(date_create($string_data_master['lastmodified']), "d M Y");?></td>
  </tr>
</table>

<table cellpadding=0 cellspacing=0 style="width:97%;" border=1>
  <tr>
    <td class="title-sm td-title text-center td-border"><b>NOMOR AKUN</b></td><td class="title-sm td-title text-center td-border" style="width:40%"><b>NAMA AKUN</b></td><td class="title-sm td-title text-center td-border" style="width:15%"><b>DEBET</b></td><td class="title-sm td-title text-center td-border" style="width:15%"><b>KREDIT</b></td><td class="title-sm td-title text-center td-border" style="width:15%"><b>BALANCE DEBET</b></td><td class="title-sm td-title text-center td-border" style="width:15%"><b>BALANCE KREDIT</b></td>
  </tr>
  <?php
    error_reporting(0);
    $id = $_GET["id"];

    $sql_products ="SELECT a.* FROM `mst_coa` a ";
    $query = '';
    $countnya = 0;
    $q = $db->query($sql_products.' where a.deleted=0 ORDER BY noakun ASC');
    $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
    foreach($data1 as $line) {
      if ($countnya == 0) {
        $query .= "(select id, noakun, nama, jenis from mst_coa where id='".$line['id']."'  ORDER BY noakun ASC)";
      } else {
        $query .= " UNION ALL (select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."'  ORDER BY noakun ASC) ";
      }
      $countnya++;
      $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
      $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
      foreach($data2 as $line2) {
        $query .= " UNION ALL (select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ORDER BY noakun ASC) ";
      }
    }
    $i = 0;
    $p = $db->query($query);
    $rows = $p->fetchAll(PDO::FETCH_ASSOC);
    $responce = '';
    $total_credit = 0;
    $total_debet = 0;
    foreach($rows as $lines){
      $month = '';
      $qmonth = "SELECT *, IF(length(month)=1,concat('0',month),month) as bulannya FROM tbl_logyec WHERE id=".$id;
      $pmonth = $db->query($qmonth);
      $rowsmonth = $pmonth->fetchAll(PDO::FETCH_ASSOC);
      foreach($rowsmonth as $r){
        $month = $r['bulannya'].'/'.$r['year'];
      }

      $qsaldo = "SELECT SUM(debet) AS db, SUM(kredit) AS cr FROM jurnal_detail_archive WHERE no_akun='".$lines['noakun']."' AND id_logyec='$month' ";
      $debet = 0;
      $kredit = 0;
      $psaldo = $db->query($qsaldo);
      $rowssalso = $psaldo->fetchAll(PDO::FETCH_ASSOC);
      foreach($rowssalso as $rs){
        $debet = $rs['db'];
        $kredit = $rs['cr'];

        $balance = $debet-$kredit;
        $balance_debet = $balance >= 0 ? $balance : 0;
        $balance_credit = $balance < 0 ? $balance*-1 : 0;

        $total_debet += $debet;
        $total_credit += $kredit;
        $total_balance_debet += $balance_debet;
        $total_balance_credit += $balance_credit;
      }
      ?>
      <tr>
        <td class="text-left td-border" style="padding-left:12px; padding-right:12px;"><?= $lines['noakun'] ?></td>
        <td class="text-left td-border" style="padding-left:12px; padding-right:12px;"><?= $lines['nama'] ?></td>
        <td class="text-right td-border" align="right" style="padding-left:12px; padding-right:12px;"><?= number_format($debet, 0) ?></td>
        <td class="text-right td-border" align="right" style="padding-left:12px; padding-right:12px;"><?= number_format($kredit, 0) ?></td>
        <td class="text-right td-border" align="right" style="padding-left:12px; padding-right:12px;"><?= number_format($balance_debet) ?></td>
        <td class="text-right td-border" align="right" style="padding-left:12px; padding-right:12px;"><?= number_format($balance_credit) ?></td>
      </tr>
      <?
      $i++;
    }
  ?>
  <tr>
    <td class="text-right td-border td-title title-sm" style="width:12%; border-top: 2px solid black !important; padding-top: 0.2em; padding-bottom: 0.2em;" colspan=2><b>TOTAL</b></td><td class="title-sm text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-top: 0.2em; padding-bottom: 0.2em; padding-left:10px; padding-right:10px; vertical-align:center;"><?= intToIDR($total_debet) ?></td><td class="title-sm text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-top: 0.2em; padding-bottom: 0.2em; padding-left:10px; padding-right:10px; vertical-align:center;"><?= intToIDR($total_credit) ?></td><td class="title-sm text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-top: 0.2em; padding-bottom: 0.2em; padding-left:10px; padding-right:10px; vertical-align:center;"><?= intToIDR($total_balance_debet) ?></td><td class="title-sm text-right td-border" style="width:15%; border-top: 2px solid black !important; padding-top: 0.2em; padding-bottom: 0.2em; padding-left:10px; padding-right:10px; vertical-align:center;"><?= intToIDR($total_balance_credit) ?></td>
  </tr>
</table>

<script>
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