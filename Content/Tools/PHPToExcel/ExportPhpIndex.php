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
$phpSql = "SELECT MASTER_DRAWING.PROJECT_NAME, MASTER_DRAWING.COMP_TYPE, COMP_TYPE_SUMM.JUMLAH JML_DWG,
                                                SUM(FABRICATION.MARKING) JML_MARKING,
                                                SUM(FABRICATION.CUTTING) JML_CUTTING,
                                                SUM(FABRICATION.ASSEMBLY) JML_ASSEMBLY,
                                                SUM(FABRICATION.WELDING) JML_WELDING,
                                                SUM(FABRICATION.DRILLING) JML_DRILLING,
                                                SUM(FABRICATION.FINISHING) JML_FINISHING, SUM(PAINTING.BLASTING) JML_BLASTING,
                                                SUM(PAINTING.PRIMER) JML_PRIMER, SUM(PAINTING.INTERMEDIATE) JML_INTERMEDIATE, SUM(PAINTING.FINISHING) JML_FINISH

                                                FROM FABRICATION, MASTER_DRAWING, COMP_TYPE_SUMM, PAINTING
                                                WHERE FABRICATION.HEAD_MARK = MASTER_DRAWING.HEAD_MARK AND
                                                MASTER_DRAWING.PROJECT_NAME = COMP_TYPE_SUMM.PROJECT_NAME AND
                                                MASTER_DRAWING.COMP_TYPE = COMP_TYPE_SUMM.COMP_TYPE AND
                                                PAINTING.HEAD_MARK = MASTER_DRAWING.HEAD_MARK AND MASTER_DRAWING.PROJECT_NAME = 'PROCESSHOUSE'

                                                GROUP BY MASTER_DRAWING.COMP_TYPE,
                                                MASTER_DRAWING.PROJECT_NAME, COMP_TYPE_SUMM.JUMLAH";
$phpParse = oci_parse($conn,$phpSql);
oci_execute($phpParse);
  
//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("Component Profile List")
        ->setSubject("Component Profile List")
        ->setDescription("Component Profile List")
        ->setKeywords("Component Profile List")
        ->setCategory("Weltes Information Center");
  
// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'COMPONENT')
       ->setCellValue('B1', 'DRAWING')
       ->setCellValue('C1', 'MARKING_SUM')
       ->setCellValue('D1', 'CUTTING_SUM')
       ->setCellValue('E1', 'ASSEMBLY_SUM')
       ->setCellValue('F1', 'WELDING_SUM')
       ->setCellValue('G1', 'DRILLING_SUM')
       ->setCellValue('H1', 'FAB_FINISHING_SUM')
       ->setCellValue('I1', 'BLASTING_SUM')
       ->setCellValue('J1', 'PRIMER_SUM')
       ->setCellValue('K1', 'INTERMEDIATE_SUM')
       ->setCellValue('L1', 'PAINT_FINISHING_SUM');
  
$baris = 2;
$no = 0;
//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while (($row = oci_fetch_array($phpParse, OCI_BOTH)) != false){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $row['COMP_TYPE'])
     ->setCellValue("B$baris", $row['JML_DWG'])
     ->setCellValue("C$baris", $row['JML_MARKING'])
     ->setCellValue("D$baris", $row['JML_CUTTING'])
     ->setCellValue("E$baris", $row['JML_ASSEMBLY'])
     ->setCellValue("F$baris", $row['JML_WELDING'])
     ->setCellValue("G$baris", $row['JML_DRILLING'])
     ->setCellValue("H$baris", $row['JML_FINISHING'])
     ->setCellValue("I$baris", $row['JML_BLASTING'])
     ->setCellValue("J$baris", $row['JML_PRIMER'])
     ->setCellValue("K$baris", $row['JML_INTERMEDIATE'])
     ->setCellValue("L$baris", $row['JML_FINISH']);
$baris = $baris + 1;
}
  
// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('COMPONENT PROFILE LIST');
  
$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("mdY_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="componentProfileList_'.$formattedFileName.'.xls"');
header('Cache-Control: max-age=0');
  
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>