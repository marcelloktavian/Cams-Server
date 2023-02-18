<?php 
error_reporting(0);
session_start();
$id_user=$_SESSION['id_user'];
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");
	$idbrg  = $_POST['idbrg'];
    $keterangan = $_POST['txtbrg'];

    //$row=$_POST['jum'];
	$row=$_GET['row'];
     	
		for ($i=1; $i<$row; $i++)
		{
		//---mengambil parameter---dari beli_detail_edit-------
		$delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];		
		$id_comp = $_POST['IDP'.$i];
		//var_dump($namabrg.'-iduser='.$id_user);die;
		$Qty = $_POST['Qty'.$i];
		// $NettPrice= str_replace(",","", $_POST['NettPrice'.$i]);
		// $Disc= str_replace(",","", $_POST['Disc'.$i]);
			
			if($id_comp=='' && $id_detail=='' && $delete==''){
			}
			else
			{
				if($delete=='' && $id_detail==''){
                //insert composition
				$sql_insert="INSERT INTO `mst_products_detail`(`products_id`, `composition_id`, `qty`, `keterangan`, `lastmodified`) VALUES ('".$idbrg."','".$id_comp."','".$Qty."','".$keterangan."',NOW())  " ;

				$hasil_insert = mysql_query($sql_insert) or die (mysql_error());
	            }
				else if($delete=='' && $id_detail!=''){
                //update composition
				$sql_update="UPDATE `mst_products_detail` SET `products_id`='".$idbrg."',`composition_id`='".$id_comp."',`qty`='".$Qty."',`keterangan`='".$keterangan."',`lastmodified`=NOW() WHERE `products_detail_id`='".$id_detail."'  ";

				$hasil_update = mysql_query($sql_update) or die (mysql_error());
	            
				}
				else if($delete!='' && $id_detail==''){
                //delete composition
				$sql_delete="DELETE FROM `mst_products_detail` WHERE `products_detail_id`='".$delete."' ";

				$hasil_delete = mysql_query($sql_delete) or die (mysql_error());
	            
				}
		    }
	    
		}
		
?>
    <script language="javascript"> 
	window.close();
	//window.opener.location.href='../../Registrasi.html';
	</script> 
