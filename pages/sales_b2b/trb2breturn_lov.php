<?php
include "../../include/koneksi.php";
require_once "../../include/config.php";

function intToIDR($val) {
  return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
}
?>

<!DOCTYPE html>

<head>
  <title>DATA DETAIL B2B DO</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap4.min.css" />

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap4.min.js"></script>

  <script>
    function intToIDRSubtotal(val, idx){
      var locale    = 'IDR';
      var options   = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
      var formatter = new Intl.NumberFormat(locale, options);

      document.getElementById("total_remaining"+idx).value = formatter.format(val.toFixed(0));
    }

    function intToIDRUnit(val, idx){
      var locale    = 'IDR';
      var options   = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
      var formatter = new Intl.NumberFormat(locale, options);

      document.getElementById("dpp_formatted"+idx).value = formatter.format(val.toFixed(0));
    }
  </script>

  <style>
		body {
			background-color:#E4B65E ;
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
    }

    p{
      margin: 0 1em 0.2em 0;
      padding: 0;
      font-size: 1em;
      font-weight: bold;
    }

    td{
      white-space: nowrap;
    }
	</style>
</head>

<body>
  <?php 
  
    if((!isset($_COOKIE['tglstart']) && !isset($_COOKIE['tglend'])) || ($_COOKIE['tglstart'] == '' && $_COOKIE['tglend'] == '')){
      $kode1 = date("Y-m-d"); $kode2 = date("Y-m-d");
    }
    else{
      $kode1 = $_COOKIE['tglstart']; $kode2 = $_COOKIE['tglend'];
    }

    if(isset($_COOKIE['baris'])){
      $get_baris = $_COOKIE['baris'];
    }
    else{
      $get_baris = 1;
    }

    if(isset($_COOKIE['filter']) && isset($_COOKIE['filter']) != null){
      $filter = $_COOKIE['filter'];
    }
    else{
      $filter = '';
    }

    setcookie("tglstart", "", time() - 3600);
    setcookie("tglend", "", time() - 3600);
  ?>

<table width="100%">
    <tr>
      <td class="fontjudul">DATA DETAIL INVOICE</td>
    </tr>
  </table>

  <hr>

  <table>
    <tr>
      <td>Tanggal B2B DO</td>
      <td><input type="date" id="tglb2breturnstart" name="tglb2breturnstart" value="<?= str_replace("/","-",$kode1) ?>"> s/d <input type="date" id="tglb2breturnend" name="tglb2breturnend" value="<?= str_replace("/","-",$kode2) ?>"></td>
      <td>Nomor B2B DO</td>
      <td><input type="text" id="filterb2blov" name="filterb2blov" value="<?= $filter == "" ? "" : $filter ?>" /></td>
      <td><button id="btncari" name="btncari" onclick="cari()">Cari</button></td>
    </tr>
  </table>

  <br>

  <input type="hidden" id="hideurutan" name="hideurutan">

  <table width="100%" cellspacing="0" cellpadding="0" id="list_detail_b2bdo" name="list_detail_b2bdo" class="table table-bordered">
    <thead>
      <tr style="color: black;">
        <td><input type="checkbox" id="select_all" name="select_all"></td>
        <td>ID</td>
        <td>ID B2B DO</td>
        <td>Nama Produk</td>
        <td>Size</td>
        <td>Detail</td>
        <td>Harga</td>
      </tr>
    </thead>
    <tbody>
      <?php 
        $sql_detail_b2bdo = "SELECT b.*, date_format(a.tgl_trans, '%d-%m-%Y') AS tanggal_b2bdo FROM b2bdo a LEFT JOIN b2bdo_detail b ON a.id_trans=b.id_trans WHERE a.tgl_trans BETWEEN '".$kode1."' AND '".$kode2."' AND a.id_trans LIKE '%".$filter."%'";

        $sql_detail_b2bdo = mysql_query($sql_detail_b2bdo);
        $baris  = $get_baris;

        while($det_b2bdo = mysql_fetch_array($sql_detail_b2bdo)){
          ?>

          <tr>
            <td class="table-light"><input type="checkbox" id="chkid<?= $baris ?>" name="chkid<?= $baris ?>" size="5" onclick=""></td>

            <td class="table-light"><?= $det_b2bdo['b2bdo_id'] ?><input type="hidden" id="b2bdo_id<?= $baris ?>" name="b2bdo_id<?= $baris ?>" value="<?= $det_b2bdo['b2bdo_id'] ?>"></td>

            <td class="table-light"><?= $det_b2bdo['id_trans'] ?><input type="hidden" id="id_trans<?= $baris ?>" name="id_trans<?= $baris ?>" value="<?= $det_b2bdo['id_trans'] ?>"></td>

            <td class="table-light"><?= $det_b2bdo['namabrg'] ?><input type="hidden" id="namabrg<?= $baris ?>" name="namabrg<?= $baris ?>" value="<?= $det_b2bdo['namabrg'] ?>"><input type="hidden" id="idbrg<?= $baris ?>" name="idbrg<?= $baris ?>" value="<?= $det_b2bdo['id_product'] ?>"></td>

            <td class="table-light"><?= $det_b2bdo['size'] == "" ? "-" : $det_b2bdo['size'] ?><input type="hidden" id="size<?= $baris ?>" name="size<?= $baris ?>" value="<?= $det_b2bdo['size'] ?>"></td>
            
            <td class="table-light">
              <div class="horizontalContainer">
                <div class="containerQty">
                  <p><bold>SIZE</bold></p>
                  <p><bold>QTY</bold></p>
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="31">
                  <input type="hidden" name="id31" id="id31" value="<?= $det_b2bdo['id31'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty31" id="qty31" size="3" value="<?= $det_b2bdo['qty31'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="32">
                  <input type="hidden" name="id32" id="id32" value="<?= $det_b2bdo['id32'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty32" id="qty32" size="3" value="<?= $det_b2bdo['qty32'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="33">
                  <input type="hidden" name="id33" id="id33" value="<?= $det_b2bdo['id33'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty33" id="qty33" size="3" value="<?= $det_b2bdo['qty33'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="34">
                  <input type="hidden" name="id34" id="id34" value="<?= $det_b2bdo['id34'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty34" id="qty34" size="3" value="<?= $det_b2bdo['qty34'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="35">
                  <input type="hidden" name="id35" id="id35" value="<?= $det_b2bdo['id35'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty35" id="qty35" size="3" value="<?= $det_b2bdo['qty35'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="36">
                  <input type="hidden" name="id36" id="id36" value="<?= $det_b2bdo['id36'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty36" id="qty36" size="3" value="<?= $det_b2bdo['qty36'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="37">
                  <input type="hidden" name="id37" id="id37" value="<?= $det_b2bdo['id37'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty37" id="qty37" size="3" value="<?= $det_b2bdo['qty37'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="38">
                  <input type="hidden" name="id38" id="id38" value="<?= $det_b2bdo['id38'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty38" id="qty38" size="3" value="<?= $det_b2bdo['qty38'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="39">
                  <input type="hidden" name="id39" id="id39" value="<?= $det_b2bdo['id39'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty39" id="qty39" size="3" value="<?= $det_b2bdo['qty39'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="40">
                  <input type="hidden" name="id40" id="id40" value="<?= $det_b2bdo['id40'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty40" id="qty40" size="3" value="<?= $det_b2bdo['qty40'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="41">
                  <input type="hidden" name="id41" id="id41" value="<?= $det_b2bdo['id41'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty41" id="qty41" size="3" value="<?= $det_b2bdo['qty41'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="42">
                  <input type="hidden" name="id42" id="id42" value="<?= $det_b2bdo['id42'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty42" id="qty42" size="3" value="<?= $det_b2bdo['qty42'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="43">
                  <input type="hidden" name="id43" id="id43" value="<?= $det_b2bdo['id43'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty43" id="qty43" size="3" value="<?= $det_b2bdo['qty43'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="44">
                  <input type="hidden" name="id44" id="id44" value="<?= $det_b2bdo['id44'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty44" id="qty44" size="3" value="<?= $det_b2bdo['qty44'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="35">
                  <input type="hidden" name="id45" id="id45" value="<?= $det_b2bdo['id45'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty45" id="qty45" size="3" value="<?= $det_b2bdo['qty45'] ?>" >
                </div>
                <div class="containerQty">
                  <input type="text" readonly size="3" class="text-center" value="46">
                  <input type="hidden" name="id46" id="id46" value="<?= $det_b2bdo['id46'] ?>" readonly size="3">
                  <input type="text" readonly class="text-center" name="qty46" id="qty46" size="3" value="<?= $det_b2bdo['qty46'] ?>" >
                </div>
              </div>
            </td>
            <td class="table-light"><?= intToIDR($det_b2bdo['harga_satuan']) ?><input type="hidden" id="price<?= $baris ?>" name="price<?= $baris ?>" value="<?= $det_b2bdo['harga_satuan'] ?>"></td>
          </tr>

          <?php 
          $baris ++;
        }
      ?>
    </tbody>
  </table>

  <table>
    <tr>
      <td>
        <p><input type="image" value="batal" src="../../assets/images/batal.png" id="baru" onclick="window.close();" /></p>
      </td>
      <td>
        <p><input name='print' type='image' src='../../assets/images/simpan_cetak.png' value='Pakai' id='print' onClick='pakai()' /></p>
      </td>
    </tr>
  </table>
</body>

<script>
  var urutan        = [];

  function cari(){
    let tglstart    = $('#tglb2breturnstart').val();
    let tglend      = $('#tglb2breturnend').val();
    let filter      = $('#filterb2blov').val();

    document.cookie = "tglstart="+tglstart;
    document.cookie = "tglend="+tglend;
    document.cookie = "filter="+filter
    document.cookie = "baris="+1;

    location.reload();
  }

  function simpan(no, id){
    if ($('input[type=checkbox][name=chkid'+no+']').is(':checked')){
      urutan.push(id);
    }
    else {
      var index = urutan.indexOf(id);
      if (index != -1) {
        urutan.splice(index, 1);
      }
    }

    $('#hideurutan').val(urutan.toString());
  }

  function pakai(){
    if(window.confirm("Apakah anda yakin ?")){
      for(var i = <?= $get_baris ?>; i< <?= $baris ?>; i++){
        if($('input[type=checkbox][name=chkid'+i+']').is(':checked')){
          if(window.opener.document.getElementById('idb2b'+(i)) != null || window.opener.document.getElementById('idb2b'+(i)) != undefined ){
            window.opener.document.getElementById('idb2b'+(i)).value = $('#id_trans'+i).val();
            window.opener.document.getElementById('iddetb2b'+(i)).value = $('#b2bdo_id'+i).val();
            window.opener.document.getElementById('idproduk'+(i)).value = $('#idbrg'+i).val();
            window.opener.document.getElementById('namaproduk'+(i)).value = $('#namabrg'+i).val();
            window.opener.document.getElementById('size'+(i)).value = $('#size'+i).val();
            window.opener.document.getElementById('harga'+(i)).value = $('#price'+i).val();
            for(let j = 31; j<47; j++){
              window.opener.document.getElementById('idItem'+i).value = $('#id'+j).val();
              window.opener.document.getElementById('id-'+(i)+'-'+j).value = $('#qty'+j).val();
            }
          }
          if(window.opener.document.getElementById('idb2b'+(i+1)) == undefined){
            window.opener.addNewRow1();
            window.opener.document.getElementById('idb2b'+(i)).value = $('#id_trans'+i).val();
            window.opener.document.getElementById('iddetb2b'+(i)).value = $('#b2bdo_id'+i).val();
            window.opener.document.getElementById('idproduk'+(i)).value = $('#idbrg'+i).val();
            window.opener.document.getElementById('namaproduk'+(i)).value = $('#namabrg'+i).val();
            window.opener.document.getElementById('size'+(i)).value = $('#size'+i).val();
            window.opener.document.getElementById('harga'+(i)).value = $('#price'+i).val();
            for(let j = 31; j<47; j++){
              window.opener.document.getElementById('idItem'+i).value = $('#id'+j).val();
              window.opener.document.getElementById('id-'+(i)+'-'+j).value = $('#qty'+j).val();
            }
          }
          }
        }
      }
      // window.close();
    }
  }

  $(document).ready(function(){
    var table = $('#list_detail_b2bdo').DataTable({
      pageLength : 500,
      lengthMenu : [5, 10, 20, 50, 100, 200, 500],
      order      : [[3, "DESC"]]
    });

    $('#select_all').on('click', function(){
      var rows = table.rows({
        'search': 'applied'
      }).nodes();

      if($(this).is(':checked')){
        urutan        = [];
        total         = 0;
        total_qty     = 0;

        $("input[type='checkbox']", rows).prop('checked', true);
      }
      else{
        $("input[type='checkbox']", rows).prop('checked', false);

        urutan        = [];
      }
      $('#hideurutan').val(urutan.toString());
    });
  });
</script>
