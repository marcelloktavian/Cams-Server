<?php 
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

// $stok=0;

$idbrg=$_POST['id_products'];
$row=$_GET['row'];
$sizenew = '';
$totalqty = 0;

$qty31=0;
$qty32=0;
$qty33=0;
$qty34=0;
$qty35=0;
$qty36=0;
$qty37=0;
$qty38=0;
$qty39=0;
$qty40=0;
$qty41=0;
$qty42=0;
$qty43=0;
$qty44=0;
$qty45=0;
$qty46=0;
// $action = $_GET['action'];

//
$count = 1;
for ($i=1; $i<$row; $i++)
    {
       if($_POST['Size'.$i] == ''){
        }
        else
        {
           if ($_POST['Qty'.$i] == '0' || $_POST['Qty'.$i] == '') {
           } else {
            $size = $_POST['Size'.$i];
            $qty = $_POST['Qty'.$i];

            if ($count==1) {
                $sizenew = $size.'='.$qty;
            }else{
                $sizenew = $sizenew.';'.$size.'='.$qty;
            }
            $count++;
            $totalqty = $totalqty + $qty;

            if ($size == '31') {
               $qty31 = $qty31 + $qty;
            }
            if ($size == '32') {
                $qty32 = $qty32 + $qty;
             }
             if ($size == '33') {
                $qty33 = $qty33 + $qty;
             }
             if ($size == '34') {
                $qty34 = $qty34 + $qty;
             }
             if ($size == '35') {
                $qty35 = $qty35 + $qty;
             }
             if ($size == '36') {
                $qty36 = $qty36 + $qty;
             }
             if ($size == '37') {
                $qty37 = $qty37 + $qty;
             }
             if ($size == '38') {
                $qty38 = $qty38 + $qty;
             }
             if ($size == '39') {
                $qty39 = $qty39 + $qty;
             }
             if ($size == '40') {
                $qty40 = $qty40 + $qty;
             }
             if ($size == '41') {
                $qty41 = $qty41 + $qty;
             }
             if ($size == '42') {
                $qty42 = $qty42 + $qty;
             }
             if ($size == '43') {
                $qty43 = $qty43 + $qty;
             }
             if ($size == '44') {
                $qty44 = $qty44 + $qty;
             }
             if ($size == '45') {
                $qty45 = $qty45 + $qty;
             }
             if ($size == '46') {
                $qty46 = $qty46 + $qty;
             }
           }
           
            
        }

    }
    // echo $sizenew;
?>
<script language="javascript"> 
	var b = <?php echo $_GET['baris'];?>;

	window.opener.document.getElementById("Size"+b+"").value = '<?= $sizenew ?>';
	window.opener.document.getElementById("SUBTOTALQTY"+b+"").value = '<?= $totalqty ?>';

    window.opener.document.getElementById("S31_"+b+"").value = '<?= $qty31 ?>';
    window.opener.document.getElementById("S32_"+b+"").value = '<?= $qty32 ?>';
    window.opener.document.getElementById("S33_"+b+"").value = '<?= $qty33 ?>';
    window.opener.document.getElementById("S34_"+b+"").value = '<?= $qty34 ?>';
    window.opener.document.getElementById("S35_"+b+"").value = '<?= $qty35 ?>';
    window.opener.document.getElementById("S36_"+b+"").value = '<?= $qty36 ?>';
    window.opener.document.getElementById("S37_"+b+"").value = '<?= $qty37 ?>';
    window.opener.document.getElementById("S38_"+b+"").value = '<?= $qty38 ?>';
    window.opener.document.getElementById("S39_"+b+"").value = '<?= $qty39 ?>';
    window.opener.document.getElementById("S40_"+b+"").value = '<?= $qty40 ?>';
    window.opener.document.getElementById("S41_"+b+"").value = '<?= $qty41 ?>';
    window.opener.document.getElementById("S42_"+b+"").value = '<?= $qty42 ?>';
    window.opener.document.getElementById("S43_"+b+"").value = '<?= $qty43 ?>';
    window.opener.document.getElementById("S44_"+b+"").value = '<?= $qty44 ?>';

    try {
        window.opener.hitungjml(b);
    } catch (err) {
        alert(err.description || err) //or console.log or however you debug
    }

	window.opener.document.getElementById("Disc"+b+"").focus();
	window.close();
</script> 