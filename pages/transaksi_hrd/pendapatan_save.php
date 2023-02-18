<?php
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$id = $_GET['id'];
$row = $_GET['jum'];
$action = $_GET['action'];

if($action == 'list'){
    $total = 0;
    $row = $_GET['row'];
    $id_penggajian = $_GET['penggajian'];
    $id_karyawan =  $_POST['idKaryawan'];
    $kehadiran =  $_POST['kehadiran'];

    for ($i=1; $i<=$row; $i++)
	{
        if(!isset($_POST['Id'.$i])){
		}
		else
		{
            if($_POST['Id'.$i] !== ''){
                $id_detail = $_POST['Id'.$i];

                $subtotal = $_POST['Subtotal'.$i];
                $keterangan = $_POST['Keterangan'.$i];

                $querydet = "UPDATE `hrd_penggajiandet` SET subtotal_variabel='$subtotal' WHERE id_penggajiandet='$id_detail' ";
                $hasildet = mysql_query($querydet) or die ("error".mysql_error());

                $total += $subtotal;
            }
        }
    }

    // $query = "UPDATE `hrd_karyawan` SET total_pendapatan=(SELECT SUM(a.subtotal) FROM hrd_karyawandet a RIGHT JOIN hrd_pendapatan_potongan b ON a.id_penpot=b.id_penpot WHERE a.id_karyawan='$id_karyawan' AND b.type='pendapatan' )+$total, total =(($total+(SELECT SUM(a.subtotal) FROM hrd_karyawandet a RIGHT JOIN hrd_pendapatan_potongan b ON a.id_penpot=b.id_penpot WHERE a.id_karyawan='$id_karyawan' AND b.type='pendapatan' ))-total_potongan)  WHERE id_karyawan='$id_karyawan' ";
    // $hasil = mysql_query($query) or die ("error".mysql_error());

    // $query = "UPDATE `hrd_penggajiandet` SET subtotal=(SELECT SUM(subtotal) FROM hrd_karyawandet a 
    //     LEFT JOIN `hrd_pendapatan_potongan` b ON b.`id_penpot`=a.`id_penpot`
    //     WHERE a.id_karyawan=$id_karyawan AND b.type='pendapatan'), jml_kehadiran='$kehadiran', subtotal_variabel='$total' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' AND `status` ='pendapatan' ";
    // $hasil = mysql_query($query) or die ("error".mysql_error());

    $query = "UPDATE `hrd_penggajiandet` SET jml_kehadiran='$kehadiran' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    $data = mysql_query("SELECT * FROM hrd_penggajiandet a 
    LEFT JOIN hrd_karyawandet b ON a.id_penpot=b.id_penpot AND a.id_karyawan=b.id_karyawan 
    LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot AND a.id_karyawan=b.id_karyawan 
    WHERE a.id_penggajian=$id_penggajian AND a.id_karyawan=$id_karyawan AND c.`metode_pethitungan`='Per Hari Hadir'");
    $row = mysql_num_rows( $data );
    if($row > 0){
        while($d = mysql_fetch_array($data)){
            $query = "UPDATE `hrd_penggajiandet` SET subtotal=(".$d['value']."*".$kehadiran.") WHERE id_penggajiandet='".$d['id_penggajiandet']."' ";
            $hasil = mysql_query($query) or die ("error".mysql_error());
        }
    }

    $query = "UPDATE `hrd_penggajian` SET total_pendapatan=(select SUM(subtotal) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='pendapatan' AND id_penggajian = '$id_penggajian'), total_potongan=(select SUM(subtotal) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot  where c.`total_pendapatan`=1 AND status='potongan' AND id_penggajian = '$id_penggajian' ),total_pendapatan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='pendapatan' AND id_penggajian = '$id_penggajian'), total_potongan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='potongan' AND id_penggajian = '$id_penggajian')  WHERE penggajian_id='$id_penggajian' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}else{
    $id_penggajian = $_GET['penggajian'];
    for ($i=1; $i<=$row; $i++)
	{
        $delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
        $id_karyawan = $_POST['IdKaryawan'.$i];
        $subtotal = $_POST['TotalHidden'.$i];
        $status = 'pendapatan';

        if($id_karyawan=='' && $id_detail=='' && $delete==''){
        }
        else
        {

            if($delete!='' && $id_detail==''){
                //delete
                $sql_delete="delete from hrd_penggajiandet WHERE id_penggajiandet = '$delete' ";
                // var_dump($sql_delete);
                mysql_query($sql_delete);
            }
        }
    }

    $total = $_POST['totalhidden'];

    $query = "UPDATE `hrd_penggajian` SET total_pendapatan=(select SUM(subtotal) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='pendapatan' AND id_penggajian = '$id_penggajian'), total_potongan=(select SUM(subtotal) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot  where c.`total_pendapatan`=1 AND status='potongan' AND id_penggajian = '$id_penggajian' ),total_pendapatan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='pendapatan' AND id_penggajian = '$id_penggajian'), total_potongan_variabel=(select SUM(subtotal_variabel) from hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot where c.`total_pendapatan`=1 AND status='potongan' AND id_penggajian = '$id_penggajian')  WHERE penggajian_id='$id_penggajian' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}
?>
<script>
    window.close();
</script>