<?php

echo 'masalah .. hub developer';
exit();
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

$projectValSQL = "PROJECT_NAME LIKE '%'";

if ($_GET["projData"] <> "ALL") {
    # code...
    $projectValSQL = 'PROJECT_NAME in ';
    $projNM = '(';
    list($proj_no, $proj_code) = explode("^", $_GET["projData"]);
    if ($proj_code == "ALL") {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    } else {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' AND PROJECT_CODE='$proj_code' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    }

    $projectNameParse = oci_parse($conn, $projectNameSql);
    oci_execute($projectNameParse);
    while ($projectNameROW = oci_fetch_array($projectNameParse)) {
        $projNM .= "'" . $projectNameROW['PROJECT_NAME'] . "',";
    }
    $projectValSQL .= substr_replace($projNM, "", -1) . ")";
    // echo "$projectValSQL";
}

$dt1 = new DateTime($date1);
$dt2 = new DateTime($date2);

//query mysql, ganti baris ini sesuai dengan query kamu
$projectNameSql = "SELECT DISTINCT(PQC.PROJECT_NAME),PQC.HEAD_MARK,
                      (
                        SELECT SUM(PAINT_QC_PASS) FROM PAINTING_QC WHERE HEAD_MARK=PQC.HEAD_MARK
                      ) AS QCPASS_QTY,
                      UNIT_SURFACE AS QCPASS_SURF,
                      SUM(
                        CASE
                          WHEN
                            POP.OPN_TYPE='BLAST'
                          THEN
                            POP.OPN_QTY
                          ELSE
                            0 
                        END
                      ) AS OPN_QTY_BLS,
                      SUM(
                        CASE
                          WHEN
                            POP.OPN_TYPE='BLAST'
                          THEN
                            POP.OPN_QTY*PQC.UNIT_SURFACE
                          ELSE
                            0 
                        END
                      ) AS OPN_SURF_BLS,
                      SUM(
                        CASE
                          WHEN
                            POP.OPN_TYPE='PAINT'
                          THEN
                            POP.OPN_QTY
                          ELSE
                            0 
                        END
                      ) AS OPN_QTY_PNT,
                      SUM(
                        CASE
                          WHEN
                            POP.OPN_TYPE='PAINT'
                          THEN
                            POP.OPN_QTY*PQC.UNIT_SURFACE
                          ELSE
                            0 
                        END
                      ) AS OPN_SURF_PNT
                  FROM PAINTING_QC PQC, PAINTING_OPN POP 
                  WHERE 
                    PQC.HEAD_MARK=POP.HEAD_MARK AND PQC.PROJECT_NAME=POP.PROJECT_NAME AND PQC.ID=POP.ID 
                    AND OPN_DATE >= TO_DATE('$date1', 'MM/DD/YYYY') AND OPN_DATE <= TO_DATE ('$date2', 'MM/DD/YYYY')
                  GROUP BY PQC.PROJECT_NAME,PQC.HEAD_MARK,UNIT_SURFACE
                  ORDER BY PQC.PROJECT_NAME,PQC.HEAD_MARK";
$projectNameParse = oci_parse($conn, $projectNameSql);
oci_execute($projectNameParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Daily Opname Report")
        ->setSubject("Daily Opname Report")
        ->setDescription("Daily Opname Report")
        ->setKeywords("Daily Opname Report")
        ->setCategory("Weltes Information Center");

// TITLE Tabel
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:K1')
        ->setCellValue('A1', 'DWG Painting Opname Report between ' . $dt1->format('l, F d, Y') . ' to ' . $dt2->format('l, F d, Y'));
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A2:K2')
        ->setCellValue('A2', 'PROJECT : ' . $_GET["projData"]);

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
        ->setCellValue('B3', 'PROJECT NAME')
        ->setCellValue('C3', 'HEAD MARK')
        ->setCellValue('D3', 'TOT QTY')
        ->setCellValue('E3', 'UNIT SURF')
        ->setCellValue('F3', 'TOT SURF')
        ->setCellValue('G3', 'BLAST OPN QTY')
        ->setCellValue('H3', 'BLAST OPN SURF')
        ->setCellValue('I3', 'PAINT OPN QTY')
        ->setCellValue('J3', 'PAINT OPN SURF');

$baris = 4;
$no = 0;

$TOT_OPN_QTY_BLS = 0;
$TOT_OPN_SURF_BLS = 0;
$TOT_OPN_QTY_PNT = 0;
$TOT_OPN_SURF_PNT = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=3 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($projectNameParse)) {
    $no = $no + 1;

    // hitung PROSENTASE
    $SUBTOT_OPN_SURF = round($row['QCPASS_QTY'], 1) * round($row['QCPASS_SURF'], 1);

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $row['PROJECT_NAME'])
            ->setCellValue("C$baris", $row['HEAD_MARK'])
            ->setCellValue("D$baris", $row['QCPASS_QTY'])
            ->setCellValue("E$baris", $row['QCPASS_SURF'])
            ->setCellValue("F$baris", $SUBTOT_OPN_SURF)
            ->setCellValue("G$baris", $row['OPN_QTY_BLS'])
            ->setCellValue("H$baris", $row['OPN_SURF_BLS'])
            ->setCellValue("I$baris", $row['OPN_QTY_PNT'])
            ->setCellValue("J$baris", $row['OPN_SURF_PNT'])
    ;

    // APly style
    $objPHPExcel->getActiveSheet()->getStyle("B3:J$baris")->applyFromArray($styleBorder);
    $baris = $baris + 1;

    // SUMMARy
    $TOT_OPN_QTY_BLS += $row['OPN_QTY_BLS'];
    $TOT_OPN_SURF_BLS += $row['OPN_SURF_BLS'];
    $TOT_OPN_QTY_PNT += $row['OPN_QTY_PNT'];
    $TOT_OPN_SURF_PNT += $row['OPN_SURF_PNT'];
}
// APPLY TO COLOUMN
$objPHPExcel->getActiveSheet()->getStyle("A1:A2")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("B4:J$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("B3:J3")->applyFromArray($styleArray2);

// SUMMARY
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G$baris", $TOT_OPN_QTY_BLS)
        ->setCellValue("H$baris", $TOT_OPN_SURF_BLS)
        ->setCellValue("I$baris", $TOT_OPN_QTY_PNT)
        ->setCellValue("J$baris", $TOT_OPN_SURF_PNT)
;
// SUMARY Tabel
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("B$baris:F$baris")
        ->setCellValue("B$baris", 'TOTAL');
// APly style SUMMARY
$objPHPExcel->getActiveSheet()->getStyle("B$baris:J$baris")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B$baris:J$baris")->applyFromArray($styleArray2);


// SET WRAPEP TEXT 
// APPLY TO ARANGE
$objPHPExcel->getActiveSheet()->getStyle('B3:J3')
        ->getAlignment()->setWrapText(true);

// SET WIDTH COLOM HEAD MARK
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(20);
// SET WIDTH COLOM PROJ NAME
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(20);


// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('OPNAME LIST');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("mdY_h:i", time());
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DailyOpnameList_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>