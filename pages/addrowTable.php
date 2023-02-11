<link rel="stylesheet" type="text/css" href="../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../assets/css/jquery.autocomplete.css" />
<script src="../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../assets/js/jquery.autocomplete.js'></script>

<!-- script buat panggil lib ajax
<script type="text/javascript" src="../assets/js/jquery-1.5.2.min.js"></script> -->
<!-- cobain panggil ajaxnya pake versi yang terbaru-->
<script type="text/javascript" src="../assets/js/jquery-1.10.2.min.js"></script>

<style type="text/css">
<!--
.style3 {color: #FFFFFF}
-->
</style>
<table width="28%" height="28" border="0" bordercolor="#333333" id="tbl_1">
  <tr>
   <td width="3%" bgcolor="#FF0000"><span class="style3"><font size="2">No</font></span></td>
   <td width="23%" bgcolor="#FF0000"><span class="style3"><font size="2">BARCODE</font></span></td>
    <td width="64%" bgcolor="#FF0000"><div align="center" class="style3"><font size="2">NAMA PART</font></div></td>
    <td width="13%" bgcolor="#FF0000" class="style3"><div align="center"><font size="2">Harga</font></div></td>
    <td width="13%" bgcolor="#FF0000" class="style3"><div align="center"><font size="2">QTY</font></div></td>
    <td width="13%" bgcolor="#FF0000" class="style3"><div align="center"><font size="2">SUBTOTAL</font></div></td>
    <td width="13%" bgcolor="#FF0000" class="style3"><div align="center"><font size="2">ACT</font></div></td>
	
  </tr>
</table>  
<script type="text/javascript">
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

td0.appendChild(generateNO(baris1));
td1.appendChild(generateBARCODE(baris1));
td2.appendChild(generateNAMAPART(baris1));
td3.appendChild(generateHARGA(baris1));
td4.appendChild(generateQTY(baris1));
td5.appendChild(generateSUBTOTAL(baris1));
td6.appendChild(generateDel1(baris1));


row.appendChild(td0);
row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);
row.appendChild(td6);
row.appendChild(td6);

/*
document.getElementById('BARCODE['+baris1+']').focus();
*/
document.getElementById('BARCODE['+baris1+']').focus();
document.getElementById('BARCODE['+baris1+']').setAttribute('onChange', 'addbarcode('+baris1+')');
document.getElementById('QTY['+baris1+']').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('HARGA['+baris1+']').setAttribute('onChange', 'hitungjml('+baris1+')');
document.getElementById('SUBTOTAL['+baris1+']').setAttribute('onEnter', 'addNewRow1()');
document.getElementById('del1['+baris1+']').setAttribute('onclick', 'delRow1('+baris1+')');


baris1++;
}
function addbarcode(a)
{
var ke1 = document.getElementById("BARCODE["+a+"]").value;
	$.ajax({
		url : 'ambilDataBrg.php',
		dataType: 'json',
		data: "barcode="+ke1,
		success: function(data) {
		var Id_Part  = data.id_barang;
		
		var Nama_Part  = data.nm_barang;	
		var harga      = data.hrg_beli;	
		
		document.getElementById('BARCODE['+a+']').value = Id_Part;
		document.getElementById('NAMAPART['+a+']').value = Nama_Part;
		document.getElementById('HARGA['+a+']').value = harga;
		//document.getElementById('QTY['+a+']').value = '1';	
        }
	});	
			
	/*});*/
	
/*});*/
//addNewRow1();
document.getElementById('QTY['+a+']').focus();
		
}

function generateNO(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "NO"+index+"";
idx.id = "NO["+index+"]";
idx.size = "2";
idx.align = "center";
idx.value = index;
return idx;
}
function generateBARCODE(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "BARCODE"+index+"";
idx.id = "BARCODE["+index+"]";
idx.size = "10";
idx.align = "center";
return idx;
}

function generateNAMAPART(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "NAMAPART"+index+"";
idx.id = "NAMAPART["+index+"]";
idx.size = "20";
idx.align = "center";
return idx;
}
function generateHARGA(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "HARGA"+index+"";
idx.id = "HARGA["+index+"]";
idx.size = "10";
idx.align = "right";
return idx;
}
function generateQTY(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "QTY"+index+"";
idx.id = "QTY["+index+"]";
idx.size = "5";
idx.align = "right";
return idx;
}
function generateSUBTOTAL(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "SUBTOTAL"+index+"";
idx.id = "SUBTOTAL["+index+"]";
idx.size = "20";
idx.align = "right";
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
	el.parentNode.removeChild(el);
	return false;
}

function hitungjml(a)
{
	var ke1 = document.getElementById("QTY["+a+"]").value;
	var ke2 = document.getElementById("HARGA["+a+"]").value;
	var jml=0;
	var total=0;
	
		jml=ke1*ke2;
    
 	document.getElementById("SUBTOTAL["+a+"]").value = jml;	
	var subtotal = new Array();
	for (var i=0; i<a;i++){
	subtotal.push(jml);
	total+=subtotal[i];
	} 
	alert ("total= "+total.toString());
	document.getElementById("SUBTOTAL["+a+"]").focus();	
	//document.getElementById("TOTAL").value = total;	
}

</script>
<table>
<tr>
<td size="10">
  <label>
  <input type="submit" name="button" id="button" value="Tambah" onclick="addNewRow1()"/>
  </label>
</td>
<td size="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td size="10">&nbsp;</td>
<td size="20">&nbsp;</td>
<td size="10">&nbsp;</td>
<td size="10">&nbsp;</td>
<td><input type="text" name="totalqty" id="totalqty" size="5" />
</td> 
<td><input type="text" name="TOTAL" id="TOTAL" size="20" />
</td> 
 
</tr>
 </table>  
