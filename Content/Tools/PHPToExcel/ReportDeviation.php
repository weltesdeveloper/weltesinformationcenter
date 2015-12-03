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

$projectNo = $_GET['projectNo'];

$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Report Weekly $projectNo")
        ->setSubject("Report Project $projectNo")
        ->setDescription("Report Project $projectNo")
        ->setKeywords("Report Project $projectNo")
        ->setCategory("Report Project $projectNo");

$gdImage = imagecreatefromjpeg('logo_weltes_resized.jpg');
// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Sample image');
$objDrawing->setDescription('Sample image');
$objDrawing->setImageResource($gdImage);
$objDrawing->setCoordinates('A1');
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(200);
$objDrawing->setWidth(200);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


//$jobName = $_GET['client'];
$buildingName = "SMSMILLHOUSE";
$clientSql = "SELECT CLIENT_NAME FROM MST_CLIENT WHERE CLIENT_ID = (SELECT DISTINCT(CLIENT_ID) FROM PROJECT WHERE PROJECT_NO ='$projectNo')";
$clientParse = oci_parse($conn, $clientSql);
oci_execute($clientParse);
$client = oci_fetch_array($clientParse)['CLIENT_NAME'];

$sql = "SELECT TO_CHAR(PROJECT_START_DT, 'DD-MON-YYYY') ||  ' TO ' ||TO_CHAR(PROJECT_END_DT, 'DD-MON-YYYY') TGL FROM PROJECT_SPAN WHERE JOB_NAME = '$projectNo'";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
$tgl = oci_fetch_array($parse)['TGL'];

// SET DOCUMENT MAIN PROPERTIES
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A5', 'PROJECT');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A6', 'OWNER');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A7', 'CLIENT');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A8', 'PERIOD');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B5', "$projectNo");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B6', "WELTES");
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B7', $client);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B8', $tgl);

// SET DOCUMENT TITLE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A10:I10')
        ->setCellValue('A10', 'WEEKLY PROGRESS REPORT');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A11', 'WEEK');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B11', 'START DATE');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('C11', 'END DATE');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D11', 'WEEKLY TERGET');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E11', 'CUMULATIVE TARGET');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F11', 'ACTUAL WEIGHT');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('G11', 'PROCENTAGE WEEKLY TARGET');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('H11', 'PROCENTAGE ACTUAL TARGET');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('I11', 'DEVIATION');


$baris = 12;
// MAIN PROPERTIES QUERY
$scurveSql = "SELECT WIV.*, (SELECT SUM(WK_WEIGHT) FROM PROJECT_SPAN_DTL WHERE JOB_NAME = :JOBNAME) TOTAL_WEIGHT,
                        PSD.WK_WEIGHT, SUM (
                                            PSD.WK_WEIGHT)
                                         OVER (ORDER BY PSD.START_WK_DT
                                               ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW)
                                            AS CUMULATIVE_WEIGHT FROM WEEK_INCR_VIEW WIV "
        . "LEFT OUTER JOIN PROJECT_SPAN_DTL PSD "
        . "ON PSD.START_WK_DT = WIV.START_WEEK_DATE AND PSD.JOB_NAME = WIV.JOBNAME WHERE WIV.JOBNAME = :JOBNAME ORDER BY WIV.WEEK_NUM_INCR ASC";
$scurveParse = oci_parse($conn, $scurveSql);
oci_bind_by_name($scurveParse, ":JOBNAME", $projectNo);
oci_execute($scurveParse);
$k = 0;
$counter = 0;
$weightCummulativeProject = array();
$totalcummulative = 0;
$weeklyjumlah = 0;
$avgErection = 0;
$totaldeviasi = 0;
while ($row = oci_fetch_array($scurveParse)) {
    $totalOnsiteSum = 0;
    $totalOnsiteWeight = 0;
    $totalPrepSum = 0;
    $totalPrepWeight = 0;
    $totalErectSum = 0;
    $totalErectWeight = 0;
    $totalQcSum = 0;
    $totalQcWeight = 0;
    $totalAll = 0;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['WEEK_NUM_INCR']);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $row['START_WEEK_DATE']);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("C$baris", $row['END_WEEK_DATE']);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("D$baris", number_format($row['WK_WEIGHT'], 2));
    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue("E$baris", number_format($row['CUMULATIVE_WEIGHT'], 2));
    $prosentasePlanning = $row['WK_WEIGHT'] / $row['TOTAL_WEIGHT'] * 100;
    $scurveDetailSql = "SELECT   SUM (DO.ONSITE_UPD_QTY) ONSITESUM,
                                                                 SUM (DO.ONSITE_UPD_QTY * MD.WEIGHT) TOTALONSITEWEIGHT,
                                                                 SUM (DO.PREP_UPD_QTY) PREPSUM,
                                                                 SUM (DO.PREP_UPD_QTY * MD.WEIGHT) TOTALPREPWEIGHT,
                                                                 SUM (DO.ERECT_UPD_QTY) ERECTSUM,
                                                                 SUM (DO.ERECT_UPD_QTY * MD.WEIGHT) TOTALERECTWEIGHT,
                                                                 SUM (DO.QC_UPD_QTY) QCSUM,
                                                                 SUM (DO.QC_UPD_QTY * MD.WEIGHT) TOTALQCWEIGHT
                                                            FROM PROJECT P
                                                                 LEFT OUTER JOIN DTL_ERC_UPD DO
                                                                    ON DO.PROJECT_NAME = P.PROJECT_NAME
                                                                 LEFT OUTER JOIN MASTER_DRAWING MD 
                                                                    ON MD.HEAD_MARK = DO.HEAD_MARK
                                                           WHERE DO.UPD_DATE BETWEEN TO_DATE(:STARTDATE,'DD-MON-YYYY') AND TO_DATE(:ENDDATE,'DD-MON-YYYY') AND P.PROJECT_NO = :PROJNO
                                                        GROUP BY P.PROJECT_NO";
    $scurveDetailParse = oci_parse($conn, $scurveDetailSql);
    oci_bind_by_name($scurveDetailParse, ":PROJNO", $projectNo);
    oci_bind_by_name($scurveDetailParse, ":STARTDATE", $row['START_WEEK_DATE']);
    oci_bind_by_name($scurveDetailParse, ":ENDDATE", $row['END_WEEK_DATE']);
    oci_execute($scurveDetailParse);

    while ($row1 = oci_fetch_array($scurveDetailParse)) {
        $totalOnsiteSum = $row1['ONSITESUM'];
        $totalOnsiteWeight = $row1['TOTALONSITEWEIGHT'];
        $totalPrepSum = $row1['PREPSUM'];
        $totalPrepWeight = $row1['TOTALPREPWEIGHT'];
        $totalErectSum = $row1['ERECTSUM'];
        $totalErectWeight = $row1['TOTALERECTWEIGHT'];
        $totalQcSum = $row1['QCSUM'];
        $totalQcWeight = $row1['TOTALQCWEIGHT'];
        $totalAll = $totalOnsiteWeight + $totalPrepWeight + $totalErectWeight + $totalQcWeight / 4;
    }
    $avgErection = ($totalOnsiteWeight + $totalPrepWeight + $totalErectWeight + $totalQcWeight) / 4;
    $totalcummulative+=$avgErection;
    $prosentaseErection = $avgErection / $row['TOTAL_WEIGHT'] * 100;
    $deviation = number_format($prosentaseErection - $prosentasePlanning, 2);

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("F$baris", number_format($avgErection, 2));
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("G$baris", number_format($prosentasePlanning, 2));
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("H$baris", number_format($prosentaseErection, 2));
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("I$baris", $deviation);
    $weeklyjumlah+=doubleval($row['WK_WEIGHT']);
    $avgErection+=doubleval(number_format($row['CUMULATIVE_WEIGHT'], 2));
    $totaldeviasi+=$deviation;
    $baris++;
}
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('A')
        ->setWidth(16);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(16);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(16);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(16);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('E')
        ->setWidth(19.6);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('F')
        ->setWidth(16);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('G')
        ->setWidth(27);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('H')
        ->setWidth(27.14);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('I')
        ->setWidth(16);

$style = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$objPHPExcel->getActiveSheet()->getStyle("A10:I10")->applyFromArray($style);

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);


$barisminsatu = $baris - 1;
$objPHPExcel->getActiveSheet()->getStyle("A11:I$barisminsatu")->applyFromArray($style);
$objPHPExcel->getActiveSheet()->getStyle("A11:I$barisminsatu")->applyFromArray($styleArray);

$styleArray1 = array(
    'font' => array(
        ///'bold' => true,
        'shrinkToFit' => true,
        'size' => 10,
        'name' => 'Times New Roman'
        ));
$objPHPExcel->getActiveSheet()->getStyle("A1:I$barisminsatu")->applyFromArray($styleArray1);

$styleArray11 = array(
    'font' => array(
        ///'bold' => true,
        'shrinkToFit' => true,
        'size' => 14,
        'name' => 'Times New Roman'
        ));
$objPHPExcel->getActiveSheet()->getStyle("A10:I10")->applyFromArray($styleArray11);

//SUMMARY
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("A$baris:C$baris")
        ->setCellValue("A$baris", "SUMMARY");
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("D$baris", $weeklyjumlah);
//$objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue("F$baris", $totalActual);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("I$baris", $totaldeviasi);
$objPHPExcel->getActiveSheet()->getStyle("A$baris:I$baris")->applyFromArray($style);
$objPHPExcel->getActiveSheet()->getStyle("A$baris:I$baris")->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->setTitle('WEEKLY REPORT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DeviationReport' . '_' . $projectNo . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>