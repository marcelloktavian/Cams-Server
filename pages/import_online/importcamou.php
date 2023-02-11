<!DOCTYPE html>
<html>
<head>
	<title>IMPORT XLS DARI CAMOU.CO.ID</title>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link rel="stylesheet" type="text/css" href="importxls_style.css">
	<?php
	 include("../../include/koneksi.php");
	?>
</head>
<body>

	<table>
		<!--form upload file-->
		<form method="post" enctype="multipart/form-data" >
			<tr>
				<td>Pilih File
				<input name="filemhsw" type="file" required="required"></td>
			</tr>
			<tr>
				<td><input name="upload" type="submit" value="Import">
				&nbsp;&nbsp;&nbsp;&nbsp;<a href="posting.php">POSTING TO PRE SALES</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="deletexls.php">DELETE ALL </a></td>
			</tr>
		</form>
	</table>
	<?php
	if (isset($_POST['upload'])) {

		require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
		require('spreadsheet-reader-master/SpreadsheetReader.php');

		//upload data excel kedalam folder uploads
		$target_dir = "uploads/".basename($_FILES['filemhsw']['name']);
		
		move_uploaded_file($_FILES['filemhsw']['tmp_name'],$target_dir);

		$Reader = new SpreadsheetReader($target_dir);

		foreach ($Reader as $Key => $Row)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
  		    if ($Key < 2) continue;			
			$sql_insert="INSERT into oln_xlscamou(
			`oln_order_id`,
			`oln_productid`,
			`oln_productname`,
			`oln_price`,
			`oln_qty`,
			`oln_totalprice`,
			`oln_tax`,
			`oln_size`,
			`oln_note`,
			`oln_ordertotal`,
			`oln_orderstatus`,
			`oln_customer`,
			`oln_customerid`,
			`oln_customer_email`,
			`oln_customer_telp`,
			`oln_expnote`,
			`oln_penerima`,
			`oln_address`,
			`oln_telp`,
			`oln_provinsi`,
			`oln_postalcode`,
			`oln_kotakab`,
			`oln_kecamatan`,
			`oln_shipmethod`,
			`oln_customer_address`,
			`oln_customer_provinsi`,
			`oln_customer_postalcode`,
			`oln_customer_kotakab`,
			`oln_customer_kecamatan`,
			`oln_tgl`) VALUES(
			'".$Row[0]."',
			'".$Row[1]."',
			'".$Row[2]."',
			'".$Row[3]."',
			'".$Row[4]."',
			'".$Row[5]."',
			'".$Row[6]."',
			'".$Row[7]."',
			'".$Row[8]."',
			'".$Row[9]."',
			'".$Row[10]."',
			'".$Row[11]."',
			'".$Row[12]."',
			'".$Row[13]."',
			'".$Row[14]."',
			'".$Row[15]."',
			'".$Row[16]."',
			'".$Row[17]."',
			'".$Row[18]."',
			'".$Row[19]."',
			'".$Row[20]."',
			'".$Row[21]."',
			'".$Row[22]."',
			'".$Row[23]."',
			'".$Row[24]."',
			'".$Row[25]."',
			'".$Row[26]."',
			'".$Row[27]."',
			'".$Row[28]."',
			'".$Row[29]."')";
			//$query=mysql_query("INSERT INTO mahasiswa(nim,nama,alamat,jurusan) VALUES ('".$Row[0]."', '".$Row[1]."','".$Row[2]."','".$Row[3]."')");
			$query=mysql_query($sql_insert);
			
			}
		if ($query) {
				echo "Import data berhasil";
			}else{
				echo mysql_error();
			}
	}
	?>
	
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
		<?php 		
		$no=1;
		$data = mysql_query("select * from oln_xlscamou");
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