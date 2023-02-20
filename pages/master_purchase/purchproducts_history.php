<?php
include "../../include/koneksi.php";
require_once "../../include/config.php";
?>

<!DOCTYPE html>

<head>
  <title>DATA HISTORY PRODUK</title>

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
  <table width="100%">
    <tr>
      <td class="fontjudul">DATA HISTORY PRODUK</td>
    </tr>
  </table>

  <hr>

  <table width="100%" cellspacing="0" cellpadding="0" id="list_history_produk" name="list_history_produk" class="table table-bordered">
    <thead>
      <tr style="color: black;">
        <th width="5%">#</th>
        <th>Produk / Jasa</th>
        <th>Tanggal Quotation</th>
        <th>DPP/Unit</th>
        <th>Satuan</th>
        <th>Lastmodified</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $id=$_GET['id'];
        $sql_detail_po  = "SELECT n.produk_jasa, m.harga, DATE_FORMAT(m.`tgl_quotation`,'%d-%m-%Y') AS tglqo, n.satuan, DATE_FORMAT(m.`lastmodified`,'%d-%m-%Y %H:%i:%s') AS tglakhir FROM history_mst_produk m LEFT JOIN mst_produk n ON m.id_produk=n.id WHERE n.id='$id' ORDER BY m.`lastmodified` DESC";

        $get_detail_po  = mysql_query($sql_detail_po);        
        $no = 1;
        while($det_po = mysql_fetch_array($get_detail_po)){
          ?>
          <tr>
            <td><?=number_format($no)?></td>
            <td><?=$det_po['produk_jasa']?></td>
            <td><?=$det_po['tglqo']?></td>
            <td align="right"><?=number_format($det_po['harga'])?></td>
            <td><?=$det_po['satuan']?></td>
            <td><?=$det_po['tglakhir']?></td>
          </tr>

          <?php
          $no ++;
        }
      ?>
    </tbody>
  </table>

  <table>
    <tr>
      <td>
        <p><input type="image" value="batal" src="../../assets/images/batal.png" id="baru" onclick="window.close();" /></p>
      </td>
    </tr>
  </table>
</body>

<script>
    $(document).ready(function(){
        var table = $('#list_history_produk').DataTable({
            pageLength : 500,
            lengthMenu : [5, 10, 20, 50, 100, 200, 500],
        });
    });
</script>