<?php 
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$masterTanggal     = $_POST['tglcheck'];
$masterNote        = $_POST['note'];
// $masterDropCust    = $_POST['id_dropcust'];
// $masterStatDropCust= $_POST['status_dropcust'];
$masterTotalCSV    = $_POST['totalcsvhidden'];
$masterTotalOLN    = $_POST['totalolnhidden'];

$trigger = $_GET['trans'];
$count1 = $_POST['jum1'];
$count2 = $_POST['jum2'];

function getmonthyeardate()
	{
		$today = date('ym');
		return $today;
	}
   
	function getincrementnumber2()
	{
	$q = mysql_fetch_array( mysql_query('select id_check from trpaymentcheck order by id_check desc limit 0,1'));
	
	$kode=substr($q['id_check'], -5);
	$bulan=substr($q['id_check'], -7,2);
	$bln_skrng=date('m');
	$num=(int)$kode;
	//echo"Kode=".$kode."Num=".$num."bulan=".$bulan;
	
	if($num==0 || $num==null || $bulan!=$bln_skrng)		
	{
		$temp = 1;
	}
	else
	{
		$temp=$num+1;
	}
	return $temp;
}

	function getmonthyeardate2()
	{
	$today = date('ym');
	return $today;
	}

	function getnewnotrxwait2()
	{
	
	$temp=getmonthyeardate2();
	$temp2=getincrementnumber2();
	$id="CHK".$temp."".str_pad($temp2, 5, 0, STR_PAD_LEFT);	
	return $id;
	}	
    
if($trigger == 'INSERT'){
    //add
    $idnew = getnewnotrxwait2();
     // execute for master
     $sql_master="INSERT INTO `trpaymentcheck`(`id_check`, `check_date`,`total_csv`,`total_oln`, `note`, `user`, `lastmodified`) VALUES ('$idnew','$masterTanggal','$masterTotalCSV','$masterTotalOLN','$masterNote','$user',NOW()) ";
     mysql_query($sql_master) or die (mysql_error());

     //get master id terakhir
     $q = mysql_fetch_array( mysql_query('select id FROM trpaymentcheck order by id DESC LIMIT 1'));
     $idparent=$q['id'];

    // looping detail csv
    if($count1 > $count2){
        for ($i=1; $i<$count1; $i++){
            $id_import=$_POST["IdCSV".$i];
            $payment_value=$_POST["ValuehiddenCSV".$i];

            // execute for detail
            if($id_import != ''){
                $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','0','','$id_import','$payment_value','','0','0','$user',NOW()) ";
                mysql_query($sql_detail) or die (mysql_error());
            }   
        }
        for ($j=1; $j<$count2; $j++){
            $id_olnb2b = $_POST["OLN".$j];
            $iddopcust = $_POST["IdDropcustOLN".$j];

            if(substr($id_olnb2b,0,3) == 'OLN'){
                $status = 'Dropshipper';
               }else{
                $status = 'Customer';
            }   

            $subtotal = $_POST['ValueOLN'.$j];

            // execute for detail
            if($id_olnb2b != ''){
                if($id_olnb2b == 'KOREKSI'){
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','0','$subtotal','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else{
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','$subtotal','0','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }
            }
        }
    }else{
        for ($j=1; $j<$count2; $j++){
            $id_olnb2b = $_POST["OLN".$j];
            $iddopcust = $_POST["IdDropcustOLN".$j];

            if(substr($id_olnb2b,0,3) == 'OLN'){
                $status = 'Dropshipper';
               }else{
                $status = 'Customer';
            }   

            $subtotal = $_POST['ValueOLN'.$j];
            var_dump($id_olnb2b);
            // execute for detail
            if($id_olnb2b == 'KOREKSI'){
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','0','$subtotal','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else{
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','$subtotal','0','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }
        }
        for ($i=1; $i<$count1; $i++){
            $id_import=$_POST["IdCSV".$i];
            $payment_value=$_POST["ValuehiddenCSV".$i];

            // execute for detail
            if($id_import != ''){
                $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','0','','$id_import','$payment_value','','0','0','$user',NOW()) ";
                mysql_query($sql_detail) or die (mysql_error());
            }   
        }
    }
    // die;
    // if($count1 > $count2){
    //     for ($i=1; $i<$count1; $i++)
    //     {
    //         // define for detail
    //         $id_import=$_POST["IdCSV".$i];
    //         $payment_value=$_POST["ValuehiddenCSV".$i];

    //         $status='';
    //         $id_olnb2b='';
    //         $iddopcust='';
    //         if($_POST["OLN".$i] != '' && $_POST["OLN".$i] != null){
    //             $id_olnb2b = $_POST["OLN".$i];
    //             $iddopcust = $_POST["IdDropcustOLN".$i];

    //             if(substr($id_olnb2b,0,3) == 'OLN'){
    //                 $status = 'Dropshipper';
    //                }else{
    //                 $status = 'Customer';
    //             }   
    //         }

    //         $subtotal=0;
    //         if($_POST['ValueOLN'.$i] != '' && $_POST['ValueOLN'.$i] != null){
    //             $subtotal = $_POST['ValueOLN'.$i];
    //         }

    //         // var_dump($id_import.'>'.$id_olnb2b);

    //         // execute for detail
    //         if($id_olnb2b != '' || $id_import != ''){
    //             $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','$id_import','$payment_value','$id_olnb2b','$subtotal','$user',NOW()) ";
    //             mysql_query($sql_detail) or die (mysql_error());
    //         }
    //     }
    // }else{
    //     for ($i=1; $i<$count2; $i++)
    //     {
    //         // define for detail
    //         $id_import='';
    //         if($_POST["IdCSV".$i] != '' && $_POST["IdCSV".$i] != null){
    //             $id_import = $_POST["IdCSV".$i];
    //         }

    //         $payment_value=0;
    //         if($_POST["ValuehiddenCSV".$i] != '' && $_POST["ValuehiddenCSV".$i] != null){
    //             $payment_value = $_POST["ValuehiddenCSV".$i];
    //         }

    //         $status='';
    //         $id_olnb2b=$_POST["OLN".$i];
    //         $iddopcust=$_POST["IdDropcustOLN".$i];
    //         if(substr($id_olnb2b,0,3) == 'OLN'){
    //             $status = 'Dropshipper';
    //         }else{
    //             $status = 'Customer';
    //         }   

    //         $subtotal=$_POST['ValueOLN'.$i];

    //         // var_dump($id_import.'>'.$id_olnb2b);

    //         // execute for detail
    //         if($id_olnb2b != '' || $id_import != ''){
    //             $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','$id_import','$payment_value','$id_olnb2b','$subtotal','$user',NOW()) ";
    //             mysql_query($sql_detail) or die (mysql_error());
    //         }
    //     }
    // }
}else{
    //edit
    $idparent     = $_POST['idmst'];
     // execute for master
     $sql_master="UPDATE `trpaymentcheck` SET `check_date`='$masterTanggal',`total_csv`='$masterTotalCSV',`total_oln`='$masterTotalOLN',`note`='$masterNote',`user`='$user' WHERE `id`='$idparent' ";
     mysql_query($sql_master) or die (mysql_error());

        // looping detail csv
        if($count1 > $count2){
            for ($i=1; $i<$count1; $i++){
                $deletecsv='';
                if(isset($_POST["deleteCSV1".$i])){
                    $deletecsv=$_POST["deleteCSV1".$i];
                }

                $iddetcsv=$_POST["IdDetCSV".$i];
                $id_import=$_POST["IdCSV".$i];
                $payment_value=$_POST["ValuehiddenCSV".$i];
    
                // execute for detail
                if($id_import != '' && $iddetcsv == ''){
                    //insert
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','0','','$id_import','$payment_value','','0','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($id_import != '' && $iddetcsv != ''){
                    //update
                    $sql_detail="UPDATE `trpaymentcheck_detail` SET `id_import`='$id_import',`payment_value`='$payment_value',`user`='$user',`lastmodified`=NOW() WHERE `id_detail`='$iddetcsv' ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($deletecsv != ''){
                    //delete
                    $sql_detail="DELETE FROM trpaymentcheck_detail WHERE id_detail = '$deletecsv' ";
                    mysql_query($sql_detail) or die (mysql_error());
                }
            }
            for ($j=1; $j<$count2; $j++){
                $deleteoln='';
                if(isset($_POST["deleteOLN1".$j])){
                    $deleteoln=$_POST["deleteOLN1".$j];
                }

                $iddetoln = $_POST["IdDetOLN".$j];
                $id_olnb2b = $_POST["OLN".$j];
                $iddopcust = $_POST["IdDropcustOLN".$j];
    
                if(substr($id_olnb2b,0,3) == 'OLN'){
                    $status = 'Dropshipper';
                }else{
                    $status = 'Customer';
                }   
        
                $subtotal = $_POST['ValueOLN'.$j];
    
                // execute for detail
                if($id_olnb2b != '' && $iddetoln == ''){
                    //insert
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','$subtotal','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($id_olnb2b != '' && $iddetoln != ''){
                    //update
                    $sql_detail="UPDATE `trpaymentcheck_detail` SET `id_dropcust`='$iddopcust',`stat_dropcust`='$status',`id_olnb2b`='$id_olnb2b',`subtotal`='$subtotal',`user`='$user',`lastmodified`=NOW() WHERE `id_detail`='$iddetoln'  ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($deleteoln != ''){
                    //delete
                    $sql_detail="DELETE FROM trpaymentcheck_detail WHERE id_detail = '$deleteoln' ";
                    mysql_query($sql_detail) or die (mysql_error());
                   
                }
            }
        }else{
            for ($j=1; $j<$count2; $j++){
                $deleteoln='';
                if(isset($_POST["deleteOLN1".$j])){
                    $deleteoln=$_POST["deleteOLN1".$j];
                }

                $iddetoln = $_POST["IdDetOLN".$j];
                $id_olnb2b = $_POST["OLN".$j];
                $iddopcust = $_POST["IdDropcustOLN".$j];
    
                if(substr($id_olnb2b,0,3) == 'OLN'){
                    $status = 'Dropshipper';
                }else{
                    $status = 'Customer';
                }   
        
                $subtotal = $_POST['ValueOLN'.$j];
    
                // execute for detail
                if($id_olnb2b != '' && $iddetoln == ''){
                    //insert
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','$iddopcust','$status','0','0','$id_olnb2b','$subtotal','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($id_olnb2b != '' && $iddetoln != ''){
                    //update
                    $sql_detail="UPDATE `trpaymentcheck_detail` SET `id_dropcust`='$iddopcust',`stat_dropcust`='$status',`id_olnb2b`='$id_olnb2b',`subtotal`='$subtotal',`user`='$user',`lastmodified`=NOW() WHERE `id_detail`='$iddetoln'  ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($deleteoln != ''){
                    //delete
                    $sql_detail="DELETE FROM trpaymentcheck_detail WHERE id_detail = '$deleteoln' ";
                    mysql_query($sql_detail) or die (mysql_error());
                   
                }
            }
            for ($i=1; $i<$count1; $i++){
                $deletecsv='';
                if(isset($_POST["deleteCSV1".$i])){
                    $deletecsv=$_POST["deleteCSV1".$i];
                }

                $iddetcsv=$_POST["IdDetCSV".$i];
                $id_import=$_POST["IdCSV".$i];
                $payment_value=$_POST["ValuehiddenCSV".$i];
    
                // execute for detail
                if($id_import != '' && $iddetcsv == ''){
                    //insert
                    $sql_detail="INSERT INTO trpaymentcheck_detail VALUES(NULL,'$idparent','0','','$id_import','$payment_value','','0','$user',NOW()) ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($id_import != '' && $iddetcsv != ''){
                    //update
                    $sql_detail="UPDATE `trpaymentcheck_detail` SET `id_import`='$id_import',`payment_value`='$payment_value',`user`='$user',`lastmodified`=NOW() WHERE `id_detail`='$iddetcsv' ";
                    mysql_query($sql_detail) or die (mysql_error());
                }else if($deletecsv != ''){
                    //delete
                    $sql_detail="DELETE FROM trpaymentcheck_detail WHERE id_detail = '$deletecsv' ";
                    mysql_query($sql_detail) or die (mysql_error());
                }
            }
        }


}

?>
<script language="javascript"> 
	window.close();
</script> 