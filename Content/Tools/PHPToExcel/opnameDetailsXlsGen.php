<?php

// echo "DALAM PERBAIKAN";exit();
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

$opnameId = strval($_GET['opnameId']);
$projectName = strval($_GET['pn']);
$subcont = strval($_GET['subcont']);
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Opname Report $opnameId")
        ->setSubject("Opname Report $opnameId")
        ->setDescription("Opname Report $opnameId")
        ->setKeywords("Opname Report $opnameId")
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
        ->mergeCells('B1:N1')
        ->setCellValue('B1', 'OPNAME HASIL PEKERJAAN');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:N2')
        ->setCellValue('B2', 'PT. WELTES ENERGI NUSANTARA');

// SET DOCUMENT MAIN PROPERTIES
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B4:C4')
        ->setCellValue('B4', 'JOB NO');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B5:C5')
        ->setCellValue('B5', 'CUSTOMER');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B6:C6')
        ->setCellValue('B6', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B7:C7')
        ->setCellValue('B7', 'SUBCONTRACTOR');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('K7:L7')
        ->setCellValue('K7', 'OPNAME PERIOD : ');



// MAIN PROPERTIES QUERY
$mstOpnameSql = "SELECT DISTINCT MO.PROJECT_NO, DTL_OPNAME.PROJECT_NAME, MO.SUBCONT_ID, OPN_PERIOD PERIODE, TO_CHAR(DTL_OPNAME.OPN_ACT_DATE, 'DD MON YYYY')  DT
  FROM MST_OPNAME MO INNER JOIN DTL_OPNAME 
  ON MO.OPNAME_ID = DTL_OPNAME.OPNAME_ID
 WHERE MO.OPNAME_ID  = :OPNID AND MO.SUBCONT_ID = '$subcont' AND DTL_OPNAME.PROJECT_NAME = '$projectName'";
//echo "$mstOpnameSql";
$mstOpnameParse = oci_parse($conn, $mstOpnameSql);
oci_bind_by_name($mstOpnameParse, ":OPNID", $opnameId);
oci_execute($mstOpnameParse);
while ($row1 = oci_fetch_array($mstOpnameParse)) {
    $jobNo = $row1['PROJECT_NO'];
    $buildingNo = $row1['PROJECT_NAME'];
    $subcontId = $row1['SUBCONT_ID'];
    $periode = intval($row1['PERIODE']) . " / " . $row1['DT'];
}
//    $jobNo = oci_fetch_array($mstOpnameParse)['PROJECT_NO'];
//    $buildingNo = oci_fetch_array($mstOpnameParse)['PROJECT_NAME'];
//    $subcontId = oci_fetch_array($mstOpnameParse)['SUBCONT_ID'];
// SET DOCUMENT MAIN PROPERTIES CONTENT
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D4', ": " . $opnameId);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D5', ": " . $jobNo);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D6', ": " . $buildingNo);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D7', ": " . $subcontId);
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('M7:N7')
        ->setCellValue('M7', $periode);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B9', 'NO')
        ->setCellValue('C9', 'HEADMARK')
        ->setCellValue('D9', 'PROFILE')
        ->setCellValue('E9', 'DWG QTY')
        ->setCellValue('F9', 'LENGTH')
        ->setCellValue('G9', 'DWG ASSG')
        ->setCellValue('H9', 'QC PASS QTY')
        ->setCellValue('I9', 'QC PASS DATE')
        ->setCellValue('J9', 'QTY OPNAME')
        ->setCellValue('K9', 'UNIT WEIGHT')
        ->setCellValue('L9', 'TOTAL WEIGHT')
        ->setCellValue('M9', 'PRICE')
        ->setCellValue('N9', 'TOTAL PRICE');

$baris = 10;
$no = 0;
$totalPrice = 0.00;
$totalWeight = 0.00;
$qtyOpname = 0;
$unitWeight = 0;
$price = 0;
$substringDateOpnameID = substr($opnameId, 0, strlen($opnameId) - 8);
$opnameDtlSql = "WITH ASSG_DWG
     AS (  SELECT SUM (ASSIGNED_QTY) ASSIGNED_QTY,
                  HEAD_MARK,
                  ID,
                  SUBCONT_ID,
                  TO_CHAR (MAX (ASSIGNMENT_DATE), 'DD-MON-YYYY') AS ASSIGNMENT_DATE
             FROM MASTER_DRAWING_ASSIGNED
         GROUP BY HEAD_MARK,ID, SUBCONT_ID),
     FAB_QC
     AS (  SELECT SUM (FAB_QC_PASS) FAB_QC_PASS, HEAD_MARK,ID, MAX(FAB_QC_PASS_DATE) FAB_QC_PASS_DATE
             FROM FABRICATION_QC
         GROUP BY HEAD_MARK,ID)
  SELECT VROP.HEAD_MARK,
         NVL(VROP.DWG_TYP,'-') DWG_TYP,
         VROP.PROFILE,
         AD.ASSIGNED_QTY,
         VROP.LENGTH,
         AD.ASSIGNMENT_DATE,
         FQ.FAB_QC_PASS,
         FQ.FAB_QC_PASS_DATE,
         VROP.QTY_OPNAME,
         VROP.UNIT_WEIGHT,
         VROP.TOTAL_WEIGHT,
         VROP.PRICE,
         VROP.TOTAL_PRICE
    FROM VW_REPORT_OPNAME_PRICE VROP
         INNER JOIN
         ASSG_DWG AD
            ON     AD.HEAD_MARK = VROP.HEAD_MARK
               AND AD.SUBCONT_ID = VROP.SUBCONT_ID
               INNER JOIN FAB_QC FQ ON
               FQ.HEAD_MARK = VROP.HEAD_MARK
               AND FQ.ID = AD.ID
   WHERE     VROP.PROJECT_NAME = '$projectName'
         AND VROP.OPNAME_ID = '$opnameId'
         AND VROP.SUBCONT_ID = '$subcont'
ORDER BY VROP.HEAD_MARK ASC";
$opnameDtlParse = oci_parse($conn, $opnameDtlSql);
oci_execute($opnameDtlParse);

while ($row = oci_fetch_array($opnameDtlParse)) {
    $no = $no + 1;
//    $profile = '#'.$row['DWG_TYP'].'# '.$row['PROFILE'];
    $profile = $row['PROFILE'];

    $varDiv = @($row['PROGRESSGROSS'] / $row['GROSSWEIGHT'] * 100);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $no)
            ->setCellValue("C$baris", $row['HEAD_MARK'] . " ($row[DWG_TYP]) ")
            ->setCellValue("D$baris", $profile)
            ->setCellValue("E$baris", $row['ASSIGNED_QTY'])
            ->setCellValue("F$baris", $row['LENGTH'])
            ->setCellValue("G$baris", $row['ASSIGNMENT_DATE'])
            ->setCellValue("H$baris", $row['FAB_QC_PASS'])
            ->setCellValue("I$baris", $row['FAB_QC_PASS_DATE'])
            ->setCellValue("J$baris", $row['QTY_OPNAME'])
            ->setCellValue("K$baris", number_format($row['UNIT_WEIGHT'], 2))
            ->setCellValue("L$baris", number_format(floatval($row['UNIT_WEIGHT'] * $row['QTY_OPNAME']), 2))
            ->setCellValue("M$baris", $row['PRICE'])
            ->setCellValue("N$baris", $row['PRICE'] * $row['UNIT_WEIGHT'] * $row['QTY_OPNAME']);

    //format sebagai number
    $objPHPExcel->getActiveSheet()->getStyle("M$baris")->getNumberFormat()->
            setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("N$baris")->getNumberFormat()->
            setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $objPHPExcel->getActiveSheet()->getStyle("B9:N$baris")->applyFromArray($styleBorder);
    $objPHPExcel->getActiveSheet()->getStyle("B10:N$baris")->applyFromArray($styleTableContent);
    $objPHPExcel->getActiveSheet()
            ->getRowDimension("$baris")
            ->setRowHeight(40);

    $baris = $baris + 1;
    $totalPrice += $row['PRICE'] * $row['UNIT_WEIGHT'] * $row['QTY_OPNAME'];
    $totalWeight += $row['UNIT_WEIGHT'] * $row['QTY_OPNAME'];
    $qtyOpname += $row['QTY_OPNAME'];
    $unitWeight += $row['UNIT_WEIGHT'];
    $price += doubleval($row['PRICE']);
}


$objPHPExcel->getActiveSheet()->getStyle("A1:N2")->applyFromArray($styleTitleoPNAME);
$objPHPExcel->getActiveSheet()->getStyle("A4:N7")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B1:N2")->applyFromArray($styleTitleCenter);
$objPHPExcel->getActiveSheet()->getStyle("B9:N9")->applyFromArray($styleTitleTableHeader);
$objPHPExcel->getActiveSheet()
        ->getRowDimension("9")
        ->setRowHeight(50);

$lastRow = $baris;
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("B$lastRow:I$lastRow")
        ->setCellValue("B$lastRow", 'SUMMARY');
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:I$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("J$lastRow", number_format($qtyOpname, 2));
$objPHPExcel->getActiveSheet()->getStyle("J$lastRow:J$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("K$lastRow", number_format($unitWeight, 2));
$objPHPExcel->getActiveSheet()->getStyle("K$lastRow:K$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("L$lastRow", number_format($totalWeight, 2));
$objPHPExcel->getActiveSheet()->getStyle("L$lastRow:L$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("M$lastRow", $price);
$objPHPExcel->getActiveSheet()->getStyle("M$lastRow:M$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->getActiveSheet()->getStyle("M$lastRow")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("N$lastRow", $totalPrice, 2);
$objPHPExcel->getActiveSheet()->getStyle("N$lastRow:N$lastRow")->applyFromArray($styleTitle);

$objPHPExcel->getActiveSheet()->getStyle("N$lastRow")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()
        ->getRowDimension("$lastRow")
        ->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:N$baris")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:N$baris")->applyFromArray($styleTableContent);

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
        ->mergeCells("G$lastRow:H$lastRow")
        ->setCellValue("G$lastRow", 'DIPERIKSA');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G$lastRow1", 'PPC : ');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G$lastRow2", 'TGL : ');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("L$lastRow:M$lastRow")
        ->setCellValue("L$lastRow", 'DISETUJUI');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("L$lastRow1", 'TGL : ');

$objPHPExcel->getActiveSheet()->getStyle("B$lastRow:N$lastRow")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow1:N$lastRow1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B$lastRow2:N$lastRow2")->applyFromArray($styleTitle);

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
        ->setWidth(15);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('F')
        ->setWidth(13.29);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('G')
        ->setWidth(16.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('H')
        ->setWidth(20.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('I')
        ->setWidth(22.57);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('J')
        ->setWidth(21.29);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('K')
        ->setWidth(21.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('L')
        ->setWidth(24.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('M')
        ->setWidth(15.43);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('N')
        ->setWidth(21.15);




$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setOddFooter('Page &P / &N');
$objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setEvenFooter('Page &P / &N');
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(9, 9);

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('OPNAME HASIL PEKERJAAN');

$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="OpnameReport' . $opnameId . '_' . $formattedDate . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>