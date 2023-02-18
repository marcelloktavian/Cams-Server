<?php
include"../../include/koneksi.php";

$urutan=$_POST['urutan'];
$ex1 = explode(',',$urutan);

for($i=0;$i<count($ex1);$i++){
    if($i==0){
        if(substr($ex1[$i],0,3) == 'OLN'){
            $sql_note = "(SELECT olnso.id, olnso.id_trans, DATE_FORMAT(olnso.tgl_trans, '%d/%m/%Y') as tgl, (olnso.faktur-olnso.payment) as total, FORMAT((olnso.faktur-olnso.payment),0) as totalhidden, 'Dropshipper' as stat,id_dropshipper as idnya, mst_dropshipper.nama as namadropcust FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE olnso.id_trans='".$ex1[$i]."') ";
        }else{
            $sql_note = "(SELECT b2bdo.id, b2bdo.id_trans, DATE_FORMAT(b2bdo.tgl_trans, '%d/%m/%Y') as tgl, (b2bdo.totalfaktur-b2bdo.payment) as total,FORMAT((olnso.faktur-olnso.payment),0) as totalhidden,'Customer' as stat,id_customer as idnya, mst_b2bcustomer.nama as namadropcust FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.id_trans='".$ex1[$i]."') ";
        }
    }else{
        if(substr($ex1[$i],0,3) == 'OLN'){
            $sql_note .= " UNION ALL (SELECT olnso.id, olnso.id_trans, DATE_FORMAT(olnso.tgl_trans, '%d/%m/%Y') as tgl, (olnso.faktur-olnso.payment) as total, FORMAT((olnso.faktur-olnso.payment),0) as totalhidden, 'Dropshipper' as stat,id_dropshipper as idnya, mst_dropshipper.nama as namadropcust FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE olnso.id_trans='".$ex1[$i]."') ";
        }else{
            $sql_note .= " UNION ALL (SELECT b2bdo.id, b2bdo.id_trans, DATE_FORMAT(b2bdo.tgl_trans, '%d/%m/%Y') as tgl, (b2bdo.totalfaktur-b2bdo.payment) as total,FORMAT((olnso.faktur-olnso.payment),0) as totalhidden, 'Customer' as stat,id_customer as idnya, mst_b2bcustomer.nama as namadropcust FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.id_trans='".$ex1[$i]."') ";
        }
    }
}

// if(substr($id_trans,0,3) == 'OLN'){
//     $sql_note = "SELECT olnso.id, olnso.id_trans, DATE_FORMAT(olnso.tgl_trans, '%d/%m/%Y') as tgl, (olnso.faktur-olnso.payment) as total, 'Dropshipper' as stat,id_dropshipper as idnya, mst_dropshipper.nama as namadropcust FROM olnso LEFT JOIN mst_dropshipper ON mst_dropshipper.id=olnso.id_dropshipper WHERE olnso.id_trans='".$id_trans."' ";
// }else{
//     $sql_note = "SELECT b2bdo.id, b2bdo.id_trans, DATE_FORMAT(b2bdo.tgl_trans, '%d/%m/%Y') as tgl, (b2bdo.totalfaktur-b2bdo.payment) as total,'Customer' as stat,id_customer as idnya, mst_b2bcustomer.nama as namadropcust FROM b2bdo LEFT JOIN mst_b2bcustomer ON mst_b2bcustomer.id=b2bdo.id_customer WHERE b2bdo.id_trans='".$id_trans."' ";
// }
$sql = mysql_query($sql_note);
$rows = array();
while($r = mysql_fetch_assoc($sql)) {
    $rows[] = $r;
}

echo json_encode($rows);
?>