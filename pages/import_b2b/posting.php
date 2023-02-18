<!-- import excel ke mysql -->
<?php 
error_reporting(0);

// menghubungkan dengan koneksi
 include("../../include/koneksi.php");
	    $sql_post="";
		
        $data = mysql_query("select * from b2b_xls_custproduct");
		while($d = mysql_fetch_array($data)){
            $cek = mysql_query("select * from mst_b2bcustomer_product WHERE products_id='".$d['id_product']."' AND b2bcustomer_id='".$d['id_customer']."'");
            $num_rows = mysql_num_rows($cek);
            if ($num_rows == 0) {
                //insert
                $sql_post = "INSERT INTO `mst_b2bcustomer_product`(`products_id`, `nama_produk`, `b2bcustomer_id`, `price`, `disc`, `nett_price`, `qty`, `closed`) VALUES ('".$d['id_product']."','".$d['product']."','".$d['id_customer']."','".$d['price']."','".$d['disc']."','".$d['nettprice']."','1','0')";

                //update customer
                $sql_cust="";	
                $sql_cust="UPDATE `mst_b2bcustomer` SET `totalqty`=totalqty+1 WHERE `id`='".$d['id_customer']."'";	
                $hasil_cust=mysql_query($sql_cust);
            } else {
                //update
                while($d2 = mysql_fetch_array($cek)){
                    $id = $d2['b2bcustomer_detail_id'];
                    $sql_post = "UPDATE `mst_b2bcustomer_product` SET `products_id`='".$d['id_product']."',`nama_produk`='".$d['product']."',`b2bcustomer_id`='".$d['id_customer']."',`price`='".$d['price']."',`disc`='".$d['disc']."',`nett_price`='".$d['nettprice']."' WHERE `b2bcustomer_detail_id`='".$id."'";
                }
            }
            $hasil_post=mysql_query($sql_post);
        }

        //menghapus data
		$sql_delete="";	
		$sql_delete="TRUNCATE TABLE b2b_xls_custproduct";	
		$hasil_delete=mysql_query($sql_delete);

// alihkan halaman ke importcustomerproduct.php
header("location:importcustomerproduct.php");
?>