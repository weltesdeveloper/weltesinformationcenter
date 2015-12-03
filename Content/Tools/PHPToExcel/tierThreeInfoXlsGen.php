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
   if(!isset($_SESSION['username'])){
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
   
   if(isset( $_POST['cd-dropdown'])) $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];    
   date_default_timezone_set('Asia/Jakarta'); //CDT
   $current_date = date('H:i:s');

error_reporting(E_ALL);

$clientVal = strval($_GET['client']);
$buildingVal = strval($_GET['building']);
$compVal = strval($_GET['comp']);

$uniqueProjectCodeSql = "SELECT PROJECT_CODE PROJCODE FROM SUB_PROJECT WHERE PROJECT_NO = :PROJNO AND PROJECT_NAME = :PROJNAME";
$uniqueProjectCodeParse = oci_parse($conn, $uniqueProjectCodeSql);
oci_bind_by_name($uniqueProjectCodeParse, ":PROJNO", $clientVal);
oci_bind_by_name($uniqueProjectCodeParse, ":PROJNAME", $buildingVal);
oci_define_by_name($uniqueProjectCodeParse, "PROJCODE", $uniqueProjectCode);
oci_execute($uniqueProjectCodeParse);
while(oci_fetch($uniqueProjectCodeParse)){$uniqueProjectCode;}

$uniqueProjectNameSql = "SELECT PROJECT_NAME PROJECTNAME FROM PROJECT WHERE PROJECT_NO = :PRNO AND PROJECT_CODE = :PRCODE";
$uniqueProjectNameParse = oci_parse($conn, $uniqueProjectNameSql);
oci_bind_by_name($uniqueProjectNameParse, ":PRNO", $clientVal);
oci_bind_by_name($uniqueProjectNameParse, ":PRCODE", $uniqueProjectCode);
oci_define_by_name($uniqueProjectNameParse, "PROJECTNAME", $uniqueProjectName);
oci_execute($uniqueProjectNameParse);
while(oci_fetch($uniqueProjectNameParse)){$uniqueProjectName;}

//query mysql, ganti baris ini sesuai dengan query kamu
$tierThreeSql = "SELECT STTV.* FROM SITE_TIER_THREE_VW STTV WHERE STTV.PROJECT_NAME = :PROJNAME AND STTV.COMP_TYPE = :COMP ORDER BY STTV.HEAD_MARK ASC";
$tierThreeParse = oci_parse($conn, $tierThreeSql);
oci_bind_by_name($tierThreeParse, ":COMP", $compVal);
oci_bind_by_name($tierThreeParse, ":PROJNAME", $uniqueProjectName);
oci_execute($tierThreeParse);                                                                                                         
  
//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Site Erection Project Report for $buildingVal")
        ->setSubject("Site Erection Project Report for $buildingVal")
        ->setDescription("Site Erection Project Report for $buildingVal")
        ->setKeywords("Site Erection Project Report for $buildingVal")
        ->setCategory("Site Erection Project Report");

$styleTitle = array(
  'font'  => array(
    'bold'  => true,
    'underline' => true,
    'shrinkToFit' => true,
    'size'  => 11,
    'name'  => 'Trebuchet'
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
            'font'  => array(
            'bold'  => true,
            'shrinkToFit' => true,
            'size'  => 9,
            'name'  => 'Verdana'
        ));

$styleArray2 = array(
  'font'  => array(
    'bold'  => true,
    'shrinkToFit' => true,
    'size'  => 9,
    'name'  => 'Verdana'
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
      ->setCellValue('B1', 'SITE ERECTION PROJECT SUMMARY REPORT'); 

$objPHPExcel->setActiveSheetIndex(0)
      ->mergeCells('B2:E2')
      ->setCellValue('B2', "JOB NO : $buildingVal"); 

$objPHPExcel->setActiveSheetIndex(0)
      ->mergeCells('B3:E3')
      ->setCellValue('B3', "DESCRIPTION :"); 

$objPHPExcel->setActiveSheetIndex(0)
      ->mergeCells('F3:F3')
      ->setCellValue('F3', 'UPDATED : '.date("d-m-Y"));  

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('B4', 'NO')
       ->setCellValue('C4', 'HEADMARK')
       ->setCellValue('D4', 'TOTAL QTY')
       ->setCellValue('E4', 'TOTAL WEIGHT')
       ->setCellValue('F4', 'ONSITE')
       ->setCellValue('G4', 'PREP')
       ->setCellValue('H4', 'ERECT')
       ->setCellValue('I4', 'QC');
$baris = 5;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($tierThreeParse)){                                                        
    $no = $no + 1;
    $varDiv = @($row['PROGRESSGROSS']/$row['GROSSWEIGHT']*100);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B$baris", $no)   
        ->setCellValue("C$baris", $row['HEAD_MARK'])
        ->setCellValue("D$baris", $row['TOTAL_QTY'])
        ->setCellValue("E$baris", $row['TOTALWEIGHT'])
        ->setCellValue("F$baris", $row['SUMQTYONSITE'])
        ->setCellValue("G$baris", $row['SUMQTYPREP'])
        ->setCellValue("H$baris", $row['SUMQTYERECT'])
        ->setCellValue("I$baris", $row['SUMQTYQC']);
    
    $objPHPExcel->getActiveSheet()->getStyle("B4:I$baris")->applyFromArray($styleBorder);
    
$baris = $baris + 1;
}
  // SET WRAPEP TEXT 
  // APPLY TO ARANGE
  $objPHPExcel->getActiveSheet()->getStyle('B4:J4')
      ->getAlignment()->setWrapText(true); 

  // APPLY TO COLOUMN
  // $objPHPExcel->getActiveSheet()->getStyle('B4:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
  //     ->getAlignment()->setWrapText(true); 

  $objPHPExcel->getActiveSheet()->getStyle("B1:B1")->applyFromArray($styleTitle);
  $objPHPExcel->getActiveSheet()->getStyle("C4:C$baris")->applyFromArray($styleArray1);
  $objPHPExcel->getActiveSheet()->getStyle("B4:J4")->applyFromArray($styleArray2);
  $objPHPExcel->getActiveSheet()->getStyle("B2:B3")->applyFromArray($styleArray1);
  $objPHPExcel->getActiveSheet()->getStyle("H3:J3")->applyFromArray($styleDTNow);

  // SET WIDTH COLOM NO
  $objPHPExcel->getActiveSheet()
  ->getColumnDimension('B')
  ->setWidth(5);
  
  // SET WIDTH COLOM COMP TYPE
  $objPHPExcel->getActiveSheet()
  ->getColumnDimension('C')
  ->setWidth(15);


  // SET WIDTH COLOM KET
  $objPHPExcel->getActiveSheet()
  ->getColumnDimension('S')
  ->setWidth(20);

    
// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('PROJECT ERECTION REPORT');
  
$objPHPExcel->setActiveSheetIndex(0);

$formattedDate = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ProjSumReportFor'.$buildingVal.'_'.$formattedDate.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>