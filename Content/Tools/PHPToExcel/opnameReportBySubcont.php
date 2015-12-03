<?php

// echo "DALAM PERBAIKAN";exit();
require_once '../../../dbinfo.inc.php';
//include file PHPExcel dan konfigurasi database
require_once '../PHPExcel.php';
// Buat object PHPExcel
$objPHPExcel = new PHPExcel();
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
EOD;
    exit;
}
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

if (isset($_POST['cd-dropdown']))
    $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
date_default_timezone_set('Asia/Jakarta'); //CDT
$current_date = date('H:i:s');

error_reporting(E_ALL);

$start = $_GET['start'];
$end = $_GET['end'];

$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Opname Report")
        ->setSubject("Opname Report")
        ->setDescription("Opname Report")
        ->setKeywords("Opname Report")
        ->setCategory("Opname Report Weltes Energi Nusantara");

$styleTitleoPNAME = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 22,
        'name' => 'Times New Roman'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleTitle = array(
    'font' => array(
        'bold' => true,
//        'shrinkToFit' => true,
//        'size' => 16,
        'name' => 'Times New Roman'
    )
);
$styleTitleCenter = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 16,
        'name' => 'Times New Roman'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleTitleTableHeader = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 16,
        'name' => 'Times New Roman'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleTableTitle = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 16,
        'name' => 'Times New Roman'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleTableContent = array(
    'font' => array(
//        'bold' => true,
        'shrinkToFit' => true,
        'size' => 16,
        'name' => 'Times New Roman'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleBorder = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        ),
    ),
    'font' => array(
//        'bold' => true,
        'shrinkToFit' => true,
//        'size' => 16,
        'name' => 'Times New Roman'
    ),
);
$ds = new DateTime($start);
$ds = $ds->format("d F Y");
$de = new DateTime($end);
$de = $de->format("d F Y");
// SET DOCUMENT TITLE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:D1')
        ->setCellValue('A1', 'SUBCONT OPNAME REPORT');
$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->applyFromArray($styleTitleCenter);
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A2:D2')
        ->setCellValue('A2', "PERIOD : $ds - $de");
$objPHPExcel->getActiveSheet()->getStyle("A2:D2")->applyFromArray($styleTitleCenter);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'NO')
        ->setCellValue('B4', 'SUBCONT')
        ->setCellValue('C4', 'TOTAL WEIGHT (KG)')
        ->setCellValue('D4', 'AMOUNT ');

$baris = 5;
$no = 1;
$sql = "SELECT SUBCONT_ID, "
        . "SUM (TOTAL_QTY * UNIT_WEIGHT) TOTAL_WEIGHT, "
        . "SUM (TOTAL_QTY * UNIT_WEIGHT * OPN_PRICE) AMOUNT "
        . "FROM VW_INFO_OPNAME_FAB "
        . "WHERE OPN_ACT_DATE BETWEEN TO_DATE ('$start', 'MM/DD/YYYY HH24:MI:SS') "
        . "AND  TO_DATE ('$end', 'MM/DD/YYYY HH24:MI:SS') "
        . "GROUP BY SUBCONT_ID "
        . "ORDER BY SUBCONT_ID";
$opnameDtlParse = oci_parse($conn, $sql);
oci_execute($opnameDtlParse);
$sum_weight = 0;
$sum_amount = 0;
while ($row = oci_fetch_array($opnameDtlParse)) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $no)
            ->setCellValue("B$baris", $row['SUBCONT_ID'])
            ->setCellValue("C$baris", number_format($row['TOTAL_WEIGHT'],2))
            ->setCellValue("D$baris", "Rp. " . number_format($row['AMOUNT'],2));
    $no = $no + 1;
    $baris = $baris + 1;
    $sum_weight += $row['TOTAL_WEIGHT'];
    $sum_amount += $row['AMOUNT'];
}
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A$baris", '')
        ->setCellValue("B$baris", "SUMMARY")
        ->setCellValue("C$baris", number_format($sum_weight,2))
        ->setCellValue("D$baris", "Rp. " . number_format($sum_amount,2));
$objPHPExcel->getActiveSheet()->getStyle("A$baris:D$baris")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('A')
        ->setWidth(16.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(30.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(25.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(23.86);

$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setOddFooter('Page &P / &N');
$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setEvenFooter('Page &P / &N');

$objPHPExcel->getActiveSheet()->getStyle("A4:D$baris")->applyFromArray($styleBorder);
// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('OPNAME SUBCONT');
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 4);
$objPHPExcel->getActiveSheet()->setAutoFilter("A4:D4");
$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="OpnameReport' . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>