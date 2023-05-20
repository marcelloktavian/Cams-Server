<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' INPUT DATA DEPOSIT DROPSHIPPER';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trdeposit_form" method="post" action="<?php echo BASE_URL ?>pages/sales_online/trolndeposit.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM olndeposit WHERE id_trans = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
					$kode = isset($row['kode']) ? $row['kode'] : '';
				}
				else if(strtolower($_GET['action']) == 'add') {
				//max id for kode pelanggan--------------------
					$select2 = $db->prepare("Select max(substring(kode,3,10)+1) as kode_id from olndeposit where kode like 'TD%'");
					$select2->execute();
					$row2  = $select2->fetch(PDO::FETCH_ASSOC);
					$kode  = "TD".sprintf("%03d", $row2['kode_id']);
				//----------------------------------------------
				}
				//var_dump($kode);die;
	        ?>
			<label for="id_tr" class="ui-helper-reset label-control">NO.TRANSAKSI</label>	
            <div class="ui-corner-all form-control">
			    <input value="<?php echo $kode; ?>" type="text" type="text" id="kode" name="kode" readonly>	
            </div>
			
            <label for="pelanggan_name" class="ui-helper-reset label-control">Nama Dropshipper (*)</label>
            <div class="ui-corner-all form-control">
                <select class="required" name="id_dropshipper" id="id_dropshipper">
                	<option value="">-pilih-</option>
                	<?php
                		$query = $db->query("SELECT * FROM mst_dropshipper where deleted=0  ORDER BY nama ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id_dropshipper']) && $row['id_dropshipper'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
                	?>
                </select>
            </div>
			<label for="telp1" class="ui-helper-reset label-control">Tgl.Deposit</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['tgl_trans']) ? $row['tgl_trans'] : ''; ?>" type="text" class="required datepicker" id="tgl_trans" name="tgl_trans">
            </div>
            
			<label for="deposit_name" class="ui-helper-reset label-control">DEPOSIT TUNAI</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['tunai']) ? $row['tunai'] : ''; ?>" type="number" style="text-transform: uppercase" id="tunai" name="tunai" onkeyup="changedeposit()">
            </div>

			
			<label for="alamat" class="ui-helper-reset label-control">DEPOSIT TRANSFER</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['transfer']) ? $row['transfer'] : ''; ?>" type="number" style="text-transform: uppercase" id="transfer" name="transfer" onkeyup="changetransfer()">
            </div>

			<div id="showMengurangi" style="display:none">
				<label for="deposit_name" class="ui-helper-reset label-control">AKUN KREDIT</label>
				<div class="ui-corner-all form-control">
						<input value="<?php echo isset($row['akun_kredit']) ? $row['akun_kredit'] : ''; ?>" type="text" style="text-transform: uppercase" id="akunKredit" name="akun_kredit" >
				</div>
			</div>

			<label for="deposit_name" class="ui-helper-reset label-control">CASHBACK</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['cashback']) ? $row['cashback'] : ''; ?>" type="number" style="text-transform: uppercase" id="cashback" name="cashback" onkeyup="changecashback()">
            </div>
			
			<label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan"style="text-transform: uppercase"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea>
            </div>

			<label for="keterangan" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
				<select name='type' id='type' onchange="changetype()">
					<option value="Menambah" selected>Menambah</option>
					<option value="Mengurang">Mengurang</option>
				</select>
            </div>
			
        </form>
		(*) wajib diisi
    </div>
</div>

<script>
	const type = document.getElementById('type');
	const akunKreditView = document.getElementById('showMengurangi');
	const akunKreditValue = document.getElementById('akunKredit');

	type.addEventListener("change", (event)=>{
		if(event.target.value == "Mengurang"){
			akunKreditView.setAttribute("style","");
			akunKreditValue.classList.add("required");
		}
		else{
			akunKreditView.setAttribute("style","display:none");
			akunKreditValue.classList.remove("required");
			akunKreditValue.value = "";
		}
	});
	$('#akunKredit').autocomplete("pages/sales_online/trolndeposit_lookup_akun.php?action=reqakunkredit", {width: 400});

	function changetype(){
		var tipe = $('#type').val();
		var tunai = $('#tunai').val();
		var transfer = $('#transfer').val();
		var cashback = $('#cashback').val();

		if(tunai != ''){
			if(tunai != '0'){
				if(tipe == 'Menambah'){
					$('#tunai').val(tunai.replace("-", ""));
				}else{
					$('#tunai').val('-'+tunai);
				}
			}
		}

		if(transfer != ''){
			if(transfer != '0'){
				if(tipe == 'Menambah'){
					$('#transfer').val(transfer.replace("-", ""));
				}else{
					$('#transfer').val('-'+transfer);
				}
			}
		}

		if(cashback != ''){
			if(cashback != '0'){
				if(tipe == 'Menambah'){
					$('#cashback').val(cashback.replace("-", ""));
				}else{
					$('#cashback').val('-'+cashback);
				}
			}
		}
	}

	function changedeposit(){
		var tipe = $('#type').val();
		var isi = $('#tunai').val();

		if(tipe == 'Menambah'){
			$('#tunai').val(isi.replace("-", ""));
		}else{
			if(isi != ''){
				if(isi.substring(0, 1) != '-'){
					$('#tunai').val('-'+isi);
				}
			}
		}
	}

	function changetransfer(){
		var tipe = $('#type').val();
		var isi = $('#transfer').val();

		if(tipe == 'Menambah'){
			$('#transfer').val(isi.replace("-", ""));
		}else{
			if(isi != ''){
				if(isi.substring(0, 1) != '-'){
					$('#transfer').val('-'+isi);
				}
			}
		}
	}

	function changecashback(){
		var tipe = $('#type').val();
		var isi = $('#cashback').val();

		if(tipe == 'Menambah'){
			$('#cashback').val(isi.replace("-", ""));
		}else{
			if(isi != ''){
				if(isi.substring(0, 1) != '-'){
					$('#cashback').val('-'+isi);
				}
			}
		}
	}
</script>