
<?php
include("../../include/koneksi.php");

//inisialisasi input penjualan B2B
$id_customer=$_GET['id_cust'];
$sql_init="SELECT c.* FROM mst_b2bcustomer c where c.id ='".$id_customer."' ";
//var_dump($sql_init);die;
$data = mysql_query($sql_init);
$rs = mysql_fetch_array($data);
$customer=$rs['nama'];
$alamat=$rs['alamat'];	
$telp=$rs['no_telp'];	
?>
<head>
<title>B2B SALES <?php echo"".$customer;?></title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<!--<script src="../../assets/js/time.js" type="text/javascript"></script>-->
<style>
body {
    background-color:#E2D65E ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
		$("#salesman").autocomplete("lookup_salesman.php?", {
		width: 158
  });
  
    $("#salesman").result(function(event, data, formatted) {
	var nama_sm = document.getElementById("salesman").value;
	for(var h=0;h< nama_sm.length;h++){
		var did = nama_sm.split(':');
		if (did[0]=="") continue;
		var id_d=did[0];
	}
	
	//alert("id_d="+id_d);
	$.ajax({
		url : 'lookup_salesman_ambil.php?id='+id_d,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
			var id_salesman  = data.id;
			$('#id_salesman').val(id_salesman);
			var nama  = data.nama;
			$('#salesman').val(nama);
		//var disc_salesman  = data.disc;
		//   $('#disc_customer').val(disc_customer);
        //var deposit  = data.trdeposit;
		    //$('#byr_deposit').val(deposit);
		//    $('#saldo_deposit').val(deposit);
		}
	});	
			
	});
	
	//autocomplete region
	// $("#region").autocomplete("lookup_address.php?", {
	// 	width: 358
	// });
	// $("#region").result(function(event, data, formatted) {
	
	// var nama_rg = document.getElementById("region").value;
	
	// for(var i=0;i<nama_rg.length;i++){
	// 	var id = nama_rg.split(':');
	// 	if (id[0]=="") continue;
	// 	var id_rg=id[0];
	// }
		//console.log("here="+id);
		//console.log(id_rg);
		//alert("id_rg="+id_rg);
  	    //document.getElementById("id_address").innerHTML.value = id_rg;
	// $.ajax({
	// 	url : 'lookup_address_ambil.php?id='+id_rg,
	// 	dataType: 'json',
	// 	data: "nama="+formatted,
	// 	success: function(data) {
	// 	var id_address  = data.id;
	// 		$('#id_address').val(id_address);
 //        }
	// 	});
	// });
    
	//autocomplete category
	$("#kategori").autocomplete("lookup_kategori.php?", {
		width: 158
	});
	
	$("#kategori").result(function(event, data, formatted) {
	var nama_kat = document.getElementById("kategori").value;
	
	for(var j=0;j<nama_kat.length;j++){
		var e_id = nama_kat.split(':');
		if (e_id[0]=="") continue;
		var id_exp=e_id[0];
	}
	
	$.ajax({
		url : 'lookup_kategori_ambil.php?id='+id_exp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
			var id_kategori  = data.id;
			$('#id_kategori').val(id_kategori);
			var nama  = data.nama;
			$('#kategori').val(nama);

			if(nama == 'SDL' || nama == 'SOL'){
				$.ajax({
					url : 'lookup_salesman_ambil.php?id=3',
					dataType: 'json',
					data: "nama="+formatted,
					success: function(data) {
						var id_salesman  = data.id;
						$('#id_salesman').val(id_salesman);
						var nama  = data.nama;
						$('#salesman').val(nama);
					}
				});	
			}else if(nama == 'SDC'){
				$.ajax({
					url : 'lookup_salesman_ambil.php?id=1',
					dataType: 'json',
					data: "nama="+formatted,
					success: function(data) {
						var id_salesman  = data.id;
						$('#id_salesman').val(id_salesman);
						var nama  = data.nama;
						$('#salesman').val(nama);
					}
				});	
			}
        }
	});

	

	});
	
	//autocomplete expedition
	$("#expedition").autocomplete("lookup_expedition.php?", {
		width: 158
	});
	
	$("#expedition").result(function(event, data, formatted) {
	var nama_exp = document.getElementById("expedition").value;
	
	for(var j=0;j<nama_exp.length;j++){
		var e_id = nama_exp.split(':');
		if (e_id[0]=="") continue;
		var id_exp=e_id[0];
	}
	
	$.ajax({
		url : 'lookup_expedition_ambil.php?id='+id_exp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_expedition  = data.id;
			$('#id_expedition').val(id_expedition);
        }
		});
	});
	
  });

</script>
 
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>B2B ORDER INPUT &nbsp; $customer</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> TOTAL QTY <input type='text' class='' name='totalqty' id='totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalhidden' id='totalhidden'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Kode Order</td>
        <td><input type='text'  id='ref_code' name='ref_code' placeholder='Code No.'  ></td>
		
		<td class='fonttext'>Tanggal PO Diinput</td>
		<td><input type='date'  id='tanggal' name='tanggal' class='datepicker'></td>
     </tr>
	 <tr>
		<td class='fonttext'>Tanggal PO Dibuat</td>
		<td><input type='date'  id='tanggalpodibuat' name='tanggalpodibuat' class='datepicker'></td>
	 </tr>
	 <tr>
        <td class='fonttext'>KATEGORI</td>
        <td><input type='text' class='inputform' name='kategori' id='kategori' placeholder='Autosuggest KATEGORI'  />
		<input type='hidden' name='id_kategori' id='id_kategori'/>
		</td>
        <td class='fonttext'>SALESMAN</td>
        <td><input type='text' class='inputform' name='salesman' id='salesman' placeholder='Autosuggest Salesman'  />
		<input type='hidden' name='id_salesman' id='id_salesman'/>
		</td>		
	</tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     
	 <tr height='1'>
     <td colspan='4'><hr/></td>
     </tr>
     <tr>
		<td class='fonttext'>Contact Person</td>
		<td><input type='text' class='inputform' name='nama' id='nama' 	value='$customer' />
		<input type='hidden' value=$id_customer name='id_customer' id='id_customer'/>
		<td class='fonttext'>Phone</td>
		<td><input type='text' class='inputform' name='telp' id='telp' 	value=$telp /></td>
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td><textarea name='alamat' id='alamat' cols='40' rows='3' placeholder='Alamat Kirim (Jalan,No)' >$alamat</textarea></td>
             
	 </tr>
	 <tr height='1'>
     <td colspan='16'><hr/></td>
	 
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='8%' class='fonttext'>Code</td>
    	<td align='center' width='12%' class='fonttext'>Products</td>
    	<td align='center' width='5%' class='fonttext'>Price@</td>
    	<td align='center' width='7%' class='fonttext'>Add Size</td>
    	<td align='center' width='58%' class='fonttext'>Size</td>
 		<td align='center' width='5%' class='fonttext'>Disc%</td>
		<td align='center' width='5%' class='fonttext'>Qty</td>
		<td align='center' width='5%' class='fonttext'>Subtotal</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>  
    </tr>
</thead>
</table>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>
<table>
    <!--
	<tr>
	    <td class='fonttext'>Expedition</td>
        <td><input type='text' class='inputform' name='expedition' id='expedition' placeholder='Autosuggest Ekspedisi' />
		<input type='hidden' name='id_expedition' id='id_expedition'/></td>
		<td class='fonttext'>Exp.Code</td>
        <td><input type='text' class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' onkeyup='hitungtotal();'/></td>
		<td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
	 -->
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='3' ></textarea></td></td>
</tr>
<tr>
<!-- <td class='fonttext'>Tunai </td> -->
<td><input type='hidden' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'>
<input type='hidden' class='inputform' name='faktur' id='faktur' /></td>
<!-- <td class='fonttext' >Tf.Bank</td> -->
<td><input type='hidden' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >&nbsp;</td>
</tr>
<tr>
<!-- <td class='fonttext' >Bayar dg Deposit</td> -->
<td><input type='hidden' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;'>
<input type='hidden' readonly placeholder='Saldo Deposit' name='saldo_deposit' id='saldo_deposit'/>
<input type='hidden' class='inputform' name='simpan_deposit' id='simpan_deposit' style='text-align:right;'></td>
<!-- <td class='fonttext'>Piutang</td> -->
<td><input type='hidden' class='inputform' name='piutang' id='piutang' style='text-align:right;'></td>
</tr>
</table>

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
<p><input type='image' value='batal' src='../../assets/images/batal.png'  id='baru'  onClick='tutup()'/></p>
</td>
</tr>

</table>";
?>

<script type="text/javascript">
//autocomplete pada grid
function get_products(a){
    //console.log('id_customer'+<?=$_GET['id_cust'];?>)  ;
   var id_customer = <?=$_GET['id_cust'];?>;  
   //var id_customer = document.getElementById("id_customer").value;  
   $("#BARCODE"+a+"").autocomplete("lookup_productsgrp.php?id_cust="+id_customer, {
	width: 158});
   //console.log('id_customer'+<?=$_GET['id_cust'];?>)  ;
   //console.log('id_customer '+id_customer)  ;
   $("#BARCODE"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("BARCODE"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0];
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_productsgrp_ambil.php?id='+id_pd+'&id_cust='+id_customer,
		dataType: 'json',
		data: "nama_produk="+formatted,
		success: function(data) {
		//console.log('produk'+data.nama_produk);
		var products  = data.product;
			$("#NamaBrg"+a+"").val(products);
		var pricelist  = data.pricelist;
			$("#Pricelist"+a+"").val(pricelist);
		var harga_pd  = data.harga;
			$("#Harga"+a+"").val(pricelist);
		var id_products  = data.id_product;
			$("#IDP"+a+"").val(id_products);
		var disc  = data.disc;
			$("#Disc"+a+"").val(disc);
		
		var size31id  = data.s31;
			$("#S31id"+a+"").val(size31id);
		if (size31id == null){
		//alert('if color grey');
		document.getElementById("S31_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S31_"+a+"").style.backgroundColor="white";
		}

		var size32id  = data.s32;
			$("#S32id"+a+"").val(size32id);
		if (size32id == null){
		//alert('if color grey');
		document.getElementById("S32_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S32_"+a+"").style.backgroundColor="white";
		}

		var size33id  = data.s33;
			$("#S33id"+a+"").val(size33id);
		if (size33id == null){
		//alert('if color grey');
		document.getElementById("S33_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S33_"+a+"").style.backgroundColor="white";
		}

		var size34id  = data.s34;
			$("#S34id"+a+"").val(size34id);
		if (size34id == null){
		//alert('if color grey');
		document.getElementById("S34_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S34_"+a+"").style.backgroundColor="white";
		}

		var size35id  = data.s35;
			$("#S35id"+a+"").val(size35id);
		if (size35id == null){
		//alert('if color grey');
		document.getElementById("S35_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S35_"+a+"").style.backgroundColor="white";
		}

		var size36id  = data.s36;
			$("#S36id"+a+"").val(size36id);
		if (size36id == null){
		//alert('if color grey');
		document.getElementById("S36_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S36_"+a+"").style.backgroundColor="white";
		}
		
		var size37id  = data.s37;
			$("#S37id"+a+"").val(size37id);
		if (size37id == null){
		//alert('if color grey');
		document.getElementById("S37_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S37_"+a+"").style.backgroundColor="white";
		}
		
		var size38id  = data.s38;
			$("#S38id"+a+"").val(size38id);
		if (size38id == null){
		//alert('if color grey');
		document.getElementById("S38_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S38_"+a+"").style.backgroundColor="white";
		}
		
		var size39id  = data.s39;
			$("#S39id"+a+"").val(size39id);
		if (size39id == null){
		//alert('if color grey');
		document.getElementById("S39_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S39_"+a+"").style.backgroundColor="white";
		}
		
		var size40id  = data.s40;
			$("#S40id"+a+"").val(size40id);
		if (size40id == null){
		//alert('if color grey');
		document.getElementById("S40_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S40_"+a+"").style.backgroundColor="white";
		}
		
		var size41id  = data.s41;
			$("#S41id"+a+"").val(size41id);
		if (size41id == null){
		//alert('if color grey');
		document.getElementById("S41_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S41_"+a+"").style.backgroundColor="white";
		}
		
		var size42id  = data.s42;
			$("#S42id"+a+"").val(size42id);
		if (size42id == null){
		//alert('if color grey');
		document.getElementById("S42_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S42_"+a+"").style.backgroundColor="white";
		}
		
		var size43id  = data.s43;
			$("#S43id"+a+"").val(size43id);
		if (size43id == null){
		//alert('if color grey');
		document.getElementById("S43_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S43_"+a+"").style.backgroundColor="white";
		}
		
		var size44id  = data.s44;
			$("#S44id"+a+"").val(size44id);
		if (size44id == null){
		//alert('if color grey');
		document.getElementById("S44_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S44_"+a+"").style.backgroundColor="white";
		}
		
		var size45id  = data.s45;
			$("#S45id"+a+"").val(size45id);
		if (size45id == null){
		//alert('if color grey');
		document.getElementById("S45_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S45_"+a+"").style.backgroundColor="white";
		}
		
		var size46id  = data.s46;
			$("#S46id"+a+"").val(size46id);
			// console.log(size46id);
		if (size46id == null){
		//alert('if color grey');
		document.getElementById("S46_"+a+"").style.backgroundColor="grey";
		} 
		else{
		//alert("else white");
		document.getElementById("S46_"+a+"").style.backgroundColor="white";
		}

        }
	});	
			
	});
//document.getElementById('BARCODE'+baris1+'').focus();	
}  
	
function addbarcode(a)
{
/*
var ke1 = document.getElementById("BARCODE["+a+"]").value;

	$.ajax({
		url : 'ambilDataBrg.php',
		dataType: 'json',
		data: "barcode="+ke1,
		success: function(data) {
		var Id_Part  = data.id_barang;
		
		var namabarang = data.nm_barang;	
		var harga      = data.hrg_beli;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('Nama['+a+']').value = namabarang;
		document.getElementById('Harga['+a+']').value = harga;
		//document.getElementById('Qty['+a+']').value = 0;	
        }
	});	
			

//addNewRow1();
document.getElementById('Qty['+a+']').focus();
//default dari kategori
document.getElementById('Idkategori['+a+']').value=1;
document.getElementById('SUBTOTAL['+a+']').value=0;
hitungjml(a);
//buat menambahkan harga kategori	
	
addkategori(a);
*/		
}

function addkategori(a)
{
/*
//var ke2 = document.getElementById("Kategori["+a+"]").value;
var ke2 = document.getElementById("cmb_kategori["+a+"]").value;
	//alert('Nilai ke2='+ke2);
	$.ajax({
		url : 'ambilDataKategori.php',
		dataType: 'json',
		data: "id_kategori="+ke2,
		success: function(data) {
		var Id_Part  = data.id;
		
		var namakategori = data.nm_jenis;	
		var harga        = data.hrg_yard;	
		
		//document.getElementById('Kategori['+a+']').value = namakategori;
		document.getElementById('Idkategori['+a+']').value = Id_Part;
		document.getElementById('Hkategori['+a+']').value = harga;
		//alert('Harga barangnya adalah='+harga);
		}
	});	
	//alert('Harga barangnya adalah='+harga);		
//addNewRow1();
document.getElementById('Qty['+a+']').focus();
hitungjml(a);
*/		
}	

var baris1=1;
addNewRow1();
function addNewRow1() 
{
validasi();
var tbl = document.getElementById("tbl_1");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't1'+baris1;

var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");
var td6 = document.createElement("td");
var td7 = document.createElement("td");
var td8 = document.createElement("td");
// var td9 = document.createElement("td");
// var td10 = document.createElement("td");
// var td11 = document.createElement("td");
// var td12 = document.createElement("td");
// var td13 = document.createElement("td");
// var td14 = document.createElement("td");
// var td15 = document.createElement("td");
// var td16 = document.createElement("td");
// var td17 = document.createElement("td");
// var td18 = document.createElement("td");
// var td19 = document.createElement("td");
// var td20 = document.createElement("td");
// var td21 = document.createElement("td");
// var td22 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generatePricelist(baris1));
td2.appendChild(generateHarga(baris1));

td3.appendChild(generateAddSize(baris1));

td4.appendChild(generateS31id(baris1));
td4.appendChild(generateS31(baris1));
td4.appendChild(generateS32id(baris1));
td4.appendChild(generateS32(baris1));
td4.appendChild(generateS33id(baris1));
td4.appendChild(generateS33(baris1));
td4.appendChild(generateS34id(baris1));
td4.appendChild(generateS34(baris1));
td4.appendChild(generateS35id(baris1));
td4.appendChild(generateS35(baris1));
td4.appendChild(generateS36id(baris1));
td4.appendChild(generateS36(baris1));
td4.appendChild(generateS37id(baris1));
td4.appendChild(generateS37(baris1));
td4.appendChild(generateS38id(baris1));
td4.appendChild(generateS38(baris1));
td4.appendChild(generateS39id(baris1));
td4.appendChild(generateS39(baris1));
td4.appendChild(generateS40id(baris1));
td4.appendChild(generateS40(baris1));
td4.appendChild(generateS41id(baris1));
td4.appendChild(generateS41(baris1));
td4.appendChild(generateS42id(baris1));
td4.appendChild(generateS42(baris1));
td4.appendChild(generateS43id(baris1));
td4.appendChild(generateS43(baris1));
td4.appendChild(generateS44id(baris1));
td4.appendChild(generateS44(baris1));
td4.appendChild(generateS45id(baris1));
td4.appendChild(generateS45(baris1));
td4.appendChild(generateS46id(baris1));
td4.appendChild(generateS46(baris1));

td4.appendChild(generateSize(baris1));

td5.appendChild(generateDisc(baris1));
td6.appendChild(generateSUBTOTALQTY(baris1));
td7.appendChild(generateSUBTOTAL(baris1));
td8.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);
row.appendChild(td7);
row.appendChild(td8);
// row.appendChild(td9);
// row.appendChild(td10);
// row.appendChild(td11);
// row.appendChild(td12);
// row.appendChild(td13);
// row.appendChild(td14);
// row.appendChild(td15);
// row.appendChild(td16);
// row.appendChild(td17);
// row.appendChild(td18);
// row.appendChild(td19);
// row.appendChild(td20);
// row.appendChild(td21);
// row.appendChild(td22);

document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Harga'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('AddsizeBtn'+baris1+'').setAttribute('onclick', 'popSize('+baris1+')');
//size 31 sd 45
document.getElementById('S31_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S32_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S33_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S34_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S35_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S36_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S37_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S38_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S39_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S40_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S41_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S42_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S43_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S44_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S45_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('S46_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');

document.getElementById('Disc'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('SUBTOTALQTY'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('SUBTOTAL'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
get_products(baris1);
baris1++;

}

function popSize(a){
	var width  = 900;
	var height = 450;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height+',scrollbars=yes';
	// var cust = $("#kodesupp").val();
	// var barang = $("#idbarang"+a+"").val();
	params += ', top='+top+', left='+left;
	if ($("#IDP"+a+"").val() != '') {
		if ($("#Size"+a+"").val() == '') {
			window.open('size_add.php?baris='+a+'&idbrg='+$("#IDP"+a+"").val(),'',params);
		} else {
			window.open('size_edit.php?baris='+a+'&idbrg='+$("#IDP"+a+"").val()+'&edit='+$("#Size"+a+"").val(),'',params);
		}
	}
};

function generateAddSize(index){
	var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "AddsizeBtn"+index+"";
	idx.id = "AddsizeBtn"+index+"";
	idx.size = "40";
	idx.value = "+";
	return idx;
}

function generateId(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Id"+index+"";
idx.id = "Id"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateBARCODE(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE"+index+"";
idx.size = "10";
idx.align = "center";
return idx;
}

function generateIDP(index) {
//id_product
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IDP"+index+"";
idx.id = "IDP"+index+"";
idx.size = "3";
idx.align = "center";
return idx;
}

function generateNama(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Nama"+index+"";
//idx.id = "Nama["+index+"]";
idx.name = "NamaBrg"+index+"";
idx.id = "NamaBrg"+index+"";
idx.size = "25";
idx.readOnly = "readonly";
//idx.disabled = "disabled";
return idx;
}

function generatePricelist(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Pricelist"+index+"";
idx.id = "Pricelist"+index+"";
idx.size = "6";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}

function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Harga"+index+"";
idx.id = "Harga"+index+"";
idx.size = "6";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS31id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S31id"+index+"";
idx.id = "S31id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS31(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S31_"+index+"";
idx.id = "S31_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS32id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S32id"+index+"";
idx.id = "S32id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS32(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S32_"+index+"";
idx.id = "S32_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS33id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S33id"+index+"";
idx.id = "S33id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS33(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S33_"+index+"";
idx.id = "S33_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS34id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S34id"+index+"";
idx.id = "S34id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS34(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S34_"+index+"";
idx.id = "S34_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS35id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S35id"+index+"";
idx.id = "S35id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS35(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S35_"+index+"";
idx.id = "S35_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS36id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S36id"+index+"";
idx.id = "S36id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS36(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S36_"+index+"";
idx.id = "S36_"+index+"";
idx.size = "1";
//idx.style="text-align:right;background-color: #72A4D2;";
//idx.style="text-align:right;background-color: #FFFFFF;";
idx.style="text-align:right";
//idx.backgroundcolor="red";
//idx.readOnly = "readonly";
return idx;
}

function generateS37id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S37id"+index+"";
idx.id = "S37id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS37(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S37_"+index+"";
idx.id = "S37_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS38id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S38id"+index+"";
idx.id = "S38id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS38(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S38_"+index+"";
idx.id = "S38_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS39id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S39id"+index+"";
idx.id = "S39id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS39(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S39_"+index+"";
idx.id = "S39_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS40id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S40id"+index+"";
idx.id = "S40id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS40(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S40_"+index+"";
idx.id = "S40_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS41id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S41id"+index+"";
idx.id = "S41id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS41(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S41_"+index+"";
idx.id = "S41_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS42id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S42id"+index+"";
idx.id = "S42id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS42(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S42_"+index+"";
idx.id = "S42_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS43id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S43id"+index+"";
idx.id = "S43id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS43(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S43_"+index+"";
idx.id = "S43_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS44id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S44id"+index+"";
idx.id = "S44id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS44(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S44_"+index+"";
idx.id = "S44_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS45id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S45id"+index+"";
idx.id = "S45id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS45(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S45_"+index+"";
idx.id = "S45_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateS46id(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S46id"+index+"";
idx.id = "S46id"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateS46(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "S46_"+index+"";
idx.id = "S46_"+index+"";
idx.size = "1";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateSize(index) {
var idx = document.createElement("textarea");
idx.cols = "75";
idx.rows = "2";
idx.name = "Size"+index+"";
idx.id = "Size"+index+"";
idx.style="text-align:left;";
idx.readOnly = "readonly";
return idx;
}

function generateDisc(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Disc"+index+"";
idx.id = "Disc"+index+"";
idx.size = "5";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}
function generateSUBTOTALQTY(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SUBTOTALQTY"+index+"";
	idx.type = "text";
    idx.id = "SUBTOTALQTY"+index+"";
	idx.align= "right";
	idx.readOnly = "readonly";
	idx.style="text-align:right;";
	idx.size = "2";
	return idx;
}

function generateSUBTOTAL(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SUBTOTAL"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SUBTOTAL"+index+"";
	idx.align= "right";
	idx.readOnly = "readonly";
	idx.style="text-align:right;";
	idx.size = "10";
	return idx;
}



function generateDel1(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "del1"+index+"";
idx.id = "del1"+index+"";
idx.size = "5";
idx.value = "X";
return idx;

}

function delRow1(id){ 
	var el = document.getElementById("t1"+id);
	if (baris1>2) {
		// baris1-=1;
		el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
    // return false;
}
}

function validasi(){
var pesan='';
var id_customer  = form2.id_customer.value;
var tanggal      = form2.tanggal.value;

	if (id_customer == '') {
            pesan = 'Customer tidak boleh kosong\n';
			form2.customer.focus;
    }
	/*
	if (tanggal == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
			form2.tanggal.focus;
    }
	*/
if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
       
		return false;
		
	}    
}

function hitungpiutang() 
{   
	var total=0;
	var totalqty=0;
    var ongkir=0;
    var tunai=0;
    var transfer=0;
    var sisa=0;
	var byr_deposit=0;
	
	
	if(document.getElementById("tunai").value == "") {
          document.getElementById("tunai").value = 0;
	}
	tunai=document.getElementById("tunai").value;
	var tunai_murni=parseInt(tunai.replace(".", ""));
	
	if(document.getElementById("transfer").value == "") {
          document.getElementById("transfer").value = 0;
	}
	transfer=document.getElementById("transfer").value;
	var transfer_murni=parseInt(transfer.replace(".", ""));
	/*
    if(document.getElementById("exp_fee").value == "") {
          document.getElementById("exp_fee").value = 0;
	}
	ongkir=document.getElementById("exp_fee").value;
	var ongkir_murni=parseInt(ongkir.replace(".", ""));
	*/
	if(document.getElementById("byr_deposit").value == "") {
          document.getElementById("byr_deposit").value = 0;
	}
	byr_deposit=document.getElementById("byr_deposit").value;
	var byr_deposit_murni=parseInt(byr_deposit.replace(".", ""));
	
	//dihitung ulang untuk mengetahui sisa
	for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE"+i+"");
		if 	(barcode != null)
	    {   
	    //alert("barcode ="+barcode.toString())
		/*
		total+= parseInt(document.getElementById("Qty"+i+"").value)* (parseInt(document.getElementById("Harga"+i+"").value)-parseInt(document.getElementById("Disc"+i+"").value));
		*/
			if(document.getElementById("SUBTOTAL"+i+"").value == "") {
			var subtotal = 0;}
			else{
			var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
			//var qty      = document.getElementById("Qty"+i+"").value;
			}
	        total+= parseInt(subtotal);
	        //totalqty+= parseInt(qty);
		}
		//else
		//return false;
	}
	
    //total dengan ongkir tp sudah dikurangi disc
    //ongkir diinput belakangan
	//total=total + ongkir_murni;	
	
	sisa = (total)-(tunai_murni+transfer_murni+byr_deposit_murni);
	//mengecek nilai piutang yang lebih kecil dari nol,diubah menjadi nol
	//artinya pembayaran lebih besar dari faktur sehingga dianggap sebagai deposit
	//alert("sisa="+sisa+",total="+total+",ongkir_murni="+ongkir_murni+",tunai="+tunai+",transfer="+transfer+",disc_dp="+disc_customer);
	//console.log(byr_deposit);
	//alert("ref.code="+document.getElementById("ref_code").value);
	
	if (sisa < 0){
	//dimasukan ke deposit
	document.getElementById("simpan_deposit").value = -sisa;
    document.getElementById("piutang").value = 0;
    }
	else{
	document.getElementById("piutang").value = sisa;
    document.getElementById("simpan_deposit").value = 0;
    }
	
	//totalhidden dipake buat validasi saja
	document.getElementById("totalhidden").value = total;	   
	//document.getElementById("totalqty").value = totalqty;	   
	document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})	
    
}

function hitungtotal(){
    
	var total=0;
	var totalqty=0;
	var sisa_tf=0;
	
	if(document.getElementById("byr_deposit").value == "") {
    var byr_deposit=0;
	}
	else
	{
	var byr_deposit=parseInt(document.getElementById("byr_deposit").value);
	}
	
	if(document.getElementById("saldo_deposit").value == "") {
       var saldo_deposit=0;;
	}
	else{
	   var saldo_deposit=parseInt(document.getElementById("saldo_deposit").value);
	}
	
	
    for (var i=1; i<=baris1;i++)
	{
	   /*if(document.getElementById("S39id"+i+"").value == "") {
       qty39=0;;
	   }
	   //else{
	   qty39 =parseInt(document.getElementById("S39_"+i+"").value);
	   //}
	   */
	   //qty39 =parseInt(document.getElementById("S39_"+i+"").value);
	   /*
	   if(document.getElementById("S40id"+i+"").value == "") {
       qty40=0;;
	   }
	   else{
	   qty40 =parseInt(document.getElementById("S40"+i+"").value);
	   }
	   */
	 var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("SUBTOTAL"+i+"").value == "") {
		var subtotal = 0;}
		else{
		var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
		}
	    
		if(document.getElementById("SUBTOTALQTY"+i+"").value == "") {
		var subtotalqty = 0;}
		else{
		var subtotalqty = document.getElementById("SUBTOTALQTY"+i+"").value;
		}
		//alert("subtotal ="+subtotal.toString())
		total+= parseInt(subtotal);
		totalqty+= parseInt(subtotalqty);
	 }
		//else{}
		//return false;
	}
	//faktur saja tanpa ongkir dan deposit
    document.getElementById("faktur").value = total;	
    //totalfaktur ditambah dengan deposit dan ongkir
	//total=total+ongkir-byr_deposit;
	//ongkir diinput belakangan
	//total=total+ongkir;
	
	//sisa_tf merupakan sisa bila total > saldo depositnya shg defaultnya jadi ke tf	
	sisa_tf=total-saldo_deposit;
	if ((saldo_deposit > 0) && (sisa_tf > 0)) {
	//defaultnya byr_deposit dan transfer bila ada deposit dan sisa>0
	document.getElementById("byr_deposit").value = total;
	document.getElementById("transfer").value = total;
	}
	else if ((saldo_deposit > 0) && (sisa_tf < 0)){
	//defaultnya byr_deposit saja bila sisa <total
	document.getElementById("byr_deposit").value = total;
	}
	else 
	{
	//defaultnya piutang(credit) bila tidak ada deposit	
    document.getElementById("transfer").value = 0;	
    document.getElementById("piutang").value = total;	
    }
	
	//totalhidden dipake buat validasi saja
	document.getElementById("totalhidden").value = total;	
	document.getElementById("totalqty").value = totalqty;	
    document.getElementById("tunai").value = 0;	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	//hitungpiutang();
}

function hitungjml(a)
{
    //validasi agar customer tidak kosong
	validasi();
	
	if((document.getElementById("S31_"+a+"").value == "")||(document.getElementById("S31id"+a+"").value == "")||(document.getElementById("S31id"+a+"").value == 0)) {
	var qty31 = 0;	    
	}	
	else{
	var qty31 = document.getElementById("S31_"+a+"").value;
	}

	if((document.getElementById("S32_"+a+"").value == "")||(document.getElementById("S32id"+a+"").value == "")||(document.getElementById("S32id"+a+"").value == 0)) {
	var qty32 = 0;	    
	}	
	else{
	var qty32 = document.getElementById("S32_"+a+"").value;
	}

	if((document.getElementById("S33_"+a+"").value == "")||(document.getElementById("S33id"+a+"").value == "")||(document.getElementById("S33id"+a+"").value == 0)) {
	var qty33 = 0;	    
	}	
	else{
	var qty33 = document.getElementById("S33_"+a+"").value;
	}

	if((document.getElementById("S34_"+a+"").value == "")||(document.getElementById("S34id"+a+"").value == "")||(document.getElementById("S34id"+a+"").value == 0)) {
	var qty34 = 0;	    
	}	
	else{
	var qty34 = document.getElementById("S34_"+a+"").value;
	}

	if((document.getElementById("S35_"+a+"").value == "")||(document.getElementById("S35id"+a+"").value == "")||(document.getElementById("S35id"+a+"").value == 0)) {
	var qty35 = 0;	    
	}	
	else{
	var qty35 = document.getElementById("S35_"+a+"").value;
	}

	if((document.getElementById("S36_"+a+"").value == "")||(document.getElementById("S36id"+a+"").value == "")||(document.getElementById("S36id"+a+"").value == 0)) {
	var qty36 = 0;	    
	}	
	else{
	var qty36 = document.getElementById("S36_"+a+"").value;
	}
	
	if((document.getElementById("S37id"+a+"").value == "")||(document.getElementById("S37_"+a+"").value == "")||(document.getElementById("S37_"+a+"").value == 0)) {
	var qty37 = 0;	    
	}
	else{
	var qty37 = document.getElementById("S37_"+a+"").value;
	}
	
	if((document.getElementById("S38id"+a+"").value == "")||(document.getElementById("S38_"+a+"").value == "")||(document.getElementById("S38_"+a+"").value == "")) {
		var qty38 = 0;	    
	}
	else{
	var qty38 = document.getElementById("S38_"+a+"").value;
	}
	
	if((document.getElementById("S39id"+a+"").value == "")|| (document.getElementById("S39_"+a+"").value == "")||(document.getElementById("S39_"+a+"").value == 0))
	{
		var qty39 = 0;	    
	}
	else{
	var qty39 = document.getElementById("S39_"+a+"").value;
	}
	
	if((document.getElementById("S40id"+a+"").value == "")||(document.getElementById("S40_"+a+"").value == "")||(document.getElementById("S40_"+a+"").value == 0)) 
	{
		var qty40 = 0;	    
	}
	else{
	var qty40 = document.getElementById("S40_"+a+"").value;
	}
	
	if((document.getElementById("S41id"+a+"").value == "")||(document.getElementById("S41_"+a+"").value == "")||(document.getElementById("S41_"+a+"").value == 0)) 
	{
		var qty41 = 0;	    
	}
	else{
	var qty41 = document.getElementById("S41_"+a+"").value;
	}
	
	if((document.getElementById("S42id"+a+"").value == "")||(document.getElementById("S42_"+a+"").value == "")||(document.getElementById("S42_"+a+"").value == 0)) {
		var qty42 = 0;	    
	}
	else{
	var qty42 = document.getElementById("S42_"+a+"").value;
	}
		
	if((document.getElementById("S43id"+a+"").value == "")||(document.getElementById("S43_"+a+"").value == "")||(document.getElementById("S43_"+a+"").value == 0)) {
		var qty43 = 0;	    
	}
	else{
	var qty43 = document.getElementById("S43_"+a+"").value;
	}
	
	if((document.getElementById("S43id"+a+"").value == "")||(document.getElementById("S43_"+a+"").value == "")||(document.getElementById("S43_"+a+"").value == 0)) {
		var qty43 = 0;	    
	}
	else{
	var qty43 = document.getElementById("S43_"+a+"").value;
	}
	
	if((document.getElementById("S44id"+a+"").value == "")||(document.getElementById("S44_"+a+"").value == "")||(document.getElementById("S44_"+a+"").value == 0)) {
		var qty44 = 0;	    
	}
	else{
	var qty44 = document.getElementById("S44_"+a+"").value;
	}
	
	if((document.getElementById("S45id"+a+"").value == "")||(document.getElementById("S45_"+a+"").value == "")||(document.getElementById("S45_"+a+"").value == 0)) {
		var qty45 = 0;	    
	}
	else{
	var qty45 = document.getElementById("S45_"+a+"").value;
	}
	
	if((document.getElementById("S46id"+a+"").value == "")||(document.getElementById("S46_"+a+"").value == "")||(document.getElementById("S46_"+a+"").value == 0)) {
		var qty46 = 0;	    
	}
	else{
	var qty46 = document.getElementById("S46_"+a+"").value;
	}

	if(document.getElementById("Pricelist"+a+"").value == ""){
    	var pricelist = 0;
	}
	else
	{
	var pricelist = document.getElementById("Pricelist"+a+"").value;
	}
	
	if(document.getElementById("Harga"+a+"").value == ""){
    	var harga = 0;
	}
	else
	{
	var harga = document.getElementById("Harga"+a+"").value;
	}
	
	if(document.getElementById("Disc"+a+"").value == ""){
    	var disc = 0;
	}
	else
	{
		var disc = document.getElementById("Disc"+a+"").value;
	}
	
	var new_harga=(100-disc)*0.01*harga;
	//document.getElementById("Harga"+a+"").value = new_harga;	
 	
	
	var jml=0;
	var total=0;
	var qty=0;
	qty=parseInt(qty31)+parseInt(qty32)+parseInt(qty33)+parseInt(qty34)+parseInt(qty35)+parseInt(qty36)+parseInt(qty37)+parseInt(qty38)+parseInt(qty39)+parseInt(qty40)+parseInt(qty41)+parseInt(qty42)+parseInt(qty43)+parseInt(qty44)+parseInt(qty45)+parseInt(qty46);
	
	//alert("qty ="+qty.toString()+',harga='+harga.toString())	
		
		//jml=qty*(harga-disc);
		jml=qty*(new_harga);
    //memasukan kembali nilai qty nya agar yang id_nya kosong diinput nilai nol
	document.getElementById("S31_"+a+"").value= qty31;
	document.getElementById("S32_"+a+"").value= qty32;
	document.getElementById("S33_"+a+"").value= qty33;
	document.getElementById("S34_"+a+"").value= qty34;
	document.getElementById("S35_"+a+"").value= qty35;
	document.getElementById("S36_"+a+"").value= qty36;
	document.getElementById("S37_"+a+"").value= qty37;
	document.getElementById("S38_"+a+"").value= qty38;
	document.getElementById("S39_"+a+"").value= qty39;
	document.getElementById("S40_"+a+"").value= qty40;
	document.getElementById("S41_"+a+"").value= qty41;
	document.getElementById("S42_"+a+"").value= qty42;
	document.getElementById("S43_"+a+"").value= qty43;
	document.getElementById("S44_"+a+"").value= qty44;
	document.getElementById("S45_"+a+"").value= qty45;
    document.getElementById("S46_"+a+"").value= qty46;

 	document.getElementById("SUBTOTAL"+a+"").value = jml.toFixed(0);	
 	document.getElementById("SUBTOTALQTY"+a+"").value = qty;	
 	hitungtotal();
	
}


function hitungrow() 
{
	document.form2.jum.value= baris1;
}

function tutup(){
window.close();
}

function cetak(){
    var pesan           = '';
    var tgl             = form2.tanggal.value;
    var id_kategori     = form2.id_kategori.value;
	var id_salesman  	= form2.id_salesman.value;
    var nama_input      = form2.nama.value;
    // var id_address      = form2.id_address.value;
    var totalfaktur     = parseInt(form2.totalhidden.value);
    var tunai           = parseInt(form2.tunai.value);
    var transfer        = parseInt(form2.transfer.value);
    var simpan_deposit  = parseInt(form2.simpan_deposit.value);
    var byr_deposit     = parseInt(form2.byr_deposit.value);
    var temp_total      = tunai + transfer;
	
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	if (tgl == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
        }    
	if (id_kategori == '') {
            pesan = 'KATEGORI PENJUALAN tidak boleh kosong\n';
        }	
    if (id_salesman == '') {
            pesan = 'Nama Salesman tidak boleh kosong\n';
        }	
    if (nama_input == '') {
            pesan = 'Nama PIC Customer tidak boleh kosong\n';
        }
	/* bole dihilangkan	
	if (id_address == '') {
            pesan = 'Region tidak boleh kosong\n';
        }
	if (id_expedition == '') {
            pesan = 'Ekspedisi tidak boleh kosong\n';
        }
	*/
	if (totalfaktur < byr_deposit) {
            pesan = 'Nilai Bayar Deposit masih  Melebihi Nilai Total Faktur\n Bayar Deposit=' +byr_deposit+', total='+totalfaktur+'. Silakan anda belanja lagi';
        }
		
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else if (totalfaktur < temp_total) {
	    //alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    var answer_deposit = confirm('Pembayaran Melebihi Nilai Total Faktur!!\n Total Bayar=' +temp_total+', totalfaktur='+totalfaktur+',Deposit='+simpan_deposit+',Mau simpan PENJUALAN dan simpan DEPOSITNYA???? ');
        if (answer_deposit)
		{	
		hitungrow();
		document.form2.action="trb2bso_save.php?jum="+baris1;
		document.form2.submit();
		}
		else
		{
		tutup();
		}
	}
	else
	{ 
		var answer = confirm("Mau Simpan data dan cetak notanya????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="trb2bso_save.php?jum="+baris1;
		document.form2.submit();
		}
		else
		{}
    /* } */
    }	
}	
	

</script>

</body>