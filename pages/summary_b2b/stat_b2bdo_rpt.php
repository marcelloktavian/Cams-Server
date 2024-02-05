<?php
error_reporting(0);
include("../../include/koneksi.php");
$tglstart = $_GET['start'];
$tglend = $_GET['end'];
$type = $_GET['type'];

$q = "SELECT sls.nama AS salesman,COUNT( m.id_trans ) AS jum_order,SUM(IF(t.nama = 'SDL',m.totalkirim,0)) as sdl,SUM(IF(t.nama = 'SOL',m.totalkirim,0)) as sol,SUM(IF(t.nama = 'SDC',m.totalkirim,0)) as sdc,SUM(m.totalkirim) as total_qty,SUM( m.faktur ) AS faktur,ROUND((SUM( m.faktur ) / 1.11)) as dpp,ROUND((SUM( m.faktur ) / 1.11) * 0.11) as ppn FROM b2bdo m
LEFT JOIN mst_b2bcustomer sls ON sls.id = m.id_customer 
JOIN ( SELECT s.id_trans,c.nama FROM b2bso s LEFT JOIN mst_b2bcategory_sale c ON s.id_kategori = c.id) t ON m.id_transb2bso = t.id_trans 
WHERE m.deleted = 0 AND DATE ( m.tgl_trans ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) GROUP BY m.id_customer ORDER BY sls.nama ASC";

$q2 = mysql_query($q);
$num = 1;
$sol = 0;
$sdl = 0;
$sdc = 0;
$jum_order = 0;
$total_qty = 0;
$faktur = 0;
$dpp = 0;
$ppn = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>B2B Laporan Penjualan</title>
	<style>
		:root {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		@page {
			size: A4;
			margin: 15px;
		}

		.title {
			font-size: large;
			font-weight: bold;
		}

		.title_dir {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
		}

		@page {
			size: A4;
			margin: 15px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		th,
		td {
			border: 1px solid black;
			padding: 4px;
			text-align: center;
		}

		.data {
			border: 1px solid black;
			padding: 8px;
			text-align: left;

		}

		.right {
			text-align: right;
		}

		.auto {
			width: auto;
		}

		tbody {
			font-size: 13px;
		}

		.zero {
			color: #e0e0e0;
		}

		.bold {
			font-weight: bold;
			font-size: 15px;
		}
	</style>
</head>

<body>
	<div class="title_dir">
		<span class="title">
			B2B LAPORAN PENJUALAN
		</span>
		<span id="timestamp"><?php date_default_timezone_set('Asia/Jakarta');
								echo $timestamp = date('d/m/Y H:i:s'); ?>
		</span>
	</div>
	<div style="margin-bottom: 20px;">
		<span><?php echo "" . $tglstart; ?>&nbsp;-&nbsp;<?php echo "" . $tglend; ?></span>
	</div>

	<table>
		<thead>
			<tr>
				<th class="">NO.</th>
				<th class="">Nama Customer</th>
				<th class="auto">QTY SOL</th>
				<th class="auto">QTY SDL</th>
				<th class="auto">QTY SDC</th>
				<th class="auto">QTY Order</th>
				<th class="auto">Total QTY</th>
				<th class="">Penjualan Bruto</th>
				<th class="">DPP</th>
				<th class="">PPN</th>
			</tr>
		</thead>
		<tbody>
			<?php while ($d = mysql_fetch_array($q2)) : ?>
				<tr>
					<td class=""><?= $num; ?></td>
					<td class="auto data"><?= $d['salesman'] ?></td>
					<td class="auto <?= $d['sol'] == 0 ? 'zero' : '' ?>"><?= number_format($d['sol']) ?></td>
					<td class="auto <?= $d['sdl'] == 0 ? 'zero' : '' ?>"><?= number_format($d['sdl']) ?></td>
					<td class="auto <?= $d['sdc'] == 0 ? 'zero' : '' ?>"><?= number_format($d['sdc']) ?></td>
					<td class="auto <?= $d['jum_order'] == 0 ? 'zero' : '' ?>"><?= number_format($d['jum_order']) ?></td>
					<td class="auto <?= $d['total_qty'] == 0 ? 'zero' : '' ?>"><?= number_format($d['total_qty']) ?></td>
					<td class="<?= $d['faktur'] == 0 ? 'zero' : '' ?>"><?= number_format($d['faktur']) ?></td>
					<td class="<?= $d['dpp'] == 0 ? 'zero' : '' ?>"><?= number_format($d['dpp']) ?></td>
					<td class="<?= $d['ppn'] == 0 ? 'zero' : '' ?>"><?= number_format($d['ppn']) ?></td>
				</tr>
			<?php
				$num++;
				$sol += $d['sol'];
				$sdl += $d['sdl'];
				$sdc += $d['sdc'];
				$jum_order += $d['jum_order'];
				$total_qty += $d['total_qty'];
				$faktur += $d['faktur'];
				$dpp += $d['dpp'];
				$ppn += $d['ppn'];
			endwhile;
			?>
			<tr class="bold">
				<td colspan="2">Total</td>
				<td class="<?= $sol == 0 ? 'zero' : '' ?>"> <?= number_format($sol) ?></td>
				<td class="<?= $sdl == 0 ? 'zero' : '' ?>"> <?= number_format($sdl) ?></td>
				<td class="<?= $sdc == 0 ? 'zero' : '' ?>"> <?= number_format($sdc) ?></td>
				<td class="<?= $jum_order == 0 ? 'zero' : '' ?>"> <?= number_format($jum_order) ?></td>
				<td class="<?= $total_qty == 0 ? 'zero' : '' ?>"> <?= number_format($total_qty) ?></td>
				<td class="<?= $faktur == 0 ? 'zero' : '' ?>"> <?= number_format($faktur) ?></td>
				<td class="<?= $dpp == 0 ? 'zero' : '' ?>"> <?= number_format($dpp) ?></td>
				<td class="<?= $ppn == 0 ? 'zero' : '' ?>"> <?= number_format($ppn) ?></td>
			</tr>
		</tbody>
	</table>
</body>
<script>
	function displayLiveTime() {
		var currentDate = new Date();

		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1; // Ingat, bulan dimulai dari 0 (Januari) hingga 11 (Desember)
		var year = currentDate.getFullYear();
		var hours = currentDate.getHours();
		var minutes = currentDate.getMinutes();
		var seconds = currentDate.getSeconds();

		var formattedTime = day.toString().padStart(2, 0) + '/' + month.toString().padStart(2, 0) + '/' + year + ' ' + hours.toString().padStart(2, 0) + ':' + minutes.toString().padStart(2, 0) + ':' + seconds.toString().padStart(2, 0);

		document.getElementById("timestamp").innerHTML = formattedTime;
	}


	displayLiveTime();

	setInterval(displayLiveTime, 1000);

	window.print()
</script>

</html>