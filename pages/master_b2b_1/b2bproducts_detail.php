<head>
<title>ADD B2B PRODUCTS DETAIL</title>
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
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
	
	//autocomplete kategori
	$("#kategori").autocomplete("lookup_kategori.php?", {
		width: 358
	});
	$("#kategori").result(function(event, data, formatted) {
	
	var nama_kg = document.getElementById("kategori").value;
	
	for(var i=0;i<nama_kg.length;i++){
		var id = nama_kg.split(':');
		if (id[0]=="") continue;
		var id_kg=id[0];
	}
		//console.log("here="+id);
		//console.log(id_rg);
		//alert("id_rg="+id_rg);
  	    //document.getElementById("id_address").innerHTML.value = id_rg;
	$.ajax({
		url : 'lookup_kategori_ambil.php?id='+id_kg,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_kategori  = data.id;
			$('#id_kategori').val(id_kategori);
        }
		});
	});
	
  });

</script>
 <?php 
  include("../../include/koneksi.php");
  		

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    
<table width='100%' cellspacing='0' cellpadding='0'>
    
     <tr>
		<td class='fonttext'>Code</td>
		<td><input type='text' class='inputform' name='kode' id='kode' 	placeholder='Code'  />
		<td class='fonttext'>Nama</td>
		<td><textarea name='nama' id='nama' cols='40' rows='3' placeholder='Nama Product' ></textarea></td>
		
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Price</td>
        <td><input type='text' class='inputform' name='price' id='price' placeholder='Harga'  />
		<td class='fonttext'>Size</td>
        <td><input type='text' class='inputform' name='size' id='size' placeholder='Size'  />
		</td>                    
	 </tr>
	 <tr>
	    <td class='fonttext'>Type</td>
        <td><input type='text' class='inputform' name='tipe' id='tipe' placeholder='Type'  />
		</td>
        <td class='fonttext'>Category</td>
        <td><input type='text' class='inputform' name='kategori' id='kategori' placeholder='Autosuggest Category'  />
		<input type='text' name='id_kategori' id='id_kategori'/>
		</td>
				
	 </tr>
	 
	 <tr height='1'>
     <td colspan='4'>Detail Composition<hr/></td>
	 </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='20%' class='fonttext'>Code</td>
    	<td align='center' width='40%' class='fonttext'>Composition</td>
    	<td align='center' width='15%' class='fonttext'>Qty</td>
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
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='2' ></textarea></td></td>
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
   $("#BARCODE"+a+"").autocomplete("lookup_composition.php?", {
	width: 158});
  //console.log('here'+a)  ;
   $("#BARCODE"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("BARCODE"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_cmp=id[0];
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_composition_ambil.php?id='+id_cmp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var composition  = data.nama;
			$("#NamaBrg"+a+"").val(composition);
		var id_composition  = data.id;
			$("#IDP"+a+"").val(id_composition);
			$("#Qty"+a+"").focus();
        }
	});	
			
	});
//document.getElementById('Qty'+baris1+'').focus();	
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

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
//id untuk dimasukin id_product
td2.appendChild(generateQty(baris1));
td3.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);

document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('Next'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('del1'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');

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
idx.size = "25";
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
idx.size = "50";
idx.readOnly = "readonly";
//idx.disabled = "disabled";
return idx;
}
function generateQty(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Qty"+index+"";
idx.id = "Qty"+index+"";
idx.size = "10";
//idx.readOnly = "readonly";
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



function hitungtotal(){
    
	var totalqty=0;
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("Qty"+i+"").value == "") {
		var nett_price = 0;}
		else{
		var nett_price = document.getElementById("Qty"+i+"").value;
		}
	    totalqty+= 1;
	 }
		//else{}
		//return false;
	}
	
    //alert("baris="+baris1.toString());
	
	//totalqty
	//document.getElementById("totalqty").value = totalqty;
	//total belum disc

}

function hitungjml(a)
{
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
    var harga   		= form2.price.value;
	var size            = form2.size.value;
    var tipe            = form2.tipe.value;
    var id_kategori     = form2.id_kategori.value;
    
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	
    if (nama_input == '') {
            pesan = 'Nama Produk tidak boleh kosong\n';
        }
	if (harga == '') {
            pesan = 'Harga tidak boleh kosong\n';
        }
	if (size == '') {
            pesan = 'Size tidak boleh kosong\n';
        }
	
	if (tipe == '') {
            pesan = 'Tipe tidak boleh kosong\n';
        }
		
	if (id_kategori == '') {
            pesan = 'Category tidak boleh kosong\n';
        }
		
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}	
	else
	{ 
		var answer = confirm("Mau Simpan master data produknya????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="b2bproducts_save.php?row="+baris1;
		document.form2.submit();
		}
		else
		{}
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