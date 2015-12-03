<?php

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

$date1 = $_GET['var1'];
$date2 = $_GET['var2'];

$projectValSQL = "PROJECT_NAME LIKE '%'";
        
  if ($_GET["projData"]<>"ALL") {
      # code...
      $projectValSQL = 'PROJECT_NAME in ';
      $projNM     = '(';
      list($proj_no,$proj_code) = explode("^", $_GET["projData"]);
      if ($proj_code=="ALL") {
          $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
          
      } else {
          $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' AND PROJECT_CODE='$proj_code' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
      } 

      $projectNameParse = oci_parse($conn, $projectNameSql);                       
      oci_execute($projectNameParse);
      while($projectNameROW = oci_fetch_array($projectNameParse))
      {
          $projNM .= "'".$projectNameROW['PROJECT_NAME']."',";
      }
      $projectValSQL .= substr_replace($projNM, "", -1).")";
      // echo "$projectValSQL";
  }

$dt1 = new DateTime($date1);
$dt2 = new DateTime($date2);

$projKategori = $_GET['projKategori'];

 
if ($date1 == $date2){
  $titleJdul = "Not Started Drawing Assigned Analysis Before ~ ".$dt1->format('l, F d, Y').".";
  $sqlDate = "AND ASSG_DATE <= TO_DATE('$date1 23:59:59','MM/DD/YYYY hh24:mi:ss')";
}else{
  $sqlDate = "AND ASSG_DATE >= TO_DATE('$date1 00:00:01','MM/DD/YYYY hh24:mi:ss') AND ASSG_DATE <= TO_DATE('$date2 23:59:59','MM/DD/YYYY hh24:mi:ss')";
  $titleJdul = "Not Started Drawing Assigned Analysis Between ".$dt1->format('l, F d, Y')." TO ".$dt2->format('l, F d, Y').".";
}

if ($projKategori=="notFABR") {
    # code...
    $projKategori = "(MARK=0)";
} elseif ($projKategori="notPAINT") {
    # code...
    $projKategori = "(BLAST=0)";
} else{
    $projKategori = "(MARK=0 or BLAST=0)";
}

//query mysql, ganti baris ini sesuai dengan query kamu
    $outstandingFabSql = "SELECT * FROM COMP_VW_INFO "
                          . "WHERE $projectValSQL $sqlDate AND SUBCONT_STATUS = 'ASSIGNED' AND "
                          . "$projKategori ORDER BY COMP_TYPE,HEAD_MARK";
    // echo "$outstandingFabSql";exit();
    $outstandingFabParse = oci_parse($conn, $outstandingFabSql);
    // oci_bind_by_name($outstandingFabParse, ":PROJNAME", $projectVal);
    oci_execute($outstandingFabParse);
  
//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Not Started Drawing Between $date1 and $date2")
        ->setSubject("Not Started Drawing Between $date1 and $date2")
        ->setDescription("Not Started Drawing Between $date1 and $date2")
        ->setKeywords("Not Started Drawing")
        ->setCategory("Weltes Information Center");
  
// TITLE Tabel
$objPHPExcel->setActiveSheetIndex(0)
      ->mergeCells('A1:M1')
      ->setCellValue('A1', $titleJdul); 

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A2', 'Head Mark')
       ->setCellValue('B2', 'Id')
       ->setCellValue('C2', 'Entry Date')
       ->setCellValue('D2', 'Comp Type')
       ->setCellValue('E2', 'Profile')
       ->setCellValue('F2', 'Total Weight')
       ->setCellValue('G2', 'Total Surface')
       ->setCellValue('H2', 'Drawing Qty')
       ->setCellValue('I2', 'Assign Qty')
       ->setCellValue('J2', 'Subcontractor')
       ->setCellValue('K2', 'Fab. Status')
       ->setCellValue('L2', 'Paint. Status')
       ->setCellValue('M2', 'Remarks')
       ;         

$baris = 3;
$no = 0;

// STYle TEXT
$styleTitle = array(
  'font'  => array(
    'bold'  => true,
    // 'underline' => true,
    'shrinkToFit' => true,
    'size'  => 11,
    'name'  => 'Trebuchet'
  ),
  'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  )
);

$styleArray1 = array(
            'font'  => array(
            // 'bold'  => true,
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
    'allborders' => array (
      'style' => PHPExcel_Style_Border::BORDER_THIN
    ),
  ),
);

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($outstandingFabParse)){
  $no = $no +1;
  if ($row['MARK'] == 0){
      $markingSign = 'N/A';
  } else{
      $markingSign = 'STARTED';
  }

  if ($row['BLAST'] == 0){
    $blastSign = 'N/A';
  }else{
    $blastSign ='STARTED';
  }    
    $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue("A$baris", $row['HEAD_MARK'])
         ->setCellValue("B$baris", $row['ID'])
         ->setCellValue("C$baris", $row['ASSG_DATE'])
         ->setCellValue("D$baris", $row['COMP_TYPE'])
         ->setCellValue("E$baris", $row['PROFILE'])
         ->setCellValue("F$baris", $row['WEIGHT']*$row['TOTAL_QTY'])
         ->setCellValue("G$baris", $row['SURFACE']*$row['TOTAL_QTY'])
         ->setCellValue("H$baris", $row['TOTAL_QTY'])
         ->setCellValue("I$baris", $row['ASSG_QTY'])
         ->setCellValue("J$baris", $row['SUBCONT_ID'])
         ->setCellValue("K$baris", $markingSign)
         ->setCellValue("L$baris", $blastSign);

    // APly style
    $objPHPExcel->getActiveSheet()->getStyle("A2:M$baris")->applyFromArray($styleBorder);
    $baris = $baris + 1;
}
  
// APPLY TO COLOUMN
  $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleTitle);
  $objPHPExcel->getActiveSheet()->getStyle("A3:M$baris")->applyFromArray($styleArray1);
  $objPHPExcel->getActiveSheet()->getStyle("A2:M2")->applyFromArray($styleArray2);

// SET WRAPEP TEXT 
  // APPLY TO ARANGE
  $objPHPExcel->getActiveSheet()->getStyle('A2:M2')
      ->getAlignment()->setWrapText(true); 

// SET WIDTH COLOM HEAD MARK
  $objPHPExcel->getActiveSheet()
  ->getColumnDimension('A')
  ->setWidth(20);
// SET WIDTH COLOM ID
  $objPHPExcel->getActiveSheet()
  ->getColumnDimension('B')
  ->setWidth(5);


// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('NOT STARTED DRAWING');
  
$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
if ($date1 == $date2){
  header('Content-Disposition: attachment;filename="Not_StartedDrawing_Before_'.$date1.'_GeneratedOn_'.$formattedFileName.'.xls"');
}else{
  header('Content-Disposition: attachment;filename="Not_StartedDrawing_Between_'.$date1.'_to_'.$date2.'_GeneratedOn_'.$formattedFileName.'.xls"');
}
header('Cache-Control: max-age=0');
  
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>