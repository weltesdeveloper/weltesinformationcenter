<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

//$ACTION = $_GET['action'];
$username = 'hadi';
//$ACTION = 'viewDetBarcode';
//switch ($ACTION) {
//    case "viewColiDetail":
$coli = $_POST['id1'];
$query = "SELECT HEAD_MARK, UNIT_PCK_QTY, WEIGHT FROM VW_PCK_INFO WHERE coli_number = '$coli'";
$hasil = oci_parse($conn, $query);
oci_execute($hasil);

$arr = array();
while ($row = oci_fetch_assoc($hasil)) {
    array_push($arr, $row);
}
echo json_encode($arr);
//break;
//}
//        break; CN.W-IGG.BGD.7271
//}
?>