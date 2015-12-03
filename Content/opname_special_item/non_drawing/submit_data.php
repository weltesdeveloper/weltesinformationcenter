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
$remark_item = $_POST['remark_item'];
$procentage = $_POST['procentage'];

$cekOpnameID = SingleQryFld("SELECT COUNT(*) FROM MST_OPNAME_SI WHERE OPNAME_ID = '$opname_id'", $conn);
if ($cekOpnameID == 0) {
    $insertMstOpnameSql = "INSERT INTO MST_OPNAME_SI(OPNAME_ID, PROJECT_NO, PROJECT_NAME, "
            . "SUBCONT_ID, OPN_PERIOD, OPN_SYS_DATE, OPN_ACT_DATE, OPN_REQ_SIGN, OPN_STATUS, OPN_TYPE) "
            . "VALUES('$opname_id', '$job', '$subjob', "
            . "'$subcont', '$periode', SYSDATE, TO_DATE('$tanggal', 'DD/MM/YYYY'), '$username', 'OPEN', 'NON DRAWING')";
//    echo $insertMstOpnameSql;
    $insertMstOpnameParse = oci_parse($conn, $insertMstOpnameSql);
    $insertMstOpname = oci_execute($insertMstOpnameParse);
    if ($insertMstOpname) {
        oci_commit($conn);
        echo "SUKSES";
        for ($i = 0; $i < sizeof($head_mark); $i++) {
            $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME_SI(OPNAME_ID, HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_WEIGHT, PROFILE, LENGTH, PROCEN_WEIGHT, REMARK) "
                    . "VALUES('$opname_id', '$head_mark[$i]', '$qty[$i]', '$price[$i]', '$weight[$i]', '$profile[$i]', '$length[$i]', '$procentage[$i]', '$remark_item[$i]')";
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
        echo "GAGAL";
    }
} else {
    for ($i = 0; $i < sizeof($head_mark); $i++) {
        $insertDtlOpnameSql = "INSERT INTO DTL_OPNAME_SI(OPNAME_ID, HEAD_MARK, TOTAL_QTY, OPN_PRICE, OPN_WEIGHT, PROFILE, LENGTH, PROCEN_WEIGHT, REMARK) "
                . "VALUES('$opname_id', '$head_mark[$i]', '$qty[$i]', '$price[$i]', '$weight[$i]', '$profile[$i]', '$length[$i]', '$procentage[$i]', '$remark_item[$i]')";
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
} 