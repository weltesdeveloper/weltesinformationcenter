<?php

include '../../dbinfo.inc.php';
include '../../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$dateStr = date('Y-m-d', strtotime('this sunday'));
$tanggal = date('Y-m-d');
if ($dateStr == $tanggal) {
    $sql = "UPDATE MST_OPNAME SET OPN_STATUS = 'CLOSE' WHERE OPN_ACT_DATE < TO_DATE('$dateStr', 'YYYY-MM-DD')";
    $parse = oci_parse($conn, $sql);
    $exe = oci_execute($parse);
    if ($exe) {
        oci_commit($conn);
    } else {
        oci_rollback($conn);
    }
} 