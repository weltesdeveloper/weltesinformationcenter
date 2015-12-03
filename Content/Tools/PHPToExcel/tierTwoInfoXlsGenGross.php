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

function hitungProsentase($onsite, $preparation, $erection, $qc) {
    $presentaseOnsite = $onsite / 100 * 5;
    $prsentasePreparation = $preparation / 100 * 5;
    $prsentaseErection = $erection / 100 * 85;
    $presentaseQc = $qc / 100 * 5;
    $result = $presentaseOnsite + $prsentasePreparation + $prsentaseErection + $presentaseQc;
    return $result;
}

function totalWeightErection($onsite, $prep,$erec, $qc){
    $w_onsite = $onsite*5/100;
    $w_prep = $prep*5/100;
    $w_erect = $erec*85/100;
    $w_qc = $qc*5/100;
    $hasil = $w_erect+$w_onsite+$w_prep+$w_qc;
    return $hasil;
}

if (isset($_POST['cd-dropdown']))
    $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
date_default_timezone_set('Asia/Jakarta'); //CDT
$current_date = date('H:i:s');

error_reporting(E_ALL);

$jobName = strval($_GET['job']);
$buildingName = strval($_GET['buildingName']);

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

//JOB ID
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B1:E1')
        ->setCellValue('B1', "JOB NO : $jobName" . "");

//DESCRIPTION
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B2:E2')
        ->setCellValue('B2', "DESCRIPTION : $buildingName");

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B3:E3')
        ->setCellValue('B3', 'UPDATED : ' . date("d-m-Y"));

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B6:T6')
        ->setCellValue('B6', 'ERECTION PROGRESS REPORT FOR GROSS WEIGHT');

// Header dari tabel , data akan di simpan di kolom A, B dan C
//NOMOR
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B9:B10')
        ->setCellValue('B9', 'NO');
//KOMPONEN
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('C9:C10')
        ->setCellValue('C9', 'KOMPONEN');
//TOTAL
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('D9:F9')
        ->setCellValue('D9', 'TOTAL');
//TOTAL & WEIGHT QTY
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('D10', 'QTY')
        ->mergeCells('E10:F10')
        ->setCellValue('E10', 'WEIGHT');

//MATERIAL ONSITE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('G9:I9')
        ->setCellValue('G9', 'MATERIAL ONSITE');
//TOTAL, % & MATERIAL ONSITE
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('G10', 'QTY')
        ->setCellValue('H10', '%')
        ->setCellValue('I10', 'WEIGHT');

//MATERIAL PREPARATION
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('J9:L9')
        ->setCellValue('J9', 'MATERIAL PREPARATION');
//TOTAL, % & PREPARATION 
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('J10', 'QTY')
        ->setCellValue('K10', '%')
        ->setCellValue('L10', 'WEIGHT');

//MATERIAL ERECTION
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('M9:O9')
        ->setCellValue('M9', 'MATERIAL ERECTION');
//TOTAL, % & ERECTION
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('M10', 'QTY')
        ->setCellValue('N10', '%')
        ->setCellValue('O10', 'WEIGHT');

//MATERIAL QC
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('P9:R9')
        ->setCellValue('P9', 'MATERIAL QC');
//TOTAL, % & QC
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('P10', 'QTY')
        ->setCellValue('Q10', '%')
        ->setCellValue('R10', 'WEIGHT');

//OVERALL PROGRESS
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('S9:T9')
        ->setCellValue('S9', 'OVERALL PROGRESS');

$objPHPExcel->getActiveSheet()->getStyle("B6:T6")->applyFromArray($styleTitle);
//$objPHPExcel->getActiveSheet()->getStyle("B6:T6")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B9:T9")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B10:T10")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("B9:T9")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("B10:T10")->applyFromArray($styleArray2);


//ISI  VALUE DATA
$componentSql = "SELECT MD.PROJECT_NAME,
                                MD.COMP_TYPE,
                                SUM (MD.TOTAL_QTY) TOTAL_QTY,
                                SUM (MD.TOTAL_QTY * MD.WEIGHT) NETT_WEIGHT,
                                SUM (MD.TOTAL_QTY * MD.GR_WEIGHT) GROSS_WEIGHT,
                                NVL (TOTAL_ONSITE, 0) QTY_ONSITE,
                                NVL (TOTAL_PREPARATION, 0) QTY_PREPARATION,
                                NVL (TOTAL_ERECTION, 0) QTY_ERECTION,
                                NVL (TOTAL_QC, 0) QTY_QC,
                                NVL (ONSITE_NETT, 0) NETT_ONSITE,
                                NVL (PREP_NETT, 0) NETT_PREPARATION,
                                NVL (ERECT_NETT, 0) NETT_ERECTION,
                                NVL (QC_NETT, 0) NETT_QC,
                                NVL (GR_ONSITE, 0) GR_ONSITE,
                                NVL (GR_PREP, 0) GR_PREPARATION,
                                NVL (GR_ERECT, 0) GR_ERECTION,
                                NVL (GR_QC, 0) GR_QC
                           FROM MASTER_DRAWING MD
                                LEFT OUTER JOIN
                                VW_CALC_WEIGHT_COMP VW
                                   ON     VW.PROJECT_NAME = MD.PROJECT_NAME
                                      AND VW.COMP_TYPE = MD.COMP_TYPE
                          WHERE MD.PROJECT_NAME = :PROJ AND MD.DWG_STATUS = 'ACTIVE'
                       GROUP BY MD.PROJECT_NAME,
                                MD.COMP_TYPE,
                                TOTAL_ONSITE,
                                TOTAL_PREPARATION,
                                TOTAL_ERECTION,
                                TOTAL_QC,
                                ONSITE_NETT,
                                PREP_NETT,
                                ERECT_NETT,
                                QC_NETT,
                                GR_ONSITE,
                                GR_PREP,
                                GR_ERECT,
                                GR_QC
                       ORDER BY MD.COMP_TYPE";
$componentParse = oci_parse($conn, $componentSql);
oci_bind_by_name($componentParse, ":PROJ", $buildingName);
oci_execute($componentParse);
$baris = 11;
$no = 1;

//TOTAL, % & PROGRESS
$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('D9:F9')
        ->setCellValue('S10', '%')
        ->setCellValue('T10', 'QTY');
$overallprogresskg = 0;
while ($row = oci_fetch_array($componentParse)) {
    $compType = $row['COMP_TYPE'];
    $totalQty = $row['TOTAL_QTY'];
    $totalWeight = $row['GROSS_WEIGHT'];
    //ONSITE
    $onsiteqty = $row['QTY_ONSITE'];
    $onsiteprocentage = @($row['GR_ONSITE']/$row['GROSS_WEIGHT']);
    $onsiteweight = $row['GR_ONSITE'];
    //PREPARATION
    $prepqty = $row['QTY_PREPARATION'];
    $prepprocentage = @($row['GR_PREPARATION']/$row['GROSS_WEIGHT']);
    $prepweight = $row['GR_PREPARATION'];

    //ERECTION
    $erecqty = $row['QTY_ERECTION'];
    $erectprocentage = @($row['GR_ERECTION']/$row['GROSS_WEIGHT']);
    $erectweight = $row['GR_ERECTION'];

    //QC 
    $qcqty = $row['QTY_QC'];
    $qcprocentage = @($row['GR_QC']/$row['GROSS_WEIGHT']);
    $qcweight = $row['GR_QC'];

    //OVERALL PROGRESS
    $prosentaseakhir = hitungProsentase($onsiteprocentage, $prepprocentage, $erectprocentage, $qcprocentage);
    $totalakhir = $prosentaseakhir*$row['GROSS_WEIGHT'];
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$baris", $no)//NOMER
            ->setCellValue("C$baris", "$compType")//KOMPONEN
            ->setCellValue("D$baris", "$totalQty")//TOTALQTY
            ->mergeCells("E$baris:F$baris")
            ->setCellValue("E$baris", number_format($totalWeight,2))
            
            ->setCellValue("G$baris", "$onsiteqty")
            ->setCellValue("H$baris", number_format($onsiteprocentage*100,2))
            ->setCellValue("I$baris", number_format($onsiteweight,2))
            
            ->setCellValue("J$baris", "$prepqty")
            ->setCellValue("K$baris", number_format($prepprocentage*100,2))
            ->setCellValue("L$baris", number_format($prepweight,2))
            
            ->setCellValue("M$baris", "$erecqty")
            ->setCellValue("N$baris", number_format($erectprocentage*100,2))
            ->setCellValue("O$baris", number_format($erectweight,2))
            
            ->setCellValue("P$baris", "$qcqty")
            ->setCellValue("Q$baris", number_format($qcprocentage*100,2))
            ->setCellValue("R$baris", number_format($qcweight,2))
            
             ->setCellValue("S$baris", number_format($prosentaseakhir*100, 2))
             ->setCellValue("T$baris", number_format($totalakhir, 2))
            
            ; 
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:T$baris")->applyFromArray($styleBorder);
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:T$baris")->applyFromArray($styleDTNow);
    $overallprogresskg+=($totalakhir);
    $baris++;
    $no++;
}

//FOOTER
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("B$baris:C$baris")
        ->setCellValue("B$baris", 'TOTAL');
$footerSql = "SELECT 
       SUM (TOTAL_QTY) QTY,
       SUM (QTY_ONSITE) QTY_ONSITE,
       SUM(QTY_PREPARATION) QTY_PREPARATION,
       SUM(QTY_ERECTION) QTY_ERECTION,
       SUM(QTY_QC) QTY_QC,
       SUM (GR_ONSITE) ONSITE,
       SUM (GR_PREPARATION) PREPARATION,
       SUM (GR_ERECTION) ERECTION,
       SUM (GR_QC) QC,
       SUM (GROSS_WEIGHT) TOTAL
       FROM SITE_TIER_TWO_VW WHERE PROJECT_NAME = '$buildingName'";
//echo "$footerSql";
$footerParse = oci_parse($conn, $footerSql);
oci_execute($footerParse);
while ($row1 = oci_fetch_array($footerParse)) {
    $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue("B$baris", $no)//NOMER
//            ->setCellValue("C$baris", "$compType")//KOMPONEN
            ->setCellValue("D$baris", $row1['QTY'])//TOTALQTY
            ->mergeCells("E$baris:F$baris")
            ->setCellValue("E$baris", $row1['TOTAL'])
            
            ->setCellValue("G$baris", number_format($row1['QTY_ONSITE'],2))
            ->setCellValue("H$baris", number_format(($row1['ONSITE']/$row1['TOTAL']*100),2))
            ->setCellValue("I$baris", number_format($row1['ONSITE'],2))
            
            ->setCellValue("J$baris", number_format($row1['QTY_PREPARATION'],2))
            ->setCellValue("K$baris", number_format(($row1['PREPARATION']/$row1['TOTAL']*100),2))
            ->setCellValue("L$baris", number_format($row1['PREPARATION'],2))
            
            ->setCellValue("M$baris", number_format($row1['QTY_ERECTION'],2))
            ->setCellValue("N$baris", number_format(($row1['ERECTION']/$row1['TOTAL']*100),2))
            ->setCellValue("O$baris", number_format($row1['ERECTION'],2))
            
            ->setCellValue("P$baris", number_format($row1['QTY_QC'],2))
            ->setCellValue("Q$baris", number_format(($row1['QC']/$row1['TOTAL']*100),2))
            ->setCellValue("R$baris", number_format($row1['QC'],2))
            
             ->setCellValue("S$baris", number_format(totalWeightErection($row1['ONSITE'], $row1['PREPARATION'], $row1['ERECTION'], $row1['QC'])/$row1['TOTAL']*100, 2))
             ->setCellValue("T$baris", number_format(totalWeightErection($row1['ONSITE'], $row1['PREPARATION'], $row1['ERECTION'], $row1['QC']), 2))
            ; 
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:T$baris")->applyFromArray($styleBorder);
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:T$baris")->applyFromArray($styleDTNow);
    
}
$objPHPExcel->getActiveSheet()->setTitle('PROJECT ERECTION REPORT');

$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="grossweight' . $jobName . '_' . $formattedDate . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>