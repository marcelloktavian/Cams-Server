<head>
<title>ADD KARYAWAN DETAIL</title>
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
.disabled{
	background: #dddddd;
}
.potongan{
	color: red;
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

<table width='100%'>
<tr>
    <td class='fontjudul'>ADD KARYAWAN DETAIL</td>
    <td class='fontjudul'> TOTAL PENDAPATAN <input type='text' class='' name='totalpendapatan' id='totalpendapatan' value='Rp. 0' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
    <input type='hidden' name='totalpendapatanhidden' id='totalpendapatanhidden' value='0'>
		</td>
		<td class='fontjudul'> TOTAL POTONGAN <input type='text' class='' name='totalpotongan' id='totalpotongan' value='Rp. 0' style='color:red;text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalpotonganhidden' id='totalpotonganhidden' value='0'>
			</td>
			<td class='fontjudul'> GRAND TOTAL <input type='text' class='' name='total' id='total' value='Rp. 0' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
			<input type='hidden' name='totalhidden' id='totalhidden' value='0'>
				</td>
</tr>
</table>

<hr>
    
<table width='100%' cellspacing='0' cellpadding='0'>

     <tr>
		<td class='fonttext'>Nomor Karyawan <b>(*)</b></td>
		<td><input type='text' class='inputform' name='nomor_karyawan' id='nomor_karyawan'  />
		<td class='fonttext'>Nama Karyawan <b>(*)</b></td>
        <td><input type='text' class='inputform' name='nama_karyawan' id='nama_karyawan'  />
		
		
     </tr>
	 <tr height='1'>
     <td colspan='4'></td>
     </tr>
     <tr>
		<td class='fonttext'>NIK <b>(*)</b></td>
		<td><input type='text' class='inputform' name='nik' id='nik' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' />
	 	<td class='fonttext'>NPWP</td>
        <td><input type='text' class='inputform' name='npwp' id='npwp'   /></td>
		</td>                    
	 </tr>
	 <tr>
		<td class='fonttext'>No. BPJS Tenaga Kerja</td>
		<td><input type='text' class='inputform' name='no_bpjs' id='no_bpjs' /></td>
        <td class='fonttext'>No. JKN KIS</td>
        <td><input type='text' class='inputform' name='no_jkn_kis' id='no_jkn_kis' /></td>
	 </tr>
     <tr>
		<td class='fonttext'>E-Mail <b>(*)</b></td>
		<td><input type='text' class='inputform' name='email' id='email'  /></td>
        <td class='fonttext'>Nomor Telepon</td>
        <td><input type='text' class='inputform' name='notelp' id='notelp' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' /></td>
	 </tr>
	 <tr>
	 <td class='fonttext'>Periode Penggajian <b>(*)</b></td>
	 <td>
	 	<select class='inputform'  name='periodepenggajian' id='periodepenggajian'>
			<option value=''>-choose(pilih)-</option>
			<option value='Mingguan'>Mingguan</option>
			<option value='Bulanan'>Bulanan</option>
		</select>
	 </td>
        <td class='fonttext'>Tipe Karyawan <b>(*)</b></td>
        <td>
		<select class='inputform'  name='tipekaryawan' id='tipekaryawan'>
			<option value=''>-choose(pilih)-</option>
			<option value='Internal'>Internal</option>
			<option value='External'>External</option>
		</select>
		</td>
	 </tr>
	 <tr>
	 <td class='fonttext'>Jabatan & Departemen</td>
	 <td>
	 <select class='inputform'  name='jabatan' id='jabatan'>
		 <option value=''>-choose(pilih)-</option>
		 ";
		 $sql_jabatan="SELECT id_jabatan, a.nama_jabatan, b.nama_dept FROM hrd_jabatan a LEFT JOIN hrd_departemen b ON b.id_dept=a.id_dept WHERE a.deleted=0 ORDER BY a.nama_jabatan";
		 $sql1 = mysql_query($sql_jabatan);
		 $i=1;
		 while($rs1=mysql_fetch_array($sql1)){
			 echo "<option value='".$rs1['id_jabatan']."'>".$rs1['nama_jabatan']." (".$rs1['nama_dept'].")</option>";
		 }
echo "
	 </select>
	 </td>
		<td class='fonttext'>Rekening BCA</td>
		<td><input type='text' class='inputform' name='rekening' id='rekening' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' /></td>
	 </tr>
	 <tr>
		<td class='fonttext'>PTKP</td>
		<td>
		<select class='inputform' name='ptkp' id='ptkp'>
			<option value=''>-choose(pilih)-</option>
			";
			$sql_ptkp="SELECT id, nama_ptkp FROM hrd_ptkp a WHERE a.deleted=0 ORDER BY a.group ASC, a.nama_ptkp ASC";
			$sql1 = mysql_query($sql_ptkp);
			$i=1;
			while($rs1=mysql_fetch_array($sql1)){
				echo "<option value='".$rs1['id']."'>".$rs1['nama_ptkp']."</option>";
			}
	echo "
		</select>
		</td>
		<td class='fonttext'>Alamat Domisili <b>(*)</b></td>
		<td colspan=10><textarea name='alamat' id='alamat' cols='50' rows='2' /></textarea></td>	
	 </tr>
	 <tr><td colspan=4><hr/></td></tr>
     <tr>
        <td class='fonttext'>Total Upah Tetap <b>(*)</b></td>
        <td><input type='text' class='inputform' name='upah_tetap' id='upah_tetap' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' /></td>
		<td class='fonttext'>Upah BPJS Kesehatan</td>
        <td><input type='text' class='inputform' name='upah_bpjs' id='upah_bpjs'  onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' /></td>
	 </tr>
     <tr>
        <td class='fonttext'>Upah BPJS Tenaga Kerja</td>
        <td><input type='text' class='inputform' name='upah_bpjs_tk' id='upah_bpjs_tk' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")' /></td>
		<td class='fonttext'>Tanggungan Tambahan BPJS Kesehatan</td>
        <td><input type='text' class='inputform' name='tambahan_bpjs' id='tambahan_bpjs' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")'/></td>
	 </tr>
     
	 
	 
</table>
<hr>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='15%' class='fonttext'>Pendapatan/Potongan</td>
    	<td align='center' width='10%' class='fonttext'>Tipe</td>
    	<td align='center' width='10%' class='fonttext'>%</td>
    	<td align='center' width='10%' class='fonttext'>Value</td>
    	<!-- <td align='center' width='10%' class='fonttext'>Dikali Hari Kehadiran</td>
    	<td align='center' width='10%' class='fonttext'>Berdasarkan % Kehadiran</td>
    	<td align='center' width='10%' class='fonttext'>Mempengaruhi Objek Pajak</td> -->
    	<td align='center' width='15%' class='fonttext'>Subtotal</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
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
   $("#BARCODE"+a+"").autocomplete("lookup_pendapatan_potongan.php?", {
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
		url : 'lookup_pendapatan_potongan_ambil.php?id='+id_cmp,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var type  = data.type;
		    $("#Tipe"+a+"").val(type.charAt(0).toUpperCase() + type.slice(1));

		    var id_penpot  = data.id_penpot;
			$("#Id"+a+"").val(id_penpot);

            var nama_penpot  = data.nama_penpot;
			$("#BARCODE"+a+"").val(nama_penpot);

            var metode_pethitungan = data.metode_pethitungan;
            // if(metode_pethitungan == 'Per Hari Hadir'){
            //     $("#HariKehadiran"+a+"").attr('checked','checked');
            // }else{
            //     $("#HariKehadiran"+a+"").attr('checked',null);
            // }

            var persentase_kehadiran = data.persentase_kehadiran;
            // if(persentase_kehadiran == '1'){
            //     $("#PersenKehadiran"+a+"").attr( "checked",'checked');
            // }else{
            //     $("#PersenKehadiran"+a+"").attr( "checked", null );
            // }

            var objek_pph21 = data.objek_pph21;
            // if(objek_pph21 == 'Menambah' || objek_pph21 == 'Mengurangi'){
            //     $("#ObjekPajak"+a+"").attr( "checked",'checked');
            // }else{
            //     $("#ObjekPajak"+a+"").attr( "checked", null );
            // }

			var element1 = document.getElementById("BARCODE"+a+"");
			var element2 = document.getElementById("Tipe"+a+"");
			var element3 = document.getElementById("Persen"+a+"");
			var element4 = document.getElementById("Value"+a+"");
			var element5 = document.getElementById("Subtotal"+a+"");
            if(metode_pethitungan == 'Manual Input'){
				element3.classList.add("disabled");
				element4.classList.add("disabled");
				element5.classList.add("disabled");

				element3.setAttribute('readonly', true);
				element4.setAttribute('readonly', true);
				element5.setAttribute('readonly', true);

				element3.value = '0';
				element4.value = '0';
				element5.value = '0';
			}else{
				element3.classList.remove("disabled");
				element4.classList.remove("disabled");
				element5.classList.remove("disabled");

				element3.removeAttribute('readonly');
				element4.removeAttribute('readonly');
				element5.removeAttribute('readonly');
			}

			if(type == 'pendapatan'){
				element1.classList.remove("potongan");
				element2.classList.remove("potongan");
				element3.classList.remove("potongan");
				element4.classList.remove("potongan");
				element5.classList.remove("potongan");
			}else{
				element1.classList.add("potongan");
				element2.classList.add("potongan");
				element3.classList.add("potongan");
				element4.classList.add("potongan");
				element5.classList.add("potongan");
			}

            var type_pengaruh = data.type_pengaruh;
			if(type_pengaruh == 'Total Upah Tetap'){
				if(document.getElementById("upah_tetap").value == ''){
					element4.value = 0;
				}else{
					element4.value = document.getElementById("upah_tetap").value;
				}
			}else if(type_pengaruh == 'Upah BPJS Tenaga Kerja'){
				if(document.getElementById("upah_bpjs_tk").value == ''){
					element4.value = 0;
				}else{
					element4.value = document.getElementById("upah_bpjs_tk").value;
				}
			}else if(type_pengaruh == 'Upah BPJS Kesehatan'){
				if(document.getElementById("upah_bpjs").value == ''){
					element4.value = 0;
				}else{
					element4.value = document.getElementById("upah_bpjs").value;
				}
			}

			$("#Persen"+a+"").focus();
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
var td4 = document.createElement("td");
var td5 = document.createElement("td");
// var td6 = document.createElement("td");
// var td7 = document.createElement("td");
// var td8 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
td1.appendChild(generateTipe(baris1));
td2.appendChild(generatePersen(baris1));
td3.appendChild(generateValue(baris1));
// td3.appendChild(generateHariKehadiran(baris1));
// td3.appendChild(generatePersenKehadiran(baris1));
// td3.appendChild(generateObjekPajak(baris1));
td4.appendChild(generateSubtotal(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
// row.appendChild(td6);
// row.appendChild(td7);
// row.appendChild(td8);

document.getElementById('BARCODE'+baris1+'').focus();
// document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Persen'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Persen'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
document.getElementById('Value'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('Value'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
document.getElementById('Subtotal'+baris1+'').setAttribute('onChange', 'hitungtotal()');
document.getElementById('Subtotal'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9^.]/g, '')");
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

function generateTipe(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Tipe"+index+"";
idx.id = "Tipe"+index+"";
idx.size = "10";
idx.align = "center";
idx.readOnly = "readonly";
return idx;
}

function generatePersen(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Persen"+index+"";
idx.id = "Persen"+index+"";
idx.size = "5";
idx.align = "center";
idx.style="text-align:right;";
idx.max="3";
return idx;
}

function generateValue(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Value"+index+"";
idx.id = "Value"+index+"";
idx.size = "15";
idx.align = "center";
idx.style="text-align:right;";
return idx;
}

function generateHariKehadiran(index) {
//id_product
var idx = document.createElement("input");
idx.type = "checkbox";
idx.name = "HariKehadiran"+index+"";
idx.id = "HariKehadiran"+index+"";
idx.size = "5";
idx.align = "center";
return idx;
}

function generatePersenKehadiran(index) {
//id_product
var idx = document.createElement("input");
idx.type = "checkbox";
idx.name = "PersenKehadiran"+index+"";
idx.id = "PersenKehadiran"+index+"";
idx.size = "5";
idx.align = "center";
return idx;
}


function generateObjekPajak(index) {
//id_product
var idx = document.createElement("input");
idx.type = "checkbox";
idx.name = "ObjekPajak"+index+"";
idx.id = "ObjekPajak"+index+"";
idx.size = "5";
idx.align = "center";
return idx;
}

function generateSubtotal(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Subtotal"+index+"";
idx.id = "Subtotal"+index+"";
idx.size = "15";
idx.align = "center";
idx.style="text-align:right;";
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
    var nomor_karyawan      = form2.nomor_karyawan.value;
    var nama_karyawan   	= form2.nama_karyawan.value;
	var nik            		= form2.nik.value;
    var email            	= form2.email.value;
	var periodepenggajian   = form2.periodepenggajian.value;
    var tipekaryawan     	= form2.tipekaryawan.value;
    var alamat     			= form2.alamat.value;
    var upah_tetap     		= form2.upah_tetap.value;
    
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
	if (upah_tetap == '') {
            pesan = 'Total Upah Tetap tidak boleh kosong\n';
        }
    if (alamat == '') {
            pesan = 'Alamat Domisili tidak boleh kosong\n';
        }
	if (tipekaryawan == '') {
        pesan = 'Tipe Karyawan tidak boleh kosong\n';
    }
	if (periodepenggajian == '') {
        pesan = 'Periode Penggajian tidak boleh kosong\n';
    }
	if (email == '') {
            pesan = 'E-Mail tidak boleh kosong\n';
        }
	if (nik == '') {
            pesan = 'NIK tidak boleh kosong\n';
        }
	
	if (nama_karyawan == '') {
            pesan = 'Nama Karyawan tidak boleh kosong\n';
        }
		
	if (nomor_karyawan == '') {
            pesan = 'Nomor Karyawan tidak boleh kosong\n';
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
		document.form2.action="karyawan_save.php?action=add&row="+baris1;
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
	document.getElementById('BARCODE1').focus();

</script>

</body>