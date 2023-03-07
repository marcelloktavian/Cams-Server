<?php
include "../../include/koneksi.php";
require_once "../../include/config.php";
?>

<!DOCTYPE html>

<head>
  <title>DATA DETAIL PO</title>

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

      document.getElementById("subtotal"+idx).value = formatter.format(val.toFixed(0));
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
    if((!isset($_COOKIE['tglstart']) && !isset($_COOKIE['tglend'])) || ($_COOKIE['tglstart'] == ''  &&  $_COOKIE['tglend'] == '')){
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
      <td class="fontjudul">DATA DETAIL PO</td>
    </tr>
    <tr>
      <td class="fontjudul">TOTAL QTY<input type="text" id="total_qty" name="total_qty" readonly></td>
      <td class="fontjudul">PERKIRAAN TOTAL<input type="text" class="" name="subtotal_formatted" id="subtotal_formatted" value="0" style="text-align: right; font-size: 30px; background-color: white; width: 300px; height: 40px; border: 1px dotted #f30; border-radius: 4px; -moz-border-radius: 4px;" readonly/><input type="hidden" id="subtotal_value" name="subtotal_value" readonly></td>
    </tr>
  </table>

  <hr>

  <table>
    <tr>
      <td>Tanggal PO</td>
      <td><input type="date" id="tglpostart" name="tglpostart" value="<?= str_replace("/","-",$kode1) ?>"> s/d <input type="date" id="tglpoend" name="tglpoend" value="<?= str_replace("/","-",$kode2) ?>"></td>
      <td><button id="btncari" name="btncari" onclick="cari()">Cari</button></td>
    </tr>
  </table>

  <br>

  <input type="hidden" id="hideurutan" name="hideurutan">

  <table width="100%" cellspacing="0" cellpadding="0" id="list_detail_po" name="list_detail_po" class="table table-bordered">
    <thead>
      <tr style="color: black;">
        <th><input type="checkbox" id="select_all" name="select_all"></th>
        <th>ID</th>
        <th>Dokumen</th>
        <th>Tanggal PO</th>
        <th>Supplier</th>
        <th>Barang / Jasa</th>
        <th>Qty Total</th>
        <th>Qty Terproses</th>
        <th>Qty Pending</th>
        <th>Satuan</th>
        <th>DPP/Unit</th>
        <th>Subtotal Sisa</th>
        <th>Nomor Akun</th>
        <th>Nama Akun</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql_detail_po  = "SELECT a.*,date_format(b.tgl_po,'%d-%m-%Y') as tgl_po_formatted,b.dokumen,b.tgl_po,b.nama_supplier,b.persen_ppn FROM `det_po` a LEFT JOIN `mst_po` b ON a.id_po=b.id WHERE a.deleted=0 AND b.tgl_po BETWEEN '".$kode1."' AND '".$kode2."' AND b.`id_supplier`='".$supplier_filter."'AND a.`qty` > a.`qty_terbayar` AND b.approval=1 ";

        $get_detail_po  = mysql_query($sql_detail_po);        
        $baris = $get_baris;

        while($det_po = mysql_fetch_array($get_detail_po)){
          ?>
          <tr>
            <td class="table-light"><input type="checkbox" id="chkid<?= $baris ?>" name="chkid<?= $baris ?>" size="5" onclick="simpan('<?= $baris ?>', '<?= $det_po['id'] ?>', '<?= $det_po['qty']-$det_po['qty_terbayar'] ?>', '<?= $det_po['subtotal']+($det_po['subtotal']*$det_po['persen_ppn']/100) ?>')"></td>

            <td class="table-light"><?= $det_po['id'] ;?><input type="hidden" name="id<?= $baris ?>" id="id<?= $baris ?>" value="<?= $det_po['id'] ?>" size="5" style="text-align:center; border: 0;" readonly/></td>

            <td class="table-light"><?= $det_po['dokumen'] ?><input type="hidden" name="id_po<?= $baris ?>" id="id_po<?= $baris ?>" value="<?= $det_po['id_po'] ?>" readonly><input type="hidden" name="dokumen_po<?= $baris ?>" id="dokumen_po<?= $baris ?>" value="<?= $det_po['dokumen'] ?>" size="17" style="text-align:center; border: 0;" readonly></td>

            <td class="table-light"><?= $det_po['tgl_po_formatted'] ?><input type="hidden" name="tanggal_po<?= $baris ?>" id="tanggal_po<?= $baris ?>" value="<?= str_replace("/","-",$det_po['tgl_po']) ?>" size="10" style="text-align:center; border: 0;" readonly></td>

            <td class="table-light"><?= $det_po['nama_supplier'] ?><input type="hidden" name="supplier<?= $baris ?>" id="suppllier<?= $baris ?>" value="<?= $det_po['nama_supplier'] ?>" size="30" style="text-align:left; border: 0;" readonly/></td>

            <td class="table-light"><?= $det_po['nama_produk'] ?><input type="hidden" name="barang_jasa<?= $baris ?>" id="barang_jasa<?= $baris ?>" value="<?= $det_po['nama_produk'] ?>" style="text-align:left; border: 0;" readonly><input type="hidden" id="id_produk<?= $baris ?>" name="id_produk<?= $baris ?>" value="<?= $det_po['id_produk'] ?>"></td>

            <td class="table-light text-right"><?= $det_po['qty'] ?></td>

            <td class="table-light text-right"><?= $det_po['qty_terbayar'] ?></td>

            <td class="table-light text-right"><?= $det_po['qty']-$det_po['qty_terbayar'] ?><input type="hidden" name="qty" id="qty" value="<?= $det_po['qty']-$det_po['qty_terbayar'] ?>" readonly><input type="hidden" name="qty<?= $baris ?>" id="qty<?= $baris ?>" value="<?= $det_po['qty']-$det_po['qty_terbayar'] ?>" style="text-align:right; border: 0;" readonly><input type="hidden" name="qty_terbayar<?= $baris ?>" id="qty_terbayar<?= $baris ?>" value="<?= $det_po['qty_terbayar'] ?>" style="text-align:right; border: 0;" readonly></td>

            <td class="table-light"><?= $det_po['satuan'] ?><input type="hidden" name="satuan<?= $baris ?>" id="satuan<?= $baris ?>" value="<?= $det_po['satuan'] ?>" size="5" style="text-align:left; border: 0;" readonly></td>

            <td class="table-light"><input class="text-left" type="text" name="dpp_formatted<?= $baris ?>" id="dpp_formatted<?= $baris ?>" value="" style="text-align:left; border: 0;" size="10" readonly /><input class="text-right" type="hidden" name="dpp_unit<?= $baris ?>" id="dpp_unit<?= $baris ?>" value="<?= $det_po['price'] ?>" size="10" style="text-align:left; border: 0;" readonly></td>

            <td class="table-light"><input type="hidden" name="subtotal_value" id="subtotal_value" value="<?= $det_po['subtotal']+($det_po['subtotal']*$det_po['persen_ppn']/100) ?>" /><input type="hidden" name="subtotal_hidden<?= $baris ?>" id="subtotal_hidden<?= $baris ?>" value="<?= $det_po['subtotal']+($det_po['subtotal']*$det_po['persen_ppn']/100) ?>" readonly><input type="text" name="subtotal<?= $baris ?>" id="subtotal<?= $baris ?>" value="" style="text-align:left; border: 0;" readonly></td>

            <td class="table-light"><input type="hidden" name="id_akun<?= $baris ?>" id="id_akun<?= $baris ?>" value="<?= $det_po['id_akun'] ?>" /><input type="text" name="nomor_akun<?= $baris ?>" id="nomor_akun<?= $baris ?>" value="<?= $det_po['nomor_akun'] ?>" /></td>

            <td class="table-light"><input type="text" name="nama_akun<?= $baris ?>" id="nama_akun<?= $baris ?>" value="<?= $det_po['nama_akun'] ?>" /></td>

            <input type="hidden" id="persen_po<?= $baris ?>" name="persen_po<?= $baris ?>" value="<?= $det_po['persen_ppn'] ?>">

            <script>
              intToIDRSubtotal(<?= $det_po['subtotal']+($det_po['subtotal']*$det_po['persen_ppn']/100) ?>, <?= $baris ?>);
              intToIDRUnit(<?= $det_po['price'] ?>, <?= $baris ?>);
            </script>
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
    var tglstart    = $('#tglpostart').val();
    var tglend      = $('#tglpoend').val();

    document.cookie = "tglstart="+tglstart;
    document.cookie = "tglend="+tglend;
    document.cookie = "baris="+1;

    location.reload();
  }

  function pakai(){
    if (window.confirm("Apakah anda yakin ?")){
      for (var i = <?= $_GET['baris'] ?>; i< <?= $baris ?>; i++){
        if ($('input[type=checkbox][name=chkid'+i+']').is(':checked')){
          if(window.opener.document.getElementById('id'+(window.opener.baris1-1)).value == ""){
            window.opener.document.getElementById('id'+(window.opener.baris1-1)).value = $('#id'+i).val();
            window.opener.document.getElementById('persen_ppn'+(window.opener.baris1-1)).value = $('#persen_po'+i).val();
            window.opener.document.getElementById('id_po'+(window.opener.baris1-1)).value = $('#id_po'+i).val();
            window.opener.document.getElementById('nomor_dokumen'+(window.opener.baris1-1)).value = $('#dokumen_po'+i).val();
            window.opener.document.getElementById('id_produk'+(window.opener.baris1-1)).value = $('#id_produk'+i).val();
            window.opener.document.getElementById('produk_jasa'+(window.opener.baris1-1)).value = $('#barang_jasa'+i).val();
            window.opener.document.getElementById('qty_remaining'+(window.opener.baris1-1)).value = $('#qty'+i).val();
            window.opener.document.getElementById('qty_payment'+(window.opener.baris1-1)).value = $('#qty_terbayar'+i).val();
            window.opener.document.getElementById('satuan'+(window.opener.baris1-1)).value = $('#satuan'+i).val();
            window.opener.document.getElementById('dpp_unit'+(window.opener.baris1-1)).value = $('#dpp_unit'+i).val();
            window.opener.document.getElementById('idAkun'+(window.opener.baris1-1)).value = $('#id_akun'+i).val();
            window.opener.document.getElementById('nomorAkun'+(window.opener.baris1-1)).value = $('#nomor_akun'+i).val();
            window.opener.document.getElementById('namaAkun'+(window.opener.baris1-1)).value = $('#nama_akun'+i).val();
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

    $('#hideurutan').val(urutan.toString());
    $('#subtotal_value').val(urutan.toString());
    $('#total_qty').val(total_qty);

    var locale    = 'IDR';
    var options   = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
    var formatter = new Intl.NumberFormat(locale, options);

    document.getElementById("subtotal_formatted").value = formatter.format(total.toFixed(0));
  }

  $(document).ready(function(){

    var table = $('#list_detail_po').DataTable({
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

        $("#list_detail_po tbody").each(function(){
          $(this).children('tr').each(function(){
            total     = parseFloat(total)+parseFloat($(this).find('#subtotal_value').val());
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