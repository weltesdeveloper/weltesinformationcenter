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

$projectName = $_GET['var1'];

//query mysql, ganti baris ini sesuai dengan query kamu
$subcontListSql = "SELECT SUBCONTRACTOR.SUBCONT_ID, SUBCONTRACTOR.SUBCONT_STATUS FROM SUBCONTRACTOR ORDER BY SUBCONT_STATUS ASC";
$subcontListParse = oci_parse($conn, $subcontListSql);
oci_execute($subcontListParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Subcontractor Report for $projectName")
        ->setSubject("Subcontractor Report for $projectName")
        ->setDescription("Subcontractor Report for $projectName")
        ->setKeywords("Subcontractor Report")
        ->setCategory("Weltes Information Center");

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SUBCONTRACTOR')
        ->setCellValue('B1', 'STATUS')
        ->setCellValue('C1', 'TONNAGE')
        ->setCellValue('D1', 'TODAY')
        ->setCellValue('E1', 'YESTERDAY')
        ->setCellValue('F1', '2 DAYS AGO')
        ->setCellValue('G1', '3 DAYS AGO')
        ->setCellValue('H1', '4 DAYS AGO')
        ->setCellValue('I1', '5 DAYS AGO')
        ->setCellValue('J1', '6 DAYS AGO')
        ->setCellValue('K1', 'AVERAGE THIS WEEK');

$baris = 2;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while (($row = oci_fetch_array($subcontListParse, OCI_BOTH)) != false) {

    if ($row['SUBCONT_STATUS'] == 'OUTSOURCE') {
        $subcontStatus = 'OUTSOURCE';
    } else {
        $subcontStatus = 'IN HOUSE';
    }

    $subcontTotalTonnSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONT, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnParse = oci_parse($conn, $subcontTotalTonnSql);
    oci_bind_by_name($subcontTotalTonnParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnParse, "CURRENTTONNSUBCONT", $tonnSubcont);
    oci_execute($subcontTotalTonnParse);
    while (oci_fetch($subcontTotalTonnParse)) {
        $tonnSubcont;
    }

    $subcontTotalTonnTodaySql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTTODAY, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnTodayParse = oci_parse($conn, $subcontTotalTonnTodaySql);
    oci_bind_by_name($subcontTotalTonnTodayParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnTodayParse, "CURRENTTONNSUBCONTTODAY", $tonnSubcontToday);
    oci_execute($subcontTotalTonnTodayParse);
    while (oci_fetch($subcontTotalTonnTodayParse)) {
        $tonnSubcontToday;
    }

    $subcontTotalTonnYesterdaySql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTYESTERDAY, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 1, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnYesterdayParse = oci_parse($conn, $subcontTotalTonnYesterdaySql);
    oci_bind_by_name($subcontTotalTonnYesterdayParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnYesterdayParse, "CURRENTTONNSUBCONTYESTERDAY", $tonnSubcontYesterday);
    oci_execute($subcontTotalTonnYesterdayParse);
    while (oci_fetch($subcontTotalTonnYesterdayParse)) {
        $tonnSubcontYesterday;
    }

    $subcontTotalTonnTwoDaysSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTTWODAYS, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 2, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnTwoDaysParse = oci_parse($conn, $subcontTotalTonnTwoDaysSql);
    oci_bind_by_name($subcontTotalTonnTwoDaysParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnTwoDaysParse, "CURRENTTONNSUBCONTTWODAYS", $tonnSubcontTwoDays);
    oci_execute($subcontTotalTonnTwoDaysParse);
    while (oci_fetch($subcontTotalTonnTwoDaysParse)) {
        $tonnSubcontTwoDays;
    }

    $subcontTotalTonnThreeDaysSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTTHREEDAYS, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 3, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnThreeDaysParse = oci_parse($conn, $subcontTotalTonnThreeDaysSql);
    oci_bind_by_name($subcontTotalTonnThreeDaysParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnThreeDaysParse, "CURRENTTONNSUBCONTTHREEDAYS", $tonnSubcontThreeDays);
    oci_execute($subcontTotalTonnThreeDaysParse);
    while (oci_fetch($subcontTotalTonnThreeDaysParse)) {
        $tonnSubcontThreeDays;
    }

    $subcontTotalTonnFourDaysSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTFOURDAYS, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 4, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnFourDaysParse = oci_parse($conn, $subcontTotalTonnFourDaysSql);
    oci_bind_by_name($subcontTotalTonnFourDaysParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnFourDaysParse, "CURRENTTONNSUBCONTFOURDAYS", $tonnSubcontFourDays);
    oci_execute($subcontTotalTonnFourDaysParse);
    while (oci_fetch($subcontTotalTonnFourDaysParse)) {
        $tonnSubcontFourDays;
    }

    $subcontTotalTonnFiveDaysSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTFIVEDAYS, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 5, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnFiveDaysParse = oci_parse($conn, $subcontTotalTonnFiveDaysSql);
    oci_bind_by_name($subcontTotalTonnFiveDaysParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnFiveDaysParse, "CURRENTTONNSUBCONTFIVEDAYS", $tonnSubcontFiveDays);
    oci_execute($subcontTotalTonnFiveDaysParse);
    while (oci_fetch($subcontTotalTonnFiveDaysParse)) {
        $tonnSubcontFiveDays;
    }

    $subcontTotalTonnSixDaysSql = "SELECT SUM(FAB.WEIGHT) AS CURRENTTONNSUBCONTSIXDAYS, FAB.SUBCONT_ID "
            . "FROM FABRICATION_HIST, VW_FAB_INFO FAB WHERE FAB.HEAD_MARK = FABRICATION_HIST.HEAD_MARK "
            . "AND TO_CHAR(FAB_ENTRY_DATE, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 6, 'MM/DD/YYYY') "
            . "AND FAB.PROJECT_NAME = :PROJNAME AND FAB.SUBCONT_ID = '$row[SUBCONT_ID]' "
            . "GROUP BY FAB.SUBCONT_ID";
    $subcontTotalTonnSixDaysParse = oci_parse($conn, $subcontTotalTonnSixDaysSql);
    oci_bind_by_name($subcontTotalTonnSixDaysParse, ":PROJNAME", $projectName);
    oci_define_by_name($subcontTotalTonnSixDaysParse, "CURRENTTONNSUBCONTSIXDAYS", $tonnSubcontSixDays);
    oci_execute($subcontTotalTonnSixDaysParse);
    while (oci_fetch($subcontTotalTonnSixDaysParse)) {
        $tonnSubcontSixDays;
    }

    $averageSubcontProdThisWeek = ($tonnSubcontToday + $tonnSubcontYesterday + $tonnSubcontTwoDays + $tonnSubcontThreeDays +
            $tonnSubcontFourDays + $tonnSubcontFiveDays + $tonnSubcontSixDays) / 7;

    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['SUBCONT_ID'])
            ->setCellValue("B$baris", $subcontStatus)
            ->setCellValue("C$baris", number_format($tonnSubcont, 2))
            ->setCellValue("D$baris", number_format($tonnSubcontToday, 2))
            ->setCellValue("E$baris", number_format($tonnSubcontYesterday, 2))
            ->setCellValue("F$baris", number_format($tonnSubcontTwoDays, 2))
            ->setCellValue("G$baris", number_format($tonnSubcontThreeDays, 2))
            ->setCellValue("H$baris", number_format($tonnSubcontFourDays, 2))
            ->setCellValue("I$baris", number_format($tonnSubcontFiveDays, 2))
            ->setCellValue("J$baris", number_format($tonnSubcontSixDays, 2))
            ->setCellValue("K$baris", number_format($averageSubcontProdThisWeek, 2));

    $baris = $baris + 1;
}

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('SUBCONTRACTOR REPORT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Subcontractor_Report_For_' . $projectName . '_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>