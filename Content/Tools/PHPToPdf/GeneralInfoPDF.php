<?php //
 echo 'dalam perbaikan silahkan hubungi developer jika penting';
 exit();
    require_once '../../../dbinfo.inc.php';
    // INCLUDE THE phpToPDF.php FILE
    require("lib/phpToPDF.php"); 
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
   
   //$_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
    if(isset( $_POST['cd-dropdown'])) $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
?>

<?php

   //query mysql, ganti baris ini sesuai dengan query kamu
$generalInfoSql =   "SELECT FABRICATION.HEAD_MARK HEADMARK, NVL((SELECT MASTER_DRAWING.COMP_TYPE FROM MASTER_DRAWING WHERE FABRICATION.HEAD_MARK = MASTER_DRAWING.HEAD_MARK),'-') COMP_TYPE, 
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
    
    $content = "
    <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <meta charset=\"UTF-8\">
        <title>General Info Check List</title>
        <link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css\">
        <link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css\">
        <script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
        <script src=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js\"></script>
        <style type=\"text/css\">
            .bs-example{
                margin: 20px;
            }
        </style>
        </head>
        <body>
        <div class=\"bs-example\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                                                <th>HEADMARK</th>
                                                <th>COMPONENT</th>
                                                <th>QUANTITY</th>
                                                <th>WEIGHT</th>
                                                <th>MARKING</th>
                                                <th>CUTTING</th>
                                                <th>ASSEMBLY</th>
                                                <th>WELDING</th>
                                                <th>DRILLING</th>
                                                <th>FABFINISH</th>
                                                <th>TOTAL FAB</th>
                                                <th>TOTAL FAB QC</th>
                                                <th>BLASTING</th>
                                                <th>PRIMER</th>
                                                <th>INTERM</th>
                                                <th>PAINTFINISH</th>
                                                <th>TOTAL PAINT</th>
                                                <th>TOTAL PAINTQC</th>
                                                <th>COLI #</th>
                                                <th>PACKING QTY</th>
                                                <th>PACKING WEIGHT</th>
                    </tr>
                </thead>
                <tbody>";              
                    while (($row = oci_fetch_array($generalInfoParse, OCI_BOTH)) != false){ 
                         $content .= "<tr>
                                        <td>$row[HEADMARK]</td>
                                        <td>$row[COMP_TYPE]</td>
                                        <td>$row[QTY]</td>
                                        <td>$row[WEIGHT]</td>
                                        <td>$row[FABMARKING]</td>
                                        <td>$row[FABCUTTING]</td>
                                        <td>$row[FABASSEMBLY]</td>
                                        <td>$row[FABWELDING]</td>
                                        <td>$row[FABDRILLING]</td>
                                        <td>$row[FABFINISHING]</td>
                                        <td>$row[TOTALFAB]</td>
                                        <td>$row[TOTALFABQC]</td>
                                        <td>$row[BLASTING]</td>
                                        <td>$row[PRIMER]</td>
                                        <td>$row[INTERM]</td>
                                        <td>$row[PAINTFINISH]</td>
                                        <td>$row[TOTALPAINT]</td>
                                        <td>$row[TOTALPAINTQC]</td>
                                        <td>$row[COLINUM]</td>
                                        <td>$row[PACKINGQTY]</td>
                                        <td>$row[WEIGHTPACK]</td>

                                      <tr>";
                    }
                $content .= "</tbody>
            </table>
        </div>
        </body>
        </html>";

    $list_header="
    <div style=\"display:block; background-color:#f2f2f2; padding:10px; border-bottom:2pt solid #cccccc; color:#6e6e6e; font-size:.85em; font-family:verdana;\">
      <div style=\"float:left; width:33%; text-align:left;\">
          <img src=\"images/wenlogo.jpg\">
      </div>
      <div style=\"float:left; width:33%; text-align:center;\">";
          $_SESSION[cd-dropdown]; $list_header .= "General Info Check List
      </div>
      <br style=\"clear:left;\"/>
    </div>";
    
    $list_footer="
    <div style=\"display:block;\">
      <div style=\"float:left; width:33%; text-align:left;\">
              &nbsp; 
      </div>
      <div style=\"float:left; width:33%; text-align:center;\">
             Page phptopdf_on_page_number of phptopdf_pages_total
      </div>
      <div style=\"float:left; width:33%; text-align:right;\">
              &nbsp;
       </div>
       <br style=\"clear:left;\"/>
    </div>";


    $pdf_options = array(
      "source_type" => 'html',
      "source" => $content,
      "action" => 'view',
      "page_orientation" => 'landscape',
      "file_name" => 'generalInfoList.pdf',
      "header" => $list_header,
      "footer" => $list_footer);


    // CALL THE phpToPDF FUNCTION WITH THE OPTIONS SET ABOVE
    phptopdf($pdf_options);

    // OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
    echo ("<a href='sample_pdf_report.pdf'>Download Your PDF</a>");
?>