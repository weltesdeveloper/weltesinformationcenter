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

$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
switch ($_POST['action']) {
    case "change_job":
        $array_subjob = array();
        $job = $_POST['job'];
        $subjobSql = "SELECT DISTINCT PROJECT_NAME, PROJECT_NAME_NEW FROM VW_SHOW_OPNAME_PRC WHERE PROJECT_NO = '$job' ORDER BY PROJECT_NAME";
        $subjobParse = oci_parse($conn, $subjobSql);
        oci_execute($subjobParse);
        while ($row = oci_fetch_assoc($subjobParse)) {
            array_push($array_subjob, $row);
        }
        echo json_encode($array_subjob);
        break;
    case "change_subjob":
        $array_subcont = array();
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
        $subcontSql = "SELECT DISTINCT SUBCONT_ID FROM VW_SHOW_OPNAME_PRC WHERE PROJECT_NO = '$job' AND PROJECT_NAME = '$subjob' AND SUBCONT_ID IS NOT NULL ORDER BY SUBCONT_ID";
        $subcontParse = oci_parse($conn, $subcontSql);
        oci_execute($subcontParse);
        while ($row = oci_fetch_assoc($subcontParse)) {
            array_push($array_subcont, $row);
        }
        echo json_encode($array_subcont);
        break;
    default:
        break;
}
