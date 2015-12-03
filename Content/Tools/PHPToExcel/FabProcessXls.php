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

 
//query mysql, ganti baris ini sesuai dengan query kamu
    $fabricationSql = "SELECT * "
            . " FROM VW_FAB_INFO "
            . " WHERE PROJECT_NAME = :PROJNAME ";
    $fabricationParse = oci_parse($conn, $fabricationSql);
    oci_bind_by_name($fabricationParse, ":PROJNAME", $_SESSION['cd-dropdown']);
    oci_execute($fabricationParse);
  
//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Fabrication List")
        ->setSubject("Fabrication List")
        ->setDescription("Fabrication Info List")
        ->setKeywords("Fabrication Info List")
        ->setCategory("Weltes Information Center Fabrication List");
  
// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'HEAD MARK')
       ->setCellValue('B1', 'QUANTITY')
       ->setCellValue('C1', 'SUBCONTRACTOR')
       ->setCellValue('D1', 'MARKING')
       ->setCellValue('E1', 'CUTTING')
       ->setCellValue('F1', 'ASSEMBLY')
       ->setCellValue('G1', 'WELDING')
       ->setCellValue('H1', 'DRILLING')
       ->setCellValue('I1', 'FINISHING')
       ->setCellValue('J1', 'TOTAL FABRICATION')
       ->setCellValue('K1', 'UNIT WEIGHT')
       ->setCellValue('L1', 'TOTAL WEIGHT')
       ->setCellValue('M1', 'FABRICATION PROGRESS')
       ->setCellValue('N1', 'ENTRY DATE');   
  
$baris = 2;
$no = 0;
//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($fabricationParse)){
$no = $no +1;

$totalCalculatedWeight = $row['ASG_QTY'] * $row['WEIGHT'];
$totalFabWeight = ((($row['MARKING']/$row['ASG_QTY'])*0.02)*$totalCalculatedWeight)+
                  ((($row['CUTTING']/$row['ASG_QTY'])*0.03)*$totalCalculatedWeight)+
                  ((($row['ASSEMBLY']/$row['ASG_QTY'])*0.25)*$totalCalculatedWeight)+
                  ((($row['WELDING']/$row['ASG_QTY'])*0.30)*$totalCalculatedWeight)+
                  ((($row['DRILLING']/$row['ASG_QTY'])*0.15)*$totalCalculatedWeight)+
                  ((($row['FINISHING']/$row['ASG_QTY'])*0.25)*$totalCalculatedWeight);
$fabSumPercentage = ($totalFabWeight / $totalCalculatedWeight) * 100;
                                                        
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $row['HEAD_MARK'])
     ->setCellValue("B$baris", $row['ASG_QTY'])
     ->setCellValue("C$baris", $row['SUBCONT_ID'])
     ->setCellValue("D$baris", $row['MARKING'])
     ->setCellValue("E$baris", $row['CUTTING'])
     ->setCellValue("F$baris", $row['ASSEMBLY'])
     ->setCellValue("G$baris", $row['WELDING'])
     ->setCellValue("H$baris", $row['DRILLING'])
     ->setCellValue("I$baris", $row['FINISHING'])
     ->setCellValue("J$baris", number_format($totalFabWeight,1))
     ->setCellValue("K$baris", $row['WEIGHT'])
     ->setCellValue("L$baris", $totalCalculatedWeight)
     ->setCellValue("M$baris", $fabSumPercentage)
     ->setCellValue("N$baris", $row['ASG_DATE']);
$baris = $baris + 1;
}
  
// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('FABRICATION INFO LIST');
  
$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("mdY_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="fabricationInfoList_'.$formattedFileName.'.xls"');
header('Cache-Control: max-age=0');
  
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>