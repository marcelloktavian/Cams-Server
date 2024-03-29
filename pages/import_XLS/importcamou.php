<!DOCTYPE html>
<html>
<head>
	<title>IMPORT XLS DARI CAMOU.CO.ID</title>
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
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="posting.php">POSTING TO PRE SALES</a>
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

			$totRef=0;
			$totXls=0;
			$totPreso=0;
			$totXlsTemp=0;
			$totBarang=0;
			$i = 0;
			$duplicate = '';
			$od = '';
			$barang = '';

			foreach ($Reader as $Key => $Row)
			{	
				if ($Key < 2) continue;
				if ($Row[0]!=null || $Row[0]!='') {
					//cek id procust kosong atau tidak
					if ($Row[1]==0 || $Row[1] == NULL || $Row[1]=='') {
						echo "<h1>ID Product Kosong</h1>";
						die;
					}
					$size = explode("::",$Row[7]);

					$getTotXls3="SELECT COUNT(*) AS totxls FROM `oln_xlscamou_temp` WHERE (oln_order_id='".$Row[0]."' AND oln_productid='".$Row[1]."' AND size='".$size[1]."')";
					// var_dump($getTotXls3);
					$data3 = mysql_query($getTotXls3);
					$rs3 = mysql_fetch_array($data3);
					$totXlsTemp+=$rs3['totxls'];

					$penerima="";

					if ($Row[0]!=null || $Row[0]!='') {
						$size = explode("::",$Row[7]);

						$getHarga="SELECT harga, stok FROM `inventory_balance` WHERE `oln_product_id`='".$Row[1]."' AND size='".trim($size[1])."' AND deleted=0";
						// var_dump($getHarga);
						$data = mysql_query($getHarga);
						$rs = mysql_fetch_array($data);
						$harga=$rs['harga'];
						$stok=$rs['stok'];

						$getDisc="SELECT disc FROM `mst_dropshipper` WHERE `oln_customer_id`='".$Row[12]."'";
						$data = mysql_query($getDisc);
						$rs = mysql_fetch_array($data);
						$disc=$rs['disc'];

						$harga_disc = (int)$harga - round((float)$harga * (float)$disc);
						$harga_akhir = round($harga_disc/1.11);


						$penerima=$Row[16]." ".$Row[17];

						$address = str_replace("'", "", str_replace("&#39;", "", $Row[18]));
						$cust_address = str_replace("'", "", str_replace("&#39;", "", $Row[25]));

						$sql_insert="INSERT into oln_xlscamou_temp	(
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
						'".addslashes($Row[2])."', 
						'".$harga_akhir."', 
						'".$Row[4]."', 
						'".($harga_akhir*$Row[4])."', 
						'".(($harga_akhir*$Row[4])*0.11)."', 
						'".trim($size[1])."', 
						'".$Row[8]."', 
						'".str_replace(",","",str_replace("Rp. ","",$Row[9]))."', 
						'".$Row[10]."', 
						'".$Row[11]."', 
						'".$Row[12]."', 
						'".$Row[13]."', 
						'".$Row[14]."', 
						'".$Row[15]."', 
						'".$penerima."', 
						'".$address."', 
						'".$Row[19]."', 
						'".$Row[20]."', 
						'".$Row[21]."', 
						'".$Row[22]."', 
						'".$Row[23]."', 
						'".$Row[24]."', 
						'".$cust_address."', 
						'".$Row[26]."', 
						'".$Row[27]."', 
						'".$Row[28]."', 
						'".$Row[29]."', 
						'".$Row[30]."')"; 

						$query=mysql_query($sql_insert);

						if(($stok - $Row[4])<0){
							$totBarang++;
							$barang = 'ORDER ID : '.$Row[0].", PRODUCT ID : ".$Row[1].", SIZE : ".$size[1].", STOK : ".$stok;
						}
					}

					$getRefCode="SELECT COUNT(*) AS totoln FROM `olnso` WHERE ref_kode = '".$Row[0]."' AND exp_code='".$Row[15]."' AND deleted=0 ";
					// $getRefCode="SELECT COUNT(*) AS totoln FROM `olnso` WHERE (exp_code <> '' AND exp_code is NOT NULL AND exp_code='".$Row[15]."') AND deleted=0 ";
					$data0 = mysql_query($getRefCode);
					$rs0 = mysql_fetch_array($data0);
					$totRef+=$rs0['totoln'];
					// if ($rs0['totoln']>0 && $i==0 && $od != $Row[0]) {
					// 	$duplicate .= $Row[0];
					// 	$i++;
					// }else if($rs0['totoln']>0 && $i>0 && $od != $Row[0]){
					// 	$duplicate .= ', '.$Row[0];
					// }

					$getTotXls1="SELECT COUNT(*) AS totxls FROM `oln_xlscamou` WHERE (oln_order_id='".$Row[0]."' AND oln_expnote='".$Row[15]."') OR (oln_order_id='".$Row[0]."' AND oln_productid='".$Row[1]."')";
					// ' AND oln_productid='".$Row[1]."' 
					$data1 = mysql_query($getTotXls1);
					$rs1 = mysql_fetch_array($data1);
					$totXls+=$rs1['totxls'];
					// if ($rs1['totxls']>0 && $i==0 && $od != $Row[0]) {
					// 	$duplicate .= $Row[0];
					// 	$i++;
					// }else if($rs1['totxls']>0 && $i>0 && $od != $Row[0]){
					// 	$duplicate .= ', '.$Row[0];
					// }

					$getTotXls2="SELECT COUNT(*) AS totpreso FROM `olnpreso` WHERE (oln_order_id='".$Row[0]."' AND oln_expnote='".$Row[15]."') OR (oln_order_id='".$Row[0]."' AND oln_productid='".$Row[1]."')";
					$data2 = mysql_query($getTotXls2);
					$rs2 = mysql_fetch_array($data2);
					$totPreso+=$rs2['totpreso'];
					// if ($rs2['totpreso']>0 && $i==0 && $od != $Row[0]) {
					// 	$duplicate .= $Row[0];
					// 	$i++;
					// }else if($rs2['totpreso']>0 && $i>0 && $od != $Row[0]){
					// 	$duplicate .= ', '.$Row[0];
					// }

					// $od=$Row[0];

					if($totXlsTemp>0){
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Ada Barang Yang Terduplikasi</h1>";
						die;
					}

					if($totBarang>0){
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Cek Ulang Stok Barang</h1>";
						echo $barang;
						die;
					}

					if ($totXls>0 || $totPreso>0 || $totRef>0) {
				// echo "<h1>Duplicate Import order id ".$duplicate."</h1>";
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Duplicate Import</h1>";
						die;
					}

					if ($Row[9] == '0') {
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Cek Ulang Totalan Import ".$Row[0]."</h1>";
						die;
					}

					if ($Row[4] == '0') {
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Cek Ulang Qty Import ".$Row[0]."</h1>";
						die;
					}

					$brg = str_replace($Row[2],"",str_replace(" >> Ukuran :: ", "", $Row[7]));
					$getHarga="SELECT harga FROM `mst_products` WHERE `oln_product_id`='".$Row[1]."' AND size='".$brg."'";
					$data = mysql_query($getHarga);
					$rs = mysql_fetch_array($data);
					$harga=$rs['harga'];
					if ($harga == '' || $harga == '0') {
						$sql_delete="TRUNCATE TABLE oln_xlscamou_temp";	
						$hasil_delete=mysql_query($sql_delete);

						echo "<h1>Cek Ulang Barang/Size ".$Row[0]."</h1>";
						die;
					}
				}
			}

			if ($totXls==0 && $totPreso==0 && $totRef==0) {
				$sql_delete = "TRUNCATE TABLE oln_xlscamou_temp;";
				$query=mysql_query($sql_delete);

				foreach ($Reader as $Key => $Row)
				{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
					if ($Key < 2) continue;
					$penerima="";

					if ($Row[0]!=null || $Row[0]!='') {
						$size = explode("::",$Row[7]);

						$getHarga="SELECT harga FROM `mst_products` WHERE `oln_product_id`='".$Row[1]."' AND size='".trim($size[1])."'";
						// var_dump($getHarga);
						$data = mysql_query($getHarga);
						$rs = mysql_fetch_array($data);
						$harga=$rs['harga'];

						$getDisc="SELECT disc FROM `mst_dropshipper` WHERE `oln_customer_id`='".$Row[12]."'";
						$data = mysql_query($getDisc);
						$rs = mysql_fetch_array($data);
						$disc=$rs['disc'];

						$harga_disc = (int)$harga - round((float)$harga * (float)$disc);
						$harga_akhir = round($harga_disc/1.11);


						$penerima=$Row[16]." ".$Row[17];

						$address = str_replace("'", "", str_replace("&#39;", "", $Row[18]));
						$cust_address = str_replace("'", "", str_replace("&#39;", "", $Row[25]));

						$sql_insert="INSERT into oln_xlscamou	(
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
						'".addslashes($Row[2])."', 
						'".$harga_akhir."', 
						'".$Row[4]."', 
						'".($harga_akhir*$Row[4])."', 
						'".(($harga_akhir*$Row[4])*0.11)."', 
						'".trim($size[1])."', 
						'".$Row[8]."', 
						'".str_replace(",","",str_replace("Rp. ","",$Row[9]))."', 
						'".$Row[10]."', 
						'".$Row[11]."', 
						'".$Row[12]."', 
						'".$Row[13]."', 
						'".$Row[14]."', 
						'".$Row[15]."', 
						'".$penerima."', 
						'".$address."', 
						'".$Row[19]."', 
						'".$Row[20]."', 
						'".$Row[21]."', 
						'".$Row[22]."', 
						'".$Row[23]."', 
						'".$Row[24]."', 
						'".$cust_address."', 
						'".$Row[26]."', 
						'".$Row[27]."', 
						'".$Row[28]."', 
						'".$Row[29]."', 
						'".$Row[30]."')"; 
			//$query=mysql_query("INSERT INTO mahasiswa(nim,nama,alamat,jurusan) VALUES ('".$Row[0]."', '".$Row[1]."','".addslashes($Row[2])."','".$Row[3]."')");
			// var_dump($sql_insert);die;
						$query=mysql_query($sql_insert);
					}
				}
				if ($query) {
					echo "Import data berhasil";
				}else{
					echo mysql_error();
				}
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
					<th class="text-left">delete</th>

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
					<td class="text-left"><a href="deletexlsid.php?id=<?php echo $d['oln_order_id'];?>">
					DELETE </a></td>

				</tr>
				<?php 
			}
			?>
		</tbody>
	</table>

</body>
</html>