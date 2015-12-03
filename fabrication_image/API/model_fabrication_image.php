<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../dbinfo.inc.php';
require_once '../../../../../../FunctionAct.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
// 
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
EOD;
    exit;
}
//
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$ACTION = $_GET['action'];
//$ACTION = "view_job";
switch ($ACTION) {

    case "view_job";
        $project_typ = $_GET['id'];
        $query = "SELECT DISTINCT project_no FROM project where PROJECT_TYP = '$project_typ'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "view_sub_job";
        $project_typ = $_GET['id1'];
        $project_no = $_GET['id2'];
        
//        $query = "select * from project where PROJECT_TYP = '$project_typ' AND PROJECT_NO = '$project_no'";
//        $hasil = oci_parse($conn, $query);
//        oci_execute($hasil);

        $query = "SELECT DISTINCT (PROJECT_NAME) PROJECT_NAME FROM VW_PROD_FAB WHERE PROJECT_NO = '$project_no'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
		
        echo json_encode($arr);
        break;

    case "view_head_mark";
        $project_no = $_GET['id1'];
        $project_name = $_GET['id2'];
        
        $query = "SELECT DISTINCT (head_mark) head_mark  FROM VW_PROD_FAB WHERE PROJECT_NO = '$project_no' AND PROJECT_NAME = '$project_name'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $r = oci_num_rows($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    
    case "insert_head_mark";
        $head_mark = $_GET['id'];

//        $query = "SELECT * FROM FAB_IMG where PROJECT_TYP = '$project_typ' and PROJECT_NO = '$project_no'  and PROJECT_NAME = '$project_name' and HEADMARK = '$head_mark'";
        $query = "SELECT * FROM FAB_IMG where HEADMARK = '$head_mark'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $data = oci_fetch_array($hasil);
        $row = oci_num_rows($hasil);

        if ($row == 1) {
            echo json_encode($row);
        } else {
//            $query = "INSERT INTO FAB_IMG (PROJECT_NO,PROJECT_NAME,PROJECT_TYP,HEADMARK,USERNAME) values('$project_no','$project_name','$project_typ','$head_mark','$_SESSION[username]')";
            $query = "INSERT INTO FAB_IMG (HEADMARK,USERNAME) values('$head_mark','$_SESSION[username]')";
            $hasil = oci_parse($conn, $query);
            oci_execute($hasil);

            echo json_encode($row);
        }

        break;

}
?>