<head>
<title>EDIT PAYMENT CHECK</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<style>
body {
    background-color:#dEEd86 ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
<?php
  // master_data 
  include("../../include/koneksi.php");
  require "../../include/config.php";
  $sql_mst="SELECT trpaymentcheck.*,DATE_FORMAT(check_date, '%Y-%m-%d') as tgl FROM trpaymentcheck WHERE id='".$_GET['id']."' AND deleted=0 ";
  $sql = mysql_query($sql_mst)or die (mysql_error());
  $rs = mysql_fetch_array($sql);
  $id    	    = $rs['id'];
  $idcheck    	= $rs['id_check'];
  $date    	    = $rs['tgl'];
  $note    	    = $rs['note'];
  $olnhidden    = $rs['total_oln'];
  $oln    	    = 'Rp '.str_replace(',','.',number_format($rs['total_oln'],0)).',00';
  $csvhidden    = $rs['total_csv'];
  $csv    	    = 'Rp '.str_replace(',','.',number_format($rs['total_csv'],0)).',00';
//   $dropcust     = $rs['namadropcust'];
//   $iddropcust   = $rs['id_dropcust'];
//   $statdropcust = $rs['status_dropcust'];
 

?>
<script language="javascript">
// var idnya = '';  
// var statusnya = '';  

$(document).ready(function(){


  $("#generate").click(function(){
	var tgl = $('#tglcheck').val();
	// console.log(tgl);

	$.ajax({
		url : 'generate_csv.php?tgl='+tgl,
		dataType: 'json',
		success: function(data) {
			// console.log(data);

			// for(var j=1; j<baris1; j++){
			// 	delRow1(j);
			// 	console.log(j);
			// }

			// baris1 = 1;
		    // var count = Object.keys(data).length;
			for (var i = 0; i < data.length; i++) {
				// console.log(data[i]['id']);
				addNewRow1();

				$('#IdCSV'+(baris1-1)).val(data[i]['id']);
				$('#CSV'+(baris1-1)).val(data[i]['id_import']);
				$('#KetCSV'+(baris1-1)).val(data[i]['keterangan']);
				$('#TglCSV'+(baris1-1)).val(data[i]['periode']);
				$('#ValueCSV'+(baris1-1)).val(data[i]['jumlah']);
				$('#ValuehiddenCSV'+(baris1-1)).val(data[i]['jumlahhidden']);
			}
			hitungtotal();
        }
	});	
  });
});

//autocomplete pada master
$().ready(function() {

		$("#dropcust").autocomplete("lookup_dropcust.php?", {
		width: 300
  });
  
    $("#dropcust").result(function(event, data, formatted) {
	var nama_ds = document.getElementById("dropcust").value;
	for(var h=0;h< nama_ds.length;h++){
		var did = nama_ds.split(':');
		if (did[0]=="") continue;
		var id_d=did[0];
        var status=did[2];
	}
	
	//alert("id_d="+id_d);
	$.ajax({
		url : 'lookup_dropcust_ambil.php?id='+id_d+'&stat='+status,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var id_dropcust  = data.id;
            idnya = data.id;
			$('#id_dropcust').val(id_dropcust);
            var nama_dropcust  = data.nama;
			$('#dropcust').val(nama_dropcust);
            var status_dropcust  = data.status;
            statusnya = data.status;
			$('#status_dropcust').val(status_dropcust);
		}
	});	
			
	});
	
	//autocomplete region
	$("#region").autocomplete("lookup_address.php?", {
		width: 500
	});
	$("#region").result(function(event, data, formatted) {
	
	var nama_rg = document.getElementById("region").value;
	
	for(var i=0;i<nama_rg.length;i++){
		var id = nama_rg.split(':');
		if (id[0]=="") continue;
		var id_rg=id[0];
	}
		//console.log("here="+id);
		//console.log(id_rg);
		//alert("id_rg="+id_rg);
  	    //document.getElementById("id_address").innerHTML.value = id_rg;
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
    
	//autocomplete expedition
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
  include("../../include/koneksi.php");
  
	function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
  

function getincrementnumber2()
{
	$q = mysql_fetch_array( mysql_query('select id_trans from olnso order by id_trans desc limit 0,1'));
	
	$kode=substr($q['id_trans'], -5);
	$bulan=substr($q['id_trans'], -7,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
	//echo"Kode=".$kode."Num=".$num."bulan=".$bulan;
	
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
	$id="OLN".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	
}	
//$id_registrasi = getnewnotrxwait();
$id_pkb = getnewnotrxwait2();

?>
</head>
<body>
<?php
echo"<form id='form2' name='form2' action='' method='post'>
    <table width='100%'>
  	<tr>
      <input type='hidden' name='idmst' id='idmst' value='$id'>
    	<td  class='fontjudul'>EDIT PAYMENT CHECK ($idcheck)</td>
		<td class='fontjudul'> TOTAL PAYMENT <input type='text' class='' name='totalcsv' id='totalcsv' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' value='$csv' />
		<input type='hidden' name='totalcsvhidden' id='totalcsvhidden' value='$csvhidden'/>
		<td class='fontjudul'> TOTAL SALES <input type='text' class='' name='totaloln' id='totaloln' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' value='$oln'/>
		<input type='hidden' name='totalolnhidden' id='totalolnhidden' value='$olnhidden'/>
        <td class='fontjudul'> SISA <input type='text' class='' name='totalsisa' id='totalsisa' style='text-align:right;font-size: 30px;background-color:white;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' value='Rp 0,00'/>
		<input type='hidden' name='totalsisahidden' id='totalsisahidden' value='0'/>
		</td>
    </tr>
</table>
    <hr />
<table width='100%' cellspacing='0' cellpadding='0' border=0>
	<tr>
	<td class='fonttext'>Tanggal Import</td>
	<td><input type='date'  id='tglcheck' name='tglcheck' class='datepicker' value='$date'><button type='button' id='generate'>Generate</td>   
	<td class='fonttext'>Note</td>
        <td rowspan=1><textarea name='note' id='note' cols='60' rows='3'>$note</textarea></td>
	</tr>
     <tr height='1'>
     <td colspan='4'></td>
     </tr>
	
     <td colspan='6'><hr/></td>
	 
</table>
<table width='100%' >
<tr><td width='50%' style='vertical-align:top'>
<table align='center' id='tbl_1' width='100%' >

<thead>
<tr><td colspan='5' align='center' class='fonttext'><h3>CSV Payment List<h3></td></tr>
    <tr>
        <td align='center' width='15%' class='fonttext'>ID Import</td>
    	<td align='center' width='50%' class='fonttext'>Keterangan</td>
    	<td align='center' width='25%' class='fonttext'>Periode</td>
    	<td align='center' width='15%' class='fonttext'>Value</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<p><input type='hidden' name='jum1' id='jum1'><img value='Tambah Baris' src='../../assets/images/tambah_baris.png'  id='baru1'  onClick='addNewRow1()' style='cursor:pointer;'/></p>
</td>

<td width='50%' style='vertical-align:top'>
<table align='center' id='tbl_2' width='100%'>

<thead>
<tr><td colspan='6' align='center' class='fonttext'><h3>OLN Sales /B2B ORDER DATA<h3></td></tr>
    <tr>
        <td align='center' width='15%' class='fonttext'>ID OLN</td>
        <td align='center' width='25%' class='fonttext'>Dropshipper/Customer</td>
    	<td align='center' width='15%' class='fonttext'>Tanggal Sales</td>
    	<td align='center' width='15%' class='fonttext'>Faktur</td>
    	<td align='center' width='15%' class='fonttext'>Value</td>
      	<td align='center' width='5%' class='fonttext'>Hapus</td>    
    </tr>
</thead>
</table>
<table width='100%' >
<tr>
<td align='left'><img value='Tambah Baris' src='../../assets/images/tambah_baris.png'  id='baru2'  onClick='addNewRow2()' style='cursor:pointer;'/>&nbsp;&nbsp;<input type='hidden' name='jum2' id='jum2'><img value='Tambah Baris' src='../../assets/images/search.png'  id='baru2'  onClick='cariLOV()' style='cursor:pointer;'/></td>
<td><div align='right'><img value='Tambah Baris' src='../../assets/images/koreksi.png'  id='baru2'  onClick='addNewRowKoreksi()' style='cursor:pointer;'/>&nbsp;&nbsp;<input type='hidden' name='jumkoreksi' id='jumkoreksi'></div></td>
</tr>
</table>
</td>
</tr>

<tr><td colspan=2><hr></td></tr>
</table>
<div id='myDivCSV'></div>
<div id='myDivOLN'></div>
</table>
<table>
<tr>
<td>
<p align='center'><img name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' style='cursor:pointer;'/></p>
</td>
<td>
<p><input type='image' value='batal' src='../../assets/images/batal.png'  id='baru'  onClick='tutup()'/></p>
</td>
</tr>

</table>";
?>

<script type="text/javascript">
//autocomplete pada grid
function get_csv(a){  
   $("#CSV"+a+"").autocomplete("lookup_csv.php?", {
	width: 550});
  //console.log('here'+a)  ;
   $("#CSV"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("CSV"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0].trim();
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_csv_ambil.php?id='+id_pd,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var id  = data.id;
			$("#IdCSV"+a+"").val(id);
            var keterangan  = data.keterangan;
            $("#KetCSV"+a+"").val(keterangan);
		    var id_import  = data.id_import;
			$("#CSV"+a+"").val(id_import);
		    var periode  = data.periode;
			$("#TglCSV"+a+"").val(periode);
		    var value  = data.jumlahhidden;
			$("#ValueCSV"+a+"").val(value);
		    var valuehidden  = data.jumlah;
			$("#ValuehiddenCSV"+a+"").val(valuehidden);
			hitungtotal();
        }
	});	
			
	});
}  

function get_oln(a){  
    // console.log(statusnya);
	// var iddropcust = $('#id_dropcust').val();
	// var statdropcust = $('#status_dropcust').val();
   $("#OLN"+a+"").autocomplete("lookup_oln.php?", {
	width: 550});
  //console.log('here'+a)  ;
   $("#OLN"+a+"").result(function(event, data, formatted) {
	var nama = document.getElementById("OLN"+a+"").value;
	for(var i=0;i<nama.length;i++){
		var id = nama.split(':');
		if (id[0]=="") continue;
		var id_pd=id[0].trim();
		var id_trans=id[2].trim().replace('<br>Tanggal Trans','');
	}
	//console.log(id_pd);
	$.ajax({
		url : 'lookup_oln_ambil.php?id='+id_pd+'&trans='+id_trans,
		dataType: 'json',
		data: "nama="+formatted,
		success: function(data) {
		    var id  = data.id;
			$("#IdOLN"+a+"").val(id);
		    var id_trans  = data.id_trans;
			$("#OLN"+a+"").val(id_trans);
			var dropcust  = data.namadropcust;
			$("#DropcustOLN"+a+"").val(dropcust);
			var idnya  = data.idnya;
			$("#IdDropcustOLN"+a+"").val(idnya);
			var stat  = data.stat;
			$("#StatusDropcustOLN"+a+"").val(stat);
			var id_trans  = data.id_trans;
			$("#OLN"+a+"").val(id_trans);
		    var tgl_trans  = data.tgl;
			$("#TglOLN"+a+"").val(tgl_trans);
		    var value  = data.total;
			$("#ValueOLN"+a+"").val(value);
			var value2  = data.total2;
			$("#FakturOLN"+a+"").val(value2);
			var value1  = data.total1;
			$("#FakturhiddenOLN"+a+"").val(value1);
			hitungtotal();
        }
	});	
			
	});
}  

var baris2=1;
var baris1=1;

// addNewRow2();
// addNewRow1();

function addNewRowKoreksi() 
{
var tbl = document.getElementById("tbl_2");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't2'+baris2;

var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");

td0.appendChild(generateIdDetOLN(baris2));
td0.appendChild(generateIdOLN(baris2));
td0.appendChild(generateOLN(baris2));
//id untuk dimasukin id_product
td1.appendChild(generateDropcustOLN(baris2));
td1.appendChild(generateIdDropcustOLN(baris2));
td1.appendChild(generateStatusOLN(baris2));
td2.appendChild(generateTanggalOLN(baris2));
td3.appendChild(generateFakturOLN(baris2));
td3.appendChild(generateFakturhiddenOLN(baris2));
td4.appendChild(generateValueOLN(baris2));
td5.appendChild(generateDel2(baris2));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('ValueOLN'+baris2+'').focus();
document.getElementById('OLN'+baris2+'').value='KOREKSI';
document.getElementById('OLN'+baris2+'').style='background-color:#C0C0C0';
document.getElementById('OLN'+baris2+'').readOnly ='true';
// document.getElementById('OLN'+baris2+'').setAttribute('onkeyup', 'get_oln(event,'+baris2+')');
document.getElementById('ValueOLN'+baris2+'').setAttribute('onkeyup', 'hitungjml('+baris2+')');
document.getElementById('del2'+baris2+'').setAttribute('onclick', 'delRow2('+baris2+')');
// get_oln(baris2);

baris2++;
}

function addNewRow2() 
{
var tbl = document.getElementById("tbl_2");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't2'+baris2;

var td0 = document.createElement("td");
var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");

td0.appendChild(generateIdDetOLN(baris2));
td0.appendChild(generateIdOLN(baris2));
td0.appendChild(generateOLN(baris2));
//id untuk dimasukin id_product
td1.appendChild(generateDropcustOLN(baris2));
td1.appendChild(generateIdDropcustOLN(baris2));
td1.appendChild(generateStatusOLN(baris2));
td2.appendChild(generateTanggalOLN(baris2));
td3.appendChild(generateFakturOLN(baris2));
td3.appendChild(generateFakturhiddenOLN(baris2));
td4.appendChild(generateValueOLN(baris2));
td5.appendChild(generateDel2(baris2));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('OLN'+baris2+'').focus();
// document.getElementById('OLN'+baris2+'').setAttribute('onkeyup', 'get_oln(event,'+baris2+')');
document.getElementById('ValueOLN'+baris2+'').setAttribute('onkeyup', 'hitungjml('+baris2+')');
document.getElementById('del2'+baris2+'').setAttribute('onclick', 'delRow2('+baris2+')');
get_oln(baris2);

baris2++;

}

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

td0.appendChild(generateIdDetCSV(baris1));
td0.appendChild(generateIdCSV(baris1));
td0.appendChild(generateCSV(baris1));
//id untuk dimasukin id_product
td1.appendChild(generateKeteranganCSV(baris1));
td2.appendChild(generateTanggalCSV(baris1));
td3.appendChild(generateValueCSV(baris1));
td3.appendChild(generateValuehiddenCSV(baris1));
td4.appendChild(generateDel1(baris1));

row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);

document.getElementById('CSV'+baris1+'').focus();
document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
get_csv(baris1);
baris1++;

}

function cariLOV(){
	// alert('ON PROGRESS');
	// var iddropcust = $('#id_dropcust').val();
	// var statdropcust = $('#status_dropcust').val();
	window.open('list_oln.php?baris='+baris2);
}

//csv
function generateIdDetCSV(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdDetCSV"+index+"";
idx.id = "IdDetCSV"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateIdCSV(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdCSV"+index+"";
idx.id = "IdCSV"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateCSV(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "CSV"+index+"";
idx.id = "CSV"+index+"";
idx.size = "15";
idx.align = "center";
return idx;
}

function generateTanggalCSV(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "TglCSV"+index+"";
idx.id = "TglCSV"+index+"";
idx.size = "20";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateKeteranganCSV(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "KetCSV"+index+"";
idx.id = "KetCSV"+index+"";
idx.size = "50";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateValueCSV(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "ValueCSV"+index+"";
idx.id = "ValueCSV"+index+"";
idx.size = "10";
idx.readOnly = "readonly";
idx.style="text-align:right;background-color:#C0C0C0";
return idx;
}

function generateValuehiddenCSV(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "ValuehiddenCSV"+index+"";
idx.id = "ValuehiddenCSV"+index+"";
idx.size = "15";
idx.readOnly = "readonly";
idx.style="text-align:right;background-color:#C0C0C0";
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

function saveIDCSV(id) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "deleteCSV1"+id+"";
idx.id = "deleteCSV1"+id+"";
return idx;
}

function delRow1(id){
	document.getElementById("myDivCSV").appendChild(saveIDCSV(id));
	document.getElementById('deleteCSV1'+id+'').value = document.getElementById('IdDetCSV'+id+'').value;

	var el = document.getElementById("t1"+id);
	// baris1-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal((baris1-1));
    hitungtotal();
	return false;
}

//OLN
function generateIdDetOLN(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdDetOLN"+index+"";
idx.id = "IdDetOLN"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateIdOLN(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdOLN"+index+"";
idx.id = "IdOLN"+index+"";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}

function generateOLN(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "OLN"+index+"";
idx.id = "OLN"+index+"";
idx.size = "15";
idx.align = "center";
return idx;
}

function generateDropcustOLN(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "DropcustOLN"+index+"";
idx.id = "DropcustOLN"+index+"";
idx.size = "30";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateIdDropcustOLN(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "IdDropcustOLN"+index+"";
idx.id = "IdDropcustOLN"+index+"";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateStatusOLN(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "StatusDropcustOLN"+index+"";
idx.id = "StatusDropcustOLN"+index+"";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateTanggalOLN(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "TglOLN"+index+"";
idx.id = "TglOLN"+index+"";
idx.size = "12";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0";
return idx;
}

function generateFakturOLN(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "FakturOLN"+index+"";
idx.id = "FakturOLN"+index+"";
idx.size = "10";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0;text-align:right;";

return idx;
}

function generateFakturhiddenOLN(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "FakturhiddenOLN"+index+"";
idx.id = "FakturhiddenOLN"+index+"";
idx.size = "10";
idx.readOnly = "readonly";
idx.style="background-color:#C0C0C0;text-align:right;";

return idx;
}


function generateValueOLN(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "ValueOLN"+index+"";
idx.id = "ValueOLN"+index+"";
idx.size = "10";
// idx.readOnly = "readonly";
idx.style="text-align:right;";

return idx;
}

function generateDel2(index) {
var idx = document.createElement("input");
idx.type = "button";
idx.name = "del2"+index+"";
idx.id = "del2"+index+"";
idx.size = "10";
idx.value = "X";
return idx;

}

function saveIDOLN(id) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "deleteOLN1"+id+"";
idx.id = "deleteOLN1"+id+"";
return idx;
}

function hitungjml(a){
    hitungtotal();
}

function delRow2(id){ 
	document.getElementById("myDivOLN").appendChild(saveIDOLN(id));
	document.getElementById('deleteOLN1'+id+'').value = document.getElementById('IdDetOLN'+id+'').value;
	
	var el = document.getElementById("t2"+id);
	// baris2-=1;
	el.parentNode.removeChild(el);
	//alert("baris terakhir="+baris1.toString())
    //hitungtotal((baris1-1));
    hitungtotal();
	return false;
}

function validasi(){
var pesan='';
var id_dropcust  = form2.id_dropcust.value;

	if (id_dropcust == '') {
            pesan = 'Customer/Dropshipper tidak boleh kosong\n';
			form2.dropcust.focus;
    }
	
if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian form : \n'+pesan);
        return false;
	}    
}

function hitungtotal(){
    
	var totalcsv=0;
	var totaloln=0;
    
    for (var i=1; i<=baris1;i++){
	var csv=document.getElementById("CSV"+i+"");
	 if (csv != null)
	 {   
	    if(document.getElementById("CSV"+i+"").value == "") {
		    var subtotal = 0;}
		else{
		    var subtotal = document.getElementById("ValuehiddenCSV"+i+"").value;
		}
		totalcsv+= parseInt(subtotal);
	 }
	}

    for (var i=1; i<=baris2;i++){
	var oln=document.getElementById("OLN"+i+"");
	 if (oln != null)
	 {   
	    if(document.getElementById("OLN"+i+"").value == "") {
		    var subtotal = 0;}
		else{
		    var subtotal = document.getElementById("ValueOLN"+i+"").value;
		}
		totaloln+= parseInt(subtotal);
	 }
	}

    var totalsisa = totalcsv-totaloln;
    //totalhidden dipake buat validasi saja
	document.getElementById("totalcsvhidden").value = totalcsv;	
	document.getElementById("totalolnhidden").value = totaloln;	
	document.getElementById("totalsisahidden").value = totalsisa;	
    document.getElementById("totalcsv").value = totalcsv.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
    document.getElementById("totaloln").value = totaloln.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
    document.getElementById("totalsisa").value = totalsisa.toLocaleString('IND', {style: 'currency', currency: 'IDR'});
}

function hitungrow() 
{
	document.form2.jum1.value= baris1;
	document.form2.jum2.value= baris2;
}

function tutup(){
window.close();
}

function cetak(){
    var pesan           = '';
    var totalcsv  = form2.totalcsvhidden.value;
    // var id_dropcust= form2.id_dropcust.value;
    var totaloln  = form2.totalolnhidden.value;

    if (totalcsv != totaloln) {
        pesan = 'Total Payment dan Sales berbeda\n';
    }

    if (totaloln == '0' || totaloln == '') {
        pesan = 'Total Sales tidak boleh kosong\n';
    }

    if (totalcsv == '0' || totalcsv == '') {
        pesan = 'Total Payment tidak boleh kosong\n';
    }

	// if(id_dropcust == ''){
	// 	pesan = 'Dropshipper/Customer tidak boleh kosong\n';
	// }

	for(var i=1;i<baris2;i++){
		if($('#DropcustOLN'+i)){
			var faktur = parseFloat($('#FakturhiddenOLN'+i).val());
			var value = parseFloat($('#ValueOLN'+i).val());
			if(value > faktur){
				pesan = 'Cek ulang OLN Sales /B2B ORDER DATA\n';
			}
		}
	}
        
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian : \n'+pesan);
        return false;
	}else{
        var answer = confirm("Mau Simpan data ??")
		if (answer)
		{	
		hitungrow() ;
		document.form2.action="paymentcheck_save.php?trans=EDIT";
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
	
<?php 
$sqldetail = $db->prepare("SELECT det.id_detail,det.id_import AS id, bank.id_import, bank.keterangan, bank.periode,det.payment_value FROM trpaymentcheck_detail det LEFT JOIN acc_prebank bank ON bank.id=det.id_import WHERE det.id_parent=? AND det.id_import<>'0';");
$sqldetail->execute(array($_GET["id"]));
$i=1;
while($result = $sqldetail->fetch(PDO::FETCH_ASSOC)){
?>

addNewRow1();
document.getElementById('IdDetCSV'+<?=$i;?>+'').value = '<?=$result['id_detail'];?>';
document.getElementById('IdCSV'+<?=$i;?>+'').value = '<?=$result['id'];?>';
document.getElementById('CSV'+<?=$i;?>+'').value = '<?=$result['id_import'];?>';
document.getElementById('KetCSV'+<?=$i;?>+'').value = '<?=$result['keterangan'];?>';
document.getElementById('TglCSV'+<?=$i;?>+'').value = '<?=$result['periode'];?>';
document.getElementById('ValueCSV'+<?=$i;?>+'').value = '<?=number_format($result['payment_value'],0);?>';
document.getElementById('ValuehiddenCSV'+<?=$i;?>+'').value = '<?=$result['payment_value'];?>';
<?php 
$i++;
}
?>

<?php 
$j = 1;
$sqldetail2 = $db->prepare("
SELECT det.id_detail,det.koreksi,det.id_olnb2b, det.`stat_dropcust`,det.id_dropcust, det.`stat_dropcust`,IF(det.`stat_dropcust`='Dropshipper',(SELECT nama FROM mst_dropshipper WHERE id=det.`id_dropcust`),(SELECT nama FROM mst_b2bcustomer WHERE id=det.`id_dropcust`)) AS dropcust,  IF(det.`stat_dropcust`='Dropshipper',(SELECT DATE_FORMAT(tgl_trans, '%d/%m/%Y') AS tgl FROM olnso WHERE id_trans=det.id_olnb2b),(SELECT DATE_FORMAT(tgl_trans, '%d/%m/%Y') AS tgl FROM b2bdo WHERE id_trans=det.id_olnb2b)) AS tgl,det.subtotal, IF(det.`stat_dropcust`='Dropshipper',(SELECT FORMAT((faktur-payment),0) AS faktur FROM olnso WHERE id_trans=det.id_olnb2b),(SELECT FORMAT((faktur-payment),0) AS faktur FROM b2bdo WHERE id_trans=det.id_olnb2b)) as faktur, IF(det.`stat_dropcust`='Dropshipper',(SELECT (faktur-payment) AS faktur FROM olnso WHERE id_trans=det.id_olnb2b),(SELECT(faktur-payment) AS faktur FROM b2bdo WHERE id_trans=det.id_olnb2b)) as fakturhidden FROM trpaymentcheck_detail det WHERE det.id_parent=? AND det.id_olnb2b<>'';");
$sqldetail2->execute(array($_GET["id"]));
$i=1;
while($result2 = $sqldetail2->fetch(PDO::FETCH_ASSOC)){
if($result2['id_olnb2b'] == 'KOREKSI'){
?>
addNewRowKoreksi();
document.getElementById('IdDetOLN'+<?=$j;?>+'').value = '<?=$result2['id_detail'];?>';
document.getElementById('OLN'+<?=$j;?>+'').value = '<?=$result2['id_olnb2b'];?>';
document.getElementById('DropcustOLN'+<?=$j;?>+'').value = '';
document.getElementById('IdDropcustOLN'+<?=$j;?>+'').value = '';
document.getElementById('StatusDropcustOLN'+<?=$j;?>+'').value = '';
document.getElementById('TglOLN'+<?=$j;?>+'').value = '';
document.getElementById('FakturOLN'+<?=$j;?>+'').value = '';
document.getElementById('FakturhiddenOLN'+<?=$j;?>+'').value = '';
document.getElementById('ValueOLN'+<?=$j;?>+'').value = '<?=$result2['koreksi'];?>';
<?php }else{ ?>
addNewRow2();
document.getElementById('IdDetOLN'+<?=$j;?>+'').value = '<?=$result2['id_detail'];?>';
document.getElementById('OLN'+<?=$j;?>+'').value = '<?=$result2['id_olnb2b'];?>';
document.getElementById('DropcustOLN'+<?=$j;?>+'').value = '<?=$result2['dropcust'];?>';
document.getElementById('IdDropcustOLN'+<?=$j;?>+'').value = '<?=$result2['id_dropcust'];?>';
document.getElementById('StatusDropcustOLN'+<?=$j;?>+'').value = '<?=$result2['stat_dropcust'];?>';
document.getElementById('TglOLN'+<?=$j;?>+'').value = '<?=$result2['tgl'];?>';
document.getElementById('FakturOLN'+<?=$j;?>+'').value = '<?=$result2['faktur'];?>';
document.getElementById('FakturhiddenOLN'+<?=$j;?>+'').value = '<?=$result2['fakturhidden'];?>';
document.getElementById('ValueOLN'+<?=$j;?>+'').value = '<?=$result2['subtotal'];?>';
<?php 
}

$j++;
}
?>

</script>
</body>