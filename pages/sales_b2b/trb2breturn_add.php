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
      </tr>
    </table>

    <hr />
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Nomor B2B Return</td>
        <td><input type="text" class="inputForm" placeholder="(dibuat otomatis oleh sistem)" readonly/></td>
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
          <td width="15%" class="fonttext">Size</td>
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
function popDetail(idx){
  var width   = screen.width;
  var height  = screen.height;
  var params  = 'width='+width+', height='+height+',scrollbars=yes';
  window.open('trb2breturn_lov.php?baris='+idx,'',params);
}

// add row generate ------------------
function generateAddDetail(index){
  let idx = document.createElement("input");
  idx.type = "button"; idx.name = "kodeGet"+index+""; idx.id = "kodeGet"+index+""; idx.value = "+"; idx.classList.add("input") ; return idx;
}

function generateIdB2BDO(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "idb2b"+index+""; idx.id = "idb2b"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="10"; return idx;
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

function generateSize(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "size"+index+""; idx.id = "size"+index+""; idx.readOnly="readonly"; idx.classList.add("input") ; idx.size=3; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
}

function generateContainer(){
  let container = document.createElement("div");
  container.classList.add('horizontalContainer'); return container;
}

function generateQty(index, n){
  let container = document.createElement("div");
  container.classList.add('containerQty');

  // let label = document.createElement("input");
  // label.type = "text"; label.readOnly="readonly"; label.size=4; label.style.backgroundColor="#dcdcdc"; label.style.border="#4f4f4f dotted 1px"; label.value=n; label.classList.add('text-center'); label.classList.add('bold');

  let idItem = document.createElement("input");
  idItem.type = "hidden"; idItem.name = "idItem"+index; idItem.id = "idItem"+index; idItem.readOnly="readonly";

  let qtyBefore = document.createElement("input");
  qtyBefore.type = "text"; qtyBefore.name = "id-"+index+"-"+n; qtyBefore.id = "id-"+index+"-"+n; qtyBefore.readOnly="readonly"; qtyBefore.size=4; qtyBefore.style.backgroundColor="#dcdcdc"; qtyBefore.style.border="#4f4f4f dotted 1px"; qtyBefore.classList.add('text-center'); qtyBefore.classList.add('bold'); qtyBefore.classList.add('blue'); qtyBefore.value = 0;

  let qtyAfter = document.createElement("input");
  qtyAfter.type = "text"; qtyAfter.name = "qty-"+index+"-"+n; qtyAfter.id = "qty-"+index+"-"+n; qtyAfter.size=4; qtyAfter.classList.add('text-center'); qtyAfter.value = 0;
  
  qtyAfter.addEventListener("keyup", (event)=>{
    if(qtyAfter.value > qtyBefore.value){
      qtyAfter.value = qtyBefore.value;
    };

    if(qtyAfter.value == ""){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    if(parseInt(qtyAfter.value) == NaN){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    if(parseInt(qtyAfter.value) < 0){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    if(parseInt(qtyAfter.value) == 0){
      qtyAfter.value = 0;
      qtyBefore.classList.remove('red');
    }

    if(parseInt(qtyAfter.value) > 0){
      qtyBefore.classList.add('red');
    }
  });

  // container.appendChild(label);
  container.appendChild(idItem); container.appendChild(qtyBefore); container.appendChild(qtyAfter);
  return container;
}

function generateHarga(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "harga"+index+""; idx.id = "harga"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="6"; idx.classList.add('text-right'); return idx;
}

function generateTotal(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.total = "total"+index+""; idx.id = "total"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; return idx;
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
  var td7 = document.createElement("td");

  var container = generateContainer();

  td0.appendChild(generateAddDetail(baris1));
  td1.appendChild(generateIdB2BDO(baris1));
  td1.appendChild(generateIdDetailB2BDO(baris1));
  td2.appendChild(generateIdProduk(baris1));
  td2.appendChild(generateNamaProduk(baris1));
  td3.appendChild(generateSize(baris1));
  for(let i = 31; i<47; i++){
    container.appendChild(generateQty(baris1, i));
  }
  td4.appendChild(container);
  td5.appendChild(generateHarga(baris1));
  td6.appendChild(generateTotal(baris1));
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