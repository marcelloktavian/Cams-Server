<?php
error_reporting(0);
include("../../include/koneksi.php");
include("../../include/config.php");
$category = $_GET['category'];
$tglstart = $_GET['start'];
$tglend = $_GET['end'];

$where_detail = "";

if ($category != '') {
	$where_detail .= " AND m.id_kategori = '$category'";
}

$where_detail .= " AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND m.state='1' AND m.deleted=0 ";

$query = "SELECT
	p.nama,
	count(*), 
		IFNULL(SUM(IF((det.qty31) > 0, det.qty31, 0)),0) AS s31,
		IFNULL(SUM(IF((det.qty32) > 0, det.qty32, 0)),0) AS s32,
		IFNULL(SUM(IF((det.qty33) > 0, det.qty33, 0)),0) AS s33,
		IFNULL(SUM(IF((det.qty34) > 0, det.qty34, 0)),0) AS s34,
		IFNULL(SUM(IF((det.qty35) > 0, det.qty35, 0)),0) AS s35,
		IFNULL(SUM(IF((det.qty36) > 0, det.qty36, 0)),0) AS s36,
		IFNULL(SUM(IF((det.qty37) > 0, det.qty37, 0)),0) AS s37,
		IFNULL(SUM(IF((det.qty38) > 0, det.qty38, 0)),0) AS s38,
		IFNULL(SUM(IF((det.qty39) > 0, det.qty39, 0)),0) AS s39,
		IFNULL(SUM(IF((det.qty40) > 0, det.qty40, 0)),0) AS s40,
		IFNULL(SUM(IF((det.qty41) > 0, det.qty41, 0)),0) AS s41,
		IFNULL(SUM(IF((det.qty42) > 0, det.qty42, 0)),0) AS s42,
		IFNULL(SUM(IF((det.qty43) > 0, det.qty43, 0)),0) AS s43,
		IFNULL(SUM(IF((det.qty44) > 0, det.qty44, 0)),0) AS s44,
		IFNULL(SUM(IF((det.qty45) > 0, det.qty45, 0)),0) AS s45,
		IFNULL(SUM(IF((det.qty46) > 0, det.qty46, 0)),0) AS s46,

		(IFNULL(SUM(IF((det.qty31) > 0, det.qty31, 0)),0) +
		IFNULL(SUM(IF((det.qty32) > 0, det.qty32, 0)),0) +
		IFNULL(SUM(IF((det.qty33) > 0, det.qty33, 0)),0) +
		IFNULL(SUM(IF((det.qty34) > 0, det.qty34, 0)),0) +
		IFNULL(SUM(IF((det.qty35) > 0, det.qty35, 0)),0) +
		IFNULL(SUM(IF((det.qty36) > 0, det.qty36, 0)),0) + 
		IFNULL(SUM(IF((det.qty37) > 0, det.qty37, 0)),0) +
		IFNULL(SUM(IF((det.qty38) > 0, det.qty38, 0)),0) +
		IFNULL(SUM(IF((det.qty39) > 0, det.qty39, 0)),0) +
		IFNULL(SUM(IF((det.qty40) > 0, det.qty40, 0)),0) +
		IFNULL(SUM(IF((det.qty41) > 0, det.qty41, 0)),0) +
		IFNULL(SUM(IF((det.qty42) > 0, det.qty42, 0)),0) +
		IFNULL(SUM(IF((det.qty43) > 0, det.qty43, 0)),0) +
		IFNULL(SUM(IF((det.qty44) > 0, det.qty44, 0)),0) +
		IFNULL(SUM(IF((det.qty45) > 0, det.qty45, 0)),0) +
		IFNULL(SUM(IF((det.qty46) > 0, det.qty46, 0)),0) ) AS subtotal
FROM
	mst_b2bproductsgrp p
	LEFT JOIN b2bso_detail det ON det.id_product = p.id
	LEFT JOIN b2bso m ON det.id_trans = m.id_trans
	WHERE p.deleted = 0 $where_detail GROUP BY p.id HAVING subtotal > 0 ORDER BY p.nama";

$i = 1;
$nomer = 0;
$grand_qty = 0;

$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
foreach ($data as $x) {
	$grand_qty += $x['subtotal'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>B2B Statistic Products Report</title>
	<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
	<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
	<style type="text/css">
		body {
			font-size: 10pt;
			/* font-family: Tahoma; */
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
			size: landscape;
		}

		@media print {
			@page {
				size: landscape
			}
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		th,
		td {
			border: 1px solid black;
			padding: 2px;
			text-align: center;
		}

		.data {
			border: 1px solid black;
			padding: 2px;
			text-align: left;

		}

		tbody {
			font-size: 14px;
		}

		.zero {
			color: #acb0ae;
		}

		.bold {
			font-weight: bold;
			font-size: large;
		}

		.separator {
			border-right: 3px solid black;
		}

		.top {
			border-top: 2px solid black;
		}

		.dashed {
			border-bottom: 1px dashed black;
			border-top: 0px;
		}

		.left {
			text-align: left;
		}

		.right {
			text-align: right;
		}
	</style>
</head>

<body>
	<div class="title_dir">
		<span class="title">
			B2B PRODUCT REPORT
		</span>
		<span id="timestamp" style="font-size: small;"><?php date_default_timezone_set('Asia/Jakarta');
														echo $timestamp = date('d/m/Y H:i:s'); ?>
		</span>
	</div>
	<div style="margin-bottom: 20px;font-size: small; ">
		<span>Dari <?php echo "" . $tglstart; ?>&nbsp;-&nbsp;<?php echo "" . $tglend; ?></span>
		&nbsp;
		&nbsp;
		&nbsp;
		<span>
			Total Produk <?= $grand_qty ?>
		</span>
	</div>
	<table>
		<thead>
			<tr>
				<th>No.</th>
				<th>Nama</th>
				<th>31</th>
				<th>32</th>
				<th>33</th>
				<th>34</th>
				<th>35</th>
				<th>36</th>
				<th>37</th>
				<th>38</th>
				<th>39</th>
				<th>40</th>
				<th>41</th>
				<th>42</th>
				<th>43</th>
				<th>44</th>
				<th>45</th>
				<th>46</th>
				<th>Total Qty</th>
				<th>%</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $line) : ?>

				<tr>
					<td class="dashed"><?= $i ?></td>
					<td class="dashed data"><?= $line['nama'] ?></td>
					<td class="dashed <?= $line['s31'] == 0 ? "zero" : "" ?>"><?= number_format($line['s31']) ?></td>
					<td class="dashed <?= $line['s32'] == 0 ? "zero" : "" ?>"><?= number_format($line['s32']) ?></td>
					<td class="dashed <?= $line['s33'] == 0 ? "zero" : "" ?>"><?= number_format($line['s33']) ?></td>
					<td class="dashed <?= $line['s34'] == 0 ? "zero" : "" ?>"><?= number_format($line['s34']) ?></td>
					<td class="dashed <?= $line['s35'] == 0 ? "zero" : "" ?>"><?= number_format($line['s35']) ?></td>
					<td class="dashed <?= $line['s36'] == 0 ? "zero" : "" ?>"><?= number_format($line['s36']) ?></td>
					<td class="dashed <?= $line['s37'] == 0 ? "zero" : "" ?>"><?= number_format($line['s37']) ?></td>
					<td class="dashed <?= $line['s38'] == 0 ? "zero" : "" ?>"><?= number_format($line['s38']) ?></td>
					<td class="dashed <?= $line['s39'] == 0 ? "zero" : "" ?>"><?= number_format($line['s39']) ?></td>
					<td class="dashed <?= $line['s40'] == 0 ? "zero" : "" ?>"><?= number_format($line['s40']) ?></td>
					<td class="dashed <?= $line['s41'] == 0 ? "zero" : "" ?>"><?= number_format($line['s41']) ?></td>
					<td class="dashed <?= $line['s42'] == 0 ? "zero" : "" ?>"><?= number_format($line['s42']) ?></td>
					<td class="dashed <?= $line['s43'] == 0 ? "zero" : "" ?>"><?= number_format($line['s43']) ?></td>
					<td class="dashed <?= $line['s44'] == 0 ? "zero" : "" ?>"><?= number_format($line['s44']) ?></td>
					<td class="dashed <?= $line['s45'] == 0 ? "zero" : "" ?>"><?= number_format($line['s45']) ?></td>
					<td class="dashed <?= $line['s46'] == 0 ? "zero" : "" ?>"><?= number_format($line['s46']) ?></td>
					<td class="dashed"><?= number_format($line['subtotal']) ?></td>
					<td class="dashed"><?= number_format($line['subtotal'] / $grand_qty * 100, 2) ?></td>
				</tr>
			<?php $i++;
			endforeach; ?>
			<tr>
				<td colspan="18" class="right">Total</td>
				<td><?= $grand_qty ?></td>
				<td colspan="1">PCS</td>
			</tr>
		</tbody>
	</table>
</body>

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
	<?php
	?>
</script>

</html>