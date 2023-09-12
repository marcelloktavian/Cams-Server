<style>
  body{
    background-color: Moccasin;
  }

  tanggal{
    color: maroon; margin-left: 40px;
  }
</style>

<?php
  include "../../include/koneksi.php";

  $sql_mst        = "SELECT * FROM `mst_invoice` WHERE `id`=".$_GET['id']." AND `deleted`=0";
  $sql            = mysql_query($sql_mst) or die (mysql_error());
  $result         = mysql_fetch_array($sql);
    $id_mst               = $result['id'];
    $nomor_invoice        = $result['nomor_invoice'];
    $tanggal_invoice      = $result['tanggal_invoice'];
    $tanggal_jatuh_tempo  = $result['tanggal_jatuh_tempo'];
    $keterangan           = $result['keterangan'];
    $id_supplier          = $result['id_supplier'];
    $nama_supplier        = $result['supplier'];
    $qty                  = $result['qty'];

    $total                = $result['total'];
    $total_payment        = $result['total_payment'];

?>

<head>
  <title>EDIT PURCHASE INVOICE</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../asstes/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script src="../../assets/js/jquery-1.4.js" type="text/javascript"></script>
  <script src="../../assets/js/jquery.autocomplete.js" type="text/javascript"></script>
</head>

<body>
  <form id="invoice_edit" name="invoice_edit" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">EDIT PURCHASE INVOICE <span style="font-weight: bold;"><?= $nomor_invoice ;?></span></td>
        <td class="fontjudul">TOTAL QTY <input type="text" class="" name="total_qty_inv" id="total_qty_inv" style="text-align: right; font-size: 30px; backgorund-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">QTY PENDING <input tpye="text" class="" name="total_pending" id="total_pending" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_pending_value" id="total_pending_value" readonly /></td>
        <td class="fontjudul">TOTAL <input type="text" class="" name="total_inv" id="total_inv" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border:1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_inv_value" id="total_inv_value" readonly></td>
      </tr>
    </table>


    <table width="50%" cellpadding="0" cellspacing="0">
      <hr>
      <tr>
        <td class="fonttext">Nomor Invoice</td>
        <td><input type="text" class="inputForm" name="nomor_invoice" id="nomor_invoice" value="<?= $nomor_invoice ?>" readonly/></td>
      </tr>
      <tr>
        <td class="fonttext">Supplier</td>
        <td><input type="text" class="inputForm" name="supplier" id="supplier" value="<?= $id_supplier.':'.$nama_supplier ?>" readonly/></td>
      </tr>
      <tr>
        <td class="fonttext">Tanggal Invoice</td>
        <td><input type="date" class="inputForm" name="tanggal_invoice" id="tanggal_invoice" value="<?= $tanggal_invoice ?>"/></td>
      </tr>
      <tr>
        <td class="fonttext">Tanggal Jatuh Tempo</td>
        <td><input type="date" class="inputForm" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="<?= $tanggal_jatuh_tempo ?>"></td>
      </tr>
      <tr height="1">
        <td colspan="100%"><hr /></td>
      </tr>
    </table>

    <table width="100%" id="invoice_detail">
      <thead>
        <tr>
          <td width="1%"></td>
          <td width="15%" class="fonttext">Nomor Dokumen</td>
          <td width="20%" class="fonttext">Produk / Jasa</td>
          <td width="8%" class="fonttext">Qty</td>
          <td width="8%" class="fonttext">Qty Terproses</td>
          <td width="8%" class="fonttext">Qty Pending</td>
          <td width="10%" class="fonttext">Satuan</td>
          <td width="10%" class="fonttext">DPP/Unit</td>
          <td width="20%" class="fonttext">Sub Total</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table>
      <tr>
        <td colspan="100%" class="fonttext">Keterangan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="keterangan" id="keterangan" style="height: 80px; width: 640px;" value="<?= $keterangan ?>"><?= $keterangan ?></textarea></td>
      </tr>
    </table>
  </form>

  <table>
    <tr>
      <td>
        <p><input type='image' value='Tambah Baris' src='../../assets/images/tambah_baris.png' id='baru' onClick='addNewRow1()'/></p>
      </td>
      <td>
        <p><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
      </td>
      <td>
        <p><input type='image' value='batal' src='../../assets/images/batal.png' id='baru' onClick='tutup()'/></p>
      </td>
    </tr>
  </table>
</body>

<script>

  // general function ------------------------

  function hitungSubTotal(){
    for(var i=1; i<=baris1; i++){
      var kode = $('#id'+i).val();
      if(kode != undefined && kode != ''){
        var subtotal_item =(parseFloat($('#qty_inv'+i).val())*parseFloat($('#dpp_unit'+i).val()));
        var ppn_item = $('#persen_ppn'+i).val();
        $('#subtotal_inv'+i).val(Math.ceil(parseFloat(subtotal_item))+Math.floor((parseInt(subtotal_item*ppn_item/100))));
      }
    }
  }

  function hitungTotal(){
    var totalinvoice = 0;
    var totalqty = 0;
    var totalpending = 0;

    for(var i=1; i<=baris1; i++){
      var kode = $('#id'+i).val();
      if(kode != undefined && kode != ''){
        totalinvoice = totalinvoice + parseFloat($('#subtotal_inv'+i).val());
        totalqty = totalqty + parseFloat($('#qty_inv'+i).val());
        totalpending = totalpending + parseFloat($('#qty_remaining'+i).val());
      }
    }

    $('#total_inv').val(intToIDR(parseFloat(totalinvoice)));
    $('#total_inv_value').val(parseFloat(totalinvoice));
    $('#total_pending').val(parseFloat(totalpending));
    $('#total_qty_inv').val(parseFloat(totalqty));
  }

  function triggerTotal(idx){
    $('#qty_inv'+idx).keyup(function(){
      checkMax(idx); hitungSubTotal(); hitungTotal();
    });

    $('#qty_inv'+idx).change(function(){
      hitungSubTotal(); hitungTotal();
    });

    $('#subtotal_inv'+idx).change(function(){
      hitungTotal();
    });
  }

  function checkMax(idx){
    if(parseFloat($('#qty_inv'+idx).val()) >= parseFloat($('#qty_remaining'+idx).val())+parseFloat($('#qty_inv_hidden'+idx).val())){
      $('#qty_inv'+idx).val(parseFloat($('#qty_remaining'+idx).val())+parseFloat($('#qty_inv_hidden'+idx).val()));
    }
    else if($('#qty_inv'+idx).val() == ''){
      $('#qty_inv'+idx).val(0);
    }
    else if($('#qty_inv'+idx).val() > 0){
      if(($('#qty_inv'+idx).val()).substring(0,1) == "0"){
        $('#qty_inv'+idx).val($('#qty_inv'+idx).val().substring(1));
      }
    }
  }

  function popDetail(idx){
    var width   = 1200;
    var height  = 600;
    var left    = (screen.width - width)/2;
    var top     = (screen.height - height)/2;
    var params  = 'width='+width+', height='+height+',scrollbars=yes';
    params     += ', top='+top+', left='+left;
    window.open('list_podetail.php?sup='+<?= $id_supplier ?>+'&curr='+idx,'',params);
  }

  function intToIDR(val){
    return(val.toLocaleString("id-ID", {style:"currency", currency:"IDR"}));
  }

  function cetak(){
    var pesan         = "";
    
    var nomor_invoice       = $('#invoice_edit').find('input[name="nomor_invoice"]').val();
    var supplier            = $('#invoice_edit').find('input[name="supplier"]').val();
    var tanggal_invoice     = $('#invoice_edit').find('input[name="tanggal_invoice"]').val();
    var tanggal_jatuh_tempo = $('#invoice_edit').find('input[name="tanggal_jatuh_tempo"]').val();
    var total_qty           = $('#total_qty_inv').val();

    if(nomor_invoice == ''){
      pesan = 'Nomor Invoice tidak boleh kosong\n';
    }
    else if(supplier == ''){
      pesan = 'Supplier tidak boleh kosong\n';
    }
    else if(tanggal_invoice == ''){
      pesan = 'Tangal Invoice tidak boleh kosong\n';
    }
    else if(tanggal_jatuh_tempo == ''){
      pesan = 'Tanggal Jatuh Tempo tidak boleh kosong\n';
    }
    else if(parseInt(total_qty) < 1){
      pesan = 'Total tidak bisa nol\n';
    }

    if(pesan != ''){
      alert('Maaf, ada kesalahan pengisian Form : \n'+pesan);
      return false;
    }
    else {
      var answer = confirm("Mau simpan data dan cetak datanya ?");
      if(answer){
        $('#invoice_edit').attr('action',"po_invoice_update.php?row="+baris1+"&id=<?= $_GET['id'] ?>").submit();
      }
    }
  }

  function tutup(){
    window.close();
  }

  // add row generate ------------------------
  function generateKode(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="id"+index; idx.id="id"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="20"; return idx;
  }

  function generatePersenPPN(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="persen_ppn"+index; idx.id="persen_ppn"+index; return idx;
  }

  function generateInvoiceDetail(index) {
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "kodeGet"+index+""; idx.id = "kodeGet"+index+""; idx.size = "40"; idx.value = "+"; return idx;
  }

  function generateIdPO(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="id_po"+index; idx.id="id_po"+index; return idx;
  }

  function generateNomorDokumen(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="nomor_dokumen"+index; idx.id="nomor_dokumen"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; return idx;
  }

  function generateIdProduk(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="id_produk"+index; idx.id="id_produk"+index; return idx;
  }

  function generateProdukJasa(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="produk_jasa"+index; idx.id="produk_jasa"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="40"; return idx;
  }

  function generateQuantity(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="qty_inv"+index; idx.id="qty_inv"+index; idx.size="10"; idx.style.textAlign = "right"; idx.value="0"; return idx;
  }

  function generateQuantityHidden(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="qty_inv_hidden"+index; idx.id="qty_inv_hidden"+index; idx.size="10"; idx.style.textAlign = "right"; idx.value="0"; return idx;
  }

  function generateQuantityPayment(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="qty_payment"+index; idx.id="qty_payment"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="10"; idx.style.textAlign = "right"; idx.value="0"; return idx;
  }

  function generateQuantityRemaining(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="qty_remaining"+index; idx.id="qty_remaining"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="10"; idx.style.textAlign = "right"; idx.value="0"; return idx;
  }

  function generateHargaSatuan(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="dpp_unit"+index; idx.id="dpp_unit"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.style.textAlign = "right"; idx.size="15"; idx.value="0"; return idx;
  }

  function generateSatuan(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="satuan"+index; idx.id="satuan"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="15"; return idx;
  }

  function generateSubTotal(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="subtotal_inv"+index; idx.id="subtotal_inv"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; idx.style.textAlign = "right"; idx.value="0"; return idx;
  }

  function generateIdAkun(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="idAkun"+index; idx.id="idAkun"+index; return idx;
  }

  function generateNomorAkun(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="nomorAkun"+index; idx.id="nomorAkun"+index; idx.size="15" ;return idx;
  }

  function generateNamaAkun(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="namaAkun"+index; idx.id="namaAkun"+index;  idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; return idx;
  }

  function generateDelete(index){
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")"; return idx;
  }

  function delRow1(index){
    var element = document.getElementById("t1"+index); element.remove(); hitungTotal();
  }

  var baris1 = 1;
  // add new row -----------------------------
  // addNewRow1();

  function addNewRow1(){
    var tbl = document.getElementById('invoice_detail');
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

    td9.hidden = "hidden";

    td0.appendChild(generateInvoiceDetail(baris1));
    td1.appendChild(generateIdPO(baris1));
    td1.appendChild(generateNomorDokumen(baris1));
    td1.appendChild(generatePersenPPN(baris1));
    td1.appendChild(generateKode(baris1));
    td2.appendChild(generateIdProduk(baris1));
    td2.appendChild(generateProdukJasa(baris1));
    td3.appendChild(generateQuantity(baris1));
    td3.appendChild(generateQuantityHidden(baris1));
    td4.appendChild(generateQuantityPayment(baris1));
    td5.appendChild(generateQuantityRemaining(baris1));
    td6.appendChild(generateSatuan(baris1));
    td7.appendChild(generateHargaSatuan(baris1));
    td8.appendChild(generateSubTotal(baris1));
    td9.appendChild(generateIdAkun(baris1));
    td9.appendChild(generateNomorAkun(baris1));
    td9.appendChild(generateNamaAkun(baris1));
    td10.appendChild(generateDelete(baris1));

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

    document.getElementById('kodeGet'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');

    document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); triggerTotal(baris1);
    baris1++;

    hitungTotal();
  }

  <?php

  $sql_detail       = "SELECT x.*,y.dokumen FROM(SELECT a.*,b.qty AS qty_po, b.qty_terbayar FROM `det_invoice` a LEFT JOIN `det_po` b ON a.id_detail=b.id AND a.id_produk=b.id_produk WHERE a.id_invoice=".$_GET['id']." AND a.`deleted`=0) AS x JOIN (SELECT a.id as id_join, a.dokumen FROM `mst_po` a LEFT JOIN `det_po` b ON a.id=b.id_po WHERE a.deleted=0 AND b.deleted=0) AS y ON x.id_po=y.id_join GROUP BY x.id";

  $sql_detail       = mysql_query($sql_detail);

  $i  = 1;
  while($rs=mysql_fetch_array($sql_detail)){
    ?>

    addNewRow1();

    $('#id'+<?= $i ?>).val('<?= $rs['id'] ?>');
    $('#persen_ppn'+<?= $i ?>).val('<?= $rs['persen_ppn'] ?>');
    $('#id_po'+<?= $i ?>).val('<?= $rs['id_po'] ?>');
    $('#nomor_dokumen'+<?= $i ?>).val('<?= $rs['dokumen'] ?>');
    $('#id_produk'+<?= $i ?>).val('<?= $rs['id_produk'] ?>');
    $('#produk_jasa'+<?= $i ?>).val('<?= $rs['nama_produk'] ?>');
    $('#qty_inv'+<?= $i ?>).val('<?= $rs['qty'] ?>');
    $('#qty_inv_hidden'+<?= $i ?>).val('<?= $rs['qty'] ?>');
    $('#qty_payment'+<?= $i ?>).val('<?= $rs['qty_terbayar'] ?>');
    $('#qty_remaining'+<?= $i ?>).val('<?= $rs['qty_po']-$rs['qty_terbayar'] ?>');
    $('#dpp_unit'+<?= $i ?>).val('<?= $rs['price'] ?>');
    $('#satuan'+<?= $i ?>).val('<?= $rs['satuan'] ?>');
    $('#subtotal_inv'+<?= $i ?>).val('<?= $rs['subtotal'] ?>');

    <?php
    $i ++;
  }
  ?>
hitungTotal();

</script>