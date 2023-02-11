<head>
<title>EDIT FORMULA PPH 21 DETAIL</title>
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
    background-color:plum ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
.disabled{
	background: #dddddd;
}
.potongan{
	color: red;
}
</style>
 <?php 
  include("../../include/koneksi.php");
  $id = $_GET['id'];

  $sql = "SELECT * FROM hrd_mstpph21 WHERE id = $id AND deleted=0";
  $sq = mysql_query($sql);
  $rs = mysql_fetch_array($sq);

  $nama 			= $rs['nama'];	
  $note 			= $rs['note'];	
?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>

<table width='100%'>
<tr>
    <td class='fontjudul'>EDIT FORMULA PPH 21 DETAIL</td>
</tr>
</table>

<hr>
    
<table width='100%' cellspacing='0' cellpadding='0'>

     <tr>
		<td class='fonttext'>Nama Formula <b>(*)</b></td>
		<td><input type='hidden' name='id_pph21' id='id_pph21' value='$id'>
        <input type='text' class='inputform' name='namapph21' id='namapph21' placeholder='Nama Formula' value='$nama' />
		<td class='fonttext'>Keterangan</td>
        <td colspan=10><textarea name='keterangan' id='keterangan' placeholder='Keterangan' cols='50' rows='2' />$note</textarea></td>	
     </tr>
	
	 
</table>
<hr>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='15%' class='fonttext'>PPH 21</td>
    	<td align='center' width='10%' class='fonttext'>Pengali (x)</td>
    	<td align='center' width='10%' class='fonttext'>Value (+/-)</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<div id='myDiv'></div>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>

</table>
</form>
<table>
<tr><td><b>(*) = </b>Wajib Diisi</td></tr>
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
   $("#BARCODE"+a+"").autocomplete("lookup_pph21.php?", {
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
		url : 'lookup_pph21_ambil.php?id='+id_cmp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var id_pph21  = data.id_pph21;
			$("#Id"+a+"").val(id_pph21);

            var nama_pph21  = data.nama_pph21;
			$("#BARCODE"+a+"").val(nama_pph21);

            $("#Pengali"+a+"").focus();
        }
	});	
			
	});
//document.getElementById('Qty'+baris1+'').focus();	
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
var td3 = document.createElement("td");
var td4 = document.createElement("td");
// var td5 = document.createElement("td");
// var td6 = document.createElement("td");
// var td7 = document.createElement("td");
// var td8 = document.createElement("td");

td0.appendChild(generateIdDetail(baris1));
td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
td1.appendChild(generatePengali(baris1));
td2.appendChild(generateValue(baris1));
// td3.appendChild(generateValue(baris1));
// td4.appendChild(generateHariKehadiran(baris1));
// td5.appendChild(generatePersenKehadiran(baris1));
// td6.appendChild(generateObjekPajak(baris1));
// td7.appendChild(generateSubtotal(baris1));
td3.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
// row.appendChild(td4);
// row.appendChild(td5);
// row.appendChild(td6);
// row.appendChild(td7);
// row.appendChild(td8);

// document.getElementById('BARCODE'+baris1+'').focus();
// document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Persen'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Pengali'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
document.getElementById('Value'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^+^-]/g, '')");
// document.getElementById('Value'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Value'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
// document.getElementById('Subtotal'+baris1+'').setAttribute('onChange', 'hitungtotal()');
// document.getElementById('Subtotal'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
//document.getElementById('Next'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('del1'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');

get_products(baris1);
baris1++;
}

function harusAngka(evt){
 var charCode = (evt.which) ? evt.which : event.keyCode
 if ((charCode < 48 || charCode > 57)&&charCode>32)
 return false;
 return true;
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

function generateIdDetail(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdDetail"+index+"";
idx.id = "IdDetail"+index+"";
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
idx.size = "65";
idx.align = "center";
return idx;
}

function generatePengali(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Pengali"+index+"";
idx.id = "Pengali"+index+"";
idx.size = "15";
idx.align = "center";
return idx;
}

function generateValue(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Value"+index+"";
idx.id = "Value"+index+"";
idx.size = "15";
idx.max = "1";
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
idx.id = "delete1"+id+"";
idx.type = "hidden";
return idx;
}


function delRow1(id){ 
	document.getElementById("myDiv").appendChild(saveID(id));
	document.getElementById('delete1'+id+'').value = document.getElementById('IdDetail'+id+'').value;

	var el = document.getElementById("t1"+id);
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
	return false;
}


function hitungtotal(){
    
	var totalpendapatan=0;
	var totalpotongan=0;
	
    for (var i=1; i<=baris1;i++){
	var Subtotal=document.getElementById("Subtotal"+i+"");
	 if (Subtotal != null)
	 {   
		var tipe = document.getElementById("Tipe"+i+"").value;

	    if(document.getElementById("Subtotal"+i+"").value == "") {
			totalpendapatan += parseFloat(0);
			totalpotongan += parseFloat(0);
		}else{
			if(tipe == 'Pendapatan'){
				totalpendapatan += parseFloat(document.getElementById("Subtotal"+i+"").value);
			}else{
				totalpotongan += parseFloat(document.getElementById("Subtotal"+i+"").value);
			}
		}
	 }
		//else{}
		//return false;
	}

	var grandtotal = totalpendapatan - totalpotongan;

	document.getElementById("totalpendapatan").value = totalpendapatan.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalpendapatanhidden").value = totalpendapatan;

	document.getElementById("totalpotongan").value = totalpotongan.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalpotonganhidden").value = totalpotongan;

	document.getElementById("total").value = grandtotal.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalhidden").value = grandtotal;
	
    //alert("baris="+baris1.toString());
	
	//totalqty
	//document.getElementById("totalqty").value = totalqty;
	//total belum disc

}

function hitungjml(a)
{
	if(document.getElementById("Persen"+a+"").value == "") {
		var Persen = 0;	    
	}
	else{
	var Persen = document.getElementById("Persen"+a+"").value;
	}

	if(document.getElementById("Value"+a+"").value == "") {
		var Value = 0;	    
	}
	else{
	var Value = document.getElementById("Value"+a+"").value;
	}

	var totaldet = (Persen/100)*Value;


	document.getElementById("Subtotal"+a+"").value = totaldet;	
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
    var pesan           	= '';
    var namapph21           = form2.namapph21.value;
    
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	if (namapph21 == '') {
            pesan = 'Nama Formula tidak boleh kosong\n';
        }
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}	
	else
	{ 
		var answer = confirm("Mau Simpan datanya???")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="pph21_save.php?action=edit&row="+baris1;
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
	// document.getElementById('BARCODE1').focus();

	<?php
		$sqldetail = "SELECT a.*, b.nama_pph21 FROM `hrd_detpph21` a LEFT JOIN hrd_pph21 b ON b.id_pph21 = a.id_pph21 WHERE a.id_parent='$id'";
		$sqdet = mysql_query($sqldetail);
		$i = 1;
		while($rs1 = mysql_fetch_array($sqdet)){
			?>
				addNewRow1();
				document.getElementById('BARCODE'+<?=$i;?>+'').focus();
				document.getElementById('IdDetail'+<?=$i;?>+'').value = '<?=$rs1['id'];?>';
				document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['id_pph21'];?>';
				document.getElementById('BARCODE'+<?=$i;?>+'').value = '<?=$rs1['nama_pph21'];?>';
				document.getElementById('Pengali'+<?=$i;?>+'').value = '<?=$rs1['pengali'];?>';
				document.getElementById('Value'+<?=$i;?>+'').value = '<?=$rs1['value'];?>';
			<?php
			$i++;
		}
	
	?>

</script>

</body>