<head>
<title>MUTASI MASUK BARANG </title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
	background-color:#FFF345 
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script language="javascript">
//autocomplete pada master
$().ready(function() {	
    //autocomplete mst_inventory
	$("#inventory").autocomplete("lookup_inventory.php?", {
		width: 158
	});
	
	$("#inventory").result(function(event, data, formatted) {
	var nama_inv = document.getElementById("inventory").value;
	
	for(var j=0;j<nama_inv.length;j++){
		var e_id = nama_inv.split(':');
		if (e_id[0]=="") continue;
		var id_inv=e_id[0];
	}
	
	$.ajax({
		url : 'lookup_inventory_ambil.php?id='+id_inv,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var id_inventory  = data.id;
			$('#id_inventory').val(id_inventory);
        }
		});
	});
	
  });

</script>
 <?php
  // master_data 
  include("../../include/koneksi.php");
  $sql_mst="SELECT im.*,DATE_FORMAT(im.tgl_trans, '%Y-%m-%d') as tgl,i.nama as sumber FROM `invmasuk` im left join mst_inventory i on im.id_inventory=i.id WHERE im.id_trans= '".$_GET['ids']."'";
  $sql = mysql_query($sql_mst)or die (mysql_error());
  $rs = mysql_fetch_array($sql);
  $id_trans    	= $rs['id_trans'];
  $kode    		= $rs['kode'];
  $tgl_asli    	= $rs['tgl_trans'];
  $tanggal     	= $rs['tgl'];
  $inventory	= $rs['sumber'];
  $id_inventory = $rs['id_inventory'];
  $nama		 	= $rs['nama'];
  $note 		= $rs['catatan'];
  $totalqty		= $rs['totalqty'];
  					

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>EDIT MUTASI MASUK BARANG</td>
		<td class='fontjudul'> TOTAL QTY <input type='text' class='' name='totalqty' id='totalqty' value='$totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />		
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Kode</td>
        <td><input type='text' id='kode' name='kode' placeholder='Code No.' value='$kode'></td>
		
		<td class='fonttext'>Tanggal</td>
		<td><input type='date' id='tanggal' name='tanggal' class='datepicker' value='$tanggal'></td>
     </tr>
	 <tr>
        <td class='fonttext'>Sumber Inventory</td>
        <td><input type='text' class='inputform' name='inventory' id='inventory' placeholder='Autosuggest Sumber Inventory' value='$inventory' />
		<input type='hidden' name='id_inventory' id='id_inventory' value='$id_inventory'/>
		</td>     
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
    	<td align='center' width='5%' class='fonttext'>Qty</td>
      	<td align='center' width='5%' class='fonttext'>Size</td>
      	<td align='center' width='25%' class='fonttext'>Subtotal</td>
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
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='3' >$note</textarea></td></td>
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
		var products  = data.nama;
			$("#NamaBrg"+a+"").val(products);
		var id_products  = data.id;
			$("#IDP"+a+"").val(id_products);
		var product_size  = data.size;
			$("#Size"+a+"").val(product_size);
			//$("#Qty"+a+"").val(1);
			$("#Qty"+a+"").focus();
        }
	});	
			
	});
//document.getElementById('BARCODE'+baris1+'').focus();	
}  
	
var baris1=1;
//untuk edit dimatikan
//addNewRow1();
function addNewRow1() 
{
//validasi();
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
td2.appendChild(generateQty(baris1));
td3.appendChild(generateSize(baris1));
td4.appendChild(generateSUBTOTAL(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('BARCODE'+baris1+'').focus();
document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
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
var pesan='';
var tanggal      = form2.tanggal.value;
	if (tanggal == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
			form2.tanggal.focus;
    }

	if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
       
		return false;
	}    
}

function hitungtotal(){
    
	var total=0;
	var totalqty=0;
	var sisa_tf=0;
	
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
	//totalhidden dipake buat validasi saja
    document.getElementById("totalqty").value = totalqty;	
    //document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'})	
}

function hitungjml(a)
{
    //validasi agar tanggal tidak kosong
	validasi();
	
	if(document.getElementById("Qty"+a+"").value == "") {
		var qty = 0;	    
	}
	else{
	var qty = document.getElementById("Qty"+a+"").value;
	}
	
	
	var jml=0;
	//alert("qty ="+qty.toString()+',harga='+harga.toString())	
		
	jml=qty;    
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
    var tgl             = form2.tanggal.value;
    var id_inventory  	= form2.id_inventory.value;
    //alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	if (tgl == '') {
            pesan = 'Tanggal tidak boleh kosong\n';
        }    
	if (id_inventory == '') {
            pesan = 'SUMBER tidak boleh kosong\n';
        }	
   	
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}	
	else
	{ 
		var answer = confirm("Mau Simpan datanya ????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="invmasuk_save.php?id_trans=<?=$_GET['ids']?>&jum="+baris1;
		document.form2.submit();
		}
		else
		{}
    }	
}
<?php 
	$sql_detail="SELECT id.* from invmasuk_detail id WHERE  id.id_trans ='".$id_trans."' ORDER BY id.namabrg ASC";
	//var_dump($sql_detail);die;
	$sql1 = mysql_query($sql_detail);
	$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['id_inv_d'];?>';
			document.getElementById('BARCODE'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('IDP'+<?=$i;?>+'').value = '<?=$rs1['id_product'];?>';
			document.getElementById('NamaBrg'+<?=$i;?>+'').value = '<?=$rs1['namabrg'];?>';
			document.getElementById('Qty'+<?=$i;?>+'').value = '<?=$rs1['jumlah_beli'];?>';
			document.getElementById('Size'+<?=$i;?>+'').value = '<?=$rs1['size'];?>';
			document.getElementById('SUBTOTAL'+<?=$i;?>+'').value = '<?=$rs1['subtotal'];?>';
			
		<?php 
			$i++;
		}
		?>	
</script>

</body>