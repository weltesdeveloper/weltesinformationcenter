<?php

echo 'error.. hub developer';
exit();

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
$date2 = $_GET['var2'];

$projectNameSql = "SELECT MASTER_DRAWING.PROJECT_NAME,MASTER_DRAWING.HEAD_MARK,PROFILE,UNIT_QTY,LENGTH,"
        . " WEIGHT,SURFACE,FABRICATION.ENTRY_DATE,SUBCONT_ID,ID "
        . " FROM MASTER_DRAWING INNER JOIN FABRICATION ON (MASTER_DRAWING.PROJECT_NAME = FABRICATION.PROJECT_NAME) "
        . " AND (MASTER_DRAWING.HEAD_MARK = FABRICATION.HEAD_MARK) "
        . " WHERE MASTER_DRAWING.DWG_STATUS='ACTIVE' AND FABRICATION.ENTRY_DATE >= TO_DATE('$date1 00:00:01', 'MM/DD/YYYY hh24:mi:ss') "
        . " AND FABRICATION.ENTRY_DATE <= TO_DATE ('$date2 23:59:59', 'MM/DD/YYYY hh24:mi:ss') "
        . " ORDER BY SUBCONT_ID,MASTER_DRAWING.PROJECT_NAME,MASTER_DRAWING.HEAD_MARK,ID";
$projectNameParse = oci_parse($conn, $projectNameSql);
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
                        <th>SUBCONT</th>
                        <th>PROJECT NAME</th>
                        <th>HEAD MARK</th>
                        <th>ID</th>
                        <th>PROFILE</th>
                        <th>QTY</th>
                        <th>LENGTH</th>
                        <th>WEIGHT</th>
                        <th>TOT. WEIGHT</th>
                        <th>AREA</th>
                        <th>TOT. AREA</th>
                        <th>ASSIGN DATE</th>
                    </tr>
                </thead>
                <tbody>";
while ($row = oci_fetch_array($projectNameParse)) {
    $content .= "<tr>
                        <td>$row[SUBCONT_ID]</td>
                        <td>$row[PROJECT_NAME]</td>
                        <td>$row[HEAD_MARK]</td>
                        <td>$row[ID]</td>
                        <td>$row[PROFILE]</td>
                        <td>$row[UNIT_QTY]</td>
                        <td>$row[LENGTH]</td>
                        <td>$row[WEIGHT]</td>
                        <td>" . $row['WEIGHT'] * $row['UNIT_QTY'] . "</td>
                        <td>$row[SURFACE]</td>
                        <td>" . $row['SURFACE'] * $row['UNIT_QTY'] . "</td>
                        <td>$row[ENTRY_DATE]</td>
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
$list_header .= "Drawing Assigned to Subcontractor between <b>$date1</b> to <b>$date2</b>
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