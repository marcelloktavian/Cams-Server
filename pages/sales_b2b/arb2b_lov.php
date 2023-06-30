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
      <td class="fontjudul">DATA B2B</td>
    </tr>
  </table>

  <hr>

  <table>
    <tr>
      <td>Tanggal B2B DO / RETURN</td>
      <td><input type="date" id="tglb2barstart" name="tglb2barstart" value="<?= str_replace("/","-",$kode1) ?>"> s/d <input type="date" id="tglb2barend" name="tglb2barend" value="<?= str_replace("/","-",$kode2) ?>"></td>
      <td>Nomor B2B DO</td>
      <td><input type="text" id="filterb2barlov" name="filterb2barlov" value="<?= $filter == "" ? "" : $filter ?>" /></td>
      <td><button id="btncari" name="btncari" onclick="cari()">Cari</button></td>
    </tr>
  </table>

  <br>

  <input type="hidden" id="hideurutan" name="hideurutan">

  <table width="100%" cellspacing="0" cellpadding="0" id="list_detail_b2bar" name="list_detail_b2bar" class="table table-bordered">
    <thead>
      <tr style="color: black;">
        <td><input type="checkbox" id="select_all" name="select_all"></td>
        <td>Type</td>
        <td>ID B2B DO / RETURN</td>
        <td>Customer</td>
        <td>Tanggal DO / RETURN</td>
        <td>Total Qty DO / RETURN</td>
        <td>Total DO / RETURN</td>
        <td>Keterangan</td>
      </tr>
    </thead>
    <tbody>
      <?php 
        $cust = $_GET['cust'];

        $sql_detail_b2bar = "SELECT b2bdo.`id`, b2bdo.`id_trans`, 'B2B DO' AS parent, b2bdo.`totalfaktur` AS `total`, b2bdo.totalkirim as totalqty, b2bdo.`tgl_trans`, DATE_FORMAT(b2bdo.tgl_trans, '%d-%m-%Y') AS tanggal_b2bar, b2bdo.`note` AS `keterangan`, mst_b2bcustomer.nama as customer FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.tgl_trans BETWEEN '".$kode1."' AND '".$kode2."' AND b2bdo.deleted = 0 AND b2bdo.id_customer='$cust'
        UNION 
        SELECT b2breturn.`id`, b2breturn.`b2breturn_num`, 'B2B RETURN' AS parent, b2breturn.`total`, b2breturn.qty as totalqty, b2breturn.`tgl_return`, DATE_FORMAT(b2breturn.tgl_return, '%d-%m-%Y') AS tanggal_b2bar, b2breturn.`keterangan`, mst_b2bcustomer.nama as customer FROM b2breturn LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2breturn.b2bcust_id WHERE b2breturn.deleted = 0 AND b2breturn.b2bcust_id='$cust'";

        $sql_detail_b2bar = mysql_query($sql_detail_b2bar);
        $baris  = $get_baris;

        while($det_b2bar = mysql_fetch_array($sql_detail_b2bar)){
          ?>

          <tr>
            <td class="table-light"><input type="checkbox" id="chkid<?= $baris ?>" name="chkid<?= $baris ?>" size="5" onclick=""></td>

            <td class="table-light"><?= $det_b2bar['parent'] ?><input type="hidden" id="b2b_id<?= $baris ?>" name="b2b_id<?= $baris ?>" value="<?= $det_b2bar['id'] ?>"><input type="hidden" id="b2b_parent<?= $baris ?>" name="b2b_parent<?= $baris ?>" value="<?= $det_b2bar['parent'] ?>"></td>

            <td class="table-light"><?= $det_b2bar['id_trans'] ?><input type="hidden" id="id_trans<?= $baris ?>" name="id_trans<?= $baris ?>" value="<?= $det_b2bar['id_trans'] ?>"></td>

            <td class="table-light"><?= $det_b2bar['customer'] ?><input type="hidden" id="customer<?= $baris ?>" name="customer<?= $baris ?>" value="<?= $det_b2bar['customer'] ?>"></td>

            <td class="table-light"><?= $det_b2bar['tanggal_b2bar'] ?><input type="hidden" id="tanggal_b2b<?= $baris ?>" name="tanggal_b2b<?= $baris ?>" value="<?= $det_b2bar['tanggal_b2bar'] ?>"></td>

            <td class="table-light right"><?= number_format($det_b2bar['totalqty']) ?><input type="hidden" id="totalqty<?= $baris ?>" name="totalqty<?= $baris ?>" value="<?= $det_b2bar['totalqty'] ?>" /></td>
            
            <td class="table-light right"><?= ($det_b2bar['parent'] == 'B2B DO') ? intToIDR($det_b2bar['total']) : intToIDR('-'.$det_b2bar['total']); ?><input type="hidden" id="total<?= $baris ?>" name="total<?= $baris ?>" value="<?= ($det_b2bar['parent'] == 'B2B DO') ? $det_b2bar['total'] : '-'.$det_b2bar['total']; ?>"></td>

            <td class="table-light"><?= $det_b2bar['keterangan'] ?><input type="hidden" id="keterangan<?= $baris ?>" name="keterangan<?= $baris ?>" value="<?= $det_b2bar['keterangan'] ?>" /></td>
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
    let tglstart    = $('#tglb2barstart').val();
    let tglend      = $('#tglb2barend').val();
    let filter      = $('#filterb2barlov').val();

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

  function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function pakai(){
    if(window.confirm("Apakah anda yakin ?")){
      let n = <?= $get_baris ?>;
      for(var i = 0; i< <?= $baris ?>; i++){
        if($('input[type=checkbox][name=chkid'+i+']').is(':checked')){
          window.opener.document.getElementById('idarb2b'+(n)).value = $('#b2b_id'+i).val();
          window.opener.document.getElementById('numb2b'+(n)).value = $('#id_trans'+i).val();
          window.opener.document.getElementById('typearb2b'+(n)).value = $('#b2b_parent'+i).val();
          window.opener.document.getElementById('customer'+(n)).value = $('#customer'+i).val();
          window.opener.document.getElementById('tanggalb2b'+(n)).value = $('#tanggal_b2b'+i).val();
          window.opener.document.getElementById('totalb2b'+(n)).value = $('#total'+i).val();
          var totalValue = $('#total' + i).val();
          var formattedTotal = formatNumber(totalValue);
          window.opener.document.getElementById('totalb2bDisplay' + n).value = formattedTotal;

          window.opener.document.getElementById('totalb2bproses'+(n)).value = 0;
          window.opener.document.getElementById('totalb2bprosesDisplay'+(n)).value = 0;

          window.opener.document.getElementById('totalb2bpending'+(n)).value = $('#total'+i).val();
          var totalValue = $('#total' + i).val();
          var formattedTotal = formatNumber(totalValue);
          window.opener.document.getElementById('totalb2bpendingDisplay' + n).value = formattedTotal;

          // window.opener.document.getElementById('keteranganb2b'+(n)).value = $('#keterangan'+i).val();
          if(window.opener.document.getElementById('idarb2b'+(n+1)) == undefined){
            window.opener.addNewRow1();
          }
          n++;
        }
      }
      window.opener.returnTotalCount();
      window.close();
    }
  }

  $(document).ready(function(){
    var table = $('#list_detail_b2bar').DataTable({
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