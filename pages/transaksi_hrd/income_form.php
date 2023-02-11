
<?php
include("../../include/koneksi.php");

//inisialisasi input penjualan B2B
//inisialisasi input penjualan B2B
$id=$_GET['id'];
$sql_init="SELECT tipe_kar, DATE_FORMAT(tgl_upah,'%d/%m/%Y') as tgl, jml_periode FROM pengupahan b WHERE b.upah_id ='".$id."' AND deleted=0 ";
//var_dump($sql_init);die;
$data = mysql_query($sql_init);
$rs = mysql_fetch_array($data);
$tipe_karyawan=$rs['tipe_kar'];
$tgl=$rs['tgl'];
$periode=$rs['jml_periode'];

?>
<head>
<title>EDIT INCOME</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<!--<script src="../../assets/js/time.js" type="text/javascript"></script>-->
<style>
body {
    background-color:#E2D65E ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script>var baris1=1;</script>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>EDIT INCOME</td>
		<td class='fontjudul'> TOTAL INCOME
		<input type='text' class='' name='total' id='total' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalhidden' id='totalhidden'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
  <table cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Tanggal Pengupahan</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$tgl</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td class='fonttext'>Tipe Karyawan</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$tipe_karyawan<br></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td class='fonttext'>Jumlah Periode Kerja</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$periode<br></td>
    </tr>
  </table>
    
     <td colspan='16'><hr/></td>
	 
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
	<td align='center' width='2%' class='fonttext'>No</td>
	<td align='center' width='20%' class='fonttext'>Nama Karyawan</td>
	<td align='center' width='7%' class='fonttext'>Kehadiran</td>
	<td align='center' width='7%' class='fonttext'>UP Pokok</td>
	<td align='center' width='7%' class='fonttext'>Tunjangan Tetap</td>
	 <td align='center' width='7%' class='fonttext'>Total Uang Makan</td>
	<td align='center' width='7%' class='fonttext'>Overtime</td>
	<td align='center' width='7%' class='fonttext'>THR</td>
	<td align='center' width='7%' class='fonttext'>Bonus</td>
	<td align='center' width='7%' class='fonttext'>Pendapatan Lain Lain</td>
	<td align='center' width='7%' class='fonttext'>Total Income</td>
    </tr>
";

$sql_edit = "SELECT det.*, kar.nama_kar FROM `pengupahan_detail` det LEFT JOIN tabel_karyawan kar ON kar.kar_id=det.kar_id WHERE det.upah_id='".$_GET['id']."' ";
$sql1 = mysql_query($sql_edit);
$i=1;
while($rs1=mysql_fetch_array($sql1)){
    ?>
        <tr id="t1<?=$i?>">
            <td class='fonttext'><input type="hidden" name="Id<?=$i?>" id="Id<?=$i?>" size="3" readonly="" value="<?=$rs1['upah_id_det']?>"><?=$i?></td>
            <td class='fonttext' align='left'><?=$rs1['nama_kar']?></td>

            <td class='fonttext'><input type="text" name="Kehadiran<?=$i?>" id="Kehadiran<?=$i?>" size="15" style='text-align:left' value="<?=$rs1['kehadiran']?>"></td>

            <td class='fonttext' align='right'><input type='hidden' name="Pokok<?=$i?>" id="Pokok<?=$i?>" value='<?=$rs1['up_pokok']?>'><?=number_format($rs1['up_pokok'],0)?></td>
            <td class='fonttext' align='right'><input type='hidden' name="Tunjangan<?=$i?>" id="Tunjangan<?=$i?>" value='<?=$rs1['tunjangan_tetap']?>'><?=number_format($rs1['tunjangan_tetap'],0)?></td>
            <td class='fonttext' align='right'><input type='hidden' name="Makan<?=$i?>" id="Makan<?=$i?>" value='<?=$rs1['ttl_makan']?>'><?=number_format($rs1['ttl_makan'],0)?></td>

            <td class='fonttext'><input type="text" name="Overtime<?=$i?>" id="Overtime<?=$i?>" size="15" style='text-align:right' value="<?=$rs1['overtime']?>" onkeyup='hitungjml(<?=$i?>)'></td>
            <td class='fonttext'><input type="text" name="THR<?=$i?>" id="THR<?=$i?>" size="15" style='text-align:right' value="<?=$rs1['thr']?>" onkeyup='hitungjml(<?=$i?>)'></td>
            <td class='fonttext'><input type="text" name="Bonus<?=$i?>" id="Bonus<?=$i?>" size="15" style='text-align:right' value="<?=$rs1['bonus']?>" onkeyup='hitungjml(<?=$i?>)'></td>
            <td class='fonttext'><input type="text" name="Pendapatan<?=$i?>" id="Pendapatan<?=$i?>" size="15" style='text-align:right' value="<?=$rs1['pendapatan']?>" onkeyup='hitungjml(<?=$i?>)'></td>
            <td class='fonttext'><input type="text" name="Total<?=$i?>" id="Total<?=$i?>" size="15" style='text-align:right' value="<?=$rs1['total_income']?>" readonly></td>

        </tr>
        <script>baris1=<?=$i?>;</script>
    <?php
    $i++;
}

echo "</thead></table>
<div id='myDiv'></div>
<table>
<td>
<p><input type='hidden' name='jum' value='' /><input  type='hidden' name='temp_limit' id='temp_limit' value='' /></p>
</table>
<table>
    <!--
	<tr>
	    <td class='fonttext'>Expedition</td>
        <td><input type='text' class='inputform' name='expedition' id='expedition' placeholder='Autosuggest Ekspedisi' />
		<input type='hidden' name='id_expedition' id='id_expedition'/></td>
		<td class='fonttext'>Exp.Code</td>
        <td><input type='text' class='inputform' name='exp_code' id='exp_code' placeholder='Kode Expedisi' /></td>
	 </tr>
	 <tr>
	    <td class='fonttext'>Exp.Fee</td>
        <td><input type='text' class='inputform' name='exp_fee' id='exp_fee' placeholder='Biaya Ekspedisi' onkeyup='hitungtotal();'/></td>
		<td class='fonttext'>Exp.Note</td>
        <td><textarea name='exp_note' id='exp_note' cols='31' rows='2' placeholder='Catatan Ekspedisi' ></textarea></td>
	 </tr>
	 -->
<tr>
<!-- <td class='fonttext'>Tunai </td> -->
<td><input type='hidden' class='inputform' name='tunai' id='tunai' style='text-align:right;' onkeyup='hitungpiutang();'>
<input type='hidden' class='inputform' name='faktur' id='faktur' /></td>
<!-- <td class='fonttext' >Tf.Bank</td> -->
<td><input type='hidden' class='inputform' name='transfer' id='transfer' style='text-align:right;'onkeyup='hitungpiutang();'></td>
<td class='fonttext' >&nbsp;</td>
</tr>
<tr>
<!-- <td class='fonttext' >Bayar dg Deposit</td> -->
<td><input type='hidden' class='inputform' name='byr_deposit' id='byr_deposit' style='text-align:right;'>
<input type='hidden' readonly placeholder='Saldo Deposit' name='saldo_deposit' id='saldo_deposit'/><input type='hidden' class='inputform' name='simpan_deposit' id='simpan_deposit' style='text-align:right;'></td>
<!-- <td class='fonttext'>Piutang</td> -->
<td><input type='hidden' class='inputform' name='piutang' id='piutang' style='text-align:right;'></td>
</tr>
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
var td5 = document.createElement("td");
var td6 = document.createElement("td");
var td7 = document.createElement("td");
var td8 = document.createElement("td");
var td9 = document.createElement("td");
var td10 = document.createElement("td");
// var td11 = document.createElement("td");
// var td12 = document.createElement("td");
// var td13 = document.createElement("td");
// var td14 = document.createElement("td");
// var td15 = document.createElement("td");
// var td16 = document.createElement("td");
// var td17 = document.createElement("td");

td0.appendChild(generateId(baris1));
//id untuk dimasukin id_product
td2.appendChild(generateKehadiran(baris1));
td6.appendChild(generateOvertime(baris1));
td7.appendChild(generateTHR(baris1));
td8.appendChild(generateBonus(baris1));
td9.appendChild(generatePendapatan(baris1));
// td11.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);
row.appendChild(td7);
row.appendChild(td8);
row.appendChild(td9);
row.appendChild(td10);
// row.appendChild(td11);
// row.appendChild(td12);
// row.appendChild(td13);
// row.appendChild(td14);
// row.appendChild(td15);
// row.appendChild(td16);
// row.appendChild(td17);

document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
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

function generateKehadiran(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Kehadiran"+index+"";
idx.id = "Kehadiran"+index+"";
idx.size = "15";
idx.align = "right";
return idx;
}

function generateOvertime(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Overtime"+index+"";
idx.id = "Overtime"+index+"";
idx.size = "15";
idx.align = "right";
return idx;
}

function generateTHR(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "THR"+index+"";
idx.id = "THR"+index+"";
idx.size = "15";
idx.align = "right";
return idx;
}

function generateBonus(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Bonus"+index+"";
idx.id = "Bonus"+index+"";
idx.size = "15";
idx.align = "right";
return idx;
}


function generatePendapatan(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Pendapatan"+index+"";
idx.id = "Pendapatan"+index+"";
idx.size = "15";
idx.align = "right";
return idx;
}

function hitungtotal(){
	var total=0;

    for (var i=1; i<=baris1;i++){
	    if(document.getElementById("Total"+i+"").value == "") {
		    var subtotal = 0;
        }else{
		    var subtotal = document.getElementById("Total"+i+"").value;
		}
		total+= parseInt(subtotal);
	}
	
	document.getElementById("totalhidden").value = total;	
    document.getElementById("total").value = total.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
}

function hitungjml(a)
{
	if(document.getElementById("Overtime"+a+"").value == ""){
    	var overtime = 0;
	}
	else
	{
	    var overtime = document.getElementById("Overtime"+a+"").value;
	}
	
	if(document.getElementById("THR"+a+"").value == ""){
    	var thr = 0;
	}
	else
	{
		var thr = document.getElementById("THR"+a+"").value;
	}

    if(document.getElementById("Bonus"+a+"").value == ""){
    	var bonus = 0;
	}
	else
	{
		var bonus = document.getElementById("Bonus"+a+"").value;
	}

    if(document.getElementById("Pendapatan"+a+"").value == ""){
    	var pendapatan = 0;
	}
	else
	{
		var pendapatan = document.getElementById("Pendapatan"+a+"").value;
	}

    var pokok = document.getElementById("Pokok"+a+"").value;
    var tunjangan = document.getElementById("Tunjangan"+a+"").value;
    var makan = document.getElementById("Makan"+a+"").value;

	var total=0;
	
    total = parseInt(pokok) + parseInt(tunjangan) + parseInt(makan) + parseInt(overtime) + parseInt(thr) + parseInt(bonus) + parseInt(pendapatan);
    
 	document.getElementById("Total"+a+"").value = total;	
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
		hitungrow() ;
		document.form2.action="income_save.php?id_trans=<?=$_GET['id']?>&jum="+baris1;
		document.form2.submit();
		}
		else
		{}
    /* } */
}	
hitungtotal();
</script>

</body>