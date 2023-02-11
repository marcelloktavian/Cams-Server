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
                    <th colspan='8'>DATA PENJUALAN ERROR</th>
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
			$data = mysql_query("SELECT det.id_parent FROM `jurnal_detail`  det
left join jurnal ON jurnal.id=det.id_parent
where det.deleted=0 and det.user='marcell' and jurnal.id is NULL
group by id_parent
having sum(debet) <> sum(kredit)");
			while($d = mysql_fetch_array($data)){
				
				$upnama = mysql_query("delete from jurnal_detail where id_parent='".$d['id_parent']."'");
				
				$no++;
			}
			
			?>
		</tbody>
	</table>

	


</body>
</html>