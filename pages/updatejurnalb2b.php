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
                    <th colspan='8'>DATA PENJUALAN B2B</th>
                </tr>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">Tangal</th>
					<th class="text-left">Keterangan</th>
					<th class="text-left">Total</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("SELECT b2bdo.tgl_trans, CONCAT('Penjualan B2B - ',mst_b2bcustomer.nama,' - ',no_faktur,' - ',ref_kode) AS keterangan, totalfaktur,b2bdo.id_customer  FROM b2bdo 
			LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer
			LEFT JOIN b2bso ON b2bso.id_trans=b2bdo.id_transb2bso
			WHERE DATE(b2bdo.tgl_trans) BETWEEN '2023-01-01' AND '2023-01-25' AND b2bdo.deleted=0 AND b2bdo.no_faktur <> 'AK23010012'");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['tgl_trans'] ?></td>
					<td class="text-left"><?php echo $d['keterangan']; ?></td>
					<td class="text-left"><?php echo number_format($d['totalfaktur']); ?></td>
				</tr>
				<?php
				$tgl = $d['tgl_trans'];
				$ket = $d['keterangan'];
				$total = $d['totalfaktur'];
				$customer = $d['id_customer'];
                //insert
				$masterNo = '';
				$q = mysql_fetch_array( mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
				FROM jurnal ORDER BY id DESC LIMIT 1"));
				$masterNo=$q['nomor'];

				// execute for master
				$sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo','$tgl','$ket','$total','$total','0','marcell',NOW(),'B2B') ";
				mysql_query($sql_master) or die (mysql_error());

				//get master id terakhir
				$q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
				$idparent=$q['id'];
				
				$dpp = round($total / 1.11);
				$ppn = round($total / 1.11 * 0.11);

				$query1=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('01.05.',IF(LENGTH('$customer')=1,'0000',IF(LENGTH('$customer')=2,'000',IF(LENGTH('$customer')=3,'00',IF(LENGTH('$customer')=4,'0','')))), '$customer')");
				while($akun1 = mysql_fetch_array($query1)){
					// piutang b2b 
					$sqlakun1="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun1['id']."','".$akun1['noakun']."','".$akun1['nama']."','".$akun1['status']."','$total','0','','0', 'marcell',NOW()) ";
					mysql_query($sqlakun1) or die (mysql_error());
				}

				$query2=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun=CONCAT('04.03.',IF(LENGTH('$customer')=1,'0000',IF(LENGTH('$customer')=2,'000',IF(LENGTH('$customer')=3,'00',IF(LENGTH('$customer')=4,'0','')))), '$customer')");
				while($akun2 = mysql_fetch_array($query2)){
					// penjualan b2b
					$sqlakun2="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun2['id']."','".$akun2['noakun']."','".$akun2['nama']."','".$akun2['status']."','0','$dpp','','0', 'marcell',NOW()) ";
					mysql_query($sqlakun2) or die (mysql_error());
				}

				$query3=mysql_query("SELECT id, noakun, nama, 'Detail' AS `status` FROM det_coa WHERE noakun='09.01.00000'");
				while($akun3 = mysql_fetch_array($query3)){
					// ppn
					$sqlakun3="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','".$akun3['id']."','".$akun3['noakun']."','".$akun3['nama']."','".$akun3['status']."','0','$ppn','','0', 'marcell',NOW()) ";
					mysql_query($sqlakun3) or die (mysql_error());
				}
                
				$no++;
			}
			?>
		</tbody>
	</table>
</body>
</html>