<?php
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$action = $_GET['action'];

$kode                = $_POST['kode_jabatan'];
$nama   		     = $_POST['nama_jabatan'];
$dept                = $_POST['id_dept'];
$melapor             = $_POST['melapor_ke'];
$lokasi_kerja        = $_POST['lokasi_kerja'];
$ringkasan           = $_POST['ringkasan'];
$kualifikasi         = $_POST['kualifikasi'];
$tanggungjawab       = $_POST['tanggung_jawab'];
$kondisi             = $_POST['kondisi_pekerjaan'];

if($action == 'add'){
    $query = "INSERT INTO hrd_jabatan(`kode_jabatan`, `nama_jabatan`, `id_dept`, `ringkasan`, `lokasi_kerja`, `melapor_ke`, `kualifikasi`, `tanggung_jawab`, `kondisi_pekerjaan`,`user`,`lastmodified`) VALUES('$kode', '$nama', '$dept', '$ringkasan', '$lokasi_kerja', '$melapor', '$kualifikasi', '$tanggungjawab', '$kondisi', '$user', NOW())";
	$hasil = mysql_query($query) or die ("error".mysql_error());
}else{
    $id=$_POST['id_jabatan'];
    $query = "UPDATE hrd_jabatan SET kode_jabatan='$kode', nama_jabatan='$nama', id_dept='$dept', ringkasan='$ringkasan', lokasi_kerja='$lokasi_kerja', melapor_ke='$melapor', kualifikasi='$kualifikasi', tanggung_jawab='$tanggungjawab', kondisi_pekerjaan='$kondisi', `user`='$user', lastmodified = NOW() WHERE id_jabatan='$id'";
	$hasil = mysql_query($query) or die ("error".mysql_error());
}

?>
<script>
    window.close();
</script>