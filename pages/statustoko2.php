<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		$statusnya = '';
		if ($_POST['status']=='Tutup') {
			$statusnya = 'Buka';
		} else {
			$statusnya = 'Tutup';
		}

		$u = $db->prepare("UPDATE tbl_status SET status=?, user=?, lastmodified=NOW() WHERE id=?");
		$u->execute(array($statusnya, $_SESSION['user']['username'], $_POST['idstatus']));

		$affected_rows = $u->rowCount();
		// if($affected_rows > 0) {
		// 	$rp['status'] = 1;
		// 	$rp['message'] = 'Success';
		// }
		// else {
		// 	$rp['status'] = 0;
		// 	$rp['message'] = 'Failed';
		// }
		// echo json_encode($rp);
		if ($affected_rows > 0) {
			echo "<script type='text/javascript'>alert('Toko ".$statusnya."');window.location.href = '".BASE_URL."';</script>";
		} else {
			echo "<script type='text/javascript'>alert('Failed');</script>";
		}
		
		// exit;
	}

	//set status
	$id = '';
	$status = '';

	$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
	$getStat->execute();
	$stat = $getStat->fetchAll();
	foreach ($stat as $stats) {
		$id = $stats['id'];
    	$status = $stats['status'];
	}

?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Status Toko
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="change_status_form" method="post" action="<?php echo BASE_URL ?>pages/statustoko.php?action=process" class="ui-helper-clearfix">
        	<label for="status" class="ui-helper-reset label-control">Status Toko </label>
            <div class="ui-corner-all form-control">
            	<input type="text" id="status" name="status" value="<?= $status?>" readonly>
            	<input type="hidden" id="idstatus" name="idstatus" value="<?= $id?>">
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button class="btn" type="submit" name="ubahStatus" id="ubahStatus">Ubah Status</button>
            </div>
       	</form>
   	</div>
</div>

<!-- <script>
    document.getElementById('refresh').onclick = function() {
    	var statusnya = '';
    	var status = document.getElementById('status').value;
    	if (status=='Tutup') {
			statusnya = 'Buka';
    	} else {
			statusnya = 'Tutup';
    	}
        document.getElementById('status').value = statusnya;
    }
</script> -->