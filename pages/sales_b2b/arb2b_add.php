<style>
  body{
    background-color: Moccasin;
  }

  tanggal{
    color: maroon; margin-left: 40px;
  }

  .containerQty{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .horizontalContainer{
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    column-gap: 5px;
  }

  .bold{
    font-weight: bold;
  }

  .title{
    background-color: #eaeaea;
    border: 2px solid #eaeaea;
  }

  .title:focus{
    outline: none;
  }

  .blue{
    color: blue;
  }

  .red{
    color: red;
  }

  td{
    white-space: nowrap;
  }

  .no-margin{
    margin: 0 !important; gap: 0 !important; padding: 0 !important;
  }

  .no-margin *{
    margin: 0 !important; gap: 0 !important; padding: 0 !important;
  }
</style>

<head>
  <title>ADD AR B2B</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<body>
  <form id="b2bar_add" name="b2bar_add" action="" method="">
    <table width="100%">
      <tr>
        <td class="fontjudul">ADD AR B2B</td>
        <td class="fontjudul">TOTAL <input type="text" class="" name="total_ap" id="total_ap" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_ap_value" id="total_ap_value" readonly></td>
        <td class="fontjudul">TOTAL PENDING <input tpye="text" class="" name="total_pending" id="total_pending" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_pending_value" id="total_pending_value" readonly /></td>
      </tr>
    </table>

    <hr />
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Nomor AR B2B</td>
        <td><input type="text" class="inputForm" placeholder="(dibuat otomatis oleh sistem)" readonly /></td>
        <td class="fonttext">Customer</td>
        <td><input type="text" class="inputForm" name="customer" id="customer" /></td>
      </tr>
      <tr>
        <td class="fonttext no-margin">Tanggal AR</td>
        <td><input type="date" class="inputForm" name="tanggal_b2bar" /></td>
        <td class="fonttext">Akun Kredit</td>
        <td><input type="text" class="inputForm" name="akun" id="akun" /></td>
      </tr>
      <tr heigth="1">
        <td colspan="100%"><hr /></td>
      </tr>
    </table>

    <table width="100%" id="b2bar_detail">
      <thead>
        <tr>
          <td width="1%" class="fonttext"></td>
          <td width="15%" class="fonttext">ID B2B DO / RET</td>
          <td width="15%" class="fonttext">Customer</td>
          <td width="15%" class="fonttext">Tanggal DO / RET</td>
          <td width="15%" class="fonttext">Total DO / RET</td>
          <td width="15%" class="fonttext">Total DO / RET Pending</td>
          <td width="15%" class="fonttext">Total DO / RET Sisa</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table>
      <tr>
        <td colspan="100%" class="fonttext">Keterangan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="keterangan" id="keterangan" style="height: 80px; width: 640px;"></textarea></td>
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
  $(document).ready(function(){
    $('#customer').autocomplete("trb2breturn_cust_list.php", {width: 400});

    $("#akun").autocomplete("lookup_akun.php?", {
      width: 178
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

  function popDetail(idx){
    if(document.getElementById("customer").value == ''){
      alert('Customer harus diisi terlebih dahulu');
    }else{
      var custid = document.getElementById("customer").value.split(':');
      var width   = screen.width;
      var height  = screen.height;
      var params  = 'width='+width+', height='+height+',scrollbars=yes';
      window.open('arb2b_lov.php?baris='+idx+'&cust='+custid[0],'',params);
    }
  }

  // ADD ROW GENERATE ----------

  function generateAddDetail(index){
    let idx = document.createElement("input");
    idx.type = "button"; idx.name = "kodeGet"+index+""; idx.id = "kodeGet"+index+""; idx.value = "+"; idx.classList.add("input") ; return idx;
  }

  function generateIDB2B(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "idarb2b"+index+""; idx.id = "idarb2b"+index+""; idx.readOnly="readonly"; idx.classList.add("text-center"); return idx;
  }

  function generateTypeB2B(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "typearb2b"+index+""; idx.id = "typearb2b"+index+""; idx.readOnly="readonly"; idx.classList.add("text-center"); return idx;
  }

  function generateNumB2B(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "numb2b"+index+""; idx.id = "numb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size=20; idx.classList.add("text-left"); return idx;
  }

  function generateCustomer(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "customer"+index+""; idx.id = "customer"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-left"); idx.size=30; return idx;
  }

  function generateTanggal(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "tanggalb2b"+index+""; idx.id = "tanggalb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-left"); idx.size=20; return idx;
  }

  function generateTotal(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "totalb2bDisplay"+index+""; idx.id = "totalb2bDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateTotalHidden(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "totalb2b"+index+""; idx.id = "totalb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generatePending(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "totalb2bpendingDisplay"+index+""; idx.id = "totalb2bpendingDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generatePendingHidden(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "totalb2bpending"+index+""; idx.id = "totalb2bpending"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateSisa(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "totalb2bsisaDisplay"+index+""; idx.id = "totalb2bsisaDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateSisaHidden(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "totalb2bsisa"+index+""; idx.id = "totalb2bsisa"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateKeterangan(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "keteranganb2b"+index+""; idx.id = "keteranganb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size=50; return idx;
  }

  function generateDelete(index){
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")"; return idx;
  }

  function delRow1(index){
    var element = document.getElementById("t1"+index); element.remove();
  }

  let baris1 = 1;

  function addNewRow1(){
    let tbl = document.getElementById('b2bar_detail');
    let row = tbl.insertRow(tbl.rows.length);
    row.id  = 't1'+baris1;

    var td0 = document.createElement("td");
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
    var td7 = document.createElement("td");

    td0.appendChild(generateAddDetail(baris1));
    td1.appendChild(generateIDB2B(baris1));
    td1.appendChild(generateTypeB2B(baris1));
    td1.appendChild(generateNumB2B(baris1));
    td2.appendChild(generateCustomer(baris1));
    td3.appendChild(generateTanggal(baris1));
    td4.appendChild(generateTotal(baris1));
    td4.appendChild(generateTotalHidden(baris1));
    td5.appendChild(generatePending(baris1));
    td5.appendChild(generatePendingHidden(baris1));
    td6.appendChild(generateSisa(baris1));
    td6.appendChild(generateSisaHidden(baris1));
    td7.appendChild(generateDelete(baris1));

    row.appendChild(td0);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    row.appendChild(td6);
    row.appendChild(td7);

    document.getElementById('kodeGet'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');
    document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); 

    baris1 ++;
  }

  addNewRow1();
</script>