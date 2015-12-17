<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$job = $_POST['job'];
$subjob = $_POST['subjob'];
$subcont = $_POST['subcont'];
$tanggal_opname = $_POST['tanggal_opname'] . "00:00:01";
$periode = $_POST['periode'];
$headmark = $_POST['headmark'];
$opnameqty = $_POST['opnameqty'];
$opnameprice = $_POST['opnameprice'];
$opname_id = str_replace("/", "", $_POST['opname_id']);
$cekMstOpname = SingleQryFld("SELECT COUNT(*) FROM MST_OPNAME WHERE OPNAME_ID = '$opname_id'", $conn);
if ($cekMstOpname == 0) {
    $insertMstOpnameSql = "INSERT INTO MST_OPNAME(OPNAME_ID, PROJECT_NO, SUBCONT_ID, OPN_SYS_DATE, OPN_ACT_DATE, OPN_PERIOD, PROJECT_NAME, OPN_REQ_SIGN, OPN_STATUS) "
            . "VALUES('$opname_id', '$job', '$subcont', SYSDATE, TO_DATE('$tanggal_opname', 'DD/MM/YYYY hh24:mi:ss'), '$periode', '$subjob', '$username', 'OPEN')";
    $insertMstOpnameParse = oci_parse($conn, $insertMstOpnameSql);
    $insertMstOpname = oci_execute($insertMstOpnameParse);
    if ($insertMstOpname) {
        oci_commit($conn);
        for ($index = 0; $index < sizeof($headmark); $index++) {
            $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME(HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_REQ_SIGN, PROJECT_NAME, OPN_SYS_DATE, OPN_ACT_DATE, OPNAME_ID) "
                    . "VALUES('$headmark[$index]', '$opnameqty[$index]', '$opnameprice[$index]', '$username', '$subjob', SYSDATE, TO_DATE('$tanggal_opname', 'DD/MM/YYYY hh24:mi:ss'), '$opname_id')";
            $insertDtlOpnameParse = oci_parse($conn, $insertDtlOpnameSql);
            $insertDtlOpname = oci_execute($insertDtlOpnameParse);
            if ($insertDtlOpname) {
                oci_commit($conn);
                echo "SUKSES";
            } else {
                oci_rollback($conn);
                echo "GAGAL";
            }
        }
    } else {
        oci_rollback($conn);
    }
} else {
    for ($index = 0; $index < sizeof($headmark); $index++) {
        $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME(HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_REQ_SIGN, PROJECT_NAME, OPN_SYS_DATE, OPN_ACT_DATE, OPNAME_ID) "
                . "VALUES('$headmark[$index]', '$opnameqty[$index]', '$opnameprice[$index]', '$username', '$subjob', SYSDATE, TO_DATE('$tanggal_opname', 'DD/MM/YYYY hh24:mi:ss'), '$opname_id')";
        $insertDtlOpnameParse = oci_parse($conn, $insertDtlOpnameSql);
        $insertDtlOpname = oci_execute($insertDtlOpnameParse);
        if ($insertDtlOpname) {
            oci_commit($conn);
            echo "SUKSES";
        } else {
            oci_rollback($conn);
            echo "GAGAL";
        }
    }
}
