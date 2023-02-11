<!DOCTYPE HTML>
<head>
<title>EDIT JABATAN DETAIL</title>
<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />
<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
<script src="../../assets/tinymce/js/tinymce/tinymce.min.js"></script>

<style>
body {
    background-color:#BBB871 ;
}
tanggal {
    color: maroon;
    margin-left: 40px;
} 
</style>
 <?php 
  include("../../include/koneksi.php");
  $id = $_GET['id'];

  $sql = "SELECT * FROM hrd_jabatan WHERE id_jabatan = $id";
  $sq = mysql_query($sql);
  $rs = mysql_fetch_array($sq);
?>
</head>
<body>
<form id='form2' name='form2' action='' method='post'>

<table width='100%'>
<tr>
    <td class='fontjudul'>EDIT JABATAN</td>
</tr>
</table>

<hr>
    
<table width='100%' cellspacing='0' cellpadding='0'>
<input value="<?=$id?>" type="hidden" class="required" id="id_jabatan" name="id_jabatan">
     <tr>
		<td class='fonttext'>Kode Jabatan</td>
		<td><input type='text' class='inputform' name='kode_jabatan' id='kode_jabatan' placeholder='Kode Jabatan'  value='<?=$rs['kode_jabatan']?>' /></td>
        <td class='fonttext'>Nama Jabatan</td>
		<td><input type='text' class='inputform' name='nama_jabatan' id='nama_jabatan' placeholder='Nama Jabatan' value='<?=$rs['nama_jabatan']?>' /></td>
		
		
     </tr>
     <tr>
        <td class='fonttext'>Departemen</td>
            <td>
            <select class='inputform'  name='id_dept' id='id_dept'>
                <option value=''>-choose(pilih)-</option>
               <?php
                $sql_jabatan="SELECT * FROM hrd_departemen WHERE deleted=0 ORDER BY nama_dept ASC";
                $sql1 = mysql_query($sql_jabatan);
                $i=1;
                while($rs1=mysql_fetch_array($sql1)){
                    $selected = '';
                    if($rs1['id_dept'] == $rs['id_dept']){
                        $selected = 'selected';
                    }
                    echo "<option value='".$rs1['id_dept']."' ".$selected.">".$rs1['nama_dept']."</option>";
                }
                ?>
            </select>
            </td>
	    <td class='fonttext'>Melapor Ke</td>
        <td><input type='text' class='inputform' name='melapor_ke' id='melapor_ke' placeholder='Melapor Ke' value='<?=$rs['melapor_ke']?>' />
	 </tr>
	 <tr>
	    <td class='fonttext'>Lokasi Kerja</td>
        <td><textarea  name='lokasi_kerja' id='lokasi_kerja' placeholder='Lokasi Kerja' rows='5' cols='70'><?=$rs['lokasi_kerja']?></textarea></td>
        <td class='fonttext'>Ringkasan Jabatan</td>
        <td><textarea  name='ringkasan' id='ringkasan' placeholder='Ringkasan Jabatan' rows='5' cols='70'><?=$rs['ringkasan']?></textarea></td>
	 </tr>
     <tr>
	    <td class='fonttext'>Kualifikasi</td>
        <td><textarea class='textcustom' name='kualifikasi' id='kualifikasi' placeholder='Kualifikasi'><?=$rs['kualifikasi']?></textarea></td>
        <td class='fonttext'>Tanggung Jawab</td>
        <td><textarea class='textcustom' name='tanggung_jawab' id='tanggung_jawab' placeholder='Tanggung Jawab'><?=$rs['tanggung_jawab']?></textarea></td>
	 </tr>
     <tr>
	    <td class='fonttext'>Kondisi Pekerjaan</td>
        <td><textarea  name='kondisi_pekerjaan' id='kondisi_pekerjaan' placeholder='Kondisi Pekerjaan' rows='5' cols='70'><?=$rs['kondisi_pekerjaan']?></textarea></td>
	 </tr>
</table>
<hr>
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

</table>

<script type="text/javascript">
    tinymce.init({
        selector: ".textcustom",  // change this value according to your HTML
        plugins: "lists",
        toolbar: "numlist bullist",
        menubar:false,
        statusbar: false,
        height : "200",
        width : "90%"
    });
function tutup(){
window.close();
}

function cetak(){
    var pesan               = '';
    var kode                = form2.kode_jabatan.value;
    var nama   		        = form2.nama_jabatan.value;
	var dept                = form2.id_dept.value;
    var melapor             = form2.melapor_ke.value;
    var lokasi_kerja        = form2.lokasi_kerja.value;
    var ringkasan           = form2.ringkasan.value;
    var kualifikasi         = form2.kualifikasi.value;
    var tanggungjawab       = form2.tanggung_jawab.value;
    var kondisi             = form2.kondisi_pekerjaan.value;
    
	//alert('temp='+temp_total+',totalfaktur='+totalfaktur+',Deposit='+simpan_deposit);
	    
    if (kondisi == '') {
         pesan = 'Kondisi Pekerjaan tidak boleh kosong\n';
    }
    if (lokasi_kerja == '') {
        pesan = 'Lokasi Kerja tidak boleh kosong\n';
    }
    if (melapor == '') {
        pesan = 'Melapor Ke tidak boleh kosong\n';
    }
    if (ringkasan == '') {
        pesan = 'Ringkasan Kerja tidak boleh kosong\n';
    }
    if (dept == '') {
        pesan = 'Departemen tidak boleh kosong\n';
    }
    if (nama == '') {
        pesan = 'Nama Jabatan tidak boleh kosong\n';
    }
    if (kode == '') {
        pesan = 'Kode Jabatan tidak boleh kosong\n';
    }
	
    if (pesan != '') {
        alert('Maaf, ada kesalahan pengisian Nota : \n'+pesan);
        return false;
	}	
	else
	{ 
		var answer = confirm("Mau Simpan Datanya???")
		if (answer)
		{	
		document.form2.action="jabatan_save.php?action=edit";
		document.form2.submit();
		}
		else
		{}
    }	
}	

</script>
</body>