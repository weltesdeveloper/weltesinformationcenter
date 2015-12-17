<?php

require_once '../../../../../dbinfo.inc.php';
require_once '../../../../../FunctionAct.php';
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


$headmark = $_POST['headmark'];
$opnameqty = $_POST['opnameqty'];
$opnameprice = $_POST['opnameprice'];
$date = $_POST['date'];
$date = new DateTime($_POST['date']);
$date = $date->format("m/d/Y 00:00:01");
$opnameid = $_POST['opnameid'];
$subcontid = $_POST['subcontid'];
$projectno = $_POST['projectno'];
$projectname = $_POST['projectname'];
$idduplikat = intval($_POST['idduplikat']);
$periode = $_POST['periode'];
// CEK APAKAH MASTER SUDAH ADA JIKA 0 MASIH BELUM ADA
if ($idduplikat == 0) {
    $sqlInsMstOpname = "INSERT INTO MST_OPNAME (OPNAME_ID,PROJECT_NO,SUBCONT_ID,OPN_SYS_DATE,OPN_ACT_DATE, OPN_PERIOD, PROJECT_NAME, OPN_REQ_SIGN, OPN_STATUS)"
            . "VALUES('$opnameid', '$projectno', '$subcontid', SYSDATE, TO_DATE('$date', 'MM/DD/YYYY HH24:MI:SS'), '$periode', '$projectname', '$username', 'OPEN')";
    $parseInsMstOpname = oci_parse($conn, $sqlInsMstOpname);
    $insertMaster = oci_execute($parseInsMstOpname);
    if ($insertMaster) {
        oci_commit($conn);
        for ($i = 0; $i < sizeof($headmark); $i++) {
            $sqlInsDtlOpname = "INSERT INTO DTL_OPNAME (HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPNAME_ID, OPN_REQ_SIGN, PROJECT_NAME, OPN_SYS_DATE, OPN_ACT_DATE)"
                    . "VALUES('$headmark[$i]', '$opnameqty[$i]', '$opnameprice[$i]', '$opnameid', '$username', '$projectname', SYSDATE, TO_DATE('$date', 'MM/DD/YYYY HH24:MI:SS'))";
            $parseInsDtlOpname = oci_parse($conn, $sqlInsDtlOpname);
            $insertDtl = oci_execute($parseInsDtlOpname);
            if ($insertDtl) {
                oci_commit($conn);
            } else {
                oci_rollback($conn);
            }
        }
        echo "SUCCESS INSERT OPNAME $opnameid PROJECT NAME $projectname AND SUBCONT NAME $subcontid";
    } else {
        oci_rollback($conn);
        echo "FAIL INSERT OPNAME";
    }
} else {
    for ($i = 0; $i < sizeof($headmark); $i++) {
        $sqlInsDtlOpname = "INSERT INTO DTL_OPNAME (HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPNAME_ID, OPN_REQ_SIGN, PROJECT_NAME, OPN_SYS_DATE, OPN_ACT_DATE)"
                . "VALUES('$headmark[$i]', '$opnameqty[$i]', '$opnameprice[$i]', '$opnameid', '$username', '$projectname', SYSDATE, TO_DATE('$date', 'MM/DD/YYYY HH24:MI:SS'))";
        $parseInsDtlOpname = oci_parse($conn, $sqlInsDtlOpname);
        $insertDtl = oci_execute($parseInsDtlOpname);
        if ($insertDtl) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }
    }
    echo "SUCCESS INSERT OPNAME $opnameid PROJECT NAME $projectname AND SUBCONT NAME $subcontid";
}