<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../dbinfo.inc.php';
require_once '../../../../../../FunctionAct.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
// 
//if (!isset($_SESSION['username'])) {
//    echo <<< EOD
//       <h1>You are UNAUTHORIZED !</h1>
//      <p>INVALID usernames/passwords<p>
//       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
//EOD;
//    exit;
//}
//
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
//oci_set_client_identifier($conn, 'hadi');
//$username = htmlentities('hadi', ENT_QUOTES);
//$ACTION = $_GET['action'];
$ACTION = "view_sub_job";
switch ($ACTION) {

    case "view_sub_job";
        //$project_typ = $_GET['id1'];
        //$project_no = $_GET['id2'];

        $query = "SELECT PROJECT_NO, PROJECT_NAME, HEAD_MARK FROM VW_PROD_FAB WHERE  PROJECT_NO = 'W15030' ORDER BY PROJECT_NO asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

//        $arr = array();
//        while ($row = oci_fetch_assoc($hasil)) {
//            array_push($arr, $row);
//        }
//        echo json_encode($arr);

        $quiz = array();
        while ($row = oci_fetch_assoc($hasil)) {
            // you don't need to check num_rows
            // fetch_assoc returns false after the last row, so you can do this
            // which is cleaner
            if (!isset($quiz[$row['PROJECT_NO']])) {
//                if (!isset($row['PROJECT_NAME'])) {
                    $quiz[$row['PROJECT_NO']] = array(
//                    'question' => $row['question_text'],
                        'PROJECT_NAME' => array()
                    );
//                }
            }
            $quiz[$row['PROJECT_NO']]['PROJECT_NAME'][] = $row['PROJECT_NAME'];
        }
        echo json_encode($quiz);

        break;
}
?>