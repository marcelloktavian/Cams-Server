
<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'kasir_pelanggan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
	            
		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE tblpelanggan SET namaperusahaan=?,alamat=?,telp1=?,telp2=?,fax=?,contactperson=?,HP=?,email=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['nama'],$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username'], $_POST['id']));
			$affected_rows = $stmt->rowCount();
			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
		else {
			$stmt = $db->prepare("INSERT INTO tblpelanggan(`namaperusahaan`,`alamat`,`telp1`,`telp2`,`fax`,`contactperson`,`HP`,`email`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			
			if($stmt->execute(array($_POST['nama'],$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username']))) {
			//if($stmt->execute(array($_POST['nama'],$_SESSION['user']['username']))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
				
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}	
		echo json_encode($r);
		header("location:".BASE_URL);
		exit;
		
	}
?>


<div class="ui-widget ui-form">    
	<div class="ui-widget-header ui-corner-top padding5">
        TRANSAKSI KASIR
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="kasir" method="post" action="<?php echo BASE_URL ?>pages/kasir/kasir_id.php" class="ui-helper-clearfix">
        	<div class="ui-corner-all form-control">
            	<table>
				<tr>
                <td>Pelanggan</td>				
				<td>
                <div class="ui-corner-all form-control">
            	<select name="pelanggan_id" id="pelanggan_id">
            		<option value="">--Pilih--</option>
            		<?php
            			$p = $db->query("SELECT * FROM tblpelanggan p ORDER BY p.namaperusahaan");
            			$rows = $p->fetchAll(PDO::FETCH_ASSOC);
						foreach ($rows as $r) {
							echo '<option value="'.$r['id'].'">'.$r['namaperusahaan'].'</option>';
						}
            		?>
            	</select>
                </div>
				</td>
				<td>
				<div class="btn_box">
				<?php
				$allow = array(1,2,3);
				if(in_array($_SESSION['user']['access'], $allow)) {
				echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/kasir/kasir_pelanggan.php?action=add\',\'table_kasir_pelanggan\')" class="btn">Pelanggan Baru</button>';
				}
				?>
				</div>
				</td>
				</tr>
				<tr>
				<td colspan=2>
				<label for="" class="ui-helper-reset label-control">&nbsp;</label>
				<div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/kasir/kasir_id.php?id='+$('#pelanggan_id').val())" class="btn" type="button">Buat Invoice</button>
				</div>				
				</td>
				</tr>
				</table>
            </div>
            <table id="table_pelanggankasir"></table>
       	</form>
   	</div>
</div>
<script type="text/javascript">
</script>