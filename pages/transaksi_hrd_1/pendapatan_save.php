<?php
// error_reporting(0);
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

                $querydet = "UPDATE `hrd_karyawandet` SET subtotal='$subtotal', keterangan='$keterangan' WHERE id_det_karyawan='$id_detail' ";
                $hasildet = mysql_query($querydet) or die ("error".mysql_error());

                $total += $subtotal;
            }
        }
    }

    $query = "UPDATE `hrd_karyawan` SET total_pendapatan=(SELECT SUM(a.subtotal) FROM hrd_karyawandet a RIGHT JOIN hrd_pendapatan_potongan b ON a.id_penpot=b.id_penpot WHERE a.id_karyawan='$id_karyawan' AND b.type='pendapatan' )+$total, total =(($total+(SELECT SUM(a.subtotal) FROM hrd_karyawandet a RIGHT JOIN hrd_pendapatan_potongan b ON a.id_penpot=b.id_penpot WHERE a.id_karyawan='$id_karyawan' AND b.type='pendapatan' ))-total_potongan)  WHERE id_karyawan='$id_karyawan' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    $query = "UPDATE `hrd_penggajiandet` SET subtotal=(SELECT SUM(subtotal) FROM hrd_karyawandet a 
        LEFT JOIN `hrd_pendapatan_potongan` b ON b.`id_penpot`=a.`id_penpot`
        WHERE a.id_karyawan=$id_karyawan AND b.type='pendapatan'), jml_kehadiran='$kehadiran', subtotal_variabel='$total' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' AND `status` ='pendapatan' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    $query = "UPDATE `hrd_penggajiandet` SET jml_kehadiran='$kehadiran' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}else{
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

            if($delete=='' && $id_detail==''){
                //insert
                $sql_insert="INSERT INTO `hrd_penggajiandet` (`id_penggajian`,`id_karyawan`,`subtotal`,`status`) VALUES('".$id."','".$id_karyawan."','".$subtotal."','".$status."')" ;
                // var_dump($sql_insert);
                mysql_query($sql_insert) ;
            }else if($delete=='' && $id_detail!=''){
                //update
                $sql_update="UPDATE `hrd_penggajiandet` SET `id_karyawan`='$id_karyawan' ,`subtotal`='$subtotal' WHERE id_penggajiandet = '$id_detail' ";
                // var_dump($sql_update);
                mysql_query($sql_update);
            }else if($delete!='' && $id_detail==''){
                //delete
                $sql_delete="delete from hrd_penggajiandet WHERE id_penggajiandet = '$delete' ";
                // var_dump($sql_delete);
                mysql_query($sql_delete);
            }
        }
    }

    $total = $_POST['totalhidden'];

    $query = "UPDATE `hrd_penggajian` SET total_pendapatan=(SELECT SUM(subtotal) FROM hrd_penggajiandet where status='pendapatan' and id_penggajian='$id'),total_pendapatan_variabel=(SELECT SUM(subtotal_variabel) FROM hrd_penggajiandet where status='pendapatan' and id_penggajian='$id') WHERE penggajian_id='$id' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}
?>
<script>
    window.close();
</script>