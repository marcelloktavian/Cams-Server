<head>
<title>ADD SIZE</title>
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
    background-color:#CACADE ;
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
  include("../../include/koneksi.php");

?>
</head>
<body>
<?php
    $id = $_GET['idbrg'];
    $baris = $_GET['baris'];
  // master_data 
  include("../../include/koneksi.php");
  $sql_mst="SELECT (SELECT gp.id FROM mst_b2bproductsgrp gp WHERE gp.id='".$id."') AS id_product,(SELECT gp.nama FROM mst_b2bproductsgrp gp WHERE gp.id='".$id."') AS product";
  //var_dump($sql_mst);die;
  $sql = mysql_query($sql_mst)or die (mysql_error());
  $rs = mysql_fetch_array($sql);
  $idprod 	= $rs['id_product'];
  $products  = $rs['product'];

  $sizelist = '';
  $count=1;
  $sql_size = "SELECT size FROM mst_b2bproductsgrp_detail det WHERE det.id_productsgrp='".$id."' AND deleted=0 ORDER BY size ASC";
  $sql = mysql_query($sql_size)or die (mysql_error());
  while ($rs = mysql_fetch_array($sql)) {
	  if ($count == 1) {
		  $sizelist = $rs['size'];
	  }else{
		  $sizelist = $sizelist.', '.$rs['size'];
	  }
	 $count++;
  }


echo"<form id='form2' name='form2' action='' method='post'>

<table width='100%'>
<tr>
    <td class='fontjudul'>ADD SIZE</td>
</tr>
</table>

<hr>
    
<table width='100%' cellspacing='0' cellpadding='0'>
    
     <tr>
		<td class='fonttext'>Products</td>
		<td>$products
		<input type='hidden' name='id_products' id='id_products'/>
     </tr>
	 <tr>
		<td class='fonttext'>Size</td>
		<td>$sizelist
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
	 <tr height='1'>
     <!-- <td colspan='4'>Detail Jenis Biaya<hr/></td> -->
	 </tr>
</table>
<hr>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
    	<td align='center' width='30%' class='fonttext'>Size</td>
    	<td align='center' width='30%' class='fonttext'>Qty</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>
<hr>

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
   $("#Size"+a+"").autocomplete("lookup_productsize.php?idbrg="+<?php echo $id ?>, {
	width: 158});
  //console.log('here'+a)  ;
   $("#Size"+a+"").result(function(event, data, formatted) {
	    var nama = document.getElementById("Size"+a+"").value;
	});
}  
		
var baris1=1;
// addNewRow1();
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
td0.appendChild(generateSize(baris1));
td1.appendChild(generateQty(baris1));
//id untuk dimasukin id_product
// td2.appendChild(generateSize(baris1));
td2.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
// row.appendChild(td3);

document.getElementById('Id'+baris1+'').focus();
// document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Size'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
//document.getElementById('Next'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
// document.getElementById('del1'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');

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

function generateQty(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Qty"+index+"";
idx.id = "Qty"+index+"";
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
function generateSize(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "Harga"+index+"";
//idx.id = "Harga["+index+"]";
idx.name = "Size"+index+"";
idx.id = "Size"+index+"";
idx.size = "25";
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
	if (baris1>2) {
		// baris1-=1;
		el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
    // return false;
}
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
    // var nama_input      = form2.nama.value;
    // var harga   		= form2.price.value;
	// var id_kategori     = form2.id_kategori.value;
    //alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
    // if (nama_input == '') {
    //         pesan = 'Nama Jenis tidak boleh kosong\n';
    //     }
	// if (harga == '') {
    //         pesan = 'Harga tidak boleh kosong\n';
    //     }
	// if (id_kategori == '') {
    //         pesan = 'Kategori tidak boleh kosong\n';
    //     }
		
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}	
	else
	{ 
		var answer = confirm("Mau Simpan datanya??")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="size_save.php?action=insert&baris="+<?php echo $baris ?>+"&row="+baris1;
		document.form2.submit();
		}
		else
		{}
    }	
}	
	
<?php
    $id = $_GET['edit'];
    $ex = explode(", ", $id);

    for ($i=0; $i < count($ex); $i++) { 
        $ex2 = explode('(',$ex[$i]);
        ?>
            addNewRow1();
            document.getElementById('Size'+<?=$i+1;?>+'').value = '<?=$ex2[0];?>';
            document.getElementById('Qty'+<?=$i+1;?>+'').value = '<?=str_replace(")","",$ex2[1]);?>';
            document.getElementById('Qty'+<?=$i+1;?>+'').focus();
        <?php
    }
?>
</script>

</body>