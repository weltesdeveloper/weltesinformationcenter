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

    case "insert_detil_img";
        $head_mark = $_GET['id1'];
        $element = $_GET['id2'];
        $remark = $_GET['id3'];
        

        $query = "INSERT INTO FAB_QC_MOBILE_DM (HEAD_MARK,ELEMENT_TYP,IMAGE_ID,REMARK) values('$head_mark','$element',IMG_ID_SEQ.NEXTVAL,'$remark')";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        
        
        if ($hasil) {
            echo json_encode('1');
        } else {
            echo json_encode('0');
        }
        break;
}
?>