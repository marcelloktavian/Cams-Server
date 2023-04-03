<head>
<title>PELUNASAN PENJUALAN ONLINE </title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script src="../../assets/js/time.js" type="text/javascript"></script>
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
 
	

    $sql = mysql_query("SELECT a.*,b.nama as dropshipper FROM olnso a
	LEFT JOIN mst_dropshipper b ON a.id_dropshipper =b.id WHERE a.id_trans= '".$_GET['ids']."'")or die (mysql_error());
	$rs = mysql_fetch_array($sql);
    $id_pelanggan = $rs['id_dropshipper'];

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post' onSubmit='return validasi_input(this)'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>DETAIL PELUNASAN PENJUALAN BELUM LUNAS</td>
		
		<td class='fontjudul'> TOTAL <input type='text' class='' name='total' id='total' value='' style='text-align:right;font-size: 30px;background-color:#FFE4B5;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Tanggal</td>
        <td><div id='clock'></div></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr>
        <td class='fonttext'>Nama Dropshipper</td>
        <td><input type='text' class='inputform' name='nama' id='nama' placeholder='Autosuggest Nama Customer'  />
		<input type='hidden' name='id_dropshipper' id='id_dropshipper'/>
		<input type='hidden' name='pelunasan' id='pelunasan'/>
		<input type='hidden' name='faktur_jual' id='faktur_jual'/>
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
		<!--
        <td class='fonttext'>Simpan Deposit</td>
        <td><input type='text' class='inputform' name='simpan_deposit' id='simpan_deposit' value='' /></td>
		-->
     </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='15%' class='fonttext'>Kode Transaksi </td>
    	<td align='center' width='10%' class='fonttext'>Tanggal Transaksi</td>
      	<td align='center' width='15%' class='fonttext'>Total Invoice</td>
      	<td align='center' width='15%' class='fonttext'>Sisa Piutang</td>
      	<td align='center' width='15%' class='fonttext'>Bayar Tunai</td>
      	<td align='center' width='15%' class='fonttext'>Bayar Bank</td>
      	<!--
		<td align='center' width='15%' class='fonttext'>Bayar Deposit</td>
		-->
    
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
<td class='fonttext' style='width:80px;'>Keterangan</td>
<td colspan='5'><input type='text' class='inputform' name='keterangan' id='keterangan' style='text-align:left;align=left;width:600px;' ></td>
</tr>
<tr>
<td class='fonttext' style='width:80px;'>Informasi Bank </td>
<td colspan='5'><input type='text' class='inputform' name='info' id='info' style='text-align:left;width:600px;' ></td>
</tr>
<tr>
<td class='fonttext' style='width:120px;'>Total Bayar</td>
<td><input type='text' class='inputform' name='faktur' id='faktur' style='text-align:right;align=right;'></td>
<td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Tunai </td>
<td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;'></td>
<td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Bank</td>
<td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'></td>
<!--
<td class='fonttext' style='width:80px;'>&nbsp;&nbsp;Deposit</td>
<td><input type='text' class='inputform' name='byrdeposit' id='byrdeposit' style='text-align:right;'></td>
-->
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
	document.form2.nama.value='<?=$rs['dropshipper'];?>';
	document.form2.id_dropshipper.value='<?=$rs['id_dropshipper'];?>';
	document.form2.pelunasan.value='<?=$rs['pelunasan'];?>';
	document.form2.faktur_jual.value='<?=$rs['total'];?>';
	
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
		var sisa       = data.sisa;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('Tgl['+a+']').value = tgl;
		document.getElementById('Invoice['+a+']').value = faktur;
		document.getElementById('Piutang['+a+']').value = piutang;	
		document.getElementById('Sisa['+a+']').value = sisa;	
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
//var td6 = document.createElement("td");

td0.appendChild(generateBARCODE(baris1));
//td0.appendChild(generateCari1(baris1));
td1.appendChild(generateId(baris1));
td1.appendChild(generateTgl(baris1));
td2.appendChild(generateInvoice(baris1));
td3.appendChild(generatePiutang(baris1));
td3.appendChild(generateSisa(baris1));
td4.appendChild(generateBayar(baris1));
td5.appendChild(generateBank(baris1));
//td6.appendChild(generateDeposit(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
//row.appendChild(td6);

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
idx.type = "hidden";
idx.name = "Piutang"+index+"";
idx.id = "Piutang["+index+"]";
idx.size = "20";
idx.style="text-align:right;";
idx.readOnly = "readonly";
return idx;
}

function generateSisa(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Sisa"+index+"";
idx.id = "Sisa["+index+"]";
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

function generateDeposit(index) {
    var idx = document.createElement("input");
	idx.name = "Deposit"+index+"";
	idx.id = "Deposit["+index+"]";
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
	var byr_deposit=0;
	var simpan_deposit=0;
	//for (var i=1; i<=baris1;i++){
		
		if(document.getElementById("Bayar["+1+"]").value == null) {
          document.getElementById("Bayar["+1+"]").value = 0;
	    }
		if(document.getElementById("Bank["+1+"]").value == null) {
          document.getElementById("Bank["+1+"]").value = 0;
	    } 
		/*
		if(document.getElementById("Deposit["+1+"]").value == null) {
          document.getElementById("Deposit["+1+"]").value = 0;
	    }
        */		
	    sisa= parseInt(document.getElementById("Sisa["+1+"]").value);
		tunai= parseInt(document.getElementById("Bayar["+1+"]").value);
		bank= parseInt(document.getElementById("Bank["+1+"]").value);
		//byr_deposit= parseInt(document.getElementById("Deposit["+1+"]").value);
		
		//total=tunai+bank+byr_deposit;
		total=tunai+bank;
		//simpan_deposit = (sisa)-(tunai+bank+byr_deposit);
	    
		//alert("sisa ="+sisa+",simpan_deposit="+simpan_deposit);
		
	/*
	if (simpan_deposit < 0){
	//dimasukan ke deposit
	document.getElementById("simpan_deposit").value = -simpan_deposit;
    }
	else{
	document.getElementById("simpan_deposit").value = 0;
    }
    */
	document.getElementById("faktur").value = total;	
    document.getElementById("total").value = total;	
    document.getElementById("transfer").value = bank;	
    document.getElementById("tunai").value = tunai;	
    //document.getElementById("byrdeposit").value = byr_deposit;	
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
//window.close();
var win=window.open("","_self");
win.close();

}

function cetak(){
        //var namaValid    = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
        //var emailValid   = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
        var nama         = form2.nama.value;
        var tunai        = parseInt(form2.tunai.value);
        var transfer     = parseInt(form2.transfer.value);
        //var byr_deposit  = parseInt(form2.byrdeposit.value);
        var sisa         = parseInt(document.getElementById("Sisa["+1+"]").value);
        //var simpan_deposit = parseInt(form2.simpan_deposit.value);
        var pelunasan    = parseInt(form2.pelunasan.value);
        var totalfaktur  = parseInt(form2.faktur_jual.value);
        var pesan        = '';
        //var temp_total   = tunai + transfer + byr_deposit;
        var temp_total   = tunai + transfer;
		
		//alert('pelunasan='+pelunasan+',totalfaktur='+totalfaktur+',Sisa='+sisa+',Simpan Deposit='+simpan_deposit+',temp_total='+temp_total);
		
		if (nama == '') {
            pesan = 'Nama Customer tidak boleh kosong\n';
        }
        
		if (sisa < temp_total) {
            pesan = 'Pembayaran Melebihi Nilai SISA PIUTANG\n total bayar=' +temp_total+', SISA PIUTANG='+sisa;
        }
		
        /*
        //validasi agar jangan entry pelunasan yang sudah lunas
		if (pelunasan == totalfaktur) {
            pesan = 'Faktur ini sudah lunas\n total pelunasan sebelumnya=' +pelunasan;
        }
        
        */
        
	if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	/*
	else if (sisa < temp_total) {
	    //alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    var answer_deposit = confirm('Pelunasan Melebihi Nilai Sisa PIUTANG!!\n Total Bayar=' +temp_total+', sisapiutang='+sisa+',Deposit='+simpan_deposit+',Mau simpan PELUNASAN dan simpan DEPOSITNYA???? ');
        if (answer_deposit)
		{	
		hitungrow();
		document.form2.action="piutang_simpan.php";
		document.form2.submit();
		}
		else
		{
		tutup();
		}
	}
	*/
	else
	{
	    var answer = confirm("Mau Simpan data dan cetak notanya????")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="piutang_simpan.php";
		document.form2.submit();
		}
		else
		{
		tutup();
		}
	}
return true;
    
}	

<?php 
	$sql_detail = "select a.id_trans,a.kode,a.tgl_trans,a.faktur,a.total,a.piutang,a.pelunasan,(a.piutang-a.pelunasan) as sisapiutang,b.nama from olnso a left join mst_dropshipper b on (a.id_dropshipper=b.id) where a.id_trans = '".$_GET['ids']."'";
    //var_dump($sql_detail);die;
	
	$sql1 = mysql_query($sql_detail);
    $i=1;
			while($rs1=mysql_fetch_array($sql1)){
		?>
			//addNewRow1();
			document.getElementById('Id['+<?=$i;?>+']').value = '<?=$rs1['id_trans'];?>';
			document.getElementById('BARCODE['+<?=$i;?>+']').value = '<?=$rs1['id_trans'];?>';
			document.getElementById('Tgl['+<?=$i;?>+']').value = '<?=$rs1['tgl_trans'];?>';
			document.getElementById('Invoice['+<?=$i;?>+']').value = '<?=$rs1['total'];?>';
			document.getElementById('Piutang['+<?=$i;?>+']').value = '<?=$rs1['piutang'];?>';			
			document.getElementById('Sisa['+<?=$i;?>+']').value = '<?=$rs1['sisapiutang'];?>';			
			document.getElementById('Bayar['+<?=$i;?>+']').value = 0;			
			document.getElementById('Bank['+<?=$i;?>+']').value = 0;			
			//document.getElementById('Deposit['+<?=$i;?>+']').value = 0;			
		<?php 
			$i++;
		}
		?>
    
	
		
</script>

</body>