<?php

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

$date1 = $_GET['var1'];
$date2 = $_GET['var2'];

$dt1 = new DateTime($date1);
$dt2 = new DateTime($date2);

$projectNameSql = "SELECT PROJECT_NAME,HEAD_MARK,PROFILE,ASG_QTY, "
        . " LENGTH,WEIGHT,SURFACE,ASG_DATE,SUBCONT_ID,ID "
        . " FROM VW_FAB_INFO "
        . " WHERE ASG_DATE >= TO_DATE('$date1 00:00:01', 'MM/DD/YYYY hh24:mi:ss') "
        . " AND ASG_DATE <= TO_DATE ('$date2 23:59:59', 'MM/DD/YYYY hh24:mi:ss') "
        . " AND SUBCONT_ID != 'ANDI' AND SUBCONT_ID != 'EBIT' "
        . " ORDER BY SUBCONT_ID,PROJECT_NAME,HEAD_MARK,ID";
$projectNameParse = oci_parse($conn, $projectNameSql);
oci_execute($projectNameParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("All Drawing Assigned to Subcont between $date1 to $date2")
        ->setSubject("All Drawing Assigned to Subcont between $date1 to $date2")
        ->setDescription("All Drawing Assigned to Subcont between $date1 to $date2")
        ->setKeywords("All Drawing Assigned to")
        ->setCategory("Weltes Information Center");

// TITLE Tabel
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:L1')
        ->setCellValue('A1', 'DWG Assigned to Subcont between ' . $dt1->format('l, F d, Y') . ' to ' . $dt2->format('l, F d, Y'));

// STYle TEXT
$styleTitle = array(
    'font' => array(
        'bold' => true,
        // 'underline' => true,
        'shrinkToFit' => true,
        'size' => 11,
        'name' => 'Trebuchet'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$styleArray1 = array(
    'font' => array(
        // 'bold'  => true,
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

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', 'SUBCONT')
        ->setCellValue('B2', 'PROJECT NAME')
        ->setCellValue('C2', 'HEAD MARK')
        ->setCellValue('D2', 'ID')
        ->setCellValue('E2', 'PROFILE')
        ->setCellValue('F2', 'QTY')
        ->setCellValue('G2', 'LENGTH')
        ->setCellValue('H2', 'WEIGHT')
        ->setCellValue('I2', 'TOT. WEIGHT')
        ->setCellValue('J2', 'AREA')
        ->setCellValue('K2', 'TOT. AREA')
        ->setCellValue('L2', 'ASSIGN DATE')
;

$baris = 3;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($projectNameParse)) {
    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['SUBCONT_ID'])
            ->setCellValue("B$baris", $row['PROJECT_NAME'])
            ->setCellValue("C$baris", $row['HEAD_MARK'])
            ->setCellValue("D$baris", $row['ID'])
            ->setCellValue("E$baris", $row['PROFILE'])
            ->setCellValue("F$baris", $row['ASG_QTY'])
            ->setCellValue("G$baris", $row['LENGTH'])
            ->setCellValue("H$baris", $row['WEIGHT'])
            ->setCellValue("I$baris", $row['WEIGHT'] * $row['ASG_QTY'])
            ->setCellValue("J$baris", $row['SURFACE'])
            ->setCellValue("K$baris", $row['SURFACE'] * $row['ASG_QTY'])
            ->setCellValue("L$baris", $row['ASG_DATE'])
    ;

    // APly style
    $objPHPExcel->getActiveSheet()->getStyle("A2:L$baris")->applyFromArray($styleBorder);
    $baris = $baris + 1;
}
// APPLY TO COLOUMN
$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("A3:L$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("A2:L2")->applyFromArray($styleArray2);

// SET WRAPEP TEXT 
// APPLY TO ARANGE
$objPHPExcel->getActiveSheet()->getStyle('A2:L2')
        ->getAlignment()->setWrapText(true);

// SET WIDTH COLOM PROJECT_NAME
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(20);
// SET WIDTH COLOM HEAD MARK
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(20);

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('DRAWING ASSIGNED TO SUBCONT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DrawingAssignedToSubcont_between_' . $date1 . '-' . $date2 . '_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>