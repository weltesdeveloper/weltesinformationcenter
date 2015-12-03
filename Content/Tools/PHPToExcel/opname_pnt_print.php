<?php

// echo "DALAM PERBAIKAN";exit();
require_once '../../../dbinfo.inc.php';
//include file PHPExcel dan konfigurasi database
require_once '../PHPExcel.php';
// Buat object PHPExcel
include '../../../FunctionAct.php';
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

$periode = strval($_GET['periode']);
$type = strval($_GET['type']);
$opnameId = "OPN-$periode";
$job = strval($_GET['job']);
$subjob = strval($_GET['subjob']);

$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Opname $type Report $opnameId")
        ->setSubject("Opname $type Report $opnameId")
        ->setDescription("Opname $type Report $opnameId")
        ->setKeywords("Opname $type Report $opnameId")
        ->setCategory("Opname $type Report Weltes Energi Nusantara");

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
        'shrinkToFit' => true,
        'size' => 16,
        'name' => 'Times New Roman'
    )
);
$styleTitleCenter = array(
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
);
// SET DOCUMENT TITLE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B1:J1')
        ->setCellValue('B1', "LAPORAN OPNAME $type");
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:J2')
        ->setCellValue('B2', 'PT. WELTES ENERGI NUSANTARA');

// SET DOCUMENT MAIN PROPERTIES
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B4:C4')
        ->setCellValue('B4', 'PERIODE');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B5:C5')
        ->setCellValue('B5', 'JOB');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B6:C6')
        ->setCellValue('B6', 'SUBJOB');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('G4:H4')
        ->setCellValue('G4', 'TANGGAL OPNAME');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('G5:H5')
        ->setCellValue('G5', 'OPNAME TYPE');
//$objPHPExcel->setActiveSheetIndex(0)
//        ->mergeCells('B8:C8')
//        ->setCellValue('B8', 'SUBCONTRACTOR');

$queryDate = "SELECT to_char(MAX(OPNAME_DATE), 'DD MONTH YYYY') OPNAME_DATE "
        . "FROM VW_REPORT_OPNAME_PNT "
        . "WHERE OPNAME_PERIOD = '$periode' "
        . "AND OPNAME_TYPE = '$type' "
        . "AND PROJECT_NO = '$job' "
        . "AND PROJECT_NAME_NEW = '$subjob'";
$tangal_opname = SingleQryFld($queryDate, $conn);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D4', ": " . "$periode");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D5', ": " . "$job");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D6', ": " . "$subjob");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('I4', ": " . $tangal_opname);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('I5', ": " . "$type");
//$objPHPExcel->setActiveSheetIndex(0)
//        ->setCellValue('D8', ": " . "GUNADI");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B10', 'NO')
        ->setCellValue('C10', 'HEADMARK')
        ->setCellValue('D10', 'COMP TYPE')
        ->setCellValue('E10', 'PROFILE')
        ->setCellValue('F10', 'QC PASS QTY')
        ->setCellValue('G10', 'SURFACE AREA')
        ->setCellValue('H10', 'QTY OPNAME')
        ->setCellValue('I10', 'PRICE')
        ->setCellValue('J10', 'TOTAL PRICE');

$baris = 11;
$no = 0;
$qty = 0;
$price = 0;
$total_price = 0;
$surface = 0;
$opnameDtlSql = "SELECT * FROM VW_REPORT_OPNAME_PNT "
        . "WHERE OPNAME_PERIOD = '$periode' "
        . "AND OPNAME_TYPE = '$type' "
        . "AND PROJECT_NO = '$job' "
        . "AND PROJECT_NAME_NEW = '$subjob' "
        . "ORDER BY PROJECT_NO, PROJECT_NAME_NEW, COMP_TYPE, HEAD_MARK";
$opnameDtlParse = oci_parse($conn, $opnameDtlSql);
oci_execute($opnameDtlParse);

while ($row = oci_fetch_array($opnameDtlParse)) {
    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $no)
            ->setCellValue("C$baris", $row['HEAD_MARK'])
            ->setCellValue("D$baris", $row['COMP_TYPE'])
            ->setCellValue("E$baris", $row['PROFILE'])
            ->setCellValue("F$baris", $row['TOTAL_QTY'])
            ->setCellValue("G$baris", $row['SURFACE'])
            ->setCellValue("H$baris", $row['OPNAME_QTY'])
            ->setCellValue("I$baris", number_format($row['OPNAME_PRICE'], 2))
            ->setCellValue("J$baris", number_format($row['SURFACE'] * $row['OPNAME_QTY'] * $row['OPNAME_PRICE'], 2));

    $objPHPExcel->getActiveSheet()->getStyle("B10:J$baris")->applyFromArray($styleBorder);
    $objPHPExcel->getActiveSheet()->getStyle("B11:J$baris")->applyFromArray($styleTableContent);
    $objPHPExcel->getActiveSheet()
            ->getRowDimension("$baris")
            ->setRowHeight(23);

    $qty+=$row['OPNAME_QTY'];
    $price+=$row['OPNAME_PRICE'];
    $surface+=$row['SURFACE'];
    $total_price+=($row['OPNAME_QTY'] * $row['OPNAME_PRICE'] * $row['SURFACE']);
    $baris = $baris + 1;
}


$objPHPExcel->getActiveSheet()->getStyle("A1:J2")->applyFromArray($styleTitleoPNAME);
$objPHPExcel->getActiveSheet()->getStyle("A4:J7")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B1:J2")->applyFromArray($styleTitleCenter);
$objPHPExcel->getActiveSheet()->getStyle("B10:J10")->applyFromArray($styleTitleTableHeader);
$objPHPExcel->getActiveSheet()
        ->getRowDimension("10")
        ->setRowHeight(50);

$lastRow = $baris;
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("B$lastRow:F$lastRow")
        ->setCellValue("B$lastRow", 'SUMMARY');
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:I$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G$lastRow", number_format($surface, 2));
$objPHPExcel->getActiveSheet()->getStyle("I$lastRow:I$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H$lastRow", number_format($qty, 2));
$objPHPExcel->getActiveSheet()->getStyle("J$lastRow:J$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("I$lastRow", number_format($price, 2));
$objPHPExcel->getActiveSheet()->getStyle("K$lastRow:K$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("J$lastRow", number_format($total_price, 2));
$objPHPExcel->getActiveSheet()->getStyle("L$lastRow:L$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->getActiveSheet()
        ->getRowDimension("$lastRow")
        ->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:J$baris")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:J$baris")->applyFromArray($styleTableContent);

$lastRow = $baris + 2;
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("C$lastRow:D$lastRow")
        ->setCellValue("C$lastRow", 'DIAJUKAN OLEH');
$lastRow1 = $baris + 6;
$lastRow2 = $baris + 7;
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C$lastRow1", 'SUBKON : ');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C$lastRow2", 'TGL : ');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("F$lastRow:G$lastRow")
        ->setCellValue("F$lastRow", 'DIPERIKSA');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F$lastRow1", 'PPC : ');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F$lastRow2", 'TGL : ');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("I$lastRow:J$lastRow")
        ->setCellValue("I$lastRow", 'DISETUJUI');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("I$lastRow1", 'TGL : ');

$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:J$lastRow")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow1:J$lastRow1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow2:J$lastRow2")->applyFromArray($styleTitle);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(6.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(24.71);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(32.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('E')
        ->setWidth(30);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('F')
        ->setWidth(25.29);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('G')
        ->setWidth(25.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('H')
        ->setWidth(20.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('I')
        ->setWidth(20.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('J')
        ->setWidth(21.29);

$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setOddFooter('Page &P / &N');
$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setEvenFooter('Page &P / &N');
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(11, 11);

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('OPNAME HASIL PEKERJAAN');

$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="OpnameReport ' . $type . '_' . $job . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>