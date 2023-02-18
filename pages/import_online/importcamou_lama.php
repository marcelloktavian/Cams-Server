<!DOCTYPE html>
<html>
<head>
	<title>IMPORT XLS DARI CAMOU.CO.ID</title>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link rel="stylesheet" type="text/css" href="importxls_style.css">
</head>
<body>
	<?php 
	if(isset($_GET['berhasil'])){
		echo "<p>".$_GET['berhasil']." Data berhasil di import.</p>";
	}
	else if(isset($_GET['posting'])){
		echo "<p> Data berhasil di posting.</p>";
	}
	?>

	<h3><a href="upload.php">IMPORT DATA</a>&nbsp;|&nbsp;<a href="posting.php">POSTING TO PRE SALES</a></h3>
	<table class="table-fill">
	<thead>
	<tr>
			<th class="text-left">id</th>
			<th class="text-left">od_id</th>
			<th class="text-left">product</th>
			<th class="text-left">price</th>
			<th class="text-left">qty</th>
			<th class="text-left">totalprice</th>
			<th class="text-left">tax</th>
			<th class="text-left">total</th>
			<th class="text-left">dropshipper</th>
			<th class="text-left">penerima</th>
			<th class="text-left">tanggal</th>
			<th class="text-left">status</th>
			<th class="text-left">id_dp</th>
			<th class="text-left">id_p</th>
			
		</tr>
		</thead>
        <tbody class="table-hover">
		<?php 
		error_reporting(0);
		include("../../include/koneksi.php");
		$no=1;
		$data = mysql_query("SELECT xls.* FROM oln_xlscamou xls ");
		while($d = mysql_fetch_array($data)){
			?>
			<tr>
				<td class="text-left"><?php echo $d['id']; ?></td>
				<td class="text-left"><?php echo $d['oln_order_id']; ?></td>
				<td class="text-left"><?php echo $d['oln_productname'].'-'.$d['oln_size']; ?></td>
				<td class="text-right"><?php echo number_format($d['oln_price']); ?></td>
				<td class="text-right"><?php echo number_format($d['oln_qty']); ?></td>
				<td class="text-right"><?php echo number_format($d['oln_totalprice']); ?></td>
				<td class="text-right"><?php echo number_format($d['oln_tax']); ?></td>
				<td class="text-right"><?php echo number_format($d['oln_ordertotal']); ?></td>
				<td class="text-left"><?php echo $d['oln_customer']; ?></td>
				<td class="text-left"><?php echo $d['oln_penerima']; ?></td>
				<td class="text-left"><?php echo $d['oln_tgl']; ?></td>
				<td class="text-left"><?php echo $d['oln_orderstatus']; ?></td>
				<td class="text-left"><?php echo $d['dropshipper_id']; ?></td>
				<td class="text-left"><?php echo $d['product_id']; ?></td>
				
			</tr>
			<?php 
		}
		?>

	</tbody>
	</table>


	
</body>
</html>