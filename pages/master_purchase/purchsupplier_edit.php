<style>
  body{
    background-color: Moccasin;
  }

  tanggal {
    color: maroon;
    margin-left: 40px;
  }
</style>

<?php
  include("../../include/koneksi.php");

  $sql_mst  = "SELECT * FROM `mst_supplier` WHERE id=".$_GET['id']." AND `deleted` = 0 ";
  $sql      = mysql_query($sql_mst) or die (mysql_error());
  $result   = mysql_fetch_array($sql);
    $id_mst     = $result['id'];
    $vendor_mst = $result['vendor'];
    $pic_mst    = $result['pic'];
    $alamat_mst = $result['alamat'];
    $telp_mst   = $result['telp'];
    $email_mst  = $result['email'];
    $ktp_mst    = $result['ktp'];
    $npwp_mst   = $result['npwp'];
    $pkp_mst    = $result['pkp'];
    $item_mst   = $result['item'];
?>

<head>
  <title>EDIT SUPPLIER DETAIL</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<body>
  <form id="purchsupplier_edit" name="purchsupplier_edit" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">EDIT SUPPLIER</td>
        <td class="fontjudul">TOTAL QTY (PRODUK/JASA)<input type="text" id="totalqty" name="totalqty" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #f30; border-radius:4px; -moz-border-radius:4px;" value="<?= $item_mst ;?>" /></td>
      </tr>
    </table>
    <hr />

    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Supplier (*)</td>
        <td><input type="text" class="inputForm" name="supplier" id="supplier" placeholder="Nama Supplier" value="<?= $vendor_mst ;?>"/></td>
        <td class="fonttext">PIC (*)</td>
        <td><input type="text" class="inputForm" name="pic" id="pic" placeholder="Nama PIC" value="<?= $pic_mst ;?>" /></td>
      </tr>
      <tr>
        <td class="fonttext">Alamat (*)</td>
        <td><input type="text" class="inputForm" name="alamat" id="alamat" placeholder="Alamat Supplier" value="<?= $alamat_mst ;?>" /></td>
        <td class="fonttext">Contact (*)</td>
        <td><input type="text" class="inputForm" name="contact" id="contact" placeholder="Nomor Supplier" value="<?= $telp_mst ;?>" /></td>
      </tr>
      <tr>
        <td class="fonttext">KTP</td>
        <td><input type="text" class="inputForm" name="ktp" id="ktp" placeholder="KTP Supplier" value="<?= isset($ktp_mst) ? $ktp_mst : '' ?>"></td>
        <td class="fonttext">NPWP</td>
        <td><input type="text" class="inputForm" name="npwp" id="npwp" placeholder="NPWP Supplier" value="<?= isset($npwp_mst) ? $npwp_mst : ''?>"></td>
      </tr>
      <tr>
        <td class="fonttext">Email</td>
        <td><input type="text" class="inputForm" name="email" id="email" placeholder="Email Supplier" value="<?= isset($email_mst) ? $email_mst : ''?>" /></td>
        <td class="fonttext">PKP</td>
        <td><input type="checkbox" name="pkp" id="pkp" style="margin:0 !important; width: 24px; height: 24px;" <?= ($pkp_mst == "1") ? 'checked' : '' ;?> /></td>
      </tr>
      <tr>
        <td colspan="100%"><hr /></td>
      </tr>
    </table>

    <table width="100%" id="purchsupplier_detail">
      <thead>
        <tr>
          <td width="5%" class="fonttext">Kode</td>
          <td width="30%" class="fonttext">Produk / Jasa</td>
          <td width="10%" class="fonttext">Kategori</td>
          <td class="fonttext">Tanggal Quotation</td>
          <td width="10%" class="fonttext">Satuan</td>
          <td width="15%" class="fonttext">DPP/Unit</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>
  </form>

  <table>
    <tr>
      <td>
        <p><input type="image" value="Tambah Baris" src="../../assets/images/tambah_baris.png" id="baru" onClick="addNewRow1()" /></p>
      </td>
      <td>
        <p><input type="image" value="Cetak" src="../../assets/images/simpan_cetak.png" id="print" onClick="cetak()" /></p>
      </td>
      <td>
        <p><input type="image" value="batal" src="../../assets/images/batal.png" id="batal" onClick="tutup()" /></p>
      </td>
    </tr>
  </table>
</body>

<script>
var baris1=1;

// general function ------------------------
function hitungqty(){
  var totalqty=0;

  for (var i=1; i<=baris1; i++){
    var kode=$('#id'+i).val();
    if(kode != null && kode != ''){
      totalqty += 1;
    }
  }

  $('#totalqty').val(totalqty);
}

function triggerqty(idx){
  $('#id'+idx).change(function(){
    hitungqty();
  });
}

function cetak(){
  var pesan     = '';
  var supplier  = $('#purchsupplier_edit').find('input[name="supplier"]').val();
  var pic       = $('#purchsupplier_edit').find('input[name="pic"]').val();
  var alamat    = $('#purchsupplier_edit').find('input[name="alamat"]').val();
  var contact   = $('#purchsupplier_edit').find('input[name="contact"]').val();
  var email     = $('#purchsupplier_edit').find('input[name="email"]').val();
  var ktp       = $('#purchsupplier_edit').find('input[name="ktp"]').val();
  var npwp      = $('#purchsupplier_edit').find('input[name="npwp"]').val();

  if (purchsupplier_edit.pkp.checked == true){
    var pkp     = "1";
  } else {
    var pkp     = "0";
  }

  if(supplier == ''){
    pesan = 'Supplier tidak boleh kosong\n';
  }
  else if(pic == ''){
    pesan = 'PIC tidak boleh kosong\n';
  }
  else if(alamat == ''){
    pesan = 'Alamat tidak boleh kosong\n';
  }
  else if(contact == ''){
    pesan = 'Contact tidak boleh kosong\n';
  }

  if(pesan != ''){
    alert('Maaf, ada kesalahan pengisian Form : \n'+pesan);
      return false;
  } else {
    var answer = confirm("Mau simpan data dan cetak datanya ?");
    if(answer){
      $('#purchsupplier_edit').attr('action', "purchsupplier_save.php?row="+baris1+"&pkp="+pkp+"&id="+<?=$_GET['id']?>).submit();
    }
  }
}

function tutup(){
  window.close();
}

// add row generate ------------------------
function generateKode(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="id"+index; idx.id="id"+index;
  return idx;
}

function generateProdukJasa(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="produk_jasa"+index; idx.id="produk_jasa"+index; idx.readOnly = "readonly"; idx.size="42"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateKategori(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="kategori"+index; idx.id="kategori"+index; idx.readOnly = "readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateQuotation(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="tgl_quotation"+index; idx.id="tgl_quotation"+index; idx.readOnly = "readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateSatuan(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="satuan"+index; idx.id="satuan"+index; idx.readOnly = "readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateHarga(index){
  var idx = document.createElement("input");
  idx.type="text"; idx.name="harga"+index; idx.id="harga"+index; idx.size="20"; idx.readOnly="readonly"; idx.class=""; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateDelete(index){
  var idx = document.createElement("input");
  idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")";
  return idx;
}

function delRow1(index){
  var element = document.getElementById("t1"+index); element.remove(); hitungqty();
}

// products autocomplete ----------------------
function get_products(a){
    $("#id"+a).autocomplete("purchsupplier_list.php", {width: 400});
    $("#id"+a).result(function(event, data, formatted){
      var nama = $('#id'+a).val();
      var id = nama.split(':');
      var id_pd = id[0];

      $.ajax({
        url       : 'purchsupplier_lookup_detail.php?id='+id_pd,
        dataType  : 'json',
        data      : 'nama='+formatted,
        success   : function(data){
          var products = data.produk_jasa;
            $('#produk_jasa'+a).val(products);
          var kategori = data.kategori;
            $('#kategori'+a).val(kategori);
          var quotation = new Date(data.tgl_quotation);
          var quotation_d = new Intl.DateTimeFormat('en', {day : '2-digit'}).format(quotation);
          var quotation_m = new Intl.DateTimeFormat('en', {month: '2-digit'}).format(quotation);
          var quotation_y = new Intl.DateTimeFormat('en', {year: 'numeric'}).format(quotation);
            $('#tgl_quotation'+a).val(`${quotation_d}/${quotation_m}/${quotation_y}`);
          var satuan = data.satuan;
            $('#satuan'+a).val(satuan);
          var harga = data.harga;
            $('#harga'+a).val(harga);
        }
      })
    });
  }

// add new row -----------------------------
function addNewRow1(){
  var tbl = document.getElementById('purchsupplier_detail');
  var row = tbl.insertRow(tbl.rows.length);
  row.id = 't1'+baris1;

  var td0 = document.createElement("td");
  var td1 = document.createElement("td");
  var td2 = document.createElement("td");
  var td3 = document.createElement("td");
  var td4 = document.createElement("td");
  var td5 = document.createElement("td");
  var td6 = document.createElement("td");

  td0.appendChild(generateKode(baris1));
  td1.appendChild(generateProdukJasa(baris1));
  td2.appendChild(generateKategori(baris1));
  td3.appendChild(generateQuotation(baris1));
  td4.appendChild(generateSatuan(baris1));
  td5.appendChild(generateHarga(baris1));
  td6.appendChild(generateDelete(baris1));

  row.appendChild(td0);
  row.appendChild(td1);
  row.appendChild(td2);
  row.appendChild(td3);
  row.appendChild(td4);
  row.appendChild(td5);
  row.appendChild(td6);

  document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')');
  triggerqty(baris1); get_products(baris1);
  baris1++;
}
<?php
  $sql_detail = "SELECT *, date_format(tgl_quotation,'%d-%m-%Y') AS tgl FROM `mst_produk` WHERE id_supplier=".$_GET['id']." AND deleted='0' ";
  $sql_detail = mysql_query($sql_detail);

  $i = 1;
  while($rs=mysql_fetch_array($sql_detail)){
    ?>
    addNewRow1();
    
    $('#id'+<?= $i ;?>).val('<?= $rs['id'].":".$rs['produk_jasa']." - ".$rs['satuan'] ;?>');
    $('#produk_jasa'+<?= $i ;?>).val('<?= $rs['produk_jasa'] ;?>');
    $('#kategori'+<?= $i ;?>).val('<?= $rs['kategori'] ;?>');
    $('#tgl_quotation'+<?= $i ;?>).val('<?= $rs['tgl'] ;?>');
    $('#satuan'+<?= $i ;?>).val('<?= $rs['satuan'] ;?>');
    $('#harga'+<?= $i ;?>).val('<?= $rs['harga'] ;?>');
    <?php
    $i++;
  }
?>

hitungqty();
</script>

