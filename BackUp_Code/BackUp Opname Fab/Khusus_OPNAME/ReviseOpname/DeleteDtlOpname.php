<?php

require_once '../../../../../dbinfo.inc.php';
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
setlocale(LC_MONETARY, "en_US");
// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$opnameID = $_GET['idopname'];
$headmark = $_GET['headmark'];
$projectname = $_GET['projectname'];

//CEK JIKA JUMLAH CUMA 1 MAKA MASTER DI DELETE
$countDtl = "SELECT COUNT(*) JUMLAH FROM DTL_OPNAME WHERE OPNAME_ID = '$opnameID'";
$parseCountDtl = oci_parse($conn, $countDtl);
oci_execute($parseCountDtl);
$jumlahrow = oci_fetch_array($parseCountDtl)['JUMLAH'];

if ($jumlahrow == 1) {
    $sqlDel = "DELETE FROM MST_OPNAME WHERE OPNAME_ID = '$opnameID'";
    $parseDel = oci_parse($conn, $sqlDel);
//    oci_bind_by_name($parseDel, ":OPNAMEID", $opnameID);
    $delete = oci_execute($parseDel);

     $sqlDel2 = "DELETE FROM DTL_OPNAME WHERE OPNAME_ID = '$opnameID' AND HEAD_MARK = '$headmark' AND PROJECT_NAME = '$projectname'";
    $parseDel2 = oci_parse($conn, $sqlDel2);
//    oci_bind_by_name($parseDel, ":OPNAMEID", $opnameID);
//    oci_bind_by_name($parseDel, ":HEADMARK", $headmark);
//    oci_bind_by_name($parseDel, ":PROJECTNAME", $projectname);
    $delete2 = oci_execute($parseDel2);
    if ($delete2 && $delete) {
        oci_commit($conn);
//        echo "OPNAME $opnameID WITH HEAD MARK $HEAD_MARK AND PROJECT NAME $projectname HAS BEEN DELETED FROM MASTER OPNAME";
        echo "success";
    } else {
        oci_rollback($conn);
//        echo "ERROR DELETE $opnameID";
        echo "fail";
    }
} else {
    $sqlDel2 = "DELETE FROM DTL_OPNAME WHERE OPNAME_ID = '$opnameID' AND HEAD_MARK = '$headmark' AND PROJECT_NAME = '$projectname'";
    $parseDel2 = oci_parse($conn, $sqlDel2);
//    oci_bind_by_name($parseDel, ":OPNAMEID", $opnameID);
//    oci_bind_by_name($parseDel, ":HEADMARK", $headmark);
//    oci_bind_by_name($parseDel, ":PROJECTNAME", $projectname);
    $delete2 = oci_execute($parseDel2);
    if ($delete2) {
        oci_commit($conn);
        echo "success";
//        echo "OPNAME $opnameID WITH HEAD MARK $headmark AND PROJECT NAME $projectname HAS BEEN DELETED FROM MASTER OPNAME";
    } else {
        oci_rollback($conn);
//        echo "ERROR DELETE $opnameID";
        echo "fail";
    }
}

