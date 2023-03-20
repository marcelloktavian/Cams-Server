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
                    <th colspan='8'>DATA PENJUALAN CASH</th>
                </tr>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">ID</th>
					<th class="text-left">No Jurnal</th>
					<th class="text-left">Total</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("  SELECT jurnal.id, no_jurnal, total_debet AS a, SUM(debet) AS b, RIGHT(jurnal.keterangan,12) as oln  FROM jurnal
            LEFT JOIN jurnal_detail ON jurnal.id=jurnal_detail.id_parent
            WHERE DATE(tgl)=DATE(NOW()) AND jurnal.keterangan LIKE '%Cash%'
            GROUP BY jurnal.id 
            HAVING total_debet <> SUM(debet)");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['id'] ?></td>
					<td class="text-left"><?php echo $d['no_jurnal']; ?></td>
					<td class="text-left"><?php echo number_format($d['total_debet']); ?></td>
				</tr>
				<?php

				$transfer='';
				$deposit='';
				$tunai='';
				$total='';
				$dropshipper='';
				$namadropshipper='';
				$q = mysql_fetch_array( mysql_query("SELECT olnso.id_trans, olnso.total, olnso.transfer, olnso.tunai, olnso.deposit, olnso.id_dropshipper,mst_dropshipper.nama FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE id_trans='".$d['oln']."' LIMIT 1"));
				$tunai=$q['tunai'];
				$transfer=$q['transfer'];
				$deposit=$q['deposit'];
				$total=$q['total'];
				$dropshipper=$q['id_dropshipper'];
				$namadropshipper=$q['nama'];

				$id_user = 'marcell';
				// $total = $d['total_debet'];
				$idparent=$d['id'];
				
				$dpp = round($total / 1.11);
				$ppn = round($total / 1.11 * 0.11);
	
				if($tunai > 0){
					$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='01.01.00001'");
					while($akun1 = mysql_fetch_array($query1)){
						// KAS
						$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', '$id_user',NOW()) ";
						mysql_query($sqlakun1) or die (mysql_error());
					}
				}else if($transfer > 0 && $deposit == 0){
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

				$no++;
			}
			?>
		</tbody>
	</table>

</body>
</html>