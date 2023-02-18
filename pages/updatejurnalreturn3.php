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
                    <th colspan='8'>DATA PENJUALAN RETURN SALAH</th>
                </tr>
				<tr>
					<th class="text-left">No.</th>
					<th class="text-left">ID</th>
					<th class="text-left">No Jurnal</th>
					<th class="text-left">Tangal</th>
					<th class="text-left">Keterangan</th>
					<th class="text-left">No Akun</th>
					<th class="text-left">Nama Akun</th>
				</tr>
			</thead>
			<?php 		
			$no=1;
				$data = mysql_query("SELECT *, jurnal.id as ids, jurnal.keterangan as ket, jurnal_detail.id as iddetail FROM jurnal
				LEFT JOIN jurnal_detail ON jurnal.id=jurnal_detail.id_parent
				WHERE jurnal.keterangan LIKE '%Retur OLN Kredit -%' AND jurnal_detail.nama_akun LIKE '%Penjualan OLN Cash%'");
			while($d = mysql_fetch_array($data)){
				?>
				<tr>
					<td class="text-left"><?php echo $no; ?></td>
					<td class="text-left"><?php echo $d['ids'] ?></td>
					<td class="text-left"><?php echo $d['no_jurnal']; ?></td>
					<td class="text-left"><?php echo $d['tgl']; ?></td>
					<td class="text-left"><?php echo $d['ket']; ?></td>
					<td class="text-left"><?php echo $d['no_akun']; ?></td>
					<td class="text-left"><?php echo $d['nama_akun']; ?></td>
				</tr>
				<?php
                $up1 = mysql_query("UPDATE jurnal_detail SET no_akun=REPLACE(no_akun, '04.01', '04.02') WHERE jurnal_detail.id='".$d['iddetail']."' ");
				$up1 = mysql_query("UPDATE jurnal_detail SET nama_akun=REPLACE(nama_akun, 'Penjualan OLN Cash -', 'Penjualan OLN Kredit -') WHERE jurnal_detail.id='".$d['iddetail']."' ");
                
				$no++;
			}
			?>
		</tbody>
	</table>


</body>
</html>