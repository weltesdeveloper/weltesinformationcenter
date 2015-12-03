<?php

require_once '../../../dbinfo.inc.php';
// INCLUDE THE phpToPDF.php FILE
require("lib/phpToPDF.php");
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

//$_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
if (isset($_POST['cd-dropdown']))
    $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
?>

<?php

$date1 = $_GET['var1'];

$projectNameSql = "SELECT MDA.SUBCONT_ID, MDA.PROJECT_NAME, SUM(MD.WEIGHT * MDA.ASSIGNED_QTY) TOTALASSIGNED "
        . "FROM MASTER_DRAWING_ASSIGNED MDA INNER JOIN MASTER_DRAWING MD "
        . " ON MDA.HEAD_MARK = MD.HEAD_MARK AND DWG_STATUS = 'ACTIVE' WHERE TRUNC(ASSIGNMENT_DATE) = TO_DATE(:DATESELECTED, 'MM/DD/YYYY') "
        . "GROUP BY MDA.PROJECT_NAME, MDA.SUBCONT_ID ORDER BY MDA.SUBCONT_ID ASC";
$projectNameParse = oci_parse($conn, $projectNameSql);
oci_bind_by_name($projectNameParse, ":DATESELECTED", $date1);
oci_execute($projectNameParse);

$content = "
    <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <meta charset=\"UTF-8\">
        <title>Drawing Assigned to Subcontractor</title>
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
                        <th>SUBCONTRACTOR</th>
                        <th>PROJECT NUMBER</th>
                        <th>CLIENT INITIAL</th>
                        <th>BUILDING</th>
                        <th>TOTAL WEIGHT</th>
                        
                    </tr>
                </thead>
                <tbody>";
while (($row = oci_fetch_array($projectNameParse, OCI_BOTH)) != false) {

    $projectNumberSql = "SELECT PROJ.PROJECT_NO PROJECTNUMBER, PROJ.CLIENT_ID CLIENTID "
            . "FROM PROJECT PROJ WHERE PROJ.PROJECT_NAME = :PROJNAME";
    $projectNumberParse = oci_parse($conn, $projectNumberSql);
    oci_bind_by_name($projectNumberParse, ":PROJNAME", $row['PROJECT_NAME']);
    oci_define_by_name($projectNumberParse, "PROJECTNUMBER", $projectNo);
    oci_define_by_name($projectNumberParse, "CLIENTID", $client);
    oci_execute($projectNumberParse);
    while (oci_fetch($projectNumberParse)) {
        $projectNo;
        $client;
    }

    $clientInitialSql = "SELECT CLIENT.CLIENT_INITIAL CLIENTINITIAL FROM CLIENT WHERE CLIENT.CLIENT_ID = :CLID";
    $clientInitialParse = oci_parse($conn, $clientInitialSql);
    oci_bind_by_name($clientInitialParse, ":CLID", $client);
    oci_define_by_name($clientInitialParse, "CLIENTINITIAL", $clientInitial);
    oci_execute($clientInitialParse);
    while (oci_fetch($clientInitialParse)) {
        $clientInitial;
    }

    $content .= "<tr>
                                        <td>$row[SUBCONT_ID]</td>
                                        <td>$projectNo</td>
                                        <td>$clientInitial</td>
                                        <td>$row[PROJECT_NAME]</td>
                                        <td>$row[TOTALASSIGNED]</td>
                                     <tr>";
}
$content .= "</tbody>
            </table>
        </div>
        </body>
        </html>";

$list_header = "
    <div style=\"display:block; background-color:#f2f2f2; padding:10px; border-bottom:2pt solid #cccccc; color:#6e6e6e; font-size:.85em; font-family:verdana;\">
      <div style=\"float:left; width:33%; text-align:left;\">
         PT. WELTES ENERGI NUSANTARA
      </div>
      <div style=\"float:left; width:33%; text-align:center;\">";
$list_header .= "Drawing Assigned to subcont on <b>$date1</b>
      </div>
      <br style=\"clear:left;\"/>
    </div>";

$list_footer = "
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