<style>
  body{
    background-color: Moccasin;
  }

  tanggal{
    color: maroon; margin-left: 40px;
  }
</style>

<head>
  <title>ADD AP</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<body>
  <form id="ap_add" name="ap_add" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">ADD AP</td>
        <td class="fontjudul">TOTAL QTY <input type="text" class="" name="total_qty_ap" id="total_qty_ap"  style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">TOTAL <input type="text" class="" name="total_ap" id="total_ap" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_ap_value" id="total_ap_value" readonly></td>
        <td class="fontjudul">TOTAL PENDING <input tpye="text" class="" name="total_pending" id="total_pending" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_pending_value" id="total_pending_value" readonly /></td>
      </tr>
    </table>

    <hr />

    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Supplier</td>
        <td><input type="text" class="inputForm" name="supplier" id="supplier" /></td>
        <td class="fonttext">Tanggal AP</td>
        <td><input type="date" class="inputForm" name="tanggal_ap" id="tanggal_ap"></td>
      </tr>
      <tr>
        <td class="fonttext">Akun Kredit</td>
        <td><input type="text" class="inputForm" name="akun" id="akun" /></td>
      </tr>
      <tr height="1">
        <td colspan="100%"><hr /></td>
      </tr>
    </table>

    <table width="100%" id="ap_detail">
      <thead>
        <tr>
          <td width="1%" class="fonttext"></td>
          <td width="20%" class="fonttext">Nomor Invoice</td>
          <td width="15%" class="fonttext">Tanggal Invoice</td>
          <td width="15%" class="fonttext">Tanggal Jatuh Tempo</td>
          <td width="15%" class="fonttext">Qty</td>
          <td width="15%" class="fonttext">Total</td>
          <td width="15%" class="fonttext">Total Terproses</td>
          <td width="15%" class="fonttext">Total Pending</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table>
      <tr>
        <td colspan="100%" class="fonttext">Keterangan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="keterangan" id="keterangan" style="height: 80px; width: 640px;" /></textarea></td>
      </tr>
    </table>
  </form>

  <table>
    <tr>
      <td>
        <p><input type='image' value='Tambah Baris' src='../../assets/images/tambah_baris.png' id='baru' onClick='addNewRow1()' /></p>
      </td>
      <td>
        <p><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
      </td>
      <td>
        <p><input type='image' value='batal' src='../../assets/images/batal.png' id='baru' onClick='tutup()' /></p>
      </td>
    </tr>
  </table>
</body>

<script>
  var sup_q   = "";
  $(document).ready(function(){
    $("#supplier").autocomplete("posupplier_list.php", {width: 400});

    $('#supplier').result(function(event, data, formatted){
      var sup   = ($('#supplier').val()).split(':');
      sup_q     = sup[0];

      $.ajax({
        url       : 'posupplier_lookup_detail.php?id='+sup_q,
        dataType  : 'json',
        data      : 'nama='+formatted,
        success   : function(data){
          var id_akun = data.id_akun;
          var nama_akun = data.nama_akun;
          var nomor_akun = data.nomor_akun;
          $('#akun').val(id_akun+':'+nomor_akun+' | '+nama_akun);
        }
      });

      for(var i = 0; i<=baris1; i++){
        var element = $('#t1'+i);
        if(element != null){
          element.remove(); hitungTotal();
        }
      }
      baris1 = 1;
      addNewRow1();
    });

    $("#akun").autocomplete("COALovParent.php?", {
      width: 178
    });

    $("#akun").result(function (event, data, formatted) {
      var nama = document.getElementById("akun").value;
      for (var i = 0; i < nama.length; i++) {
        var id = nama.split(';');
        if (id[1] == "") continue;
        var id_pd = id[1];
      }

      $.ajax({
        url: 'COALoVdet.php?id=' + id_pd,
        dataType: 'json',
        data: "nama=" + formatted,
        success: function (data) {
          var id = data.id;
          var noakun = data.noakun;
          var nama = data.nama;
          $("#akun").val(id+":"+noakun+" | "+nama);
        }
      });
    });
  });

  function checkMax(idx){
    if(parseFloat($('#total_inv'+idx).val()) >= parseFloat($('#total_sisa_inv'+idx).val())){
      $('#total_inv'+idx).val($('#total_sisa_inv'+idx).val());
    }
    else if($('#total_inv'+idx).val() == ''){
      $('#total_inv'+idx).val(0);
    }
    else if($('#total_inv'+idx).val() > 0){
      if(($('#total_inv'+idx).val()).substring(0,1) == "0"){
        $('#total_inv'+idx).val($('#total_inv'+idx).val().substring(1));
      }
    }
  }

  function hitungTotal(){
    var totalinvoice = 0;
    var totalqty = 0;
    var totalpending = 0;

    for(var i=1; i<=baris1; i++){
      var kode = $('#id_invoice'+i).val();
      if(kode != undefined && kode != ''){
        console.log($('#total_inv'+i).val());
        console.log($('#qty'+i).val());
        totalinvoice = totalinvoice + parseFloat($('#total_inv'+i).val());
        totalqty = totalqty + parseFloat($('#qty'+i).val());
        totalpending = totalpending + parseFloat($('#total_sisa_inv'+i).val());
      }
    }

    $('#total_ap').val(intToIDR(parseFloat(totalinvoice)));
    $('#total_ap_value').val(parseFloat(totalinvoice));
    $('#total_pending').val(intToIDR(parseFloat(totalpending)));
    $('#total_pending_value').val(parseFloat(totalpending));
    $('#total_qty_ap').val(parseFloat(totalqty));
  }

  function triggerTotal(idx){
    $('#total_inv'+idx).keyup(function(){
      checkMax(idx); hitungTotal();
    });
  }

  function popDetail(idx){
    var width   = screen.width;
    var height  = screen.height;
    var params  = 'width='+width+', height='+height+',scrollbars=yes';
    window.open('list_invoicedetail.php?sup='+sup_q+'&curr='+idx,'',params);
  }

  function cetak(){
    var pesan = "";

    var tanggal_ap    = $('#ap_add').find('input[name="tanggal_ap"]').val();
    var supplier      = $('#ap_add').find('input[name="supplier"]').val();
    var akun          = $('#ap_add').find('input[name="akun"]').val();
    var total_ap      = $('#total_ap_value').val();

    for(var i = 0; i<baris1; i++){
      if((typeof $('#id_invoice'+i) != undefined) && ($('#id_invoice'+i).val() != '')){
        if(parseInt($('#total_inv'+i).val()) == 0){
          pesan = "Total pembayaran nomor invoice - "+ $('#nomor_invoice'+i).val() +" masih kosong.";
          i = baris1;
        }
      }
    }

    if(tanggal_ap == ''){
      pesan = 'Tanggal AP tidak boleh kosong\n';
    }
    else if(supplier == ''){
      pesan = 'Supplier tidak boleh kosong\n';
    }
    else if(akun == ''){
      pesan = 'Akun tidak boleh kosong\n';
    }
    else if(parseInt(total_ap) == 0){
      pesan = 'Total tidak bisa nol\n';
    }

    if(pesan != ''){
      alert('Maaf, ada kesalahan pengisian Form : \n'+pesan);
      return false;
    }
    else{
      var answer = confirm("Mau simpan data dan cetak datanya ?");
      if(answer){
        $('#ap_add').attr('action',"ap_save.php?row="+baris1).submit();
      }
    }
  }

  function tutup(){
    window.close();
  }

  // add row generate ------------------------
  function generateAddDetail(index) {
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "kodeGet"+index+""; idx.id = "kodeGet"+index+""; idx.size = "40"; idx.value = "+"; return idx;
  }

  function generateIDInvoice(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="id_invoice"+index; idx.id="id_invoice"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateNomorInvoice(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="nomor_invoice"+index; idx.id="nomor_invoice"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="40"; return idx;
  }

  function generateTanggalInvoice(index){
    var idx = document.createElement("input");
    idx.type="date"; idx.name="tanggal_invoice"+index; idx.id="tanggal_invoice"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; return idx;
  }

  function generateTanggalJatuhTempo(index){
    var idx = document.createElement("input");
    idx.type="date"; idx.name="tanggal_jatuh_tempo"+index; idx.id="tanggal_jatuh_tempo"+index; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; return idx;
  }

  function generateQty(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="qty"+index; idx.id="qty"+index; idx.size="10"; idx.style.textAlign = "right"; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.value="0"; return idx;
  }

  function generateTotal(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="total_inv"+index; idx.id="total_inv"+index; idx.size="20"; idx.style.textAlign = "right"; idx.readOnly="readonly"; idx.style.border="#4f4f4f dotted 1px"; idx.style.backgroundColor="#dcdcdc"; idx.value="0"; return idx;
  }

  function generateTotalTerbayar(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="total_terbayar_inv"+index; idx.id="total_terbayar_inv"+index; idx.size="20"; idx.style.textAlign = "right"; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.value="0"; return idx;
  }

  function generateTotalSisa(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="total_sisa_inv"+index; idx.id="total_sisa_inv"+index; idx.size="20"; idx.style.textAlign = "right"; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.value="0"; return idx;
  }

  function generateDelete(index){
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")"; return idx;
  }

  function delRow1(index){
    var element = document.getElementById("t1"+index); element.remove(); hitungTotal();
  }

  function intToIDR(val){
    return(val.toLocaleString("id-ID", {style:"currency", currency:"IDR"}));
  }

  var baris1 = 1;
  // add new row -----------------------------
  addNewRow1();

  function addNewRow1(){
    var tbl = document.getElementById('ap_detail');
    var row = tbl.insertRow(tbl.rows.length);
    row.id  = 't1'+baris1;

    var td0 = document.createElement("td");
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
    var td7 = document.createElement("td");
    var td8 = document.createElement("td");

    td0.appendChild(generateAddDetail(baris1));
    td0.appendChild(generateIDInvoice(baris1));
    td1.appendChild(generateNomorInvoice(baris1));
    td2.appendChild(generateTanggalInvoice(baris1));
    td3.appendChild(generateTanggalJatuhTempo(baris1));
    td4.appendChild(generateQty(baris1));
    td5.appendChild(generateTotal(baris1));
    td6.appendChild(generateTotalTerbayar(baris1));
    td7.appendChild(generateTotalSisa(baris1));
    td8.appendChild(generateDelete(baris1));

    row.appendChild(td0);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    row.appendChild(td6);
    row.appendChild(td7);
    row.appendChild(td8);

    document.getElementById('kodeGet'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');

    document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); triggerTotal(baris1);
    baris1++;

    hitungTotal();
  }
</script>