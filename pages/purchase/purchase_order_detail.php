<head>
<title>PURCHASE ORDER</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>

<style>
body {
    background-color:#FDF6B5 ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
		$("#supplier").autocomplete("lookup_supplier.php?", {
		width: 158
  });
  
    $("#supplier").result(function(event, data, formatted) {
	var nama_ds = document.getElementById("supplier").value;
	for(var h=0;h< nama_ds.length;h++){
		var did = nama_ds.split(':');
		if (did[0]=="") continue;
		var id_d=did[0];
	}
	
	$.ajax({
		url : 'lookup_supplier_ambil.php?id='+id_d,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var id_supplier  = data.id;
			$('#id_supplier').val(id_supplier);
		    var supplier  = data.nama;
		    $('#supplier').val(supplier);
		}
	});	
			
	});
	
	//autocomplete region
	$("#region").autocomplete("lookup_address.php?", {
		width: 358
	});
	$("#region").result(function(event, data, formatted) {
	
	var nama_rg = document.getElementById("region").value;
	
	for(var i=0;i<nama_rg.length;i++){
		var id = nama_rg.split(':');
		if (id[0]=="") continue;
		var id_rg=id[0];
	}
		//console.log("here="+id);
		//console.log(id_rg);
		//alert("id_rg="+id_rg);
  	    //document.getElementById("id_address").innerHTML.value = id_rg;
	$.ajax({
		url : 'lookup_address_ambil.php?id='+id_rg,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_address  = data.id;
			$('#id_address').val(id_address);
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
 <?php 
  include("../../include/koneksi.php");
  
	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
  

function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_trans from olnso order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -5);
	$bulan=substr($q['id_trans'], -7,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
	//echo"Kode=".$kode."Num=".$num."bulan=".$bulan;
	
	if($num==0 || $num==null || $bulan!=$bln_skrng)		
	{
		$temp = 1;
	}
	else
	{
		$temp=$num+1;
	}
	return $temp;
}

function getmonthyeardate2()
{
	$today = date('ym');
	return $today;
}

function getnewnotrxwait2()
{
	
	$temp=getmonthyeardate2();
	$temp2=getincrementnumber2();
	$id="OLN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	
}	
//$id_registrasi = getnewnotrxwait();
$id_pkb = getnewnotrxwait2();

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>ADD PURCHASE ORDER</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> TOTAL QTY <input type='text' class='' name='totalqty' id='totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- Hidden krn tidak diacc sama Enrico-->
		<input type='hidden' class='' name='total_blmdisc' id='total_blmdisc' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalhidden' id='totalhidden'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
		<td class='fonttext'>No PO</td>
        <td><input type='text' class='inputform' id='ponum' name='ponum' placeholder='Nomor PO'  ></td>
		<td class='fonttext'>Supplier</td>
        <td><input type='text' class='inputform' name='supplier' id='supplier' placeholder='Autosuggest Supplier'  />
		<input type='hidden' name='id_supplier' id='id_supplier'/>
		</td>
     </tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    
	    <td class='fonttext'>Tanggal PO</td>
		<td><input type='date' class='inputform' id='tanggalpo' name='tanggalpo' class='datepicker'></td>
        <td class='fonttext'>Tanggal Jatuh Tempo</td>
		<td><input type='date' class='inputform' id='tanggaljt' name='tanggaljt' class='datepicker'></td> 
   
	 </tr>
	 <tr height='1'>
     <td colspan='4'><hr/></td>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='15%' class='fonttext'>Code</td>
    	<td align='center' width='25%' class='fonttext'>Products</td>
    	<td align='center' width='10%' class='fonttext'>Price@</td>
      	<td align='center' width='5%' class='fonttext'>Qty</td>
      	<td align='center' width='5%' class='fonttext'>Size</td>
      	<td align='center' width='10%' class='fonttext'>Disc@</td>
      	<td align='center' width='25%' class='fonttext'>Subtotal</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>
<table>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=1 align='left'><textarea name='txtbrg' id='txtbrg' cols='55' rows='2' ></textarea></td>
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
   $("#BARCODE"+a+"").autocomplete("lookup_products.php?", {
	width: 178});
  console.log('here'+a)  ;
   $("#BARCODE"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("BARCODE"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0];
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_products_ambil.php?id='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
            var id  = data.id;
			$("#Id"+a+"").val(id);
		var products  = data.nama;
			$("#NamaBrg"+a+"").val(products);
		var harga_pd  = data.harga;
			$("#Harga"+a+"").val(harga_pd);
		var id_products  = data.id;
			$("#IDP"+a+"").val(id_products);
		var product_size  = data.size;
			$("#Size"+a+"").val(product_size);
			//$("#Qty"+a+"").val(1);
			$("#Qty"+a+"").focus();

		$.ajax({
			url : 'lookup_productstok_ambil.php?id='+id_pd,
			dataType: 'json',
			data: "nama="+formatted,
			success: function(data2) {
				var stok  = data2.stok;
				$("#Stok"+a+"").val(stok);
				$("#Qty"+a+"").focus();
        }
	});	
			
			
			//var type  = data.type;
			//$('#type').val(type);
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

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateQty(baris1));
td3.appendChild(generateStok(baris1));
td4.appendChild(generateSize(baris1));
td5.appendChild(generateDisc(baris1));
td6.appendChild(generateSUBTOTAL(baris1));
td7.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);
row.appendChild(td7);

document.getElementById('BARCODE'+baris1+'').focus();
//document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'validasi()');
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'get_products('+baris1+')');
//document.getElementById('cmb_kategori['+baris1+']').setAttribute('onChange', 'addkategori('+baris1+')');
//document.getElementById('Cari1['+baris1+']').setAttribute('onclick', 'popjasa('+baris1+')');
//document.getElementById('Cari2['+baris1+']').setAttribute('onclick', 'popkategori('+baris1+')');
document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Disc'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('SUBTOTAL'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
get_products(baris1);
baris1++;
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
idx.size = "15";
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
idx.size = "40";
idx.readOnly = "readonly";
//idx.disabled = "disabled";
return idx;
}
function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Harga"+index+"";
idx.id = "Harga"+index+"";
idx.size = "8";
idx.readOnly = "readonly";
return idx;
}
function generateQty(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Qty"+index+"";
//idx.id = "Qty["+index+"]";
idx.name = "Qty"+index+"";
idx.id = "Qty"+index+"";
idx.size = "3";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateStok(index) {
var idx = document.createElement("input");
idx.type = "hidden";
//idx.name = "Qty"+index+"";
//idx.id = "Qty["+index+"]";
idx.name = "Stok"+index+"";
idx.id = "Stok"+index+"";
idx.size = "3";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}
function generateSize(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Size"+index+"";
idx.id = "Size"+index+"";
idx.size = "3";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateDisc(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Disc"+index+"";
idx.id = "Disc"+index+"";
idx.size = "8";
idx.style="text-align:right;";
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
	idx.size = "25";
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
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
	return false;
}

function validasi(){
var pesan='';
var id_supplier  = form2.id_supplier.value;

	if (id_supplier == '') {
            pesan = 'Supplier tidak boleh kosong\n';
			form2.supplier.focus;
    }
	
if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
        return false;
	}    	
}


function hitungtotal(){
    
	var total=0;
	var totalqty=0;

    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("SUBTOTAL"+i+"").value == "") {
		var subtotal = 0;}
		else{
		var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
		var qty = document.getElementById("Qty"+i+"").value;
		}
	    //alert("subtotal ="+subtotal.toString())
		total+= Math.ceil(parseInt(subtotal));
		totalqty+= parseInt(qty);
		total_blmdisc+= parseInt(subtotal);
	 }
		//else{}
		//return false;
	}
	
	//totalhidden dipake buat validasi saja
	document.getElementById("totalhidden").value = total;	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalqty").value = totalqty;
}

function hitungjml(a)
{
	//validasi agar dropshipper tidak kosong
	validasi();
	
	if(document.getElementById("Qty"+a+"").value == "") {
		var qty = 0;	    
	}
	else{
	var qty = document.getElementById("Qty"+a+"").value;
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
	var jml=0;
	var total=0;
	//alert("qty ="+qty.toString()+',harga='+harga.toString())	
		
		jml=qty*(harga-disc);
    
 	document.getElementById("SUBTOTAL"+a+"").value = jml;	
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
    var ponum           = form2.ponum.value;
    var id_supplier     = form2.id_supplier.value;
    var tanggalpo       = form2.tanggalpo.value;
    var tanggaljt       = form2.tanggaljt.value;

	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	
	//validasi untuk menghitung ulang total
    for (var i=1; i<=baris1;i++){
	var tmpsubtotal=0;
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("SUBTOTAL"+i+"").value == "") {
		var subtotal = 0;}
		else{
		var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
		var qty = document.getElementById("Qty"+i+"").value;
		var harga = document.getElementById("Harga"+i+"").value;		
		var tmpsubtotal= parseInt(qty)*parseInt(harga);

		}
		
	    //alert("subtotal baru ="+tmpsubtotal.toString()+",subtotal_lama ="+subtotal.toString())
        if (tmpsubtotal != subtotal) {
            pesan = 'Maaf,Subtotal ada yang salah!! Silakan lakukan pengecekan ulang.';
        }
	 }			    		
	}
    //-----end here-------------------
	
    	
    for (var i=1; i<=baris1;i++){
    	if (document.getElementById("SUBTOTAL"+i+"")!=null) {
    		console.log(document.getElementById("NamaBrg"+i+"").value);

    		var subtotal = document.getElementById("Qty"+i+"").value;
			var stok = document.getElementById("Stok"+i+"").value;

			if(parseInt(subtotal) > parseInt(stok)){
				pesan += 'Cek stok barang\n';
    			break;
			}
    	}
    	
    }

	if (nama_input == '') {
            pesan = 'Nama Penerima tidak boleh kosong\n';
        }
	/*
	if (tgl == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
        }
	*/
	if (id_dropshipper == '') {
            pesan = 'Dropshipper tidak boleh kosong\n';
        }
	if (id_address == '') {
            pesan = 'Region tidak boleh kosong\n';
        }
	if (id_expedition == '') {
            pesan = 'Ekspedisi tidak boleh kosong\n';
        }

	// if ((form2.transfer.value != '' && form2.transfer.value != '0') && (form2.byr_deposit.value != '' && form2.byr_deposit.value != '0')) {
 //            pesan = 'Isi salah satu transfer atau deposit\n';
 //        }
	/*
	if (totalfaktur < byr_deposit) {
            pesan = 'Nilai Bayar Deposit masih  Melebihi Nilai Total Faktur\n Bayar Deposit=' +byr_deposit+', total='+totalfaktur+'. Silakan anda belanja lagi';
        }
	*/

	$.ajax({
  		type: "POST",
  		url: "ajax_expcode.php",
  		data: {expcode: exp_code},
  		dataType: "text",
  		success: function (res) {
  			console.log(res);
  			if (res=='0') {
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
					document.form2.action="trolnso_save.php";
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
					document.form2.action="trolnso_save.php";
					document.form2.submit();
				}
				else{}
    		}
  		}else{
  			alert('Kode Expedisi sudah dipakai di transaksi lain!!');
  		}
  		}
	});	
}	

function convertToRupiah(objek) 
{
	  separator = ".";
	  a = objek.value;
	  b = a.replace(/[^\d]/g,"");
	  c = "";
	  panjang = b.length; 
	  j = 0; 
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;

}       
	

</script>

</body>