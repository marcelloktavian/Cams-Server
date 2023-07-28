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

    .right{
      text-align: right;
    }

    .loadedData{
      display: none;
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

    if(isset($_GET['baris'])){
      $total_baris = $_GET['baris'];
    }
    else{
      $total_baris = 0;
    }

    if(isset($_GET['curr'])){
      $get_baris = $_GET['curr'];
    }
    else{
      $get_baris = 1;
    }

    if(isset($_COOKIE['filter']) && isset($_COOKIE['filter']) != ''){
      $filter = $_COOKIE['filter'];
    }
    else{
      $filter = '';
    }

    if(isset($_GET['cust']) && isset($_GET['cust']) != null){
      $customer = $_GET['cust'];
    }
    else{
      $customer = '';
    }

    if(isset($_GET['type']) && isset($_GET['type']) != null){
      $type = $_GET['type'];
    }
    else{
      $type = '';
    }

    setcookie("tglstart", "", time() - 3600);
    setcookie("tglend", "", time() - 3600);
    setcookie("filter", "", time() - 3600);
  ?>

<script>
  const loadedData = new Set();

  function loadData(line, total){
    try {
      const element = window.opener.document.getElementById('iddetb2b' + line).value;
      if (element) {
        loadedData.add(element);
      }
    }catch (error) {
      console.log(`Error occurred while trying to get element 'iddetb2b${line}': ${error.message}`);
    }

    if(line <= total){
      loadData(line + 1, total);
    }
  }

  let totalBaris = <?= $total_baris ?>;
  loadData(0, totalBaris);
</script>

<table width="100%">
    <tr>
      <td class="fontjudul">DATA DETAIL B2B</td>
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
          <td>Detail</td>
          <td>Total Qty</td>
          <td>Sisa Qty Return</td>
          <td>Harga</td>
          <td>Subtotal</td>
        </tr>
      </thead>
      <tbody>
        <?php 
          // $sql_detail_b2bdo = "SELECT b.*, date_format(a.tgl_trans, '%d-%m-%Y') AS tanggal_b2bdo FROM b2bdo a LEFT JOIN b2bdo_detail b ON a.id_trans=b.id_trans WHERE a.tgl_trans BETWEEN '".$kode1."' AND '".$kode2."' AND a.id_trans LIKE '%".$filter."%'";

          $sql_detail_b2bdo = "SELECT a.id, d.* , e.*, (d.qty31 - COALESCE(e.totqty31, 0)) AS unret31, (d.qty32 - COALESCE(e.totqty32, 0)) AS unret32, (d.qty33 - COALESCE(e.totqty33, 0)) AS unret33, (d.qty34 - COALESCE(e.totqty34, 0)) AS unret34, (d.qty35 - COALESCE(e.totqty35, 0)) AS unret35, (d.qty36 - COALESCE(e.totqty36, 0)) AS unret36, (d.qty37 - COALESCE(e.totqty37, 0)) AS unret37, (d.qty38 - COALESCE(e.totqty38, 0)) AS unret38, (d.qty39 - COALESCE(e.totqty39, 0)) AS unret39, (d.qty40 - COALESCE(e.totqty40, 0)) AS unret40, (d.qty41 - COALESCE(e.totqty41, 0)) AS unret41, (d.qty42 - COALESCE(e.totqty42, 0)) AS unret42, (d.qty43 - COALESCE(e.totqty43, 0)) AS unret43, (d.qty44 - COALESCE(e.totqty44, 0)) AS unret44, (d.qty45 - COALESCE(e.totqty45, 0)) AS unret45, (d.qty46 - COALESCE(e.totqty46, 0)) AS unret46 FROM b2bdo a LEFT JOIN b2bso b ON a.id_transb2bso=b.id_trans LEFT JOIN mst_b2bcategory_sale c ON c.`id`=b.`id_kategori` LEFT JOIN b2bdo_detail d ON a.id_trans=d.id_trans LEFT JOIN b2breturn_qty e ON d.b2bdo_id=e.id_b2breturn_qty WHERE a.tgl_trans BETWEEN '".$kode1."' AND '".$kode2."' AND a.id_trans LIKE '%".$filter."%' AND b.id_customer = '".$customer."' AND c.id= '".$type."' ";

          $sql_detail_b2bdo = mysql_query($sql_detail_b2bdo);
          $baris  = 0;

          while($det_b2bdo = mysql_fetch_array($sql_detail_b2bdo)){
            $totalqty = $det_b2bdo['unret31'] + $det_b2bdo['unret32'] + $det_b2bdo['unret33'] + $det_b2bdo['unret34'] + $det_b2bdo['unret35'] + $det_b2bdo['unret36'] + $det_b2bdo['unret37'] + $det_b2bdo['unret38'] + $det_b2bdo['unret39'] + $det_b2bdo['unret40'] + $det_b2bdo['unret41'] + $det_b2bdo['unret42'] + $det_b2bdo['unret43'] + $det_b2bdo['unret44'] + $det_b2bdo['unret45'] + $det_b2bdo['unret46'];
            if($totalqty>0){
            ?>
            
            <tr class="checkList" id="<?= $det_b2bdo['b2bdo_id'] ?>">
              <td class="table-light"><input type="checkbox" id="chkid<?= $baris ?>" name="chkid<?= $baris ?>" size="5" onclick=""></td>

              <td class="table-light"><?= $det_b2bdo['b2bdo_id'] ?><input type="hidden" id="b2bdo_id<?= $baris ?>" name="b2bdo_id<?= $baris ?>" value="<?= $det_b2bdo['b2bdo_id'] ?>"><input id="b2bdo_master<?= $baris ?>" name="b2bdo_master<?= $baris ?>" type="hidden" value="<?= $det_b2bdo['id'] ?>" /></td>

              <td class="table-light"><?= $det_b2bdo['id_trans'] ?><input type="hidden" id="id_trans<?= $baris ?>" name="id_trans<?= $baris ?>" value="<?= $det_b2bdo['id_trans'] ?>"></td>

              <td class="table-light"><?= $det_b2bdo['namabrg'] ?><input type="hidden" id="namabrg<?= $baris ?>" name="namabrg<?= $baris ?>" value="<?= $det_b2bdo['namabrg'] ?>"><input type="hidden" id="idbrg<?= $baris ?>" name="idbrg<?= $baris ?>" value="<?= $det_b2bdo['id_product'] ?>"></td>

              <td class="table-light">
                <div class="horizontalContainer">
                  <div class="containerQty">
                    <p><bold>SIZE</bold></p>
                    <p><bold>QTY</bold></p>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="31" <?= $det_b2bdo['unret31'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-31" id="id-<?= $baris ?>-31" value="<?= $det_b2bdo['id31'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-31" id="qty-<?= $baris ?>-31" size="3" value="<?= $det_b2bdo['unret31'] ?>" <?= $det_b2bdo['unret31'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="32" <?= $det_b2bdo['unret32'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-32" id="id-<?= $baris ?>-32" value="<?= $det_b2bdo['id32'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-32" id="qty-<?= $baris ?>-32" size="3" value="<?= $det_b2bdo['unret32'] ?>" <?= $det_b2bdo['unret32'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="33" <?= $det_b2bdo['unret33'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-33" id="id-<?= $baris ?>-33" value="<?= $det_b2bdo['id33'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-33" id="qty-<?= $baris ?>-33" size="3" value="<?= $det_b2bdo['unret33'] ?>" <?= $det_b2bdo['unret33'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="34" <?= $det_b2bdo['unret34'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-34" id="id-<?= $baris ?>-34" value="<?= $det_b2bdo['id34'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-34" id="qty-<?= $baris ?>-34" size="3" value="<?= $det_b2bdo['unret34'] ?>" <?= $det_b2bdo['unret34'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="35" <?= $det_b2bdo['unret35'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-35" id="id-<?= $baris ?>-35" value="<?= $det_b2bdo['id35'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-35" id="qty-<?= $baris ?>-35" size="3" value="<?= $det_b2bdo['unret35'] ?>" <?= $det_b2bdo['unret35'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="36" <?= $det_b2bdo['unret36'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-36" id="id-<?= $baris ?>-36" value="<?= $det_b2bdo['id36'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-36" id="qty-<?= $baris ?>-36" size="3" value="<?= $det_b2bdo['unret36'] ?>" <?= $det_b2bdo['unret36'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?> >
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="37" <?= $det_b2bdo['unret37'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-37" id="id-<?= $baris ?>-37" value="<?= $det_b2bdo['id37'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-37" id="qty-<?= $baris ?>-37" size="3" value="<?= $det_b2bdo['unret37'] ?>" <?= $det_b2bdo['unret37'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="38" <?= $det_b2bdo['unret38'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-38" id="id-<?= $baris ?>-38" value="<?= $det_b2bdo['id38'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-38" id="qty-<?= $baris ?>-38" size="3" value="<?= $det_b2bdo['unret38'] ?>" <?= $det_b2bdo['unret38'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="39" <?= $det_b2bdo['unret39'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-39" id="id-<?= $baris ?>-39" value="<?= $det_b2bdo['id39'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-39" id="qty-<?= $baris ?>-39" size="3" value="<?= $det_b2bdo['unret39'] ?>" <?= $det_b2bdo['unret39'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="40" <?= $det_b2bdo['unret40'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-40" id="id-<?= $baris ?>-40" value="<?= $det_b2bdo['id40'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-40" id="qty-<?= $baris ?>-40" size="3" value="<?= $det_b2bdo['unret40'] ?>" <?= $det_b2bdo['unret40'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="41" <?= $det_b2bdo['unret41'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-41" id="id-<?= $baris ?>-41" value="<?= $det_b2bdo['id41'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-41" id="qty-<?= $baris ?>-41" size="3" value="<?= $det_b2bdo['unret41'] ?>" <?= $det_b2bdo['unret41'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="42" <?= $det_b2bdo['unret42'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-42" id="id-<?= $baris ?>-42" value="<?= $det_b2bdo['id42'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-42" id="qty-<?= $baris ?>-42" size="3" value="<?= $det_b2bdo['unret42'] ?>" <?= $det_b2bdo['unret42'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="43" <?= $det_b2bdo['unret43'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-43" id="id-<?= $baris ?>-43" value="<?= $det_b2bdo['id43'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-43" id="qty-<?= $baris ?>-43" size="3" value="<?= $det_b2bdo['unret43'] ?>" <?= $det_b2bdo['unret43'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="44" <?= $det_b2bdo['unret44'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-44" id="id-<?= $baris ?>-44" value="<?= $det_b2bdo['id44'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-44" id="qty-<?= $baris ?>-44" size="3" value="<?= $det_b2bdo['unret44'] ?>" <?= $det_b2bdo['unret44'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?> >
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="35" <?= $det_b2bdo['unret45'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-45" id="id-<?= $baris ?>-45" value="<?= $det_b2bdo['id45'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-45" id="qty-<?= $baris ?>-45" size="3" value="<?= $det_b2bdo['unret45'] ?>" <?= $det_b2bdo['unret45'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                  <div class="containerQty">
                    <input type="text" readonly size="3" class="text-center" value="46" <?= $det_b2bdo['unret46'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                    <input type="hidden" class="value-nol text-center" name="id-<?= $baris ?>-46" id="id-<?= $baris ?>-46" value="<?= $det_b2bdo['id46'] ?>" readonly size="3">
                    <input type="text" readonly class="value-nol text-center" name="qty-<?= $baris ?>-46" id="qty-<?= $baris ?>-46" size="3" value="<?= $det_b2bdo['unret46'] ?>" <?= $det_b2bdo['unret46'] == 0 ? 'style="background-color:#d0d0d0 !important;"' : '' ?>>
                  </div>
                </div>
              </td>
              <td class="table-light right"><?= $det_b2bdo['jumlah_kirim'] ?><input type="hidden" id="totalqty<?= $baris ?>" name="totalqty<?= $baris ?>" value="<?= $det_b2bdo['jumlah_kirim'] ?>"></td>

              <td class="table-light right"><?= $totalqty ?><input type="hidden" id="totalreturn<?= $baris ?>" name="totalreturn<?= $baris ?>" value="<?= $totalqty ?>"></td>

              <td class="table-light right"><?= intToIDR($det_b2bdo['harga_satuan']) ?><input type="hidden" id="price<?= $baris ?>" name="price<?= $baris ?>" value="<?= $det_b2bdo['harga_satuan'] ?>"></td>
              
              <td class="table-light right"><?= intToIDR($totalqty*$det_b2bdo['harga_satuan']) ?><input type="hidden" id="total<?= $baris ?>" name="total<?= $baris ?>" value="<?= $totalqty*$det_b2bdo['harga_satuan'] ?>"></td>
            </tr>

            <?php 
            }
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
  const checkList = document.querySelectorAll('.checkList');

  checkList.forEach((row)=>{
    console.log(row.id);
    if(loadedData.has(row.id)){
      console.log(row.id);
      row.classList.add('loadedData');
    }
  });

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
      let n = <?= $get_baris ?>;
      for(var i = 0; i< <?= $baris ?>; i++){
        var totqty = 0;
        if($('input[type=checkbox][name=chkid'+i+']').is(':checked')){
          window.opener.document.getElementById('idb2b'+(n)).value = $('#id_trans'+i).val();
          window.opener.document.getElementById('id_b2breturn_det'+(n)).value = "";
          window.opener.document.getElementById('idmstb2b'+(n)).value = $('#b2bdo_master'+i).val();
          window.opener.document.getElementById('iddetb2b'+(n)).value = $('#b2bdo_id'+i).val();
          window.opener.document.getElementById('idproduk'+(n)).value = $('#idbrg'+i).val();
          window.opener.document.getElementById('namaproduk'+(n)).value = $('#namabrg'+i).val();
          window.opener.document.getElementById('harga'+(n)).value = $('#price'+i).val();
          window.opener.document.getElementById('hargaHidden'+(n)).value = $('#price'+i).val();
          for(let j = 31; j<47; j++){
            window.opener.document.getElementById('idItem-'+(n)+'-'+j).value = $('#id-'+i+'-'+j).val();
            if($('#qty'+j).val() > 0){
              window.opener.document.getElementById('id-'+(n)+'-'+j).classList.add('red');
            }
            window.opener.document.getElementById('id-'+(n)+'-'+j).value = $('#qty-'+i+'-'+j).val();
            window.opener.document.getElementById('id-'+(n)+'-'+j).value = $('#qty-'+i+'-'+j).val();
            if($('#qty-'+i+'-'+j).val() > 0){
              window.opener.document.getElementById('id-'+(n)+'-'+j).style.color = "red";
            }else{
              window.opener.document.getElementById('id-'+(n)+'-'+j).style.backgroundColor = '#b3b3b3';
              window.opener.document.getElementById('qty-'+(n)+'-'+j).readOnly = true;
              window.opener.document.getElementById('qty-'+(n)+'-'+j).style.backgroundColor = '#D3D3D3';
              window.opener.document.getElementById('qty-'+(n)+'-'+j).style.border = '1px solid #4f4f4f';
              window.opener.document.getElementById('qty-'+(n)+'-'+j).tabIndex = '-1';
            }
            window.opener.document.getElementById('qty-'+(n)+'-'+j).value = 0;
            // totqty += parseInt($('#qty-'+i+'-'+j).val());
            // window.opener.document.getElementById('totalqty'+n).value=totqty;
          }
          window.opener.subtotalCount(n);
          if(window.opener.document.getElementById('idb2b'+(n+1)) == undefined){
            window.opener.addNewRow1();
          }
          n++;
        }
      }
      window.close();
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
