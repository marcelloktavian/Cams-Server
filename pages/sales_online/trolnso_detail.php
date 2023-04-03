<?php
  include "../../include/koneksi.php";
?>

<head>
  <title>ONLINE SALES</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
  <script src="../../assets/js/time.js" type="text/javascript"></script>
</head>

<style>
  body{
    background-color: Moccasin;
  }

  tanggal{
    color: maroon;
    margin-left: 40px;
  }
</style>

<script>
  $(document).ready(function(){
    $("#dropshipper").autocomplete("lookup_dropshipper.php?",{
      width: 158
    });

    $("#dropshipper").result(function(event, data, formatted){
      var nama_ds = $('#dropshipper').val();

      for(let i = 0; i< nama_ds.length; i++){
        var did = nama_ds.split(':');

        if(did[0]=="") continue;
        var id_d=did[0];
      }

      $.ajax({
        url: 'lookup_dropshipper_ambil.php?id='+id_d,
        dataType: 'json',
        data: 'nama='+formatted,
        success: function(data){
          var id_dropshipper = data.id;
            $('#id_dropshipper').val(id_dropshipper);
          var disc_dropshipper = data.disc;
            $('#disc_dropshipper').val(disc_dropshipper);
          var deposit = data.trdeposit;
            $('#saldo_deposit').val(deposit);
        }
      });
    });

    $('#region').autocomplete("lookup_address.php?",{
      width: 358
    });

    $('#region').result(function(event, data, formatted){
      var nama_rg = $('#region').val();

      for(var i=0;i<nama_rg.length;i++){
        var id = nama_rg.split(':');
        if (id[0]=="") continue;
        var id_rg=id[0];
      }

      $.ajax({
        url : 'lookup_address_ambil.php?id='+id_rg,
        dataType: 'json',
        data: "nama="+formatted,
        success: function(data) {
        var id_address  = data.id;
          $('#id_address').val(id_address);
        }
      });
    });

    $("#expedition").autocomplete("lookup_expedition.php?", {
      width: 158
    });
    
    $("#expedition").result(function(event, data, formatted) {
    var nama_exp = document.getElementById("expedition").value;
    
    for(var j=0;j<nama_exp.length;j++){
      var e_id = nama_exp.split(':');
      if (e_id[0]=="") continue;
      var id_exp=e_id[0];
    }
    
    $.ajax({
      url : 'lookup_expedition_ambil.php?id='+id_exp,
      dataType: 'json',
      data: "nama="+formatted,
      success: function(data) {
      var id_expedition  = data.id;
        $('#id_expedition').val(id_expedition);
          }
      });
    });
  });
</script>

<?php

function getmonthyeardate(){
  return (date('ym'));
}

function getincrementnumber2(){
	$q = mysql_fetch_array( mysql_query('select id_trans from olnso order by id_trans desc limit 0,1'));

	$kode=substr($q['id_trans'], -5);
	$bulan=substr($q['id_trans'], -7,2);
	$bln_skrng=date('m');
	$num=(int)$kode;

	if($num==0 || $num==null || $bulan!=$bln_skrng){$temp = 1;}
	else{$temp=$num+1;}

	return $temp;
}

function getmonthyeardate2(){
	$today = date('ym');
	return $today;
}

function getnewnotrxwait2(){
	$temp=getmonthyeardate2();
	$temp2=getincrementnumber2();
	$id="OLN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
}	

$id_pkb = getnewnotrxwait2();

?>

<body>
  <form id="form2" name="form2" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">ADD SALES</td>
        <td class="fontjudul">TOTAL <input type="text" class="" name="total" id="total" style="text-align:right; font-size:30px; background-color:white; height:40px; border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;" /></td>
        <td class="fontjudul">TOTAL QTY <input type="text" class="" name="totalqty" id='totalqty' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' /></td>
        <td><input type='hidden' class='' name='total_blmdisc' id='total_blmdisc' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' /><input type='hidden' name='totalhidden' id='totalhidden'/></td>
      </tr>
    </table>

    <hr />

    <table width="100%" cellspacing="0" cellpadding="0">
      <tr>
      <td class='fonttext'>Ref.Code</td>
        <td><input type='text'  id='ref_code' name='ref_code' placeholder='ID WEBSITE'  > ( Harap kosongkan/Jangan diisi nol bila tidak ada no.websitenya )</td>
        <td class='fonttext'>DROPSHIPPER</td>
        <td><input type='text' class='inputform' name='dropshipper' id='dropshipper' placeholder='Autosuggest Dropshipper'  /><input type='hidden' name='id_dropshipper' id='id_dropshipper'/></td>
      </tr>
      <tr height=1>
        <td colspan=4></td>
      </tr>
      <tr>
        <td class='fonttext'>Tanggal</td>
        <td><div id='clock'></div></td>
        <td class='fonttext'>Disc.DROPSHIPPER</td>
        <td><input type='text' class='inputform' name='disc_dropshipper' id='disc_dropshipper' placeholder='discount dropshipper' /></td>
      </tr>
      <tr height='1'>
        <td colspan='4'><hr/></td>
      </tr>
      <tr>
        <td class='fonttext'>Name</td>
        <td><input type='text' class='inputform' name='nama' id='nama' 	placeholder='Nama Penerima'  />
        <td class='fonttext'>Phone</td>
        <td><input type='text' class='inputform' name='telp' id='telp' 	placeholder='Telp' /></td>
      </tr>
      <tr height='1'>
        <td colspan='4'></td>
      </tr>
      <tr>
        <td class='fonttext'>Postal Address</td>
        <td><textarea name='alamat' id='alamat' cols='40' rows='3' placeholder='Alamat Kirim (Jalan,No)' ></textarea></td>
        <td class='fonttext'>REGION</td>
        <td><input type='text' class='inputform' name='region' id='region' placeholder='Autosuggest Kecamatan'  /><input type='hidden' name='id_address' id='id_address'/></td>               
      </tr>
      <tr height="1">
        <td colspan="6"><hr /></td>
      </tr>
    </table>

    <table align='center' width='100%' id='tbl_1'>
      <thead>
        <tr>
          <td align='center' width='15%' class='fonttext'>Code</td>
          <td align='center' width='25%' class='fonttext'>Products</td>
          <td align='center' width='10%' class='fonttext'>Price@</td>
          <td align='center' width='5%' class='fonttext'>Qty</td>
          <td align='center' width='5%' class='fonttext'>Size</td>
          <td align='center' width='10%' class='fonttext' hidden>Disc@</td>
          <td align='center' width='25%' class='fonttext'>Subtotal</td>
          <td align='center' width='25%' class='fonttext'>Nett</td>
          <td align='center' width='5%' class='fonttext'>Hapus</td>    
        </tr>
      </thead>
    </table>

    <table>
      <td><p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p></td>
    </table>

    <table>
      <tr>
        <td class='fonttext'>Expedition</td>
          <td><input type='text' class='inputform' name='expedition' id='expedition' placeholder='Autosuggest Ekspedisi' /><input type='hidden' name='id_expedition' id='id_expedition'/></td>
          <td class='fonttext'>Exp.Code</td>
          <td><input type='text' style='text-transform: uppercase;'  class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
      </tr>
      <tr>
        <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' onkeyup='hitungtotal();'/></td>
        <td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
      </tr>
      <tr>
        <td class='fonttext' style='width:20px;'>
        Keterangan
        </td>
        <td colspan=1 align='left'><textarea name='txtbrg' id='txtbrg' cols='55' rows='2' ></textarea></td>
      </tr>
      <tr>
        <td class='fonttext'>Disc.Faktur </td>
        <td><input type='text' class='inputform' name='disc_faktur' id='disc_faktur' style='text-align:right;' onkeyup='hitungtotal();'></td>
      </tr>
      <tr>
        <td class='fonttext'>Tunai </td>
        <td><input type='text' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'><input type='hidden' class='inputform' name='faktur' id='faktur' /></td>
      </tr>
      <tr>
        <td class='fonttext' >Tf.Bank</td>
        <td><input type='text' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
        <td class='fonttext' >&nbsp;</td>
      </tr>
      <tr>
        <td class='fonttext' >Bayar dg Deposit</td>
        <td><input type='text' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;' onkeyup='validasiPembayaran(); hitungpiutang();'><input type='text' readonly placeholder='Saldo Deposit' name='saldo_deposit' id='saldo_deposit'/><input type='hidden' class='inputform' name='simpan_deposit' id='simpan_deposit' style='text-align:right;'></td>
        <td class='fonttext' hidden>Piutang</td>
        <td><input type='text' class='inputform' name='piutang' id='piutang' style='text-align:right;' hidden></td>
      </tr>
    </table>

  </form>

  <table>
    <tr>
      <td><p><input type='image' value='Tambah Baris' src='../../assets/images/tambah_baris.png'  id='baru'  onClick='addNewRow1()'/></p></td>
      <td><p align='center'><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p></td>
      <td><p><input type='image' value='batal' src='../../assets/images/batal.png'  id='baru'  onClick='tutup()'/></p></td>
    </tr>
  </table>
</body>

<script>

function validasiPembayaran(){
  if(document.getElementById("byr_deposit").value == "") {
    document.getElementById("byr_deposit").value = 0;
	}

  const totalValidasi = document.getElementById('totalhidden');
  const transferValidasi = document.getElementById('transfer');
  const tunaiValidasi = document.getElementById('tunai');
  const depositValidasi = document.getElementById('byr_deposit');

  transferValidasi.value = parseInt(totalValidasi.value) - parseInt(tunaiValidasi.value) - parseInt(depositValidasi.value);
};

function cetak(){
  var pesan           = '';
  var nama_input      = form2.nama.value;
  var id_dropshipper  = form2.id_dropshipper.value;
  var id_address      = form2.id_address.value;
  var id_expedition   = form2.id_expedition.value;
  var exp_code   		  = form2.exp_code.value;
  var totalfaktur     = parseInt(form2.totalhidden.value);
  var tunai           = parseInt(form2.tunai.value);
  var transfer        = parseInt(form2.transfer.value);
  var simpan_deposit  = parseInt(form2.simpan_deposit.value);
  var byr_deposit     = parseInt(form2.byr_deposit.value);
  var temp_total      = tunai + transfer;

  for (var i=1; i<=baris1;i++){
    var tmpsubtotal=0;
    var barcode=document.getElementById("BARCODE"+i+"");
    if (barcode != null){   
      if(document.getElementById("SUBTOTAL"+i+"").value == "") {
        var subtotal = 0;}
      else{
        var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
        var qty = document.getElementById("Qty"+i+"").value;
        var harga = document.getElementById("Harga"+i+"").value;		
        var tmpsubtotal= parseInt(qty)*parseInt(harga);
      }
      if (tmpsubtotal != subtotal) {
        pesan = 'Maaf,Subtotal ada yang salah!! Silakan lakukan pengecekan ulang.';
      }
    }
	}

  for (var i=1; i<=baris1;i++){
    if (document.getElementById("SUBTOTAL"+i+"")!=null) {
      console.log(document.getElementById("NamaBrg"+i+"").value);

      var subtotal = document.getElementById("Qty"+i+"").value;
      var stok = document.getElementById("Stok"+i+"").value;

      if(parseInt(subtotal) > parseInt(stok)){
        pesan += 'Cek stok barang\n';
          break;
      }
    }
  }

	if (nama_input == '') {
    pesan = 'Nama Penerima tidak boleh kosong\n';
  }
	if (id_dropshipper == '') {
    pesan = 'Dropshipper tidak boleh kosong\n';
  }
	if (id_address == '') {
    pesan = 'Region tidak boleh kosong\n';
  }
	if (id_expedition == '') {
    pesan = 'Ekspedisi tidak boleh kosong\n';
  }
  if(transfer + tunai + byr_deposit != totalfaktur){
    pesan = "Pembayaran belum sesuai dengan GRAND TOTAL";
  }

	$.ajax({
    type: "POST",
    url: "ajax_expcode.php",
    data: {expcode: exp_code},
    dataType: "text",
    success: function (res) {
    console.log(res);

    if (res=='0') {
      if (pesan != '') {
          alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
          return false;
    }
    else if (totalfaktur < temp_total) {
      var answer_deposit = confirm('Pembayaran Melebihi Nilai Total Faktur!!\n Total Bayar=' +temp_total+', totalfaktur='+totalfaktur+',Deposit='+simpan_deposit+',Mau simpan PENJUALAN dan simpan DEPOSITNYA???? ');
        if (answer_deposit){	
					hitungrow();
					document.form2.action="trolnso_save.php";
					document.form2.submit();
				}
				else{
					tutup();
				}	
			}
			else{ 
				var answer = confirm("Mau Simpan data dan cetak notanya????")
				if (answer){	
					hitungrow() ;
					document.form2.action="trolnso_save.php";
					document.form2.submit();
				}
				else{}
      }
    }else{alert('Kode Expedisi sudah dipakai di transaksi lain!!');}

    }
	});
}
  function get_products(a){  
    $("#BARCODE"+a+"").autocomplete("lookup_products.php?", {
      width: 178
    });

    $("#BARCODE"+a+"").result(function(event, data, formatted) {
      var nama = document.getElementById("BARCODE"+a+"").value;
      for(var i=0;i<nama.length;i++){
        var id = nama.split(':');
        if (id[0]=="") continue;
        var id_pd=id[0];
      }

      $.ajax({
        url : 'lookup_products_ambil.php?id='+id_pd,
        dataType: 'json',
        data: "nama="+formatted,
        success: function(data) {
        var products  = data.nama;
          $("#NamaBrg"+a+"").val(products);
        var harga_pd  = data.harga;
          $("#Harga"+a+"").val(harga_pd);
        var id_products  = data.id;
          $("#IDP"+a+"").val(id_products);
        var product_size  = data.size;
          $("#Size"+a+"").val(product_size);

        $.ajax({
          url : 'lookup_productstok_ambil.php?id='+id_pd,
          dataType: 'json',
          data: "nama="+formatted,
          success: function(data2) {
            var stok  = data2.stok;
            $("#Stok"+a+"").val(stok);
            $("#Qty"+a+"").focus();
            }
        });	
        }
      });	
    });
  }

var baris1=1;

addNewRow1();
function addNewRow1() {
  var tbl = document.getElementById("tbl_1");
  var row = tbl.insertRow(tbl.rows.length);
  row.id = 't1'+baris1;

  var td0 = document.createElement("td");
  var td1 = document.createElement("td");
  var td2 = document.createElement("td");
  var td3 = document.createElement("td");
  var td4 = document.createElement("td");
  var td5 = document.createElement("td");
  var td6 = document.createElement("td");
  var td7 = document.createElement("td");
  var td8 = document.createElement("td");

  td5.setAttribute('hidden', true);

  td0.appendChild(generateId(baris1));
  td0.appendChild(generateBARCODE(baris1));
  td1.appendChild(generateIDP(baris1));
  td1.appendChild(generateNama(baris1));
  td2.appendChild(generateHarga(baris1));
  td3.appendChild(generateQty(baris1));
  td3.appendChild(generateStok(baris1));
  td4.appendChild(generateSize(baris1));
  td5.appendChild(generateDisc(baris1));
  td6.appendChild(generateSUBTOTAL(baris1));
  td7.appendChild(generateNETT(baris1));
  td8.appendChild(generateDel1(baris1));

  row.appendChild(td0);
  row.appendChild(td1);
  row.appendChild(td2);
  row.appendChild(td3);
  row.appendChild(td4);
  row.appendChild(td5);
  row.appendChild(td6);
  row.appendChild(td7);
  row.appendChild(td8);

  document.getElementById('BARCODE'+baris1+'').focus();
  document.getElementById('BARCODE'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
  document.getElementById('Qty'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
  document.getElementById('Disc'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
  document.getElementById('NETT'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
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
  idx.name = "BARCODE"+index+"";
  idx.id = "BARCODE"+index+"";
  idx.size = "15";
  idx.align = "center";
  return idx;
}

function generateIDP(index) {
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
  idx.name = "NamaBrg"+index+"";
  idx.id = "NamaBrg"+index+"";
  idx.size = "40";
  idx.readOnly = "readonly";
  return idx;
}
function generateHarga(index) {
  var idx = document.createElement("input");
  idx.type = "text";
  idx.name = "Harga"+index+"";
  idx.id = "Harga"+index+"";
  idx.size = "8";
  idx.readOnly = "readonly";
  return idx;
}

function generateQty(index) {
  var idx = document.createElement("input");
  idx.type = "text";
  idx.name = "Qty"+index+"";
  idx.id = "Qty"+index+"";
  idx.size = "3";
  idx.style="text-align:right;";
  return idx;
}

function generateStok(index) {
  var idx = document.createElement("input");
  idx.type = "hidden";
  idx.name = "Stok"+index+"";
  idx.id = "Stok"+index+"";
  idx.size = "3";
  idx.style="text-align:right;";
  return idx;
}

function generateSize(index) {
  var idx = document.createElement("input");
  idx.type = "text";
  idx.name = "Size"+index+"";
  idx.id = "Size"+index+"";
  idx.size = "3";
  idx.style="text-align:right;";
  return idx;
}

function generateDisc(index) {
  var idx = document.createElement("input");
  idx.type = "text";
  idx.name = "Disc"+index+"";
  idx.id = "Disc"+index+"";
  idx.size = "8";
  idx.style="text-align:right;";
  idx.readOnly = "readonly";
  return idx;
}

function generateSUBTOTAL(index) {
  var idx = document.createElement("input");
  idx.name = "SUBTOTAL"+index+"";
  idx.id = "SUBTOTAL"+index+"";
  idx.align= "right";
  idx.readOnly = "readonly";
  idx.style="text-align:right;";
  idx.size = "25";
  return idx;
}


function generateNETT(index) {
  var idx = document.createElement("input");
  idx.name = "NETT"+index+"";
  idx.id = "NETT"+index+"";
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

function delRow1(id){ 
	var el = document.getElementById("t1"+id);
	baris1-=1;
	el.parentNode.removeChild(el);;
    hitungtotal();
	return false;
}

function validasi(){
  var pesan='';
  var id_dropshipper  = form2.id_dropshipper.value;

  if (id_dropshipper == '') {
    pesan = 'Dropshipper tidak boleh kosong\n';
    form2.dropshipper.focus;
  }
    
  if (pesan != '') {
    alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
    return false;
  }    	
}

function hitungpiutang(){ 
  var total=0;
  var totalqty=0;
  var ongkir=0;
  var discfaktur=0;
  var tunai=0;
  var transfer=0;
  var sisa=0;
  var byr_deposit=0;
  var total_blmdisc=0;

	if(document.getElementById("tunai").value == "") {
    document.getElementById("tunai").value = 0;
	}

	tunai=document.getElementById("tunai").value;
	var tunai_murni=parseInt(tunai.replace(".", ""));

	if(document.getElementById("transfer").value == "") {
    document.getElementById("transfer").value = 0;
	}

	transfer=document.getElementById("transfer").value;
	var transfer_murni=parseInt(transfer.replace(".", ""));

  if(document.getElementById("exp_fee").value == "") {
    document.getElementById("exp_fee").value = 0;
	}

	ongkir=document.getElementById("exp_fee").value;
	var ongkir_murni=parseInt(ongkir.replace(".", ""));
	
	if(document.getElementById("disc_faktur").value == "") {
    document.getElementById("disc_faktur").value = 0;
	}

	discfaktur=document.getElementById("disc_faktur").value;
	var discfaktur_murni=parseInt(discfaktur.replace(".", ""));

	if(document.getElementById("byr_deposit").value == "") {
    document.getElementById("byr_deposit").value = 0;
	}

	byr_deposit=document.getElementById("byr_deposit").value;
	var byr_deposit_murni=parseInt(byr_deposit.replace(".", ""));

	if(document.getElementById("disc_dropshipper").value == "") {
    document.getElementById("disc_dropshipper").value = 0;
	}
	else{
    var disc_dropshipper=parseFloat(document.getElementById("disc_dropshipper").value);
	}

	for (var i=1; i<=baris1;i++){

		var barcode=document.getElementById("BARCODE"+i+"");
		if 	(barcode != null){   
			if(document.getElementById("SUBTOTAL"+i+"").value == "") {
        var subtotal = 0;}
			else{
        var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
        var qty = document.getElementById("Qty"+i+"").value;
			}
      total+= Math.ceil(parseInt(subtotal)*(1-disc_dropshipper));
      totalqty+= parseInt(qty);
			total_blmdisc+= parseInt(subtotal);
		}
	}
	total=total+ongkir_murni-discfaktur_murni;
	total_blmdisc=total_blmdisc+ongkir_murni - discfaktur_murni;
	sisa = (total)-(tunai_murni+transfer_murni+byr_deposit_murni);

	if (sisa < 0){
    document.getElementById("simpan_deposit").value = -sisa;
    document.getElementById("piutang").value = 0;
  }
	else{
    document.getElementById("piutang").value = sisa;
    document.getElementById("simpan_deposit").value = 0;
  }

	document.getElementById("totalhidden").value = total;	   
	document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalqty").value = totalqty;
  document.getElementById("total_blmdisc").value = total_blmdisc.toLocaleString('IND', {style: 'currency', currency: 'IDR'});	
}

$(document).ready(function(){
  document.getElementById('tunai').setAttribute('onKeyUp', 'validasiPembayaran()');
});

function hitungtotal(){
	var total=0;
	var totalqty=0;
	var sisa_tf=0;
	var discfaktur=0;
  var total_blmdisc=0;

	if(document.getElementById("exp_fee").value == "") {
    var ongkir=0;
	}
	else{
    ongkir=parseInt(document.getElementById("exp_fee").value);
	}

	if(document.getElementById("byr_deposit").value == "") {
    var byr_deposit=0;
	}
	else{
    var byr_deposit=parseInt(document.getElementById("byr_deposit").value);
	}

	if(document.getElementById("saldo_deposit").value == "") {
    var saldo_deposit=0;;
	}
	else{
    var saldo_deposit=parseInt(document.getElementById("saldo_deposit").value);
	}

	if(document.getElementById("disc_dropshipper").value == "") {
    document.getElementById("disc_dropshipper").value = 0;
	}
	else{
    var disc_dropshipper=parseFloat(document.getElementById("disc_dropshipper").value);
	}

  if(document.getElementById("disc_faktur").value == "") {
    document.getElementById("disc_faktur").value = 0;
	}

	discfaktur=document.getElementById("disc_faktur").value;
	var discfaktur_murni=parseInt(discfaktur.replace(".", ""));

  for (var i=1; i<=baris1;i++){
    var barcode=document.getElementById("BARCODE"+i+"");

    if (barcode != null){   
      if(document.getElementById("SUBTOTAL"+i+"").value == "") {
        var subtotal = 0;}
        else{
        var subtotal = document.getElementById("SUBTOTAL"+i+"").value;
        var qty = document.getElementById("Qty"+i+"").value;
      }
		total+= Math.ceil(parseInt(subtotal)*(1-disc_dropshipper));
		totalqty+= parseInt(qty);
		total_blmdisc+= parseInt(subtotal);
    }
	}

  document.getElementById("faktur").value = total;	

	total=total+ongkir-discfaktur_murni;
  total_blmdisc=total_blmdisc + ongkir - discfaktur_murni;

	sisa_tf=total-saldo_deposit;

  if (saldo_deposit >= total)  {
    document.getElementById("byr_deposit").value = total;
    document.getElementById("transfer").value = 0;
    document.getElementById("tunai").value = 0;
	}
	
	else if (saldo_deposit < total){
    totalakhir=sisa_tf;
    document.getElementById("byr_deposit").value = saldo_deposit;
    document.getElementById("transfer").value = totalakhir-$('#tunai').val();	
	}
	
	if (saldo_deposit <= 0){
    document.getElementById("transfer").value = total-saldo_deposit-$('#tunai').val();	
  }

	document.getElementById("totalhidden").value = total;	
  document.getElementById("tunai").value = 0;	
  document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
	document.getElementById("totalqty").value = totalqty;
  document.getElementById("total_blmdisc").value = total_blmdisc.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
}

function hitungjml(a){
	validasi();

	if(document.getElementById("Qty"+a+"").value == "") {
		var qty = 0;	    
	}
	else{
    var qty = document.getElementById("Qty"+a+"").value;
	}

	if(document.getElementById("Harga"+a+"").value == ""){
    var harga = 0;
	}
	else{
    var harga = document.getElementById("Harga"+a+"").value;
	}
	
	if(document.getElementById("Disc"+a+"").value == ""){
    var disc = 0;
	}
	else{
		var disc = document.getElementById("Disc"+a+"").value;
	}

	var jml=0;
	var total=0;

  jml=qty*(harga-disc);

  document.getElementById("SUBTOTAL"+a+"").value = jml;	

  var disc_dropshipper = document.getElementById("disc_dropshipper").value;
  var tot = Math.ceil(parseInt(jml)*(1-disc_dropshipper));
  document.getElementById("NETT"+a+"").value = tot;	

  hitungtotal();
}

function hitungrow(){
	document.form2.jum.value= baris1;
}

function tutup(){
  window.close();
}

function convertToRupiah(objek) {
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