<?php
require_once "../../assets/phpexcel/PHPExcel.php";
include("../../include/koneksi.php");
$id=$_GET['id'];
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Acc. No.')
            ->setCellValue('B1', 'Trans. Amount')
            ->setCellValue('C1', 'emp.Number')
            ->setCellValue('D1', 'emp.Name')
            ->setCellValue('E1', 'Dept')
            ->setCellValue('F1', 'Trans. Date');
$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->setTitle("payroll");

$stylecenter = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$styleright = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    )
);

$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->applyFromArray($stylecenter);
$objPHPExcel->getActiveSheet()->getStyle("D1")->applyFromArray($stylecenter);
$objPHPExcel->getActiveSheet()->getStyle("F1")->applyFromArray($stylecenter);

$no = 2;

$sqldetail = "SELECT b.no_karyawan, b.rekening, a.`id_penggajiandet`, a.`id_karyawan`, b.`nama_karyawan`, d.`nama_dept`, b.no_telp, a.wa, DATE_FORMAT(e.tgl_pembayaran,'%d/%m/%Y') AS tglpembayaran,
IFNULL((SELECT SUM(subtotal)+SUM(subtotal_variabel) FROM hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot WHERE `id_penggajian`='$id' AND b.`id_karyawan`=a.`id_karyawan` AND `status`='pendapatan' AND total_pendapatan=1),0) AS pendapatan, 
IFNULL((SELECT SUM(subtotal)+SUM(subtotal_variabel) FROM hrd_penggajiandet b LEFT JOIN hrd_pendapatan_potongan c ON c.id_penpot=b.id_penpot WHERE `id_penggajian`='$id' AND b.`id_karyawan`=a.`id_karyawan` AND `status`='potongan' AND total_pendapatan=1),0) AS potongan, a.status FROM hrd_penggajiandet a
LEFT JOIN hrd_penggajian e ON e.penggajian_id=a.id_penggajian
LEFT JOIN hrd_karyawan b ON b.`id_karyawan`=a.`id_karyawan`
LEFT JOIN `hrd_jabatan` c ON c.`id_jabatan`=b.`id_jabatan`
LEFT JOIN hrd_departemen d ON c.id_dept=d.`id_dept`
WHERE a.`id_penggajian`='$id' 
GROUP BY a.`id_karyawan`
ORDER BY b.`nama_karyawan` ASC";
        
$sqdet = mysql_query($sqldetail);
while($rs1 = mysql_fetch_array($sqdet)){

    $objPHPExcel->getActiveSheet()
    ->getStyle('F'.$no)
    ->getNumberFormat()
    ->setFormatCode('d-mmm-yy;@');

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, $rs1['rekening'])
            ->setCellValue('B'.$no, number_format(($rs1['pendapatan'] - $rs1['potongan']),2,',','.'))
            ->setCellValue('C'.$no, $rs1['no_karyawan'])
            ->setCellValue('D'.$no, $rs1['nama_karyawan'])
            ->setCellValue('E'.$no, $rs1['nama_dept'])
            ->setCellValue('F'.$no, $rs1['tglpembayaran']);

    $objPHPExcel->getActiveSheet()->getStyle("B".$no.":C".$no)->applyFromArray($styleright);
    $objPHPExcel->getActiveSheet()->getStyle("F".$no)->applyFromArray($styleright);

    

    $no++;
}

foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="payroll.xlsx"');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
unset($objPHPExcel);
?>