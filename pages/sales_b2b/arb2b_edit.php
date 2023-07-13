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
  <title>EDIT AR B2B</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type="text/javascript" src="../../assets/js/jquery.autocomplete.js"></script>
</head>

<?php
include "../../include/koneksi.php";

$sql_mst    = "SELECT a.*, b.id AS id_customer, b.nama AS nama_customer, COALESCE(c.nama, d.nama) AS nama_akun_kredit, COALESCE(e.nama, f.nama) AS nama_akun_debet FROM b2bar a LEFT JOIN mst_b2bcustomer b ON a.b2bcust_id = b.id LEFT JOIN det_coa c ON a.id_akun_kredit = c.id AND a.no_akun_kredit = c.noakun LEFT JOIN mst_coa d ON a.id_akun_kredit = d.id AND a.no_akun_kredit = d.noakun LEFT JOIN det_coa e ON a.id_akun_debet = e.id AND a.no_akun_debet = e.noakun LEFT JOIN mst_coa f ON a.id_akun_debet = f.id AND a.no_akun_debet = f.noakun LEFT JOIN det_coa g ON g.noakun = CONCAT('04.03.', LPAD(b.id, 5, 0)) WHERE a.id='".$_GET['id']."'";

$sql        = mysql_query($sql_mst) or die (mysql_error());
$result     = mysql_fetch_array($sql);
  $tanggal_arb2b    = $result['tgl_ar'];
  $customer_arb2b   = $result['id_customer'].":".$result['nama_customer'];
  $akun_debet_arb2b = $result['id_akun_debet'].":".$result['no_akun_debet']." - ".$result['nama_akun_debet'];
  $akun_kredit_arb2b = $result['id_akun_kredit'].":".$result['no_akun_kredit']." - ".$result['nama_akun_kredit'];
?>

<body>
  <form id="b2bar_add" name="b2bar_add" action="" method="post">
    <input type="hidden" id="id_arb2b_mst" name="id_arb2b_mst" value="<?= $result['id'] ?>" />
    <table width="100%">
      <tr>
        <td class="fontjudul">EDIT AR B2B</td>
        <td class="fontjudul">TOTAL <input type="text" class="" name="total_arb2b" id="total_arb2b" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_arb2b_value" id="total_arb2b_value" readonly></td>
        <td class="fontjudul">TOTAL PENDING <input tpye="text" class="" name="total_arb2b_pending" id="total_arb2b_pending" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" name="total_arb2b_pending_value" id="total_arb2b_pending_value" readonly /></td>
      </tr>
    </table>

    <hr />
    <table width="50%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext no-margin">Tanggal AR</td>
        <td><input type="date" class="inputForm" id="tanggal_arb2b" name="tanggal_arb2b" value="<?= $tanggal_arb2b ?>" /></td>
      </tr>
      <tr>
        <td class="fonttext">Customer</td>
        <td><input type="text" class="inputForm" name="customer_arb2b" id="customer_arb2b" value="<?= $customer_arb2b ?>" readonly style="background-color: #dcdcdc; border: 1px solid black;" /></td>
      </tr>
      <tr>
        <td class="fonttext">Akun Debet</td>
        <td><input type="text" class="inputForm" name="akun_debet_arb2b" id="akun_debet_arb2b" value="<?= $akun_debet_arb2b ?>" readonly style="background-color: #dcdcdc; border: 1px solid black;" /></td>
      </tr>
      <tr>
        <td class="fonttext">Akun Kredit</td>
        <td><input type="text" class="inputForm" name="akun_kredit_arb2b" id="akun_kredit_arb2b" readonly style="background-color: #dcdcdc; border: 1px solid black;" value="<?= $akun_kredit_arb2b ?>" /></td>
      </tr>
    </table>
    <hr>

    <table width="100%" id="b2bar_detail">
      <thead>
        <tr>
          <td width="1%" class="fonttext"></td>
          <td width="15%" class="fonttext">ID B2B DO / RET</td>
          <td width="15%" class="fonttext">Customer</td>
          <td width="15%" class="fonttext">Tanggal DO / RET</td>
          <td width="15%" class="fonttext">Total DO / RET</td>
          <td width="15%" class="fonttext">Total DO / RET Terproses</td>
          <td width="15%" class="fonttext">Total DO / RET Pending</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table>
      <tr>
        <td colspan="100%" class="fonttext">Keterangan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="keterangan" id="keterangan" value="<?= $result['keterangan'] ?>" style="height: 80px; width: 640px;"><?= $result['keterangan'] ?></textarea></td>
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
  // General Function
  function tutup(){
    window.close();
  }

  function intToIDR(val){
    return(val.toLocaleString("id-ID", {style:"currency", currency:"IDR"}));
  }

  function cetak(){
  let pesan = "";

  const tanggal = document.getElementById('tanggal_arb2b').value;
  const customer = document.getElementById('customer_arb2b').value;
  const akunDebet = document.getElementById('akun_debet_arb2b').value;
  const akunKredit = document.getElementById('akun_kredit_arb2b').value;

  let counter = 0;
  for(let i = 1; i<baris1; i++){
    if(document.getElementById('idarb2b'+i) == undefined || document.getElementById('idarb2b'+i) == null || document.getElementById('idarb2b'+i).value == ""){
      } else {
      counter ++;
    }
  }

  if(tanggal == ""){
    pesan = "Tanggal AR B2B tidak boleh kosong";
  } else if(customer == ""){
    pesan = "Customer AR B2B tidak boleh kosong";
  } else if(akunDebet == ""){
    pesan = "Akun Debet AR B2B tidak boleh kosong";
  } else if(akunKredit == ""){
    pesan = "Akun Kredit AR B2B tidak boleh kosong";
  } else if(counter == 0){
    pesan = "Detail AR B2B tidak boleh kosong";
  }

  if(pesan != ""){
    alert("Maaf, ada kesalahan pengisian form : \n"+pesan); return false;
  } else {
    let answer = confirm('Mau simpan data dan cetak datanya ?');
    if(answer){
      $('#b2bar_add').attr('action',"arb2b_update.php?row="+baris1).submit();
    }
  }
}

  $(document).ready(function(){
    returnTotalCount();
  });

  function popDetail(idx){
    if(document.getElementById("customer_arb2b").value == ''){
      alert('Customer harus diisi terlebih dahulu');
    }else{
      var custid = document.getElementById("customer_arb2b").value.split(':');
      var width   = screen.width;
      var height  = screen.height;
      var params  = 'width='+width+', height='+height+',scrollbars=yes';
      window.open('arb2b_lov.php?curr='+idx+'&cust='+custid[0],'',params);
    }
  }

  function returnTotalCount(){
    let total = 0;
    let totalpending = 0;

    for(let i = 1 ; i<=baris1; i++){
      if(document.getElementById('kodeGet'+i) != undefined && (document.getElementById('numb2b'+i) != undefined && document.getElementById('numb2b'+i) != "")){
        if(!isNaN(parseInt(document.getElementById("totalb2b"+i).value))){
          total += parseInt(document.getElementById("totalb2b"+i).value);
          totalpending += parseInt(document.getElementById("totalb2bpending"+i).value);
        }
      }
    }

    document.getElementById('total_arb2b').value = intToIDR(total);
    document.getElementById('total_arb2b_value').value = total;

    document.getElementById('total_arb2b_pending').value = intToIDR(totalpending);
    document.getElementById('total_arb2b_pending_value').value = totalpending;
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
    idx.type = "text"; idx.name = "totalb2bprosesDisplay"+index+""; idx.id = "totalb2bprosesDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generatePendingHidden(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "totalb2bproses"+index+""; idx.id = "totalb2bproses"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateSisa(index){
    let idx = document.createElement("input");
    idx.type = "text"; idx.name = "totalb2bpendingDisplay"+index+""; idx.id = "totalb2bpendingDisplay"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
  }

  function generateSisaHidden(index){
    let idx = document.createElement("input");
    idx.type = "hidden"; idx.name = "totalb2bpending"+index+""; idx.id = "totalb2bpending"+index+""; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.classList.add("input") ; idx.classList.add("text-right"); idx.size=20; return idx;
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
    returnTotalCount();
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

  returnTotalCount(); addNewRow1();

  $(document).ready(()=>{
    $('#customer_arb2b').autocomplete("arb2b_cust_list.php", {width: 400});

    $('#customer_arb2b').result((event, data, formatted)=>{

      let sup   = ($('#customer_arb2b').val()).split(':');
      sup_q     = sup[0];

      $.ajax({
        url       : 'arb2b_get_akun.php?action=akunkredit&id='+sup_q,
        dataType  : 'json',
        data      : 'nama='+formatted,
        success   : function(data){
          let id_akun = data.id_akun;
          let nama_akun = data.nama_akun;
          let nomor_akun = data.nomor_akun;
          $('#akun_kredit_arb2b').val(id_akun+':'+nomor_akun+' - '+nama_akun);
        }
      });

      for(var i = 0; i<=baris1; i++){
        var element = $('#t1'+i);
        if(element != null){
          element.remove();
        }
      }

      baris1 = 1;
      addNewRow1();
    });

    $('#akun_debet_arb2b').autocomplete("arb2b_akun_list.php", {width: 400});
  });

<?php 

$sql_do         = "SELECT a.id_b2b, a.id_parent, b.id_trans, a.parent, 'B2B DO' AS typeb2b, c.nama, date_format(b.tgl_trans, '%d/%m/%Y') AS tgl_trans, b.totalfaktur, b.note FROM b2bar_detail a LEFT JOIN b2bdo b ON a.id_b2b=b.id LEFT JOIN mst_b2bcustomer c ON b.id_customer=c.id  WHERE a.parent = 'DO' AND a.id_parent='".$_GET['id']."' AND a.deleted=0";

$sql_ret        = "SELECT a.id_b2b, a.id_parent, b.b2breturn_num, a.parent, 'B2B RETURN' AS typeb2b, c.nama, date_format(b.tgl_return, '%d/%m/%Y') AS tgl_return , b.total, b.keterangan FROM b2bar_detail a LEFT JOIN b2breturn b ON a.id_b2b=b.id LEFT JOIN mst_b2bcustomer c ON b.b2bcust_id=c.id WHERE a.parent = 'RETUR' AND a.id_parent='".$_GET['id']."' AND a.deleted=0";

$sql_do        = mysql_query($sql_do);
$sql_ret       = mysql_query($sql_ret);

$i = 1;
while($rs=mysql_fetch_array($sql_do)){
  ?>
  
  $('#idarb2b<?= $i ?>').val('<?= $rs['id_b2b'] ?>');
  $('#typearb2b<?= $i ?>').val('<?= $rs['typeb2b'] ?>');
  $('#numb2b<?= $i ?>').val('<?= $rs['id_trans'] ?>');
  $('#customer<?= $i ?>').val('<?= $rs['nama'] ?>');
  $('#tanggalb2b<?= $i ?>').val('<?= $rs['tgl_trans'] ?>');
  $('#totalb2bDisplay<?= $i ?>').val('<?= $rs['totalfaktur'] ?>');
  $('#totalb2b<?= $i ?>').val('<?= $rs['totalfaktur'] ?>');
  $('#totalb2bprosesDisplay<?= $i ?>').val('0');
  $('#totalb2bproses<?= $i ?>').val('0');
  $('#totalb2bpendingDisplay<?= $i ?>').val('<?= $rs['totalfaktur'] ?>');
  $('#totalb2bpending<?= $i ?>').val('<?= $rs['totalfaktur'] ?>');
  $('#keteranganb2b<?= $i ?>').val('<?= $rs['note'] ?>');

  addNewRow1();

  <?php
  $i ++;
}

while($rs=mysql_fetch_array($sql_ret)){
  ?>

  $('#idarb2b<?= $i ?>').val('<?= $rs['id_b2b'] ?>');
  $('#typearb2b<?= $i ?>').val('<?= $rs['typeb2b'] ?>');
  $('#numb2b<?= $i ?>').val('<?= $rs['b2breturn_num'] ?>');
  $('#customer<?= $i ?>').val('<?= $rs['nama'] ?>');
  $('#tanggalb2b<?= $i ?>').val('<?= $rs['tgl_return'] ?>');
  $('#totalb2bDisplay<?= $i ?>').val('-<?= $rs['total'] ?>');
  $('#totalb2b<?= $i ?>').val('-<?= $rs['total'] ?>');
  $('#totalb2bprosesDisplay<?= $i ?>').val('0');
  $('#totalb2bproses<?= $i ?>').val('0');
  $('#totalb2bpendingDisplay<?= $i ?>').val('-<?= $rs['total'] ?>');
  $('#totalb2bpending<?= $i ?>').val('-<?= $rs['total'] ?>');
  $('#keteranganb2b<?= $i ?>').val('<?= $rs['keterangan'] ?>');

  addNewRow1();

  <?php
  $i ++;
}

?> 
</script>