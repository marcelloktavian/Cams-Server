<?
// $brg = explode('=','4');
// var_dump($brg);
					
// die;
?>

<head>
<title><?php
echo"".$_GET['nama'];?></title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script src="../../assets/js/time.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
    background-color: SkyBlue;
}
tanggal {
    color: maroon;
    margin-left: 40px;
}

#tbl_1{
clear: both;
border: 1px solid #FF6600;
height: 20px;
overflow-y:auto;
overflow-x:scroll;
float:left;
width:1200px;
} 
</style>

<script language="javascript">
$().ready(function() {	
		$("#nama").autocomplete("jual_customer.php", {
		width: 158
  });
  
   $("#nama").result(function(event, data, formatted) {
	var nama = document.getElementById("nama").value;
	$.ajax({
		url : 'jual_ambilCustomer.php?nama='+nama,
		//url : 'ambilDataSupplier.php,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var alamat  = data.alamat;
			$('#alamat').val(alamat);
			var telp  = data.telp1;
			$('#telp').val(telp);
			var id_customer  = data.id;
			$('#id_customer').val(id_customer);				
        }
	});	
			
	});
	
  });
</script>
 <?php 
 include("../../include/koneksi.php");
 //include("koneksi/koneksi.php");
 	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
 
function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_trans from trjual order by id_trans desc limit 0,1'));
	
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
	$id="TSO".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
	return $id;
}

function max_id(){
//$bilangan=1234; // Nilai Proses
//$temp = sprintf("%07d", $bilangan);
//echo "$fzeropadded "; // Hasil 0001234

$q = mysql_fetch_array(mysql_query('select (max(id)+1) as nomor from trjual_id'));
//$temp="INV-".$q['nomor'];
$temp = sprintf("%06d", $q['nomor']);

return $temp;
}	
//$id_registrasi = getnewnotrxwait();
//$id_pkb = getnewnotrxwait2();
//$id_pkb = max_id();
$id_pkb = "";//max_id();
$nama_pelanggan = $_GET['nama'];
$id_pelanggan = $_GET['id'];
//mencari nilai deposit pelanggan 
$sql_pelanggan="";
$sql_pelanggan="Select * from tblpelanggan where id=$id_pelanggan";
$sq2 = mysql_query($sql_pelanggan);
$rspelanggan=mysql_fetch_array($sq2);
$deposit_pelanggan=$rspelanggan['deposit'];
$info="";
if ($deposit_pelanggan > 0 ) {
$info="<td style='text-align:center;font-size: 15px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />PELANGGAN INI MEMILIKI DEPOSIT<input type='hidden' class='inputform' name='total_deposit' id='total_deposit' value='$deposit_pelanggan' /></td>";
}
else
{
$info="<td><input type='hidden' class='inputform' name='total_deposit' id='total_deposit' value=0 /></td>";
}
?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post' onSubmit='return validasi_input(this)'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>PENJUALAN KASIR</td>
		$info
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='' style='text-align:right;font-size: 30px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' class='inputform' name='total_hidden' id='total_hidden' />
		</td>
    </tr>
	<tr>
	
	</tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'><input type='hidden' name='cek_kasir' value='Yes'>Kode</td>        
        <td>
		<input type='hidden' class='inputform' name='kode_hidden' id='kode_hidden' value='$id_pkb'/>
		<input type='text' class='inputform' name='kode' id='kode' value='$id_pkb'disabled='disabled'/>
		</td>
		<td class='fonttext'>Tanggal</td>
        <td><div id='clock'></div></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Nama Customer</td>
        <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Autosuggest Nama Customer' value='$nama_pelanggan' />
		<input type='hidden' name='id_customer' id='id_customer' value='$id_pelanggan'/>
		</td>
     
        <td class='fonttext'>Telp</td>
        <td><input type='text' class='inputform' name='telp' id='telp' value='' disabled='disabled'/></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Alamat</td>
        <td><textarea name='textarea' id='alamat' cols='31' rows='2' disabled='disabled'></textarea></td>
        <td class='fonttext'>Simpan Deposit</td>
        <td><input type='text' class='inputform' name='simpan_deposit' id='simpan_deposit' style='text-align:right;'></td>
     </tr>
	 <tr/><td colspan='6'> F2= Tambah Baris, F4=Simpan, Esc=Tutup,Tab=Pindah Kolom</td></tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr> 
        <td align='center' width='15%' class='fonttext'>Kategori</td>
        <td align='center' width='15%' class='fonttext'>Nama BARANG</td>
 
      	<td align='center' width='3%' class='fonttext'>Kuantum</td>
      	<td align='center' width='15%' class='fonttext'>Harga Satuan</td>
      	<td align='center' width='15%' class='fonttext'>Harga Barang (+ ppn 10%)</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>   
    </tr>
</thead>
</table>
<table align='center' width='100%'>
<tr>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</td>
</tr>
</table>
<table>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='2' ></textarea></td></td>
</tr>
<tr>
<td class='fonttext' style='width:20px;'>Faktur</td>
<td><input type='text' class='inputform' name='faktur' id='faktur' style='text-align:right;align=right;'></td>
<td class='fonttext'>Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'></td>
<td class='fonttext'>&nbsp;</td>
<td>&nbsp;</td>

</tr>
<tr>
<td class='fonttext'>Ongkos Kuli</td>
<td><input type='text' class='inputform' name='ongkir' id='ongkir' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >Bank</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td class='fonttext'>Piutang</td>
<td><input type='text' class='inputform' name='piutang' id='piutang' style='text-align:right;'></td>
<td class='fonttext' >Bayar dg Deposit</td>
<td><input type='text' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;'value=$deposit_pelanggan  ></td>

</tr>
</table>
<hr/>
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


//function untuk membuat shortcut di aplikasi kasirnya

document.onkeydown = function (e) {
                switch (e.keyCode) {
                    // esc
                    case 27:
                        //setTimeout('self.location.href="logout.php"', 0);
                        //alert('esc');
						tutup();
						break;
                    case 113:
                        //setTimeout('self.location.href="logout.php"', 0);
                        //alert('f2');
						addNewRow1();
						break;
                    // f4
                    case 115:
                        //setTimeout('self.location.href="help.php"', 0);
                        //alert('f3');
						cetak();
						break;
                }
                //menghilangkan fungsi default tombol
                //e.preventDefault();
            };

//---------------------------------------------	
//--focus on nama----
document.getElementById('nama').focus();

function create_table_brg_per_jenis(baris,jenis_id){
var tbl = document.getElementById("tbl_1");
//alert(baris);
var row = tbl.insertRow(tbl.rows.length);	  

var is_exist = (document.getElementById('tbl_jenis_'+jenis_id)!=null);
 if (is_exist) {
	//document.getElementById('tbl_jenis_'+jenis_id).innerHtml = '';
	var tbljenis = document.getElementById('tbl_jenis_'+jenis_id);
	var rowCount = tbljenis.rows.length; while(--rowCount) tbljenis.deleteRow(rowCount);
	return false;
 }
var html = "<table style='display:none' align='center' width='100%' id='tbl_jenis_"+jenis_id+"'>";
	html += "<thead>";
	html += " <tr> "+
        " <td align='center' width='15%' class='fonttext'>KODE BARANG</td> "+
        " <td align='center' width='15%' class='fonttext'>Nama BARANG</td>"+
 
      	" <td align='center' width='15%' class='fonttext'>Harga</td>"+
      	" <td align='center' width='3%' class='fonttext'>Qty</td>"+
      	" <td align='center' width='15%' class='fonttext'>Jumlah</td>"+
      	" <td align='center' width='5%' class='fonttext'>Hapus</td>   "+
   "  </tr>";
	html += "</thead>";
	html += "<tr>";
	html += "</tr>";
	html += "</table>";
	
	var td0 = document.createElement("td");
	td0.colSpan = "5";
	td0.innerHTML = html;
	row.appendChild(td0);
	//return html;
	
}

barcode_event = function (id_comp){
	var txt =   document.getElementById("BARCODE["+id_comp+"]").value;;//$('#BARCODE['+id+']').val();
	var items  = txt.split(',');
	var arr_id =[];
	var jenis =document.getElementById("cmb_kategori["+id_comp+"]").value;
	var arr_qty =[];
	for(var i=0;i<items.length;i++){
		id = items[i].split('=');
		//alert(id);
		// arr_id += id[0]+',';
		if (id[0]=="") continue;
		
		arr_id[i] = id[0];//+',';
		if (id[1]===undefined) 
			arr_qty[id[0]] = 1;
		else
			arr_qty[id[0]] = id[1];
	}
//	console.log(arr_id);
//	console.log(arr_qty);
	//if (arr_id!="") arr_id = arr_id.substr(0,arr_id.length-1);
//	alert(arr_id);
	//if (arr_id==="") {
	if (arr_id.length==0) {
		alert('Barang belum diisi!');
		document.getElementById('BARCODE['+id_comp+']').focus();
		return false;
	}
	//cek kelebihan koma
	// console.log(items.length );
	// console.log(arr_id.length );
	 if (items.length >arr_id.length ){
		  alert('Format penulisan salah, jumlah koma melebihi jumlah barang yang diinput, lebih '+(items.length-arr_id.length)); 
		  document.getElementById('BARCODE['+id_comp+']').focus();
		  return false;
	 }
	 
	 
	//cek duplikasi barang
	 var duplicates = [];
						 
			 
		for (i = 0;i < arr_id.length; i++) { 
		  if (duplicates.indexOf(arr_id[i]) === -1 && arr_id.indexOf(arr_id[i], i+1) !== -1) {
	 
			duplicates.push(arr_id[i]);
		  }
		}
		
	 
	 if (duplicates.length >0 ){
		  alert('Ada kode barang yg sama yaitu '+duplicates.toString()); 
		  document.getElementById('BARCODE['+id_comp+']').focus();
		  return false;
	 }
	
	//alert(id_comp); 
	//ga jadi --chan
//	create_table_brg_per_jenis(id_comp+1,id_comp);
	$.ajax({
		url : 'ambilDataBrg.php',
		dataType: 'json',
		data: "list_barcode="+arr_id+"&jenis="+jenis,
		success: function(data) {
			result = JSON.parse(JSON.stringify(data));
			var i=0;
			var sum_qty = 0;
			var sum_subtotal = 0;
			var harga_default = 0;
			var arr_barang_ada = [];
			var arr_barang_tidak_ada = [];
			$.each(result, function(index, val) {
			//	console.log(k);
				// var det_id = "jenis_"+id_comp+'_'+i;
				// var tbl_det = document.getElementById("tbl_jenis_"+id_comp);
				// var row = tbl_det.insertRow(tbl_det.rows.length);
				// row.id = 't_det_'+det_id;
				var qty = parseInt(arr_qty[val.kode_brg]);
				var harga = parseFloat(val.hrg_jual);
				var subtotal = qty * harga;
				harga_default = harga;
				sum_qty += qty;
				sum_subtotal += subtotal;
				arr_barang_ada.push(val.kode_brg);
				// var td0 = document.createElement("td");//kode
				// var td1 = document.createElement("td");//nama
				// var td2 = document.createElement("td");//harga
				// var td3 = document.createElement("td");//qty
				// var td4 = document.createElement("td");//jumlah
				// var td5 = document.createElement("td"); //hapus
				// td0.appendChild(generateKodeBrg(det_id,val.kode_brg)); 
				// td0.appendChild(generateId(det_id));
				// td1.appendChild(generateNama(det_id,val.nm_barang));
				// td2.appendChild(generateHarga(det_id,harga));
				// td3.appendChild(generateQty(det_id,qty));
				// td4.appendChild(generateSUBTOTAL(det_id,subtotal));
				// td5.appendChild(generateDel1(det_id));
				// document.getElementById('BARCODE['+det_id+']').value = val.nm_barang;
				// row.appendChild(td0);
				// row.appendChild(td1);
								//row.appendChild(td1);
				// row.appendChild(td2);
				// row.appendChild(td3);
				// row.appendChild(td4);
				// row.appendChild(td5); 	
				i++;
			});
			
			
				console.log(arr_barang_ada ); 
			//cek barang kalau ada yg tidak ada didatabase
				for (i = 0;i < arr_id.length; i++) { 
					  if (arr_barang_ada.indexOf(arr_id[i]) === -1){ // && arr_id.indexOf(arr_id[i], i+1) !== -1) {
						arr_barang_tidak_ada.push(arr_id[i]);
				 
					  } else {
					  }
					}
					
				console.log(arr_barang_tidak_ada ); 
				 
				 if (arr_barang_tidak_ada.length >0 ){
					  alert('Ada kode barang yag tidak ada ditabase yaitu '+arr_barang_tidak_ada.toString()); 
					  document.getElementById('BARCODE['+id_comp+']').focus();
					  return false;
				 }
			//end cek barang didatabase		
			
			// var Id_Part  = data.id_barang;
			
			// var namabarang = data.nm_barang;	
			// var harga      = data.hrg_jual;	 
			
			 document.getElementById('Qty['+id_comp+']').value = sum_qty;
			// document.getElementById('BARCODE['+a+']').value = Id_Part;
			// document.getElementById('Nama['+a+']').value = namabarang;
			 document.getElementById('Harga['+id_comp+']').value = harga_default;
			 document.getElementById('SUBTOTAL['+id_comp+']').value = sum_subtotal;
			 hitungtotal();
			 
		//document.getElementById('Qty['+a+']').value = 0;	
        }
	});	
	
	
	
}

function addbarcode(a)
{
var ke1 = document.getElementById("BARCODE["+a+"]").value;
	$.ajax({
		url : 'ambilDataBrg.php',
		dataType: 'json',
		data: "barcode="+ke1,
		success: function(data) {
		var Id_Part  = data.id_barang;
		
		var namabarang = data.nm_barang;	
		var harga      = data.hrg_jual;	
		//var harga      = data.hrg_beli;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('Nama['+a+']').value = namabarang;
		document.getElementById('Harga['+a+']').value = harga;
		//document.getElementById('Qty['+a+']').value = 0;	
        }
	});	

//addNewRow1();
document.getElementById('Qty['+a+']').focus();
//hitungjml(a);		
}	

var baris1=1;//utk print
var baris2=1;//utk detil
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

td0.appendChild(generateKategori(baris1));
td1.appendChild(generateBARCODE(baris1));
//td0.appendChild(generateCari1(baris1));
td1.appendChild(generateId(baris1));
//td1.appendChild(generateNama(baris1));
td2.appendChild(generateQty(baris1));
td3.appendChild(generateHarga(baris1));
td4.appendChild(generateSUBTOTAL(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
//row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5); 

document.getElementById('BARCODE['+baris1+']').focus();
//document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'addbarcode('+baris1+')');
document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'barcode_event('+baris1+')');

//harusnya
//oninput="this.value = this.value.replace(/[^a-zA-Z0-9,=]/g, '').replace(/(\..*)\./g, '$1');"  
document.getElementById('BARCODE['+baris1+']').setAttribute('onInput', 'this.value = this.value.replace(/[^a-zA-Z0-9,=]/g, \'\').replace(/(\\..*)\./g, \'$1\');');
//tapi jadi gini knp ya
//oninput="this.value = this.value.replace(/[^a-zA-Z0-9,=]/g, '').replace(/(..*)./g, '$1');


// document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'makeQty(this.value,'+baris1+')');
//document.getElementById('BARCODE['+baris1+']').setAttribute('onBlur', 'makeQty(this.value,'+baris1+')');
//alert($('#BARCODE['+baris1+']').text());
//console.log($('#BARCODE['+baris1+']'));
$('#BARCODE['+baris1+']').bind('change',function(){
	var txt =  $(this).text();
	alert(txt);
	
});

//$('#BARCODE['+baris1+']').trigger('change');
//alert('kadie');
//document.getElementById('Cari1['+baris1+']').setAttribute('onclick', 'popjasa('+baris1+')');
document.getElementById('Harga['+baris1+']').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('Qty['+baris1+']').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('SUBTOTAL['+baris1+']').setAttribute('onEnter', 'addNewRow1()');
document.getElementById('del1['+baris1+']').setAttribute('onclick', 'delRow1('+baris1+')');
baris1++;
}
//input array textarea di qty barang
function makeQty(v,a)
{
var q=0;
var txtArray=v.split(',');
	for(var i=0;i<txtArray.length;i++){
	//alert(txtArray[i]);
	q+=1;
	}
document.getElementById("Qty["+a+"]").value = q;
hitungjml(a);	
}

function popjasa(a){
	
	var width  = 550;
 	var height = 400;
 	var left   = (screen.width  - width)/2;
 	var top    = (screen.height - height)/2;
  	var params = 'width='+width+', height='+height+',scrollbars=yes';
 	params += ', top='+top+', left='+left;
		window.open('popbarang.php?row='+a+'','',params);
};

function generateId(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Id"+index+"";
idx.id = "Id["+index+"]";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateBARCODE(index,val='') {
var idx = document.createElement("textarea");
idx.type = "text";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE["+index+"]";
idx.size = "40";
idx.align = "center";
idx.cols = "50";
idx.rows = "4";
if (val!='')
	idx.value = val;
return idx;
}

function generateKodeBrg(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "kode_brg"+index+"";
idx.id = "kode_brg["+index+"]";
idx.size = "5";
idx.align = "center"; 
if (val!='')
	idx.value = val;
return idx;
}

function generateCari1(index) {
	var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "Cari1";
	idx.value = "...";
	idx.id = "Cari1["+index+"]";
	idx.size = "5";
	//idx.
	return idx;
}

function generateNama(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Nama"+index+"";
idx.id = "Nama["+index+"]";
idx.size = "15";
idx.readOnly = "readonly";
if (val!='')
	idx.value = val;
return idx;
}

function generateQty(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Qty"+index+"";
idx.id = "Qty["+index+"]";
idx.size = "3";
idx.style="text-align:right;";

idx.readOnly = "readonly";

if (val!='')
	idx.value = val;
//idx.readOnly = "readonly";
return idx;
}

function generateHarga(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Harga"+index+"";
idx.id = "Harga["+index+"]";
idx.size = "8";

//idx.readOnly = "readonly";

idx.style="text-align:right;";
if (val!='')
	idx.value = val;
return idx;
}


function generateSUBTOTAL(index,val='') {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SUBTOTAL"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SUBTOTAL["+index+"]";
	idx.align= "right";
	idx.readOnly = "readonly";
	idx.style="text-align:right;";
	idx.size = "15";
	if (val!='')
	idx.value = val;
	return idx;
}

function generateDel1(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "del1"+index+"";
idx.id = "del1["+index+"]";
idx.size = "10";
idx.value = "X";
return idx;
}


function generateKategori(index) {
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

function delRow1(id){ 
	var el = document.getElementById("t1"+id);
	baris1-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal(id);
	return false;
}
function hitungpiutang()
{   
	var total=0;
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
	
    if(document.getElementById("ongkir").value == "") {
          document.getElementById("ongkir").value = 0;
	}
	ongkir=document.getElementById("ongkir").value;
	var ongkir_murni=parseInt(ongkir.replace(".", ""));
	
	if(document.getElementById("byr_deposit").value == "") {
          document.getElementById("byr_deposit").value = 0;
	}
	byr_deposit=document.getElementById("byr_deposit").value;
	var byr_deposit_murni=parseInt(byr_deposit.replace(".", ""));
	
	for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE["+i+"]");
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

function hitungtotal()
{   
	var total=0;
    var ongkir=0;
	var deposit_pelanggan=0;
	
	if(document.getElementById("ongkir").value == "") {
          document.getElementById("ongkir").value = 0;
	}
	ongkir=parseInt(document.getElementById("ongkir").value);
	
	if(document.getElementById("total_deposit").value == "") {
          document.getElementById("total_deposit").value = 0;
	}
	deposit_pelanggan=parseInt(document.getElementById("total_deposit").value);
	
	
	if(document.getElementById("piutang").value == "") {
          document.getElementById("piutang").value = 0;
	}
	piutang=parseInt(document.getElementById("piutang").value);
	
	if(document.getElementById("tunai").value == "") {
          document.getElementById("tunai").value = 0;
	}
	tunai=parseInt(document.getElementById("tunai").value);
	
	if(document.getElementById("transfer").value == "") {
          document.getElementById("transfer").value = 0;
	}
	transfer=parseInt(document.getElementById("transfer").value);
	
	for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE["+i+"]");
		if 	(barcode != null)
	    {   
	    //alert("barcode ="+barcode.toString())
		total+= parseInt(document.getElementById("Qty["+i+"]").value)* parseInt(document.getElementById("Harga["+i+"]").value);
		}
		//else
		//return false;
	}
    document.getElementById("faktur").value = total;	
    document.getElementById("tunai").value = total-deposit_pelanggan;	
    //document.getElementById("total").value = total; 	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	hitungpiutang();
	//document.getElementById("piutang").value = total-(tunai + transfer);	
    //document.getElementById("total").value = convertToRupiah(total);	
}

function hitungjml(a)
{
	if(document.getElementById("Qty["+a+"]").value == null) {
          document.getElementById("Qty["+a+"]").value = 0;
	}
	
	if(document.getElementById("Harga["+a+"]").value == null){
          document.getElementById("Harga["+a+"]").value = 0;
	}
	
	
	var ke1 = document.getElementById("Qty["+a+"]").value;
	var ke2 = document.getElementById("Harga["+a+"]").value;
	var jml=0;
	var total=0;
	
		jml=(ke1*ke2) ;
    
 	document.getElementById("SUBTOTAL["+a+"]").value = jml;	
 	hitungtotal();
}

function hitungrow() 
{
	document.form2.jum.value= baris1;
}

function tutup(){
//window.close();
var win=window.open("","_self");
win.close();
}

function cetak(){
	//	alert(baris1);
		//return false;
    //var namaValid    = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
        //var emailValid   = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
        var nama         = form2.nama.value;
        var totalfaktur  = parseInt(form2.total_hidden.value);
        var tunai        = parseInt(form2.tunai.value);
        var transfer     = parseInt(form2.transfer.value);
        var simpan_deposit = parseInt(form2.simpan_deposit.value);
        var byr_deposit    = parseInt(form2.byr_deposit.value);
        //var email        = form2.email.value;
        var pesan        = '';
        //var temp_total   = tunai + transfer + kartu;
        var temp_total   = tunai + transfer;
		//var simpan_deposit=temp_total-totalfaktur;
		if (nama == '') {
            pesan = 'Nama Customer tidak boleh kosong\n';
        } 
		
		if (totalfaktur < byr_deposit) {
            pesan = 'Nilai Bayar Deposit masih  Melebihi Nilai Total Faktur\n Bayar Deposit=' +byr_deposit+', total='+totalfaktur+'. Silakan anda belanja lagi';
        }
        
		/*
		if (totalfaktur < temp_total) {
            pesan = 'Pembayaran Melebihi Nilai Total Faktur\n temp=' +temp_total+', total='+totalfaktur;
        }
        */
		
		
		/*
        if (nama != '' && !nama.match(namaValid)) {
            pesan += '-nama tidak valid\n';
        }
        
        if (jeniskelamin == '') {
            pesan += '-jenis kelamin harus dipilih\n';
        }
        
        if (email == '') {
            pesan += '-email tidak boleh kosong\n';
        }
        
        if (email !=''  && !email.match(emailValid)) {
            pesan += '-alamat email tidak valid\n';
        }
        */
        
	/*
			TEU JADI biar kategori sama bisa muncul di beberapa row utk handle transaksi tambahan

	if (pesan==""){
			
			var arr_kategori=[];
				for (i=1;i<(baris1);i++){
					arr_kategori[i-1] = document.getElementById("cmb_kategori["+i+"]").value;	
				}
				 var duplicates = [];
						 
						 
					for (i = 0;i < arr_kategori.length; i++) { 
					  if (duplicates.indexOf(arr_kategori[i]) === -1 && arr_kategori.indexOf(arr_kategori[i], i+1) !== -1) {
				 
						duplicates.push(arr_kategori[i]);
					  }
					}
					
				 
				 if (duplicates.length >0 ){
					  pesan += 'Kategori tidak boleh sama \n'; 
				 }
				
				 
				
		}
		
		*/
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
		document.form2.action="jual_simpan.php";
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
		document.form2.action="jual_simpan.php";
		document.form2.submit();
		}
		else
		{
		tutup();
		}
	}
return true;
    
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