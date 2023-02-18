<head>
<title>PENDAPATAN DETAIL</title>
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
    background-color:skyblue ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
.disabled{
	background: #dddddd;
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
  $id = $_GET['karyawan'];
  $baris = $_GET['baris'];
  $penggajian = $_GET['penggajian'];

  $sql = "SELECT a.id_karyawan, a.nama_karyawan, c.nama_dept FROM hrd_karyawan a LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan = '$id' ";
  $sq = mysql_query($sql);
  $rs=mysql_fetch_array($sq);

  $idkaryawan = $rs['id_karyawan'];
  $nama = $rs['nama_karyawan'];
  $dept = $rs['nama_dept'];

  $sql2 = "select det.jml_kehadiran, mst.jml_periode from hrd_penggajiandet det LEFT JOIN hrd_penggajian mst ON det.id_penggajian=mst.penggajian_id	 where det.id_penggajian='$penggajian' and det.id_karyawan='$id' and det.status='pendapatan' ";
  $sq2 = mysql_query($sql2);
  $rs2=mysql_fetch_array($sq2);

  $kehadiran = $rs2['jml_kehadiran'];
  $periode = $rs2['jml_periode'];

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>

<table width='100%'>
<tr>
    <td class='fontjudul'>PENDAPATAN DETAIL</td>
			<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='Rp. 0' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
			<input type='hidden' name='totalhidden' id='totalhidden' value='0'>
			<input type='hidden' name='totalSebelumnya' id='totalSebelumnya' value='0'>
				</td>
</tr>
</table>

<hr>
    
<table width='25%' cellspacing='0' cellpadding='0'>
     <tr>
		<td class='fonttext'>Nama</td>
		<td class='fonttext'>: $nama <input type='hidden' id='idKaryawan' name='idKaryawan' value='$idkaryawan'></td>
	 </tr>
     <tr>
	 	<td class='fonttext'>Departemen</td>
		 <td class='fonttext'>: $dept</td>
		</td>                    
	 </tr>
	 <tr>
	 	<td class='fonttext'>Jumlah Kehadiran</td>
		 <td class='fonttext'>: <input type='text' name='kehadiran' id='kehadiran' value='$kehadiran' size=1 onkeydown='validasi()' onkeyup='if(/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,".'"'.'"'.")'> / $periode</td>
		</td>                    
	 </tr>
</table>
<hr>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
        <td align='center' width='15%' class='fonttext'>Nama</td>
    	<td align='center' width='10%' class='fonttext'>Tipe</td>
    	<td align='center' width='10%' class='fonttext'>Subtotal</td>
    	<td align='center' width='15%' class='fonttext'>Keterangan</td>
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
<tr>
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
function validasi(){
	var hadir = $('#kehadiran').val();
	var periode = <?=$periode?>

	if(hadir > periode){
		$('#kehadiran').val(periode);
	}
}

//autocomplete pada grid
function validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

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
            if(metode_pethitungan == 'Per Hari Hadir'){
                $("#HariKehadiran"+a+"").attr('checked','checked');
            }else{
                $("#HariKehadiran"+a+"").attr('checked',null);
            }

            var persentase_kehadiran = data.persentase_kehadiran;
            if(persentase_kehadiran == '1'){
                $("#PersenKehadiran"+a+"").attr( "checked",'checked');
            }else{
                $("#PersenKehadiran"+a+"").attr( "checked", null );
            }

            var objek_pph21 = data.objek_pph21;
            if(objek_pph21 == 'Menambah' || objek_pph21 == 'Mengurangi'){
                $("#ObjekPajak"+a+"").attr( "checked",'checked');
            }else{
                $("#ObjekPajak"+a+"").attr( "checked", null );
            }

			var element1 = document.getElementById("Persen"+a+"");
			var element2 = document.getElementById("Value"+a+"");
			var element3 = document.getElementById("Subtotal"+a+"");
            if(metode_pethitungan == 'Manual Input'){
				element1.classList.add("disabled");
				element2.classList.add("disabled");
				element3.classList.add("disabled");
				element1.setAttribute('readonly', true);
				element2.setAttribute('readonly', true);
				element3.setAttribute('readonly', true);
				element1.value = '0';
				element2.value = '0';
				element3.value = '0';
			}else{
				element1.classList.remove("disabled");
				element2.classList.remove("disabled");
				element3.classList.remove("disabled");
				element1.removeAttribute('readonly');
				element2.removeAttribute('readonly');
				element3.removeAttribute('readonly');
			}


			$("#Persen"+a+"").focus();
        }
	});	
			
	});
//document.getElementById('Qty'+baris1+'').focus();	
}  
		
var baris1=1;
function addNewRow1() 
{
var tbl = document.getElementById("tbl_1");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't1'+baris1;

var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
// var td4 = document.createElement("td");
// var td5 = document.createElement("td");
// var td6 = document.createElement("td");
// var td7 = document.createElement("td");
// var td8 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateBARCODE(baris1));
td1.appendChild(generateTipe(baris1));
// td2.appendChild(generatePersen(baris1));
// td3.appendChild(generateValue(baris1));
// td4.appendChild(generateHariKehadiran(baris1));
// td5.appendChild(generatePersenKehadiran(baris1));
// td6.appendChild(generateObjekPajak(baris1));
td2.appendChild(generateSubtotal(baris1));
td3.appendChild(generateKeterangan(baris1));
// td8.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
// row.appendChild(td4);
// row.appendChild(td5);
// row.appendChild(td6);
// row.appendChild(td7);
// row.appendChild(td8);

document.getElementById('BARCODE'+baris1+'').focus();
// document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Persen'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Persen'+baris1+'').setAttribute('onkeypress', 'return harusAngka(event)');
// document.getElementById('Value'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
// document.getElementById('Value'+baris1+'').setAttribute('onkeypress', 'return harusAngka(event)');
document.getElementById('Subtotal'+baris1+'').setAttribute('onChange', 'hitungtotal()');
document.getElementById('Subtotal'+baris1+'').setAttribute('onkeypress', 'return harusAngka(event)');
//document.getElementById('Next'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
// document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
// document.getElementById('del1'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');

// get_products(baris1);
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
idx.readOnly = "readonly";
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


function generateKeterangan(index) {
//id_product
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Keterangan"+index+"";
idx.id = "Keterangan"+index+"";
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
    
	var total=0;
	
    for (var i=1; i<=baris1;i++){
	var Subtotal=document.getElementById("Subtotal"+i+"");
	 if (Subtotal != null)
	 {   
	    if(document.getElementById("Subtotal"+i+"").value == "") {
			total += parseInt(0);
		}else{
			total += parseInt(document.getElementById("Subtotal"+i+"").value);
		}
	 }
		//else{}
		//return false;
	}

	document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalhidden").value = total;
	
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
		var answer = confirm("Mau Simpan datanya???")
		if (answer)
		{	
			var b = <?php echo $_GET['baris'];?>;
			window.opener.document.getElementById("TotalHidden"+b+"").value = parseInt(window.opener.document.getElementById("TotalHidden"+b+"").value) - parseInt($('#totalSebelumnya').val()) ;
			// window.opener.document.getElementById("TotalHidden"+b+"").value = window.opener.document.getElementById("Total"+b+"").value;

			window.opener.document.getElementById("TotalHidden"+b+"").value = parseInt(window.opener.document.getElementById("TotalHidden"+b+"").value)  + parseInt($('#totalhidden').val());
			// window.opener.document.getElementById("TotalHidden"+b+"").value = window.opener.document.getElementById("Total"+b+"").value;

			window.opener.document.getElementById("Total"+b+"").value = document.getElementById("totalhidden").value;

			window.opener.hitungtotal();

			window.opener.document.getElementById("Kehadiran"+b+"").value = document.getElementById("kehadiran").value;

			// hitungrow();
			document.form2.action="pendapatan_save.php?action=list&row="+baris1+"&penggajian="+<?=$_GET['penggajian']?>;
			document.form2.submit();
		}
		else
		{}
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

<?php
$i = 1;
$sqldetail = "SELECT d.id_det_karyawan, e.nama_penpot, e.type, IFNULL(d.subtotal,0) as total, d.keterangan FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='pendapatan' AND metode_pethitungan='Manual Input' LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan='$id' ";
$sqdet = mysql_query($sqldetail);
$total = 0;
while($rsdet=mysql_fetch_array($sqdet)){
	?>
		addNewRow1();
		document.getElementById("Id"+<?=$i?>).value = "<?php echo $rsdet['id_det_karyawan'] ?>";
		document.getElementById("BARCODE"+<?=$i?>).value = "<?php echo $rsdet['nama_penpot'] ?>";
		document.getElementById("Tipe"+<?=$i?>).value = "<?php echo ucfirst($rsdet['type']) ?>";
		document.getElementById("Subtotal"+<?=$i?>).value = "<?php echo $rsdet['total'] ?>";
		document.getElementById("Keterangan"+<?=$i?>).value = "<?php echo $rsdet['keterangan'] ?>";
	<?php
	$total = $total + $rsdet['total'];
	$i++;
}

?>	

document.getElementById("totalSebelumnya").value = "<?php echo $total ?>";
hitungtotal();
</script>

</body>