<link rel="stylesheet" type="text/css" href="../../css/styles.css" />
<?php
include("koneksi/koneksi.php");
$sql = mysql_query("SELECT * FROM registrasi a
LEFT JOIN kendaraan b ON a.no_polisi = b.no_polisi
LEFT JOIN pelanggan c ON c.id_plg = b.id_plg WHERE a.id_registrasi= '".$_GET['id']."'")or die (mysql_error());
		$rs = mysql_fetch_array($sql);
echo"<table width='100%' border='0'>
  <tr>
    <div class='ui-widget-header ui-corner-top padding5'>
        ADD PEMBELIAN
    </div>
  </tr>
</table>
    <hr />    
		<form id='form2' name='form2' action='BASE_URLpages/beli.php?action=processadd' method='post' >
			<input type='hidden' name='id_plg' id='id_plg'/>
      		<input type='hidden' name='jum' id='jum' value='' />
      		<input type='hidden' name='jum1' id='jum1' value='' />
     
<table width='96%' border='0'>
	<tr>
          <td class='fonttext'>Kode.Transaksi</td>
          <td><div class='ui-corner-all form-control'>
                <input value='' type='text' class='' size='10' id='kode' name='kode'>
             </div>
		  </td><td class='fonttext'>Supplier</td>
          <td><select class='required' name='id_supplier' id='id_supplier'>
          <option value=''>-pilih-</option>";
		  
                		$query = $db->query("SELECT * FROM tblpelanggan ORDER BY namaperusahaan ASC");
						$rows = $query->fetchAll(PDO::FETCH_ASSOC);
						foreach($rows as $r) {
							$select = isset($row['id']) && $row['id'] == $r['id'] ? 'selected' : ''; 
							echo "<option".$select." value=".$r['id'].">".$r['namaperusahaan']."</option>";
						}
          
        echo"        	
	      </select></td>
          <td class='fonttext'>Tgl.Transaksi</td>
          <td>
		  <div class='ui-corner-all form-control'>
                <input value='' type='text' class='required datepicker' id='tgl_trans' name='tgl_trans'>
          </div>	
		  </td>
    </tr>
    <tr height='5px'>
  	  <td colspan='4'></td>
  	</tr>
</table>

<table width='100%'>
   <tr>
       <td class='fonttext'>Keterangan</td>
   </tr>
   <tr>
       <td><textarea name='keterangan' id='keterangan' cols='117' rows='3'></textarea></td>
   </tr>
   <tr>
       <td align='right'><input name='Cetak' type='submit' value='SIMPAN' id='cetak' onClick='cetakMM()'/></td>
   </tr>
</table>
<div id='myDiv2'></div>
<b>DETAIL</b>
    
<table align='center' width='100%' id='tbl_1'>
<thead>
    <tr>
   
        <td align='center' width='45%' height='20px' class='fonttext'>NAMA BARANG</td>
        <td align='center' width='15%' height='20px' class='fonttext'>HARGA</td>
        <td align='center' width='15%' height='20px' class='fonttext'>QTY</td>
        <td align='center' width='15%' height='20px' class='fonttext'>SUBTOTAL</td>
        <td align='center' width='7%' height='20px' class='fonttext'>Act</td>
    
    </tr>
</thead>
</table>
<p><input type='button' value='TAMBAH'  id='New2' onClick='addNewRow1()'/></p>
</form>";
?>

<script type="text/javascript">
    /*
	document.form2.alamat.value='<?=$rs['alamat'];?>';
	document.form2.nama.value='<?=$rs['nama'];?>';
	
	document.form2.tlp.value='<?=$rs['tlp'];?>';
	document.form2.kota.value='<?=$rs['kota'];?>';
	document.form2.no_polisi.value='<?=$rs['no_polisi'];?>';
	document.form2.type.value='<?=$rs['type'];?>';
	document.form2.no_rangka.value='<?=$rs['no_rangka'];?>';
	document.form2.odometer.value='<?=$rs['odometer'];?>';
    */
var baris1=1;
addNewRow1();
function addNewRow1() {
var tbl = document.getElementById("tbl_1");
var row = tbl.insertRow(tbl.rows.length);
row.id = 't1'+baris1;

var td1 = document.createElement("td");
var td2 = document.createElement("td");
var td3 = document.createElement("td");
var td4 = document.createElement("td");
var td5 = document.createElement("td");

td1.appendChild(generateId_BARANG(baris1));
td1.appendChild(generateNamaBarang(baris1));
td1.appendChild(generateCari1(baris1));
td2.appendChild(generateHarga(baris1));
td3.appendChild(generateQTY(baris1));
td4.appendChild(generateSUBTOTAL(baris1));
td5.appendChild(generateDel1(baris1));

row.appendChild(td1);
row.appendChild(td2);
row.appendChild(td3);
row.appendChild(td4);
row.appendChild(td5);

document.getElementById('Cari1['+baris1+']').setAttribute('onclick', 'popjasa('+baris1+')');
document.getElementById('del1['+baris1+']').setAttribute('onclick', 'delRow1('+baris1+')');
baris1++;
}

function popjasa(a){
	
	var width  = 550;
 	var height = 400;
 	var left   = (screen.width  - width)/2;
 	var top    = (screen.height - height)/2;
  	var params = 'width='+width+', height='+height+',scrollbars=yes';
 	params += ', top='+top+', left='+left;
		window.open('popjasaservice.php?row='+a+'','',params);
};

function generateId_BARANG(index) {
var idx = document.createElement("input");
idx.type = "hidden";
idx.name = "Id_BARANG"+index+"";
idx.id = "Id_BARANG["+index+"]";
idx.size = "3";
idx.readOnly = "readonly";
return idx;
}
function generateSUBTOTAL(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "SUBTOTAL"+index+"";
idx.id = "SUBTOTAL["+index+"]";
idx.size = "20";
idx.readOnly = "readonly";
return idx;
}

function generateQTY(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "QTY"+index+"";
idx.id = "QTY["+index+"]";
idx.size = "15";
idx.readOnly = "readonly";
return idx;
}
function generateNamaBarang(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "NamaBarang"+index+"";
idx.id = "NamaBarang["+index+"]";
idx.size = "45";
idx.readOnly = "readonly";
return idx;
}
function generateHarga(index) {
var idx = document.createElement("input");
idx.type = "text";
idx.name = "Harga"+index+"";
idx.id = "Harga["+index+"]";
idx.size = "14";
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
	el.parentNode.removeChild(el);
	return false;
}


function hitungrow() 
{
	document.form2.jum.value= baris1;
}




function cetakMM(){
hitungrow() ;
	document.form2.action="insertregis.php?idregis=<?=$_GET['id']?>&flag=pkb";
	document.form2.submit();
	
}
</script>

