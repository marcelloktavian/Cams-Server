<?php
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$action = $_GET['action'];
$row = $_GET['row'];

$namapph21          = $_POST['namapph21'];
$keterangan         = $_POST['keterangan'];

if($action == 'add'){
    //save master
    $query = "INSERT INTO `hrd_mstpph21` (`nama`,`note`,`user`,`lastmodified`)
    VALUES('$namapph21','$keterangan','$user',NOW());";
    // var_dump($query);die;
	$hasil = mysql_query($query) or die ("error".mysql_error());

    $sqlget = "SELECT id FROM hrd_mstpph21 ORDER BY id DESC LIMIT 1";
    $sq = mysql_query($sqlget);
    $rs = mysql_fetch_array($sq);
 
    $lastid = $rs['id'];

    for ($i=1; $i<=$row; $i++)
	{
        if(!isset($_POST['Id'.$i])){
		}
		else
		{
            if($_POST['Id'.$i] !== ''){
                $iddetpph21 = $_POST['Id'.$i];

                $pengali = $_POST['Pengali'.$i];
                $value = $_POST['Value'.$i];
              
                //save detail
                $querydet = "INSERT INTO `hrd_detpph21` (`id_parent`,`id_pph21`,`pengali`,`value`)
                  VALUES('$lastid','$iddetpph21','$pengali','$value');";
                $hasildet = mysql_query($querydet) or die ("error".mysql_error());
            }
           
        }
    }
}else{
    $id_pph21 = $_POST['id_pph21'];
    //save master
    $query = "UPDATE `hrd_mstpph21` SET `nama`='$namapph21',`note`='$keterangan',`user`='$user',`lastmodified`=NOW() WHERE id='$id_pph21' ";
	$hasil = mysql_query($query) or die ("error".mysql_error());

    for ($i=1; $i<=$row; $i++)
	{
        $delete = $_POST['delete1'.$i];
		$id_detail = $_POST['IdDetail'.$i];
        $iddetpph21 = $_POST['Id'.$i];

        if($iddetpph21=='' && $id_detail=='' && $delete==''){
        }
        else
        {
            $pengali = $_POST['Pengali'.$i];
            $value = $_POST['Value'.$i];

            if($delete=='' && $id_detail==''){
                //insert
				$sql_insert="INSERT INTO `hrd_detpph21` (`id_parent`,`id_pph21`,`pengali`,`value`)
                VALUES('$id_pph21','$iddetpph21','$pengali','$value');" ;
                // var_dump($sql_insert);die;
				mysql_query($sql_insert) ;
			}else if($delete=='' && $id_detail!=''){
                //update
				$sql_update="UPDATE `hrd_detpph21`  SET `id_pph21` = '$iddetpph21', `pengali` = '$pengali',`value` = '$value' WHERE `id` = '$id_detail';";
                // var_dump($sql_update);
		    	mysql_query($sql_update);
			}else if($delete!='' && $id_detail==''){
                //delete
				$sql_delete="delete from hrd_detpph21 where id ='".$delete."'";
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