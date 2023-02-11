<head>
<title>PELUNASAN PENJUALAN BELUM BAYAR</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<!--<script src="../../assets/js/time.js" type="text/javascript"></script>-->
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
    background-color:  #FFF8DC;
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
		$("#nama").autocomplete("piutang_customer.php", {
		width: 158
  });
  
   $("#nama").result(function(event, data, formatted) {
	var nama = document.getElementById("nama").value;
	$.ajax({
		url : 'piutang_ambilCustomer.php?nama='+nama,
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
 
	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
 
function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_trans from trpiutang order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -4);
	$bulan=substr($q['id_trans'], -6,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
	if($num==0 || $num==null || $bulan!=$bln_skrng)		
	{
		$temp = 1;
	}
	else
	{
		$temp=$num+1;
	}
	return $temp;
}

function getmonthyeardate2()
{
	$today = date('ym');
	return $today;
}

function getnewnotrxwait2()
{
	$temp=getmonthyeardate2();
	$temp2=getincrementnumber2();
	$id="TPB".$temp."".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
	return $id;
}	
//$id_registrasi = getnewnotrxwait();
$id_pkb = getnewnotrxwait2();

    $sql = mysql_query("SELECT * FROM trbelipiutang a
	LEFT JOIN tblsupplier b ON a.id_supplier =b.id WHERE a.id_trans= '".$_GET['ids']."'")or die (mysql_error());
		$rs = mysql_fetch_array($sql);
$alamat=$rs['alamat'];
?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post' onSubmit='return validasi_input(this)'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>DETAIL PELUNASAN PENJUALAN BELUM BAYAR</td>
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='' style='text-align:right;font-size: 30px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Kode</td>        
        <td>
		<input type='hidden' class='inputform' name='kode_hidden' id='kode_hidden' value='$id_pkb'/>
		<input type='text' class='inputform' name='kode' id='kode' value='$id_pkb'disabled='disabled'/>
		</td>
		<td class='fonttext'>Tanggal</td>
        <td><input type='text' class='inputform' name='tanggal' id='tanggal' value='' disabled='disabled'/></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Nama Customer</td>
        <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Autosuggest Nama Customer'  />
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
        <td><textarea name='textarea' id='alamat' cols='31' rows='2' disabled='disabled'>$alamat</textarea></td>
        <td class='fonttext'>&nbsp;</td>
        <td>&nbsp;</td>
     </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='15%' class='fonttext'>Kode Transaksi Jual</td>
    	<td align='center' width='10%' class='fonttext'>Tanggal Transaksi</td>
      	<td align='center' width='15%' class='fonttext'>Total Invoice</td>
      	<td align='center' width='15%' class='fonttext'>Sisa Piutang</td>
      	<td align='center' width='15%' class='fonttext'>Bayar Tunai</td>
      	<td align='center' width='15%' class='fonttext'>Bayar Bank</td>
    
    </tr>
</thead>
</table>
<table align='center' width='100%'>
<tr>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</td>
</tr>
<tr><td>TAB= pindah kolom</td></tr>
</table>
<table>
<tr>
<td class='fonttext' style='width:80px;'>Total Bayar</td>
<td><input type='text' class='inputform' name='faktur' id='faktur' style='text-align:right;align=right;'></td>
<td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;'></td>
<td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Bank</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'></td>
</tr>
</table>
<hr/>
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
	
	//data master
    document.form2.total.value='<?=$rs['totalfaktur'];?>';
	document.form2.kode.value='<?=$rs['id_trans'];?>';
	document.form2.kode_hidden.value='<?=$rs['id_trans'];?>';
	document.form2.tanggal.value='<?=$rs['tgl_trans'];?>';
	document.form2.nama.value='<?=$rs['namaperusahaan'];?>';
	document.form2.id_supplier.value='<?=$rs['id'];?>';
	document.form2.telp.value='<?=$rs['telp1'];?>';
	/*document.form2.alamat.value='<?=$rs['alamat'];?>';*/
	
	document.form2.faktur.value='<?=$rs['faktur'];?>';
	document.form2.tunai.value='<?=$rs['tunai'];?>';
	document.form2.transfer.value='<?=$rs['transfer'];?>';
	

	
function addbarcode(a)
{
var ke1 = document.getElementById("BARCODE["+a+"]").value;
	$.ajax({
		url : 'ambilDataJual.php',
		dataType: 'json',
		data: "barcode="+ke1,
		success: function(data) {
		var Id_Part    = data.id_trans;
		var tgl		   = data.tgl_trans;	
		var invoice    = data.faktur;	
		var piutang    = data.piutang;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('Tgl['+a+']').value = tgl;
		document.getElementById('Invoice['+a+']').value = faktur;
		document.getElementById('Piutang['+a+']').value = piutang;	
        }
	});	

//addNewRow1();
document.getElementById('Bayar['+a+']').focus();
//hitungjml(a);		
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

td0.appendChild(generateBARCODE(baris1));
//td0.appendChild(generateCari1(baris1));
td1.appendChild(generateId(baris1));
td1.appendChild(generateTgl(baris1));
td2.appendChild(generateInvoice(baris1));
td3.appendChild(generatePiutang(baris1));
td4.appendChild(generateBayar(baris1));
td5.appendChild(generateBank(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('BARCODE['+baris1+']').focus();
document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'addbarcode('+baris1+')');
//document.getElementById('Cari1['+baris1+']').setAttribute('onclick', 'popjasa('+baris1+')');
document.getElementById('Bayar['+baris1+']').setAttribute('onChange', 'hitungtotal()');
document.getElementById('Bank['+baris1+']').setAttribute('onChange', 'hitungtotal()');
baris1++;

}

function popjasa(a){
	
	var width  = 550;
 	var height = 400;
 	var left   = (screen.width  - width)/2;
 	var top    = (screen.height - height)/2;
  	var params = 'width='+width+', height='+height+',scrollbars=yes';
 	params += ', top='+top+', left='+left;
		window.open('popjual.php?row='+a+'','',params);
};

function generateId(index) {
var idx = document.createElement("input");
idx.type = "hidden";
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
idx.style="text-transform: uppercase;";
idx.readOnly = "readonly";
return idx;
}

function generateTgl(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Tgl"+index+"";
idx.id = "Tgl["+index+"]";
idx.size = "40";
idx.readOnly = "readonly";
return idx;
}

function generateInvoice(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Invoice"+index+"";
idx.id = "Invoice["+index+"]";
idx.size = "20";
idx.style="text-align:right;";
idx.readOnly = "readonly";
return idx;
}

function generatePiutang(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Piutang"+index+"";
idx.id = "Piutang["+index+"]";
idx.size = "20";
idx.style="text-align:right;";
idx.readOnly = "readonly";
return idx;
}

function generateBayar(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "Bayar"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "Bayar["+index+"]";
	idx.align= "right";
	idx.style="text-align:right;";
	idx.size = "20";
	return idx;
}

function generateBank(index) {
    //var idx = document.createElement("div");
    var idx = document.createElement("input");
	idx.name = "Bank"+index+"";
	//idx.name = "SUBTOTAL[]";
	idx.id = "Bank["+index+"]";
	idx.align= "right";
	idx.style="text-align:right;";
	idx.size = "20";
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


function generateDel1(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "del1"+index+"";
idx.id = "del1["+index+"]";
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

function hitungtotal()
{   
	var total=0;
    var tunai=0;
    var bank=0;
	
	//for (var i=1; i<=baris1;i++){
		
		if(document.getElementById("Bayar["+1+"]").value == null) {
          document.getElementById("Bayar["+1+"]").value = 0;
	    }
		if(document.getElementById("Bank["+1+"]").value == null) {
          document.getElementById("Bank["+1+"]").value = 0;
	    } 
		//var barcode=document.getElementById("BARCODE["+i+"]");
		//if 	(barcode != null)
	    //{   
	    //alert("barcode ="+barcode.toString())
		tunai= parseInt(document.getElementById("Bayar["+1+"]").value);
		bank= parseInt(document.getElementById("Bank["+1+"]").value);
		//}
		//else
		//return false;
		total=tunai+bank;
	//}
    document.getElementById("faktur").value = total;	
    document.getElementById("total").value = total;	
    document.getElementById("transfer").value = bank;	
    document.getElementById("tunai").value = tunai;	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
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

function tutup(){
window.close();
}

function cetak(){
        //var namaValid    = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
        //var emailValid   = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
        var nama         = form2.nama.value;
        //var totalfaktur  = parseInt(form2.total.value);
        var tunai        = parseInt(form2.tunai.value);
        var transfer     = parseInt(form2.transfer.value);
        var sisa         = parseInt(document.getElementById("Piutang["+1+"]").value);
        //var jeniskelamin = form2.jenis_kelamin.value;
        //var email        = form2.email.value;
        var pesan        = '';
        var temp_total   = tunai + transfer;
		//alert('sisa='+sisa);
		
		if (nama == '') {
            pesan = 'Nama Customer tidak boleh kosong\n';
        }
        
		if (sisa < temp_total) {
            pesan = 'Pembayaran Melebihi Nilai SISA PIUTANG\n total bayar=' +temp_total+', SISA PIUTANG='+sisa;
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
        
	if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else
	{
	    var answer = confirm("Mau Cetak notanya????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="piutang_pabrik_nota.php?id_trans=<?=$rs['id_trans'];?>";
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


<?php 
	$sql1 = mysql_query("select a.id_trans,a.id_transjual,b.tgl_trans,a.faktur,a.piutang,a.bayar,a.bank from trbelipiutang_detail a left join trbeli b on (a.id_transjual=b.id_trans) where a.id_trans = '".$_GET['ids']."'");
    $i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			//addNewRow1();
			document.getElementById('Id['+<?=$i;?>+']').value = '<?=$rs1['id_trans'];?>';
			document.getElementById('BARCODE['+<?=$i;?>+']').value = '<?=$rs1['id_transjual'];?>';
			document.getElementById('Tgl['+<?=$i;?>+']').value = '<?=$rs1['tgl_trans'];?>';
			document.getElementById('Invoice['+<?=$i;?>+']').value = '<?=$rs1['faktur'];?>';
			document.getElementById('Piutang['+<?=$i;?>+']').value = '<?=$rs1['piutang'];?>';			
			document.getElementById('Bayar['+<?=$i;?>+']').value = '<?=$rs1['bayar'];?>';			
			document.getElementById('Bank['+<?=$i;?>+']').value = '<?=$rs1['bank'];?>';			
		<?php 
			$i++;
		}
		?>
    
	
		
</script>

</body>