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

//query mysql, ganti baris ini sesuai dengan query kamu
$projectNameSql = "SELECT MDA.SUBCONT_ID, MDA.PROJECT_NAME, SUM(MDA.WEIGHT * MDA.ASSIGNED_QTY) TOTALASSIGNED "
        . "FROM MASTER_DRAWING_ASSIGNED MDA INNER JOIN MASTER_DRAWING MD "
        . " ON MDA.HEAD_MARK = MD.HEAD_MARK AND DWG_STATUS = 'ACTIVE' WHERE TRUNC(ASSIGNMENT_DATE) = TO_DATE(:DATESELECTED, 'MM/DD/YYYY') "
        . "GROUP BY MDA.PROJECT_NAME, MDA.SUBCONT_ID ORDER BY MDA.SUBCONT_ID ASC";
$projectNameParse = oci_parse($conn, $projectNameSql);
oci_bind_by_name($projectNameParse, ":DATESELECTED", $date1);
oci_execute($projectNameParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("All Drawing Assigned to Subcont on $date1")
        ->setSubject("All Drawing Assigned to Subcont on $date1")
        ->setDescription("All Drawing Assigned to Subcont on $date1")
        ->setKeywords("All Drawing Assigned to")
        ->setCategory("Weltes Information Center");

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SUBCONTRACTOR')
        ->setCellValue('B1', 'PROJECT NUMBER')
        ->setCellValue('C1', 'CLIENT INITIAL')
        ->setCellValue('D1', 'BUILDING')
        ->setCellValue('E1', 'TOTAL WEIGHT');

$baris = 2;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while (($row = oci_fetch_array($projectNameParse, OCI_BOTH)) != false) {

    $projectNumberSql = "SELECT PROJ.PROJECT_NO PROJECTNUMBER, PROJ.CLIENT_ID CLIENTID "
            . "FROM PROJECT PROJ WHERE PROJ.PROJECT_NAME = :PROJNAME";
    $projectNumberParse = oci_parse($conn, $projectNumberSql);
    oci_bind_by_name($projectNumberParse, ":PROJNAME", $row['PROJECT_NAME']);
    oci_define_by_name($projectNumberParse, "PROJECTNUMBER", $projectNo);
    oci_define_by_name($projectNumberParse, "CLIENTID", $client);
    oci_execute($projectNumberParse);
    while (oci_fetch($projectNumberParse)) {
        $projectNo;
        $client;
    }

    $clientInitialSql = "SELECT CLIENT.CLIENT_INITIAL CLIENTINITIAL FROM CLIENT WHERE CLIENT.CLIENT_ID = :CLID";
    $clientInitialParse = oci_parse($conn, $clientInitialSql);
    oci_bind_by_name($clientInitialParse, ":CLID", $client);
    oci_define_by_name($clientInitialParse, "CLIENTINITIAL", $clientInitial);
    oci_execute($clientInitialParse);
    while (oci_fetch($clientInitialParse)) {
        $clientInitial;
    }

    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['SUBCONT_ID'])
            ->setCellValue("B$baris", $projectNo)
            ->setCellValue("C$baris", $clientInitial)
            ->setCellValue("D$baris", $row['PROJECT_NAME'])
            ->setCellValue("E$baris", $row['TOTALASSIGNED']);
    $baris = $baris + 1;
}

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('DRAWING ASSIGNED TO SUBCONT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DrawingAssignedToSubcont_on_' . $date1 . '_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>