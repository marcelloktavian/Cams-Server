<head>
<title>EDIT COST</title>
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
    /* background-color:Moccasin */;
    background-color:#CAEFDE ;
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
			var nama  = data.nama_kategori;
			$('#kategori').val(nama);
        }
		});
	});
	
  });

</script>
 <?php 
  // master_data 
  include("../../include/koneksi.php");
  $sql_mst="SELECT a.*,c.nama_kategori FROM `mst_jenisbiaya` a left join mst_kategori_biaya c on a.id_kategori=c.id WHERE a.id= '".$_GET['ids']."'";
  //var_dump($sql_mst);die;
  $sql = mysql_query($sql_mst)or die (mysql_error());
  $rs = mysql_fetch_array($sql);
  $idkategori 	= $rs['id_kategori'];
  $kategori  	= $rs['nama_kategori'];
  $jenis    	= $rs['nama_jenis'];
  $keterangan 	= $rs['keterangan'];
  	 
?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>

<table width='100%'>
<tr>
    <td class='fontjudul'>EDIT COST</td>
</tr>
</table>

<hr>
    
<table width='100%' cellspacing='0' cellpadding='0'>
    
<tr>
<td class='fonttext'>Category</td>
<td><input type='text' class='inputform' name='kategori' id='kategori' placeholder='Autosuggest Category' value='$kategori' />
<input type='hidden' name='id_kategori' id='id_kategori' value='$idkategori'/>
</tr>
<tr height='1'>
<td colspan='4'></td>
</tr>
<tr>
 <td class='fonttext'>Type</td>
 <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Type' value='$jenis' /> </td>
</tr>

	 <tr height='1'>
     <!-- <td colspan='4'>Detail Product<hr/></td> -->
	 </tr>
</table>
<hr>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
	<td align='center' width='65%' class='fonttext'>Cost</td>
	<td align='center' width='30%' class='fonttext'>Satuan</td>
	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<div id='myDiv'></div>

<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>
<hr>
<table>
    
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='100' rows='3' >$keterangan</textarea></td></td>
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
		var id_cmp=id[0];
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_products_ambil.php?id='+id_cmp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var composition  = data.nama;
			$("#NamaBrg"+a+"").val(composition);
		var id_composition  = data.id;
			$("#IDP"+a+"").val(id_composition);
		//var	size = data.size
			$("#Size"+a+"").val(size);
			$("#Size"+a+"").focus();
        }
	});	
			
	});
//document.getElementById('Qty'+baris1+'').focus();	
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
// var td3 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateNama(baris1));
td1.appendChild(generateSatuan(baris1));
//id untuk dimasukin id_product
// td2.appendChild(generateSize(baris1));
td2.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
// row.appendChild(td3);

// document.getElementById('NamaJenis'+baris1+'').focus();
// document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Size'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('Next'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
// document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');

// get_products(baris1);
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
idx.name = "NamaBiaya"+index+"";
idx.id = "NamaBiaya"+index+"";
idx.size = "75";
// idx.readOnly = "readonly";
idx.bgcolor = "grey";
//idx.disabled = "disabled";
return idx;
}

function generateSatuan(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Satuan"+index+"";
idx.id = "Satuan"+index+"";
idx.size = "25";
idx.align = "center";
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
idx.id = "delete1["+id+"]";
idx.type = "hidden";
return idx;
}

var del1 = 1;
function delRow1(id){ 
	//buat menyimpan id_detail yang didelete
	document.getElementById("myDiv").appendChild(saveID(id));
	document.getElementById('delete1['+id+']').value = document.getElementById('Id'+id+'').value;
	del1++; 
	var el = document.getElementById("t1"+id);
	//baris1-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    // hitungtotal(id);
	return false;
}


function hitungtotal(){
    
	var totalqty=0;
	
    for (var i=1; i<=baris1;i++){
	var barcode=document.getElementById("BARCODE"+i+"");
	 if (barcode != null)
	 {   
	    /*
		if(document.getElementById("Qty"+i+"").value == "") {
		var nett_price = 0;}
		else{
		var nett_price = document.getElementById("Qty"+i+"").value;
		}
		*/
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
	var id_kategori     = form2.id_kategori.value;
    //alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
    if (nama_input == '') {
            pesan = 'Nama Produk tidak boleh kosong\n';
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
		var answer = confirm("Mau Simpan master jenis biayanya???")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="jenisbiaya_save.php?row="+baris1+"&id_trans=<?=$_GET['ids']?>";
		document.form2.submit();
		}
		else
		{}
    }	
}	
	
<?php 
	$sql_detail="SELECT *  FROM det_jenisbiaya a WHERE a.id_parent ='".$_GET['ids']."' ORDER BY a.nama_biaya ASC";
	//var_dump($sql_detail);die;
	$sql1 = mysql_query($sql_detail);
	$i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			addNewRow1();
			document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['id'];?>';
			document.getElementById('NamaBiaya'+<?=$i;?>+'').value = '<?=$rs1['nama_biaya'];?>';
			document.getElementById('Satuan'+<?=$i;?>+'').value = '<?=$rs1['satuan'];?>';
		<?php 
			$i++;
		}
		?>

</script>

</body>