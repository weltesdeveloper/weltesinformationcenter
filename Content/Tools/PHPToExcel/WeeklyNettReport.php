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

function hitungProsentase($onsite, $preparation, $erection, $qc) {
    $presentaseOnsite = $onsite / 100 * 5;
    $prsentasePreparation = $preparation / 100 * 5;
    $prsentaseErection = $erection / 100 * 85;
    $presentaseQc = $qc / 100 * 5;
    $result = $presentaseOnsite + $prsentasePreparation + $prsentaseErection + $presentaseQc;
    return $result;
}

if (isset($_POST['cd-dropdown']))
    $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
date_default_timezone_set('Asia/Jakarta'); //CDT
$current_date = date('H:i:s');

error_reporting(E_ALL);

$jobName = $_GET['client'];
$buildingName = "SMSMILLHOUSE";
$clientSql = "SELECT CLIENT_NAME FROM MST_CLIENT WHERE CLIENT_ID = (SELECT DISTINCT(CLIENT_ID) FROM PROJECT WHERE PROJECT_NO ='$jobName')";
$clientParse = oci_parse($conn, $clientSql);
oci_execute($clientParse);
$client = oci_fetch_array($clientParse)['CLIENT_NAME'];

$projectDateSql = "SELECT PROJECT_START_DT ||' TO '||PROJECT_END_DT PD FROM PROJECT_SPAN WHERE JOB_NAME = '$jobName'";
$projectDateParse = oci_parse($conn, $projectDateSql);
oci_execute($projectDateParse);
$projectDate = oci_fetch_array($projectDateParse)['PD'];
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
        'size' => 8,
        'name' => 'Verdana'
        ));


$styleArray2 = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 8,
        'name' => 'Verdana'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleArrayRight = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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

$styleBold = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 8,
        'name' => 'Verdana'
    )
);

$styleTitle = array(
    'font' => array(
        'bold' => true,
        'shrinkToFit' => true,
        'size' => 14,
        'name' => 'Verdana'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);


$stylefont8 = array(
    'font' => array(
        //'bold' => true,
        //'shrinkToFit' => true,
        'size' => 8,
        'name' => 'Verdana'
    )
);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('A4', "PROJECT");
$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('A5', "OWNER");
$objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('A6', "CLIENT");
$objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('A7', "PERIOD");
$objPHPExcel->getActiveSheet()->getStyle("A7")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('B4', "$jobName");
$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('B5', "WELTES");
$objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('B6', "$client");
$objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray2);

$objPHPExcel->setActiveSheetIndex(0)
        //->mergeCells('A8:N8')
        ->setCellValue('B7', "$projectDate");
$objPHPExcel->getActiveSheet()->getStyle("A7")->applyFromArray($styleArray2);


//TITLE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A8:N8')
        ->setCellValue('A8', "WEEKLY PROGRESS REPORT REPORT FOR NETT WEIGHT");
$objPHPExcel->getActiveSheet()->getStyle("A8:N8")->applyFromArray($styleTitle);
//NOMER
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A11:A14')
        ->setCellValue('A11', "NO");

//DESCRIPTION
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B11:E14')
        ->setCellValue('B11', "DESCRIPTION");
//WEIGHT
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('F11:F14')
        ->setCellValue('F11', "WEIGHT");

//VALUE
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('G11:G14')
        ->setCellValue('G11', "VALUE");
//PROGRESS PLAN
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('H11:H14')
        ->setCellValue('H11', 'PROGRESS PLAN');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('I11:N11')
        ->setCellValue('I11', 'ACTUAL PROGRESS');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('I12:K12')
        ->setCellValue('I12', 'PHYSICAL PROGRESS');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('L12:N12')
        ->setCellValue('L12', 'VALUE PROGRESS');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('I13:I14')
        ->setCellValue('I13', 'LAST WEEK');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('J13:J14')
        ->setCellValue('J13', 'THIS WEEK');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('K13:K14')
        ->setCellValue('K13', 'TO THIS WEEK');
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('L13:L14')
        ->setCellValue('L13', 'LAST WEEK');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('M13:M14')
        ->setCellValue('M13', 'THIS WEEK');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('N13:N14')
        ->setCellValue('N13', 'TO THIS WEEK');

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('B15:E15');

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A15", 1);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B15", 2);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F15", 3);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("G15", 4);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H15", 5);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("I15", 6);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("J15", 7);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("K15", 8);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("L15", 9);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("M15", 10);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("N15", 11);
$objPHPExcel->getActiveSheet()->getStyle("A15:N15")->applyFromArray($styleArray2);

$today = date('w');
$sql = "SELECT STO.*,(SELECT SUM (NETWEIGHT)FROM SITE_TIER_ONE_VW STO WHERE PROJECTNO = '$jobName') TOTAL_PROJECT "
        . "FROM SITE_TIER_ONE_VW STO WHERE PROJECTNO = '$jobName' ORDER BY PROJECTNAME";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
$counter = 1;
$baris = 16;
while ($row = oci_fetch_array($parse)) {
    $projectName = $row['COMPPROJECTNAME'];
    $nettWeight = $row['NETWEIGHT'];
    $grossWeight = $row['GROSSWEIGHT'];
    $nettAll = $row['TOTAL_PROJECT'];
    $procentNett = $nettWeight / $nettAll * 100;

    //HITUNG PHISICAL LAST WEEK
    $hasilLastWeek = 0.00;
    $sql1 = "SELECT  GET_PROCENTAGE_ALL (SUM (VSD.NETT_ONSITE) * 5 / 100, SUM (VSD.NETT_PREP) * 5 / 100, SUM (VSD.NETT_ERECT) * 85 / 100, SUM (VSD.NETT_QC) * 5 / 100) 
        AS TOTAL FROM VW_SHOW_DATA VSD
        WHERE VSD.PROJECT_NAME = '$projectName' AND TO_DATE(VSD.UPD_DATE,'DD MM YYYY') <= TO_DATE(SYSDATE, 'DD MM YYYY')-$today";
    $parse1 = oci_parse($conn, $sql1);
    oci_execute($parse1);
    $hasilLastWeek = oci_fetch_array($parse1)[0] + 0.00;
    $physiscalLastWeek = number_format($hasilLastWeek / $row['NETWEIGHT'] * 100, 2);

    //HITUNG PHISICAL THIS WEEK
    $hasilthisweek = 0.00;
    $sql2 = "SELECT  GET_PROCENTAGE_ALL (SUM (VSD.NETT_ONSITE) * 5 / 100, SUM (VSD.NETT_PREP) * 5 / 100, SUM (VSD.NETT_ERECT) * 85 / 100, SUM (VSD.NETT_QC) * 5 / 100) 
        AS TOTAL FROM VW_SHOW_DATA VSD
        WHERE VSD.PROJECT_NAME = '$projectName'"
            . "AND TO_DATE(UPD_DATE, 'DD MM YYYY') BETWEEN TO_DATE(SYSDATE, 'DD MM YYYY')-$today AND TO_DATE(SYSDATE, 'DD MM YYYY') + (6 - $today)";
    $parse2 = oci_parse($conn, $sql2);
    oci_execute($parse2);
    $hasilthisweek = oci_fetch_array($parse2)[0] + 0.00;
    $physiscalThisWeek = number_format($hasilthisweek / $row['NETWEIGHT'] * 100, 2);

    //HITUNG PHISICAL TO THIS WEEK
    $phisyscaltothisweek = $physiscalThisWeek + $physiscalLastWeek;

    //VALUE PROGRESS LAST WEEK
    $valuelastweek = number_format($hasilLastWeek / $row['TOTAL_PROJECT'] * 100, 2);
    //VALUE PROGRESS THIS WEEK
    $valuethisweek = number_format($hasilthisweek / $row['TOTAL_PROJECT'] * 100, 2);
    //VALUE PROGRESS TO THIS WEEK
    $valuetothisweek = $valuelastweek + $valuethisweek;

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$baris", $counter)
            ->mergeCells("B$baris:E$baris")
            ->setCellValue("B$baris", "$projectName")//BUIDING
            ->setCellValue("F$baris", "")//TOTALQTY
            ->setCellValue("G$baris", "")//TOTAL PER BUILDING
            ->setCellValue("H$baris", "")//PROSENTASE PER BUILDING
            ->setCellValue("I$baris", "")//PISICAL LAST WEEK
            ->setCellValue("J$baris", "")//PISICAL THIS WEEK
            ->setCellValue("K$baris", "")//PISICAL TO THISWEEK
            ->setCellValue("L$baris", "")
            ->setCellValue("M$baris", "")
            ->setCellValue("N$baris", "");
    $objPHPExcel->getActiveSheet()->getStyle("A$baris")->applyFromArray($styleArray2);
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:N$baris")->applyFromArray($styleBold);
    $baris++;
    $SqlPerComponent = "SELECT * FROM SITE_TIER_TWO_VW WHERE PROJECT_NAME = '$projectName'";
    $parsePerComponent = oci_parse($conn, $SqlPerComponent);
    oci_execute($parsePerComponent);
    $totalLastweek = 0;
    $totalthisweek = 0;
    $totaltothisweek = 0;
    while ($row1 = oci_fetch_array($parsePerComponent)) {
        $comp_type = $row1['COMP_TYPE'];
        $totat_weight = $row1['TOTAL_WEIGHT'];
        $prosentaseproject = $totat_weight / $nettAll * 100;

        $persenOnsite = ($row1['NETT_ONSITE'] / $row1['TOTAL_WEIGHT'] * 100);
        $persenPrep = $row1['NETT_PREPARATION'] / $row1['TOTAL_WEIGHT'] * 100;
        $persenErection = $row1['NETT_ERECTION'] / $row1['TOTAL_WEIGHT'] * 100;
        $persenQc = $row1['NETT_QC'] / $row1['TOTAL_WEIGHT'] * 100;
        $prosentaseakhir = hitungProsentase($persenOnsite, $persenPrep, $persenErection, $persenQc);
        $totalakhir = $prosentaseakhir * $row1['TOTAL_WEIGHT'] / 100;
        
        //PHISYCAL LAST WEEK
        $totalakhir1 = 0;
        $prosentaseakhir1 = 0;
        $prosentasevalueprogress1 = 0;
        
        $sqlLastWeek = "SELECT NVL(SUM (NETT_ONSITE),0) NETT_ONSITE, 
                            NVL(SUM(NETT_PREP) ,0) NETT_PREP, 
                            NVL(SUM(NETT_ERECT),0) NETT_ERECT, 
                            NVL(SUM(NETT_QC),0) NETT_QC, 
                            COMP_TYPE
                                FROM VW_SHOW_DATA
                                WHERE PROJECT_NAME = '$projectName'
                                AND COMP_TYPE = '$comp_type'
                                AND TO_DATE(UPD_DATE, 'DD MM YYYY') < TO_DATE(SYSDATE, 'DD MM YYYY')-$today 
                                GROUP BY COMP_TYPE";
        $parseLastWeek = oci_parse($conn, $sqlLastWeek);
        oci_execute($parseLastWeek);
        while ($row2 = oci_fetch_array($parseLastWeek)) {
            $persenOnsite = $row2['NETT_ONSITE'] / $totat_weight * 100;
            $persenPrep = $row2['NETT_PREP'] / $totat_weight * 100;
            $persenErection = $row2['NETT_ERECT'] / $totat_weight * 100;
            $persenQc = $row2['NETT_QC'] / $totat_weight * 100;

            $prosentaseakhir1 = hitungProsentase($persenOnsite, $persenPrep, $persenErection, $persenQc);
            $totalakhir1 = $prosentaseakhir1 * $totat_weight / 100;
            $prosentaseakhir1 = $totalakhir1 / $totat_weight * 100;
            $prosentasevalueprogress1 = $totalakhir1 / $nettAll * 100;
            $totalLastweek += $prosentaseakhir1;
        }

        //PHYSICAL THIS WEEK
        $totalakhir2 = 0;
        $prosentaseakhir2 = 0;
        $prosentasevalueprogress2 = 0;
        
        $sqlThisWeek = "SELECT NVL(SUM (NETT_ONSITE),0) NETT_ONSITE, 
                            NVL(SUM(NETT_PREP) ,0) NETT_PREP, 
                            NVL(SUM(NETT_ERECT),0) NETT_ERECT, 
                            NVL(SUM(NETT_QC),0) NETT_QC, 
                            COMP_TYPE
                                FROM VW_SHOW_DATA
                                WHERE PROJECT_NAME = '$projectName'
                                AND COMP_TYPE = '$comp_type'
                                AND TO_DATE(UPD_DATE, 'DD MM YYYY') BETWEEN TO_DATE(SYSDATE, 'DD MM YYYY')-$today AND TO_DATE(SYSDATE, 'DD MM YYYY') + (6 - $today)
                                GROUP BY COMP_TYPE";
        $parseThisWeek = oci_parse($conn, $sqlThisWeek);
        oci_execute($parseThisWeek);
        while ($row2 = oci_fetch_array($parseThisWeek)) {
            $persenOnsite = $row2['NETT_ONSITE'] / $totat_weight * 100;
            $persenPrep = $row2['NETT_PREP'] / $totat_weight * 100;
            $persenErection = $row2['NETT_ERECT'] / $totat_weight * 100;
            $persenQc = $row2['NETT_QC'] / $totat_weight * 100;

            $prosentaseakhir2 = hitungProsentase($persenOnsite, $persenPrep, $persenErection, $persenQc);
            $totalakhir2 = $prosentaseakhir2 * $totat_weight / 100;
            $prosentaseakhir2 = $totalakhir2 / $totat_weight * 100;
            $prosentasevalueprogress2 = $totalakhir2 / $nettAll * 100;
            $totalthisweek += $totalakhir2;
        }

        //PHYSICAL TO THIS WEEK
        $totalakhir3 = $totalakhir1 + $totalakhir2;
        $prosentaseakhir3 = $prosentaseakhir1 + $prosentaseakhir2;
        $prosentasevalueprogress3 = ($prosentasevalueprogress1 + $prosentasevalueprogress2);
        $totaltothisweek = $totalLastweek+$totalthisweek;
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("B$baris:E$baris")
                ->setCellValue("B$baris", "     " . $comp_type)//BUIDING
                ->setCellValue("F$baris", number_format($totat_weight, 2))//TOTALQTY
                ->setCellValue("G$baris", number_format($prosentaseproject, 2)." %")//TOTAL PER BUILDINGs
                ->setCellValue("H$baris", "")//PROSENTASE PER BUILDING
                ->setCellValue("I$baris", number_format($prosentaseakhir1, 2)." %")//PISICAL LAST WEEK
                ->setCellValue("J$baris", number_format($prosentaseakhir2, 2)." %")//PISICAL THIS WEEK
                ->setCellValue("K$baris", number_format($prosentaseakhir3, 2)." %")//PISICAL TO THISWEEK
                ->setCellValue("L$baris", number_format($prosentasevalueprogress1, 2)." %")
                ->setCellValue("M$baris", number_format($prosentasevalueprogress2, 2)." %")
                ->setCellValue("N$baris", number_format($prosentasevalueprogress3, 2)." %");
        $baris++;
    }
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("B$baris:E$baris")
                ->setCellValue("B$baris", "SUB TOTAL" )//BUIDING
                ->setCellValue("F$baris", number_format($nettWeight, 2))//TOTALQTY
                ->setCellValue("G$baris", number_format($procentNett, 2)." %")//TOTAL PER BUILDINGs
                ->setCellValue("H$baris", "")//PROSENTASE PER BUILDING
                ->setCellValue("I$baris", number_format($physiscalLastWeek, 2)." %")//PISICAL LAST WEEK
                ->setCellValue("J$baris", number_format($physiscalThisWeek, 2)." %")//PISICAL THIS WEEK
                ->setCellValue("K$baris", number_format($phisyscaltothisweek, 2)." %")//PISICAL TO THISWEEK
                ->setCellValue("L$baris", number_format($valuelastweek, 2)." %")
                ->setCellValue("M$baris", number_format($valuethisweek, 2)." %")
                ->setCellValue("N$baris", number_format($valuetothisweek, 2)." %");
    $objPHPExcel->getActiveSheet()->getStyle("B$baris:N$baris")->applyFromArray($styleBold);
    $objPHPExcel->getActiveSheet()->getStyle("B$baris")->applyFromArray($styleArrayRight);
        $baris++;
    $counter++;
}

$objPHPExcel->getActiveSheet()->getStyle("A11:N11")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("I11:N11")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("A11:N11")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("A12:N12")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("A13:N13")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("A14:N14")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("A15:N15")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("I11:N11")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("I12:K12")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("L12:N12")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()->getStyle("A16:N$baris")->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle("F16:N$baris")->applyFromArray($styleArrayRight);


$sqlWeightAll = "SELECT SUM (NETWEIGHT) NETT_WEIGHT FROM SITE_TIER_ONE_VW STO WHERE PROJECTNO = '$jobName'";
$parseWeightAll = oci_parse($conn, $sqlWeightAll);
oci_execute($parseWeightAll);
$weightAll = oci_fetch_array($parseWeightAll)['NETT_WEIGHT'];

$sqlLastWeek = "SELECT GET_PROCENTAGE_ALL (
                SUM (VSD.NETT_ONSITE) * 5 / 100,
                SUM (VSD.NETT_PREP) * 5 / 100,
                SUM (VSD.NETT_ERECT) * 85 / 100,
                SUM (VSD.NETT_QC) * 5 / 100)
                AS TOTAL
                FROM VW_SHOW_DATA VSD  WHERE 
                TO_DATE(UPD_DATE, 'DD MM YYYY') < TO_DATE(SYSDATE, 'DD MM YYYY')-$today";
$parseLastWeek = oci_parse($conn, $sqlLastWeek);
oci_execute($parseLastWeek);
$lastWeek = oci_fetch_array($parseLastWeek)['TOTAL'];

$sqlThisWeek = "SELECT GET_PROCENTAGE_ALL (
                SUM (VSD.NETT_ONSITE) * 5 / 100,
                SUM (VSD.NETT_PREP) * 5 / 100,
                SUM (VSD.NETT_ERECT) * 85 / 100,
                SUM (VSD.NETT_QC) * 5 / 100)
                AS TOTAL
                FROM VW_SHOW_DATA VSD  WHERE 
                TO_DATE(UPD_DATE, 'DD MM YYYY') BETWEEN TO_DATE(SYSDATE, 'DD MM YYYY')-$today AND TO_DATE(SYSDATE, 'DD MM YYYY') + (6 - $today)";
$parseThisWeek = oci_parse($conn, $sqlThisWeek);
oci_execute($parseThisWeek);
$thisweek = oci_fetch_array($parseThisWeek)['TOTAL'];

$prosentaseLastWeek = ($lastWeek/$weightAll*100);
$prosentaseThissWeek = $thisweek/$weightAll*100;
$prosentaseAll = $prosentaseLastWeek+$prosentaseThissWeek;
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("A$baris:E$baris")
        ->setCellValue("A$baris", "TOTAL")
        ->setCellValue("F$baris", "$weightAll")
        ->setCellValue("G$baris", "100%")
        ->setCellValue("H$baris", "")
        ->setCellValue("I$baris", number_format($prosentaseLastWeek,2))
        ->setCellValue("J$baris", number_format($prosentaseThissWeek,2))
        ->setCellValue("K$baris", number_format($prosentaseAll,2))
        ->setCellValue("L$baris", number_format($prosentaseLastWeek,2))
        ->setCellValue("M$baris", number_format($prosentaseThissWeek,2))
        ->setCellValue("N$baris", number_format($prosentaseAll,2));

$objPHPExcel->getActiveSheet()->setTitle('WEEKLY REPORT');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle("A11:N$baris")
    ->getAlignment()->setWrapText(true); 

$objPHPExcel->getActiveSheet()->getStyle("I13:N14")
    ->getAlignment()->setWrapText(true); 
$objPHPExcel->getActiveSheet()->getStyle("I13:N14")->applyFromArray($styleArray2);
$objPHPExcel->getActiveSheet()
    ->getColumnDimension('F')
    ->setWidth(15);
$objPHPExcel->getActiveSheet()
    ->getColumnDimension('H')
    ->setWidth(9.43);
$objPHPExcel->getActiveSheet()->getStyle("A11:N$baris")
    ->applyFromArray($stylefont8); 


$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2014.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="NETTREPORT' . $jobName . '_' . $formattedDate . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>