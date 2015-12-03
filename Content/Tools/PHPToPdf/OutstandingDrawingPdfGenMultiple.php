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
   
   //$_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
    if(isset( $_POST['cd-dropdown'])) $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
?>

<?php

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
      $titleJdul = "Not Started Drawing Before ~ ".$dt1->format('D, M d, Y').".";
      $sqlDate = "AND ASSG_DATE <= TO_DATE('$date1 23:59:59','MM/DD/YYYY hh24:mi:ss')";
    }else{
      $sqlDate = "AND ASSG_DATE >= TO_DATE('$date1 00:00:01','MM/DD/YYYY hh24:mi:ss') AND ASSG_DATE <= TO_DATE('$date2 23:59:59','MM/DD/YYYY hh24:mi:ss')";
      $titleJdul = "Not Started Drawing Between ".$dt1->format('D, M d, Y')." To ".$dt2->format('D, M d, Y').".";
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

    $outstandingFabSql = "SELECT * FROM COMP_VW_INFO "
                          . "WHERE $projectValSQL $sqlDate AND ASSG_QTY > '0' AND "
                          . "$projKategori ORDER BY COMP_TYPE,HEAD_MARK";
    $outstandingFabParse = oci_parse($conn, $outstandingFabSql);
    // oci_bind_by_name($outstandingFabParse, ":PROJNAME", $projectVal);
    oci_execute($outstandingFabParse);
    
    $content = "
    <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <meta charset=\"UTF-8\">
        <title>Not Started Drawing Analysis</title>
        <style type=\"text/css\">
            .bs-example{
                margin: 20px;
            }
            table{
              border: solid;
            }
            table thead tr th{
              font-size: 14px;
              padding:3px;
            }
            table tbody tr td{
              font-size: 12px;
              padding:3px;
            }
        </style>
        </head>
        <body>
        <div class=\"bs-example\">
            <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                <thead>
                    <tr>
                                <th>Head Mark</th>
                                <th>Id</th>
                                <th>Entry Date</th>
                                <th>Comp Type</th>
                                <th>Profile</th>
                                <th>Drawing Qty</th>
                                <th>Assign Qty</th>
                                <th>Total Weight</th>
                                <th>Total Surface</th>
                                <th>Subcontractor</th>
                                <th>Fabrication Status</th>
                                <th>Painting Status</th>
                        
                    </tr>
                </thead>
                <tbody>";              
                    while ($row = oci_fetch_array($outstandingFabParse)){
                        
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
                        
                         $content .= "<tr>
                                        <td>".$row['HEAD_MARK']."</td>
                                        <td>".$row['ID']."</td>
                                        <td>".$row['ASSG_DATE']."</td>
                                        <td>".$row['COMP_TYPE']."</td>
                                        <td>".$row['PROFILE']."</td>
                                        <td>".$row['TOTAL_QTY']."</td>
                                        <td>".$row['ASSG_QTY']."</td>
                                        <td>".$row['WEIGHT']*$row['TOTAL_QTY']."</td>
                                        <td>".$row['SURFACE']*$row['TOTAL_QTY']."</td>
                                        <td>".$row['SUBCONT_ID']."</td>
                                        <td>$markingSign</td>
                                        <td>$blastSign</td>
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
         PT. WELTES ENERGI NUSANTARA
      </div>
      <div style=\"float:left; width:33%; text-align:center;\">";
         $list_header .= "$titleJdul
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
              Generated by : $username
       </div>
       <br style=\"clear:left;\"/>
    </div>";


    $pdf_options = array(
      "source_type" => 'html',
      "encoding" => 'UTF-8',
      "source" => $content,
      "action" => 'view',
      "page_size" => 'A3',
      "page_orientation" => 'portrait',
      "file_name" => 'sample_pdf_report.pdf',
      "header" => $list_header,
      "footer" => $list_footer);


    // CALL THE phpToPDF FUNCTION WITH THE OPTIONS SET ABOVE
    phptopdf($pdf_options);

    // OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
    echo ("<a href='sample_pdf_report.pdf'>Download Your PDF</a>");
?>