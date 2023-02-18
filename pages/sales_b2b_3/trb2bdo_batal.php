
<?php
include("../../include/koneksi.php");

//inisialisasi input penjualan B2B
$id_trans=$_GET['ids'];
$sql_init="SELECT b.*,s.nama as salesman,c.nama AS customer,a.kecamatan,a.kabupaten,a.provinsi,e.nama AS expedition FROM b2bso b LEFT JOIN mst_b2bsalesman s on b.id_salesman=s.id LEFT JOIN mst_b2bcustomer c ON b.id_customer=c.id LEFT JOIN mst_address a ON b.id_address=a.id LEFT JOIN mst_b2bexpedition e ON b.id_expedition=e.id WHERE b.id_trans ='".$id_trans."' ";
//var_dump($sql_init);die;
$data = mysql_query($sql_init);
$rs = mysql_fetch_array($data);
$id_customer=$rs['id_customer'];
$id_salesman=$rs['id_salesman'];
$totalqty=$rs['totalqty'];
$customer=$rs['customer'];
$ref_kode=$rs['ref_kode'];
$contact=$rs['nama'];
$alamat=$rs['alamat'];
$tanggal=date('d-m-Y',strtotime($rs['tgl_trans']));
$salesman=$rs['salesman'];
$telp=$rs['telp'];
$alamat=$rs['alamat'];	
$kecamatan=$rs['kecamatan'];	
$kabupaten=$rs['kabupaten'];	
$provinsi=$rs['provinsi'];	
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
    background-color:#831618 ;
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
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>SEND B2B SALES $customer
		<input type='hidden' name='id_customer' id='id_customer'/>
		</td>
		
		<td class='fontjudul'> TOTAL QTY ORDER <input type='text' class='' name='totalqty' id='totalqty' value='$totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
		<input type='text' name='totalfaktur' id='totalfaktur'/>
		<input type='text' name='faktur' id='faktur'/>
		
		<td class='fontjudul'> TOTAL QTY KIRIM <input type='text' class='' name='totalkirim' id='totalkirim' ' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Ref.Code</td>
        <td>$ref_kode <input type='text' name='id_trans' id='id_trans' value='$id_trans'/></td>
		
		<td class='fonttext'>Tanggal Order</td>
		<td>$tanggal</td>
     </tr>
	 <tr>
        <td class='fonttext'>SALESMAN</td>
        <td>$salesman
		<input type='text' name='id_salesman' id='id_salesman' value='$id_salesman'/></td>
		</td>
		<td class='fonttext'>Tanggal Kirim</td>
		<td><input type='date' class='inputform' name='tglkirim' id='tglkirim'/></td>
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
        <td>$alamat</td>
		<td class='fonttext'>REGION</td>
        <td>$kecamatan-$kabupaten-$provinsi</td>               
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
      	<td align='center' width='10%' class='fonttext'>Qty Order</td>
      	<td align='center' width='5%' class='fonttext'>Size</td>
      	<td align='center' width='10%' class='fonttext'>Qty Sisa</td>
      	<td align='center' width='10%' class='fonttext'>Qty Kirim</td>
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
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
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

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateQty(baris1));
td4.appendChild(generateSize(baris1));
td5.appendChild(generateSisa(baris1));
td6.appendChild(generateKirim(baris1));
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
document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Kirim'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('SISA'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
//document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
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
idx.size = "10";
idx.style="text-align:right;";
idx.readOnly = "readonly";
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

function generateSisa(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SISA"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SISA"+index+"";
	idx.align= "right";
	idx.readOnly = "readonly";
	idx.style="text-align:right";
	idx.size = "10";
	return idx;
}

function generateKirim(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Kirim"+index+"";
idx.id = "Kirim"+index+"";
idx.size = "10";
idx.style="text-align:right;";
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
    //buat menyimpan id_detail yang didelete
	document.getElementById("myDiv").appendChild(saveID(id));
	document.getElementById('delete1'+id+'').value = document.getElementById('Id'+id+'').value;
	del1++;
 
	var el = document.getElementById("t1"+id);
	//baris1-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    //hitungtotal(id);
    hitungtotal();
	return false;
	
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
		}
	    //alert("subtotal ="+subtotal.toString())
		total+= parseInt(kirim * harga) ;
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
	document.getElementById("totalkirim").value = totalkirim;	
    //document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})
	//hitungpiutang();
}

function hitungjml(a)
{
    //validasi agar customer tidak kosong
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
	
	if(document.getElementById("Kirim"+a+"").value == ""){
    	var kirim = 0;
	}
	else
	{
		var kirim = document.getElementById("Kirim"+a+"").value;
	}
	var sisa=0;
	var total=0;
	//alert("qty ="+qty.toString()+',harga='+harga.toString())	
		
		sisa=qty-kirim;
    
 	document.getElementById("SISA"+a+"").value = sisa;	
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
    var id_expedition   = form2.id_expedition.value;
	
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	
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
$sql_edit = "Select a.*,(a.jumlah_beli-a.jumlah_kirim) as saldo from b2bso_detail a where a.id_trans = '".$_GET['ids']."'";
//var_dump($sql_edit);
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
			document.getElementById('Qty'+<?=$i;?>+'').value = '<?=$rs1['jumlah_beli'];?>';
			document.getElementById('Size'+<?=$i;?>+'').value = '<?=$rs1['size'];?>';
			document.getElementById('SISA'+<?=$i;?>+'').value = '<?=$rs1['saldo'];?>';
			
		<?php 
			$i++;
		}
		?>
	

</script>

</body>