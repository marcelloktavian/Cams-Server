
<?php
include("../../include/koneksi.php");

//inisialisasi input penjualan B2B
$id_trans=$_GET['ids'];
$sql_init="SELECT b.*,s.nama as salesman,c.nama AS customer,e.nama AS expedition,IFNULL(c.due,0) as due FROM b2bso b LEFT JOIN mst_b2bsalesman s on b.id_salesman=s.id LEFT JOIN mst_b2bcustomer c ON b.id_customer=c.id LEFT JOIN mst_b2bexpedition e ON b.id_expedition=e.id WHERE b.id_trans ='".$id_trans."' ";
// var_dump($sql_init);die;
$data = mysql_query($sql_init);
$rs = mysql_fetch_array($data);
$id_customer=$rs['id_customer'];
$id_salesman=$rs['id_salesman'];
$id_address=$rs['id_address'];
$totalqty=$rs['totalqty'];
$customer=$rs['customer'];
$ref_kode=$rs['ref_kode'];
$contact=$rs['nama'];
$alamat=$rs['alamat'];
$tanggal=date('d-m-Y',strtotime($rs['tgl_trans']));
$salesman=$rs['salesman'];
$telp=$rs['telp'];
$alamat=$rs['alamat'];	
$due=$rs['due'];	
// $kecamatan=$rs['kecamatan'];	
// $kabupaten=$rs['kabupaten'];	
// $provinsi=$rs['provinsi'];	
$note=$rs['note'];

$nofaktur='';

$sql_b2b = "SELECT no_faktur FROM b2bdo WHERE id_transb2bso='$id_trans' AND deleted=1 ORDER BY id DESC LIMIT 1";
$data2 = mysql_query($sql_b2b);
$num_rows = mysql_num_rows($data2);
if ($num_rows == 1) {
	$rs2 = mysql_fetch_array($data2);
	$nofaktur=$rs2['no_faktur'];
}
?>
<head>
<title>SEND B2B SALES <?php echo"".$id_trans;?></title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<!--<script src="../../assets/js/time.js" type="text/javascript"></script>-->
<style>
body {
    /*background-color:#E2D65E*/ ;
    background-color:#b2cecf ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
				
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
$tgl = date("d F Y", strtotime($tanggal));

echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
	<td class='fontjudul' colspan='4'>SEND B2B SALES $customer
		<input type='hidden' name='id_customer' id='id_customer' value='$id_customer'/>
		<input type='hidden' name='due' id='due' value='$due'/>
	</td>	
	</tr>
  	<tr>
    	
		<td class='fontjudul'> TOTAL QTY ORDER <input type='text' class='' name='totalqty' id='totalqty' value='$totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;width:70px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
		<td class='fontjudul'> TOTAL QTY KIRIM <input type='text' class='' name='totalkirim' id='totalkirim' style='text-align:right;font-size: 30px;background-color:white;height:40px;width:70px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
		<td class='fontjudul'> TOTAL FAKTUR <input type='text' class='' name='totalfakturmask' id='totalfakturmask'  style='text-align:right;font-size: 30px;background-color:white;height:40px;width:250px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
		<td>		
		<input type='hidden' name='totalfaktur' id='totalfaktur'/>
		<input type='hidden' name='faktur' id='faktur'/>
		</td>
		
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Ref.Code</td>
        <td>$ref_kode <input type='hidden' name='ref_kode' id='ref_kode' value='$ref_kode'> <input type='hidden' name='id_trans' id='id_trans' value='$id_trans'/><input type='hidden' name='nofaktur' id='nofaktur' value='$nofaktur'>($nofaktur)</td>
		
		<td class='fonttext'>Tanggal Order</td>
		<td>$tgl</td>
     </tr>
	 <tr>
        <td class='fonttext'>SALESMAN</td>
        <td>$salesman
		<input type='hidden' name='id_salesman' id='id_salesman' value='$id_salesman'/></td>
		</td>
		<td class='fonttext'>Tanggal Kirim</td>
		<td><input type='date' class='inputform' name='tglkirim' id='tglkirim' onchange='gantitempo()'/></td>
	</tr>
	<tr>
	<td class='fonttext'></td>
	<td>
	</td>
	<td class='fonttext'>Tanggal Jatuh Tempo</td>
	<td><input type='date' class='inputform' name='tgljatuhtempo' id='tgljatuhtempo'/></td>
</tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
     
	 <tr height='1'>
     <td colspan='4'><hr/></td>
     </tr>
     <tr>
		<td class='fonttext'>Contact Person</td>
		<td>$contact</td>
		<td class='fonttext'>Phone</td>
		<td>$telp</td>
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td>$alamat<input type='hidden' name='id_address' id='id_address' value='$id_address'/></td>
		            
	 </tr>
	 <tr height='1'>
     <td colspan='6'><hr/></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Note</td>
        <td colspan='5'>$note</td>
	 </tr>
	 <tr height='1'>
     <td colspan='6'><hr/></td>
	 </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='15%' class='fonttext'>Code</td>
    	<td align='center' width='25%' class='fonttext'>Products</td>
    	<td align='center' width='10%' class='fonttext'>Price@ (inc PPN)/Disc</td>
		<td align='center' width='5%' class='fonttext'>31</td>
		<td align='center' width='5%' class='fonttext'>32</td>
		<td align='center' width='5%' class='fonttext'>33</td>
		<td align='center' width='5%' class='fonttext'>34</td>
		<td align='center' width='5%' class='fonttext'>35</td>
      	<td align='center' width='5%' class='fonttext'>36</td>
      	<td align='center' width='5%' class='fonttext'>37</td>
      	<td align='center' width='5%' class='fonttext'>38</td>
      	<td align='center' width='5%' class='fonttext'>39</td>
      	<td align='center' width='5%' class='fonttext'>40</td>
      	<td align='center' width='5%' class='fonttext'>41</td>
      	<td align='center' width='5%' class='fonttext'>42</td>
      	<td align='center' width='5%' class='fonttext'>43</td>
      	<td align='center' width='5%' class='fonttext'>44</td>
      	<td align='center' width='5%' class='fonttext'>45</td>
      	<td align='center' width='5%' class='fonttext'>46</td>
 		<td align='center' width='10%' class='fonttext'>Qty Order</td>
      	<td align='center' width='10%' class='fonttext'>Qty Kirim</td>
      	<td align='center' width='10%' class='fonttext'>Qty Sisa</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<div id='myDiv'></div>
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
        <td><input type='text' class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' onkeyup='hitungtotal();'/></td>
		<td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='36' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='3' ></textarea></td></td>
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


function gantitempo() {
	var date = new Date(document.getElementById("tglkirim").value);
	date.setDate(date.getDate() + parseInt($('#due').val()));
	var bulan = (date.getMonth()+1);
	var tgl = date.getDate();
	if(tgl<10){
		tgl = '0'+tgl;
	}
	if(bulan<10){
		bulan = '0'+bulan;
	}
	var tgl = date.getFullYear()+'-'+bulan+'-'+tgl;
	$('#tgljatuhtempo').val(tgl);
}

function get_products(a){
    
   var id_customer = document.getElementById("id_customer").value;  
   $("#BARCODE"+a+"").autocomplete("lookup_products.php?id_cust="+id_customer, {
	width: 158});
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
		data: "nama_produk="+formatted,
		success: function(data) {
		//console.log('produk'+data.nama_produk);
		var products  = data.nama_produk;
			$("#NamaBrg"+a+"").val(products);
		var harga_pd  = data.nett_price;
			$("#Harga"+a+"").val(harga_pd);
		var id_products  = data.products_id;
			$("#IDP"+a+"").val(id_products);
		//var product_size  = data.size;
			//$("#Size"+a+"").val(product_size);
		    //$("#Qty"+a+"").val(1);
			$("#Qty"+a+"").focus();
			
			//var type  = data.type;
			//$('#type').val(type);
        }
	});	
			
	});
//document.getElementById('BARCODE'+baris1+'').focus();	
}  
	

var baris1=1;
//addNewRow1();
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
var td9 = document.createElement("td");
var td10 = document.createElement("td");
var td11 = document.createElement("td");
var td12 = document.createElement("td");
var td13 = document.createElement("td");
var td14 = document.createElement("td");
var td15 = document.createElement("td");
var td16 = document.createElement("td");
var td17 = document.createElement("td");
var td18 = document.createElement("td");
var td19 = document.createElement("td");
var td20 = document.createElement("td");
var td21 = document.createElement("td");
var td22 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td2.appendChild(generateDisc(baris1));

td3.appendChild(generateS31id(baris1));
td3.appendChild(generateS31(baris1));
td3.appendChild(generateKirim31(baris1));
td4.appendChild(generateS32id(baris1));
td4.appendChild(generateS32(baris1));
td4.appendChild(generateKirim32(baris1));
td5.appendChild(generateS33id(baris1));
td5.appendChild(generateS33(baris1));
td5.appendChild(generateKirim33(baris1));
td6.appendChild(generateS34id(baris1));
td6.appendChild(generateS34(baris1));
td6.appendChild(generateKirim34(baris1));
td7.appendChild(generateS35id(baris1));
td7.appendChild(generateS35(baris1));
td7.appendChild(generateKirim35(baris1));

td8.appendChild(generateS36id(baris1));
td8.appendChild(generateS36(baris1));
td8.appendChild(generateKirim36(baris1));
td9.appendChild(generateS37id(baris1));
td9.appendChild(generateS37(baris1));
td9.appendChild(generateKirim37(baris1));
td10.appendChild(generateS38id(baris1));
td10.appendChild(generateS38(baris1));
td10.appendChild(generateKirim38(baris1));
td11.appendChild(generateS39id(baris1));
td11.appendChild(generateS39(baris1));
td11.appendChild(generateKirim39(baris1));
td12.appendChild(generateS40id(baris1));
td12.appendChild(generateS40(baris1));
td12.appendChild(generateKirim40(baris1));
td13.appendChild(generateS41id(baris1));
td13.appendChild(generateS41(baris1));
td13.appendChild(generateKirim41(baris1));
td14.appendChild(generateS42id(baris1));
td14.appendChild(generateS42(baris1));
td14.appendChild(generateKirim42(baris1));
td15.appendChild(generateS43id(baris1));
td15.appendChild(generateS43(baris1));
td15.appendChild(generateKirim43(baris1));
td16.appendChild(generateS44id(baris1));
td16.appendChild(generateS44(baris1));
td16.appendChild(generateKirim44(baris1));
td17.appendChild(generateS45id(baris1));
td17.appendChild(generateS45(baris1));
td17.appendChild(generateKirim45(baris1));
td18.appendChild(generateS46id(baris1));
td18.appendChild(generateS46(baris1));
td18.appendChild(generateKirim46(baris1));
td19.appendChild(generateQty(baris1));
td20.appendChild(generateKirim(baris1));
td21.appendChild(generateSisa(baris1));
td21.appendChild(generateSaldo(baris1));
td22.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);
row.appendChild(td7);
row.appendChild(td8);
row.appendChild(td9);
row.appendChild(td10);
row.appendChild(td11);
row.appendChild(td12);
row.appendChild(td13);
row.appendChild(td14);
row.appendChild(td15);
row.appendChild(td16);
row.appendChild(td17);
row.appendChild(td18);
row.appendChild(td19);
row.appendChild(td20);
row.appendChild(td21);
row.appendChild(td22);


document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//size 31 sd 46
document.getElementById('K31_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K32_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K33_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K34_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K35_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K36_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K37_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K38_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K39_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K40_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K41_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K42_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K43_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K44_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K45_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('K46_'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');

document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Kirim'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('SISA'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
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
idx.size = "8";
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
idx.size = "35";
idx.readOnly = "readonly";
//idx.disabled = "disabled";
return idx;
}
function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Harga"+index+"";
idx.id = "Harga"+index+"";
idx.size = "8";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}

function generateDisc(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Disc"+index+"";
idx.id = "Disc"+index+"";
idx.size = "8";
idx.style="text-align:right;background-color: grey;";
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
idx.size = "8";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
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
idx.type = "text";
idx.name = "S31_"+index+"";
idx.id = "S31_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim31(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K31_"+index+"";
idx.id = "K31_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S32_"+index+"";
idx.id = "S32_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim32(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K32_"+index+"";
idx.id = "K32_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S33_"+index+"";
idx.id = "S33_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim33(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K33_"+index+"";
idx.id = "K33_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S34_"+index+"";
idx.id = "S34_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim34(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K34_"+index+"";
idx.id = "K34_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S35_"+index+"";
idx.id = "S35_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim35(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K35_"+index+"";
idx.id = "K35_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S36_"+index+"";
idx.id = "S36_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.color="red";
idx.readOnly = "readonly";
return idx;
}
function generateKirim36(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K36_"+index+"";
idx.id = "K36_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
idx.color="red";
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
idx.type = "text";
idx.name = "S37_"+index+"";
idx.id = "S37_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim37(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K37_"+index+"";
idx.id = "K37_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S38_"+index+"";
idx.id = "S38_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim38(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K38_"+index+"";
idx.id = "K38_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S39_"+index+"";
idx.id = "S39_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}

function generateKirim39(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K39_"+index+"";
idx.id = "K39_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S40_"+index+"";
idx.id = "S40_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim40(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K40_"+index+"";
idx.id = "K40_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S41_"+index+"";
idx.id = "S41_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim41(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K41_"+index+"";
idx.id = "K41_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S42_"+index+"";
idx.id = "S42_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim42(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K42_"+index+"";
idx.id = "K42_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S43_"+index+"";
idx.id = "S43_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim43(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K43_"+index+"";
idx.id = "K43_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S44_"+index+"";
idx.id = "S44_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim44(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K44_"+index+"";
idx.id = "K44_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S45_"+index+"";
idx.id = "S45_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim45(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K45_"+index+"";
idx.id = "K45_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
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
idx.type = "text";
idx.name = "S46_"+index+"";
idx.id = "S46_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}
function generateKirim46(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "K46_"+index+"";
idx.id = "K46_"+index+"";
idx.size = "1";
idx.style="text-align:right;background-color: white;";
//idx.readOnly = "readonly";
return idx;
}
function generateSisa(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SISA"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SISA"+index+"";
	idx.align= "right";
	idx.style="text-align:right;background-color: grey;";
	idx.readOnly = "readonly";
	idx.size = "8";
	return idx;
}


function generateKirim(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Kirim"+index+"";
idx.id = "Kirim"+index+"";
idx.size = "8";
idx.style="text-align:right;background-color: grey;";
idx.readOnly = "readonly";
return idx;
}

function generateSaldo(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.type = "hidden";
    idx.name = "SALDO"+index+"";
	idx.id = "SALDO"+index+"";
	idx.align= "right";
	idx.style="text-align:right;background-color: grey;";
	idx.readOnly = "readonly";
	idx.size = "5";
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

function saveID(id) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "delete1"+id+"";
idx.id = "delete1"+id+"";
idx.type = "text";
return idx;
}

var del1 = 1;
function delRow1(id){
	document.getElementById("K31_"+id).value='0';
	document.getElementById("K32_"+id).value='0';
	document.getElementById("K33_"+id).value='0';
	document.getElementById("K34_"+id).value='0';
	document.getElementById("K35_"+id).value='0';
	document.getElementById("K36_"+id).value='0';
	document.getElementById("K37_"+id).value='0';
	document.getElementById("K38_"+id).value='0';
	document.getElementById("K39_"+id).value='0';
	document.getElementById("K40_"+id).value='0';
	document.getElementById("K41_"+id).value='0';
	document.getElementById("K42_"+id).value='0';
	document.getElementById("K43_"+id).value='0';
	document.getElementById("K44_"+id).value='0';
	document.getElementById("K45_"+id).value='0';
	document.getElementById("K46_"+id).value='0';
	hitungjml(id);
	hitungtotal();
}

function validasi(){
/*
var pesan='';
var id_customer  = form2.id_customer.value;
var tanggal      = form2.tanggal.value;

	if (id_customer == '') {
            pesan = 'Customer tidak boleh kosong\n';
			form2.customer.focus;
    }
	
	if (tanggal == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
			form2.tanggal.focus;
    }
	
if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
       
		return false;
		
	}    
*/
}

function hitungpiutang() 
{   
	var total=0;
	var totalkirim=0;
    var ongkir=0;
    var sisa=0;
	
	if(document.getElementById("exp_fee").value == "") {
          document.getElementById("exp_fee").value = 0;
	}
	ongkir=document.getElementById("exp_fee").value;
	var ongkir_murni=parseInt(ongkir.replace(".", ""));
	
	
	//dihitung ulang untuk mengetahui sisa
	for (var i=1; i<=baris1;i++){
		
		var barcode=document.getElementById("BARCODE"+i+"");
		if 	(barcode != null)
	    {   
	    //alert("barcode ="+barcode.toString())
		/*
		total+= parseInt(document.getElementById("Qty"+i+"").value)* (parseInt(document.getElementById("Harga"+i+"").value)-parseInt(document.getElementById("Disc"+i+"").value));
		*/
			if(document.getElementById("Kirim"+i+"").value == "") {
			var kirim = 0;
			}
			else{
			var harga 	= document.getElementById("Harga"+i+"").value;
			var kirim 	= document.getElementById("Kirim"+i+"").value;
			}
	        total+= parseInt(kirim * harga);
	        totalkirim+= parseInt(kirim);
		}
		//else
		//return false;
	}
	//faktur saja tanpa ongkir dan deposit
    document.getElementById("faktur").value = total;	
    
    //total dengan ongkir 
    total=total + ongkir_murni;	
	
	//totalhidden dipake buat validasi saja
	document.getElementById("totalfaktur").value = total;	   
	document.getElementById("totalkirim").value = totalkirim;	   
	//document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})	
    
}

function hitungtotal(){
    
	var total=0;
	var totalkirim=0;
	if(document.getElementById("exp_fee").value == "") {
       var ongkir=0;;
	}
	else{
	ongkir=parseInt(document.getElementById("exp_fee").value);
	}
	
		
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("Kirim"+i+"").value == "") {
		var kirim = 0;}
		else{
		var kirim    = document.getElementById("Kirim"+i+"").value;
		var harga    = document.getElementById("Harga"+i+"").value;
		var disc     = document.getElementById("Disc"+i+"").value;
		}
	    //alert("subtotal ="+subtotal.toString())
		total+= parseInt(kirim * (harga*(100-disc)*0.01)) ;
		totalkirim+= parseInt(kirim);
	 }
		//else{}
		//return false;
	}
	//faktur saja tanpa ongkir dan deposit
    document.getElementById("faktur").value = total;	
    //totalfaktur ditambah dengan deposit dan ongkir
	//total=total+ongkir;
	total=total+ongkir;
    //alert("TOTAL ="+total.toString())
	
	//totalhidden dipake buat validasi saja
	document.getElementById("totalfaktur").value = total;	
	document.getElementById("totalfakturmask").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	//document.getElementById("totalfakturmask").value = total;	
	document.getElementById("totalkirim").value = totalkirim;	
    //document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	//hitungpiutang();
}

function hitungjml(a)
{
    //validasi agar customer tidak kosong
	validasi();
	
	if((document.getElementById("S31_"+a+"").value == 0)||(document.getElementById("K31_"+a+"").value == "")||(document.getElementById("K31_"+a+"").value == 0)||(parseInt(document.getElementById("K31_"+a+"").value) > parseInt(document.getElementById("S31_"+a+"").value))) {
	var kirim31 = 0;	    
	}	
	else{
	var kirim31 = document.getElementById("K31_"+a+"").value;
	}

	if((document.getElementById("S32_"+a+"").value == 0)||(document.getElementById("K32_"+a+"").value == "")||(document.getElementById("K32_"+a+"").value == 0)||(parseInt(document.getElementById("K32_"+a+"").value) > parseInt(document.getElementById("S32_"+a+"").value))) {
	var kirim32 = 0;	    
	}	
	else{
	var kirim32 = document.getElementById("K32_"+a+"").value;
	}

	if((document.getElementById("S33_"+a+"").value == 0)||(document.getElementById("K33_"+a+"").value == "")||(document.getElementById("K33_"+a+"").value == 0)||(parseInt(document.getElementById("K33_"+a+"").value) > parseInt(document.getElementById("S33_"+a+"").value))) {
	var kirim33 = 0;	    
	}	
	else{
	var kirim33 = document.getElementById("K33_"+a+"").value;
	}

	if((document.getElementById("S34_"+a+"").value == 0)||(document.getElementById("K34_"+a+"").value == "")||(document.getElementById("K34_"+a+"").value == 0)||(parseInt(document.getElementById("K34_"+a+"").value) > parseInt(document.getElementById("S34_"+a+"").value))) {
	var kirim34 = 0;	    
	}	
	else{
	var kirim34 = document.getElementById("K34_"+a+"").value;
	}

	if((document.getElementById("S35_"+a+"").value == 0)||(document.getElementById("K35_"+a+"").value == "")||(document.getElementById("K35_"+a+"").value == 0)||(parseInt(document.getElementById("K35_"+a+"").value) > parseInt(document.getElementById("S35_"+a+"").value))) {
	var kirim35 = 0;	    
	}	
	else{
	var kirim35 = document.getElementById("K35_"+a+"").value;
	}
	
	if((document.getElementById("S36_"+a+"").value == 0)||(document.getElementById("K36_"+a+"").value == "")||(document.getElementById("K36_"+a+"").value == 0)||(parseInt(document.getElementById("K36_"+a+"").value) > parseInt(document.getElementById("S36_"+a+"").value))) {
	var kirim36 = 0;	    
	}	
	else{
	var kirim36 = document.getElementById("K36_"+a+"").value;
	}
	
	if((document.getElementById("S37_"+a+"").value == 0)||(document.getElementById("K37_"+a+"").value == "")||(document.getElementById("K37_"+a+"").value == 0)||(parseInt(document.getElementById("K37_"+a+"").value) > parseInt(document.getElementById("S37_"+a+"").value))) {
	var kirim37 = 0;	    
	}
	else{
	var kirim37 = document.getElementById("K37_"+a+"").value;
	}
	
	if((document.getElementById("S38_"+a+"").value == 0)||(document.getElementById("K38_"+a+"").value == "")||(document.getElementById("K38_"+a+"").value == "")||(parseInt(document.getElementById("K38_"+a+"")).value > parseInt(document.getElementById("S38_"+a+"").value))) {
		var kirim38 = 0;	    
	}
	else{
	var kirim38 = document.getElementById("K38_"+a+"").value;
	}
	
	if((document.getElementById("S39_"+a+"").value == 0)||(document.getElementById("K39_"+a+"").value == "")||(document.getElementById("K39_"+a+"").value == 0)||(parseInt(document.getElementById("K39_"+a+"").value) > parseInt(document.getElementById("S39_"+a+"").value)))
	{
		var kirim39 = 0;	    
	}
	else{
	var kirim39 = document.getElementById("K39_"+a+"").value;
	}
	
	if((document.getElementById("S40_"+a+"").value == 0)||(document.getElementById("K40_"+a+"").value == "")||(document.getElementById("K40_"+a+"").value == 0)||(parseInt(document.getElementById("K40_"+a+"").value) > parseInt(document.getElementById("S40_"+a+"").value))) 
	{
		var kirim40 = 0;	    
	}
	else{
	var kirim40 = document.getElementById("K40_"+a+"").value;
	}
	
	if((document.getElementById("S41_"+a+"").value == 0)||(document.getElementById("K41_"+a+"").value == "")||(document.getElementById("K41_"+a+"").value == 0)||(parseInt(document.getElementById("K41_"+a+"").value) > document.getElementById("S41_"+a+"").value)) 
	{
		var kirim41 = 0;	    
	}
	else{
	var kirim41 = document.getElementById("K41_"+a+"").value;
	}
	
	if((document.getElementById("S42_"+a+"").value == 0)||(document.getElementById("K42_"+a+"").value == "")||(document.getElementById("K42_"+a+"").value == 0)||(parseInt(document.getElementById("K42_"+a+"").value) > parseInt(document.getElementById("S42_"+a+"").value))) {
		var kirim42 = 0;	    
	}
	else{
	var kirim42 = document.getElementById("K42_"+a+"").value;
	}
		
	if((document.getElementById("S43_"+a+"").value == 0)||(document.getElementById("K43_"+a+"").value == "")||(document.getElementById("K43_"+a+"").value == 0)||(parseInt(document.getElementById("K43_"+a+"").value) > parseInt(document.getElementById("S43_"+a+"").value))) {
		var kirim43 = 0;	    
	}
	else{
	var kirim43 = document.getElementById("K43_"+a+"").value;
	}
	
		
	if((document.getElementById("S44_"+a+"").value == 0)||(document.getElementById("K44_"+a+"").value == "")||(document.getElementById("K44_"+a+"").value == 0)||(parseInt(document.getElementById("K44_"+a+"").value) > parseInt(document.getElementById("S44_"+a+"").value))) {
		var kirim44 = 0;	    
	}
	else{
	var kirim44 = document.getElementById("K44_"+a+"").value;
	}
	
	if((document.getElementById("S45_"+a+"").value == 0)||(document.getElementById("K45_"+a+"").value == "")||(document.getElementById("K45_"+a+"").value == 0)||(parseInt(document.getElementById("K45_"+a+"").value) > parseInt(document.getElementById("S45_"+a+"").value))) {
		var kirim45 = 0;	    
	}
	else{
	var kirim45 = document.getElementById("K45_"+a+"").value;
	}
	
	if((document.getElementById("S46_"+a+"").value == 0)||(document.getElementById("K46_"+a+"").value == "")||(document.getElementById("K46_"+a+"").value == 0)||(parseInt(document.getElementById("K46_"+a+"").value) > parseInt(document.getElementById("S46_"+a+"").value))) {
		var kirim46 = 0;	    
	}
	else{
	var kirim46 = document.getElementById("K46_"+a+"").value;
	}

	if(document.getElementById("Qty"+a+"").value == "") {
		var qty = 0;	    
	}
	else{
	var qty = document.getElementById("Qty"+a+"").value;
	}
	
	if(document.getElementById("SISA"+a+"").value == ""){
    	var sisa = 0;
	}
	else
	{
	var sisa = document.getElementById("SISA"+a+"").value;
	}
	
	if(document.getElementById("SALDO"+a+"").value == ""){
    	var saldo = 0;
	}
	else
	{
		var saldo = document.getElementById("SALDO"+a+"").value;
	}
	
	var kirim=0
	//var sisa=0
	var total=0;
	
	kirim=parseInt(kirim31)+parseInt(kirim32)+parseInt(kirim33)+parseInt(kirim34)+parseInt(kirim35)+parseInt(kirim36)+parseInt(kirim37)+parseInt(kirim38)+parseInt(kirim39)+parseInt(kirim40)+parseInt(kirim41)+parseInt(kirim42)+parseInt(kirim43)+parseInt(kirim44)+parseInt(kirim45)+parseInt(kirim46);
	
	//memasukan kembali nilai qty nya agar yang id_nya kosong diinput nilai nol
	document.getElementById("K31_"+a+"").value= kirim31;
	document.getElementById("K32_"+a+"").value= kirim32;
	document.getElementById("K33_"+a+"").value= kirim33;
	document.getElementById("K34_"+a+"").value= kirim34;
	document.getElementById("K35_"+a+"").value= kirim35;
	document.getElementById("K36_"+a+"").value= kirim36;
	document.getElementById("K37_"+a+"").value= kirim37;
	document.getElementById("K38_"+a+"").value= kirim38;
	document.getElementById("K39_"+a+"").value= kirim39;
	document.getElementById("K40_"+a+"").value= kirim40;
	document.getElementById("K41_"+a+"").value= kirim41;
	document.getElementById("K42_"+a+"").value= kirim42;
	document.getElementById("K43_"+a+"").value= kirim43;
	document.getElementById("K44_"+a+"").value= kirim44;
	document.getElementById("K45_"+a+"").value= kirim45;
    document.getElementById("K46_"+a+"").value= kirim46;
	
	//document.getElementById("Kirim"+a+"").value = kirim;	
 		
	sisa=saldo-kirim;    
 	//alert("kirim ="+kirim.toString()+',sisa='+sisa.toString())	
	
	document.getElementById("SISA"+a+"").value = sisa;	
	document.getElementById("Kirim"+a+"").value = kirim;	
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
    var tglkirim        = form2.tglkirim.value;
    var tgljatuhtempo   = form2.tgljatuhtempo.value;
    var id_expedition   = form2.id_expedition.value;
    var totalkirim   	= form2.totalkirim.value;
	
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	if (totalkirim == '') {
            pesan = 'Total tidak boleh kosong\n';
        }  
	if (tgljatuhtempo == '') {
            pesan = 'Tanggal jatuh tempo tidak boleh kosong\n';
        }
    if (tglkirim == '') {
            pesan = 'Tanggal Kirim tidak boleh kosong\n';
        }
	
	if (id_expedition == '') {
            pesan = 'Ekspedisi tidak boleh kosong\n';
        }
	
		
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else
	{ 
		var answer = confirm("Mau Simpan data dan pengiriman barang????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="trb2bso_confirmed_save.php?id_trans=<?=$_GET['ids']?>&baris="+baris1;
		document.form2.submit();
		}
		else
		{
		tutup();
		}
    }	
}	
	
<?php 
$sql_edit = "Select a.*,(a.jumlah_beli-a.jumlah_kirim) as saldo,(a.qty31-a.kirim31) as saldo31,(a.qty32-a.kirim32) as saldo32,(a.qty33-a.kirim33) as saldo33,(a.qty34-a.kirim34) as saldo34,(a.qty35-a.kirim35) as saldo35,(a.qty36-a.kirim36) as saldo36,(a.qty37-a.kirim37) as saldo37,(a.qty38-a.kirim38) as saldo38,(a.qty39-a.kirim39) as saldo39,(a.qty40-a.kirim40) as saldo40,(a.qty41-a.kirim41) as saldo41,(a.qty42-a.kirim42) as saldo42,(a.qty43-a.kirim43) as saldo43,(a.qty44-a.kirim44) as saldo44,(a.qty45-a.kirim45) as saldo45, (a.qty46-a.kirim46) as saldo46 from b2bso_detail a where (a.id_trans = '".$_GET['ids']."') and ((a.jumlah_beli-a.jumlah_kirim) > 0)";
//var_dump($sql_edit);die;
$sql1 = mysql_query($sql_edit);
$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['b2bso_id'];?>';
			document.getElementById('BARCODE'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('IDP'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('NamaBrg'+<?=$i;?>+'').value = '<?=$rs1['namabrg'];?>';
			document.getElementById('Harga'+<?=$i;?>+'').value = '<?=$rs1['harga_satuan'];?>';
			document.getElementById('Disc'+<?=$i;?>+'').value = '<?=$rs1['disc'];?>';

			document.getElementById('S31id'+<?=$i;?>+'').value = '<?=$rs1['id31'];?>';
			document.getElementById('S31_'+<?=$i;?>+'').value = '<?=$rs1['saldo31'];?>';
			document.getElementById('K31_'+<?=$i;?>+'').value = '<?=$rs1['saldo31'];?>';

			document.getElementById('S32id'+<?=$i;?>+'').value = '<?=$rs1['id32'];?>';
			document.getElementById('S32_'+<?=$i;?>+'').value = '<?=$rs1['saldo32'];?>';
			document.getElementById('K32_'+<?=$i;?>+'').value = '<?=$rs1['saldo32'];?>';

			document.getElementById('S33id'+<?=$i;?>+'').value = '<?=$rs1['id33'];?>';
			document.getElementById('S33_'+<?=$i;?>+'').value = '<?=$rs1['saldo33'];?>';
			document.getElementById('K33_'+<?=$i;?>+'').value = '<?=$rs1['saldo33'];?>';

			document.getElementById('S34id'+<?=$i;?>+'').value = '<?=$rs1['id34'];?>';
			document.getElementById('S34_'+<?=$i;?>+'').value = '<?=$rs1['saldo34'];?>';
			document.getElementById('K34_'+<?=$i;?>+'').value = '<?=$rs1['saldo34'];?>';

			document.getElementById('S35id'+<?=$i;?>+'').value = '<?=$rs1['id35'];?>';
			document.getElementById('S35_'+<?=$i;?>+'').value = '<?=$rs1['saldo35'];?>';
			document.getElementById('K35_'+<?=$i;?>+'').value = '<?=$rs1['saldo35'];?>';

			document.getElementById('S36id'+<?=$i;?>+'').value = '<?=$rs1['id36'];?>';
			document.getElementById('S36_'+<?=$i;?>+'').value = '<?=$rs1['saldo36'];?>';
			document.getElementById('K36_'+<?=$i;?>+'').value = '<?=$rs1['saldo36'];?>';

			document.getElementById('S37id'+<?=$i;?>+'').value = '<?=$rs1['id37'];?>';
			document.getElementById('S37_'+<?=$i;?>+'').value = '<?=$rs1['saldo37'];?>';
			document.getElementById('K37_'+<?=$i;?>+'').value = '<?=$rs1['saldo37'];?>';

			document.getElementById('S38id'+<?=$i;?>+'').value = '<?=$rs1['id38'];?>';
			document.getElementById('S38_'+<?=$i;?>+'').value = '<?=$rs1['saldo38'];?>';
			document.getElementById('K38_'+<?=$i;?>+'').value = '<?=$rs1['saldo38'];?>';

			document.getElementById('S39id'+<?=$i;?>+'').value = '<?=$rs1['id39'];?>';
			document.getElementById('S39_'+<?=$i;?>+'').value = '<?=$rs1['saldo39'];?>';
			document.getElementById('K39_'+<?=$i;?>+'').value = '<?=$rs1['saldo39'];?>';

			document.getElementById('S40id'+<?=$i;?>+'').value = '<?=$rs1['id40'];?>';
			document.getElementById('S40_'+<?=$i;?>+'').value = '<?=$rs1['saldo40'];?>';
			document.getElementById('K40_'+<?=$i;?>+'').value = '<?=$rs1['saldo40'];?>';

			document.getElementById('S41id'+<?=$i;?>+'').value = '<?=$rs1['id41'];?>';
			document.getElementById('S41_'+<?=$i;?>+'').value = '<?=$rs1['saldo41'];?>';
			document.getElementById('K41_'+<?=$i;?>+'').value = '<?=$rs1['saldo41'];?>';

			document.getElementById('S42id'+<?=$i;?>+'').value = '<?=$rs1['id42'];?>';
			document.getElementById('S42_'+<?=$i;?>+'').value = '<?=$rs1['saldo42'];?>';
			document.getElementById('K42_'+<?=$i;?>+'').value = '<?=$rs1['saldo42'];?>';

			document.getElementById('S43id'+<?=$i;?>+'').value = '<?=$rs1['id43'];?>';
			document.getElementById('S43_'+<?=$i;?>+'').value = '<?=$rs1['saldo43'];?>';
			document.getElementById('K43_'+<?=$i;?>+'').value = '<?=$rs1['saldo43'];?>';

			document.getElementById('S44id'+<?=$i;?>+'').value = '<?=$rs1['id44'];?>';
			document.getElementById('S44_'+<?=$i;?>+'').value = '<?=$rs1['saldo44'];?>';
			document.getElementById('K44_'+<?=$i;?>+'').value = '<?=$rs1['saldo44'];?>';

			document.getElementById('S45id'+<?=$i;?>+'').value = '<?=$rs1['id45'];?>';
			document.getElementById('S45_'+<?=$i;?>+'').value = '<?=$rs1['saldo45'];?>';
			document.getElementById('K45_'+<?=$i;?>+'').value = '<?=$rs1['saldo45'];?>';

			document.getElementById('S46id'+<?=$i;?>+'').value = '<?=$rs1['id46'];?>';
			document.getElementById('S46_'+<?=$i;?>+'').value = '<?=$rs1['saldo46'];?>';
			document.getElementById('K46_'+<?=$i;?>+'').value = '<?=$rs1['saldo46'];?>';

			document.getElementById('Qty'+<?=$i;?>+'').value = '<?=$rs1['jumlah_beli'];?>';
			document.getElementById('SALDO'+<?=$i;?>+'').value = '<?=$rs1['saldo'];?>';
			hitungjml(''+<?=$i;?>+'')
		<?php 
			$i++;
		}
		?>	

</script>

</body>