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

  .input{
    height: 3em;
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
  <title>ADD B2B Return</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<body>
  <form id="b2breturn_add" name="b2breturn_add" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">ADD B2B Return</td>
        <td class="fontjudul">TOTAL QTY <input type="text" class="" name="total_qty_b2breturn" id="total_qty_b2breturn"  style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">TOTAL RETURN<input type="text" class="" name="total_b2breturn" id="total_b2breturn" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_b2breturn_value" id="total_b2breturn_value" readonly></td>
      </tr>
    </table>

    <hr />
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Nomor B2B Return</td>
        <td><input type="text" class="inputForm" placeholder="(dibuat otomatis oleh sistem)" readonly/></td>
        <td class="fonttext">Type</td>
        <td><select type="text" class="inputForm" id="type_b2breturn" name="type_b2breturn">
          <option value="1">SOL - Product Sol Sepatu</option>
          <option value="2">SDC - Contract Manufacturing Camou</option>
          <option value="3">SDL - Contract Manufacturing Non Camou</option>
        </select></td>
      </tr>
      <tr>
        <td class="fonttext">Customer</td>
        <td><input type="text" id="customer_b2breturn" name="customer_b2breturn" class="inputForm"></td>
        <td class="fonttext no-margin">Tanggal Return</td>
        <td><input type="date" class="inputForm" name="tanggal_b2breturn" id="tanggal_b2breturn"></td>
      </tr>
      <tr height="1">
        <td colspan="100%"><hr /></td>
      </tr>
    </table>
    
    <p class="fonttext">*(keterangan warna) abu abu : qty b2b | putih   : qty return</p>

    <table width="100%" id="b2breturn_detail">
      <thead>
        <tr>
          <td width="1%" class="fonttext"></td>
          <td width="15%" class="fonttext">ID B2B</td>
          <td width="15%" class="fonttext">Nama Produk</td>
          <td width="15%" class="fonttext">
            <?php
              for($i = 31; $i<47; $i++){
                ?>
                  <input size=4 value=<?= $i ?> readonly class="text-center bold title"/>
                <?php
              }
            ?>
          </td>
          <td width="15%" class="fonttext">Harga</td>
          <td width="15%" class="fonttext">Subtotal</td>
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
function intToIDR(val){
  return(val.toLocaleString("id-ID", {style:"currency", currency:"IDR"}));
}

function returnTotalCount(){
  let count = 0;

  for(let i = 1 ; i<=baris1; i++){
    if(document.getElementById('kodeGet'+i) != undefined && (document.getElementById('idb2b'+i) != undefined && document.getElementById('idb2b'+i) != "")){
      if(parseInt(document.getElementById("total"+i).value) > 0){
        count = count + parseInt(document.getElementById("total"+i).value);
      }
    }
  }

  document.getElementById('total_b2breturn').value = intToIDR(count);
  document.getElementById('total_b2breturn_value').value = count;
}

function qtyTotalCount(){
  let count = 0;

  for(let i = 1 ; i<=baris1; i++){
    if(document.getElementById('kodeGet'+i) != undefined && (document.getElementById('idb2b'+i) != undefined && document.getElementById('idb2b'+i) != "")){
      for(let j = 31; j<47; j++){
        count = count + parseInt(document.getElementById("qty-"+i+"-"+j).value);
      }
    }
  }

  document.getElementById('total_qty_b2breturn').value = count;
}

function subtotalCount(idx){
  let subtotal = 0;
  for(let i = 31; i<47; i++){
    subtotal = subtotal + parseInt(document.getElementById("qty-"+idx+"-"+i).value);
  }
  document.getElementById("total"+idx).value = subtotal*document.getElementById("harga"+idx).value;

  qtyTotalCount(); returnTotalCount();
}

const customer = document.getElementById('customer_b2breturn');
const type = document.getElementById('type_b2breturn');

function popDetail(idx){
  var width   = screen.width;
  var height  = screen.height;
  var params  = 'width='+width+', height='+height+',scrollbars=yes';
  window.open('trb2breturn_lov.php?cust='+(customer.value).split(' : ')[0]+'&type='+type.value+'&baris='+idx,'',params);
}

// save function --------------------
function cetak(){
  let pesan = "";

  const type = document.getElementById('type_b2breturn').value;
  const customer = document.getElementById('customer_b2breturn').value;
  const tanggal = document.getElementById('tanggal_b2breturn').value;

  if(type == ""){
    pesan = "Tipe Return tidak boleh kosong !";
  }
  else if(customer == ""){
    pesan = "Nama Customer tidak boleh kosong !";
  }
  else if(tanggal == ""){
    pesan = "Tanggal return tidak boleh kosong !";
  }
  else if(baris1 == 1){
    pesan = "Barang return tidak boleh kosong !";
  }

  if(pesan != ""){
    alert("Maaf, ada kesalahan pengisian form : \n"+pesan); return false;
  } else {
    let answer = confirm('Mau simpan data dan cetak datanya ?');
    $('#b2breturn_add').attr('action',"trb2breturn_save.php?row="+baris1).submit();
  }
}

// add row generate ------------------
function generateAddDetail(index){
  let idx = document.createElement("input");
  idx.type = "button"; idx.name = "kodeGet"+index+""; idx.id = "kodeGet"+index+""; idx.value = "+"; idx.classList.add("input") ; return idx;
}

function generateIdB2BReturnDetail(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "id_b2breturn_det"+index+""; idx.id = "id_b2breturn_det"+index+""; idx.readOnly="readonly"; return idx;
}

function generateIdB2BDO(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "idb2b"+index+""; idx.id = "idb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="10"; return idx;
}

function generateIdMasterB2BDO(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "idmstb2b"+index+""; idx.id = "idmstb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; return idx;
}

function generateIdDetailB2BDO(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "iddetb2b"+index+""; idx.id = "iddetb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; return idx;
}

function generateIdProduk(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "idproduk"+index+""; idx.id = "idproduk"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ;return idx;
}

function generateNamaProduk(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "namaproduk"+index+""; idx.id = "namaproduk"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="30"; return idx;
}

function generateContainer(){
  let container = document.createElement("div");
  container.classList.add('horizontalContainer'); return container;
}

function generateQty(index, n){
  let container = document.createElement("div");
  container.classList.add('containerQty');

  let idItem = document.createElement("input");
  idItem.type = "hidden"; idItem.name = "idItem-"+index+"-"+n; idItem.id = "idItem-"+index+"-"+n; idItem.readOnly="readonly";

  let qtyBefore = document.createElement("input");
  qtyBefore.type = "text"; qtyBefore.name = "id-"+index+"-"+n; qtyBefore.id = "id-"+index+"-"+n; qtyBefore.readOnly="readonly"; qtyBefore.size=4; qtyBefore.style.backgroundColor="#dcdcdc"; qtyBefore.style.border="#4f4f4f dotted 1px"; qtyBefore.classList.add('text-center'); qtyBefore.classList.add('bold'); qtyBefore.classList.add('blue'); qtyBefore.value = 0; qtyBefore.tabIndex = -1;

  let qtyAfter = document.createElement("input");
  qtyAfter.type = "text"; qtyAfter.name = "qty-"+index+"-"+n; qtyAfter.id = "qty-"+index+"-"+n; qtyAfter.size=4; qtyAfter.classList.add('text-center'); qtyAfter.value = 0;

  qtyAfter.addEventListener("change", (event)=>{
    if(qtyAfter.value == ""){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    if(qtyAfter.value > 0){
      if((qtyAfter.value).substring(0,1) == "0"){
        qtyAfter.value = (qtyAfter.value).substring(1);
      }
      qtyBefore.classList.add('red');
    }

    if(parseInt(qtyAfter.value) > parseInt(qtyBefore.value)){
      qtyAfter.value = qtyBefore.value;
    }

    if(parseInt(qtyAfter.value) == NaN || parseInt(qtyAfter.value) < 0 || parseInt(qtyAfter.value) == 0){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    subtotalCount(index);
  });

  container.appendChild(idItem); container.appendChild(qtyBefore); container.appendChild(qtyAfter);
  return container;
}

function generateHarga(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "harga"+index+""; idx.id = "harga"+index+""; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="6"; idx.classList.add('text-right'); return idx;
}

function generateTotal(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "total"+index+""; idx.id = "total"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; return idx;
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
  var tbl = document.getElementById('b2breturn_detail');
  var row = tbl.insertRow(tbl.rows.length);
  row.id  = 't1'+baris1;

  var td0 = document.createElement("td");
  var td1 = document.createElement("td");
  var td2 = document.createElement("td");
  var td3 = document.createElement("td");
  var td4 = document.createElement("td");
  var td5 = document.createElement("td");
  var td6 = document.createElement("td");

  var container = generateContainer();

  td0.appendChild(generateAddDetail(baris1));
  td1.appendChild(generateIdB2BDO(baris1));
  td1.appendChild(generateIdMasterB2BDO(baris1));
  td1.appendChild(generateIdDetailB2BDO(baris1));
  td1.appendChild(generateIdB2BReturnDetail(baris1));
  td2.appendChild(generateIdProduk(baris1));
  td2.appendChild(generateNamaProduk(baris1));
  for(let i = 31; i<47; i++){
    container.appendChild(generateQty(baris1, i));
  }
  td3.appendChild(container);
  td4.appendChild(generateHarga(baris1));
  td5.appendChild(generateTotal(baris1));
  td6.appendChild(generateDelete(baris1));

  row.appendChild(td0);
  row.appendChild(td1);
  row.appendChild(td2);
  row.appendChild(td3);
  row.appendChild(td4);
  row.appendChild(td5);
  row.appendChild(td6);

  document.getElementById('kodeGet'+baris1+'').setAttribute('onclick', 'popDetail('+baris1+')');
  document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); 

  baris1 ++;

}

addNewRow1();

  $(document).ready(()=>{
    $('#customer_b2breturn').autocomplete("trb2breturn_cust_list.php", {width: 400});
  });
</script>