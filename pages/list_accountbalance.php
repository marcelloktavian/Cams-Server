<head>
	<title>DATA ACCOUNT BALANCE</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap4.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap4.min.js"></script>

	<!-- <script src="../../assets/js/time.js" type="text/javascript"></script> -->
	<style>
		body {
			background-color:#E4E66A ;
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
            // $kode1 = date("Y/m/d");
        }else{
            $kode1 = $_COOKIE['tglstart'];
        }
        setcookie("tglstart", "", time() - 3600);
	echo"
	<table width='100%'>
	<tr>
	<td  class='fontjudul'>DATA ACCOUNT BALANCE</td>
	</td>
	</tr>
	</table>
<hr>
<table>
<tr>
<td>Tanggal</td>
<td><input type='date' id='tgltransstart' name='tgltransstart' value='".str_replace("/","-",$kode1)."'> </td>
<td><button id='btncari' name='btncari' onclick='cari()'>Cari</button></td>
</tr>
</table><br>
<input type='hidden' id='hideurutan' name='hideurutan'>
<input type='hidden' id='hidesubtotal' name='hidesubtotal' value='0'>
	<table width='100%' cellspacing='0' cellpadding='0' border='1px' id='tbl_accountbalance' name='tbl_accountbalance' class='table table-bordered'>
    <thead>
	<tr style='color:black;'>
        <th>ID</th>
        <th>Nomor Akun</th>
        <th>Nama Akun</th>
        <th>Type</th>
        <th>Tanggal</th>
        <th>Saldo</th>
        <th>Pakai</th>
    </tr>
    </thead>
    <tbody>
        ";
    $sql_products ="SELECT a.* FROM `account_balance` a where a.deleted=0 GROUP BY noakun";
    $query = '';
    $countnya = 0;

    $sql = mysql_query($sql_products);
    while($line = mysql_fetch_array($sql)) {
        if ($countnya == 0) {
            if($kode1 != ''){
                $query .= "(select id, '' as iddet, noakun, nama, jenis, IFNULL(tanggal,'') as tanggal, saldo from account_balance where noakun='".$line['noakun']."' AND (date(tanggal)='$kode1' OR tanggal is NULL) ORDER BY tanggal DESC, id DESC LIMIT 1) ";
            }else{
                $query .= "(select id, '' as iddet, noakun, nama, jenis, IFNULL(tanggal,'') as tanggal, saldo from account_balance where noakun='".$line['noakun']."' ORDER BY tanggal DESC, id DESC LIMIT 1) ";
            }
        } else {
            if($kode1 != ''){
                $query .= " UNION ALL (select id,  '' as iddet,  noakun, nama, jenis, IFNULL(tanggal,'') as tanggal, saldo from account_balance  where noakun='".$line['noakun']."' AND (date(tanggal)='$kode1'  OR tanggal is NULL) ORDER BY tanggal DESC, id DESC LIMIT 1) ";
            }else{
                $query .= " UNION ALL (select id,  '' as iddet,  noakun, nama, jenis, IFNULL(tanggal,'') as tanggal, saldo from account_balance  where noakun='".$line['noakun']."' ORDER BY tanggal DESC, id DESC LIMIT 1) ";
            }
        }
        $countnya++;
        $sql2 = mysql_query("SELECT * FROM accountdet_balance WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
        while($line2 = mysql_fetch_array($sql2)) {
            $query .= " UNION ALL (select id_parent as id, id as iddet, noakun, nama, (select jenis from account_balance a where a.id=accountdet_balance.id_parent) as jenis, IFNULL(tanggal,'') as tanggal, saldo from accountdet_balance where id='".$line2['id']."')  ";
        }
        
    }

    $sql = mysql_query($query);

    $total = 0;
    $nomor=1;

    $sq = mysql_query($query);
    while($rs2=mysql_fetch_array($sq)){
        $date=date_create($rs2['tanggal']);
        $tgl='';
        if($rs2['tanggal'] != ''){
            $tgl = date_format($date,"d-m-Y");
        }
        echo"<tr>
       <td class='table-light'>".$nomor."</td>
       <td class='table-light'>".$rs2['noakun']."</td>
       <td class='table-light'>".$rs2['nama']."</td>
       <td class='table-light'>".$rs2['jenis']."</td>
       <td class='table-light'>".$tgl."</td>
       <td class='table-light'><input type='hidden' id='saldo' name='saldo' value='".$rs2['saldo']."'><div style='float:right;'>".number_format($rs2['saldo'],0)."</div></td>
       <td class='table-light'><input type='button' name='chkid' name='chkid' onclick='simpannama(".'"'.$rs2['id'].'"'.",".'"'.$rs2['iddet'].'"'.",".'"'.$rs2['noakun'].'"'.",".'"'.$rs2['nama'].'"'.",".'"'.$rs2['saldo'].'"'.",".'"'.$rs2['jenis'].'"'.",".'"'.$tgl.'"'.")' value='Pakai'></td>
        </tr>";

        // $total = $total  + $rs2['subtotal']; 
        $nomor++;
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
        document.cookie = "tglstart="+tglstart;
        location.reload();
    }

    function simpannama(idparent, iddetail, noakun, nama, subtotal, jenis, tanggal) {
        var b = <?php echo $_GET['baris'];?>;

        window.opener.document.getElementById("idakunparent"+b).value = idparent;
        window.opener.document.getElementById("idakundet"+b).value = iddetail;
        window.opener.document.getElementById("noakun"+b).value = noakun;
        window.opener.document.getElementById("akun"+b).value = nama;

       

        window.opener.document.getElementById("debet"+b).value = '';
        window.opener.document.getElementById("kredit"+b).value = '';
        window.opener.document.getElementById("debet"+b).removeAttribute("disabled");
        window.opener.document.getElementById("kredit"+b).removeAttribute("disabled");


        if (jenis == 'Debet') {
            window.opener.document.getElementById("debet"+b).value = subtotal;
            window.opener.document.getElementById("kredit"+b).setAttribute("disabled", true); 
        } else {
            window.opener.document.getElementById("kredit"+b).value = subtotal;
            window.opener.document.getElementById("debet"+b).setAttribute("disabled", true); 
            
        }

        var tgl = window.opener.document.getElementById("tanggal").value.split('-');
        window.opener.document.getElementById('bukti'+b+'').value='AK'+tgl[1]+tgl[0].substring(2,4)+'/';

        if(noakun=='1.01.00.00'){
            window.opener.document.getElementById("uraian"+b).value = 'Saldo Kas Per Tanggal '+tanggal;
            window.opener.document.getElementById("bukti"+b).focus();
            window.opener.document.getElementById("bukti"+b).value = window.opener.document.getElementById("bukti"+b).value;
        }else{
            window.opener.document.getElementById("uraian"+b).value = '';
            window.opener.document.getElementById("uraian"+b).focus();
        }

        window.opener.hitungtotal();
        window.close();
    }

    $(document).ready( function () {
        
        var table=$('#tbl_accountbalance').DataTable({
            pageLength: 500,
            lengthMenu: [5, 10, 20, 50, 100, 200, 500],
        });

        $('#select-all').on('click', function () {
             // Get all rows with search applied
             var data_urutan=[];
             var tot = 0;
             var rows = table.rows({
                 'search': 'applied'
             }).nodes();
             // Check/uncheck checkboxes for all rows in the table
             $('#tbl_accountbalance tbody').each(function(){
    			  $(this).children('tr').each(function(){
                        data_urutan.push($(this).find('#nama').val());
                        tot = parseFloat(tot) + parseFloat($(this).find('#subtotal').val());
    			  });
    		});
            $('#hideurutan').val(data_urutan.toString());
            $('#hidesubtotal').val(tot);
     });
     
} );


</script>
</body>