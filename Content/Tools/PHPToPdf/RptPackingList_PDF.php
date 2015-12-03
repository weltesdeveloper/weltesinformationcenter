<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

$DTAwal = $_GET['DTAwal'];
$DTAkhir = $_GET['DTAkhir'];

$projectValSQL = "PROJECT_NAME LIKE '%'";

if ($_GET["projData"] <> "ALL") {
    # code...
    $projectValSQL = 'PROJECT_NAME in ';
    $projNM = '(';
    list($proj_no, $proj_code) = explode("^", $_GET["projData"]);
    if ($proj_code == "ALL") {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    } else {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' AND PROJECT_CODE='$proj_code' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    }

    $projectNameParse = oci_parse($conn, $projectNameSql);
    oci_execute($projectNameParse);
    while ($projectNameROW = oci_fetch_array($projectNameParse)) {
        $projNM .= "'" . $projectNameROW['PROJECT_NAME'] . "',";
    }
    $projectValSQL .= substr_replace($projNM, "", -1) . ")";
    // echo "$projectValSQL";
}

$dt1 = new DateTime($DTAwal);
$dt2 = new DateTime($DTAkhir);
?>

<?php

// $Content = "";
$Content = "
<!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <meta charset=\"UTF-8\">
        <title>PACKING REPORT</title>
        </head>
        <body>
    <table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">
	<thead>
		<tr style=\"font-size:11px;\">
			<th>Form. 5</th>
			<th rowspan=\"2\">Packing No.</th>
			<th rowspan=\"2\">Marking No.</th>
			<th colspan=\"2\">Description</th>
			<th rowspan=\"2\">Overal<br>Length<br>(mm)</th>
			<th rowspan=\"2\">Qty<br>(Pcs)</th>
			<th rowspan=\"2\">Unit Weight</th>
			<th rowspan=\"2\">Sub Total Weight</th>
			<th rowspan=\"2\">Total Weight</th>
			<th colspan=\"3\">Dimension (mm)</th>
			<th rowspan=\"2\">Volume (m<sup>3</sup>)</th>
			<!--<th rowspan=\"2\">Ship No.</th>-->
			<th rowspan=\"2\">Do. No.</th>
			<th rowspan=\"2\">Vehicle License</th>
			<th rowspan=\"2\">DO. Date</th>
			<th rowspan=\"2\">Prepared By</th>
			<th rowspan=\"2\">Checked</th>
		</tr>
		<tr style=\"font-size:11px;\">
			<th>No</th>
			<th>Main Name</th>
			<th>Main Profile</th>
			<th>P</th>
			<th>L</th>
			<th>T</th>
		</tr>
	</thead>
	<tbody>";
$i = 1;
// where WELTESADMIN.PACKING_DATE between $DateFirst and $DateLast
$sqlPck = oci_parse($conn, "SELECT PCK.* FROM MST_PACKING PCK WHERE $projectValSQL AND ACT_PACK_DATE BETWEEN TO_DATE('$DTAwal 00:00:01', 'MM/DD/YYYY HH24:MI:SS') AND TO_DATE('$DTAkhir 23:59:59', 'MM/DD/YYYY HH24:MI:SS') ORDER BY PCK.COLI_NUMBER");
oci_execute($sqlPck);
while ($rowPck = oci_fetch_array($sqlPck, OCI_BOTH)) {

    $COLI_NUMBER = $rowPck['COLI_NUMBER'];

    $PACKING_LENGTH = $rowPck['PACK_LEN'];
    $PACKING_WIDTH = $rowPck['PACK_WID'];
    $PACKING_HEIGHT = $rowPck['PACK_HT'];
    $PACKING_VOLUME = round((($PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT) / 1000000000), 2);
    $PACKING_WEIGHT = SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM VW_PCK_INFO WHERE COLI_NUMBER='$COLI_NUMBER'", $conn);

    $DONumber = SingleQryFld("SELECT DO_NO FROM DTL_DELIV WHERE COLI_NUMBER = '$COLI_NUMBER'", $conn);
    $VehicleNo = SingleQryFld("SELECT VHC_NO FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);
    $DO_DATE = SingleQryFld("SELECT DO_DATE FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);
    $Content .= "<tr style=\"font-size:10px;text-align: center;\">
			<td>$i </td>
			<td>$COLI_NUMBER </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>$PACKING_WEIGHT </td>
			<td>$PACKING_LENGTH </td>
			<td>$PACKING_WIDTH </td>
			<td>$PACKING_HEIGHT </td>
			<td>$PACKING_VOLUME </td>
			<!--<td> </td>-->
			<td>$DONumber </td>
			<td>$VehicleNo </td>
			<td>$DO_DATE </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>";
    $SubTotWg = "";
    $TotWg = "";
    $fnlTotWg = "";

    $j = 0;
    $query = "SELECT * from MASTER_DRAWING LEFT JOIN DTL_PACKING ON MASTER_DRAWING.HEAD_MARK=DTL_PACKING.HEAD_MARK "
            . " where DTL_PACKING.COLI_NUMBER='" . $COLI_NUMBER . "' order By DTL_PACKING.HEAD_MARK";
    // echo "$query";
    $sqlPPck = oci_parse($conn, $query);
    oci_execute($sqlPPck);
    $jml = oci_num_fields($sqlPPck);
    while ($rowPPck = oci_fetch_array($sqlPPck, OCI_BOTH)) {
        $j++;
        $HEAD_MARK = $rowPPck['HEAD_MARK'];
        $COMP_TYPE = $rowPPck['COMP_TYPE'];
        $PROFILE = $rowPPck['PROFILE'];
        $OVLENGTH = $rowPPck['LENGTH'];
        $UNIT_QTY = $rowPPck['UNIT_PCK_QTY'];
        $UNIT_WEIGHT = $rowPPck['WEIGHT'];
        $SubTotWg = ($UNIT_QTY*$UNIT_WEIGHT);

        $Content .= "<tr style=\"font-size:10px;text-align: center;\">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>$HEAD_MARK </td>
			<td>$COMP_TYPE </td>
			<td>$PROFILE </td>
			<td>$OVLENGTH </td>
			<td>$UNIT_QTY </td>
			<td>$UNIT_WEIGHT </td>
			<td>$SubTotWg </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>";
    }
    $i++;
}
$Content .= "</tbody>	
	</table></body>
        </html>";


$list_header = "
    <div style=\"display:block; background-color:#f2f2f2; padding:10px; border-bottom:2pt solid #cccccc; color:#6e6e6e; font-size:.85em; font-family:verdana;\">
      <div style=\"float:left; width:33%; text-align:left;\">
          PT. WELTES ENERGI NUSANTARA
      </div>
      <div style=\"float:left; width:33%; text-align:center;\">";
$_SESSION[cd - dropdown];
$list_header .= "Master Of Packing List
      </div>
      <div style=\"float:left; width:33%; text-align:right;\">
            " . $dt1->format('D, M d, Y') . " - " . $dt2->format('D, M d, Y') . "
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
            Generated by $username
              &nbsp;
       </div>
       <br style=\"clear:left;\"/>
    </div>";


$pdf_options = array(
    "source_type" => 'html',
    "source" => $Content,
    "action" => 'view',
    "page_orientation" => 'landscape',
    "file_name" => 'rptPackingList.pdf',
    "header" => $list_header,
    "footer" => $list_footer);


// CALL THE phpToPDF FUNCTION WITH THE OPTIONS SET ABOVE
phptopdf($pdf_options);

// OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
echo ("<a href='rptPackingList.pdf'>Download Your PDF</a>");
?>