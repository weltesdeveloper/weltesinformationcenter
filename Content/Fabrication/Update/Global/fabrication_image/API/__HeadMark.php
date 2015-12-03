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

		//$query = "SELECT DISTINCT (head_mark) head_mark  FROM VW_PROD_FAB WHERE PROJECT_NO = '$project_no' AND PROJECT_NAME = '$project_name' ORDER BY head_mark asc";
        $query = "SELECT DISTINCT (head_mark) head_mark  FROM VW_PROD_FAB ORDER BY head_mark asc";
		$hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
		
        echo json_encode($arr);
        break;

}
?>