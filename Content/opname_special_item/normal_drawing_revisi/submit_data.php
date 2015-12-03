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

//JIKA HEAD MARK ADA
if (isset($_POST['head_mark'])) {
    $head_mark = $_POST['head_mark'];
    $profile = $_POST['profile'];
    $length = $_POST['length'];
    $qty = $_POST['qty'];
    $weight = $_POST['unit_weight'];
    $price = $_POST['price'];
    $procent = $_POST['procent'];
    $deleteDtlOpnameSql = "DELETE FROM DTL_OPNAME WHERE OPNAME_ID = '$opname_id'";
    $deleteDtlOpnameParse = oci_parse($conn, $deleteDtlOpnameSql);
    $deleteDtlOpname = oci_execute($deleteDtlOpnameParse);
    if ($deleteDtlOpname) {
        oci_commit($conn);
        echo "SUKSES DELETE DETAIL OPNAME ";
        for ($index = 0; $index < sizeof($head_mark); $index++) {
            $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME(HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_REQ_SIGN, PROJECT_NAME, OPN_SYS_DATE, OPN_ACT_DATE, OPNAME_ID, PROCEN_WEIGHT) "
                    . "VALUES('$head_mark[$index]', '$qty[$index]', '$price[$index]', '$username', '$subjob', SYSDATE, TO_DATE('$tanggal', 'DD/MM/YYYY hh24:mi:ss'), '$opname_id', '$procent[$index]')";
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
}
//JIKA HEAD MARK TIDAK ADA
else {
    $deleteDtlOpnameSql = "DELETE FROM MST_OPNAME WHERE OPNAME_ID = '$opname_id'";
    $deleteDtlOpnameParse = oci_parse($conn, $deleteDtlOpnameSql);
    $deleteDtlOpname = oci_execute($deleteDtlOpnameParse);
    if ($deleteDtlOpname) {
        oci_commit($conn);
        echo "SUKSES DELETE";
    } else {
        echo "GAGAL DELETE";
    }
}