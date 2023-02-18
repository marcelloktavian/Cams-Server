<?php
    include "../../include/koneksi.php";
    // for define master
    error_reporting(0);
    session_start();
    $id_user=$_SESSION['user']['username'];

    $masterTanggal      = $_POST['tanggal'];
    $masterkode         = $_POST['kode'];
    $masterkode2        = $_POST['kode2'];
    $masterDebet        = $_POST['debet'];
    $masterKredit       = $_POST['kredit'];
    $keterangan         = $_POST['keterangan'];
    
    $trigger = $_GET['trans'];
    $count = $_POST['jum'];
    if($trigger == 'INSERT'){
        
        // execute for master
        $sql_master="INSERT INTO `cashreceipt`(`kode`,`kasbon2`, `tanggal`, `total_debet`, `total_kredit`, `keterangan`, `deleted`, `user`, `lastmodified`) VALUES ('$masterkode','$masterkode2','$masterTanggal','$masterDebet','$masterKredit','$keterangan','0','$id_user',NOW()) ";
	    mysql_query($sql_master) or die (mysql_error());

        //get master id terakhir
        $q = mysql_fetch_array( mysql_query('select id FROM cashreceipt order by id DESC LIMIT 1'));
	    $idparent=$q['id'];

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            
            if ($_POST["idakunparent".$i] != '') {
                $idparentakun=$_POST["idakunparent".$i];
                $iddetakun=$_POST["idakundet".$i];
                $noakun=$_POST["noakun".$i];
                $namaakun=$_POST["akun".$i];
                $uraian=$_POST["uraian".$i];
                $buktikas=$_POST["bukti".$i];
                $debet=$_POST["debet".$i];
                $kredit=$_POST['kredit'.$i];
    
                // execute for detail
                $sql_detail="INSERT INTO `cashreceipt_det`(`id_parent`, `id_akun_parent`, `id_akun_det`, `no_akun`, `nama_akun`, `uraian`,`buktikas`,`debet`, `kredit`, `lastmodified`) VALUES ('$idparent','$idparentakun','$iddetakun','$noakun','$namaakun','$uraian','$buktikas','$debet','$kredit',NOW()) ";
                mysql_query($sql_detail) or die (mysql_error());
            }
            
        }

        $sisasaldo = $masterDebet - $masterKredit;

        $sql_balance="INSERT INTO `account_balance`(`noakun`, `nama`, `jenis`,`tanggal`,`saldo`, `id_receipt` ,`deleted`, `user`, `lastmodified`) VALUES ('1.01.00.00', 'Kas','Debet','$masterTanggal','$sisasaldo','$idparent','0','$id_user',NOW()) ";
	    mysql_query($sql_balance) or die (mysql_error());
    } 
    else if($trigger == 'EDIT'){
        $masterid=$_POST['id'];

        // execute for master
        $sql_master="UPDATE `cashreceipt` SET `kode`='$masterkode',`kasbon2`='$masterkode2',`tanggal`='$masterTanggal',`total_debet`='$masterDebet',`total_kredit`='$masterKredit',`keterangan`='$keterangan',`user`='$id_user',`lastmodified`=NOW() WHERE `id`='$masterid' ";
	    mysql_query($sql_master) or die (mysql_error());

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            // if ($_POST["idakunparent".$i] != '') {
                $iddetail=$_POST["idDet".$i];
                $idparentakun=$_POST["idakunparent".$i];
                $iddetakun=$_POST["idakundet".$i];
                $noakun=$_POST["noakun".$i];
                $namaakun=$_POST["akun".$i];
                $uraian=$_POST["uraian".$i];
                $buktikas=$_POST["bukti".$i];
                $debet=$_POST["debet".$i];
                $kredit=$_POST['kredit'.$i];
                $delete=$_POST["delete".$i];

                // execute for detail
                if ($iddetail == '' && $idparentakun == '' && $delete == ''){
                    // tidak terjadi apa apa
                    // nothing happens
                }else{
                    if($delete == '' && $iddetail== ''){
                        // insert ke detail
                        $sql_detail="INSERT INTO `cashreceipt_det`(`id_parent`, `id_akun_parent`, `id_akun_det`, `no_akun`, `nama_akun`, `uraian`,`buktikas`,`debet`, `kredit`, `lastmodified`) VALUES ('$masterid','$idparentakun','$iddetakun','$noakun','$namaakun','$uraian','$buktikas','$debet','$kredit',NOW()) ";
                        // var_dump($sql_detail.'   s');
                        mysql_query($sql_detail) or die (mysql_error());
                    } else if($delete == '' && $iddetail != ''){
                        // update detail
                        $detailQuery="UPDATE `cashreceipt_det` SET `id_akun_parent`='$idparentakun',`id_akun_det`='$iddetakun',`no_akun`='$noakun',`nama_akun`='$namaakun',`uraian`='$uraian',`buktikas`='$buktikas',`debet`='$debet',`kredit`='$kredit',`lastmodified`=NOW() WHERE `id`='$iddetail' ";
                        // var_dump($detailQuery.'   a');
                        mysql_query($detailQuery) or die (mysql_error());

                    } else if($delete != '' && $iddetail == ''){
                        // delete 
                        $detailQuery="DELETE FROM cashreceipt_det WHERE id='$delete' ";
                        
                        mysql_query($detailQuery) or die (mysql_error());
                    }
                }
            // }
            
        }

        $sisasaldo = $masterDebet - $masterKredit;

        $sql_balance="UPDATE `account_balance` SET `tanggal`='$masterTanggal',`saldo`='$sisasaldo',`user`='$id_user',`lastmodified`=NOW() WHERE `id_receipt`='$masterid' ";
	    mysql_query($sql_balance) or die (mysql_error());
    }
    else{
        echo "alert('Gagal Simpan')";die;
    }

?>
<script>
window.close();
</script>