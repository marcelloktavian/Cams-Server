<head>
<title>ONLINE SALES RETURN</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<!--
<script src="../../assets/js/time.js" type="text/javascript"></script>
-->
<style>
body {
    background-color:Moccasin ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<?php
  // master_data 
  include("../../include/koneksi.php");
  $sql_mst="SELECT so.*,DATE_FORMAT(so.tgl_trans, '%d/%m/%Y') as tgl,d.nama as dropshipper,a.kecamatan,e.nama as expedition FROM `olnso` so left join mst_dropshipper d on so.id_dropshipper=d.id left join mst_address a on so.id_address=a.id left join mst_expedition e on so.id_expedition=e.id WHERE so.id= '".$_GET['ids']."'";
  $sql = mysql_query($sql_mst)or die (mysql_error());
  $rs = mysql_fetch_array($sql);
  $id_trans    	= $rs['id_trans'];
  $ref_kode    	= $rs['ref_kode'];
  $discount    	= $rs['discount'];
  $tanggal     	= $rs['tgl'];
  $dropshipper 	= $rs['dropshipper'];
  $id_dropshipper 	= $rs['id_dropshipper'];
  $kecamatan 	= $rs['kecamatan'];
  $id_address 	= $rs['id_address'];
  $nama  		= $rs['nama'];
  $alamat 		= $rs['alamat'];
  $telp 		= $rs['telp'];
  $total 		= $rs['total'];
  $totalqty 	= $rs['totalqty'];
  $expedition 	= $rs['expedition'];
  $id_expedition 	= $rs['id_expedition'];
  $exp_code 		= $rs['exp_code'];
  $exp_fee 			= $rs['exp_fee'];
  $exp_note			= $rs['exp_fee'];
  $exp_info			= $expedition.',code='.$exp_code.',fee='.$exp_fee.',note='.$exp_note;
  $discount_faktur 	= $rs['discount_faktur'];
  $note 		= $rs['note'];
  $total 		= $rs['total'];
  					

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>RETURN $id_trans</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='totalhidden'  id='totalhidden' value='$total' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> TOTAL ORDER 	QTY <input type='text' class='' name='totalqty' id='totalqty' value='$totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> NILAI RETURN <input type='text' class='' name='totalreturn'  id='totalreturn' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> QTY RETURN <input type='text' class='' name='totalqtyreturn' id='totalqtyreturn' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<td class='fontjudul'> PENALTY <input type='text' class='' name='totalpenalty' id='totalpenalty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- Hidden krn tidak diacc sama Enrico-->
		<input type='hidden' name='totalreturnhidden' id='totalreturnhidden'/>
		<input type='hidden' name='total' id='total' value='$total'/>
		<input type='hidden' name='totalpenaltyhidden' id='totalpenaltyhidden'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
		<input type='hidden' class='inputform' name='id_oln' id='id_oln' value='$id_trans'/>
		
		<td class='fonttext'>Ref.Code</td>
        <td>$ref_kode<input type='hidden'  id='ref_code' name='ref_code' value='$ref_kode' placeholder='ID WEBSITE'  ></td>
		<td class='fonttext'>DROPSHIPPER($discount)</td>
        <td>$dropshipper<input type='hidden' class='inputform' name='dropshipper' id='dropshipper' placeholder='Autosuggest Dropshipper'  value='$dropshipper'/>
		<input type='hidden' name='id_dropshipper' id='id_dropshipper' value='$id_dropshipper'/>
		<input type='hidden' name='disc_dropshipper' id='disc_dropshipper' value='$discount'/>
		</td>
     </tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    
	    <td class='fonttext'>Tgl.Online</td>
        <td>$tanggal</td>
		<!-- dimatikan karena tanggal jadi otomatis		
		<td><input type='date'  id='tanggal' name='tanggal' class='datepicker'></td>
        <td><div id='clock'></div></td>
		-->
		
		<td class='fonttext'>Tgl.Retur</td>
        <td><input type='date' class='inputform' name='tanggal' id='tanggal' placeholder='tanggal retur' />
		</td>      
   
	 </tr>
	 <tr height='1'>
     <td colspan='4'><hr/></td>
     </tr>
     <tr>
		<td class='fonttext'>Name</td>
		<td>$nama<input type='hidden' class='inputform' name='nama' id='nama' 	placeholder='Nama Penerima'  value='$nama'/>
		<td class='fonttext'>Phone</td>
		<td>$telp<input type='hidden' class='inputform' name='telp' id='telp' 	placeholder='Telp' value='$telp'/></td>
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td>$alamat <input type='hidden' class='inputform' name='alamat' id='alamat' 	placeholder='Telp' value='$alamat'/>
		</td>
		<td class='fonttext'>REGION</td>
        <td>$kecamatan<input type='hidden' class='inputform' name='region' id='region' placeholder='Autosuggest Kecamatan' value='$kecamatan' />
		<input type='hidden' name='id_address' id='id_address' value='$id_address'/>
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
    	<td align='center' width='10%' class='fonttext'>Price@ (inc PPN)</td>
      	<td align='center' width='5%' class='fonttext'>Qty</td>
      	<td align='center' width='10%' class='fonttext'>Pinalty@</td>
      	<td align='center' width='5%' class='fonttext'>Return</td>
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
Expedition Info
</td>
<td>$exp_info
<input type='hidden' name='id_expedition' id='id_expedition' value='$id_expedition'/>
<input type='hidden' name='exp_code' id='exp_code' value='$exp_code'/>
<input type='hidden' name='exp_fee' id='exp_fee' value='$exp_fee'/>
<input type='hidden' name='exp_note' id='exp_note' value='$exp_note'/>
</td>
</tr>
   
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan Retur
</td>
<td colspan=1 align='left'><textarea name='txtbrg' id='txtbrg' cols='55' rows='2' >$note</textarea></td>
<td class='fonttext'>Disc.Faktur </td>
<td><input type='text' class='inputform' name='disc_faktur' id='disc_faktur' style='text-align:right;' onkeyup='hitungtotal();'></td>
</tr>
<tr>
<td class='fonttext'>Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungtotal();'><input type='text' class='inputform' name='faktur' id='faktur' /></td>
<td class='fonttext' >Tf.Bank</td>
<!-- semua pembayaran baik tf maupun piutang dianggap udah byr via tf,maka tf diisi faktur -->
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;' onkeyup='hitungtotal();'></td>
<td class='fonttext' >&nbsp;</td>
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
	var valuenya = parseInt(document.getElementById("totalhidden").value);
	document.getElementById("totalhidden").value = valuenya.toLocaleString('IND', {style: 'currency', currency: 'IDR'});

//autocomplete pada grid
function get_products(a){  
   $("#BARCODE"+a+"").autocomplete("lookup_products.php?", {
	width: 178});
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
//addNewRow1();
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
td2.appendChild(generateNettPrice(baris1));
td3.appendChild(generateQty(baris1));
td3.appendChild(generateSize(baris1));
td4.appendChild(generatePinalty(baris1));
td5.appendChild(generateReturn(baris1));
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
document.getElementById('Return'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Pinalty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('SUBTOTAL'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
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
idx.style="text-align:center; background-color: #D3D3D3;";
idx.align = "center";
idx.readOnly = "readonly";
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
idx.style="text-align:left; background-color: #D3D3D3;";
idx.readOnly = "readonly";
//idx.disabled = "disabled";
return idx;
}
function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "hidden";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Harga"+index+"";
idx.id = "Harga"+index+"";
idx.size = "8";
idx.style="text-align:right; background-color: #D3D3D3;";
idx.readOnly = "readonly";
return idx;
}

function generateNettPrice(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "NettPrice"+index+"";
idx.id = "NettPrice"+index+"";
idx.size = "8";
idx.style="text-align:right; background-color: #D3D3D3;";
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
idx.style="text-align:right; background-color: #D3D3D3;";
idx.readOnly = "readonly";
return idx;
}

function generateSize(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Size"+index+"";
idx.id = "Size"+index+"";
idx.size = "3";
idx.style="text-align:right; background-color: #D3D3D3;";
idx.readOnly = "readonly";
return idx;
}
function generatePinalty(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Pinalty"+index+"";
idx.id = "Pinalty"+index+"";
idx.size = "8";
idx.style="text-align:right;";
//idx.style="text-align:right; background-color: #D3D3D3;";
//idx.readOnly = "readonly";
return idx;
}

function generateReturn(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Return"+index+"";
idx.id = "Return"+index+"";
idx.size = "3";
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
	idx.style="text-align:right; background-color: #D3D3D3;";
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
/*
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
*/	
}


function hitungtotal(){
    
	var total=0;
	var totalqty=0;
	var totalqtyreturn=0;
	var totalpenalty=0;
	var totalreturn=0;
	var sisa_tf=0;
	var discfaktur=0;
    
	
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
		var qty = document.getElementById("Qty"+i+"").value;
		}
	    
		if(document.getElementById("Return"+i+"").value == "") {
		var qtyreturn = 0;}
		else{
		var qtyreturn = document.getElementById("Return"+i+"").value;
		}
		
		if(document.getElementById("Pinalty"+i+"").value == "") {
		var penalty = 0;}
		else{
		var penalty = document.getElementById("Pinalty"+i+"").value;
		}

		//alert("subtotal ="+subtotal.toString())
		totalreturn+= Math.round(parseFloat(subtotal));
		totalqty+= parseFloat(qty);
		totalqtyreturn+= parseFloat(qtyreturn);
		totalpenalty+= Math.round(parseFloat(penalty)*parseFloat(qtyreturn));
		
	 }
		//else{}
		//return false;
	}
	
    //alert("totalqtyreturn="+totalqtyreturn.toString()+"disc_faktur"+discfaktur_murni.toString());
	
	
	document.getElementById("faktur").value = totalreturn;	
    
	//totalreturn dikurangi disc_faktur
	totalreturn=totalreturn- discfaktur_murni;
    document.getElementById("tunai").value = 0;	
    //totalhidden dipake buat validasi saja
	document.getElementById("totalreturnhidden").value = totalreturn;	
	document.getElementById("totalreturn").value = totalreturn.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	//totalqty
	document.getElementById("totalqtyreturn").value = totalqtyreturn;

	document.getElementById("totalpenaltyhidden").value = totalpenalty;	
	document.getElementById("totalpenalty").value = totalpenalty.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
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
	
	if(document.getElementById("NettPrice"+a+"").value == ""){
    	var nett_price = 0;
	}
	else
	{
	var nett_price = document.getElementById("NettPrice"+a+"").value;
	}
	
	if(document.getElementById("Pinalty"+a+"").value == ""){
    	var pinalty = 0;
	}
	else
	{
		var pinalty = document.getElementById("Pinalty"+a+"").value;
	}
	
	if(document.getElementById("Return"+a+"").value == ""){
    	var qtyreturn = 0;
	}
	else
	{
		var qtyreturn = document.getElementById("Return"+a+"").value;
	}
	var jml=0;
	var total=0;
	//validasi default agar qty return tdk bole lebih besar dari qty barang
	if (qtyreturn > qty)
	{ 
	  alert("Retur="+qtyreturn.toString()+", tidak boleh lebih besar dari qty ="+qty.toString());	
	  
	  qtyreturn=qty;
	  var subtotalreturn= Math.round(parseFloat(qtyreturn) * (parseFloat(nett_price)-parseFloat(pinalty)));
	  document.getElementById("Return"+a+"").value = qty;	
 	}
	else
	{
	  var subtotalreturn= Math.round(parseFloat(qtyreturn) * (parseFloat(nett_price)-parseFloat(pinalty)));
	}
	
	//alert("qtyreturn ="+qtyreturn.toString()+",harga="+harga.toString()+",disc="+disc.toString()+" sutotalreturn="+subtotalreturn.toString());	
		
		//jml=qty*(harga-disc);
		
 	//document.getElementById("SUBTOTAL"+a+"").value = jml;	
 	document.getElementById("SUBTOTAL"+a+"").value = subtotalreturn;	
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
    var total             = form2.total.value;
    var penalty             = form2.totalpenaltyhidden.value;
    var totalreturn = form2.totalreturnhidden.value; 
    
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	if(totalreturn < 0){
		pesan = 'Cek Ulang Total Penalty\n';
	}
    if (tgl == '') {
            pesan = 'Tanggal Retur tidak boleh kosong\n';
        }
		
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
		document.form2.action="trolnso_save_return.php";
		document.form2.submit();
		}
		else
		{}
    /* } */
    }	
}	


<?php 
	$sql_detail="SELECT sod.*,so.discount,((1-so.discount)*sod.harga_satuan) as nett_price from olnsodetail sod left join olnso so on sod.id_trans=so.id_trans WHERE  sod.id_trans ='".$id_trans."' ORDER BY sod.namabrg ASC";
	//var_dump($sql_detail);die;
	$sql1 = mysql_query($sql_detail);
	$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['id_so_d'];?>';
			document.getElementById('BARCODE'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('IDP'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('NamaBrg'+<?=$i;?>+'').value = "<?=$rs1['namabrg'];?>";
			document.getElementById('Harga'+<?=$i;?>+'').value = '<?=$rs1['harga_satuan'];?>';
			document.getElementById('Qty'+<?=$i;?>+'').value = '<?=$rs1['jumlah_beli'];?>';
			document.getElementById('Size'+<?=$i;?>+'').value = '<?=$rs1['size'];?>';
			document.getElementById('NettPrice'+<?=$i;?>+'').value = '<?=$rs1['nett_price'];?>';
			//document.getElementById('SUBTOTAL'+<?=$i;?>+'').value = '<?=$rs1['subtotal'];?>';
			
		<?php 
			$i++;
		}
		?>
       
	


</script>

</body>