<?php
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$action = $_GET['action'];
$row = $_GET['row'];

$nomor_karyawan     = $_POST['nomor_karyawan'];
$nama_karyawan      = $_POST['nama_karyawan'];
$nik                = $_POST['nik'];
$npwp               = $_POST['npwp'];
$no_bpjs            = $_POST['no_bpjs'];
$no_jkn_kis         = $_POST['no_jkn_kis'];
$email              = $_POST['email'];
$ptkp               = $_POST['ptkp'];
$notelp             = $_POST['notelp'];
$periodepenggajian  = $_POST['periodepenggajian'];
$tipekaryawan       = $_POST['tipekaryawan'];
$jabatan            = $_POST['jabatan'];
$alamat             = $_POST['alamat'];
$rekening           = $_POST['rekening'];
$upah_tetap         = $_POST['upah_tetap'];
$upah_bpjs          = $_POST['upah_bpjs'];
$upah_bpjs_tk       = $_POST['upah_bpjs_tk'];
$tambahan_bpjs      = $_POST['tambahan_bpjs'];
$total              = $_POST['totalhidden'];
$totalpendapatan    = $_POST['totalpendapatanhidden'];
$totalpotongan      = $_POST['totalpotonganhidden'];

if($action == 'add'){
    //save master
    $query = "INSERT INTO `hrd_karyawan`(`no_karyawan`, `id_jabatan`, `nama_karyawan`, `nik`, `npwp`, `jkn_kis`, `bpjs_tk`, `no_telp`, `periode`, `tipe`, `ptkp`, `email`, `alamat`,`rekening`, `upah_tetap`, `up_bpjs`, `up_bpjs_tk`, `tanggungan_tambahan`, `total_pendapatan`, `total_potongan`, `total`, `user`, `lastmodified`) VALUES ('$nomor_karyawan','$jabatan','$nama_karyawan','$nik','$npwp','$no_jkn_kis','$no_jkn_kis','$notelp','$periodepenggajian','$tipekaryawan','$ptkp','$email','$alamat','$rekening','$upah_tetap','$upah_bpjs','$upah_bpjs_tk','$tambahan_bpjs','$totalpendapatan','$totalpotongan','$total','$user',NOW())";
    // var_dump($query);die;
	$hasil = mysql_query($query) or die ("error".mysql_error());

    $sqlget = "SELECT id_karyawan FROM hrd_karyawan ORDER BY id_karyawan DESC LIMIT 1";
    $sq = mysql_query($sqlget);
    $rs = mysql_fetch_array($sq);
 
    $lastid = $rs['id_karyawan'];

    for ($i=1; $i<=$row; $i++)
	{
        if(!isset($_POST['Id'.$i])){
		}
		else
		{
            if($_POST['Id'.$i] !== ''){
                $id_penpot = $_POST['Id'.$i];

                $persen = $_POST['Persen'.$i];
                $value = $_POST['Value'.$i];
                // $dikali_per_hadir = 0;
                // if(isset($_POST['HariKehadiran'.$i]) && $_POST['HariKehadiran'.$i]==true){
                //     $dikali_per_hadir = 1;
                // }
                // $persen_hadir = 0;
                // if(isset($_POST['PersenKehadiran'.$i]) && $_POST['PersenKehadiran'.$i]==true){
                //     $persen_hadir = 1;
                // }
                // $objek_pajak = 0;
                // if(isset($_POST['ObjekPajak'.$i]) && $_POST['ObjekPajak'.$i]==true){
                //     $objek_pajak = 1;
                // }
                $Subtotal = $_POST['Subtotal'.$i];
    
                $querydet = "INSERT INTO `hrd_karyawandet`(`id_karyawan`, `id_penpot`, `persen`, `value`, `subtotal`) VALUES ('$lastid','$id_penpot','$persen','$value','$Subtotal')";
                $hasildet = mysql_query($querydet) or die ("error".mysql_error());
            }
           
        }
    }
}else{
    $id_karyawan = $_POST['id_karyawan'];
    //save master
    $query = "UPDATE `hrd_karyawan` SET `no_karyawan`='$nomor_karyawan', `id_jabatan`='$jabatan', `nama_karyawan`='$nama_karyawan', `nik`='$nik', `npwp`='$npwp', `jkn_kis`='$no_jkn_kis', `bpjs_tk`='$no_jkn_kis', `no_telp`='$notelp', `periode`='$periodepenggajian', `tipe`='$tipekaryawan', `id_ptkp`='$ptkp' ,`email`='$email', `alamat`='$alamat', `rekening`='$rekening', `upah_tetap`='$upah_tetap', `up_bpjs`='$upah_bpjs', `up_bpjs_tk`='$upah_bpjs_tk', `tanggungan_tambahan`='$tambahan_bpjs', `total_pendapatan`='$totalpendapatan', `total_potongan`='$totalpotongan', `total`='$total', `user`='$user', `lastmodified`=NOW() WHERE id_karyawan='$id_karyawan' ";
	$hasil = mysql_query($query) or die ("error".mysql_error());

    for ($i=1; $i<=$row; $i++)
	{
        $delete = $_POST['delete1'.$i];
		$id_detail = $_POST['IdDetail'.$i];
        $id_penpot = $_POST['Id'.$i];

        if($id_penpot=='' && $id_detail=='' && $delete==''){
        }
        else
        {
            $persen = $_POST['Persen'.$i];
            $value = $_POST['Value'.$i];
            // $dikali_per_hadir = 0;
            // if(isset($_POST['HariKehadiran'.$i]) && $_POST['HariKehadiran'.$i]==true){
            //     $dikali_per_hadir = 1;
            // }
            // $persen_hadir = 0;
            // if(isset($_POST['PersenKehadiran'.$i]) && $_POST['PersenKehadiran'.$i]==true){
            //     $persen_hadir = 1;
            // }
            // $objek_pajak = 0;
            // if(isset($_POST['ObjekPajak'.$i]) && $_POST['ObjekPajak'.$i]==true){
            //     $objek_pajak = 1;
            // }
            $Subtotal = $_POST['Subtotal'.$i];

            if($delete=='' && $id_detail==''){
                //insert
				$sql_insert="INSERT INTO `hrd_karyawandet`(`id_karyawan`, `id_penpot`, `persen`, `value`, `subtotal`) VALUES ('$id_karyawan','$id_penpot','$persen','$value','$Subtotal')" ;
                // var_dump($sql_insert);
				mysql_query($sql_insert) ;
			}else if($delete=='' && $id_detail!=''){
                //update
				$sql_update="UPDATE `hrd_karyawandet` SET `id_penpot`='$id_penpot', `persen`='$persen', `value`='$value', `subtotal`='$Subtotal' WHERE id_det_karyawan='$id_detail' ";
                // var_dump($sql_update);
		    	mysql_query($sql_update);
			}else if($delete!='' && $id_detail==''){
                //delete
				$sql_delete="delete from hrd_karyawandet where id_det_karyawan ='".$delete."'";
                // var_dump($sql_delete);
				mysql_query($sql_delete);
			}
        }

    }
}
?>

<script>
    window.close();
</script>