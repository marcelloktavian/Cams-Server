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
                    <th colspan='8'>DATA PENJUALAN RETURN</th>
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
            WHERE jurnal.keterangan LIKE '%RETUR OLN%'");
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
				$ex = explode(' - ',$d['ket']);
				$idoln =  $ex[2];
				$data2 = mysql_query("SELECT olnso.id_trans, olnso.total, olnso.transfer, olnso.deposit, olnso.piutang, olnso.id_dropshipper,mst_dropshipper.nama FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE id_trans='".$idoln."' LIMIT 1");
				$type = '';
				while($d2 = mysql_fetch_array($data2)){
					if($d2['piutang'] > 0){
						$type='Kredit';
					}else{
						$type='Cash';
					}
				}
				$upnama = mysql_query("UPDATE jurnal SET keterangan=REPLACE(keterangan, 'Retur OLN ','Retur OLN $type ') WHERE jurnal.id = '".$d['id']."' ");
				
                // PPN
                // $up1 = mysql_query("UPDATE jurnal_detail SET kredit='".$d['ppn']."'
                // WHERE nama_akun LIKE '%PPN%' AND id_parent='".$d['id']."' ");

                // // DPP
                // $up2 = mysql_query("UPDATE jurnal_detail SET kredit='".$d['dpp']."'
                // WHERE nama_akun LIKE '%Penjualan OLN Kredit%' AND id_parent='".$d['id']."' ");
                
				$no++;
			}
			$up1 = mysql_query("UPDATE jurnal SET keterangan=REPLACE(keterangan, 'Kredit -', '-') WHERE jurnal.keterangan LIKE '%RETUR OLN%'");
			$up2 = mysql_query("UPDATE jurnal SET keterangan=REPLACE(keterangan, 'Cash -', '-') WHERE jurnal.keterangan LIKE '%RETUR OLN%'");
			$up3 = mysql_query("UPDATE jurnal SET keterangan=REPLACE(keterangan, 'Retur OLN -', 'Retur OLN Cash -') WHERE jurnal.keterangan LIKE '%RETUR OLN%'");
			?>
		</tbody>
	</table>

	


</body>
</html>