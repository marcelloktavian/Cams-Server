<?
// $brg = explode('=','4');
// var_dump($brg);
					
// die;
?>

<head>
<title>PENJUALAN KASIR 1</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script src="../../assets/js/time.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
    background-color: SkyBlue;
}
tanggal {
    color: maroon;
    margin-left: 40px;
}

#tbl_1{
clear: both;
border: 1px solid #FF6600;
height: 20px;
overflow-y:auto;
overflow-x:scroll;
float:left;
width:1200px;
} 
</style>

<script language="javascript">
$().ready(function() {	
		$("#nama").autocomplete("jual_customer.php", {
		width: 158
  });
  
   $("#nama").result(function(event, data, formatted) {
	var nama = document.getElementById("nama").value;
	$.ajax({
		url : 'jual_ambilCustomer.php?nama='+nama,
		//url : 'ambilDataSupplier.php,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		var alamat  = data.alamat;
			$('#alamat').val(alamat);
			var telp  = data.telp1;
			$('#telp').val(telp);
			var id_customer  = data.id;
			$('#id_customer').val(id_customer);				
        }
	});	
			
	});
	
  });
</script>
 <?php 
 include("../../include/koneksi.php");
 //include("koneksi/koneksi.php");
 
  
  
//$id_registrasi = getnewnotrxwait();
$id_pkb =  $_GET['id'];// getnewnotrxwait2();
 $sql = mysql_query("SELECT * FROM trjual a
LEFT JOIN tblpelanggan b ON b.id =a.id_customer WHERE a.id_trans= '".$id_pkb."'")or die (mysql_error());
		$rs = mysql_fetch_array($sql);

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post' onSubmit='return validasi_input(this)'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>PENJUALAN KASIR 1</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='' style='text-align:right;font-size: 30px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'><input type='hidden' name='cek_kasir' value='Yes'>Kode</td>        
        <td>
		<input type='hidden' class='inputform' name='kode_hidden' id='kode_hidden' value='$id_pkb'/>
		<input type='text' class='inputform' name='kode' id='kode' value='$id_pkb'disabled='disabled'/>
		</td>
		<td class='fonttext'>Tanggal</td>
        <td><div id='clock'></div></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Nama Customer</td>
        <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Autosuggest Nama Customer' readonly='readonly' />
		<input type='hidden' name='id_customer' id='id_customer'/>
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
        <td class='fonttext'>Sisa Deposit</td>
        <td><input type='text' class='inputform' name='sisadeposit' id='sisadeposit' value='' disabled='disabled'/></td>
     </tr>
	 <tr/><td colspan='6'> F2= Tambah Baris, F4=Simpan, Esc=Tutup,Tab=Pindah Kolom</td></tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr> 
        <td align='center' width='15%' class='fonttext'>Kategori</td>
        <td align='center' width='15%' class='fonttext'>Nama BARANG</td>
 
      	<td align='center' width='3%' class='fonttext'>Kuantum</td>
      	<td align='center' width='15%' class='fonttext'>Harga Satuan</td>
      	<td align='center' width='15%' class='fonttext'>Harga Barang (+ ppn 10%)</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>   
    </tr>
</thead>
</table>
<table align='center' width='100%'>
<tr>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</td>
</tr>
</table>
<table>
<tr>
<td class='fonttext' style='width:20px;'>
Keterangan
</td>
<td colspan=6 align='left'><textarea name='txtbrg' id='txtbrg' cols='117' rows='2' ></textarea></td></td>
</tr>
<tr>
<td class='fonttext' style='width:20px;'>Faktur</td>
<td><input type='text' class='inputform' name='faktur' id='faktur' style='text-align:right;align=right;' readonly='readonly'></td>
<td class='fonttext'>Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;'></td>
<td class='fonttext' >Kartu</td>
<td><input type='text' class='inputform' name='kartu' id='kartu' style='text-align:right;'></td>

</tr>
<tr>
<td class='fonttext'>Ongkir</td>
<td><input type='text' class='inputform' name='ongkir' id='ongkir' style='text-align:right;' onchange='hitungtotal()'></td>
<td class='fonttext' >Transfer</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'></td>
<td class='fonttext' >Deposit</td>
<td><input type='text' class='inputform' name='deposit' id='deposit' style='text-align:right;'></td>

</tr>
</table>
<hr/>
</form>
<table>
<tr>
<td>

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

	document.form2.total.value='<?=$rs['totalfaktur'];?>';
	document.form2.kode.value='<?=$rs['id_trans'];?>';
	document.form2.kode_hidden.value='<?=$rs['id_trans'];?>';
	//document.form2.tanggal.value='<?=$rs['tgl_trans'];?>';
	document.form2.nama.value='<?=$rs['namaperusahaan'];?>';
	document.form2.id_customer.value='<?=$rs['id'];?>';
	document.form2.telp.value='<?=$rs['telp1'];?>';
	document.form2.alamat.value='<?=$rs['alamat'];?>';
	
	document.form2.faktur.value='<?=$rs['faktur'];?>';
	document.form2.ongkir.value='<?=$rs['biaya'];?>';
	document.form2.tunai.value='<?=$rs['tunai'];?>';
	document.form2.transfer.value='<?=$rs['transfer'];?>';
	document.form2.kartu.value='<?=$rs['kartu'];?>';
//function untuk membuat shortcut di aplikasi kasirnya

document.onkeydown = function (e) {
                switch (e.keyCode) {
                    // esc
                    case 27:
                        //setTimeout('self.location.href="logout.php"', 0);
                        //alert('esc');
						tutup();
						break;
                    case 113:
                        //setTimeout('self.location.href="logout.php"', 0);
                        //alert('f2');
					//	addNewRow1();
						break;
                    // f4
                    case 115:
                        //setTimeout('self.location.href="help.php"', 0);
                        //alert('f3');
						cetak();
						break;
                }
                //menghilangkan fungsi default tombol
                //e.preventDefault();
            };

//---------------------------------------------	
//--focus on nama----
 
 

var baris1=1;//utk print
var baris2=1;//utk detil

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

td0.appendChild(generateKategori(baris1));
td1.appendChild(generateBARCODE(baris1));
//td0.appendChild(generateCari1(baris1));
td1.appendChild(generateId(baris1));
//td1.appendChild(generateNama(baris1));
td2.appendChild(generateQty(baris1));
td3.appendChild(generateHarga(baris1));
td4.appendChild(generateSUBTOTAL(baris1));
//td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
//row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5); 

document.getElementById('BARCODE['+baris1+']').focus();
 
baris1++;
}
//input array textarea di qty barang
function makeQty(v,a)
{
var q=0;
var txtArray=v.split(',');
	for(var i=0;i<txtArray.length;i++){
	//alert(txtArray[i]);
	q+=1;
	}
document.getElementById("Qty["+a+"]").value = q;
hitungjml(a);	
}
 

function generateId(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Id"+index+"";
idx.id = "Id["+index+"]";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateBARCODE(index,val='') {
var idx = document.createElement("textarea");
idx.type = "text";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE["+index+"]";
idx.size = "40";
idx.align = "center";
idx.cols = "50";
idx.rows = "4";
idx.readOnly = "readonly";
if (val!='')
	idx.value = val;
return idx;
}

function generateKodeBrg(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "kode_brg"+index+"";
idx.id = "kode_brg["+index+"]";
idx.size = "5";
idx.align = "center"; 
idx.readOnly = "readonly";
if (val!='')
	idx.value = val;
return idx;
}

function generateCari1(index) {
	var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "Cari1";
	idx.value = "...";
	idx.id = "Cari1["+index+"]";
	idx.size = "5";
	//idx.
	return idx;
}

function generateNama(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Nama"+index+"";
idx.id = "Nama["+index+"]";
idx.size = "15";
idx.readOnly = "readonly";
if (val!='')
	idx.value = val;
return idx;
}

function generateQty(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Qty"+index+"";
idx.id = "Qty["+index+"]";
idx.size = "3";
idx.style="text-align:right;";  
idx.readOnly = "readonly";

if (val!='')
	idx.value = val;
//idx.readOnly = "readonly";
return idx;
}

function generateHarga(index,val='') {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Harga"+index+"";
idx.id = "Harga["+index+"]";
idx.size = "8";
idx.readOnly = "readonly";
//idx.readOnly = "readonly";

idx.style="text-align:right;";
if (val!='')
	idx.value = val;
return idx;
}


function generateSUBTOTAL(index,val='') {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "SUBTOTAL"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "SUBTOTAL["+index+"]";
	idx.align= "right";
	idx.readOnly = "readonly";
	idx.style="text-align:right;";
	idx.size = "15";
	if (val!='')
	idx.value = val;
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


function generateKategori(index) {
var idx = document.createElement("select"); 
idx.name = "cmb_kategori_"+index+"";
idx.id = "cmb_kategori["+index+"]"; 
idx.disabled = "disabled";
<? $sql = mysql_query('select id_jenis, nm_jenis from jenis_barang where deleted=0 order by nm_jenis ');
  while ($row = mysql_fetch_assoc($sql)) {?>
    var opt = new Option('<?=$row['nm_jenis']?>', '<?=$row['id_jenis']?>');
	idx.options.add(opt);
<?  }
 ?>
//idx.innerHtml = "<=get_list_jenisbarang()?>";
return idx;
}
 

function hitungtotal()
{   
	var total=0;
    var ongkir=0;
	if(document.getElementById("ongkir").value == "") {
          document.getElementById("ongkir").value = 0;
	}
	ongkir=parseInt(document.getElementById("ongkir").value);
	
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
    document.getElementById("faktur").value = total;	
    document.getElementById("total").value = total + ongkir;	
    //document.getElementById("total").value = convertToRupiah(total);	
}

function hitungjml(a)
{
	if(document.getElementById("Qty["+a+"]").value == null) {
          document.getElementById("Qty["+a+"]").value = 0;
	}
	
	if(document.getElementById("Harga["+a+"]").value == null){
          document.getElementById("Harga["+a+"]").value = 0;
	}
	
	
	var ke1 = document.getElementById("Qty["+a+"]").value;
	var ke2 = document.getElementById("Harga["+a+"]").value;
	var jml=0;
	var total=0;
	
		jml=(ke1*ke2) ;
    
 	document.getElementById("SUBTOTAL["+a+"]").value = jml;	
 	hitungtotal();
}

function hitungrow() 
{
	document.form2.jum.value= baris1;
}




<?php $sql = mysql_query("SELECT * FROM trjual_print a WHERE a.id_trans= '".$id_pkb."'")or die (mysql_error());
		//$detail = mysql_fetch_array($sql);
	$i=1;
			while($rs1=mysql_fetch_array($sql)){
		?>
			addNewRow1(); 
			document.getElementById('cmb_kategori[<?=$i;?>]').value = '<?=$rs1['id_jenis'];?>';
			document.getElementById('BARCODE[<?=$i;?>]').value = '<?=$rs1['nama_barang'];?>';
			document.getElementById('Qty[<?=$i;?>]').value = '<?=$rs1['kuantum'];?>';
			document.getElementById('Harga[<?=$i;?>]').value = '<?=$rs1['harga'];?>';
			document.getElementById('SUBTOTAL[<?=$i;?>]').value = '<?=$rs1['harga_plus_ppn'];?>';
		<?php 
			$i++;
		}
		?>	
 




function tutup(){
var win=window.open("","_self");
win.close();
}

function cetak(){
	//	alert(baris1);
		//return false;
    //var namaValid    = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
        //var emailValid   = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
        var nama         = form2.nama.value;
        var totalfaktur  = form2.total.value;
        var tunai        = parseInt(form2.tunai.value);
        var transfer     = parseInt(form2.transfer.value);
        var kartu        = parseInt(form2.kartu.value);
        //var jeniskelamin = form2.jenis_kelamin.value;
        //var email        = form2.email.value;
        var pesan        = '';
        var temp_total   = tunai + transfer + kartu;
		
		if (nama == '') {
            pesan = 'Nama Customer tidak boleh kosong\n';
        } 
		
		
		if (totalfaktur < temp_total) {
            pesan = 'Pembayaran Melebihi Nilai Total Faktur\n temp=' +temp_total+', total='+totalfaktur;
        }
        
		
		
		/*
        if (nama != '' && !nama.match(namaValid)) {
            pesan += '-nama tidak valid\n';
        }
        
        if (jeniskelamin == '') {
            pesan += '-jenis kelamin harus dipilih\n';
        }
        
        if (email == '') {
            pesan += '-email tidak boleh kosong\n';
        }
        
        if (email !=''  && !email.match(emailValid)) {
            pesan += '-alamat email tidak valid\n';
        }
        */
        
		if (pesan==""){
			
			var arr_kategori=[];
				for (i=1;i<(baris1);i++){
					arr_kategori[i-1] = document.getElementById("cmb_kategori["+i+"]").value;	
				}
				 var duplicates = [];
						 
						 
					for (i = 0;i < arr_kategori.length; i++) {
					//	alert(arr_kategori.indexOf(arr_kategori[i], i+1));
					  if (duplicates.indexOf(arr_kategori[i]) === -1 && arr_kategori.indexOf(arr_kategori[i], i+1) !== -1) {
					//  if (duplicates.indexOf(arr_kategori[i]) === -1) {
						duplicates.push(arr_kategori[i]);
					  }
					}
					
					// console.log(arr_kategori);
					// console.log(duplicates);
				 if (duplicates.length >0 ){
					  pesan += 'Kategori tidak boleh sama \n'; 
				 }
				
				 
				
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
		document.form2.action="jual_simpan.php?id_trans=<?php echo $_GET['id']?>";
		document.form2.submit();
		}
		else
		{
		tutup();
		}
	}
return true;
    
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