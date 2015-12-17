<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$ACTION = $_GET['action'];
$username = 'hadi';
//$ACTION = 'viewDetBarcode';
switch ($ACTION) {
    case "viewSubJob";
        $sql = "SELECT DISTINCT (PROJECT_NAME) PROJECT_NAME FROM PAINTING WHERE PAINT_STATUS = 'NOTCOMPLETE' ORDER BY PROJECT_NAME";
        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;

    case "viewPaintingList";
        $job = $_GET['job__'];
        
        $sql = "
        SELECT HEAD_MARK,
           TOTAL_QTY,
           FINISHING
        FROM VW_PNT_INFO       
        WHERE PROJECT_NAME = '$job' AND FINISHING = PAINT_QC_PASS ORDER BY HEAD_MARK,ID";
//        WHERE PROJECT_NAME = '$job' AND FINISHING <> PAINT_QC_PASS ORDER BY HEAD_MARK,ID";

        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;
    
}
?>