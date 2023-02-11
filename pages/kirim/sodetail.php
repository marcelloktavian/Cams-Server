<head>
<title>Tambah Pengiriman</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<!--
<script src="../../assets/js/time.js" type="text/javascript"></script>
-->
<!-- library datetimepicker-->
<!--
<link rel="stylesheet" href="../../assets/css/ui.jqgrid.css" type="text/css" media="all" /> 

<link type="text/css" href="../../assets/css/ui-lightness/ui.all.css" rel="stylesheet" />

<script src="../../assets/js/jquery-1.5.2.min.js" type="text/javascript" charset="utf-8"></script> 

<script src="../../assets/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>

<script src="../../assets/js/jquery.jqGrid.src.js" type="text/javascript" charset="utf-8"></script> 
-->
<!-- end here -->
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
    background-color:Moccasin ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
		$("#dropshipper").autocomplete("lookup_dropshipper.php?", {
		width: 158
  });
  
    $("#dropshipper").result(function(event, data, formatted) {
	var nama_ds = document.getElementById("dropshipper").value;
	$.ajax({
		url : 'lookup_dropshipper_ambil.php?nama='+nama_ds,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_dropshipper  = data.id;
			$('#id_dropshipper').val(id_dropshipper);
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
		console.log("here="+id);
		//console.log(id_rg);
		//alert("id_rg="+id_rg);
  	    //document.getElementById("id_address").innerHTML.value = id_rg;
	$.ajax({
		url : 'lookup_address_ambil.php?nama='+id_rg,
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
	var nama_rg = document.getElementById("expedition").value;
	$.ajax({
		url : 'lookup_expedition_ambil.php?nama='+nama_rg,
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
  /*
  function getincrementnumber()
	{
		$q = mysql_fetch_array( mysql_query('select id_registrasi from registrasi order by id_registrasi desc limit 0,1'));
		
		$kode=substr($q['id_registrasi'], -4);
		$bulan=substr($q['id_registrasi'], -6,2);
		$bln_skrng=date('m');
		$num=(int)$kode;
		
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
   */
	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
   /*
   function getnewnotrxwait()
	{
		
		$temp=getmonthyeardate();
		$temp2=getincrementnumber();
		$id="REG".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
		return $id;
		
	}	
    */

function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_trans from trbeli order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -4);
	$bulan=substr($q['id_trans'], -6,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
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
	$id="PKB".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
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
    	<td  class='fontjudul'>TAMBAH PENGIRIMAN</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Kode Transaksi</td>        
        <td>
		<input type='hidden' class='inputform' name='kode_hidden' id='kode_hidden' value='$id_pkb'/>
		<input type='text' class='inputform' name='kode' id='kode' value='$id_pkb'disabled='disabled'/>
		</td>
		<td class='fonttext'>Ref.Code</td>
        <td><input type='text'  id='ref_code' name='ref_code' placeholder='ID WEBSITE'  ></td>
     </tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Tanggal</td>
        <td><input type='date'  id='tanggal' name='tanggal' class='datepicker'></td>
        <td class='fonttext'>DROPSHIPPER</td>
        <td><input type='text' class='inputform' name='dropshipper' id='dropshipper' placeholder='Autosuggest Dropshipper'  />
		<input type='hidden' name='id_dropshipper' id='id_dropshipper'/>
		</td>      
   
	 </tr>
	 <tr height='1'>
     <td colspan='4'><hr/></td>
     </tr>
     <tr>
		<td class='fonttext'>Name</td>
		<td><input type='text' class='inputform' name='nama' id='nama' 	placeholder='Nama Penerima'  />
		<td class='fonttext'>Phone</td>
		<td><input type='text' class='inputform' name='nama' id='nama' 	placeholder='Telp' /></td>
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td><textarea name='textarea' id='alamat' cols='31' rows='2' placeholder='Alamat Kirim' ></textarea></td>
		<td class='fonttext'>REGION</td>
        <td><input type='text' class='inputform' name='region' id='region' placeholder='Autosuggest Kecamatan'  />
		<input type='hidden' name='id_address' id='id_address'/>
		</td>               
	 </tr>
	 <tr height='1'>
     <td colspan='4'><hr/></td>
	 <tr>
	    <td class='fonttext'>Expedition</td>
        <td><input type='text' class='inputform' name='expedition' id='expedition' placeholder='Autosuggest Ekspedisi' />
		<input type='hidden' name='id_expedition' id='id_expedition'/></td>
		<td class='fonttext'>Exp.Code</td>
        <td><input type='text' class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
	 </tr>
	 <tr height='1'>
     <td colspan='4'></td>
	 <tr>
	    <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' /></td>
		<td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
	 <tr height='1'>
     <td colspan='6'><hr/></td>
	 
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='15%' class='fonttext'>Code</td>
    	<td align='center' width='25%' class='fonttext'>Products</td>
    	<td align='center' width='10%' class='fonttext'>Price@</td>
      	<td align='center' width='5%' class='fonttext'>Qty</td>
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
<table>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='2' ></textarea></td></td>
</tr>
<tr>
<td class='fonttext'>Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'></td>
<td class='fonttext' >Bank</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >&nbsp;</td>
</tr>
<tr>
<td class='fonttext' >Bayar dg Deposit</td>
<td><input type='text' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;'></td>
<td class='fonttext'>Piutang</td>
<td><input type='text' class='inputform' name='piutang' id='piutang' style='text-align:right;'></td>

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
	width: 158});
  //console.log('here'+a)  ;
   $("#BARCODE"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("BARCODE"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0];
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_products_ambil.php?nama='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var products  = data.nama;
			$("#Nama"+a+"").val(products);
		var harga_pd  = data.harga;
			$("#Harga"+a+"").val(harga_pd);
		var id_products  = data.id;
			$("#Id"+a+"").val(id_products);
			$("#Qty"+a+"").focus();
			
			//var type  = data.type;
			//$('#type').val(type);
        }
	});	
			
	});
	
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

td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateId(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateQty(baris1));
td4.appendChild(generateDisc(baris1));
td5.appendChild(generateSUBTOTAL(baris1));
td6.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);

document.getElementById('BARCODE'+baris1+'').focus();
//document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'addbarcode('+baris1+')');
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
/*
function popjasa(a){
	
	var width  = 550;
 	var height = 400;
 	var left   = (screen.width  - width)/2;
 	var top    = (screen.height - height)/2;
  	var params = 'width='+width+', height='+height+',scrollbars=yes';
 	params += ', top='+top+', left='+left;
		window.open('popbarang.php?row='+a+'','',params);
};

function popkategori(a){
	
	var width  = 550;
 	var height = 400;
 	var left   = (screen.width  - width)/2;
 	var top    = (screen.height - height)/2;
  	var params = 'width='+width+', height='+height+',scrollbars=yes';
 	params += ', top='+top+', left='+left;
		window.open('popkategori.php?row='+a+'','',params);
};
*/

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
function generateNama(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Nama"+index+"";
//idx.id = "Nama["+index+"]";
idx.name = "Nama"+index+"";
idx.id = "Nama"+index+"";
idx.size = "40";
idx.readOnly = "readonly";
idx.disabled = "disabled";
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
idx.disabled = "disabled";
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

function generateDisc(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Disc"+index+"";
idx.id = "Disc"+index+"";
idx.size = "8";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
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
	idx.size = "15";
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
/*
function generateKategori(index) {

var idx = document.createElement("input");
idx.type = "text";
idx.name = "Kategori"+index+"";
idx.id = "Kategori["+index+"]";
idx.size = "10";
idx.align = "center";

var idx = document.createElement("select"); 
idx.name = "cmb_kategori_"+index+"";
idx.id = "cmb_kategori["+index+"]"; 
<? $sql = mysql_query('select id_jenis, nm_jenis from jenis_barang where deleted=0 order by nm_jenis ');
  while ($row = mysql_fetch_assoc($sql)) {?>
    var opt = new Option('<?=$row['nm_jenis']?>', '<?=$row['id_jenis']?>');
	idx.options.add(opt);
<?  }
 ?>
//idx.innerHtml = "<=get_list_jenisbarang()?>";
return idx;
}

function generateCari2(index) {
	var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "Cari2";
	idx.value = "...";
	idx.id = "Cari2["+index+"]";
	idx.size = "5";
	return idx;
}

function generateIdkategori(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Idkategori"+index+"";
idx.id = "Idkategori["+index+"]";
idx.size = "2";
idx.readOnly = "readonly";
return idx;
}

function generateHargakategori(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Hkategori"+index+"";
idx.id = "Hkategori["+index+"]";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateCari1(index) {
	var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "Cari1";
	idx.value = "...";
	idx.id = "Cari1["+index+"]";
	idx.size = "5";
	return idx;
}
*/

function delRow1(id){ 
	var el = document.getElementById("t1"+id);
	baris1-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
	return false;
}

function hitungpiutang()
{   
	var total=0;
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
	
       
	if(document.getElementById("byr_deposit").value == "") {
          document.getElementById("byr_deposit").value = 0;
	}
	byr_deposit=document.getElementById("byr_deposit").value;
	
	var byr_deposit_murni=parseInt(byr_deposit.replace(".", ""));
	
	for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE"+i+"");
		if 	(barcode != null)
	    {   
	    //alert("barcode ="+barcode.toString())
		total+= parseInt(document.getElementById("Qty["+i+"]").value)* parseInt(document.getElementById("Harga["+i+"]").value);
		}
		//else
		//return false;
	}
    total=total+ongkir_murni;
	sisa = (total)-(tunai_murni+transfer_murni+byr_deposit_murni);
	//document.getElementById("tunai").value = total+ongkir_murni;
	//mengecek nilai piutang yang lebih kecil dari nol,diubah menjadi nol
	//artinya pembayaran lebih besar dari faktur sehingga dianggap sebagai deposit 
	if (sisa < 0){
	//dimasukan ke deposit
	document.getElementById("simpan_deposit").value = -sisa;
    document.getElementById("piutang").value = 0;
    }
	else{
	document.getElementById("piutang").value = sisa;
    document.getElementById("simpan_deposit").value = 0;
    }
	
	document.getElementById("total_hidden").value = total;
	
	document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})	
    
}

function hitungtotal(){
    
	var total=0;
	var faktur=0;
	
	if(document.getElementById("piutang").value == "") {
          document.getElementById("piutang").value = 0;
	}
	var piutang=parseInt(document.getElementById("piutang").value);
	
	if(document.getElementById("tunai").value == "") {
          document.getElementById("tunai").value = 0;
	}
	var tunai=parseInt(document.getElementById("tunai").value);
	
	if(document.getElementById("transfer").value == "") {
          document.getElementById("transfer").value = 0;
	}
	var transfer=parseInt(document.getElementById("transfer").value);
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("SUBTOTAL"+i+"").value == "") {
		var subtotal = 0;}
		else{
		var subtotal = document.getElementById("SUBTOTAL"+i+"").value;}
	    //alert("subtotal ="+subtotal.toString())
		total+= parseInt(subtotal);
	 }
		//else{}
		//return false;
	}
    document.getElementById("faktur").value = total;
    //defaultnya cash tunai	
    document.getElementById("tunai").value = total;	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	hitungpiutang();
}

function hitungjml(a)
{
	
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
    var pesan        = '';
    var nama_input   = form2.nama.value;
    var tgl          = form2.tanggal.value;
    if (nama_input == '') {
            pesan = 'Nama Customer/Supplier tidak boleh kosong\n';
        }
	if (tgl == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
        }
		
        /*dimatikan karena sudah ada default kategori=1 (Arrow Kelir) ketika input barang 
				
	    var arr_kategori=[];
		for (i=1;i<(baris1);i++){
		    arr_kategori[i-1] = document.getElementById("Idkategori["+i+"]").value;	
			//alert("arr_kategori[i-1]="+arr_kategori[i-1]);
				
				if (arr_kategori[i-1]==""){
				pesan = 'Kategori tidak boleh kosong\n';	
				}
			}
		*/		
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else
	{ 
		var answer = confirm("Mau Simpan data dan cetak notanya????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="beli_simpan.php";
		document.form2.submit();
		}
		else
		{}
    /* } */
    }	
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