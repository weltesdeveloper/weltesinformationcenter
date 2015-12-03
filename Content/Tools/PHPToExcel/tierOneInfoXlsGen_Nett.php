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

$jobName = strval($_GET['job']);

//SELECT CLient Description
/* $sqlPROJ = "SELECT PROJECT_NO, CLIENT_NAME, CLIENT_INIT
  FROM MST_CLIENT
  INNER JOIN PROJECT ON (MST_CLIENT.CLIENT_ID = PROJECT.CLIENT_ID)
  WHERE PROJECT_NAME='$jobName'";
  $parsePROJ  = oci_parse($conn, $sqlPROJ);
  oci_execute($parsePROJ);
  $rowPROJ = oci_fetch_array($parsePROJ); */

//query mysql, ganti baris ini sesuai dengan query kamu
$tierOneSql = "SELECT STOV.PROJECTNAME PROJECTNAME, "
        . "STOV.NETWEIGHT NETWEIGHT, "
        . "STOV.TOTALPROGRESSNET PROGRESSNET "
        . "FROM SITE_TIER_ONE_VW STOV "
        . "WHERE STOV.PROJECTNO = :PROJNO "
        . "ORDER BY STOV.PROJECTNAME ASC";
$tierOneParse = oci_parse($conn, $tierOneSql);
oci_bind_by_name($tierOneParse, ":PROJNO", $jobName);
oci_execute($tierOneParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Site Erection Project Report for $jobName")
        ->setSubject("Site Erection Project Report for $jobName")
        ->setDescription("Site Erection Project Report for $jobName")
        ->setKeywords("Site Erection Project Report for $jobName")
        ->setCategory("Site Erection Project Report");

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
        ->mergeCells('B1:F1')
        ->setCellValue('B1', 'SITE ERECTION PROJECT SUMMARY REPORT FOR NETT WEIGHT');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:E2')
        ->setCellValue('B2', "JOB NO : $jobName");

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B3:E3')
        ->setCellValue('B3', "DESCRIPTION :");

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('F3:F3')
        ->setCellValue('F3', 'UPDATED : ' . date("d-m-Y"));

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B4', 'NO')
        ->setCellValue('C4', 'BUILDING')
        ->setCellValue('D4', 'TOTAL NETT WEIGHT (Kg)')
        ->setCellValue('E4', 'PROGRESS NETT WEIGHT  (Kg)')
        ->setCellValue('F4', 'PROGRESS %');

$baris = 5;
$no = 0;
$SUMMARY_TOTAL_WEIGHT = 0;
$SUMMARY_TOTAL_PROGRESS = 0;
//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($tierOneParse)) {
    $no = $no + 1;
    $varDiv = @($row['PROGRESSNET'] / $row['NETWEIGHT'] * 100);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $no)
            ->setCellValue("C$baris", $row['PROJECTNAME'])
            ->setCellValue("D$baris", $row['NETWEIGHT'])
            ->setCellValue("E$baris", $row['PROGRESSNET'])
            ->setCellValue("F$baris", number_format($varDiv, 2) . '%');


    $baris = $baris + 1;
    $SUMMARY_TOTAL_WEIGHT+=$row['NETWEIGHT'];
    $SUMMARY_TOTAL_PROGRESS+=$row['PROGRESSNET'];
}

$objPHPExcel->getActiveSheet()->getStyle("B1:B1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("C4:C$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("B4:J4")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("B2:B3")->applyFromArray($styleArray1);


// SET WIDTH COLOM NO
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(5);

// SET WIDTH COLOM COMP TYPE
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(25.71);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(25.29);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('E')
        ->setWidth(30.71);

$objPHPExcel->getActiveSheet()
        ->getColumnDimension('F')
        ->setWidth(25.71);

//SUMMARY
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("B$baris:C$baris")
        ->setCellValue("B$baris", 'SUMMARY');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D$baris", $SUMMARY_TOTAL_WEIGHT)
        ->setCellValue("E$baris", $SUMMARY_TOTAL_PROGRESS)
        ->setCellValue("F$baris", number_format($SUMMARY_TOTAL_PROGRESS / $SUMMARY_TOTAL_WEIGHT * 100, 2) . '%');
$objPHPExcel->getActiveSheet()->getStyle("B4:F$baris")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("D5:F$baris")->applyFromArray($styleDTNow);
$objPHPExcel->getActiveSheet()->getStyle("B$baris:F$baris")->applyFromArray($styleDTNow);
$objPHPExcel->getActiveSheet()->getStyle("B$baris:F$baris")->applyFromArray($styleArray1);

$objPHPExcel->getActiveSheet()->getStyle("D5:F$baris")
        ->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('PROJECT ERECTION REPORT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="NettReport' . $jobName . '_' . $formattedDate . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>