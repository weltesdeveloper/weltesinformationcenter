<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../dbinfo.inc.php';
require_once '../../../../../../FunctionAct.php';
require_once '../API/fungsi/fungsi_thumb.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
// 
//if (!isset($_SESSION['username'])) {
//    echo <<< EOD
//       <h1>You are UNAUTHORIZED !</h1>
//       <p>INVALID usernames/passwords<p>
//       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
//EOD;
//    exit;
//}
//
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
//oci_set_client_identifier($conn, $_SESSION['username']);
//$username = htmlentities($_SESSION['username'], ENT_QUOTES);


print_r($_FILES);
$nama_file = $_FILES["file"]["name"];
$lob_upload = $_FILES["file"]["tmp_name"];

$head = $_POST['value1'];
$element = $_POST['value2'];
$remark = $_POST['value3'];
$subcont = $_POST['value4'];
$qty = $_POST['value5'];
$image_id = $_POST['value6'];
$user = $_POST['value7'];

$lob = oci_new_descriptor($conn, OCI_D_LOB);
if ($image_id == 0) {
    $stmt = oci_parse($conn, "INSERT INTO FAB_QC_MOBILE_DM (HEAD_MARK,ELEMENT_TYP,IMAGE_ID,REMARK,IMG,SUBCONT_ID,QTY,USERNAME, IMG_DATE)
               values('$head','$element',IMG_ID_SEQ.NEXTVAL,'$remark',EMPTY_BLOB(),'$subcont','$qty','$user', SYSDATE) returning IMG into :IMG");
} else {
    $stmt = oci_parse($conn, "INSERT INTO FAB_QC_MOBILE_DM (HEAD_MARK,ELEMENT_TYP,IMAGE_ID,REMARK,IMG,SUBCONT_ID,QTY,USERNAME, IMG_DATE)
               values('$head','$element','$image_id','$remark',EMPTY_BLOB(),'$subcont','$qty','$user',SYSDATE) returning IMG into :IMG");
}
oci_bind_by_name($stmt, ':IMG', $lob, -1, OCI_B_BLOB);
oci_execute($stmt, OCI_DEFAULT);
$lob->savefile($lob_upload);
oci_commit($conn);

$lob->free();
oci_free_statement($stmt);
oci_close($conn);
?>