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

//SELECT CLient Description
$sqlPROJ = "SELECT PROJECT_NO, CLIENT_NAME, CLIENT_INIT
          FROM MST_CLIENT
               INNER JOIN PROJECT ON (MST_CLIENT.CLIENT_ID = PROJECT.CLIENT_ID)
          WHERE PROJECT_NAME='$projectName'";
$parsePROJ = oci_parse($conn, $sqlPROJ);
oci_execute($parsePROJ);
$rowPROJ = oci_fetch_array($parsePROJ);

//query mysql, ganti baris ini sesuai dengan query kamu
$compProfileSql = "WITH MD
                            AS (  SELECT COMP_TYPE, SUM (TOTAL_QTY * WEIGHT) TOTAL_WEIGHT
                                    FROM MASTER_DRAWING
                                   WHERE PROJECT_NAME = '$projectName' AND DWG_STATUS = 'ACTIVE'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            ASSG
                            AS (  SELECT COMP_TYPE, SUM (ASSG_QTY * WEIGHT) ASSG_WEIGHT
                                    FROM COMP_VW_INFO
                                   WHERE PROJECT_NAME = '$projectName'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            FAB
                            AS (  SELECT CVI.COMP_TYPE,
                                         SUM (CVI.MARK * CVI.WEIGHT) MARKING,
                                         SUM (CVI.CUT * CVI.WEIGHT) CUTTING,
                                         SUM (CVI.ASSY * CVI.WEIGHT) ASSEMBLY,
                                         SUM (CVI.WELD * CVI.WEIGHT) WELDING,
                                         SUM (CVI.DRILL * CVI.WEIGHT) DRILLING,
                                         SUM (CVI.JML_FAB * CVI.WEIGHT) FAB_FINS,
                                         SUM (CVI.FAB_QCPASS * CVI.WEIGHT) FAB_QCPASS,
                                         SUM (CVI.BLAST * CVI.WEIGHT) BLAST,
                                         SUM (CVI.PRIMER * CVI.WEIGHT) PRIMER,
                                         SUM (CVI.INTMD * CVI.WEIGHT) INTMD,
                                         SUM (CVI.TOP_COAT * CVI.WEIGHT) TOP_COAT,
                                         SUM (CVI.PNT_QCPASS * CVI.WEIGHT) PNT_QCPASS
                                    FROM COMP_VW_INFO CVI
                                   WHERE CVI.PROJECT_NAME = '$projectName'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            PCK
                            AS (  SELECT COMP_TYPE, SUM (CVIP.UNIT_PCK_QTY * CVIP.WEIGHT) PACK_WEIGHT
                                    FROM COMP_VW_INFO_PCK CVIP
                                   WHERE CVIP.PROJECT_NAME = '$projectName'
                                GROUP BY CVIP.COMP_TYPE
                                ORDER BY CVIP.COMP_TYPE)
                       SELECT MD.COMP_TYPE,
                              NVL (MD.TOTAL_WEIGHT, 0) TOTAL_QTY,
                              NVL (ASSG.ASSG_WEIGHT, 0) TOTAL_ASSG,
                              NVL (FAB.MARKING, 0) MARKING,
                              NVL (FAB.CUTTING, 0) CUTTING,
                              NVL (FAB.ASSEMBLY, 0) ASSEMBLY,
                              NVL (FAB.WELDING, 0) WELDING,
                              NVL (FAB.DRILLING, 0) DRILLING,
                              NVL (FAB.FAB_FINS, 0) FAB_FINS,
                              NVL (FAB.FAB_QCPASS, 0) FAB_QCPASS,
                              NVL (FAB.BLAST, 0) BLAST,
                              NVL (FAB.PRIMER, 0) PRIMER,
                              NVL (FAB.INTMD, 0) INTMD,
                              NVL (FAB.TOP_COAT, 0) TOP_COAT,
                              NVL (FAB.PNT_QCPASS, 0) PNT_QCPASS,
                              NVL (PCK.PACK_WEIGHT, 0) PACK_QTY,
                              NVL (PCK.PACK_WEIGHT, 0) DLV
                         FROM MD
                              LEFT JOIN ASSG ON ASSG.COMP_TYPE = MD.COMP_TYPE
                              LEFT JOIN FAB ON FAB.COMP_TYPE = ASSG.COMP_TYPE
                              LEFT JOIN PCK ON PCK.COMP_TYPE = FAB.COMP_TYPE";
$compProfileParse = oci_parse($conn, $compProfileSql);
//oci_bind_by_name($compProfileParse, ":PROJNAME", $projectName);
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

$styleRight = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleColor = array(
    'font' => array(
//        'bold' => true,
        'color' => array('rgb' => 'FF0000')
//        'size' => 15,
//        'name' => 'Verdana'
    )
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
            ->setCellValue("D$baris", round($row['TOTAL_QTY'], 2))
            ->setCellValue("E$baris", round($row['TOTAL_ASSG'], 2))
            ->setCellValue("F$baris", round($row['TOTAL_QTY']-$row['TOTAL_ASSG'], 2))
            ->setCellValue("G$baris", round($row['MARKING'], 2))
            ->setCellValue("H$baris", round($row['CUTTING'], 2))
            ->setCellValue("I$baris", round($row['ASSEMBLY'], 2))
            ->setCellValue("J$baris", round($row['WELDING'], 2))
            ->setCellValue("K$baris", round($row['DRILLING'], 2))
            ->setCellValue("L$baris", round($row['FAB_FINS'], 2))
            ->setCellValue("M$baris", round($row['FAB_QCPASS'], 2))
            ->setCellValue("N$baris", round($row['BLAST'], 2))
            ->setCellValue("O$baris", round($row['PRIMER'], 2))
            ->setCellValue("P$baris", round($row['INTMD'], 2))
            ->setCellValue("Q$baris", round($row['TOP_COAT'], 2))
            ->setCellValue("R$baris", round($row['PNT_QCPASS'], 2))
            ->setCellValue("S$baris", round($row['PACK_QTY'], 2))
            ->setCellValue("T$baris", round($row['PACK_QTY'], 2));

    $objPHPExcel->getActiveSheet()->getStyle("B4:U$baris")->applyFromArray($styleBorder);
    //KASIH WARNA
    if (($row['TOTAL_ASSG']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("E$baris")->applyFromArray($styleColor);
    }
    if (($row['TOTAL_QTY'] - $row['TOTAL_ASSG']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("F$baris")->applyFromArray($styleColor);
    }
    if (($row['MARKING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("G$baris")->applyFromArray($styleColor);
    }
    if (($row['CUTTING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("H$baris")->applyFromArray($styleColor);
    }
    if (($row['ASSEMBLY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("I$baris")->applyFromArray($styleColor);
    }
    if (($row['WELDING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("J$baris")->applyFromArray($styleColor);
    }
    if (($row['DRILLING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("K$baris")->applyFromArray($styleColor);
    }
    if (($row['FAB_FINS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("L$baris")->applyFromArray($styleColor);
    }
    if (($row['FAB_QCPASS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("M$baris")->applyFromArray($styleColor);
    }
    if (($row['BLAST']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("N$baris")->applyFromArray($styleColor);
    }
    if (($row['PRIMER']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("O$baris")->applyFromArray($styleColor);
    }
    if (($row['INTMD']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("P$baris")->applyFromArray($styleColor);
    }
    if (($row['TOP_COAT']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("Q$baris")->applyFromArray($styleColor);
    }
    if (($row['PNT_QCPASS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("R$baris")->applyFromArray($styleColor);
    }
    if (($row['PACK_QTY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("S$baris")->applyFromArray($styleColor);
    }
    if (($row['PACK_QTY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("T$baris")->applyFromArray($styleColor);
    }
    $baris = $baris + 1;
}

$compProfileSql = "WITH MD
                            AS (  SELECT COMP_TYPE, SUM (TOTAL_QTY * WEIGHT) TOTAL_WEIGHT
                                    FROM MASTER_DRAWING
                                   WHERE PROJECT_NAME = '$projectName' AND DWG_STATUS = 'ACTIVE'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            ASSG
                            AS (  SELECT COMP_TYPE, SUM (ASSG_QTY * WEIGHT) ASSG_WEIGHT
                                    FROM COMP_VW_INFO
                                   WHERE PROJECT_NAME = '$projectName'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            FAB
                            AS (  SELECT CVI.COMP_TYPE,
                                         SUM (CVI.MARK * CVI.WEIGHT) MARKING,
                                         SUM (CVI.CUT * CVI.WEIGHT) CUTTING,
                                         SUM (CVI.ASSY * CVI.WEIGHT) ASSEMBLY,
                                         SUM (CVI.WELD * CVI.WEIGHT) WELDING,
                                         SUM (CVI.DRILL * CVI.WEIGHT) DRILLING,
                                         SUM (CVI.JML_FAB * CVI.WEIGHT) FAB_FINS,
                                         SUM (CVI.FAB_QCPASS * CVI.WEIGHT) FAB_QCPASS,
                                         SUM (CVI.BLAST * CVI.WEIGHT) BLAST,
                                         SUM (CVI.PRIMER * CVI.WEIGHT) PRIMER,
                                         SUM (CVI.INTMD * CVI.WEIGHT) INTMD,
                                         SUM (CVI.TOP_COAT * CVI.WEIGHT) TOP_COAT,
                                         SUM (CVI.PNT_QCPASS * CVI.WEIGHT) PNT_QCPASS
                                    FROM COMP_VW_INFO CVI
                                   WHERE CVI.PROJECT_NAME = '$projectName'
                                GROUP BY COMP_TYPE
                                ORDER BY COMP_TYPE),
                            PCK
                            AS (  SELECT COMP_TYPE, SUM (CVIP.UNIT_PCK_QTY * CVIP.WEIGHT) PACK_WEIGHT
                                    FROM COMP_VW_INFO_PCK CVIP
                                   WHERE CVIP.PROJECT_NAME = '$projectName'
                                GROUP BY CVIP.COMP_TYPE
                                ORDER BY CVIP.COMP_TYPE)
                       SELECT --MD.COMP_TYPE,
                              NVL (SUM(MD.TOTAL_WEIGHT), 0) TOTAL_QTY,
                              NVL (SUM(ASSG.ASSG_WEIGHT), 0) TOTAL_ASSG,
                              NVL (SUM(FAB.MARKING), 0) MARKING,
                              NVL (SUM(FAB.CUTTING), 0) CUTTING,
                              NVL (SUM(FAB.ASSEMBLY), 0) ASSEMBLY,
                              NVL (SUM(FAB.WELDING), 0) WELDING,
                              NVL (SUM(FAB.DRILLING), 0) DRILLING,
                              NVL (SUM(FAB.FAB_FINS), 0) FAB_FINS,
                              NVL (SUM(FAB.FAB_QCPASS), 0) FAB_QCPASS,
                              NVL (SUM(FAB.BLAST), 0) BLAST,
                              NVL (SUM(FAB.PRIMER), 0) PRIMER,
                              NVL (SUM(FAB.INTMD), 0) INTMD,
                              NVL (SUM(FAB.TOP_COAT), 0) TOP_COAT,
                              NVL (SUM(FAB.PNT_QCPASS), 0) PNT_QCPASS,
                              NVL (SUM(PCK.PACK_WEIGHT), 0) PACK_QTY,
                              NVL (SUM(PCK.PACK_WEIGHT), 0) DLV
                         FROM MD
                              LEFT JOIN ASSG ON ASSG.COMP_TYPE = MD.COMP_TYPE
                              LEFT JOIN FAB ON FAB.COMP_TYPE = ASSG.COMP_TYPE
                              LEFT JOIN PCK ON PCK.COMP_TYPE = FAB.COMP_TYPE";
$compProfileParse = oci_parse($conn, $compProfileSql);
//oci_bind_by_name($compProfileParse, ":PROJNAME", $projectName);
oci_execute($compProfileParse);

while ($row = oci_fetch_array($compProfileParse)) {
//    $no = $no + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells("B$baris:C$baris")
            ->setCellValue("B$baris", "SUMMARY")
            ->setCellValue("D$baris", round($row['TOTAL_QTY'], 2))
            ->setCellValue("E$baris", round($row['TOTAL_ASSG'], 2))
            ->setCellValue("F$baris", round($row['TOTAL_QTY']-$row['TOTAL_ASSG'], 2))
            ->setCellValue("G$baris", round($row['MARKING'], 2))
            ->setCellValue("H$baris", round($row['CUTTING'], 2))
            ->setCellValue("I$baris", round($row['ASSEMBLY'], 2))
            ->setCellValue("J$baris", round($row['WELDING'], 2))
            ->setCellValue("K$baris", round($row['DRILLING'], 2))
            ->setCellValue("L$baris", round($row['FAB_FINS'], 2))
            ->setCellValue("M$baris", round($row['FAB_QCPASS'], 2))
            ->setCellValue("N$baris", round($row['BLAST'], 2))
            ->setCellValue("O$baris", round($row['PRIMER'], 2))
            ->setCellValue("P$baris", round($row['INTMD'], 2))
            ->setCellValue("Q$baris", round($row['TOP_COAT'], 2))
            ->setCellValue("R$baris", round($row['PNT_QCPASS'], 2))
            ->setCellValue("S$baris", round($row['PACK_QTY'], 2))
            ->setCellValue("T$baris", round($row['PACK_QTY'], 2));

    $objPHPExcel->getActiveSheet()->getStyle("B4:U$baris")->applyFromArray($styleBorder);
    //KASIH WARNA
    if (($row['TOTAL_ASSG']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("E$baris")->applyFromArray($styleColor);
    }
    if (($row['TOTAL_QTY'] - $row['TOTAL_ASSG']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("F$baris")->applyFromArray($styleColor);
    }
    if (($row['MARKING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("G$baris")->applyFromArray($styleColor);
    }
    if (($row['CUTTING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("H$baris")->applyFromArray($styleColor);
    }
    if (($row['ASSEMBLY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("I$baris")->applyFromArray($styleColor);
    }
    if (($row['WELDING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("J$baris")->applyFromArray($styleColor);
    }
    if (($row['DRILLING']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("K$baris")->applyFromArray($styleColor);
    }
    if (($row['FAB_FINS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("L$baris")->applyFromArray($styleColor);
    }
    if (($row['FAB_QCPASS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("M$baris")->applyFromArray($styleColor);
    }
    if (($row['BLAST']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("N$baris")->applyFromArray($styleColor);
    }
    if (($row['PRIMER']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("O$baris")->applyFromArray($styleColor);
    }
    if (($row['INTMD']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("P$baris")->applyFromArray($styleColor);
    }
    if (($row['TOP_COAT']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("Q$baris")->applyFromArray($styleColor);
    }
    if (($row['PNT_QCPASS']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("R$baris")->applyFromArray($styleColor);
    }
    if (($row['PACK_QTY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("S$baris")->applyFromArray($styleColor);
    }
    if (($row['PACK_QTY']) != $row['TOTAL_QTY']) {
        $objPHPExcel->getActiveSheet()->getStyle("T$baris")->applyFromArray($styleColor);
    }
    $baris = $baris + 1;
}
// SET WRAPEP TEXT 
// APPLY TO ARANGE
$objPHPExcel->getActiveSheet()->getStyle('B4:S4')
        ->getAlignment()->setWrapText(true);

// APPLY TO COLOUMN
// $objPHPExcel->getActiveSheet()->getStyle('B4:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
//     ->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle("B1:B1")->applyFromArray($styleTitle);
$objPHPExcel->getActiveSheet()->getStyle("C4:C$baris")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("B4:T4")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("B2:B3")->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle("P3:S3")->applyFromArray($styleDTNow);
$objPHPExcel->getActiveSheet()->getStyle("D5:T$baris")->applyFromArray($styleRight);

// SET WIDTH COLOM NO
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('B')
        ->setWidth(5);
// SET WIDTH COLOM COMP TYPE
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('C')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('D')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('E')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('F')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('G')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('H')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('I')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('J')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('K')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('L')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('M')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('N')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('O')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('P')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('Q')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('R')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('S')
        ->setWidth(15);
$objPHPExcel->getActiveSheet()
        ->getColumnDimension('T')
        ->setWidth(15);


$objPHPExcel->getActiveSheet()->getStyle("D5:T$baris")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("F")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("G")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$objPHPExcel->getActiveSheet()->getStyle("H")->getNumberFormat()->
        setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

//SET TINGGI BARIS
for ($i = 5; $i <= $baris; $i++) {
    $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
}

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