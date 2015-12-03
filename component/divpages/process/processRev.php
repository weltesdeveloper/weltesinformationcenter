<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
EOD;
    exit;
}
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);



$ACTION = $_POST['action'];
switch ($ACTION) {

    case 'ViewListCompRev':
        $job = $_POST['id1'];
        $jobname = $_POST['id2'];

        $q = "SELECT *
                    FROM MASTER_COMP
                   WHERE PROJECT_NO = '$job' AND PROJECT_NAME = '$jobname' ";
//        $q = "select COMP_NAME,COMP_PROFILE,COMP_LENGTH,COMP_WEIGHT, sum(COMP_MST_QTY) COMP_MST_QTY "
//                . "from MASTER_COMP"
//                . " WHERE PROJECT_NO = '$job' AND PROJECT_NAME = '$jobname'"
//                . " GROUP BY COMP_NAME,COMP_PROFILE,COMP_LENGTH,COMP_WEIGHT ORDER BY COMP_NAME ASC";
        $h = oci_parse($conn, $q);
        oci_execute($h);
        $arr = array();
        while ($row = oci_fetch_array($h)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "edit_data":
        $comp_id = $_POST['comp_id__'];
        $setDataUpdate = $_POST['setDataUpdate__'];
        $newValue = str_replace("'", '"', $_POST['newValue']);

//        $sql_text = "UPDATE MASTER_COMP SET $setDataUpdate = '$newValue',ENTRY_DATE=SYSDATE,ENTRY_SIGN = '$username' WHERE HEAD_MARK = '$head_mark' ";
        $sql_text = "UPDATE MASTER_COMP SET $setDataUpdate = '$newValue' WHERE COMP_ID = '$comp_id' ";
        $parse = oci_parse($conn, $sql_text);
        $res = oci_execute($parse);
        if ($res) {
            oci_commit($conn);
            echo json_encode('success');
        } else {
            oci_rollback($conn);
            echo oci_error();
        }
        break;
}
?>