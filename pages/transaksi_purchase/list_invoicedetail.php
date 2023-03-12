<?php
include "../../include/koneksi.php";
require_once "../../include/config.php";

function intToIDR($val) {
  return 'Rp ' . number_format($val, 0, ',', '.') . ',-';
}
?>

<!DOCTYPE html>

<head>
  <title>DATA DETAIL INVOICE</title>

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
	</style>
</head>

<body>
  <?php
    if((!isset($_COOKIE['tglstart']) && !isset($_COOKIE['tglend'])) || ($_COOKIE['tglstart'] == '' && $_COOKIE['tglend'] == '')){
      $kode1 = date("Y/m/d"); $kode2 = date("Y/m/d");
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

    if(isset($_GET['sup']) && isset($_GET['sup']) != null){
      $supplier_filter = $_GET['sup'];
    }
    else{
      $supplier_filter = '';
    }

    setcookie("tglstart", "", time() - 3600);
    setcookie("tglend", "", time() - 3600);
  ?>

  <table width="100%">
    <tr>
      <td class="fontjudul">DATA DETAIL INVOICE</td>
    </tr>
    <tr>
      <td class="fontjudul">TOTAL QTY <input type="text" id="total_qty" name="total_qty" style="text-align: right; font-size: 30px; background-color: white; width: 300px; height: 40px; border: 1px dotted #f30; border-radius: 4px; -moz-border-radius: 4px;" readonly></td>
      <td class="fontjudul">PERKIRAAN TOTAL <input type="text" class="" name="subtotal_formatted" id="subtotal_formatted" value="0" style="text-align: right; font-size: 30px; background-color: white; width: 300px; height: 40px; border: 1px dotted #f30; border-radius: 4px; -moz-border-radius: 4px;" readonly/><input type="hidden" id="subtotal_value" name="subtotal_value" readonly></td>
    </tr>
  </table>

  <hr>

  <table>
    <tr>
      <td>Tanggal PO</td>
      <td><input type="date" id="tglapstart" name="tglapstart" value="<?= str_replace("/","-",$kode1) ?>"> s/d <input type="date" id="tglapend" name="tglapend" value="<?= str_replace("/","-",$kode2) ?>"></td>
      <td><button id="btncari" name="btncari" onclick="cari()">Cari</button></td>
    </tr>
  </table>
  
  <br>

  <input type="hidden" id="hideurutan" name="hideurutan">

  <table width="100%" cellspacing="0" cellpadding="0" id="list_detail_ap" name="list_detail_ap" class="table table-bordered">
    <thead>
      <tr style="color: black;">
        <td><input type="checkbox" id="select_all" name="select_all"></td>
        <td>ID</td>
        <td>Nomor Invoice</td>
        <td>Tanggal Invoice</td>
        <td>Tanggal Jatuh Tempo</td>
        <td>Supplier</td>
        <td>Qty</td>
        <td>Subtotal</td>
        <td>Subtotal Terbayar</td>
        <td>Subtotal Sisa</td>
        <td>Keterangan</td>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql_detail_inv  = "SELECT a.*,date_format(a.tanggal_invoice, '%d-%m-%Y') AS tanggal_invoice_formatted,date_format(a.tanggal_jatuh_tempo, '%d-%m-%Y') AS tanggal_jatuh_tempo_formatted FROM `mst_invoice` a WHERE a.deleted=0 AND a.tanggal_invoice BETWEEN '".$kode1."' AND '".$kode2."' AND a.`id_supplier`='".$supplier_filter."' AND a.`total` > a.`total_payment` AND `post_ap`=1";

        $get_detail_inv = mysql_query($sql_detail_inv);
        $baris  = $get_baris;

        while($det_ap = mysql_fetch_array($get_detail_inv)){
          ?>
          <tr>
            <td class="table-light"><input type="checkbox" id="chkid<?= $baris ?>" name="chkid<?= $baris ?>" size="5" onclick="simpan('<?= $baris ?>','<?= $det_ap['id'] ?>','<?= $det_ap['qty'] ?>','<?= $det_ap['total_remaining'] ?>')"></td>

            <td class="table-light"><?= $det_ap['id'] ?><input type="hidden" name="id<?= $baris ?>" id="id<?= $baris ?>" value="<?= $det_ap['id'] ?>"></td>

            <td class="table-light"><?= $det_ap['nomor_invoice'] ?><input type="hidden" name="nomor_invoice<?= $baris ?>" id="nomor_invoice<?= $baris ?>" value="<?= $det_ap['nomor_invoice'] ?>"></td>

            <td class="table-light"><?= $det_ap['tanggal_invoice_formatted'] ?><input type="hidden" name="tanggal_invoice<?= $baris ?>" id="tanggal_invoice<?= $baris ?>" value="<?= $det_ap['tanggal_invoice'] ?>"></td>

            <td class="table-light"><?= $det_ap['tanggal_jatuh_tempo_formatted'] ?><input type="hidden" name="tanggal_jatuh_tempo<?= $baris ?>" id="tanggal_jatuh_tempo<?= $baris ?>" value="<?= $det_ap['tanggal_jatuh_tempo'] ?>"></td>

            <td class="table-light"><?= $det_ap['supplier'] ?><input type="hidden" name="supplier<?= $baris ?>" id="supplier<?= $baris ?>" value="<?= $det_ap['supplier'] ?>"></td>

            <td class="table-light text-right"><?= $det_ap['qty'] ?><input type="hidden" name="qty<?= $baris ?>" id="qty<?= $baris ?>" value="<?= $det_ap['qty'] ?>"><input type="hidden" name="qty" id="qty" value="<?= $det_ap['qty'] ?>"></td>

            <td class="table-light text-right"><?= intToIDR($det_ap['total']) ?><input type="hidden" name="total<?= $baris ?>" id="total<?= $baris ?>" value="<?= $det_ap['total'] ?>"></td>

            <td class="table-light text-right"><?= intToIDR($det_ap['total_payment']) ?><input type="hidden" name="total_payment<?= $baris ?>" id="total_payment<?= $baris ?>" value="<?= $det_ap['total_payment'] ?>"></td>

            <td class="table-light text-right"><?= intToIDR($det_ap['total_remaining']) ?><input type="hidden" name="total_remaining<?= $baris ?>" id="total_remaining<?= $baris ?>" value="<?= $det_ap['total_remaining'] ?>"><input type="hidden" name="total_remaining" id="total_remaining" value="<?= $det_ap['total_remaining'] ?>"></td>

            <td class="table-light"><?= $det_ap['keterangan'] ?><input type="hidden" name="keterangan<?= $baris ?>" id="keterangan<?= $baris ?>" value="<?= $det_ap['keterangan'] ?>"></td>
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
  var total         = 0;
  var total_qty     = 0;

  function cari(){
    var tglstart    = $('#tglapstart').val();
    var tglend      = $('#tglapend').val();

    document.cookie = "tglstart="+tglstart;
    document.cookie = "tglend="+tglend;
    document.cookie = "baris="+1;

    location.reload();
  }

  function pakai(){
    if(window.confirm("Apakah anda yakin ?")){
      for(var i = <?= $_GET['baris'] ?>; i< <?= $baris ?>; i++){
        if($('input[type=checkbox][name=chkid'+i+']').is(':checked')){
          if(window.opener.document.getElementById('id_invoice'+(window.opener.baris1-1)).value == ""){ 
            window.opener.document.getElementById('id_invoice'+(window.opener.baris1-1)).value = $('#id'+i).val();
            window.opener.document.getElementById('nomor_invoice'+(window.opener.baris1-1)).value = $('#nomor_invoice'+i).val();
            window.opener.document.getElementById('tanggal_invoice'+(window.opener.baris1-1)).value = $('#tanggal_invoice'+i).val();
            window.opener.document.getElementById('tanggal_jatuh_tempo'+(window.opener.baris1-1)).value = $('#tanggal_jatuh_tempo'+i).val();
            window.opener.document.getElementById('qty'+(window.opener.baris1-1)).value = $('#qty'+i).val();
            window.opener.document.getElementById('total_inv'+(window.opener.baris1-1)).value = $('#total_remaining'+i).val();
            window.opener.document.getElementById('total_terbayar_inv'+(window.opener.baris1-1)).value = $('#total_payment'+i).val();
            window.opener.document.getElementById('total_sisa_inv'+(window.opener.baris1-1)).value = $('#total_remaining'+i).val();
            window.opener.addNewRow1();
          }
        }
      }
      window.close();
    }
  }

  function simpan(no, id, qty, subtotal){
    if ($('input[type=checkbox][name=chkid'+no+']').is(':checked')){
      total = parseFloat(total) + parseFloat(subtotal);
      total_qty = parseFloat(total_qty) + parseFloat(qty);
      urutan.push(id);
    }
    else {
      total = parseFloat(total) - parseFloat(subtotal);
      total_qty = parseFloat(total_qty) - parseFloat(qty);
      var index = urutan.indexOf(id);
      if (index != -1) {
        urutan.splice(index, 1);
      }
    }

    if(total < 0 ){total = 0;}
    if(total_qty < 0){total_qty = 0;}

    $('#hideurutan').val(urutan.toString());
    $('#subtotal_value').val(urutan.toString());
    $('#total_qty').val(total_qty);

    var locale    = 'IDR';
    var options   = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
    var formatter = new Intl.NumberFormat(locale, options);

    document.getElementById("subtotal_formatted").value = formatter.format(total.toFixed(0));
  }

  $(document).ready(function(){

    var table = $('#list_detail_ap').DataTable({
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

        $("#list_detail_ap tbody").each(function(){
          $(this).children('tr').each(function(){
            total     = parseFloat(total)+parseFloat($(this).find('#total_remaining').val());
            total_qty = parseFloat(total_qty)+parseFloat($(this).find('#qty').val());
          });
        });
      }
      else{
        $("input[type='checkbox']", rows).prop('checked', false);

        urutan        = [];
        total         = 0;
        total_qty     = 0;
      }
      $('#hideurutan').val(urutan.toString());
      $('#subtotal_value').val(total);
      $('#total_qty').val(total_qty);

      var locale      = 'IDR';
      var options     = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
      var formatter   = new Intl.NumberFormat(locale, options);

      document.getElementById("subtotal_formatted").value = formatter.format(total.toFixed(0));
    });
  });
</script>