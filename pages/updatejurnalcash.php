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
					<th class="text-left">Tangal</th>
					<th class="text-left">Keterangan</th>
					<th class="text-left">Total</th>
					<th class="text-left">DPP</th>
					<th class="text-left">PPN</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
			$data = mysql_query("SELECT *,jurnal.total_debet, ROUND(jurnal.total_debet/1.11) AS dpp, ROUND(ROUND(jurnal.total_debet/1.11)*0.11) AS ppn,jurnal.keterangan as ket FROM jurnal 
            WHERE jurnal.keterangan LIKE '%Penjualan OLN Cash%'");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['id'] ?></td>
					<td class="text-left"><?php echo $d['no_jurnal']; ?></td>
					<td class="text-left"><?php echo $d['tgl']; ?></td>
					<td class="text-left"><?php echo $d['ket']; ?></td>
					<td class="text-left"><?php echo number_format($d['total_debet']); ?></td>
					<td class="text-left"><?php echo number_format($d['dpp']); ?></td>
					<td class="text-left"><?php echo number_format($d['ppn']); ?></td>
				</tr>
				<?php
                // PPN
                $up1 = mysql_query("UPDATE jurnal_detail SET kredit='".$d['ppn']."'
                WHERE nama_akun LIKE '%PPN%' AND id_parent='".$d['id']."' ");

                // DPP
                $up2 = mysql_query("UPDATE jurnal_detail SET kredit='".$d['dpp']."'
                WHERE nama_akun LIKE '%Penjualan OLN Cash%' AND id_parent='".$d['id']."' ");
                
				$no++;
			}
			?>
		</tbody>
	</table> 

	<table border ="1px">
			<thead>
                <tr>
                    <th colspan='8'>DATA PENJUALAN CASH CANCELLED</th>
                </tr>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">ID</th>
					<th class="text-left">No Jurnal</th>
					<th class="text-left">Tangal</th>
					<th class="text-left">Keterangan</th>
					<th class="text-left">Total</th>
					<th class="text-left">DPP</th>
					<th class="text-left">PPN</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
				$data = mysql_query("SELECT *,jurnal.total_kredit, ROUND(jurnal.total_kredit/1.11) AS dpp, ROUND(ROUND(jurnal.total_kredit/1.11)*0.11) AS ppn,jurnal.keterangan as ket FROM jurnal 
				WHERE jurnal.keterangan LIKE '%CANCELLED OLN Cash%'");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['id'] ?></td>
					<td class="text-left"><?php echo $d['no_jurnal']; ?></td>
					<td class="text-left"><?php echo $d['tgl']; ?></td>
					<td class="text-left"><?php echo $d['ket']; ?></td>
					<td class="text-left"><?php echo number_format($d['total_kredit']); ?></td>
					<td class="text-left"><?php echo number_format($d['dpp']); ?></td>
					<td class="text-left"><?php echo number_format($d['ppn']); ?></td>
				</tr>
				<?php
                // PPN
                $up1 = mysql_query("UPDATE jurnal_detail SET debet='".$d['ppn']."'
                WHERE nama_akun LIKE '%PPN%' AND id_parent='".$d['id']."' ");

                // DPP
                $up2 = mysql_query("UPDATE jurnal_detail SET debet='".$d['dpp']."'
                WHERE nama_akun LIKE '%Penjualan OLN Cash%' AND id_parent='".$d['id']."' ");
                
				$no++;
			}
			?>
		</tbody>
	</table>

</body>
</html>