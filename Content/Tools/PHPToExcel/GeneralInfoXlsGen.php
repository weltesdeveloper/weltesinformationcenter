<?php

echo 'dalam perbaikan silahkan hubungi gobis jaya';
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


//query mysql, ganti baris ini sesuai dengan query kamu
$generalInfoSql = "SELECT FABRICATION.HEAD_MARK HEADMARK, NVL((SELECT MASTER_DRAWING.COMP_TYPE FROM MASTER_DRAWING WHERE FABRICATION.HEAD_MARK = MASTER_DRAWING.HEAD_MARK),'-') COMP_TYPE, 
                                                NVL(FABRICATION.UNIT_QTY,'0') QTY, NVL(FABRICATION.UNIT_WEIGHT,'0') WEIGHT, NVL(FABRICATION.MARKING,'0') FABMARKING, 
                                                NVL(FABRICATION.CUTTING,'0') FABCUTTING, NVL(FABRICATION.ASSEMBLY,'0') FABASSEMBLY, NVL(FABRICATION.WELDING,'0') FABWELDING, 
                                                NVL(FABRICATION.DRILLING,'0') FABDRILLING, NVL(FABRICATION.FINISHING,'0') FABFINISHING, 
                                                        NVL((FABRICATION.MARKING*0.02*FABRICATION.UNIT_WEIGHT)+(FABRICATION.CUTTING*0.03*FABRICATION.UNIT_WEIGHT)+
                                                        (FABRICATION.ASSEMBLY*0.25*FABRICATION.UNIT_WEIGHT)+(FABRICATION.WELDING*0.3*FABRICATION.UNIT_WEIGHT)+
                                                        (FABRICATION.DRILLING*0.15*FABRICATION.UNIT_WEIGHT)+(FABRICATION.FINISHING*0.25*FABRICATION.UNIT_WEIGHT),'0') TOTALFAB, 
                                                    NVL((SELECT SUM(CURRENT_QC_WEIGHT) FROM FABRICATION_QC_HIST WHERE FABRICATION.HEAD_MARK = FABRICATION_QC_HIST.HEAD_MARK),'0') TOTALFABQC,
                                                    NVL((SELECT PAINTING.BLASTING FROM PAINTING WHERE FABRICATION.HEAD_MARK = PAINTING.HEAD_MARK),'0') BLASTING,
                                                    NVL((SELECT PAINTING.PRIMER FROM PAINTING WHERE FABRICATION.HEAD_MARK = PAINTING.HEAD_MARK),'0') PRIMER,
                                                    NVL((SELECT PAINTING.INTERMEDIATE FROM PAINTING WHERE FABRICATION.HEAD_MARK = PAINTING.HEAD_MARK),'0') INTERM,
                                                    NVL((SELECT PAINTING.FINISHING FROM PAINTING WHERE FABRICATION.HEAD_MARK = PAINTING.HEAD_MARK),'0') PAINTFINISH, 
                                                        NVL((SELECT (PAINTING.BLASTING*UNIT_SURFACE)+(PAINTING.PRIMER*UNIT_SURFACE)+
                                                        (PAINTING.INTERMEDIATE*UNIT_SURFACE)+(PAINTING.FINISHING*UNIT_SURFACE) FROM PAINTING WHERE FABRICATION.HEAD_MARK = PAINTING.HEAD_MARK),'0') TOTALPAINT,
                                                        NVL((SELECT SUM(CURRENT_QC_SURFACE) FROM PAINTING_QC_HIST WHERE FABRICATION.HEAD_MARK = PAINTING_QC_HIST.HEAD_MARK),'0') TOTALPAINTQC,
                                                NVL((SELECT PREPACKING_LIST.COLI_NUMBER FROM PREPACKING_LIST WHERE FABRICATION.HEAD_MARK = PREPACKING_LIST.HEAD_MARK),'-') COLINUM,
                                                NVL((SELECT PREPACKING_LIST.UNIT_QTY FROM PREPACKING_LIST WHERE FABRICATION.HEAD_MARK = PREPACKING_LIST.HEAD_MARK
                                                        AND PREPACKING_LIST.COLI_NUMBER IS NOT NULL),'0') PACKINGQTY,
                                                NVL((SELECT PREPACKING_LIST.UNIT_WEIGHT FROM PREPACKING_LIST WHERE FABRICATION.HEAD_MARK = PREPACKING_LIST.HEAD_MARK 
                                                        AND PREPACKING_LIST.COLI_NUMBER IS NOT NULL),'0') WEIGHTPACK
                                            FROM FABRICATION 
                                            WHERE FABRICATION.PROJECT_NAME = :PROJNAME";
$generalInfoParse = oci_parse($conn, $generalInfoSql);
oci_bind_by_name($generalInfoParse, ":PROJNAME", $_SESSION['cd-dropdown']);
oci_execute($generalInfoParse);

//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("General Info List")
        ->setSubject("General Info List")
        ->setDescription("General Info List")
        ->setKeywords("General Info List")
        ->setCategory("Weltes Information Center");

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'HEAD MARK')
        ->setCellValue('B1', 'COMPONENT')
        ->setCellValue('C1', 'QUANTITY')
        ->setCellValue('D1', 'WEIGHT')
        ->setCellValue('E1', 'MARKING')
        ->setCellValue('F1', 'CUTTING')
        ->setCellValue('G1', 'ASSEMBLY')
        ->setCellValue('H1', 'WELDING')
        ->setCellValue('I1', 'DRILLING')
        ->setCellValue('J1', 'FAB FINISH')
        ->setCellValue('K1', 'TOTAL FAB')
        ->setCellValue('L1', 'TOTAL FAB QC')
        ->setCellValue('M1', 'BLASTING')
        ->setCellValue('N1', 'PRIMER')
        ->setCellValue('O1', 'INTERMEDIATE')
        ->setCellValue('P1', 'PAINT FINISH')
        ->setCellValue('Q1', 'TOTAL PAINT')
        ->setCellValue('R1', 'TOTAL PAINT QC')
        ->setCellValue('S1', 'COLI NUMBER')
        ->setCellValue('T1', 'PACKING QTY')
        ->setCellValue('U1', 'PACKING WEIGHT');


$baris = 2;
$no = 0;
//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while (($row = oci_fetch_array($generalInfoParse, OCI_BOTH)) != false) {
    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $row['HEADMARK'])
            ->setCellValue("B$baris", $row['COMP_TYPE'])
            ->setCellValue("C$baris", $row['QTY'])
            ->setCellValue("D$baris", $row['WEIGHT'])
            ->setCellValue("E$baris", $row['FABMARKING'])
            ->setCellValue("F$baris", $row['FABCUTTING'])
            ->setCellValue("G$baris", $row['FABASSEMBLY'])
            ->setCellValue("H$baris", $row['FABWELDING'])
            ->setCellValue("I$baris", $row['FABDRILLING'])
            ->setCellValue("J$baris", $row['FABFINISHING'])
            ->setCellValue("K$baris", $row['TOTALFAB'])
            ->setCellValue("L$baris", $row['TOTALFABQC'])
            ->setCellValue("M$baris", $row['BLASTING'])
            ->setCellValue("N$baris", $row['PRIMER'])
            ->setCellValue("O$baris", $row['INTERM'])
            ->setCellValue("P$baris", $row['PAINTFINISH'])
            ->setCellValue("Q$baris", $row['TOTALPAINT'])
            ->setCellValue("R$baris", $row['TOTALPAINTQC'])
            ->setCellValue("S$baris", $row['COLINUM'])
            ->setCellValue("T$baris", $row['PACKINGQTY'])
            ->setCellValue("U$baris", $row['WEIGHTPACK']);
    $baris = $baris + 1;
}

// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('GENERAL INFO LIST');

$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("mdY_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="generalInfoList_' . $formattedFileName . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>