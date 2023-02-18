<head>
<title>BIAYA OPERASIONAL</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>

<style>
body {
    background-color:#EAE0AD ;
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
    	
		<td class='fontjudul'>BIAYA OPERASIONAL</td>
        <!-- subtotal -->
		<td class='fontjudul'> SUBTOTAL
		<input type='text' class='' name='subtotal_m' id='subtotal_m' value='0' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- subtotal hidden -->
		<input type="hidden" name="subtotal" id="subtotal">
		<!--  ppn -->
        <td class='fontjudul'> PPN
		<input type='text' class='' name='PPN_m' id='PPN_m' value='0' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- ppn hidden -->
		<input type="hidden" name="PPN" id="PPN">
		 <!-- grandtotal -->
        <td class='fontjudul'> TOTAL <input type='text' class='' name='grandtotal_m' id='grandtotal_m' value='0' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- grandtotal hidden -->
		<input type="hidden" name="grandtotal" id="grandtotal">
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
   
        <td align='center' width='15%' class='fonttext'>Kode Operasional</td>
    	<td align='center' width='15%' class='fonttext'>Nama Biaya</td>
		<td align='center' width='10%' class='fonttext'>Qty</td>
		<td align='center' width='10%' class='fonttext'>Jenis Satuan</td>
		<td align='center' width='13%' class='fonttext'>Harga Satuan</td>
		<td align='center' width='10%' class='fonttext'>Jumlah</td>
		<td align='center' width='5%'  class='fonttext'>Hapus</td>
    
    </tr>
</thead>
</table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>

</table>
</form>
<table>
<tr>
<td>
<p><input type='image' value='Tambah Baris' src='../../assets/images/tambah_baris.png'  id='baru'  onClick='addNewRow1()'/></p>
</td>
<td>
<p align='center'><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
</td>
<td>
<p><input type='image' value='batal' src='../../assets/images/batal.png'  id='tutup' onClick='tutup()' /></p>
</td>
</tr>

</table>

<script type="text/javascript">
//autocomplete pada grid
function get_products(a){  
   $("#BARCODE"+a+"").autocomplete("OperasionalLov.php?", {
	width: 178});
//   console.log('here'+a)  ;
   $("#BARCODE"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("BARCODE"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0];
	}
	// console.log(id_pd);
	$.ajax({
		url : 'OperasionalLoVdet.php?id='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var Nama  = data.namaoperasional;
			$("#namabiaya"+a+"").val(Nama);
			var idbiaya  = data.id;
			$("#idbiaya"+a+"").val(idbiaya);
			$("#BARCODE"+a+"").val(idbiaya);
			$("#Qty"+a+"").focus();
        }
	});	
			
	});
}  	

var baris1=1;
addNewRow1();
function addNewRow1() 
{
var tbl = document.getElementById("tbl_1");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't1'+baris1;

var td0 = document.createElement("td");
var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");
var td6 = document.createElement("td");

td0.appendChild(generateIDBiaya(baris1));
td0.appendChild(generateBARCODE(baris1));
td1.appendChild(generateBiaya(baris1));
td2.appendChild(generateqty(baris1));
td3.appendChild(generatepacking(baris1));
td4.appendChild(generateHargaSatuan(baris1));
td5.appendChild(generateSubtotal(baris1));
td6.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);


document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('hargaSatuan'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Subtotal'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
get_products(baris1);
baris1++;
}

function hitungtotalqty(){
	var total=0;
    for (var i=1; i<=baris1;i++){
		
		var KG=document.getElementById("KG"+i+"");
		if 	(KG != null)
	    {   
		total+= parseFloat(document.getElementById("KG"+i+"").value);
		}
	}
    document.getElementById("kgtotal").value = total;
}

function hitungtotal(){
	var total=0;
    for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE"+i+"");
		if 	(barcode != null)
	    {   
		total+= parseFloat(document.getElementById("Subtotal"+i+"").value);
		}
	}
	// 
	var locale = 'IDR';
	var options = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
	var formatter = new Intl.NumberFormat(locale, options);
	// 
    document.getElementById("subtotal").value = total.toFixed(0);
	document.getElementById("PPN").value = parseFloat(total.toFixed(0)) * 0.11;
	document.getElementById("grandtotal").value = (parseFloat(total.toFixed(0)) + parseFloat(total.toFixed(0) * 0.11));
	// 
	document.getElementById("subtotal_m").value = formatter.format(total.toFixed(0));
	document.getElementById("PPN_m").value = formatter.format(parseFloat(total.toFixed(0)) * 0.11);
	document.getElementById("grandtotal_m").value = formatter.format((parseFloat(total.toFixed(0)) + parseFloat(total.toFixed(0) * 0.11)));
		
}



function hitungjml(a)
{
	if(document.getElementById("Qty"+a+"").value == '') {
          document.getElementById("Qty"+a+"").value = 0;
	}
	if(document.getElementById("hargaSatuan"+a+"").value == ''){
          document.getElementById("hargaSatuan"+a+"").value = 0;
	}
	
	var qty = parseFloat(document.getElementById("Qty"+a+"").value);
	var hargaSatuan = parseFloat(document.getElementById("hargaSatuan"+a+"").value);
	var jml=0;
		jml=qty*hargaSatuan;
 	document.getElementById("Subtotal"+a+"").value = jml.toFixed(2);	
 	hitungtotal();
 	// hitungtotalqty();
}



function generateIDBiaya(index){
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "idbiaya"+index+"";
idx.id = "idbiaya"+index+"";
idx.size="15";
idx.align = "left";
return idx;
}

function generateBARCODE(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE"+index+"";
idx.size = "15";
idx.align = "center";
return idx;
}

function generateBiaya(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "namabiaya"+index+"";
idx.id = "namabiaya"+index+"";
idx.size = "40";
idx.readOnly = "readonly";
return idx;
}

function generateqty(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Qty"+index+"";
idx.id = "Qty"+index+"";
idx.size = "3";
idx.style="text-align:right;";
return idx;
}

function generatepacking(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "packing"+index+"";
idx.id = "packing"+index+"";
idx.size = "16";
idx.style="text-align:left;";
return idx;
}

function generateHargaSatuan(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "hargaSatuan"+index+"";
idx.id = "hargaSatuan"+index+"";
idx.size = "10";
idx.style="text-align:right;";
return idx;
}

function generateSubtotal(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Subtotal"+index+"";
idx.id = "Subtotal"+index+"";
idx.size = "10";
idx.style="text-align:right;";
return idx;
}

function generateDel1(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "del1"+index+"";
idx.id = "del1"+index+"";
idx.size = "10";
idx.value = "X";
return idx;

}

function delRow1(id){ 
	var el = document.getElementById("t1"+id);
	baris1-=1;
	el.parentNode.removeChild(el);
	return false;
}



function hitungrow() 
{
	document.form2.jum.value= baris1;
}

function tutup(){
window.close();
}

function cetak(){
    var pesan        = '';
	var totalRp      = document.getElementById("subtotal").value;
	var tgltransaksi = document.getElementById("tanggal").value;

		if(totalRp == ''){
		pesan='Biaya Tidak Boleh Kosong';	
		}else if(tgltransaksi == ''){
		pesan='Tanggal Transaksi Tidak Boleh Kosong';	
		}

	    var arr_idbarang=[];
		for (i=1;i<(baris1);i++){
			arr_idbarang[i-1] = document.getElementById("idbiaya"+i+"").value;	
				if (arr_idbarang[i-1]==""){
				pesan = 'Masukan Nama Biaya Kembali\n';	
				}
			}
				
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else
	{	var answer = confirm("Mau Simpan datanya????")
		if (answer)
		{	
		hitungrow();
		// hitung() ;
		document.form2.action="simpanOperasional.php?trans=INSERT";
		document.form2.submit();
		}
		else
		{}
    } 
}	

// document.onkeydown = function (e) {
//                 switch (e.keyCode) {
//                     // esc
//                     case 27:
//                         //setTimeout('self.location.href="logout.php"', 0);
//                         //alert('esc');
// 						tutup();
// 						break;
//                     case 113:
//                         //setTimeout('self.location.href="logout.php"', 0);
//                         //alert('f2');
// 						addNewRow1();
// 						break;
//                     // f4
//                     case 115:
//                         //setTimeout('self.location.href="help.php"', 0);
//                         //alert('f3');
// 						cetak();
// 						break;
//                 }
//                 //menghilangkan fungsi default tombol
//                 //e.preventDefault();
//             };
    
	

</script>
</body>