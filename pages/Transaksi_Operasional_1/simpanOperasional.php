<?php
    include "../../include/koneksi.php";
    // for define master
    error_reporting(0);
    session_start();
    $id_user=$_SESSION['user']['username'];

    $masterTanggal=$_POST['tanggal'];
    $masterSubtotal=$_POST['subtotal'];
    $masterPPN=$_POST['PPN'];
    $masterGrandtotal=$_POST['grandtotal'];
    

    // function getincrementnumber2()
	// {
    //     global $db;
    //     $q = mysqli_fetch_array( mysqli_query($db,'select id_bo from biayaoperasional order by id_bo desc limit 0,1'));
    //     $kode=substr($q['id_bo'], -4);
    //     $bulan=substr($q['id_bo'], -7,2);
    //     $bln_skrng=date('m');
    //     $num=(int)$kode;
        
    //     if($num==0 || $num==null || $bulan!=$bln_skrng)		
    //     {
    //         $temp = 1;
    //     }
    //     else
    //     {
    //         $temp=$num+1;
    //     }
    //     return $temp;
    // }

	// function getmonthyeardate2()
	// {
	// $today = date('ym');
	// return $today;
	// }

// 	function getnewnotrxwait2()
// 	{
// 	$temp=getmonthyeardate2();
// 	$temp2=getincrementnumber2();
// 	$id="BO"."/".$temp."/".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
// 	return $id;
//     }

//    $idbo =  getnewnotrxwait2();
//    echo $idbo;

    $trigger = $_GET['trans'];
    $count = $_POST['jum'];
    if($trigger == 'INSERT'){
        
        // execute for master
        $sql_master="INSERT INTO `biayaoperasional`(`tanggal`, `subtotal`, `ppn`, `total`, `deleted`, `user`, `lastmodified`) VALUES ('$masterTanggal','$masterSubtotal','$masterPPN','$masterGrandtotal','0','$id_user',NOW()) ";
	    mysql_query($sql_master) or die (mysql_error());

        //get master id terakhir
        $q = mysql_fetch_array( mysql_query('select id FROM biayaoperasional order by id DESC LIMIT 1'));
	    $idparent=$q['id'];

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            $idbiaya=$_POST["idbiaya".$i];
            $namabiaya=$_POST["namabiaya".$i];
            $qty=$_POST["Qty".$i];
            $packing=$_POST['packing'.$i];
            $harga_satuan=$_POST['hargaSatuan'.$i];
            $jumlah=$_POST["Subtotal".$i];
            // execute for detail
            
            $sql_detail="INSERT INTO biayaoperasional_det VALUES(NULL,'$idparent','$idbiaya','$namabiaya','$packing','$qty','$harga_satuan','$jumlah') ";
	        mysql_query($sql_detail) or die (mysql_error());
        }
    } 
    else if($trigger == 'EDIT'){
        $masterid=$_POST['id'];

        // execute for master
        $sql_master="UPDATE biayaoperasional SET tanggal='$masterTanggal',subtotal=$masterSubtotal,ppn=$masterPPN,total=$masterGrandtotal, user='$id_user', lastmodified=NOW() WHERE id='$masterid' ";
	    mysql_query($sql_master) or die (mysql_error());

        // looping detail
        for ($i=1; $i<$count; $i++)
	    {
            // define for detail
            $iddetail=$_POST["idDet".$i];
            $idbiaya=$_POST["idbiaya".$i];
            $namabiaya=$_POST["namabiaya".$i];
            $qty=$_POST["Qty".$i];
            $packing=$_POST['packing'.$i];
            $harga_satuan=$_POST['hargaSatuan'.$i];
            $jumlah=$_POST["Subtotal".$i];
            $delete=$_POST["delete".$i];
            // execute for detail
            if ($iddetail == '' && $idbiaya == '' && $delete == ''){
                // tidak terjadi apa apa
                // nothing happens
            }else{
                if($delete == '' && $iddetail== ''){
                    // insert ke detail
                    $detailQuery="INSERT INTO biayaoperasional_det VALUES(NULL,'$masterid','$idbiaya','$namabiaya','$packing','$qty','$harga_satuan','$jumlah') ";
                    mysql_query($detailQuery) or die (mysql_error());
                } else if($delete == '' && $iddetail != ''){
                    // update detail
                    $detailQuery="UPDATE biayaoperasional_det SET id_biaya='$idbiaya',namabiaya='$namabiaya',satuan='$packing',qty='$qty',harga_satuan='$harga_satuan',jumlah='$jumlah' WHERE id='$iddetail' ";
                    mysql_query($detailQuery) or die (mysql_error());

                } else if($delete != '' && $iddetail == ''){
                    // delete 
                    $detailQuery="DELETE FROM biayaoperasional_det WHERE id='$delete' ";
                    mysql_query($detailQuery) or die (mysql_error());
                }
            }

        }
    }
    else{
        echo "alert('Gagal Simpan')";die;
    }

?>
<script>
window.close();
</script>