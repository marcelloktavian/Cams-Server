<head>
	<title>PEMBAYARAN PIUTANG B2B</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
	<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
	<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>

	<style>
		body {
			background-color: #BAE0AD;
		}

		tanggal {
			color: maroon;
			margin-left: 40px;
		}
	</style>
</head>

<body>
<?php
  require "../../include/config.php";
  include '../../include/koneksi.php';

  $sql_text = "SELECT *, piutang_do-COALESCE(total_return,0) AS piutang_akhir, piutang_do-COALESCE(total_return,0)-COALESCE(piutang_terbayar,0) AS piutang_sisa FROM (
    SELECT a.id AS id_b2bso, b.id AS id_b2bdo, a.id_trans AS id_trans_so, b.id_trans AS id_trans_do, b.no_faktur, a.tgl_trans, a.id_customer, c.nama AS nama_customer, c.alamat AS alamat_customer, c.no_telp AS telp_customer, a.id_salesman, d.nama AS nama_salesman, d.alamat AS alamat_salesman, d.no_telp AS telp_salesman, a.piutang AS piutang_so, SUM(b.piutang) AS piutang_do, a.totalqty, a.totalkirim AS totalkirim_so, SUM(b.totalkirim) AS totalkirim_do FROM b2bso a LEFT JOIN b2bdo b ON a.id_trans=b.id_transb2bso LEFT JOIN mst_b2bcustomer c ON a.id_customer=c.id LEFT JOIN mst_b2bsalesman d ON a.id_salesman=d.id WHERE b.no_faktur IS NOT NULL AND b.deleted=0 AND a.deleted=0 GROUP BY no_faktur
  ) AS a LEFT JOIN (
    SELECT id_trans_do AS id_b2bdo, b2bdo_num, (qty31+qty32+qty33+qty34+qty35+qty36+qty37+qty38+qty39+qty40+qty41+qty42+qty43+qty44+qty45+qty46) AS total_qty ,SUM(subtotal) AS total_return FROM b2breturn_detail WHERE deleted=0 GROUP BY b2bdo_num
  ) AS b ON a.id_b2bdo=b.id_b2bdo LEFT JOIN (
    SELECT id AS id_jurnal, no_jurnal, tgl AS tgl_jurnal, keterangan AS keterangan_jurnal, SUM(total_debet) AS piutang_terbayar, SUBSTRING_INDEX(keterangan, '-',-1) AS nomor_faktur_jurnal FROM jurnal WHERE keterangan LIKE 'Pembayaran Piutang%' AND `status`='B2B PAY' AND deleted=0 GROUP BY SUBSTRING_INDEX(keterangan, '-',-1)
  ) AS c ON a.no_faktur=TRIM(c.nomor_faktur_jurnal) WHERE a.totalqty=a.totalkirim_do-COALESCE(b.total_qty,0) AND no_faktur = '".$_GET['no_faktur']."'";
  $piutang = mysql_fetch_array(mysql_query($sql_text));

  $sql_akun = "SELECT * FROM det_coa WHERE noakun = CONCAT('01.05.',LPAD('128',5,0))";
  $akun_piutang = mysql_fetch_array(mysql_query($sql_akun));
?>
	<form id='form2' name='form2' action='' method='post'>
		<table width='100%'>
			<tr>

				<td class='fontjudul'>PEMBAYARAN PIUTANG B2B</td>
				<!-- subtotal -->
				<td class='fontjudul'> TOTAL DEBET
					<input type='text' class='' name='total_debet_m' id='total_debet_m' value='0'
						style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
					<!-- subtotal hidden -->
					<input type="hidden" name="total_debet" id="total_debet">

					<!-- subtotal -->
				<td class='fontjudul'> TOTAL KREDIT
					<input type='text' class='' name='total_kredit_m' id='total_kredit_m' value='0'
						style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
					<!-- subtotal hidden -->
					<input type="hidden" name="total_kredit" id="total_kredit">

				</td>
			</tr>
		</table>
		<hr />
		<table width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td class="fonttext">Tanggal</td>
				<td><input type='date' class='inputform' name='tanggal' id='tanggal' value='' />
				</td>
			</tr>
			<tr height='5'>
				<td colspan='6'></td>
			</tr>
			<tr height='5'>
				<td colspan='6'></td>
			</tr>
		</table>
		<hr>
		<table align='center' width='100%' id='tbl_1'>
			<thead>
				<tr>

					<td align='center' width='10%' class='fonttext'>No Akun</td>
					<td align='center' width='15%' class='fonttext'>Nama Akun</td>
					<td align='center' width='10%' class='fonttext'>Debet</td>
					<td align='center' width='10%' class='fonttext'>Kredit</td>
					<td align='center' width='10%' class='fonttext'>Keterangan</td>
					<td align='center' width='5%' class='fonttext'>Hapus</td>

				</tr>
        <tr id="t11">
          <td>
            <input type="hidden" name="idakun1" id="idakun1" size="15" align="left" readonly value="<?= $akun_piutang['id'] ?>">
            <input type="hidden" name="status1" id="status1" size="15" align="left" readonly value="Detail">
            <input type="text" name="noakun1" id="noakun1" size="15" autocomplete="off" class="ac_input" style="background-color: rgb(211, 211, 211);" readonly value="<?= $akun_piutang['noakun'] ?>">
          </td>
          <td>
            <input type="text" name="namaakun1" id="namaakun1" size="35" readonly="" style="background-color: rgb(211, 211, 211);" readonly value="<?= $akun_piutang['nama'] ?>">
          </td>
          <td>
            <input type="text" name="debet1" id="debet1" size="15" autocomplete="off" style="text-align: right;" onchange="hitungjml(1)" onkeypress="return isNumberKey(event)" value="<?= $piutang['piutang_sisa'] ?>">
            <input type='hidden' name="max-piutang" id="max-piutang" value="<?= $piutang['piutang_sisa'] ?>" />
          </td>
          <td>
            <input type="text" name="kredit1" id="kredit1" size="15" autocomplete="off" style="text-align: right; background-color: rgb(211, 211, 211);" onchange="hitungjml(1)" onkeypress="return isNumberKey(event)" readonly value="0">
          </td>
          <td>
            <input type="text" name="keterangan1" id="keterangan1" size="30" style="background-color: rgb(211, 211, 211);" readonly value="Pembayaran Piutang B2B - <?= $_GET['nama_customer'] ?> - <?= $_GET['no_faktur'] ?>">
          </td>
          <td>
            
          </td>
        </tr>
      </thead>
		</table>
		<hr>
		<table>
			<tbody>
				<tr>
					<td class="fonttext" style="width:20px;">
						Keterangan
					</td>
					<td colspan="6" align="left"><textarea name="txtbrg" id="txtbrg" cols="100" rows="3" readonly style="background-color: rgb(211, 211, 211);">Pembayaran Piutang B2B - <?= $_GET['nama_customer'] ?> - <?= $_GET['no_faktur'] ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<td>
			<p><input type='hidden' name='jum' value='' /><input type='hidden' name='temp_limit' id='temp_limit'
					value='' /></p>

			</table>
	</form>
	<table>
		<tr>
			<td>
				<p><input type='image' value='Tambah Baris' src='../../assets/images/tambah_baris.png' id='baru'
						onClick='addNewRow1()' /></p>
			</td>
			<td>
				<p align='center'><input name='print' type='image' src='../../assets/images/simpan_cetak.png'
						value='Cetak' id='print' onClick='cetak()' /></p>
			</td>
			<td>
				<p><input type='image' value='batal' src='../../assets/images/batal.png' id='tutup' onClick='tutup()' />
				</p>
			</td>
		</tr>

	</table>

	<script type="text/javascript">
		//autocomplete pada grid
		function get_products(a) {
			$("#noakun" + a + "").autocomplete("COALov.php?", {
				width: 178
			});
			//   console.log('here'+a)  ;
			$("#noakun" + a + "").result(function (event, data, formatted) {
				var nama = document.getElementById("noakun" + a + "").value;
				for (var i = 0; i < nama.length; i++) {
					var id = nama.split(';');
					if (id[1] == "") continue;
					var id_pd = id[1];
				}
				// console.log(id_pd);
				$.ajax({
					url: 'COALoVdet.php?id=' + id_pd,
					dataType: 'json',
					data: "nama=" + formatted,
					success: function (data) {
						var id = data.id;
						$("#idakun" + a + "").val(id);
						var status = data.status;
						$("#status" + a + "").val(status);
						var noakun = data.noakun;
						$("#noakun" + a + "").val(noakun);
						var nama = data.nama;
						$("#namaakun" + a + "").val(nama);

						$("#debet" + a + "").focus();
					}
				});

			});
		}

		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 46 && charCode > 31 
				&& (charCode < 48 || charCode > 57))
				return false;

			return true;
		}

		var baris1 = 2;
		addNewRow1();
		document.getElementById('tanggal').focus();

		function addNewRow1() {
			var tbl = document.getElementById("tbl_1");
			var row = tbl.insertRow(tbl.rows.length);
			row.id = 't1' + baris1;

			var td0 = document.createElement("td");
			var td1 = document.createElement("td");
			var td2 = document.createElement("td");
			var td3 = document.createElement("td");
			var td4 = document.createElement("td");
			var td5 = document.createElement("td");
			// var td6 = document.createElement("td");
			// var td7 = document.createElement("td");

			td0.appendChild(generateIDAkun(baris1));
			td0.appendChild(generateStatus(baris1));
			td0.appendChild(generateNoAkun(baris1));
			td1.appendChild(generateNamaAkun(baris1));
			td2.appendChild(generateDebet(baris1));
			td3.appendChild(generateKredit(baris1));
			td4.appendChild(generateKeterangan(baris1));
			td5.appendChild(generateDel1(baris1));
			// td5.appendChild(generateSubtotal(baris1));
			// td6.appendChild(generateppn(baris1));
			// td7.appendChild(generateDel1(baris1));

			row.appendChild(td0);
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			// row.appendChild(td6);
			// row.appendChild(td7);
			
			document.getElementById('noakun' + baris1 + '').focus();
			document.getElementById('del1' + baris1 + '').setAttribute('onclick', 'delRow1(' + baris1 + ')');
      document.getElementById('debet' + baris1 + '').value="0";
      document.getElementById('kredit' + baris1 + '').value="0";
			document.getElementById('debet' + baris1 + '').setAttribute('onkeyup', 'hitungjml(' + baris1 + ')');
			document.getElementById('kredit' + baris1 + '').setAttribute('onkeyup', 'hitungjml(' + baris1 + ')');
			document.getElementById('debet' + baris1 + '').setAttribute('onkeypress', 'return isNumberKey(event)');
			document.getElementById('kredit' + baris1 + '').setAttribute('onkeypress', 'return isNumberKey(event)');
			document.getElementById('del1' + baris1 + '').setAttribute('onkeydown', 'addNewRow1()');
			get_products(baris1);
			baris1++;
		}

		function hitungtotal() {
			var totaldebet = 0;
			var totalkredit = 0;
			for (var i = 1; i <= baris1; i++) {

				var idnya = document.getElementById("idakun" + i + "");
				if (idnya != null) {
					totaldebet += parseFloat(document.getElementById("debet" + i + "").value);
					totalkredit += parseFloat(document.getElementById("kredit" + i + "").value);
				}
			}
			var locale = 'IDR';
			var options = {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			};
			var formatter = new Intl.NumberFormat(locale, options);
			// 
			document.getElementById("total_debet").value = totaldebet.toFixed(2);
			document.getElementById("total_kredit").value = totalkredit.toFixed(2);
			// 
			document.getElementById("total_debet_m").value = formatter.format(totaldebet.toFixed(2));
			document.getElementById("total_kredit_m").value = formatter.format(totalkredit.toFixed(2));

		}

		function hitungjml(a) {
			if (document.getElementById("debet" + a + "").value == '') {
				document.getElementById("debet" + a + "").value = 0;
			}
			if (document.getElementById("kredit" + a + "").value == '') {
				document.getElementById("kredit" + a + "").value = 0;
			}

			var debet = parseFloat(document.getElementById("debet" + a + "").value);
			var kredit = parseFloat(document.getElementById("kredit" + a + "").value);

			hitungtotal();
		}

		function generateIDAkun(index) {
			var idx = document.createElement("input");
			idx.type = "hidden";
			idx.name = "idakun" + index + "";
			idx.id = "idakun" + index + "";
			idx.size = "15";
			idx.align = "left";
			return idx;
		}

		function generateStatus(index) {
			var idx = document.createElement("input");
			idx.type = "hidden";
			idx.name = "status" + index + "";
			idx.id = "status" + index + "";
			idx.size = "15";
			idx.align = "left";
			return idx;
		}

		function generateNoAkun(index) {
			var idx = document.createElement("input");
			idx.type = "text";
			idx.name = "noakun" + index + "";
			idx.id = "noakun" + index + "";
			idx.size = "15";
			return idx;
		}

		function generateNamaAkun(index) {
			var idx = document.createElement("input");
			idx.type = "text";
			idx.name = "namaakun" + index + "";
			idx.id = "namaakun" + index + "";
			idx.size = "35";
			idx.readOnly = "readonly";
			idx.style="background-color:#D3D3D3";
			return idx;
		}

		function generateKredit(index) {
			var idx = document.createElement("input");
			idx.type = "text";
			idx.name = "kredit" + index + "";
			idx.id = "kredit" + index + "";
			idx.size = "15";
			idx.style = "text-align:right;";
			idx.autocomplete = "off";
			return idx;
		}

		function generateDebet(index) {
			var idx = document.createElement("input");
			idx.type = "text";
			idx.name = "debet" + index + "";
			idx.id = "debet" + index + "";
			idx.size = "15";
			idx.style = "text-align:right;";
			idx.autocomplete = "off";
			return idx;
		}

		function generateKeterangan(index){
			var idx = document.createElement("input");
			idx.type = "text";
			idx.name = "keterangan" + index + "";
			idx.id = "keterangan" + index + "";
			idx.size = "30";
			return idx;
		}

		function generateDel1(index) {
			var idx = document.createElement("input");
			idx.type = "button";
			idx.name = "del1" + index + "";
			idx.id = "del1" + index + "";
			idx.size = "10";
			idx.value = "X";
			return idx;

		}

		function delRow1(id) {
			var el = document.getElementById("t1" + id);
			baris1 -= 1;
			el.parentNode.removeChild(el);
			return false;
		}

		function hitungrow() {
			document.form2.jum.value = baris1;
		}

		function tutup() {
			window.close();
		}

		function cetak() {
			var pesan = '';
			var tgltransaksi = document.getElementById("tanggal").value;
			var totaldebet = document.getElementById("total_debet").value;
			var totalkredit = document.getElementById("total_kredit").value;

			if (tgltransaksi == '') {
				pesan = 'Tanggal Tidak Boleh Kosong';
			} else if ((totaldebet == '0' || totaldebet == '') && (totalkredit == '0' || totaldebet == '')) {
				pesan = 'Total Debet atau Total Kredit Harus Diisi';
			} else if (totaldebet != totalkredit) {
				pesan = 'Total Debet dan Total Kredit Tidak Sama';
			}


			if (pesan != '') {
				alert('Maaf, ada kesalahan pengisian Nota : \n' + pesan);
				return false;
			} else {
				var answer = confirm("Mau Simpan datanya????")
				if (answer) {
					hitungrow();
					// hitung() ;
					document.form2.action = "trb2bpiutang_pembayaran_save.php?action=add";
					document.form2.submit();
				} else {}
			}
		}

    const inputPiutang = document.getElementById('debet1');
    const maxPiutang = document.getElementById('max-piutang');

    inputPiutang.addEventListener('keyup',(event)=>{
			console.log(inputPiutang.value);
      if(parseInt(inputPiutang.value) < 0){
        inputPiutang.value = 0;
      } else if(parseInt(inputPiutang.value) > parseInt(maxPiutang.value)){
        inputPiutang.value = maxPiutang.value;
      }
    });

    hitungtotal();
	</script>
</body>