<?php

include '../dbinfo.inc.php';
include '../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$tgl_trakhir_opname = SingleQryFld("select to_char(max(distinct(opn_act_date)+4), 'mm/dd/yyyy') tgl from mst_opname", $conn);
$DATE = strval(date("m/d/Y"));
if ($DATE == $tgl_trakhir_opname) {
    $sql = "UPDATE MST_OPNAME SET OPN_STATUS = 'CLOSE' WHERE OPN_ACT_DATE <  to_date('$tgl_trakhir_opname', 'MM/DD/YYYY')";
    $parse = oci_parse($conn, $sql);
    $exe = oci_execute($parse);
    if ($exe) {
        oci_commit($conn);
        echo "OPNAME SUDAH DI LOCK";
    } else {
        oci_rollback($conn);
        echo "OPNAME GAGAL DI LOCK";
    }
}else{
    echo "BUKAN SAAAT LOCK OPNAME BOSS";
}
/*-------------------------------------------------------------------------------------------------------------------------*/
$tgl_trakhir_opname_si = SingleQryFld("select to_char(max(distinct(opn_act_date)+4), 'mm/dd/yyyy') tgl from MST_OPNAME_SI", $conn);
$DATE = strval(date("m/d/Y"));
if ($DATE == $tgl_trakhir_opname) {
    $sql1 = "UPDATE MST_OPNAME SET OPN_STATUS = 'CLOSE' WHERE OPN_ACT_DATE <  to_date('$tgl_trakhir_opname_si', 'MM/DD/YYYY')";
    $parse1 = oci_parse($conn, $sql1);
    $exe1 = oci_execute($parse1);
    if ($exe1) {
        oci_commit($conn);
        echo "OPNAME SUDAH DI LOCK";
    } else {
        oci_rollback($conn);
        echo "OPNAME GAGAL DI LOCK";
    }
}else{
    echo "BUKAN SAAAT LOCK OPNAME BOSS";
}
