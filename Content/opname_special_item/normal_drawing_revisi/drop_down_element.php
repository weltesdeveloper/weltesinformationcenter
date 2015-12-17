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
    case "change_tanggal":
        $job = array();
        $tanggal = $_POST['tanggal'];
        $tanggalSql = "SELECT DISTINCT PROJECT_NO FROM VW_INFO_OPNAME_FAB WHERE TO_CHAR(OPN_ACT_DATE, 'DD/MM/YYYY') = '$_POST[tanggal]'";
        $tanggalParse = oci_parse($conn, $tanggalSql);
        oci_execute($tanggalParse);
        while ($row = oci_fetch_assoc($tanggalParse)) {
            array_push($job, $row);
        }
        echo json_encode($job);
        break;
    case "change_job":
        $array_subjob = array();
        $job = $_POST['job'];
        $subjobSql = "SELECT DISTINCT PROJECT_NAME, PROJECT_NAME_NEW "
                . "FROM VW_INFO_OPNAME_FAB WHERE PROJECT_NO = '$job' "
                . "AND TO_CHAR(OPN_ACT_DATE, 'DD/MM/YYYY') = '$_POST[tanggal]' "
                . "ORDER BY PROJECT_NAME";
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
        $subcontSql = "SELECT DISTINCT SUBCONT_ID "
                . "FROM VW_INFO_OPNAME_FAB "
                . "WHERE PROJECT_NO = '$job' "
                . "AND PROJECT_NAME = '$subjob' "
                . "AND TO_CHAR(OPN_ACT_DATE, 'DD/MM/YYYY') = '$_POST[tanggal]' "
                . "AND SUBCONT_ID IS NOT NULL "
                . "ORDER BY SUBCONT_ID";
        $subcontParse = oci_parse($conn, $subcontSql);
        oci_execute($subcontParse);
        while ($row = oci_fetch_assoc($subcontParse)) {
            array_push($array_subcont, $row);
        }
        echo json_encode($array_subcont);
        break;
    case "change_subcont":
        $ARRAY = array();
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
        $subcont = $_POST['subcont'];
        $tanggal = $_POST['tanggal'];
        $sql = "SELECT DISTINCT OPNAME_ID, OPN_PERIOD "
                . "FROM VW_INFO_OPNAME_FAB "
                . "WHERE PROJECT_NO = '$job' "
                . "AND PROJECT_NAME = '$subjob' "
                . "AND TO_CHAR(OPN_ACT_DATE, 'DD/MM/YYYY') = '$_POST[tanggal]' "
                . "AND SUBCONT_ID = '$subcont'"
                . "ORDER BY SUBCONT_ID";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_assoc($parse)) {
            array_push($ARRAY, $row);
        }
        echo json_encode($ARRAY);
        break;
}