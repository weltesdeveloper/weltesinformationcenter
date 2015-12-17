<?php

require_once '../koneksi_broo.php';
//require_once '../FunctionAct.php';
//session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
//if (!isset($_SESSION['username'])) {
//    echo <<< EOD
//       <h1>You are UNAUTHORIZED !</h1>
//       <p>INVALID usernames/passwords<p>
//       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
//EOD;
//    exit;
//}
// GENERATE THE APPLICATION PAGE
$conn_miko = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_LOGISTIC_DB1);
$conn_gobis = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_LOGISTIC_DB2);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
//oci_set_client_identifier($conn, $_SESSION['username']);
//$username = htmlentities($_SESSION['username'], ENT_QUOTES);
?>     

<?php
$ACTION = $_POST['action'];

$table = 'MST_PO_HIST_REV';
$CAT1__ = 'PO_NO';
$CAT2__ = "PO_REV";
$CAT3__ = "'-'";
$REM__ = 'PO_REM_REV';

        

switch ($ACTION) {
    case 'ViewList':
        $query = "SELECT M.$CAT1__ AS CAT1, $CAT2__ AS CAT2, $CAT3__ AS CAT3, to_char($REM__) as REM "
            . "FROM $table M "
            . "WHERE $REM__ IS NOT NULL  ";
        $hasil = oci_parse($conn_gobis, $query);
        oci_execute($hasil);
        $arr=array();
        while($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;
        
        
    case 'UpdateMiko':
        $CAT1 = $_POST['CAT1__'];
        $CAT2 = $_POST['CAT2__'];
        $CAT3 = $_POST['CAT3__'];
        $REM = $_POST['REM__'];

        for ($i = 0; $i < sizeof($CAT1); $i++) {
            $insertSql = "UPDATE $table SET $REM__ = '".  str_replace("'",'"',$REM[$i])."' WHERE $CAT1__='$CAT1[$i]'";
//                    . " AND INV_ID='$CAT2[$i]' AND PR_REV='$CAT3[$i]' ";
            
            $insertParse = oci_parse($conn_miko, $insertSql);
            $exe = oci_execute($insertParse);
            
            if ($exe) {
                oci_commit($conn_miko);
                echo json_encode("SUKSES");
            } else {
                echo json_encode("GAGAL");
            }
        }

        break;
        
        
        
}
?>