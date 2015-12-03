<?php

require_once '../../../dbinfo.inc.php';
//include file PHPExcel dan konfigurasi database
require_once '../PHPExcel.php';
// Buat object PHPExcel
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("1")
        ->setTitle("Site Erection Project Report for 1")
        ->setSubject("Site Erection Project Report for 1")
        ->setDescription("Site Erection Project Report for 1")
        ->setKeywords("Site Erection Project Report for 1")
        ->setCategory("Site Erection Project Report");
$gdImage = imagecreatefromjpeg('logo_weltes_resized.jpg');
// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Sample image');
$objDrawing->setDescription('Sample image');
$objDrawing->setImageResource($gdImage);
$objDrawing->setCoordinates('B15');
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(140);
$objDrawing->setWidth(140);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="nettweight' . 1 . '_' . 2 . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;