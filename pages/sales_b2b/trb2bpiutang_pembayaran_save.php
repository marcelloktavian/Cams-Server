<?php
    include "../../include/koneksi.php";
    // for define master
    error_reporting(0);
    session_start();
    $id_user=$_SESSION['user']['username'];

    $action             = $_GET['action'];
    $count              = $_POST['jum'];

    $masterTanggal      = $_POST['tanggal'];
    $masterKeterangan   = $_POST['txtbrg'];
    $masterTotalKredit  = $_POST['total_kredit'];
    $masterTotalDebet   = $_POST['total_debet'];

    if($action == 'add'){
        //insert
        $masterNo = '';
        $query = mysql_query("SELECT CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), IF(SUBSTR(no_jurnal, 1,2) <> SUBSTR(YEAR(NOW()),3) OR SUBSTR(no_jurnal, 3,2) <> IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())) OR SUBSTR(no_jurnal, 5,2) <> IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001', IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=1, CONCAT('0000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=2, CONCAT('000',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=3, CONCAT('00',((SUBSTR(no_jurnal, 7,5))+1)), IF(LENGTH(((SUBSTR(no_jurnal, 7,5))+1))=4, CONCAT('0',((SUBSTR(no_jurnal, 7,5))+1)),((SUBSTR(no_jurnal, 7,5))+1) ) ) )))) AS nomor
        FROM jurnal ORDER BY id DESC LIMIT 1");
        if(mysql_num_rows($query) == '1'){
        }else{
            $query = mysql_query("select CONCAT(SUBSTR(YEAR(NOW()),3), IF(LENGTH(MONTH(NOW()))=1, CONCAT('0',MONTH(NOW())),MONTH(NOW())), IF(LENGTH(DAY(NOW()))=1, CONCAT('0',DAY(NOW())),DAY(NOW())), '00001') as nomor ");
        }

        $q = mysql_fetch_array($query);
            $masterNo=$q['nomor'];


        // execute for master
        $sql_master="INSERT INTO `jurnal`(`no_jurnal`,`tgl`,`keterangan`, `total_debet`, `total_kredit`, `deleted`, `user`, `lastmodified`,`status`) VALUES ('$masterNo','$masterTanggal','$masterKeterangan','$masterTotalDebet','$masterTotalKredit','0','$id_user',NOW(),'B2B PAY') ";
        mysql_query($sql_master) or die (mysql_error());

        //get master id terakhir
        $q = mysql_fetch_array( mysql_query('select id FROM jurnal order by id DESC LIMIT 1'));
        $idparent=$q['id'];

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            $idakun=$_POST["idakun".$i];
            $status=$_POST["status".$i];
            $noakun=$_POST["noakun".$i];
            $namaakun=$_POST["namaakun".$i];
            $debet=$_POST["debet".$i];
            $kredit=$_POST['kredit'.$i];
            $keterangan=$_POST['keterangan'.$i];

            if($idakun==''){
            }
            else
            {
                // execute for detail
                $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$idparent','$idakun','$noakun','$namaakun','$status','$debet','$kredit','$keterangan','0', '$id_user',NOW()) ";
                mysql_query($sql_detail) or die (mysql_error());
            }
        }
    }else{
        $id = $_POST['idparent'];
        // execute for master
        $sql_master="UPDATE jurnal SET tgl='$masterTanggal', keterangan='$masterKeterangan', total_debet='$masterTotalDebet', total_kredit='$masterTotalKredit', `user`='$id_user', lastmodified=NOW(), state_edit=0 WHERE id='$id' ";
        mysql_query($sql_master) or die (mysql_error());

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            $iddetail=$_POST["iddetail".$i];
            $idakun=$_POST["idakun".$i];
            $status=$_POST["status".$i];
            $noakun=$_POST["noakun".$i];
            $namaakun=$_POST["namaakun".$i];
            $debet=$_POST["debet".$i];
            $kredit=$_POST['kredit'.$i];
            $keterangan=$_POST['keterangan'.$i];
            $delete=$_POST["delete".$i];
            // execute for detail
            if ($iddetail == '' && $idakun == '' && $delete == ''){
                // tidak terjadi apa apa
                // nothing happens
            }else{
                if($delete == '' && $iddetail== ''){
                    // insert ke detail
                    $sql_detail="INSERT INTO jurnal_detail VALUES(NULL,'$id','$idakun','$noakun','$namaakun','$status','$debet','$kredit','$keterangan','0', '$id_user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                } else if($delete == '' && $iddetail != ''){
                    // update detail
                    $detailQuery="UPDATE jurnal_detail SET id_akun='$idakun', no_akun='$noakun', nama_akun='$namaakun', `status`='$status', debet='$debet', kredit='$kredit', keterangan='$keterangan', `user`='$id_user', lastmodified=NOW() WHERE id = '$iddetail'";
                    mysql_query($detailQuery) or die (mysql_error());

                } else if($delete != '' && $iddetail == ''){
                    // delete 
                    $detailQuery="DELETE FROM jurnal_detail WHERE id='$delete' ";
                    mysql_query($detailQuery) or die (mysql_error());
                }
            }

        }
    }
?>

<script>
window.close();
</script>