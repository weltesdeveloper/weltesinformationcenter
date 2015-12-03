<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../../dbinfo.inc.php';
require_once '../../../../../../../FunctionAct.php';
//require_once '../API/fungsi/fungsi_thumb.php';
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



//$lob_upload = $_FILES["lob_upload"]["name"];
$lob_upload = $_FILES["lob_upload"]["tmp_name"];

$lob = oci_new_descriptor($conn, OCI_D_LOB);
$stmt = oci_parse($conn, "insert into FAB_QC_MOBILE_DM (IMG)
               values(EMPTY_BLOB()) returning IMG into :IMG");
oci_bind_by_name($stmt, ':IMG', $lob, -1, OCI_B_BLOB);
oci_execute($stmt, OCI_DEFAULT);
if ($lob->savefile($lob_upload)) {
    oci_commit($conn);
    echo "Blob successfully uploaded\n";
} else {
    echo "Couldn't upload Blob\n";
}
$lob->free();
oci_free_statement($stmt);
oci_close($conn);


?>