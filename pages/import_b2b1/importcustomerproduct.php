<!DOCTYPE html>
<html>
<head>
	<title>IMPORT B2B CUSTOMER PRODUCT</title>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link rel="stylesheet" type="text/css" href="importb2b_style_cusproduct.css">
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
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="posting.php">POSTING TO CUSTOMER PRODUCT</a>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="deletexls.php">DELETE ALL</a>
					</td>

				</tr>
			</form>
		</table>
		<?php
		error_reporting(0);
		if (isset($_POST['upload'])) {

			require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
			require('spreadsheet-reader-master/SpreadsheetReader.php');

		//upload data excel kedalam folder uploads
			$target_dir = "uploads/".basename($_FILES['filemhsw']['name']);
		// var_dump($target_dir);die;
			move_uploaded_file($_FILES['filemhsw']['tmp_name'],$target_dir);

			$Reader = new SpreadsheetReader($target_dir);

			$i = 0;
			$duplicate = '';
			$od = '';
			$total = 0;
			$idnya = '';

			foreach ($Reader as $Key => $Row)
			{
				if ($Key < 2) continue;
					if ($Row[0]==0 || $Row[0] == NULL || $Row[0]=='') {
						echo "<h1>Customer Kosong</h1>";
						die;
					}
			}
			
				foreach ($Reader as $Key => $Row)
				{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
					if ($Key < 1) continue;
					$penerima="";
					if ($Row[0]!=null || $Row[0]!='') {
						
						$sql_insert="INSERT INTO `b2b_xls_custproduct`(`id_product`, 
                        `product`, 
                        `id_customer`, 
                        `customer`, 
                        `price`, 
                        `disc`, 
                        `nettprice`) VALUES(
						'".$Row[0]."',
						'".$Row[1]."',
						'".$Row[2]."',
						'".$Row[3]."',
						'".$Row[4]."',
						'".$Row[5]."',
                        '".$Row[6]."')";
                        
						$query=mysql_query($sql_insert);
					}		
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
                    <th class="text-left">ID</th>
					<th class="text-left">ID Customer</th>
					<th class="text-left">Customer</th>
					<th class="text-left">ID Product</th>
					<th class="text-left">Product</th>
					<th class="text-left">Price</th>
					<th class="text-left">Disc</th>
					<th class="text-left">Nett Price</th>
					<th class="text-left">delete</th>

				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("select * from b2b_xls_custproduct");
			while($d = mysql_fetch_array($data)){
				?>
                    <tr>
                        <td class="text-left"><?php echo $d['id']; ?></td>
                        <td class="text-left"><?php echo $d['id_customer']; ?></td>
                        <td class="text-left"><?php echo $d['customer']; ?></td>
                        <td class="text-left"><?php echo $d['id_product']; ?></td>
                        <td class="text-left"><?php echo $d['product']; ?></td>
                        <td class="text-left"><?php echo number_format($d['price']); ?></td>
                        <td class="text-left"><?php echo number_format($d['disc']); ?></td>
                        <td class="text-left"><?php echo number_format($d['nettprice']); ?></td>
                        <td class="text-left"><a href="deletexlsid.php?id=<?php echo $d['id'];?>">
					DELETE </a></td>
                    </tr>
				<?php 
			}
			?>
		</tbody>
	</table>

</body>
</html>