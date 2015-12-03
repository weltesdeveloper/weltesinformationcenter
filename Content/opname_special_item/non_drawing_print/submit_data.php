<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
session_start();
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$job = $_POST['job'];
$subjob = $_POST['subjob'];
$subcont = $_POST['subcont'];
$tanggal = $_POST['tanggal'];
$periode = $_POST['periode'];
$opname_id = $_POST['opname_id'];
$head_mark = $_POST['head_mark'];
$profile = $_POST['profile'];
$length = $_POST['length'];
$qty = $_POST['qty'];
$weight = $_POST['weight'];
$price = $_POST['price'];

$deleteDtlOpnameSql = "DELETE FROM DTL_OPNAME_SI WHERE OPNAME_ID = '$opname_id'";
$deleteDtlOpnameParse = oci_parse($conn, $deleteDtlOpnameSql);
$deleteDtlOpname = oci_execute($deleteDtlOpnameParse);
if ($deleteDtlOpname) {
    oci_commit($conn);
    echo "SUKSES DELETE DETAIL OPNAME ";
    for ($i = 0; $i < sizeof($head_mark); $i++) {
        $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME_SI(OPNAME_ID, HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_WEIGHT, PROFILE, LENGTH) "
                . "VALUES('$opname_id', '$head_mark[$i]', '$qty[$i]', '$price[$i]', '$weight[$i]', '$profile[$i]', '$length[$i]')";
        echo $insertDtlOpnameSql;
        $insertDtlOpnameParse = oci_parse($conn, $insertDtlOpnameSql);
        $insertDtlOpname = oci_execute($insertDtlOpnameParse);
        if ($insertDtlOpname) {
            oci_commit($conn);
            echo "SUKSES";
        } else {
            echo "GAGAL";
        }
    }
} else {
    oci_rollback($conn);
    echo "GAGAL DELETE";
}