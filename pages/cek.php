<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="refresh" content="10" />
	
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<?php
	include("../include/koneksi.php");
	?>
</head>
<body>	
		<table border ="1px">
			<thead>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">ID Trans</th>
					<th class="text-left">Tanggal</th>
					<th class="text-left">ID WEB</th>
					<th class="text-left">Dropshipper</th>
					<th class="text-left">Qty Detail</th>
					<th class="text-left">Qty Master</th>
					<th class="text-left">Total Detail</th>
					<th class="text-left">Total Master</th>
					<th class="text-left">Faktur</th>
					<th class="text-left">Status</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("SELECT dt.id_trans,DATE_FORMAT(m.lastmodified,'%d/%m/%Y') AS tglposted, m.ref_kode AS id_web,m.exp_fee AS ongkir,d.nama AS dropshipper,SUM(dt.jumlah_beli) AS qty_detail,m.totalqty,SUM(ceil(dt.subtotal * (1-m.discount))) AS total_detail,m.total,m.faktur ,m.nama AS pembeli,e.nama AS expedition,m.state,m.discount AS discdp,m.discount_faktur AS disc_faktur, m.deposit, m.transfer  FROM olnsodetail dt  INNER JOIN olnso m ON dt.id_trans = m.id_trans  LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id LEFT JOIN mst_expedition e ON m.id_expedition = e.id WHERE ((m.deleted=0) AND (m.state='1') AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('01/01/2023','%d/%m/%Y') AND STR_TO_DATE('01/01/2025','%d/%m/%Y')) GROUP BY dt.id_trans HAVING (SUM(CEIL(dt.subtotal * (1-m.discount))) <> m.faktur) OR (SUM(dt.jumlah_beli) <> m.totalqty) ORDER BY m.id_trans ASC");
			while($d = mysql_fetch_array($data)){
				$status='';
				if($d['deposit']==0 && $d['transfer']==0){
					$status = 'Credit';
				}else{
					$status = 'Cash';
				}
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['id_trans'] ?></td>
					<td class="text-left"><?php echo $d['tglposted']; ?></td>
					<td class="text-left"><?php echo $d['id_web']; ?></td>
					<td class="text-left"><?php echo $d['dropshipper']; ?></td>
					<td class="text-left"><?php echo $d['qty_detail']; ?></td>
					<td class="text-left"><?php echo $d['totalqty']; ?></td>
					<td class="text-left"><?php echo $d['total_detail']; ?></td>
					<td class="text-left"><?php echo $d['total']; ?></td>
					<td class="text-left"><?php echo $d['faktur']; ?></td>
					<td class="text-left"><?php echo $status; ?></td>
				</tr>
				<?php 
				$no++;
			}
			?>
		</tbody>
	</table>

</body>
</html>