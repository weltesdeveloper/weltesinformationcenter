<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
require_once '../../smart_resize_image.function.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$job = $_POST['job'];
$subjob = $_POST['subjob'];
$sql = "SELECT DISTINCT COLI_NUMBER FROM COMP_VW_INFO_PCK WHERE PROJECT_NAME = '$subjob' ORDER BY COLI_NUMBER";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
$retVal = array();
while ($row = oci_fetch_assoc($parse)) {
    array_push($retVal, $row['COLI_NUMBER']);
}

echo json_encode($retVal);