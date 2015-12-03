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

$projectNameSql = "SELECT DISTINCT(HEAD_MARK),ID,UNIT_WEIGHT,UNIT_QTY,TOTAL_QTY,ASSG_DATE,SUBCONT_ID,PROJECT_NAME,COMP_TYPE,
                         SUM(MARKING) AS MARK,
                         SUM(CUTTING) AS CUT,
                         SUM(ASSEMBLY) AS ASSY,
                         SUM(WELDING) AS WELD,
                         SUM(DRILLING) AS DRILL,
                         SUM(FINISHING) AS FINISH 
                  FROM VW_PROD_FAB
                  WHERE FAB_ENTRY_DATE >= TO_DATE('$date1 00:00:01', 'MM/DD/YYYY hh24:mi:ss') AND FAB_ENTRY_DATE <= TO_DATE ('$date2 23:59:59', 'MM/DD/YYYY hh24:mi:ss') 
                      AND COMP_TYPE NOT IN('END PLATE') 
                  GROUP BY HEAD_MARK,ID,UNIT_WEIGHT,UNIT_QTY,TOTAL_QTY,ASSG_DATE,SUBCONT_ID,PROJECT_NAME,COMP_TYPE
                  ORDER BY SUBCONT_ID,PROJECT_NAME,COMP_TYPE,HEAD_MARK,ID";
$projectNameParse = oci_parse($conn, $projectNameSql);
oci_execute($projectNameParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Daily Production Report")
        ->setSubject("Daily Production Report")
        ->setDescription("Daily Production Report")
        ->setKeywords("Daily Production Report")
        ->setCategory("Weltes Information Center");

// TITLE Tabel
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:V1')
        ->setCellValue('A1', 'DWG Production Report between ' . $dt1->format('l, F d, Y') . ' to ' . $dt2->format('l, F d, Y'));

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
        ->setCellValue('C2', 'COMP TYPE')
        ->setCellValue('D2', 'HEAD MARK')
        ->setCellValue('E2', 'ID')
        ->setCellValue('F2', 'UNIT WEIGHT')
        ->setCellValue('G2', 'TOT QTY')
        ->setCellValue('H2', 'ASSG QTY')
        ->setCellValue('I2', 'MARK')
        ->setCellValue('J2', 'MARK WT 2%')
        ->setCellValue('K2', 'CUT')
        ->setCellValue('L2', 'CUT WT 3%')
        ->setCellValue('M2', 'ASSY')
        ->setCellValue('N2', 'ASSY WT 25%')
        ->setCellValue('O2', 'WELD')
        ->setCellValue('P2', 'WELD WT 30%')
        ->setCellValue('Q2', 'DRILL')
        ->setCellValue('R2', 'DRILL 15%')
        ->setCellValue('S2', 'FIN')
        ->setCellValue('T2', 'FIN WT 25%')
        ->setCellValue('U2', 'SUB TOT WEIGHT')
        ->setCellValue('V2', 'ASSIGN DATE');

$baris = 3;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($projectNameParse)) {
    $no = $no + 1;

    // hitung PROSENTASE
    $ASSG_QTY = $row['UNIT_QTY'];
    $UNIT_WEIGHT = $row['UNIT_WEIGHT'];
    // $ASSG_QTY_TOT      += $ASSG_QTY;
    $ASSG_WT = round($UNIT_WEIGHT * $ASSG_QTY, 1);
    // $ASSG_WT_TOT       += $ASSG_WT;

    $MARK = $row['MARK'];
    // $MARK_TOT          += $MARK;
    $MARK_WT = round(($MARK * 2 / 100 * $UNIT_WEIGHT), 1);
    // $MARK_WT_TOT       += $MARK_WT; 

    $CUT = $row['CUT'];
    // $CUT_TOT           += $CUT;
    $CUT_WT = round(($CUT * 3 / 100 * $UNIT_WEIGHT), 1);
    // $CUT_WT_TOT        += $CUT_WT;

    $ASSY = $row['ASSY'];
    // $ASSY_TOT          += $ASSY;
    $ASSY_WT = round(($ASSY * 25 / 100 * $UNIT_WEIGHT), 1);
    // $ASSY_WT_TOT       += $ASSY_WT; 

    $WELD = $row['WELD'];
    // $WELD_TOT          += $WELD;
    $WELD_WT = round(($WELD * 30 / 100 * $UNIT_WEIGHT), 1);
    // $WELD_WT_TOT       += $WELD_WT; 

    $DRILL = $row['DRILL'];
    // $DRILL_TOT         += $DRILL; 
    $DRILL_WT = round(($DRILL * 15 / 100 * $UNIT_WEIGHT), 1);
    // $DRILL_WT_TOT      += $DRILL_WT;

    $FINISH = $row['FINISH'];
    // $FINISH_TOT        += $FINISH; 
    $FINISH_WT = round(($FINISH * 25 / 100 * $UNIT_WEIGHT), 1);
    // $FINISH_WT_TOT     += $FINISH_WT;

    $SUB_TOT_WT = $MARK_WT + $CUT_WT + $ASSY_WT + $WELD_WT + $DRILL_WT + $FINISH_WT;
    // $TOT_WT            += $SUB_TOT_WT;
    $ASSG_DATE = $row['ASSG_DATE'];

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['SUBCONT_ID'])
            ->setCellValue("B$baris", $row['PROJECT_NAME'])
            ->setCellValue("C$baris", $row['COMP_TYPE'])
            ->setCellValue("D$baris", $row['HEAD_MARK'])
            ->setCellValue("E$baris", $row['ID'])
            ->setCellValue("F$baris", $row['UNIT_WEIGHT'])
            ->setCellValue("G$baris", $row['TOTAL_QTY'])
            ->setCellValue("H$baris", $ASSG_QTY . " ($ASSG_WT)")
            ->setCellValue("I$baris", $MARK)
            ->setCellValue("J$baris", $MARK_WT)
            ->setCellValue("K$baris", $CUT)
            ->setCellValue("L$baris", $CUT_WT)
            ->setCellValue("M$baris", $ASSY)
            ->setCellValue("N$baris", $ASSY_WT)
            ->setCellValue("O$baris", $WELD)
            ->setCellValue("P$baris", $WELD_WT)
            ->setCellValue("Q$baris", $DRILL)
            ->setCellValue("R$baris", $DRILL_WT)
            ->setCellValue("S$baris", $FINISH)
            ->setCellValue("T$baris", $FINISH_WT)
            ->setCellValue("U$baris", $SUB_TOT_WT)
            ->setCellValue("V$baris", $ASSG_DATE)
    ;

    // APly style
    $objPHPExcel->getActiveSheet()->getStyle("A2:V$baris")->applyFromArray($styleBorder);
    $baris = $baris + 1;
}
// APPLY TO COLOUMN
$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("A3:V$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("A2:V2")->applyFromArray($styleArray2);

// SET WRAPEP TEXT 
// APPLY TO ARANGE
$objPHPExcel->getActiveSheet()->getStyle('A2:V2')
        ->getAlignment()->setWrapText(true);

// SET WIDTH COLOM HEAD MARK
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(20);
// SET WIDTH COLOM PROJ NAME
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(20);


// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('PRODUCTION LIST');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("mdY_h:i", time());
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DailyProductionList_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>