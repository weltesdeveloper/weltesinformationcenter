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
?>     

<?php

$ACTION = $_POST['action'];
switch ($ACTION) {
        
    case 'ViewListWaste':
        $query = "SELECT WASTE_NM,WASTE_WIDTH,WASTE_LENGTH,WASTE_WEIGHT,WASTE_GRADE,WASTE_INP_DT FROM MST_WASTE@WELTES_LOGINV_LINK";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        
        $arr=array();
        while ($row = oci_fetch_assoc($hasil)){
            array_push($arr, $row);
        }
        
        echo json_encode($arr);

        break;

}
?>