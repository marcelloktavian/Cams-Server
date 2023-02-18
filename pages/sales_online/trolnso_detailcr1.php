<head>
<title>ONLINE CREDIT</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<script src="../../assets/js/time.js" type="text/javascript"></script>
<style>
body {
    background-color:#daad86 ;
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
	for(var h=0;h< nama_ds.length;h++){
		var did = nama_ds.split(':');
		if (did[0]=="") continue;
		var id_d=did[0];
	}
	
	//alert("id_d="+id_d);
	$.ajax({
		url : 'lookup_dropshipper_ambil.php?id='+id_d,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_dropshipper  = data.id;
			$('#id_dropshipper').val(id_dropshipper);
		var disc_dropshipper  = data.disc;
		   $('#disc_dropshipper').val(disc_dropshipper);
        var deposit  = data.trdeposit;
		    //$('#byr_deposit').val(deposit);
		    $('#saldo_deposit').val(deposit);
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
    	<td  class='fontjudul'>ADD CREDIT SALES</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> TOTAL QTY <input type='text' class='' name='totalqty' id='totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalhidden' id='totalhidden'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <!--        
		<td class='fonttext'>Kode Transaksi</td>        
        <td>
		
		<input type='hidden' class='inputform' name='kode_hidden' id='kode_hidden' value='$id_pkb'/>
		<input type='text' class='inputform' name='kode' id='kode' value='$id_pkb'disabled='disabled'/>
		
		</td>
		-->
		<td class='fonttext'>Ref.Code</td>
        <td><input type='text'  id='ref_code' name='ref_code' placeholder='ID WEBSITE'  >( Harap kosongkan/Jangan diisi nol bila tidak ada no.websitenya )</td>
		<td class='fonttext'>DROPSHIPPER</td>
        <td><input type='text' class='inputform' name='dropshipper' id='dropshipper' placeholder='Autosuggest Dropshipper'  />
		<input type='hidden' name='id_dropshipper' id='id_dropshipper'/>
		
		</td>
     </tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Tanggal</td>
		<!-- dimatikan karena tanggalnya jadi now()
        <td><input type='date'  id='tanggal' name='tanggal' class='datepicker'></td>
		-->
		<td><div id='clock'></div></td>
        <td class='fonttext'>Disc.DROPSHIPPER</td>
        <td><input type='text' class='inputform' name='disc_dropshipper' id='disc_dropshipper' placeholder='discount dropshipper'  />
		</td>      
   
	 </tr>
	 <tr height='1'>
     <td colspan='4'><hr/></td>
     </tr>
     <tr>
		<td class='fonttext'>Name</td>
		<td><input type='text' class='inputform' name='nama' id='nama' 	placeholder='Nama Penerima'  />
		<td class='fonttext'>Phone</td>
		<td><input type='text' class='inputform' name='telp' id='telp' 	placeholder='Telp' /></td>
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td><textarea name='alamat' id='alamat' cols='40' rows='3' placeholder='Alamat Kirim (Jalan,No)' ></textarea></td>
		<td class='fonttext'>REGION</td>
        <td><input type='text' class='inputform' name='region' id='region' placeholder='Autosuggest Kecamatan'  />
		<input type='hidden' name='id_address' id='id_address'/>
		</td>               
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
	    <td class='fonttext'>Expedition</td>
        <td><input type='text' class='inputform' name='expedition' id='expedition' placeholder='Autosuggest Ekspedisi' />
		<input type='hidden' name='id_expedition' id='id_expedition'/></td>
		<td class='fonttext'>Exp.Code</td>
        <td><input type='text' style='text-transform: uppercase;' class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' onkeyup='hitungtotal();'/></td>
		<td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=1 align='left'><textarea name='txtbrg' id='txtbrg' cols='55' rows='2' ></textarea></td>
<td class='fonttext'>Disc.Faktur </td>
<td><input type='text' class='inputform' name='disc_faktur' id='disc_faktur' style='text-align:right;' onkeyup='hitungtotal();'></td>
</tr>
<tr>
<td class='fonttext'>Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'><input type='hidden' class='inputform' name='faktur' id='faktur' /></td>
<td class='fonttext' >Tf.Bank</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >&nbsp;</td>
</tr>
<tr>
<td class='fonttext' >Bayar dg Deposit</td>
<td><input type='text' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;'><input type='text' readonly placeholder='Saldo Deposit' name='saldo_deposit' id='saldo_deposit'/><input type='hidden' class='inputform' name='simpan_deposit' id='simpan_deposit' style='text-align:right;'></td>
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
		url : 'lookup_products_ambil.php?id='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
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
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'validasi()');
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
var id_dropshipper  = form2.id_dropshipper.value;

	if (id_dropshipper == '') {
            pesan = 'Dropshipper tidak boleh kosong\n';
			form2.dropshipper.focus;
    }
	
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
    var discfaktur=0;
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
	
    if(document.getElementById("exp_fee").value == "") {
          document.getElementById("exp_fee").value = 0;
	}
	ongkir=document.getElementById("exp_fee").value;
	var ongkir_murni=parseInt(ongkir.replace(".", ""));
	
	if(document.getElementById("disc_faktur").value == "") {
          document.getElementById("disc_faktur").value = 0;
	}
	discfaktur=document.getElementById("disc_faktur").value;
	var discfaktur_murni=parseInt(discfaktur.replace(".", ""));
	
	if(document.getElementById("byr_deposit").value == "") {
          document.getElementById("byr_deposit").value = 0;
	}
	byr_deposit=document.getElementById("byr_deposit").value;
	var byr_deposit_murni=parseInt(byr_deposit.replace(".", ""));
	
	if(document.getElementById("disc_dropshipper").value == "") {
          document.getElementById("disc_dropshipper").value = 0;
	}
	else
	{
	var disc_dropshipper=parseFloat(document.getElementById("disc_dropshipper").value);
	}
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
			var qty      = document.getElementById("Qty"+i+"").value;
			}
	        total+= parseInt(subtotal);
	        totalqty+= parseInt(qty);
		}
		//else
		//return false;
	}
	
    //total dengan ongkir tp sudah dikurangi disc
    total=(1-disc_dropshipper)*total;	
	total=total+ongkir_murni-discfaktur_murni;
	
    sisa = (total)-(tunai_murni+transfer_murni+byr_deposit_murni);
	//mengecek nilai piutang yang lebih kecil dari nol,diubah menjadi nol
	//artinya pembayaran lebih besar dari faktur sehingga dianggap sebagai deposit
	//alert("sisa="+sisa+",total="+total+",ongkir_murni="+ongkir_murni+",tunai="+tunai+",transfer="+transfer+",disc_dp="+disc_dropshipper);
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
	document.getElementById("totalqty").value = totalqty;	   
	document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})	
    
}

function hitungtotal(){
    
	var total=0;
	var totalqty=0;
	var sisa_tf=0;
	var discfaktur=0;
    
	if(document.getElementById("exp_fee").value == "") {
       var ongkir=0;;
	}
	else{
	ongkir=parseInt(document.getElementById("exp_fee").value);
	}
	
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
	
	if(document.getElementById("disc_dropshipper").value == "") {
          document.getElementById("disc_dropshipper").value = 0;
	}
	else
	{
	var disc_dropshipper=parseFloat(document.getElementById("disc_dropshipper").value);
	}
	
	if(document.getElementById("disc_faktur").value == "") {
          document.getElementById("disc_faktur").value = 0;
	}
	discfaktur=document.getElementById("disc_faktur").value;
	var discfaktur_murni=parseInt(discfaktur.replace(".", ""));
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("SUBTOTAL"+i+"").value == "") {
		var subtotal = 0;}
		else{
		var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
		var qty      = document.getElementById("Qty"+i+"").value;
		}
	    //alert("subtotal ="+subtotal.toString())
		total+= parseInt(subtotal);
		totalqty+= parseInt(qty);
	 }
		//else{}
		//return false;
	}
	//total sudah dikurangi disc dropshipper
    total=(1-disc_dropshipper)*total;
	//faktur saja tanpa ongkir dan deposit
    document.getElementById("faktur").value = total;	
    
	//totalfaktur ditambah dengan ongkir dikurangi disc_faktur
	total=total+ongkir - discfaktur_murni;
    
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
    var nama_input      = form2.nama.value;
    //var tgl             = form2.tanggal.value;
    var id_dropshipper  = form2.id_dropshipper.value;
    var id_address      = form2.id_address.value;
    var id_expedition   = form2.id_expedition.value;
	var totalfaktur     = parseInt(form2.totalhidden.value);
    var tunai           = parseInt(form2.tunai.value);
    var transfer        = parseInt(form2.transfer.value);
    var simpan_deposit  = parseInt(form2.simpan_deposit.value);
    var byr_deposit     = parseInt(form2.byr_deposit.value);
    var temp_total      = tunai + transfer;
	
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	
    if (nama_input == '') {
            pesan = 'Nama Penerima tidak boleh kosong\n';
        }
	/* dimatikan tanggalnya
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