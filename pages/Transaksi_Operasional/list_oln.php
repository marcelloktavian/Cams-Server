<head>
	<title>OLN SALES /B2B ORDER DATA</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap4.min.css" /> -->
    <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap4.min.js"></script>
    <script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
    <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

	<!-- <script src="../../assets/js/time.js" type="text/javascript"></script> -->
	<style>
		body {
			background-color:#00CCFF ;
		} 
        
	</style>
<script>
$().ready(function() {

    $("#dropcust").autocomplete("lookup_dropcust.php?", {
        width: 300
    });

    $("#dropcust").result(function(event, data, formatted) {
    var nama_ds = document.getElementById("dropcust").value;
    for(var h=0;h< nama_ds.length;h++){
        var did = nama_ds.split(':');
        if (did[0]=="") continue;
        var id_d=did[0];
        var status=did[2];
    }

    //alert("id_d="+id_d);
    $.ajax({
    url : 'lookup_dropcust_ambil.php?id='+id_d+'&stat='+status,
    dataType: 'json',
    data: "nama="+formatted,
    success: function(data) {
        var id_dropcust  = data.id;
        idnya = data.id;
        $('#iddropcust').val(id_dropcust);
        var nama_dropcust  = data.nama;
        $('#dropcust').val(nama_dropcust);
        var status_dropcust  = data.status;
        statusnya = data.status;
        $('#statdropcust').val(status_dropcust);
    }
    });	
        
    });
});
</script>
<?php 
include("../../include/koneksi.php");
require_once '../../include/config.php';
error_reporting(0);
// $id = $_GET['dropcust'];
// $stat = $_GET['stat'];

// $dropcust = '';
// $sql = '';

// if($stat = 'Dropshipper'){
//     $sql1 = "select nama from mst_dropshipper where id='$id' ";
// }else{
//     $sql1 = "select nama from mst_b2bcustomer where id='$id' ";
// }

// if($sql1 != ''){
//     $sq1 = mysql_query($sql1);
//     while($rs1=mysql_fetch_array($sq1)){
//         $dropcust = $rs1['nama'];
//     }
// }


?>
</head>
<body>
	<?php

        if ($_COOKIE['tglstartOLN']=='' && $_COOKIE['tglendOLN']=='') {
            $kode1 = date("Y/m/d");
            $kode2 = date("Y/m/d");
            $iddropcust = '';
            $dropcust = '';
            $statdropcust = '';
        }else{
            $kode1 = $_COOKIE['tglstartOLN'];
            $kode2 = $_COOKIE['tglendOLN'];
            $iddropcust = $_COOKIE['iddropcust'];
            $dropcust = $_COOKIE['dropcust'];
            $statdropcust = $_COOKIE['statdropcust'];
        }
        setcookie("tglstartOLN", "", time() - 3600);
        setcookie("tglendOLN", "", time() - 3600);
        setcookie("iddropcust", "", time() - 3600);
        setcookie("dropcust", "", time() - 3600);
        setcookie("statdropcust", "", time() - 3600);
	echo"
	<table width='100%'>
	<tr>
	<td class='fontjudul'>OLN SALES /B2B ORDER DATA</td>
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
<td>Dropshipper/Customer</td>
<td><input type='text' value='$dropcust' placeholder='Autosuggest Dropshipper/Customer' class='inputform' name='dropcust' id='dropcust' onkeydown='clear()'> <input type='hidden' value='$iddropcust' name='iddropcust' id='iddropcust'> <input type='hidden' value='$statdropcust' name='statdropcust' id='statdropcust'></td>
</tr>
<tr>
<td>Tanggal Transaksi</td>
<td><input type='date' id='tgltransstartOLN' name='tgltransstartOLN' value='".str_replace("/","-",$kode1)."'> s/d <input type='date' id='tgltransendOLN' name='tgltransendOLN' value='".str_replace("/","-",$kode2)."'></td>
<td><button id='btncari' name='btncari' onclick='cari()'>Cari</button></td>
<td><button id='btnpakai' name='btnpakai' onclick='pakai()'>Pakai</button></td>
</tr>
</table>
<br>
<input type='hidden' id='hideurutan' name='hideurutan'>
	<table width='100%' cellspacing='0' cellpadding='0' border='1px' id='tbl_oln' name='tbl_oln' class='table table-bordered'>
    <thead>
	<tr style='color:black;'>
        <th width='2%'><input type='checkbox' name='select_all_oln' id='select_all_oln'></th>
        <th width='15%'>ID</th>
        <th>Dropshipper/Customer</th>
        <th width='20%'>Tanggal Sales</th>
        <th width='20%'>Subtotal</th>
    </tr>
    </thead>
    <tbody>
        ";

        if($statdropcust == 'Dropshipper'){
            $sql = "(SELECT so.id, so.id_trans, DATE_FORMAT(so.tgl_trans, '%d/%m/%Y') AS tgl, (so.faktur-so.payment) AS total, dp.nama FROM olnso so LEFT JOIN mst_dropshipper dp ON dp.id=so.id_dropshipper WHERE so.deleted=0 AND so.stkirim=1 AND (so.faktur-so.payment)<>0 AND so.id_dropshipper='$iddropcust' AND date(so.lastmodified) BETWEEN '".$kode1."' AND '".$kode2."' ORDER BY so.id ASC)";
        }else if($statdropcust == 'Customer'){
            $sql = "(SELECT doo.id, doo.id_trans, DATE_FORMAT(doo.tgl_trans, '%d/%m/%Y') AS tgl, (doo.totalfaktur-doo.payment) AS total, cus.nama FROM b2bdo doo LEFT JOIN mst_b2bcustomer cus ON doo.id_customer=cus.id WHERE doo.deleted=0 AND (doo.totalfaktur-doo.payment)<>0 AND cus.id='$iddropcust' AND  date(tgl_trans) BETWEEN '".$kode1."' AND '".$kode2."' ORDER BY doo.id ASC)";
        }else{
            $sql = "(SELECT so.id, so.id_trans, DATE_FORMAT(so.tgl_trans, '%d/%m/%Y') AS tgl, (so.faktur-so.payment) AS total, dp.nama FROM olnso so LEFT JOIN mst_dropshipper dp ON dp.id=so.id_dropshipper WHERE so.deleted=0 AND so.stkirim=1 AND (so.faktur-so.payment)<>0 AND date(so.lastmodified) BETWEEN '".$kode1."' AND '".$kode2."' ORDER BY so.id DESC) 
            UNION ALL 
            (SELECT doo.id, doo.id_trans, DATE_FORMAT(doo.tgl_trans, '%d/%m/%Y') AS tgl, (doo.totalfaktur-doo.payment) AS total, cus.nama FROM b2bdo doo LEFT JOIN mst_b2bcustomer cus ON doo.id_customer=cus.id WHERE doo.deleted=0 AND (doo.totalfaktur-doo.payment)<>0 AND  date(tgl_trans) BETWEEN '".$kode1."' AND '".$kode2."' ORDER BY doo.id DESC) ORDER BY id ASC ";
        }
       
       // var_dump($sql);die;

        // $total = 0;
        $no=1;
    $sq = mysql_query($sql);
    while($rs2=mysql_fetch_array($sq)){
        echo"<tr>
        <td class='table-light'><input type='checkbox' name='chkid$no' name='chkid$no' onclick='simpannama(".'"'.$no.'"'.",".'"'.$rs2['id_trans'].'"'.",".'"'.$rs2['total'].'"'.")'></td>
       <td class='table-light'><input type='hidden' id='id_trans' name='id_trans' value='".$rs2['id_trans']."'>".$rs2['id_trans']."</td>
       <td class='table-light'>".$rs2['nama']."</td>
       <td class='table-light'>".$rs2['tgl']."</td>
       <td class='table-light'><input type='hidden' id='subtotal' name='subtotal' value='".$rs2['total']."'><div style='float:right;'>".number_format($rs2['total'],0)."</div></td>
        </tr>";

        // $total = $total  + $rs2['total']; 
        $no++;
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
        var tglstart = document.getElementById('tgltransstartOLN').value;
        var tglend = document.getElementById('tgltransendOLN').value;
        var iddropcust = document.getElementById('iddropcust').value;
        var dropcust = document.getElementById('dropcust').value;
        var statdropcust = document.getElementById('statdropcust').value;

        document.cookie = "tglstartOLN="+tglstart;
        document.cookie = "tglendOLN="+tglend;
        if(dropcust == ''){
            document.cookie = "iddropcust=";
            document.cookie = "dropcust=";
            document.cookie = "statdropcust=";
        }else{
            document.cookie = "iddropcust="+iddropcust;
            document.cookie = "dropcust="+dropcust;
            document.cookie = "statdropcust="+statdropcust;
        }
        
        location.reload();
    }

    function pakai() {
        if (window.confirm("Apakah anda yakin?")) {
            var baris = <?php echo $_GET['baris'];?>;
        //    console.log(baris);
        //    for (var i = 0; i < urutan.length; i++) {
                // var id_trans = urutan[i];
                $.ajax({
                    url: "ajax_oln.php",
                    type: "POST",
                    cache: false,
                    data:'urutan=' + urutan,
                    dataType:'json',
                    success: function(data){
                        // window.opener.addNewRow2();
                        var baris = <?php echo $_GET['baris'];?>;
                        for (var i = 0; i < data.length; i++) {
                            window.opener.addNewRow2();
                            window.opener.document.getElementById("IdOLN"+baris).value = data[i]['id'];
                            window.opener.document.getElementById("OLN"+baris).value = data[i]['id_trans'];
                            window.opener.document.getElementById("DropcustOLN"+baris).value = data[i]['namadropcust'];
                            window.opener.document.getElementById("IdDropcustOLN"+baris).value = data[i]['idnya'];
                            window.opener.document.getElementById("StatusDropcustOLN"+baris).value = data[i]['stat'];
                            window.opener.document.getElementById("TglOLN"+baris).value = data[i]['tgl'];
                            window.opener.document.getElementById("ValueOLN"+baris).value = data[i]['total'];
                            window.opener.document.getElementById("FakturOLN"+baris).value = data[i]['totalhidden'];
                            window.opener.document.getElementById("FakturhiddenOLN"+baris).value = data[i]['total'];
                            baris++;
                            window.opener.hitungtotal();

                        }
                        window.close();
                        
                      
                    }
                });
        //    }
            
            // window.close();
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
    }

    $(document).ready( function () {
        // var table=$('#tbl_oln').DataTable({
        //     pageLength: 500,
        //     lengthMenu: [5, 10, 20, 50, 100, 200, 500],
        //     order: [[ 3, "DESC" ]]
        // });

        $('#select_all_oln').click(function () {
          // var data_urutan = [];
          
          // Get all rows with search applied
          // var rows = table.rows({
          //   'search': 'applied'
          // }).nodes();
          // Check/uncheck checkboxes for all rows in the table
          if($(this).is(':checked')){
            // console.log('Y');
            urutan = [];
            total = 0;
            // $('input[type="checkbox"]', rows).prop('checked', true);
            
            $('#tbl_oln tbody').each(function () {
              $(this).children('tr').each(function () {
                $('input[type="checkbox"]').attr('checked', true);
                urutan.push($(this).find('#id_trans').val());
                total = parseFloat(total) + parseFloat($(this).find('#subtotal').val());
              });
            });
          }else{
            // console.log('T');
            // $('input[type="checkbox"]', rows).prop('checked', false);
            $('#tbl_oln tbody').each(function () {
              $(this).children('tr').each(function () {
                $('input[type="checkbox"]').attr('checked', false);
              });
            });
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