<?php
error_reporting(0);
session_start();
include("../../include/koneksi.php");

$row = $_GET['jum'];
$id = $_GET['id_trans'];

    for ($i=1; $i<=$row; $i++)
	{
        $iddet      = $_POST['Id'.$i];

        $kehadiran  = $_POST['Kehadiran'.$i];
        $overtime   = $_POST['Overtime'.$i];
        $thr        = $_POST['THR'.$i];
        $bonus      = $_POST['Bonus'.$i];
        $pendapatan = $_POST['Pendapatan'.$i];
        $total      = $_POST['Total'.$i];

        $query = "UPDATE pengupahan_detail SET kehadiran='".$kehadiran."',overtime='".$overtime."',thr='".$thr."',bonus='".$bonus."',pendapatan='".$pendapatan."',total_income='".$total."' WHERE upah_id_det='".$iddet."' ";
		//var_dump($query);die;
		$hasil = mysql_query($query) or die (mysql_error());
    }
?>
    <script language="javascript"> 
	    window.close();
	</script> 