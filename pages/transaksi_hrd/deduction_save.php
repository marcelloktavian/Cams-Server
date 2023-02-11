<?php
error_reporting(0);
session_start();
include("../../include/koneksi.php");

$row = $_GET['jum'];
$id = $_GET['id_trans'];

    for ($i=1; $i<=$row; $i++)
	{
        $iddet      = $_POST['Id'.$i];

        $simpanan   = $_POST['Simpanan'.$i];
        $pinjaman   = $_POST['Pinjaman'.$i];
        $pph21      = $_POST['PPH21'.$i];
        $pph21thr   = $_POST['PPH21THR'.$i];
        $potongan   = $_POST['Potongan'.$i];
        $total      = $_POST['Total'.$i];

        $query = "UPDATE pengupahan_detail SET simpanan_kop='".$simpanan."',pinjaman_kop='".$pinjaman."',pph21='".$pph21."',pph21_thr='".$pph21thr."',potongan='".$potongan."',total_deduction='".$total."' WHERE upah_id_det='".$iddet."' ";
		//var_dump($query);die;
		$hasil = mysql_query($query) or die (mysql_error());
    }
?>
    <script language="javascript"> 
	    window.close();
	</script> 