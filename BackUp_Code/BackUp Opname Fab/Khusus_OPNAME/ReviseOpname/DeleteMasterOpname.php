<?php

require_once '../../../../../dbinfo.inc.php';
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
setlocale(LC_MONETARY, "en_US");
// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$opnameID = $_GET['opnameID'];

$sqlDel = "DELETE FROM MST_OPNAME WHERE OPNAME_ID = :OPNAMEID";
$parseDel = oci_parse($conn, $sqlDel);
oci_bind_by_name($parseDel, ":OPNAMEID", $opnameID);
$delete = oci_execute($parseDel);

//$sqlDel2 = "DELETE FROM DTL_OPNAME WHERE OPNAME_ID = :OPNAMEID";
//$parseDel2 = oci_parse($conn, $sqlDel2);
//oci_bind_by_name($parseDel2, ":OPNAMEID", $opnameID);
//$delete2 = oci_execute($parseDel2);
if ($delete) {
    oci_commit($conn);
    echo "OPNAME $opnameID HAS BEEN DELETED FROM MASTER OPNAME";
} else {
    oci_rollback($conn);
    echo "ERROR DELETE $opnameID";
}

