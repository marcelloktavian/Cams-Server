<head>
  <title>DATA OPERASIONAL</title>
  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap4.min.js"></script>

  <!-- <script src="../../assets/js/time.js" type="text/javascript"></script> -->
  <style>
    body {
      background-color:#E4B65E ;
    } 
        
  </style>

<?php 
include("../../include/koneksi.php");
require_once '../../include/config.php';
error_reporting(0);
?>
</head>
<body>
  <?php

        if ($_COOKIE['tglstart']=='' && $_COOKIE['tglend']=='') {
            $kode1 = date("Y/m/d");
            $kode2 = date("Y/m/d");
        }else{
            $kode1 = $_COOKIE['tglstart'];
            $kode2 = $_COOKIE['tglend'];
        }
        setcookie("tglstart", "", time() - 3600);
        setcookie("tglend", "", time() - 3600);
  echo"
  <table width='100%'>
  <tr>
  <td class='fontjudul'>DATA OPERASIONAL</td>
  <td class='fontjudul'>TOTAL
        <input type='text' class='' name='subtotal_m' id='subtotal_m' value='0' style='text-align:right;font-size: 30px;background-color:white;width: 300px;height:40px;border:1px dotted #f30; border-radius:4px; -moz-border-radius:4px;' />
        
        <input type='hidden' id='hidesubtotal' name='hidesubtotal' value='0'>
    </td>

  </td>
  </tr>
  </table>
<hr>
<table>
<tr>
<td>Tanggal Transaksi</td>
<td><input type='date' id='tgltransstart' name='tgltransstart' value='".str_replace("/","-",$kode1)."'> s/d <input type='date' id='tgltransend' name='tgltransend' value='".str_replace("/","-",$kode2)."'></td>
<td><button id='btncari' name='btncari' onclick='cari()'>Cari</button></td>
<td><button id='btnpakai' name='btnpakai' onclick='pakai()'>Pakai</button></td>
</tr>
</table><br>
<input type='hidden' id='hideurutan' name='hideurutan'>
  <table width='100%' cellspacing='0' cellpadding='0' border='1px' id='tbl_operational' name='tbl_operational' class='table table-bordered'>
    <thead>
  <tr style='color:black;'>
        <th><input type='checkbox' id='select_all' name='select_all'></th>
        <th>ID</th>
        <th>Kode</th>
        <th>Tanggal</th>
        <th>Nama Biaya</th>
        <th>Qty</th>
        <th>Harga Satuan</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
        ";
        $sqlppn = "SELECT SUM(detop.ppn) AS ppn, DATE_FORMAT(mstop.tanggal,'%d/%m/%Y') as tanggal  FROM biayaoperasional mstop
      LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
      LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
      LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
      LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
      WHERE mstop.deleted=0 AND mstop.tanggal BETWEEN '".$kode1."' AND '".$kode2."' AND (detbiaya.id <> 163 AND detbiaya.id <> 164 AND detbiaya.id <> 186 AND detbiaya.id <> 187 AND detbiaya.id <> 269) GROUP BY mstop.tanggal
      ";

      // echo $sqlppn;
      $result2= mysql_query($sqlppn);
      $ppn = 0;
      $no=1;
      while ($data2 = mysql_fetch_array($result2)):
        $ppn = $data2['ppn'];
        echo"<tr>
        <td class='table-light'><input type='checkbox' name='chkid".$no."' onclick='simpannama(".'"'.$no.'"'.",".'"0"'.",".'"'.$ppn.'"'.")'></td>
       <td class='table-light'><input type='hidden' name='id'  id='id' value='0'>-</td>
       <td class='table-light'>-</td>
       <td class='table-light'>".$data2['tanggal']."</td>
       <td class='table-light'><input type='hidden' id='nama' name='nama' value=''>PPN MASUKAN ATAS PEMBELIAN</td>
       <td class='table-light'>1</td>
       <td class='table-light'><div style='float:right;'>".number_format($ppn,0)."</div></td>
       <td class='table-light'><input type='hidden' id='subtotal' name='subtotal' value='".$ppn."'><div style='float:right;'>".number_format($ppn,0)."</div></td>
        </tr>";
        $no++;
      endwhile;

      

    $total = 0;
    $where = "WHERE mst.deleted=0 AND date(mst.tanggal) between '$kode1' AND '$kode2' ORDER by mst.tanggal DESC, det.nama_biaya ASC";
    $sql = "SELECT det.*, mst.kode, DATE_FORMAT(mst.tanggal,'%d/%m/%Y') as tanggal FROM biayaoperasional_det det LEFT JOIN biayaoperasional mst ON mst.id=det.id_parent ".$where;
    $sq = mysql_query($sql);
    while($rs2=mysql_fetch_array($sq)){
        echo"<tr>
        <td class='table-light'><input type='checkbox' name='chkid".$no."' onclick='simpannama(".'"'.$no.'"'.",".'"'.$rs2['id'].'"'.",".'"'.$rs2['subtotal'].'"'.")'></td>
       <td class='table-light'><input type='hidden' name='id' id='id' value='".$rs2['id']."'>".$rs2['id']."</td>
       <td class='table-light'>".$rs2['kode']."</td>
       <td class='table-light'>".$rs2['tanggal']."</td>
       <td class='table-light'><input type='hidden' id='nama' name='nama' value='".$rs2['id']."'>".$rs2['nama_biaya']."</td>
       <td class='table-light'>".number_format($rs2['qty'],0).' '.$rs2['satuan']."</td>
       <td class='table-light'><div style='float:right;'>".number_format($rs2['harga_satuan'],0)."</div></td>
       <td class='table-light'><input type='hidden' id='subtotal' name='subtotal' value='".$rs2['subtotal']."'><div style='float:right;'>".number_format($rs2['subtotal'],0)."</div></td>
        </tr>";
        $no++;
        // $total = $total  + $rs2['subtotal']; 
    }

    echo "</tbody>
  </table>
    
  <table>
  <tr>
  <td>
  <p><input type='image' value='batal' src='../../assets/images/batal.png'  id='baru'  onClick='window.close();'/></p>
  </td>
  </tr>

  </table>";
  ?>

<script>
    var urutan = [];
    var total = 0;

    function cari() {
        var tglstart = document.getElementById('tgltransstart').value;
        var tglend = document.getElementById('tgltransend').value;
        document.cookie = "tglstart="+tglstart;
        document.cookie = "tglend="+tglend;
        location.reload();
    }

    function pakai() {
        if (window.confirm("Apakah anda yakin?")) {
            var b = <?php echo $_GET['baris'];?>;

            window.opener.document.getElementById("iddetbiaya").value = $('#hideurutan').val();
            window.opener.document.getElementById("kredit"+b+"").value = $('#hidesubtotal').val();
            window.opener.document.getElementById("debet"+b+"").value = '0';

            window.opener.document.getElementById("uraian"+b+"").focus();
            window.opener.hitungtotal();
            window.close();
       }
    }
    function simpannama(no, id, subtotal) {
        if ($('input[type=checkbox][name=chkid'+no+']').is(':checked')) {
            total = parseFloat(total) + parseFloat(subtotal);
            urutan.push(id);
        }
        else {
            total = parseFloat(total) - parseFloat(subtotal);
            var index = urutan.indexOf(id);
            if (index !== -1) {
                urutan.splice(index, 1);
            }
        }

        $('#hideurutan').val(urutan.toString());
        
        $('#hidesubtotal').val(total);

        var locale = 'IDR';
        var options = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
        var formatter = new Intl.NumberFormat(locale, options);

        document.getElementById("subtotal_m").value = formatter.format(total.toFixed(0));
        
        // var namanya = nama;
        // urutan.push(namanya);
        // $('#hideurutan').val(urutan.toString());
        // total = parseFloat(total) + parseFloat(subtotal);
        // $('#hidesubtotal').val(total);
        // $('#hidesubtotal').val(total);

        // var locale = 'IDR';
        // var options = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
        // var formatter = new Intl.NumberFormat(locale, options);

        // document.getElementById("subtotal_m").value = formatter.format(total.toFixed(0));
    }

    $(document).ready( function () {
        
        var table=$('#tbl_operational').DataTable({
            pageLength: 500,
            lengthMenu: [5, 10, 20, 50, 100, 200, 500],
            order: [[ 3, "DESC" ]]
        });

        $('#select_all').on('click', function () {
          // var data_urutan = [];
          
          // Get all rows with search applied
          var rows = table.rows({
            'search': 'applied'
          }).nodes();
          // Check/uncheck checkboxes for all rows in the table
          if($(this).is(':checked')){
            // console.log('Y');
            urutan = [];
            total = 0;
            $('input[type="checkbox"]', rows).prop('checked', true);
            
            $('#tbl_operational tbody').each(function () {
              $(this).children('tr').each(function () {
                urutan.push($(this).find('#id').val());
                total = parseFloat(total) + parseFloat($(this).find('#subtotal').val());
              });
            });
          }else{
            // console.log('T');
            $('input[type="checkbox"]', rows).prop('checked', false);

            urutan = [];
            total = 0;
          }

         
          $('#hideurutan').val(urutan.toString());

          $('#hidesubtotal').val(total);

            var locale = 'IDR';
            var options = {style: 'currency', currency: 'IDR', minimumFractionDigits: 2, maximumFractionDigits: 2};
            var formatter = new Intl.NumberFormat(locale, options);

            document.getElementById("subtotal_m").value = formatter.format(total.toFixed(0));

        });
     
} );


</script>
</body>