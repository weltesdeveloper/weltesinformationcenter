<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
require_once '../../smart_resize_image.function.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$job = $_POST['job'];
$sql = "SELECT DISTINCT PROJECT_NAME_OLD, PROJECT_NAME_NEW FROM VW_PROJ_INFO WHERE PROJECT_NO = '$job' AND PROJECT_TYP = 'STRUCTURE' ORDER BY PROJECT_NAME_NEW";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
$retVal = array();
while ($row = oci_fetch_assoc($parse)) {
    array_push($retVal, $row);
}

echo json_encode($retVal);