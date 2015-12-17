<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$periode = $_POST['periode'];
$type = $_POST['type'];
$head_mark = $_POST['head_mark'];
$opname_qty = $_POST['opname_qty'];
$price = $_POST['price'];

$opname_id = $_POST['opname_id'];
$deleteDtlOpnameSql = "DELETE FROM DTL_OPNAME_PNT WHERE OPNAME_ID = '$opname_id'";
$message = "";
$deleteDtlOpnameParse = oci_parse($conn, $deleteDtlOpnameSql);
$deleteDtlOpname = oci_execute($deleteDtlOpnameParse);
if ($deleteDtlOpname) {
    for ($i = 0; $i < sizeof($head_mark); $i++) {
        $insertDtlOpnSql = "INSERT INTO DTL_OPNAME_PNT (OPNAME_ID, HEAD_MARK, OPNAME_QTY, OPNAME_PRICE) "
                . "VALUES('$opname_id', '$head_mark[$i]', '$opname_qty[$i]', '$price[$i]')";
        $insertDtlOpnParse = oci_parse($conn, $insertDtlOpnSql);
        $insertDtlOpn = oci_execute($insertDtlOpnParse);
        if ($insertDtlOpn) {
            oci_commit($conn);
            $message .="SUCCESS";
        } else {
            oci_rollback($conn);
            $message .="FAIL";
        }
    }
    if (strpos($message, "FAIL") == true) {
        echo "FAIL UPDATE";
    } else {
        echo "SUCCESS UPDATE";
    }
}
