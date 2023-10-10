<?php
error_reporting(0);
require_once 'PHPExcel-1.8\Classes/PHPExcel.php';
require_once '../../include/koneksi.php';

$objPHPExcel = PHPExcel_IOFactory::load('DB B2B Kategori Produk.xlsx'); // Ganti dengan nama file Excel yang sesuai

$worksheet = $objPHPExcel->getActiveSheet();

$i = 1;
foreach ($worksheet->getRowIterator() as $row) {
    if($i>1){
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $kode = '';
        $nama = '';
        $harga = '';

        foreach ($cellIterator as $cell) {
            $columnIndex = $cell->getColumn();
            $cellValue = $cell->getFormattedValue();

            if ($columnIndex == 'B') {
                $kode = mysql_real_escape_string($cellValue);
            } elseif ($columnIndex == 'E') {
                $nama = mysql_real_escape_string($cellValue);
            } elseif ($columnIndex == 'F') {
                $harga = mysql_real_escape_string($cellValue);
            }
        }

        $query1 = "UPDATE mst_b2bproducts SET nama = '$nama', harga = '$harga', lastmodified=NOW() WHERE kode = '$kode'";
        $res1 = mysql_query($query1);

        $query2 = "UPDATE mst_b2bproductsgrp SET nama = '$nama', harga = '$harga', lastmodified=NOW() WHERE kode = '$kode'";
        $res2 = mysql_query($query2);

        $query3 = "UPDATE mst_b2bcustomer_product SET nama_produk = '$nama' WHERE products_id = (SELECT id FROM mst_b2bproductsgrp WHERE kode = '$kode' LIMIT 1)";
        $res3 = mysql_query($query3);
    }
    $i++;
}
if($res1 && $res2 && $res3){
    echo "Sukses";
}else{
    echo "Gagal";
}
?>
