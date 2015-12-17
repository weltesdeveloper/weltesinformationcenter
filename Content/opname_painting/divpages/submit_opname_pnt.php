<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$head_mark = $_POST['head_mark'];
$opname_qty = $_POST['opname_qty'];
$price = $_POST['price'];
$type = $_POST['type'];
$periode = $_POST['periode'];
$tgl_opname = $_POST['tgl_opname'];
$project_name = $_POST['project_name'];

$project_code = SingleQryFld("SELECT PROJECT_CODE FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$project_name'", $conn);
$project_no = SingleQryFld("SELECT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$project_name'", $conn);

$opname_id = "$type-$project_no-$project_code-$periode";

//JIKA MASTER OPNAME MASIH BELUM ADA
$array_dtl = "";
$cekOpnameID = SingleQryFld("SELECT COUNT(*) FROM MST_OPNAME_PNT WHERE OPNAME_ID = '$opname_id'", $conn);
if ($cekOpnameID == 0) {
    $insertMstOpnSql = "INSERT INTO MST_OPNAME_PNT (OPNAME_ID, OPNAME_DATE, OPNAME_SYSDATE, OPNAME_SIGN, OPNAME_SUBCONT, OPNAME_TYPE, OPNAME_PERIOD) 
    VALUES ('$opname_id', to_date('$tgl_opname', 'MM/DD/YYYY'), SYSDATE, '$username', 'GUNADI', '$type', '$periode')";
    $insertMstOpnParse = oci_parse($conn, $insertMstOpnSql);
    $insertMstOpn = oci_execute($insertMstOpnParse);
    if ($insertMstOpn) {
        for ($i = 0; $i < sizeof($head_mark); $i++) {
            $insertDtlOpnSql = "BEGIN SP_DTL_OPNAME_PNT_INS('$opname_id', '$head_mark[$i]', '$opname_qty[$i]', '$price'); END;";
            $insertDtlOpnParse = oci_parse($conn, $insertDtlOpnSql);
            $insertDtlOpn = oci_execute($insertDtlOpnParse);
            if ($insertDtlOpn) {
                oci_commit($conn);
                $array_dtl.="SUCCESS";
            } else {
                oci_rollback($conn);
                $array_dtl.="FAIL";
            }
        }
        if (strpos($array_dtl, "FAIL") == true) {
            echo "FAIL INSERT DTL HEAD MARK";
        } else {
            echo "SUCCESS INSERT";
        }
    } else {
        oci_rollback($conn);
        echo "FAIL INSERT";
    }
} else {
    for ($i = 0; $i < sizeof($head_mark); $i++) {
        $insertDtlOpnSql = "BEGIN SP_DTL_OPNAME_PNT_INS('$opname_id', '$head_mark[$i]', '$opname_qty[$i]', '$price'); END;";
//        echo "$insertDtlOpnSql";
        $insertDtlOpnParse = oci_parse($conn, $insertDtlOpnSql);
        $insertDtlOpn = oci_execute($insertDtlOpnParse);
        if ($insertDtlOpn) {
            oci_commit($conn);
            $array_dtl.="SUCCESS";
        } else {
            oci_rollback($conn);
            $array_dtl.="FAIL";
        }
    }
    if (strpos($array_dtl, "FAIL") == true) {
        echo "FAIL INSERT DTL HEAD MARK";
    } else {
        echo "SUCCESS INSERT";
    }
}