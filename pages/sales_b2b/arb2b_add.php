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
      </tr>
    </table>

    <hr />
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Nomor AR B2B</td>
        <td><input type="text" class="inputForm" placeholder="(dibuat otomatis oleh sistem)" readonly /></td>
        <td class="fonttext no-margin">Tanggal AR</td>
        <td><input type="date" class="inputForm" name="tanggal_b2bar" /></td>
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
          <td width="15%" class="fonttext">Tanggal DO / RET</td>
          <td width="15%" class="fonttext">Total DO / RET</td>
          <td class="fonttext">Keterangan</td>
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
  function popDetail(idx){
    var width   = screen.width;
    var height  = screen.height;
    var params  = 'width='+width+', height='+height+',scrollbars=yes';
    window.open('arb2b_lov.php?baris='+idx,'',params);
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
    idx.type = "text"; idx.name = "numb2b"+index+""; idx.id = "numb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size=40; idx.classList.add("text-center"); return idx;
  }

  function generateTanggal(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "tanggalb2b"+index+""; idx.id = "tanggalb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-center"); idx.size=40; return idx;
  }

  function generateTotal(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "totalb2b"+index+""; idx.id = "totalb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ;  idx.size=40; return idx;
  }

  function generateKeterangan(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "keteranganb2b"+index+""; idx.id = "keteranganb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size=160; return idx;
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

    td0.appendChild(generateAddDetail(baris1));
    td1.appendChild(generateIDB2B(baris1));
    td1.appendChild(generateTypeB2B(baris1));
    td1.appendChild(generateNumB2B(baris1));
    td2.appendChild(generateTanggal(baris1));
    td3.appendChild(generateTotal(baris1));
    td4.appendChild(generateKeterangan(baris1));
    td5.appendChild(generateDelete(baris1));

    row.appendChild(td0);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);

    document.getElementById('kodeGet'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');
    document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); 

    baris1 ++;
  }

  addNewRow1();
</script>