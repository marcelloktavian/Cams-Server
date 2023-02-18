<head>
<title>CASH RECEIPT</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>

<style>
body {
    background-color:#ABCDAE ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
</head>
<body>
<?php
error_reporting(0);
//connection with database with PDO
require "../../include/config.php";
    $id = $_GET["id"];
    $res = $db->prepare(" SELECT * FROM cashreceipt where id=? ");
    $res->execute(array($id));
    $data = $res->fetch(PDO::FETCH_ASSOC);
?>
<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
      <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
    	
		<td class='fontjudul'>Cash Receipt</td>
        <!-- debet -->
		<td class='fontjudul'> Debet
		<input type='text' class='' name='debet_m' id='debet_m' value='<?php echo "IDR ".number_format($data['total_debet'],2,'.',',') ?>' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- debet hidden -->
		<input type="hidden" name="debet" id="debet" value='<?=$data['total_debet']?>'>

        <!-- Kredit -->
        <td class='fontjudul'> Kredit <input type='text' class='' name='kredit_m' id='kredit_m' value='<?php echo "IDR ".number_format($data['total_kredit'],2,'.',',') ?>' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<!-- Kredit hidden -->
		<input type="hidden" name="kredit" id="kredit" value='<?=$data['total_kredit']?>'>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
	<tr>
		<td class="fonttext">Kas Bon 1</td>
        <td><input type='text' class='inputform' name='kode' id='kode' placeholder='Kas Bon 1' value='<?=$data['kode']?>' />
        </td>
		<td class="fonttext">Kas Bon 2</td>
        <td><input type='text' class='inputform' name='kode2' id='kode2' placeholder='Kas Bon 2' value='<?=$data['kasbon2']?>' />
        </td>
        
    </tr>
    <tr>
		<td class="fonttext">Tanggal</td>
        <td><input type='date' class='inputform' name='tanggal' id='tanggal' value='<?=$data['tanggal']?>' />
        </td>
		<td  class="fonttext">Keterangan</td>
        <td ><textarea  name="txtbrg" id="txtbrg" cols="50" rows="2" placeholder="Keterangan"><?=$data['keterangan']?></textarea></td>
    </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
     <tr height='5'>
     <td colspan='6'></td>
     </tr>
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='15%' class='fonttext'>Account</td>
    	<td align='center' width='15%' class='fonttext'>Uraian</td>
        <td align='center' width='15%' class='fonttext'>Bukti Kas</td>
		<td align='center' width='10%' class='fonttext'>Debet</td>
		<td align='center' width='10%' class='fonttext'>Kredit</td>
		<td align='center' width='5%'  class='fonttext'>Hapus</td>
    
    </tr>
</thead>
<thead>
    <tr>
   
        <td colspan=3></td>
		<td align='center' width='10%' class='fonttext'><input type="hidden" name="debet_tot" id="debet_tot">
        <input type="text" name="debet_total" id="debet_total" size="20"  style="text-align: right;" disabled value='<?=$data['total_debet']?>'></td>
		<td align='center' width='10%' class='fonttext'><input type="hidden" name="kredit_tot" id="kredit_tot">
        <input type="text" name="kredit_total" id="kredit_total" size="20"  style="text-align: right;" disabled value='<?=$data['total_kredit']?>'></td>
		<td align='center' width='5%'  class='fonttext'></td>
    
    </tr>
</thead>
<hr>
<table>
<tbody><tr>
<td class="fonttext" style="width:20px;">

</td>
</tr>
</tbody></table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
<input type='hidden' id='iddetbiaya' name='iddetbiaya'>
<div id='myDiv'></div>
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
<p><input type='image' value='batal' src='../../assets/images/batal.png'  id='tutup' onClick='tutup()' /></p>
</td>
</tr>

</table>

<script type="text/javascript">
//autocomplete pada grid
function get_products(a){  
   $("#noakun"+a+"").autocomplete("CashReceiptLov.php?", {
	width: 178});
//   console.log('here'+a)  ;
   $("#noakun"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("noakun"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id1 = nama.split(')');
        var id2 = id1[0].replace("(", "").split(':');
		var id_parent = id2[0];
        var id_detail = id2[1];
	}
	// console.log(id_pd);
	$.ajax({
		url : 'CashReceiptLovdet.php?idparent='+id_parent+'&iddetail='+id_detail,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
            var idakunparent  = data.idakunparent;
			$("#idakunparent"+a+"").val(idakunparent);
            var idakundet  = data.idakundet;
			$("#idakundet"+a+"").val(idakundet);
			var noakun  = data.noakun;
			$("#noakun"+a+"").val(noakun);
			var akun  = data.nama;
			$("#akun"+a+"").val(akun);
			
            var jenis = data.jenis;
            if (jenis == 'Debet') {
                $("#debet"+a+"").attr("disabled", false);
                $("#kredit"+a+"").attr("disabled", true);
                $("#operasionalBtn"+a+"").attr("disabled", true);
            } else {
                $("#debet"+a+"").attr("disabled", true);
                $("#kredit"+a+"").attr("disabled", false);
                $("#operasionalBtn"+a+"").attr("disabled", false);
            }
            var tgl = $("#tanggal").val().split('-');
            document.getElementById('bukti'+a+'').value='AK'+tgl[1]+tgl[0].substring(2,4)+'/';
            $("#uraian"+a+"").focus();
        }
	});	
			
	});
}  	

var baris1=1;
// addNewRow1();
document.getElementById('kode').focus();
function addNewRow1() 
{
var tbl = document.getElementById("tbl_1");
var row = tbl.insertRow(tbl.rows.length-1);
row.id = 't1'+baris1;

var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");

td0.appendChild(generateIDDetail(baris1));
td0.appendChild(generateIDAkunParent(baris1));
td0.appendChild(generateIDAkunDet(baris1));
td0.appendChild(generateNoAkun(baris1));
td0.appendChild(generateAccountBalance(baris1));
td1.appendChild(generateAkun(baris1));
td1.appendChild(generateUraian(baris1));
td1.appendChild(generateOperasional(baris1));
td2.appendChild(generateBukti(baris1));
td3.appendChild(generateDebet(baris1));
td4.appendChild(generateKredit(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('idakundet'+baris1+'').focus();
document.getElementById('noakun'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('BalanceBtn'+baris1+'').setAttribute('onclick', 'popBalance('+baris1+')');
document.getElementById('operasionalBtn'+baris1+'').setAttribute('onclick', 'popOperasional('+baris1+')');
document.getElementById('debet'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('kredit'+baris1+'').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('del1'+baris1+'').setAttribute('onkeydown', 'addNewRow1()');
get_products(baris1);
baris1++;
}

function generateIDDetail(index){
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "idDet"+index+"";
idx.id = "idDet"+index+"";
idx.size="15";
idx.align = "left";
return idx;
}

function popBalance(a){
	var width  = 1200;
	var height = 600;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height+',scrollbars=yes';
	// var cust = $("#kodesupp").val();
	// var barang = $("#idbarang"+a+"").val();
	params += ', top='+top+', left='+left;
	window.open('list_accountbalance.php?baris='+a,'',params);
};

function popOperasional(a){
	var width  = 1200;
	var height = 600;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height+',scrollbars=yes';
	// var cust = $("#kodesupp").val();
	// var barang = $("#idbarang"+a+"").val();
	params += ', top='+top+', left='+left;
	window.open('list_operasional.php?baris='+a,'',params);
};

function validasi(){
    var pesan='';
    var tgltransaksi = document.getElementById("tanggal").value;
    console.log(document.getElementById("tanggal").value);
    if(tgltransaksi == ''){
        pesan='Tanggal Tidak Boleh Kosong';	
    }
        
    if (pesan != '') {
            alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
            return false;
        }    	
}

function hitungtotal(){
	var debet=0;
	var kredit=0;

    for (var i=1; i<=baris1;i++){
		var barcode=document.getElementById("idakunparent"+i+"");
        console.log(barcode);
		if 	(barcode != null)
	    {      
            if(document.getElementById("debet"+i+"").value == ''){
			    debet = debet + 0;
            }else{
			    debet += parseFloat(document.getElementById("debet"+i+"").value);
            }

            if(document.getElementById("kredit"+i+"").value == ''){
			    kredit = kredit + 0;
            }else{
			    kredit += parseFloat(document.getElementById("kredit"+i+"").value);
            }
			
		}
	}

	// 
	var locale = 'IDR';
	var options = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
	var formatter = new Intl.NumberFormat(locale, options);
	// 
    document.getElementById("debet").value = debet.toFixed(0);
	document.getElementById("kredit").value = kredit.toFixed(0);

    document.getElementById("debet_tot").value = debet.toFixed(0);
	document.getElementById("kredit_tot").value = kredit.toFixed(0);

    document.getElementById("debet_total").value = formatter.format(debet.toFixed(0));
	document.getElementById("kredit_total").value = formatter.format(parseFloat(kredit.toFixed(0)));
	// 
	document.getElementById("debet_m").value = formatter.format(debet.toFixed(0));
	document.getElementById("kredit_m").value = formatter.format(parseFloat(kredit.toFixed(0)));
		
}



function hitungjml(a)
{
    validasi();
 	hitungtotal();
}

function generateIDAkunParent(index){
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "idakunparent"+index+"";
idx.id = "idakunparent"+index+"";
idx.size="15";
idx.align = "left";
return idx;
}

function generateIDAkunDet(index){
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "idakundet"+index+"";
idx.id = "idakundet"+index+"";
idx.size="15";
idx.align = "left";
return idx;
}

function generateNoAkun(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "noakun"+index+"";
idx.id = "noakun"+index+"";
idx.size = "15";
idx.align = "center";
return idx;
}

function generateAccountBalance(index) {
    var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "BalanceBtn"+index+"";
	idx.id = "BalanceBtn"+index+"";
	idx.size = "40";
	idx.value = "+";
	return idx;
}

function generateOperasional(index) {
    var idx = document.createElement("input");
	idx.type = "button";
	idx.name = "operasionalBtn"+index+"";
	idx.id = "operasionalBtn"+index+"";
	idx.size = "40";
	idx.value = "+";
	return idx;
}

function generateAkun(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "akun"+index+"";
idx.id = "akun"+index+"";
idx.size = "35";
// idx.readOnly = "readonly";
return idx;
}

function generateUraian(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "uraian"+index+"";
idx.id = "uraian"+index+"";
idx.size = "35";
// idx.readOnly = "readonly";
return idx;
}

function generateBukti(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "bukti"+index+"";
idx.id = "bukti"+index+"";
idx.size = "20";
return idx;
}

function generateDebet(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "debet"+index+"";
idx.id = "debet"+index+"";
idx.size = "20";
idx.style="text-align:right;";  
return idx;
}

function generateKredit(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "kredit"+index+"";
idx.id = "kredit"+index+"";
idx.size = "20";
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

function saveID(id) {
	var idx = document.createElement("input");
	idx.type = "hidden";
	idx.name = "delete"+id+"";
	idx.id = "delete"+id+"";
	return idx;
}

function delRow1(id){ 
	var row = $('#tbl_1 tr').length;
// console.log(row);
var el = document.getElementById("t1"+id);
if (row>2) {
		// baris1-=1;
		document.getElementById("myDiv").appendChild(saveID(id));
		document.getElementById('delete'+id+'').value = document.getElementById('idDet'+id+'').value;
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
    var pesan        = '';
	var tgltransaksi = document.getElementById("tanggal").value;
    var totdebet = document.getElementById("debet_tot").value;
    var totkredit = document.getElementById("kredit_tot").value;

		if(tgltransaksi == ''){
		    pesan='Tanggal Tidak Boleh Kosong';	
		}

	    // var arr_idbarang=[];
		// for (i=1;i<(baris1);i++){
		// 	arr_idbarang[i-1] = document.getElementById("idakunparent"+i+"").value;	
		// 		if (arr_idbarang[i-1]==""){
		// 		pesan = 'Masukan Nama Akun Kembali\n';	
		// 		}
		// 	}
            if (parseFloat(totdebet) < parseFloat(totkredit)) {
                pesan = 'Cek kembali total debet dan kredit\n';	
            }			
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}
	else
	{	var answer = confirm("Mau Simpan datanya????")
		if (answer)
		{	
		hitungrow();
		// hitung() ;
		document.form2.action="simpancashReceipt.php?&trans=EDIT";
		document.form2.submit();
		}
		else
		{}
    } 
}

<?php 
    $sqldetail = $db->prepare("SELECT * FROM cashreceipt_det det WHERE det.id_parent=?");
    $sqldetail->execute(array($_GET["id"]));
    $i=1;
    while($result = $sqldetail->fetch(PDO::FETCH_ASSOC)){
    ?>

    addNewRow1();
    document.getElementById('idDet'+<?=$i;?>+'').value = '<?=$result['id'];?>';
    document.getElementById('idakunparent'+<?=$i;?>+'').value = '<?=$result['id_akun_parent'];?>';
    document.getElementById('idakundet'+<?=$i;?>+'').value = '<?=$result['id_akun_det'];?>';
    document.getElementById('noakun'+<?=$i;?>+'').value = '<?=$result['no_akun'];?>';
    document.getElementById('akun'+<?=$i;?>+'').value = '<?=$result['nama_akun'];?>';
    document.getElementById('uraian'+<?=$i;?>+'').value = '<?=$result['uraian'];?>';
    document.getElementById('bukti'+<?=$i;?>+'').value = '<?=$result['buktikas'];?>';
    document.getElementById('debet'+<?=$i;?>+'').value = '<?=$result['debet'];?>';
    document.getElementById('kredit'+<?=$i;?>+'').value = '<?=$result['kredit'];?>';

    document.getElementById('kredit'+<?=$i;?>+'').focus();

    <?php 
    if($result['debet'] == '0'){
        ?>
            $("#debet"+<?=$i;?>+"").attr("disabled", true);
            $("#kredit"+<?=$i;?>+"").attr("disabled", false);
             $("#operasionalBtn"+<?=$i;?>+"").attr("disabled", false);
        <?php
    }else if($result['kredit'] == '0'){
        ?>
            $("#debet"+<?=$i;?>+"").attr("disabled", false);
            $("#kredit"+<?=$i;?>+"").attr("disabled", true);
            $("#operasionalBtn"+<?=$i;?>+"").attr("disabled", true);
        <?php
    }
    $i++;
}
?>

</script>
</body>