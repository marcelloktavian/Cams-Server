<?php
    require "../../include/koneksi.php";
    $action = $_GET['action'];
    $idolncust = $_GET['idolncust'];
    $nama = $_GET['nama'];
    $telp= $_GET['telp'];
    $inputnama = $nama."(".$telp.")";
    $id = $_GET['id'];
    // var_dump($action,$idolncust,$nama,$telp);

    $query = "INSERT INTO mst_dropshipper SET oln_customer_id='$idolncust',nama='$inputnama',lastmodified='now()',deleted=0,disc='0.2',alamat='0',no_telp='0',hp='0',email='0',type='0',note='0',user='0',tgl_lahir='0' ";
	// var_dump($query);die;
    $hasil = mysql_query($query) or die (mysql_error());

    $query2= "UPDATE olnpreso olp SET olp.id_dropshipper = (SELECT id FROM mst_dropshipper d WHERE (d.oln_customer_id=olp.oln_customerid)) where olp.oln_order_id=$id";
    $hasil2 = mysql_query($query2) or die (mysql_error());

    

    header("location:olnpresocr_detail_edit.php?ids=".$id."");

?>
<script>
    window.close();
</script>


