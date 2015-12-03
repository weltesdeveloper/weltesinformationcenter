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

$ACTION = $_POST['action'];
switch ($ACTION) {
    
    case "ViewNestingFile";
        $query = "SELECT DISTINCT(NESTING_FILE) NESTING_FILE FROM MST_COMP_STOCK ";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;
        
    case 'listComptFinsh':
        $NestingFile = $_POST['NestingFile__'];

        $query = "SELECT MCS.COMP_NAME,
                        MCS.PROJECT_NO,
                        MCS.PROJECT_NAME,
                        MCS.CUTTING,
                        MCS.TANGGAL_CUTTING,
                        MCS.FINISHING,
                        MCS.TANGGAL_FINISHING,
                        MC.COMP_PROFILE,
                        MC.COMP_LENGTH,
                        MC.COMP_WEIGHT,
                        SUM (MC.COMP_MST_QTY) COMP_MST_QTY
                   FROM MST_COMP_STOCK MCS
                        LEFT OUTER JOIN MASTER_COMP MC ON MC.COMP_NAME = MCS.COMP_NAME
                  WHERE MCS.NESTING_FILE = '$NestingFile'
               GROUP BY MCS.COMP_NAME,
                        MCS.PROJECT_NO,
                        MCS.PROJECT_NAME,
                        MCS.CUTTING,
                        MCS.TANGGAL_CUTTING,
                        MCS.FINISHING,
                        MCS.TANGGAL_FINISHING,
                        MC.COMP_PROFILE,
                        MC.COMP_LENGTH,
                        MC.COMP_WEIGHT
               ORDER BY MCS.COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;
        
    case 'inputStockCompFinshAll':
        $NestingFile = $_POST['NestingFile__'];
        $dateFinsh = $_POST['dateFinsh__'];
        $mesinFinsh = $_POST['mesinFinsh__'];
        $compName = $_POST['compName__'];
        $finsh = $_POST['finsh__'];

        for ($i = 0; $i < sizeof($compName); $i++) {
            $insertSql = "BEGIN SP_MST_COMP_STOCK_FINSH "
                    . "('$compName[$i]','$finsh[$i]','$dateFinsh','$mesinFinsh','$NestingFile'); "
                    . "END;";
            echo $insertSql;
            $insertParse = oci_parse($conn, $insertSql);
            $exe = oci_execute($insertParse);
            if ($exe) {
                oci_commit($conn);
                echo json_encode("SUKSES");
            } else {
                echo json_encode("GAGAL");
            }
        }

        break;
}
?>