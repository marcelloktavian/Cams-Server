<?php
require_once '../../include/config.php';
include '../../include/koneksi.php';

$sql_header = "SELECT a.id, a.no_akun, a.nama_akun, b.nama, b.type, DATE_FORMAT(c.tgl, '%d/%m/%Y') AS tgl_formatted, a.debet, a.kredit FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON a.id_parent=c.id WHERE c.deleted=0 AND a.`no_akun` = '".$_GET['no_akun']."' LIMIT 1";

$run_header = mysql_query($sql_header);
$fetch_header = mysql_fetch_array($run_header);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=AR OLN CREDIT ".$fetch_header['nama']." ".date('d_m_Y').".xls");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$sql_excel = "SELECT a.id, c.no_jurnal, a.no_akun, a.nama_akun, b.nama, b.type, DATE_FORMAT(c.tgl, '%d/%m/%Y') AS tgl_formatted, a.debet, a.kredit FROM `jurnal_detail` a LEFT JOIN `mst_dropshipper` b ON CAST(SUBSTRING(a.`no_akun`, 7) AS INT)=b.id LEFT JOIN `jurnal` c ON a.id_parent=c.id WHERE c.deleted=0 AND a.`no_akun` = '".$_GET['no_akun']."' ORDER BY c.tgl DESC";

$run_excel = mysql_query($sql_excel);

function intToIDR($val) {
	return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
}
?>

<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
*{
	font-family: Tahoma;
}
@page {
	zoom: 0.8;
	size: A4;
	margin: 15px;
}
.fontsmall {
  color:#777777;
  font-size:10px;
}
.fonttext {
	color:#777777;
	font-size:14px;
}
.fontjudul {
	color:#777777;
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
table tr td{
	padding-left: 5px !important; padding-right: 5px !important;
}
</style>

<table>
	<tr>
		<td class="style99" width="100%" colspan=6 class="text-left"><b>AR OLN CREDIT DETAIL<br>
		<?= strtoupper($fetch_header['nama']) ?></b></td>
		<td class="text-right">
			<?php
				date_default_timezone_set('Asia/Jakarta');
				echo date('d/m/Y').'<br>';
				echo date('H:i:s');
			?>
		</td>
	</tr>
</table>

<table cellpadding=0 cellspacing=0 border=1>
	<tr>
		<td class="title-sm td-title text-center" align="center"><b>Nomor Jurnal</b></td>
		<td class="title-sm td-title text-center" align="center"><b>Tanggal Jurnal</b></td>
		<td class="title-sm td-title text-center" align="center"><b>Nomor Akun</b></td>
		<td class="title-sm td-title text-center" align="center" width="60%"><b>Nama Akun</b></td>
		<td class="title-sm td-title text-center" align="center" width="1%"><b>Type</b></td>
		<td class="title-sm td-title text-center" align="center" width="10%"><b>Debet</b></td>
		<td class="title-sm td-title text-center" align="center" width="10%"><b>Kredit</b></td>
	</tr>

	<?php 
	$total_credit = 0; $total_debet = 0;
	while($line = mysql_fetch_array($run_excel)){
		?>
	<tr>
		<td class="td-border text-center" align="center"><?= $line['no_jurnal'] ?></td>
		<td class="td-border text-center" align="center"><?= $line['tgl_formatted'] ?></td>
		<td class="td-border text-center" align="center"><?= $line['no_akun'] ?></td>
		<td class="td-border"><?= $line['nama_akun'] ?></td>
		<td class="text-center" align="center"><?= $line['type'] ?></td>
		<td class="td-border text-right" align="right"><?= $line['debet'] ?></td>
		<td class="td-border text-right" align="right"><?= $line['kredit'] ?></td>
	</tr>
		<?php
		$total_credit += $line['kredit']; $total_debet += $line['debet'];
	}
	?>

	<tr>
		<td class="td-border" colspan="5" align="right"><b>GRAND TOTAL :</b></td>
		<td class="td-border text-right" align="right"><b><?= $total_debet ?></b></td>
		<td class="td-border text-right" align="right"><b><?= $total_credit?></b></td>
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