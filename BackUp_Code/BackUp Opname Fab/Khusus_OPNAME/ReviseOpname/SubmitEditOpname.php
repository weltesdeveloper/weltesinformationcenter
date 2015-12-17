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

$sama = $_POST['sama'];
$newidopname = $_POST['newidopname'];
$qtyOpname = $_POST['qtyOpname'];
$opnameprice = $_POST['opnameprice'];
$headMark = $_POST['headmark'];

$projectNo = $_POST['projectNo'];
$projectName = $_POST['projectName'];
$subcont = $_POST['subcont'];
$oldOpnameid = $_POST['oldOpnameid'];
$opnamedate = $_POST['opnamedate'] . " 00:00:01";
if ($sama == "true") {
    $updateDtlOpname = "UPDATE DTL_OPNAME SET TOTAL_QTY = '$qtyOpname', OPN_PRICE = '$opnameprice', OPN_REQ_SIGN = '$username' WHERE "
            . "OPNAME_ID = '$newidopname' AND HEAD_MARK = '$headMark' AND PROJECT_NAME = '$projectName'";
    $parseDtlOpname = oci_parse($conn, $updateDtlOpname);
    $execute = oci_execute($parseDtlOpname);
    if ($execute) {
        oci_commit($conn);
        echo "SUCCESS COMMIT";
    } else {
        oci_rollback($conn);
        echo "ERROR COMMIT";
    }
} else {
    $cekOpnameID = "SELECT COUNT(*) JUMLAH FROM DTL_OPNAME WHERE OPNAME_ID = '$newidopname'";
    $parseOpnameID = oci_parse($conn, $cekOpnameID);
    oci_execute($parseOpnameID);
    $jumlah = oci_fetch_array($parseOpnameID)['JUMLAH'];
    echo $jumlah;
    if ($jumlah == '0') {
        $sqlInsMstOpname = "INSERT INTO MST_OPNAME (OPNAME_ID,PROJECT_NO,SUBCONT_ID,OPN_SYS_DATE,OPN_ACT_DATE)"
                . "VALUES('$newidopname', '$projectNo', '$subcont', SYSDATE, TO_DATE('$opnamedate', 'MM/DD/YYYY HH24:MI:SS'))";
        $parseInsMstOpname = oci_parse($conn, $sqlInsMstOpname);
        $insertMaster = oci_execute($parseInsMstOpname);

        $sqlInsDtlOpname = "UPDATE DTL_OPNAME SET TOTAL_QTY = '$qtyOpname', OPN_PRICE = '$opnameprice', OPN_REQ_SIGN = '$username', OPNAME_ID = '$newidopname' WHERE "
            . "OPNAME_ID = '$oldOpnameid' AND HEAD_MARK = '$headMark' AND PROJECT_NAME = '$projectName'";
        $parseInsDtlOpname = oci_parse($conn, $sqlInsDtlOpname);
        $insertDtl = oci_execute($parseInsDtlOpname);
        if ($sqlInsMstOpname && $sqlInsDtlOpname) {
            oci_commit($conn);
            echo "SUCCESS COMMIT";
        } else {
            oci_rollback($conn);
            echo "ERROR COMMIT";
        }
    } else {
        $sqlInsDtlOpname = "UPDATE DTL_OPNAME SET TOTAL_QTY = '$qtyOpname', OPN_PRICE = '$opnameprice', OPN_REQ_SIGN = '$username', OPNAME_ID = '$newidopname' WHERE "
            . "OPNAME_ID = '$oldOpnameid' AND HEAD_MARK = '$headMark' AND PROJECT_NAME = '$projectName'";
        $parseInsDtlOpname = oci_parse($conn, $sqlInsDtlOpname);
        $insertDtl = oci_execute($parseInsDtlOpname);
        if ($sqlInsMstOpname && $sqlInsDtlOpname) {
            oci_commit($conn);
            echo "SUCCESS COMMIT";
        } else {
            oci_rollback($conn);
            echo "ERROR COMMIT";
        }
    }
}