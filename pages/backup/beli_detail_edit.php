<head>
<title>Kasir Beli</title>
<link rel="stylesheet" type="text/css" href="../assets/css/styles.css" />

<link rel="stylesheet" type="text/css" href="../assets/css/jquery.autocomplete.css" />
<!--
<script src="../assets/js/jsbilangan.js" type="text/javascript"></script>
<script src="../assets/js/time.js" type="text/javascript"></script>
-->
<script type="text/javascript" src="../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../assets/js/jquery.autocomplete.js'></script>
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
error_reporting(0);
session_start();
?>

<script language="javascript">
$().ready(function() {	
		$("#nama").autocomplete("data_supplier.php", {
		width: 158
  });
  
   $("#nama").result(function(event, data, formatted) {
	var nama = document.getElementById("nama").value;
	$.ajax({
		url : 'ambilDataSupplier.php?nama='+nama,
		//url : 'ambilDataSupplier.php,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var alamat  = data.alamat;
			$('#alamat').val(alamat);
			var telp  = data.telp1;
			$('#telp').val(telp);
			var id_supp  = data.id;
			$('#id_supplier').val(id_supp);
			//var type  = data.type;
			//$('#type').val(type);
			
        }
	});	
			
	});
	
  });
</script>

<?php 
  include("koneksi/koneksi.php");
  $sql = mysql_query("SELECT * FROM trbeli a
LEFT JOIN tblsupplier b ON b.id =a.id_supplier WHERE a.id_trans= '".$_GET['ids']."'")or die (mysql_error());
		$rs = mysql_fetch_array($sql);

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>EDIT PEMBELIAN</td>
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
		<td class='fonttext'>Tanggal</td>
        <td><input type='text' class='inputform' name='tangga' id='tanggal' value='' disabled='disabled'/></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Nama Supplier</td>
        <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Autosuggest Nama Supplier'  />
		<input type='hidden' name='id_supplier' id='id_supplier'/>
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
        
     </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='15%' class='fonttext'>Barcode</td>
    	<td align='center' width='15%' class='fonttext'>Nama Part</td>
      	<td align='center' width='15%' class='fonttext'>Harga</td>
      	<td align='center' width='3%' class='fonttext'>Qty</td>
      	<td align='center' width='15%' class='fonttext'>Jumlah</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>
    
    </tr>
</thead>
</table>
<div id='myDiv'></div>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>

</table>
</form>
<table>
<tr>
<td>
<p><input type='image' value='Tambah Baris' src='../assets/images/tambah_baris.png'  id='baru'  onClick='addNewRow1()'/></p>
</td>
<td>
<p align='center'><input name='print' type='image' src='../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
</td>
<td>
<p><input type='image' value='batal' src='../assets/images/batal.png'  id='baru'  onClick='tutup()'/></p>
</td>
</tr>

</table>";
?>

<script type="text/javascript">
    document.form2.total.value='<?=$rs['totalfaktur'];?>';
	document.form2.kode.value='<?=$rs['id_trans'];?>';
	document.form2.kode_hidden.value='<?=$rs['id_trans'];?>';
	document.form2.tanggal.value='<?=$rs['tgl_trans'];?>';
	document.form2.nama.value='<?=$rs['namaperusahaan'];?>';
	document.form2.id_supplier.value='<?=$rs['id_supplier'];?>';
	document.form2.telp.value='<?=$rs['telp1'];?>';
	document.form2.alamat.value='<?=$rs['alamat'];?>';
    
	
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
		var harga      = data.hrg_beli;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('Nama['+a+']').value = namabarang;
		document.getElementById('Harga['+a+']').value = harga;
		//document.getElementById('Qty['+a+']').value = 1;	
        }
	});	
			

//addNewRow1();
document.getElementById('Qty['+a+']').focus();
hitungjml(a);		
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
td0.appendChild(generateCari1(baris1));
td1.appendChild(generateNama(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateQty(baris1));
td4.appendChild(generateSUBTOTAL(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('BARCODE['+baris1+']').focus();
document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'addbarcode('+baris1+')');
document.getElementById('Cari1['+baris1+']').setAttribute('onclick', 'popjasa('+baris1+')');
document.getElementById('Qty['+baris1+']').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('SUBTOTAL['+baris1+']').setAttribute('onEnter', 'addNewRow1()');
document.getElementById('del1['+baris1+']').setAttribute('onclick', 'delRow1('+baris1+')');
baris1++;

}

function popjasa(a)
{
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
//idx.type = "hidden";
idx.name = "Id"+index+"";
idx.id = "Id["+index+"]";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateBARCODE(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE["+index+"]";
idx.size = "15";
idx.align = "center";
return idx;
}
function generateNama(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Nama"+index+"";
idx.id = "Nama["+index+"]";
idx.size = "40";
idx.readOnly = "readonly";
return idx;
}
function generateQty(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Qty"+index+"";
idx.id = "Qty["+index+"]";
idx.size = "3";
idx.style="text-align:right;";
//idx.readOnly = "readonly";
return idx;
}

function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Harga"+index+"";
idx.id = "Harga["+index+"]";
idx.size = "8";
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
function generateSUBTOTAL(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SUBTOTAL"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SUBTOTAL["+index+"]";
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
idx.id = "del1["+index+"]";
idx.size = "10";
idx.value = "X";
return idx;

}

function saveID(id) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "delete1"+id+"";
idx.id = "delete1["+id+"]";
idx.type = "hidden";
return idx;
}

var del1 = 1;
function delRow1(id){
    //buat menyimpan id_detail yang didelete
	document.getElementById("myDiv").appendChild(saveID(id));
	document.getElementById('delete1['+id+']').value = document.getElementById('Id['+id+']').value;
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
    
	//var ke1 = document.getElementById("Qty["+a+"]").value;
	//var ke2 = document.getElementById("Harga["+a+"]").value;
	var total=0;
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
    document.getElementById("total").value = total;	
    //document.getElementById("total").value = convertToRupiah(total);	
		
}

function hitungjml(a)
{
	//if(document.getElementById("Qty["+a+"]").value == null) {
    //      document.getElementById("Qty["+a+"]").value = 0;
	//}
	//if(document.getElementById("Harga["+a+"]").value == null){
    //      document.getElementById("Harga["+a+"]").value = 0;
	//}
	
	var ke1 = document.getElementById("Qty["+a+"]").value;
	var ke2 = document.getElementById("Harga["+a+"]").value;
	var jml=0;
	var total=0;
	
		jml=ke1*ke2;
    
 	document.getElementById("SUBTOTAL["+a+"]").value = jml;	
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
    var answer = confirm("Mau Simpan data dan cetak notanya????")
	if (answer)
	{	
	hitungrow() ;
	document.form2.action="simpan_beli.php?id_trans=<?=$_GET['ids']?>";
	document.form2.submit();
	}
	else
	{
	
	}
	
}	


<?php 

$sql1 = mysql_query("select a.id_detail,a.id_barang, b.nm_barang, a.qty, a.harga, (a.qty * a.harga) as subtotal from trbeli_detail a, barang b where a.id_barang=b.id_barang and a.id_trans = '".$_GET['ids']."'");
$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			document.getElementById('Id['+<?=$i;?>+']').value = '<?=$rs1['id_detail'];?>';
			document.getElementById('BARCODE['+<?=$i;?>+']').value = '<?=$rs1['id_barang'];?>';
			document.getElementById('Nama['+<?=$i;?>+']').value = '<?=$rs1['nm_barang'];?>';
			document.getElementById('Qty['+<?=$i;?>+']').value = '<?=$rs1['qty'];?>';
			document.getElementById('Harga['+<?=$i;?>+']').value = '<?=$rs1['harga'];?>';
			document.getElementById('SUBTOTAL['+<?=$i;?>+']').value = '<?=$rs1['subtotal'];?>';
		<?php 
			$i++;
		}
		?>
	

</script>

</body>