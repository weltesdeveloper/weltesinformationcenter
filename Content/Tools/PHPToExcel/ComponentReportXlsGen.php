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

$projectName = $_GET['var1'];
if ($projectName == 'SMSROTARY JUICE SCREEN') {
    $projectName = 'SMSROTARY JUICE SCREEN ';
}

//SELECT CLient Description
$sqlPROJ = "SELECT PROJECT_NO, CLIENT_NAME, CLIENT_INIT
          FROM MST_CLIENT
               INNER JOIN PROJECT ON (MST_CLIENT.CLIENT_ID = PROJECT.CLIENT_ID)
          WHERE PROJECT_NAME='$projectName'";
$parsePROJ = oci_parse($conn, $sqlPROJ);
oci_execute($parsePROJ);
$rowPROJ = oci_fetch_array($parsePROJ);

//query mysql, ganti baris ini sesuai dengan query kamu
$compProfileSql = "SELECT COMP_TYPE,
                        SUM (TOTAL_QTY) TOTAL_QTY,
                        SUM (ASSIGNED_QTY) ASSIGNED_QTY,
                        SUM (MARKING) MARKING,
                        SUM (CUTTING) CUTTING,
                        SUM (ASSEMBLY) ASSEMBLY,
                        SUM (WELDING) WELDING,
                        SUM (DRILLING) DRILLING,
                        SUM (FAB_FINISHING) FAB_FINISHING,
                        SUM (FAB_QC_PASS) FAB_QC_PASS,
                        SUM (BLASTING) BLASTING,
                        SUM (PRIMER) PRIMER,
                        SUM (INTERMEDIATE) INTERMEDIATE,
                        SUM (PNT_FINISHING) PNT_FINISHING,
                        SUM (PAINT_QC_PASS) PAINT_QC_PASS,
                        SUM (PCK_QTY) PCK_QTY,
                        SUM (DLV_QTY) DLV_QTY,
                        SUM (ERECT_UPD_QTY) ERECT_UPD_QTY
                   FROM VW_DRAWING_INFO
                  WHERE PROJECT_NAME = :PROJNAME
               GROUP BY COMP_TYPE
               ORDER BY COMP_TYPE ASC";
$compProfileParse = oci_parse($conn, $compProfileSql);
oci_bind_by_name($compProfileParse, ":PROJNAME", $projectName);
oci_execute($compProfileParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Component Report for $projectName")
        ->setSubject("Component Report for $projectName")
        ->setDescription("Component Report for $projectName")
        ->setKeywords("Component Report for $projectName")
        ->setCategory("Weltes Information Center");

$styleTitle = array(
    'font' => array(
        'bold' => true,
        'underline' => true,
        'shrinkToFit' => true,
        'size' => 11,
        'name' => 'Trebuchet'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$styleDTNow = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    )
);

$styleArray1 = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 9,
        'name' => 'Verdana'
        ));

$styleArray2 = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 9,
        'name' => 'Verdana'
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

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B1:S1')
        ->setCellValue('B1', 'CHECKLIST COMPONENT ');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:O2')
        ->setCellValue('B2', "Project : $rowPROJ[PROJECT_NO] | " . $projectName);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B3:O3')
        ->setCellValue('B3', "Client : $rowPROJ[CLIENT_INIT] | " . $rowPROJ['CLIENT_NAME']);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('P3:S3')
        ->setCellValue('P3', 'up date ' . date("d-m-Y"));

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B4', 'NO')
        ->setCellValue('C4', 'COMP')
        ->setCellValue('D4', 'QTY')
        ->setCellValue('E4', 'ASSGN')
        ->setCellValue('F4', 'NOT ASSGN')
        ->setCellValue('G4', 'MARK')
        ->setCellValue('H4', 'CUTT')
        ->setCellValue('I4', 'ASSY')
        ->setCellValue('J4', 'WELD')
        ->setCellValue('K4', 'DRILL')
        ->setCellValue('L4', 'FAB FINISH')
        ->setCellValue('M4', 'FAB QC')
        ->setCellValue('N4', 'BLAST')
        ->setCellValue('O4', 'PRIMER')
        ->setCellValue('P4', 'INTERMEDIATE')
        ->setCellValue('Q4', 'TOP COAT')
        ->setCellValue('R4', 'PNT QC')
        ->setCellValue('S4', 'PACK QTY')
        ->setCellValue('T4', 'DELIV QTY')
        ->setCellValue('U4', 'KET');

$baris = 5;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($compProfileParse)) {
    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $no)
            ->setCellValue("C$baris", $row['COMP_TYPE'])
            ->setCellValue("D$baris", $row['TOTAL_QTY'])
            ->setCellValue("E$baris", $row['ASSIGNED_QTY'])
            ->setCellValue("F$baris", $row['TOTAL_QTY'] - $row['ASSIGNED_QTY'])
            ->setCellValue("G$baris", $row['MARKING'])
            ->setCellValue("H$baris", $row['CUTTING'])
            ->setCellValue("I$baris", $row['ASSEMBLY'])
            ->setCellValue("J$baris", $row['WELDING'])
            ->setCellValue("K$baris", $row['DRILLING'])
            ->setCellValue("L$baris", $row['FAB_FINISHING'])
            ->setCellValue("M$baris", $row['BLASTING'])
            ->setCellValue("N$baris", $row['FAB_QC_PASS'])
            ->setCellValue("O$baris", $row['PRIMER'])
            ->setCellValue("P$baris", $row['INTERMEDIATE'])
            ->setCellValue("Q$baris", $row['PNT_FINISHING'])
            ->setCellValue("R$baris", $row['PAINT_QC_PASS'])
            ->setCellValue("S$baris", $row['PCK_QTY'])
            ->setCellValue("T$baris", $row['DLV_QTY']);

    $objPHPExcel->getActiveSheet()->getStyle("B4:U$baris")->applyFromArray($styleBorder);

    $baris = $baris + 1;
}
// SET WRAPEP TEXT 
// APPLY TO ARANGE
$objPHPExcel->getActiveSheet()->getStyle('B4:U4')
        ->getAlignment()->setWrapText(true);

// APPLY TO COLOUMN
// $objPHPExcel->getActiveSheet()->getStyle('B4:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
//     ->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle("B1:B1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("C4:C$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("B4:U4")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("B2:B3")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("P3:U3")->applyFromArray($styleDTNow);

// SET WIDTH COLOM NO
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(5);

// SET WIDTH COLOM COMP TYPE
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(15);


// SET WIDTH COLOM KET
//$objPHPExcel->getActiveSheet()
//        ->getColumnDimension('S')
//        ->setWidth(20);


$sumSql = "SELECT GET_BLDG_WT(:projName) TOTAL_SUM FROM DUAL";
$sumParse = oci_parse($conn, $sumSql);
oci_bind_by_name($sumParse, ":projName", $projectName);
oci_define_by_name($sumParse, "TOTAL_SUM", $total);
oci_execute($sumParse);
while (oci_fetch($sumParse)) {
    $total;
}

$subcontAssignedSql = "SELECT GET_BLDG_ASSGWT(:projName) TOTALASSIGNED FROM DUAL";
$subcontAssignedParse = oci_parse($conn, $subcontAssignedSql);
oci_bind_by_name($subcontAssignedParse, ":projName", $projectName);
oci_define_by_name($subcontAssignedParse, "TOTALASSIGNED", $totalAssignedWeight);
oci_execute($subcontAssignedParse);
while (oci_fetch($subcontAssignedParse)) {
    $totalAssignedWeight;
}

$currFabTonnSql = "SELECT GET_BLDG_FAB(:projName) AS CURRENT_SUM FROM DUAL";
$currFabTonnParse = oci_parse($conn, $currFabTonnSql);
oci_bind_by_name($currFabTonnParse, ":projName", $projectName);
oci_define_by_name($currFabTonnParse, "CURRENT_SUM", $currentFabricationSum);
oci_execute($currFabTonnParse);
while (oci_fetch($currFabTonnParse)) {
    $currentFabricationSum;
}

$percAssigned = ($totalAssignedWeight / $total) * 100;
$percFab = ($currentFabricationSum / $total) * 100;

$barisReportWeight = $baris + 1;
$barisReportAssigned = $baris + 2;
$barisReportNotAssigned = $baris + 3;
$barisFabWeight = $baris + 4;
$barisFabNotWeight = $baris + 5;
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B$barisReportWeight", 'TOTAL PROJECT WEIGHT')
        ->setCellValue("F$barisReportWeight", number_format($total, 0))
        ->setCellValue("B$barisReportAssigned", 'TOTAL PROJECT WEIGHT ASSIGNED')
        ->setCellValue("F$barisReportAssigned", number_format($totalAssignedWeight, 0))
        ->setCellValue("G$barisReportAssigned", number_format($percAssigned, 2) . '%')
        ->setCellValue("B$barisReportNotAssigned", 'TOTAL PROJECT NOT ASSIGNED')
        ->setCellValue("F$barisReportNotAssigned", number_format($total - $totalAssignedWeight, 0))
        ->setCellValue("G$barisReportNotAssigned", number_format(100 - $percAssigned, 2) . '%')
        ->setCellValue("B$barisFabWeight", 'TOTAL FABRICATION WEIGHT')
        ->setCellValue("F$barisFabWeight", number_format($currentFabricationSum, 0))
        ->setCellValue("G$barisFabWeight", number_format($percFab, 2) . '%')
        ->setCellValue("B$barisFabNotWeight", 'NOT FABRICATION WEIGHT')
        ->setCellValue("F$barisFabNotWeight", number_format($total - $currentFabricationSum, 0))
        ->setCellValue("G$barisFabNotWeight", number_format(100 - $percFab, 2) . '%');

$objPHPExcel->getActiveSheet()->getStyle("F$barisReportWeight")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("F$barisReportAssigned")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("F$barisFabWeight")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('COMPONENT REPORT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ComponentReportFor' . $projectName . '_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>