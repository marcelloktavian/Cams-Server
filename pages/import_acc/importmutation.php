<!DOCTYPE html>
<html>
<head>
	<title>IMPORT MUTATION</title>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link rel="stylesheet" type="text/css" href="importxls_style.css">
	<?php
	include("../../include/koneksi.php");
	error_reporting(0);
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
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="posting.php">POSTING TO PRE BANK</a>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="deletexls.php">DELETE ALL</a>
					</td>

				</tr>
			</form>
		</table>
		<?php
		// error_reporting(0);
		if (isset($_POST['upload'])) {

			require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
			require('spreadsheet-reader-master/SpreadsheetReader.php');

		//upload data excel kedalam folder uploads
			$target_dir = "uploads/".basename($_FILES['filemhsw']['name']);

			move_uploaded_file($_FILES['filemhsw']['tmp_name'],$target_dir);

			$Reader = new SpreadsheetReader($target_dir);

			$norek = '';
			$nmrek = '';
			$periode = '';
			$matauang = '';
			$saldoawal = 0;
			$mutasi = 0;

				foreach ($Reader as $Key => $Row)
				{	
					if ($Key < 1) continue;
					$ex1 = explode(":",$Row[0]);

					if($Key == 2){
						$norek = trim($ex1[1]);
					}
					if($Key == 3){
						$nmrek = trim($ex1[1]);
					}
					if($Key == 4){
						$periode = trim($ex1[1]);
					}
					if($Key == 5){
						$matauang = trim($ex1[1]);
					}
					if($ex1[0]=='Saldo Awal '){
						$saldoawal = str_replace(",","",trim($ex1[1]));
					}
					if($ex1[0]=='Mutasi Kredit '){
						$mutasi =str_replace(",","", trim($ex1[1]));
					}
				}


				foreach ($Reader as $Key => $Row)
				{	
					if ($Key < 7) continue;
					$ex1 = explode(":",$Row[0]);
					if($ex1[0]!='Saldo Awal ' && $ex1[0]!='Mutasi Kredit ' && $ex1[0]!=NULL){
						$sql = "INSERT INTO `acc_xlsmutation`(`norek`, `namarek`, `periode`, `matauang`, `tanggal_trans`, `keterangan`, `cabang`, `jumlah`, `saldoawal`, `mutasikredit`) VALUES ('$norek','$nmrek','$periode','$matauang','".$Row[0]."','".$Row[1]."','".$Row[2]."','".str_replace(",","",str_replace(".00 CR","",$Row[3]))."','$saldoawal','$mutasi')";
						$query=mysql_query($sql);
					}
					
				}
				
			}
		?>

		<table class="table-fill">
			<thead>
				<tr>
					<th class="text-left">id</th>
					<th class="text-left">No Rekening</th>
					<th class="text-left">Nama Rekening</th>
					<th class="text-left">Periode</th>
					<th class="text-left">Mata Uang</th>
					<th class="text-left">Tanggal Transaksi</th>
					<th class="text-left">Keterangan</th>
					<th class="text-left">Cabang</th>
					<th class="text-left">Jumlah</th>
					<th class="text-left">Delete</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("select * from acc_xlsmutation");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $d['id']; ?></td>
					<td class="text-left"><?php echo $d['norek']; ?></td>
					<td class="text-left"><?php echo $d['namarek'] ?></td>
					<td class="text-left"><?php echo $d['periode'] ?></td>
					<td class="text-left"><?php echo $d['matauang'] ?></td>
					<td class="text-left"><?php echo $d['tanggal_trans'] ?></td>
					<td class="text-left"><?php echo $d['keterangan'] ?></td>
					<td class="text-left"><?php echo $d['cabang'] ?></td>
					<td class="text-right"><?php echo number_format($d['jumlah']); ?></td>
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