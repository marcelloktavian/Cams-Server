<head>
<title>EDIT B2B CUSTOMER </title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<script src="../../assets/js/time.js" type="text/javascript"></script>

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
	
  });

</script>
 <?php
error_reporting(0);
 
  include("../../include/koneksi.php");
  $sql_mst="SELECT m.*,a.kecamatan,a.kabupaten,a.provinsi FROM mst_b2bcustomer m LEFT JOIN mst_address a ON m.id_address =a.id WHERE m.id= '".$_GET['ids']."'";
  $sql = mysql_query($sql_mst) or die (mysql_error());
  $rs = mysql_fetch_array($sql);
		$nama= $rs['nama'];
		$npwp= $rs['npwp'];
		$nik= $rs['ktp'];
		$id_address= $rs['id_address'];
		$alamat= $rs['alamat'];
		$no_telp= $rs['no_telp'];
		$hp= $rs['hp'];
		$contact= $rs['contact'];
		$due= $rs['due'];
		$acc_info= $rs['acc_info'];	
		$totalqty= $rs['totalqty'];	
        $kecamatan=$rs['kecamatan'];
        $note=$rs['note'];
        $no_akun=$rs['no_akun'];
?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
	    
    	<td  class='fontjudul'><div id='clock'></div></td>
		<td class='fontjudul'> TOTAL QTY (PRODUCTS ASSIGNED) <input type='text' class='' value='$totalqty' name='totalqty' id='totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    
     <tr>
		<td class='fonttext'>Name</td>
		<td><input type='text' class='inputform' value='$nama' name='nama' id='nama' placeholder='Nama B2B Customer'  />
		<td class='fonttext'>Phone</td>
		<td><input type='text' class='inputform' value='$no_telp' name='telp' id='telp' placeholder='Telp/Fax' /></td>
     </tr>
	 <tr>
		<td class='fonttext'>NPWP</td>
		<td><input type='text' class='inputform' name='npwp' value='$npwp' id='npwp' 	placeholder='NPWP'  />
		<td class='fonttext'>NIK</td>
		<td><input type='text' class='inputform' name='nik' value='$nik' id='nik' 	placeholder='NIK' /></td>
	</tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
	    <td class='fonttext'>Postal Address</td>
        <td><textarea name='alamat' id='alamat' cols='40' rows='3' placeholder='Alamat B2BCustomer (Jalan,No)' >$alamat</textarea></td>
		<td class='fonttext'>Account.Info</td>
        <td><textarea name='acc_info' id='acc_info' cols='40' rows='3' placeholder='Info rekening' >$acc_info</textarea></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Contact Person</td>
        <td><input type='text' class='inputform'  value='$contact' name='contact' id='contact' placeholder='Contact Person'  />
		</td>
        <td class='fonttext'>Handphone</td>
        <td><input type='text' class='inputform'  value='$hp' name='handphone' id='handphone' placeholder='Handphone'  />
		</td>               
				
	 </tr>
	 <tr>
	    <td class='fonttext'>REGION</td>
        <td><input type='text' class='inputform' value='$kecamatan' name='region' id='region' placeholder='Autosuggest Kecamatan'  />
		<input type='hidden' value='$id_address' name='id_address' id='id_address'/>
		</td>
		
		<td class='fonttext'>Due Date</td>
        <td><input type='text' class='inputform' value='$due' name='duedate' id='duedate' placeholder='jatuh tempo'  /></td>
		               
	 </tr>
	 
	 <tr height='1'>
     <td colspan='6'><hr/></td>
	 
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='20%' class='fonttext'>Code</td>
    	<td align='center' width='40%' class='fonttext'>Products</td>
    	<td align='center' width='15%' class='fonttext'>Price@</td>
      	<td align='center' width='10%' class='fonttext'>Disc(%)</td>
      	<td align='center' width='15%' class='fonttext'>Nett Price</td>
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
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='2' >$note</textarea></td></td>
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
   $("#BARCODE"+a+"").autocomplete("lookup_productsgrp.php?", {
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
		url : 'lookup_productsgrp_ambil.php?id='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var products  = data.nama;
			$("#NamaBrg"+a+"").val(products);
		var harga_pd  = data.harga;
			$("#Harga"+a+"").val(harga_pd);
		var id_products  = data.id;
			$("#IDP"+a+"").val(id_products);
			$("#Qty"+a+"").focus();
			
			//var type  = data.type;
			//$('#type').val(type);
        }
	});	
			
	});
document.getElementById('Harga'+baris1+'').focus();	
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

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateIDP(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateDisc(baris1));
td4.appendChild(generateNettPrice(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Harga'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Disc'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('NettPrice'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
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
idx.name = "NamaBrg"+index+"";
idx.id = "NamaBrg"+index+"";
idx.size = "50";
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
//dimatikan karena bisa ubah harga
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

function generateNettPrice(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "NettPrice"+index+"";
	idx.id = "NettPrice"+index+"";
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

function saveID(id) {
var idx = document.createElement("input");
idx.type = "text";
//idx.type = "hidden";
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
    hitungtotal(id);
	return false;
	
}




function hitungtotal(){
    
	var totalqty=0;
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    if(document.getElementById("NettPrice"+i+"").value == "") {
		var nett_price = 0;}
		else{
		var nett_price = document.getElementById("NettPrice"+i+"").value;
		}
	    totalqty+= 1;
	 }
		//else{}
		//return false;
	}
	
    //alert("baris="+baris1.toString());
	
	//totalqty
	document.getElementById("totalqty").value = totalqty;
	//total belum disc

}

function hitungjml(a)
{
	
	if(document.getElementById("Harga"+a+"").value == ""){
    	var harga = 0;
	}
	else
	{
	var harga = document.getElementById("Harga"+a+"").value;
	}
	//disc nya diganti persen disc
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
	//disc nya diganti persen disc
		
		jml=Math.round(harga*((100-disc)*0.01));
        
 	document.getElementById("NettPrice"+a+"").value = jml;	
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
    var alamat   		= form2.alamat.value;
	var phone           = form2.telp.value;
    var contact         = form2.contact.value;
    var id_address      = form2.id_address.value;
    
	
    if (nama_input == '') {
            pesan = 'Nama Penerima tidak boleh kosong\n';
        }
	if (alamat == '') {
            pesan = 'Alamat tidak boleh kosong\n';
        }
	if (phone == '') {
            pesan = 'Phone tidak boleh kosong\n';
        }
	
	if (contact == '') {
            pesan = 'Contact Person tidak boleh kosong\n';
        }
		
	if (id_address == '') {
            pesan = 'Region tidak boleh kosong\n';
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
		document.form2.action="b2bcustomer_save.php?id_trans=<?=$_GET['ids']?>&row="+baris1;
		document.form2.submit();
		}
		else
		{}
    }	
}	

<?php 
	$sql_detail="Select d.* from mst_b2bcustomer_product d inner join mst_b2bcustomer m on d.b2bcustomer_id=m.id left join mst_b2bproducts p on d.products_id=p.id where d.b2bcustomer_id = '".$_GET['ids']."'";
	$sql1 = mysql_query($sql_detail);
	$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			
			
			document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['b2bcustomer_detail_id'];?>';
			document.getElementById('IDP'+<?=$i;?>+'').value = '<?=$rs1['products_id'];?>';
			document.getElementById('BARCODE'+<?=$i;?>+'').value = '<?=$rs1['nama_produk'];?>';
			document.getElementById('NamaBrg'+<?=$i;?>+'').value = '<?=$rs1['nama_produk'];?>';
			document.getElementById('Harga'+<?=$i;?>+'').value = '<?=$rs1['price'];?>';
			document.getElementById('Disc'+<?=$i;?>+'').value = '<?=$rs1['disc'];?>';
			document.getElementById('NettPrice'+<?=$i;?>+'').value = '<?=$rs1['nett_price'];?>';
			
		<?php 
			$i++;
		}
		?>


</script>

</body>