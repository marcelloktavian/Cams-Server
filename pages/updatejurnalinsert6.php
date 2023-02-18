<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<?php
	include("../include/koneksi.php");
	?>
</head>
<body>	
		<table border ="1px">
			<thead>
                <tr>
                    <th colspan='8'>DATA PENJUALAN CASH TGL 6 JANUARI 2023</th>
                </tr>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">ID</th>
					<th class="text-left">No OLN</th>
					<th class="text-left">Tanggal</th>
					<th class="text-left">Dropshipper</th>
					<th class="text-left">Total</th>
					<th class="text-left">DPP</th>
					<th class="text-left">PPN</th>
				</tr>
			</thead>
			<?php 		
			$no=1;

			$data = mysql_query("SELECT olnso.id_trans, olnso.total, olnso.transfer, olnso.deposit, olnso.id_dropshipper,mst_dropshipper.nama, date(olnso.lastmodified) as tgl, ROUND(total/1.11) AS dpp, ROUND(total/1.11*0.11) AS ppn, olnso.lastmodified, olnso.id 
			FROM olnso 
			LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper 
			WHERE DATE(olnso.lastmodified) = '2023-01-06' AND piutang = 0");
			while($d = mysql_fetch_array($data)){
				$idtrans=$d['id_trans'];
				$transfer=$d['transfer'];
				$deposit=$d['deposit'];
				$total=$d['total'];
				$dropshipper=$d['id_dropshipper'];
				$namadropshipper=$d['nama'];

				$masterNo = '';
					$q = mysql_fetch_array( mysql_query("SELECT IF(LENGTH(RIGHT(no_jurnal, 5)+1) = 1, CONCAT('0000',(RIGHT(no_jurnal, 5)+1)), IF(LENGTH(RIGHT(no_jurnal, 5)+1) = 2, 
					CONCAT('000',(RIGHT(no_jurnal, 5)+1)), IF(LENGTH(RIGHT(no_jurnal, 5)+1) = 3,CONCAT('00',(RIGHT(no_jurnal, 5)+1)), IF(LENGTH(RIGHT(no_jurnal, 5)+1) = 4,CONCAT('0',(RIGHT(no_jurnal, 5)+1)), CONCAT('',(RIGHT(no_jurnal, 5)+1))) ) ) ) AS nomor 
					FROM jurnal WHERE DATE(tgl) = '2023-01-06' ORDER BY id DESC LIMIT 1"));
				$masterNo='230106'.$q['nomor'];

				$id_user = 'marcell';

				// execute for master
				$sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`) VALUES ('$masterNo','".$d['tgl']."','Penjualan OLN Cash - $namadropshipper - $idtrans','$total','$total','0','$id_user',NOW()) ";
				mysql_query($sql_master) or die (mysql_error());

				//get master id terakhir
				$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
				$idparent=$q['id'];
				
				$dpp = $total / 1.11;
				$ppn = $total / 1.11 * 0.111;

				if($transfer > 0 && $deposit == 0){
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='01.01.00002'");
					while($akun1 = mysql_fetch_array($query1)){
						// BCA
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}else if($transfer == 0 && $deposit > 0){
					//deposit only
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('02.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
					while($akun1 = mysql_fetch_array($query1)){
						// saldo titipan
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}else if($transfer > 0 && $deposit > 0){
					//deposit dan transfer
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='01.01.00002'");
					while($akun1 = mysql_fetch_array($query1)){
						// BCA
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$transfer','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}

					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('02.02.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
					while($akun1 = mysql_fetch_array($query1)){
						// saldo titipan
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$deposit','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}

				$query2=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.01.',IF(LENGTH('$dropshipper')=1,'0000',IF(LENGTH('$dropshipper')=2,'000',IF(LENGTH('$dropshipper')=3,'00',IF(LENGTH('$dropshipper')=4,'0','')))), '$dropshipper')");
				while($akun2 = mysql_fetch_array($query2)){
					// penjualan oln cash
					$sqlakun2="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun2['id']."','".$akun2['noakun']."','".$akun2['nama']."','".$akun2['status']."','0','$dpp','','0', '$id_user',NOW()) ";
					mysql_query($sqlakun2) or die (mysql_error());
				}

				$query3=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.01.00000'");
				while($akun3 = mysql_fetch_array($query3)){
					// ppn
					$sqlakun3="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun3['id']."','".$akun3['noakun']."','".$akun3['nama']."','".$akun3['status']."','0','$ppn','','0', '$id_user',NOW()) ";
					mysql_query($sqlakun3) or die (mysql_error());
				}
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['id'] ?></td>
					<td class="text-left"><?php echo $d['id_trans']; ?></td>
					<td class="text-left"><?php echo $d['lastmodified']; ?></td>
					<td class="text-left"><?php echo $namadropshipper; ?></td>
					<td class="text-left"><?php echo number_format($d['total']); ?></td>
					<td class="text-left"><?php echo number_format($d['dpp']); ?></td>
					<td class="text-left"><?php echo number_format($d['ppn']); ?></td>
				</tr>
				<?php
				$no++;
			}
			?>
		</tbody>
	</table>
			
</body>
</html>