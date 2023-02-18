<?php
include("../../include/koneksi.php");

//inisialisasi input penjualan B2B
//inisialisasi input penjualan B2B
$id=$_GET['id'];
$sql_init="SELECT b.*,DATE_FORMAT(tgl_upah_start, '%d/%m/%Y') as awal,DATE_FORMAT(tgl_upah_end, '%d/%m/%Y') as akhir FROM hrd_penggajian b WHERE b.penggajian_id ='".$id."' AND deleted=0 ";
//var_dump($sql_init);die;
$data = mysql_query($sql_init);
$rs = mysql_fetch_array($data);

$nama_periode=$rs['nama_periode'];
$tgl=$rs['awal'].' - '.$rs['akhir'];
$periode=number_format($rs['jml_periode'],0);
$total = $rs['total_potongan'];
?>
<head>
<title>EDIT POTONGAN</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<!--<script src="../../assets/js/time.js" type="text/javascript"></script>-->
<style>
body {
    background-color:#B2DA5A ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<script>
var baris1=1;

$(document).ready(function(){

$("#generate").click(function(){
  var id = $('#periode').val();
  // console.log(tgl);

  $.ajax({
      url : 'generate_karyawan.php?id='+id+'&status=potongan',
      dataType: 'json',
      success: function(data) {
          console.log(data);

          // for(var j=1; j<baris1; j++){
          // 	delRow1(j);
          // 	console.log(j);
          // }

          // baris1 = 1;
          // var count = Object.keys(data).length;
          for (var i = 0; i < data.length; i++) {
              // console.log(data[i]['id']);
              addNewRow1();

              $('#Id'+(baris1-1)).val('');
              $('#Id'+(baris1-1)).html('');
              $('#IdKaryawan'+(baris1-1)).val(data[i]['id_karyawan']);
              $('#Karyawan'+(baris1-1)).val(data[i]['nama_karyawan']);
              $('#Departemen'+(baris1-1)).val(data[i]['nama_dept']);
              $('#Total'+(baris1-1)).val(data[i]['total']);
              $('#TotalHidden'+(baris1-1)).val(data[i]['total']);
          }
          hitungtotal();
      }
  });	
});
});

</script>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
    	<td  class='fontjudul'>EDIT POTONGAN</td>
		<td class='fontjudul'> TOTAL POTONGAN VARIABEL
		<input type='text' class='' name='total' id='total' value='Rp ".number_format($total,2,',','.')."' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
		<input type='hidden' name='totalhidden' id='totalhidden' value=''/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0'>
  <table cellspacing='0' cellpadding='0'>
    <tr>
        <td class='fonttext'>Nama Periode</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$nama_periode</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td class='fonttext'>Tanggal Penggajian</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$tgl<br></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td class='fonttext'>Jumlah Hari Kerja</td>
        <td class='fonttext'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;</td>
        <td class='fonttext'>$periode<br></td>
    </tr>
  </table>
    
     <td colspan='16'><hr/></td>
	 
</table>
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
	<td align='center' width='20%' class='fonttext'>Nama Karyawan</td>
	<td align='center' width='10%' class='fonttext'>Nama Departemen</td>
	<td align='center' width='10%' class='fonttext'>Jumlah Kehadiran</td>
	<td align='center' width='7%' class='fonttext'>Total Potongan Variabel</td>
	<td align='center' width='2%' class='fonttext'>Detail</td>
	<td align='center' width='2%' class='fonttext'>Hapus</td>
    </tr></thead></table>
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
// var td6 = document.createElement("td");
// var td7 = document.createElement("td");
// var td8 = document.createElement("td");
// var td9 = document.createElement("td");
// var td10 = document.createElement("td");
// var td11 = document.createElement("td");
// var td12 = document.createElement("td");
// var td13 = document.createElement("td");
// var td14 = document.createElement("td");
// var td15 = document.createElement("td");
// var td16 = document.createElement("td");
// var td17 = document.createElement("td");

td0.appendChild(generateId(baris1));
td0.appendChild(generateIdKaryawan(baris1));
td0.appendChild(generateKaryawan(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateDepartemen(baris1));
td2.appendChild(generateKehadiran(baris1));
// td6.appendChild(generateOvertime(baris1));
// td7.appendChild(generateTHR(baris1));
// td8.appendChild(generateBonus(baris1));
td3.appendChild(generateTotal(baris1));
td3.appendChild(generateTotalHidden(baris1));
td4.appendChild(generateDetail(baris1));
// td4.appendChild(generateReset(baris1));
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
// row.appendChild(td9);
// row.appendChild(td10);
// row.appendChild(td11);
// row.appendChild(td12);
// row.appendChild(td13);
// row.appendChild(td14);
// row.appendChild(td15);
// row.appendChild(td16);
// row.appendChild(td17);

document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
document.getElementById('Total'+baris1+'').setAttribute('onchange', 'hitungtotal()');
document.getElementById('Total'+baris1+'').setAttribute('onkeyup', "this.value = this.value.replace(/[^0-9]/g, '')");
document.getElementById('Detail'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');
// document.getElementById('Reset'+baris1+'').setAttribute('onclick', 'resetDetail('+baris1+')');
baris1++;

}

function popDetail(a){
    var karyawan = document.getElementById("IdKaryawan"+a).value;
	var width  = 1200;
	var height = 600;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height+',scrollbars=yes';
	// var cust = $("#kodesupp").val();
	// var barang = $("#idbarang"+a+"").val();
	params += ', top='+top+', left='+left;
	window.open('list_potongan.php?baris='+a+'&karyawan='+karyawan+'&penggajian='+<?=$id?>,'',params);
};

function resetDetail(a){
    var awal = document.getElementById("TotalHidden"+a).value;
    document.getElementById("Total"+a).value = awal;
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

function generateIdKaryawan(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdKaryawan"+index+"";
idx.id = "IdKaryawan"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateKaryawan(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Karyawan"+index+"";
idx.id = "Karyawan"+index+"";
idx.size = "50";
idx.readOnly = "readonly";
return idx;
}

function generateDepartemen(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Departemen"+index+"";
idx.id = "Departemen"+index+"";
idx.size = "20";
idx.readOnly = "readonly";
return idx;
}

function generateKehadiran(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Kehadiran"+index+"";
idx.id = "Kehadiran"+index+"";
idx.size = "10";
idx.style="text-align:right;";
idx.readOnly = "readonly";
return idx;
}

function generateTotal(index) {
var idx = document.createElement("input");
idx.type = "text";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "Total"+index+"";
idx.id = "Total"+index+"";
idx.size = "20";
idx.style="text-align:right;";
idx.readOnly = "readonly";
return idx;
}

function generateTotalHidden(index) {
var idx = document.createElement("input");
idx.type = "hidden";
//idx.name = "BARCODE"+index+"";
//idx.id = "BARCODE["+index+"]";
idx.name = "TotalHidden"+index+"";
idx.id = "TotalHidden"+index+"";
idx.size = "20";
idx.style="text-align:right;";
return idx;
}

function generateDetail(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "Detail"+index+"";
idx.id = "Detail"+index+"";
idx.size = "10";
idx.value = "..";
return idx;

}

function generateReset(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "Reset"+index+"";
idx.id = "Reset"+index+"";
idx.size = "10";
idx.value = "X";
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
	document.getElementById('delete1'+id+'').value = document.getElementById('Id'+id+'').value;

	var el = document.getElementById("t1"+id);
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal(baris1-1);
    hitungtotal();
	return false;
}
function hitungtotal(){
	var total=0;

    for (var i=1; i<=baris1;i++){
        var subtotal = 0;
        var Total=document.getElementById("Total"+i+"");
        if (Total != null)
        {   
            if(document.getElementById("Total"+i+"").value == "") {
                var subtotal = 0;
            }else{
                var subtotal = document.getElementById("Total"+i+"").value;
            }
        }  
		total+= parseFloat(subtotal);
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
	
    total = parseFloat(pokok) + parseFloat(tunjangan) + parseFloat(makan) + parseFloat(overtime) + parseFloat(thr) + parseFloat(bonus) + parseFloat(pendapatan);
    
 	document.getElementById("Total"+a+"").value = total;	
 	document.getElementById("TotalHidden"+a+"").value = total;	
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
		document.form2.action="potongan_save.php?action=edit&id=<?=$_GET['id']?>&jum="+baris1;
		document.form2.submit();
		}
		else
		{}
    /* } */
}	
// hitungtotal();

<?php
		// $sqldetail = "SELECT a.`id_penggajiandet`, a.`id_karyawan`, b.`nama_karyawan`, d.`nama_dept`, IFNULL(a.`subtotal_variabel`,0) as subtotal_variabel,IFNULL(a.`subtotal`,0) as subtotal

		// -- (SELECT IFNULL(SUM(d.subtotal),0) as total FROM hrd_karyawan aa LEFT JOIN hrd_karyawandet d ON d.id_karyawan=aa.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='potongan' AND metode_pethitungan='Manual Input' LEFT JOIN hrd_jabatan b ON b.id_jabatan=aa.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE aa.deleted=0 AND aa.id_karyawan=a.id_karyawan) as subtotalmanual
		 
		// ,a.jml_kehadiran FROM hrd_penggajiandet a
		// LEFT JOIN hrd_karyawan b ON b.`id_karyawan`=a.`id_karyawan`
		// LEFT JOIN `hrd_jabatan` c ON c.`id_jabatan`=b.`id_jabatan`
		// LEFT JOIN hrd_departemen d ON c.id_dept=d.`id_dept`
		// WHERE a.`status`='potongan' AND a.`id_penggajian`='$id' ORDER BY a.`id_penggajiandet` ASC";

		$sqldetail = "SELECT a.`id_penggajiandet`, a.`id_karyawan`, b.`nama_karyawan`, d.`nama_dept`, b.no_telp, a.wa, a.jml_kehadiran,
		IFNULL((SELECT SUM(subtotal_variabel) FROM hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot WHERE `id_penggajian`='$id' AND b.`id_karyawan`=a.`id_karyawan` AND `status`='potongan' AND total_pendapatan=1),0) AS subtotal_variabel, 
		IFNULL((SELECT SUM(subtotal) FROM hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot WHERE `id_penggajian`='$id' AND b.`id_karyawan`=a.`id_karyawan` AND `status`='potongan' AND total_pendapatan=1),0) AS subtotal, a.status FROM hrd_penggajiandet a
		LEFT JOIN hrd_karyawan b ON b.`id_karyawan`=a.`id_karyawan`
		LEFT JOIN `hrd_jabatan` c ON c.`id_jabatan`=b.`id_jabatan`
		LEFT JOIN hrd_departemen d ON c.id_dept=d.`id_dept`
		WHERE a.`id_penggajian`='$id' 
		GROUP BY a.`id_karyawan`
		ORDER BY b.`nama_karyawan` ASC";

		$sqdet = mysql_query($sqldetail);
		$i = 1;
		while($rs1 = mysql_fetch_array($sqdet)){
			?>
				addNewRow1();
				document.getElementById('Id'+<?=$i;?>+'').value = '<?=$rs1['id_penggajiandet'];?>';
				document.getElementById('IdKaryawan'+<?=$i;?>+'').value = '<?=$rs1['id_karyawan'];?>';
				document.getElementById('Karyawan'+<?=$i;?>+'').value = '<?=$rs1['nama_karyawan'];?>';
				document.getElementById('Kehadiran'+<?=$i;?>+'').value = '<?=number_format($rs1['jml_kehadiran'],0);?>';
				document.getElementById('Departemen'+<?=$i;?>+'').value = '<?=ucfirst($rs1['nama_dept']);?>';
				document.getElementById('Total'+<?=$i;?>+'').value = '<?=$rs1['subtotal_variabel'];?>';
				document.getElementById('TotalHidden'+<?=$i;?>+'').value = '<?=$rs1['subtotal_variabel'];?>';
			<?php
			$i++;
		}
	
	?>
hitungtotal();
</script>

</body>