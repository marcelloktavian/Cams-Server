<head>
	<title>JURNAL MANUAL</title>
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
error_reporting(0);
//connection with database with PDO
require "../../include/config.php";

?>
	<form id='form2' name='form2' action='' method='post'>
		<table width='100%'>
			<tr>

				<td class='fontjudul'>JURNAL MANUAL</td>
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
			</thead>
		</table>
		<hr>
		<table>
			<tbody>
				<tr>
					<td class="fonttext" style="width:20px;">
						Keterangan
					</td>
					<td colspan="6" align="left"><textarea name="txtbrg" id="txtbrg" cols="100" rows="3"></textarea>
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

		var baris1 = 1;
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
			document.getElementById('debet' + baris1 + '').setAttribute('onChange', 'hitungjml(' + baris1 + ')');
			document.getElementById('kredit' + baris1 + '').setAttribute('onChange', 'hitungjml(' + baris1 + ')');
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
					document.form2.action = "simpanJurnalmanual.php?action=add";
					document.form2.submit();
				} else {}
			}
		}
	</script>
</body>