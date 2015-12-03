<?php
    
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

   $projectName = strval($_GET['var1']);
?>

<?php

    $compProfileSql =   "SELECT MASTER_DRAWING.PROJECT_NAME, MASTER_DRAWING.COMP_TYPE, COMP_TYPE_SUMM.JUMLAH JML_DWG,
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
                                                PAINTING.HEAD_MARK = MASTER_DRAWING.HEAD_MARK AND MASTER_DRAWING.PROJECT_NAME = :PROJNAME

                                                GROUP BY MASTER_DRAWING.COMP_TYPE,
                                                MASTER_DRAWING.PROJECT_NAME, COMP_TYPE_SUMM.JUMLAH";
                        $compProfileParse = oci_parse($conn, $compProfileSql);
                        oci_bind_by_name($compProfileParse, ":PROJNAME", $projectName);
                        oci_execute($compProfileParse);
    
    $content = "
    <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <meta charset=\"UTF-8\">
        <title>PRODUCTION REPORT Check List</title>
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
                        <th>COMPONENT</th>
                        <th>TOTAL DRAWING</th>
                        <th>DRAWING ASSIGNED</th>
                        <th>MARKING SUM</th>
                        <th>CUTTING SUM</th>
                        <th>ASSEMBLY SUM</th>
                        <th>WELDING SUM</th>
                        <th>DRILLING SUM</th>
                        <th>FAB FINISHING SUM</th>
                        <th>BLASTING SUM</th>
                        <th>PRIMER SUM</th>
                        <th>INTERMEDIATE SUM</th>
                        <th>PAINT FINISHING SUM</th>  
                    </tr>
                </thead>
                <tbody>";              
                    while (($row = oci_fetch_array($compProfileParse, OCI_BOTH)) != false){
                        $totalDrawingSql = "SELECT SUM(MASTER_DRAWING.TOTAL_QTY) TOTALQTY FROM MASTER_DRAWING WHERE MASTER_DRAWING.COMP_TYPE = :COMPTYPE AND MASTER_DRAWING.PROJECT_NAME = :PROJNAME";
                                                            $totalDrawingParse = oci_parse($conn, $totalDrawingSql);
                                                            oci_bind_by_name($totalDrawingParse, ":COMPTYPE", $row['COMP_TYPE']);
                                                            oci_bind_by_name($totalDrawingParse, ":PROJNAME", $projectName);
                                                            oci_define_by_name($totalDrawingParse, "TOTALQTY", $totalDrawingQty);
                                                            oci_execute($totalDrawingParse);
                                                            while(oci_fetch($totalDrawingParse)){$totalDrawingQty;}
                         $content .= "<tr>
                                        <td>$row[COMP_TYPE]</td>
                                        <td>$totalDrawingQty</td>
                                        <td>$row[JML_DWG]</td>
                                        <td>$row[JML_MARKING]</td>
                                        <td>$row[JML_CUTTING]</td>
                                        <td>$row[JML_ASSEMBLY]</td>
                                        <td>$row[JML_WELDING]</td>
                                        <td>$row[JML_DRILLING]</td>
                                        <td>$row[JML_FINISHING]</td>
                                        <td>$row[JML_BLASTING]</td>
                                        <td>$row[JML_PRIMER]</td>
                                        <td>$row[JML_INTERMEDIATE]</td>
                                        <td>$row[JML_FINISH]</td>
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
          $_SESSION[cd-dropdown]; $list_header .= "Production Report by Component List for $projectName
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
            Generated by $username
              &nbsp;
       </div>
       <br style=\"clear:left;\"/>
    </div>";


    $pdf_options = array(
      "source_type" => 'html',
      "source" => $content,
      "action" => 'view',
      "page_orientation" => 'landscape',
      "file_name" => 'sample_pdf_report.pdf',
      "header" => $list_header,
      "footer" => $list_footer);


    // CALL THE phpToPDF FUNCTION WITH THE OPTIONS SET ABOVE
    phptopdf($pdf_options);

    // OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
    echo ("<a href='sample_pdf_report.pdf'>Download Your PDF</a>");
?>