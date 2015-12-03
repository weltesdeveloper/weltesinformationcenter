<?php

    require_once '../../../dbinfo.inc.php';
    require_once '../../../FunctionAct.php';
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

$projectName = $_GET['var1'];
 
//query mysql, ganti baris ini sesuai dengan query kamu
        $generalInfoSql =   "SELECT GR.* FROM GEN_REPORT GR WHERE GR.PROJECT_NAME = :PROJNAME ORDER BY HEAD_MARK";
        $generalInfoParse = oci_parse($conn, $generalInfoSql);
        oci_bind_by_name($generalInfoParse, ":PROJNAME", $projectName);
        oci_execute($generalInfoParse);                                                                                                                       
  
//Set properties, isi teks ini bisa anda lihat
//di file excel yang dihasilkan, klik kanan file tersebut
//dan pilih properties.
$objPHPExcel->getProperties()->setCreator("PT. Weltes Energi Nusantara")
        ->setLastModifiedBy("$username")
        ->setTitle("General Report for $projectName")
        ->setSubject("General Report for $projectName")
        ->setDescription("General Reportt for $projectName")
        ->setKeywords("General Report for $projectName")
        ->setCategory("Weltes Information Center");


$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('B2', 'GENERAL INFORMATION REPORT FOR '.$projectName);  

// Header dari tabel , data akan di simpan di kolom A, B dan C
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('B4', 'NO')
       ->setCellValue('C4', 'HEADMARK')
       ->setCellValue('D4', 'ID')
       ->setCellValue('E4', 'COMP')
       ->setCellValue('F4', 'PROFILE')
       ->setCellValue('G4', 'QTY')
       ->setCellValue('H4', 'TOT.SURFACE')
       ->setCellValue('I4', 'TOT.WEIGHT')
       ->setCellValue('J4', 'SUBCONT')
       ->setCellValue('K4', 'MARKING')
       ->setCellValue('L4', 'CUTTING')
       ->setCellValue('M4', 'ASSEMBLY')
       ->setCellValue('N4', 'WELDING')
       ->setCellValue('O4', 'DRILLING')
       ->setCellValue('P4', 'FAB FINISHING')
       ->setCellValue('Q4', 'Σ.FAB')
       ->setCellValue('R4', 'Σ.FABQC')
       ->setCellValue('S4', 'BLASTING')
       ->setCellValue('T4', 'PRIMER')  
       ->setCellValue('U4', 'INTERMEDIATE')
       ->setCellValue('V4', 'PAINT FINISHING')
       ->setCellValue('W4', 'Σ.PAINT')
       ->setCellValue('X4', 'Σ.PAINTQC')
       ->setCellValue('Y4', 'COLI')
       ->setCellValue('Z4', 'PACK QTY')  
       ->setCellValue('AA4', 'DO NO');
  
$baris = 5;
$no = 0;

//kode untuk menampilkan data dari database ke sel excel
//$baris=2 artinya kita mulai memasukan data ke baris kedua  
while ($row = oci_fetch_array($generalInfoParse)){                                                        
    $no = $no + 1;
    $QTY = $row['QTY'];
      if ($row['ID']=="") {
        $QTY =  $row['TOTAL_QTY'];
      } 
    $DO_NO = SingleQryFld("SELECT DO_NO FROM DTL_DELIV WHERE COLI_NUMBER = '$row[COLINUMBER]'",$conn);
    $objPHPExcel->setActiveSheetIndex(0)
           
        ->setCellValue("B$baris", $no)   
        ->setCellValue("C$baris", $row['HEAD_MARK'])
        ->setCellValue("D$baris", $row['ID'])
        ->setCellValue("E$baris", $row['COMP_TYPE'])
        ->setCellValue("F$baris", $row['PROFILE'])
        ->setCellValue("G$baris", $QTY)
        ->setCellValue("H$baris", number_format($row['SURFACE']*$row['QTY'],2))
        ->setCellValue("I$baris", number_format($row['WEIGHT']*$row['QTY'],2))
        ->setCellValue("J$baris", $row['SUBCONT_ID'])
        ->setCellValue("K$baris", $row['FABMARKING'])
        ->setCellValue("L$baris", $row['FABCUTTING'])
        ->setCellValue("M$baris", $row['FABASSEMBLY'])
        ->setCellValue("N$baris", $row['FABWELDING'])        
        ->setCellValue("O$baris", $row['FABDRILLING'])
        ->setCellValue("P$baris", $row['FABFINISHING'])
        ->setCellValue("Q$baris", number_format($row['TOTALFAB'],2))
        ->setCellValue("R$baris", number_format($row['TOTALQC'],2))
        ->setCellValue("S$baris", $row['BLASTING'])
        ->setCellValue("T$baris", $row['PRIMER'])
        ->setCellValue("U$baris", $row['INTERM'])   
        ->setCellValue("V$baris", $row['PAINTFINISH'])
        ->setCellValue("W$baris", number_format($row['TOTALPAINT'],2))
        ->setCellValue("X$baris", number_format($row['TOTALPAINTQC'],2))
        ->setCellValue("Y$baris", $row['COLINUMBER'])
        ->setCellValue("Z$baris", $row['PCKQTY'])
        ->setCellValue("AA$baris", $DO_NO);
    
    $styleTitle = array(
                'font'  => array(
                'bold'  => true,
                'underline' => true,
                'shrinkToFit' => true,
                'size'  => 14,
                'name'  => 'Trebuchet'
            ));
    
    $styleArray1 = array(
                'font'  => array(
                'bold'  => true,
                'shrinkToFit' => true,
                'size'  => 10,
                'name'  => 'Verdana'
            ));
    
    $styleArray2 = array(
      'font'  => array(
        'bold'  => true,
        'shrinkToFit' => true,
        'size'  => 10,
        'name'  => 'Verdana'
      ),
      'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      )
    );
    
    $styleBorder = array(
      'borders' => array(
        'outline' => array (
          'style' => PHPExcel_Style_Border::BORDER_THIN
        ),
      ),
    );
    
    $objPHPExcel->getActiveSheet()->getStyle("B2:B2")->applyFromArray($styleTitle);
    
    $objPHPExcel->getActiveSheet()->getStyle("C4:C$baris")->applyFromArray($styleArray1);
    $objPHPExcel->getActiveSheet()->getStyle("A4:AA4")->applyFromArray($styleArray2);
    $objPHPExcel->getActiveSheet()->getStyle("B4:AA$baris")->applyFromArray($styleBorder);
    
    
$baris = $baris + 1;
}
        
    
// nama dari sheet yang aktif
$objPHPExcel->getActiveSheet()->setTitle('GENERAL BUILDING REPORT');
  
$objPHPExcel->setActiveSheetIndex(0);

$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="GeneralReportFor '.$projectName.'_'.$formattedFileName.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>