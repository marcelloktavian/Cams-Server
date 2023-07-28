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
  <title>EDIT B2B Return</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<?php
include "../../include/koneksi.php";

$sql_mst    = "SELECT a.*, b.nama AS customer, a.id AS id_mst, c.nama AS kategori FROM `b2breturn` a LEFT JOIN mst_b2bcustomer b ON a.b2bcust_id=b.id LEFT JOIN mst_b2bcategory_sale c ON a.id_kategori=c.id WHERE a.id='".$_GET['id']."' AND a.deleted=0";

$sql        = mysql_query($sql_mst) or die (mysql_error());
$result     = mysql_fetch_array($sql);
  $id_mst         = $result['id_mst'];
  $b2breturn_num  = $result['b2breturn_num'];
  $b2breturn_cust = $result['b2bcust_id'].' : '.$result['customer'];
  $b2breturn_type = $result['id_kategori'];
  $tgl_return     = $result['tgl_return'];
  $keterangan     = $result['keterangan'];
?>

<body>
  <form id="b2breturn_add" name="b2breturn_add" action="" method="post">
    <table width="100%">
      <tr>
        <td class="fontjudul">EDIT B2B Return <?= $b2breturn_num ?></td>
        <td class="fontjudul">TOTAL QTY <input type="text" class="" name="total_qty_b2breturn" id="total_qty_b2breturn"  style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">TOTAL RETURN <input type="text" class="" name="total_b2breturn" id="total_b2breturn" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_b2breturn_value" id="total_b2breturn_value" readonly></td>
      </tr>
    </table>

    <input type="hidden" id="id_b2breturn" name="id_b2breturn" value="<?= $id_mst ?>">

    <hr />
    <table width="50%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext no-margin">Tanggal Return</td>
        <td><input type="date" class="inputForm" name="tanggal_b2breturn" id="tanggal_b2breturn" value="<?= $tgl_return ?>"></td>
      </tr>
      <tr>
        <td class="fonttext">Customer</td>
        <td><input type="text" id="customer_b2breturn" name="customer_b2breturn" class="inputForm" value="<?= $b2breturn_cust ?>" readonly></td>
      </tr>
      <tr>
        <td class="fonttext">Type</td>
        <td><input type="text" class="inputForm" placeholder="(dibuat otomatis oleh sistem)" value="<?= $b2breturn_num ?>" readonly hidden/>
        <select type="text" class="inputForm" id="type_b2breturn" name="type_b2breturn">
          <option value="1" <?= $b2breturn_type=='1' ? 'selected' : '' ?>>SOL - Product Sol Sepatu</option>
          <option value="2" <?= $b2breturn_type=='2' ? 'selected' : '' ?>>SDC - Contract Manufacturing Camou</option>
          <option value="3" <?= $b2breturn_type=='3' ? 'selected' : '' ?>>SDL - Contract Manufacturing Non Camou</option>
        </select></td>
      </tr>
    </table>
    <hr>
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
          <td width="10%" class="fonttext">Qty Return</td>
          <td width="15%" class="fonttext">Harga</td>
          <td width="15%" class="fonttext">Subtotal</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table>
      <tr>
        <td colspan="100%" class="fonttext">Keterangan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="keterangan" id="keterangan" value="<?= $keterangan ?>" style="height: 80px; width: 640px;" /><?= $keterangan ?></textarea></td>
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

function tutup(){
  window.close();
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
  document.getElementById("totalqty"+idx).value = subtotal;
  document.getElementById("totalDisplay"+idx).value = (subtotal*document.getElementById("harga"+idx).value).toLocaleString();
  document.getElementById("total"+idx).value = subtotal*document.getElementById("harga"+idx).value;

  qtyTotalCount(); returnTotalCount();
}

const customer = document.getElementById('customer_b2breturn');
const type = document.getElementById('type_b2breturn');

function popDetail(idx){
  var width   = screen.width;
  var height  = screen.height;
  var params  = 'width='+width+', height='+height+',scrollbars=yes';
  window.open('trb2breturn_lov.php?cust='+(customer.value).split(' : ')[0]+'&type='+type.value+'&curr='+idx+'&baris='+baris1,'',params);
}

function hitungsubtotal(idx){
  if(document.getElementById('totalqty'+idx).value == ''){
    var qty = 0;
  }else{
    var qty = document.getElementById('totalqty'+idx).value;
  }
  var harga = document.getElementById('harga'+idx).value;
  var hargaMax = document.getElementById('hargaHidden'+idx).value;
  if(parseInt(harga) > parseInt(hargaMax)){
    document.getElementById('harga'+idx).value = hargaMax;
  }
  var subtotal = parseInt(qty) * parseInt(harga);
  document.getElementById('total'+idx).value = subtotal;
  document.getElementById("totalDisplay"+idx).value = (subtotal).toLocaleString();
  subtotalCount(idx);
}

// save function --------------------
function cetak(){
  let pesan = "";

  const type = document.getElementById('type_b2breturn').value;
  const customer = document.getElementById('customer_b2breturn').value;
  const tanggal = document.getElementById('tanggal_b2breturn').value;
  const qty = document.getElementById('total_qty_b2breturn').value;

  if(type == ""){
    pesan = "Tipe Return tidak boleh kosong !";
  }
  else if(customer == ""){
    pesan = "Nama Customer tidak boleh kosong !";
  }
  else if(tanggal == ""){
    pesan = "Tanggal return tidak boleh kosong !";
  }
  // else if(baris1 == 1){
  //   pesan = "Barang return tidak boleh kosong !";
  // }
  else if(qty == 0){
    pesan = "Barang return tidak boleh kosong !";
  }

  if(pesan != ""){
    alert("Maaf, ada kesalahan pengisian form : \n"+pesan); return false;
  } else {
    let answer = confirm('Mau simpan data dan cetak datanya ?');
    if (answer)
		{	
      $('#b2breturn_add').attr('action',"trb2breturn_update.php?row="+baris1).submit();
    }
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
  idx.type = "hidden"; idx.name = "idmstb2b"+index+""; idx.id = "idmstb2b"+index+""; idx.readOnly="readonly"; return idx;
}

function generateIdDetailB2BDO(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "iddetb2b"+index+""; idx.id = "iddetb2b"+index+""; idx.readOnly="readonly"; return idx;
}

function generateIdProduk(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "idproduk"+index+""; idx.id = "idproduk"+index+""; idx.readOnly="readonly"; return idx;
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

  qtyAfter.addEventListener("input", (event)=>{
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
  idx.type = "text"; idx.name = "harga"+index+""; idx.id = "harga"+index+""; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="6"; idx.classList.add('text-right'); 
  idx.oninput = function() {
    this.value = this.value.replace(/\D/g, '');
  };
  return idx;
}

function generateHargaHidden(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "hargaHidden"+index+""; idx.id = "hargaHidden"+index+""; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.size="6"; idx.classList.add('text-right');
   return idx;
}


function generateTotalQty(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "totalqty"+index+""; idx.id = "totalqty"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.size="6"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add('text-right'); return idx;
}

function generateTotal(index){
  let idx = document.createElement("input");
  idx.type = "text"; idx.name = "totalDisplay"+index+""; idx.id = "totalDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc";  idx.size="6"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add('text-right'); return idx;
}

function generateTotalHidden(index){
  let idx = document.createElement("input");
  idx.type = "hidden"; idx.name = "total"+index+""; idx.id = "total"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="6"; idx.classList.add("input") ; idx.classList.add('text-right'); return idx;
}

function generateDelete(index){
  var idx = document.createElement("input");
  idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")"; return idx;
}

function delRow1(index){
  var element = document.getElementById("t1"+index); element.remove();
  qtyTotalCount(); returnTotalCount();
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
  td1.appendChild(generateIdMasterB2BDO(baris1));
  td1.appendChild(generateIdDetailB2BDO(baris1));
  td1.appendChild(generateIdB2BReturnDetail(baris1));
  td2.appendChild(generateIdProduk(baris1));
  td2.appendChild(generateNamaProduk(baris1));
  for(let i = 31; i<47; i++){
    container.appendChild(generateQty(baris1, i));
  }
  td3.appendChild(container);
  td4.appendChild(generateTotalQty(baris1));
  td5.appendChild(generateHarga(baris1));
  td5.appendChild(generateHargaHidden(baris1));
  td6.appendChild(generateTotal(baris1));
  td6.appendChild(generateTotalHidden(baris1));
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
  document.getElementById('harga'+baris1+'').setAttribute('onkeyup', 'hitungsubtotal('+baris1+')');
  document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); 

  baris1 ++;

}

addNewRow1();

  $(document).ready(()=>{
    $('#customer_b2breturn').autocomplete("trb2breturn_cust_list.php", {width: 400});
  });

<?php 

$sql_detail     = "SELECT *, a.id AS id_b2breturn_det FROM b2breturn_detail a LEFT JOIN b2bunreturned_qty b ON a.`id_b2bdo_det`=b.`b2bdo_id` WHERE a.id_parent='".$_GET['id']."' AND a.deleted=0 ";

$sql_detail     = mysql_query($sql_detail);

$i = 1;
while($rs=mysql_fetch_array($sql_detail)){
  $totalqty = $rs['qty31'] + $rs['qty32'] + $rs['qty33'] + $rs['qty34'] + $rs['qty35'] + $rs['qty36'] + $rs['qty37'] + $rs['qty38'] + $rs['qty39'] + $rs['qty40'] + $rs['qty41'] + $rs['qty42'] + $rs['qty42'] + $rs['qty43'] + $rs['qty44'] + $rs['qty46'] + $rs['qty46'];
  ?>
  addNewRow1();

  $('#id_b2breturn_det<?= $i ?>').val('<?= $rs['id_b2breturn_det'] ?>');
  $('#idb2b<?= $i ?>').val('<?= $rs['b2bdo_num'] ?>');
  $('#idmstb2b'+'<?= $i ?>').val('<?= $rs['id_trans_do'] ?>');
  $('#iddetb2b'+'<?= $i ?>').val('<?= $rs['id_b2bdo_det'] ?>');
  $('#idproduk'+'<?= $i ?>').val('<?= $rs['id_product'] ?>');
  $('#namaproduk'+'<?= $i ?>').val('<?= $rs['namabrg'] ?>');

  $('#idItem-<?=$i?>-31').val('<?=$rs['id31']?>');
  $('#id-<?=$i?>-31').val('<?=$rs['unret31'] + $rs['qty31']?>');
  if(<?=$rs['unret31'] + $rs['qty31']?> > 0){
    document.getElementById('id-<?=$i?>-31').style.color = red;
  }else{
    document.getElementById('id-<?=$i?>-31').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-31').readOnly = true;
    document.getElementById('qty-<?=$i?>-31').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-31').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-31').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-31').val('<?=$rs['qty31']?>');
  $('#idItem-<?=$i?>-32').val('<?=$rs['id32']?>');
  $('#id-<?=$i?>-32').val('<?=$rs['unret32'] + $rs['qty32']?>');
  if(<?=$rs['unret32'] + $rs['qty32']?> > 0){
    document.getElementById('id-<?=$i?>-32').style.color = red;
  }else{
    document.getElementById('id-<?=$i?>-32').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-32').readOnly = true;
    document.getElementById('qty-<?=$i?>-32').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-32').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-32').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-32').val('<?=$rs['qty32']?>');
  $('#idItem-<?=$i?>-33').val('<?=$rs['id33']?>');
  $('#id-<?=$i?>-33').val('<?=$rs['unret33'] + $rs['qty33']?>');
  if (<?=$rs['unret33'] + $rs['qty33']?> > 0) {
    document.getElementById('id-<?=$i?>-33').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-33').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-33').readOnly = true;
    document.getElementById('qty-<?=$i?>-33').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-33').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-33').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-33').val('<?=$rs['qty33']?>');
  $('#idItem-<?=$i?>-34').val('<?=$rs['id34']?>');
  $('#id-<?=$i?>-34').val('<?=$rs['unret34'] + $rs['qty34']?>');
  if (<?=$rs['unret34'] + $rs['qty34']?> > 0) {
    document.getElementById('id-<?=$i?>-34').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-34').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-34').readOnly = true;
    document.getElementById('qty-<?=$i?>-34').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-34').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-34').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-34').val('<?=$rs['qty34']?>');
  $('#idItem-<?=$i?>-35').val('<?=$rs['id35']?>');
  $('#id-<?=$i?>-35').val('<?=$rs['unret35'] + $rs['qty35']?>');
  if (<?=$rs['unret35'] + $rs['qty35']?> > 0) {
    document.getElementById('id-<?=$i?>-35').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-35').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-35').readOnly = true;
    document.getElementById('qty-<?=$i?>-35').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-35').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-35').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-35').val('<?=$rs['qty35']?>');
  $('#idItem-<?=$i?>-36').val('<?=$rs['id36']?>');
  $('#id-<?=$i?>-36').val('<?=$rs['unret36'] + $rs['qty36']?>');
  if (<?=$rs['unret36'] + $rs['qty36']?> > 0) {
    document.getElementById('id-<?=$i?>-36').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-36').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-36').readOnly = true;
    document.getElementById('qty-<?=$i?>-36').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-36').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-36').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-36').val('<?=$rs['qty36']?>');
  $('#idItem-<?=$i?>-37').val('<?=$rs['id37']?>');
  $('#id-<?=$i?>-37').val('<?=$rs['unret37'] + $rs['qty37']?>');
  if (<?=$rs['unret37'] + $rs['qty37']?> > 0) {
    document.getElementById('id-<?=$i?>-37').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-37').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-37').readOnly = true;
    document.getElementById('qty-<?=$i?>-37').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-37').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-37').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-37').val('<?=$rs['qty37']?>');
  $('#idItem-<?=$i?>-38').val('<?=$rs['id38']?>');
  $('#id-<?=$i?>-38').val('<?=$rs['unret38'] + $rs['qty38']?>');
  if (<?=$rs['unret38'] + $rs['qty38']?> > 0) {
    document.getElementById('id-<?=$i?>-38').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-38').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-38').readOnly = true;
    document.getElementById('qty-<?=$i?>-38').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-38').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-38').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-38').val('<?=$rs['qty38']?>');
  $('#idItem-<?=$i?>-39').val('<?=$rs['id39']?>');
  $('#id-<?=$i?>-39').val('<?=$rs['unret39'] + $rs['qty39']?>');
  if (<?=$rs['unret39'] + $rs['qty39']?> > 0) {
    document.getElementById('id-<?=$i?>-39').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-39').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-39').readOnly = true;
    document.getElementById('qty-<?=$i?>-39').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-39').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-39').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-39').val('<?=$rs['qty39']?>');
  $('#idItem-<?=$i?>-40').val('<?=$rs['id40']?>');
  $('#id-<?=$i?>-40').val('<?=$rs['unret40'] + $rs['qty40']?>');
  if (<?=$rs['unret40'] + $rs['qty40']?> > 0) {
    document.getElementById('id-<?=$i?>-40').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-40').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-40').readOnly = true;
    document.getElementById('qty-<?=$i?>-40').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-40').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-40').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-40').val('<?=$rs['qty40']?>');
  $('#idItem-<?=$i?>-41').val('<?=$rs['id41']?>');
  $('#id-<?=$i?>-41').val('<?=$rs['unret41'] + $rs['qty41']?>');
  if (<?=$rs['unret41'] + $rs['qty41']?> > 0) {
    document.getElementById('id-<?=$i?>-41').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-41').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-41').readOnly = true;
    document.getElementById('qty-<?=$i?>-41').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-41').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-41').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-41').val('<?=$rs['qty41']?>');
  $('#idItem-<?=$i?>-42').val('<?=$rs['id42']?>');
  $('#id-<?=$i?>-42').val('<?=$rs['unret42'] + $rs['qty42']?>');
  if (<?=$rs['unret42'] + $rs['qty42']?> > 0) {
    document.getElementById('id-<?=$i?>-42').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-42').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-42').readOnly = true;
    document.getElementById('qty-<?=$i?>-42').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-42').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-42').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-42').val('<?=$rs['qty42']?>');
  $('#idItem-<?=$i?>-43').val('<?=$rs['id43']?>');
  $('#id-<?=$i?>-43').val('<?=$rs['unret43'] + $rs['qty43']?>');
  if (<?=$rs['unret43'] + $rs['qty43']?> > 0) {
    document.getElementById('id-<?=$i?>-43').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-43').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-43').readOnly = true;
    document.getElementById('qty-<?=$i?>-43').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-43').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-43').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-43').val('<?=$rs['qty43']?>');
  $('#idItem-<?=$i?>-44').val('<?=$rs['id44']?>');
  $('#id-<?=$i?>-44').val('<?=$rs['unret44'] + $rs['qty44']?>');
  if (<?=$rs['unret44'] + $rs['qty44']?> > 0) {
    document.getElementById('id-<?=$i?>-44').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-44').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-44').readOnly = true;
    document.getElementById('qty-<?=$i?>-44').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-44').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-44').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-44').val('<?=$rs['qty44']?>');
  $('#idItem-<?=$i?>-45').val('<?=$rs['id45']?>');
  $('#id-<?=$i?>-45').val('<?=$rs['unret45'] + $rs['qty45']?>');
  if (<?=$rs['unret45'] + $rs['qty45']?> > 0) {
    document.getElementById('id-<?=$i?>-45').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-45').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-45').readOnly = true;
    document.getElementById('qty-<?=$i?>-45').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-45').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-45').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-45').val('<?=$rs['qty45']?>');
  $('#idItem-<?=$i?>-46').val('<?=$rs['id46']?>');
  $('#id-<?=$i?>-46').val('<?=$rs['unret46'] + $rs['qty46']?>');
  if (<?=$rs['unret46'] + $rs['qty46']?> > 0) {
    document.getElementById('id-<?=$i?>-46').style.color = 'red';
  }else{
    document.getElementById('id-<?=$i?>-46').style.backgroundColor = '#b3b3b3';
    document.getElementById('qty-<?=$i?>-46').readOnly = true;
    document.getElementById('qty-<?=$i?>-46').style.backgroundColor = '#D3D3D3';
    document.getElementById('qty-<?=$i?>-46').style.border = '1px solid #4f4f4f';
    document.getElementById('qty-<?=$i?>-46').tabIndex = '-1';
  }
  $('#qty-<?=$i?>-46').val('<?=$rs['qty46']?>');

  $('#totalqty'+'<?= $i ?>').val('<?= $totalqty ?>');
  $('#harga'+'<?= $i ?>').val('<?= $rs['harga_satuan'] ?>');
  $('#hargaHidden'+'<?= $i ?>').val('<?= $rs['harga_satuan_real'] ?>');
  $('#total'+'<?= $i ?>').val('<?= $rs['subtotal'] ?>');

  subtotalCount('<?= $i ?>');
  <?php
  $i ++;
}

?>
</script>