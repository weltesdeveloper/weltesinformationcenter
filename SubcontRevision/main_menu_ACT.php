<?php

require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';

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
switch ($_POST['action']) {
    case "change_subjob":
        $array = array();
        $project_name = $_POST['project_name'];
        $sql = "SELECT DISTINCT SUBCONT_ID FROM MASTER_DRAWING_ASSIGNED WHERE PROJECT_NAME = '$project_name' ORDER BY SUBCONT_ID ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;

    case "change_subcont":
        $array = array();
        $project_name = $_POST['project_name'];
        $subcont = $_POST['subcont'];
        $sql = "SELECT * FROM MASTER_DRAWING_ASSIGNED WHERE PROJECT_NAME = '$project_name' AND SUBCONT_ID = '$subcont'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row1 = oci_fetch_array($parse)) {
            array_push($array, $row1);
        }
        echo json_encode($array);
        break;

    case "update_assign":
        switch ($_POST['type']) {
            case "subcont":
                $project_name = $_POST['project_name'];
                $subcont_id = strtoupper($_POST['subcont_id']);
                $headmark = $_POST['headmark'];
                $id = $_POST['id'];
                $sql = "UPDATE MASTER_DRAWING_ASSIGNED SET SUBCONT_ID = '$subcont_id' WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark' AND ID = '$id'";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES UPDATE SUBCONT";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL UPDATE SUBCONT";
                }
                break;

            case "qty":
                $project_name = $_POST['project_name'];
                $qty = $_POST['qty'];
                $headmark = $_POST['headmark'];
                $id = $_POST['id'];
                $sql = "UPDATE MASTER_DRAWING_ASSIGNED SET ASSIGNED_QTY = '$qty' WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark' AND ID = '$id'";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES UPDATE QTY";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL UPDATE QTY";
                }
                break;

            case "qc":
                $project_name = $_POST['project_name'];
                $qc = strtoupper($_POST['qc']);
                $headmark = $_POST['headmark'];
                $id = $_POST['id'];
                $sql = "UPDATE MASTER_DRAWING_ASSIGNED SET QC_INSP = '$qc' WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark' AND ID = '$id'";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES UPDATE QC";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL UPDATE QC";
                }
                break;

            case "spv":
                $project_name = $_POST['project_name'];
                $spv = strtoupper($_POST['spv']);
                $headmark = $_POST['headmark'];
                $id = $_POST['id'];
                $sql = "UPDATE MASTER_DRAWING_ASSIGNED SET SPV_FAB = '$spv' WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark' AND ID = '$id'";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES UPDATE SPV";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL UPDATE SPV";
                }
                break;

            default:
                break;
        }
        break;

    case "delete":
        $project_name = $_POST['project_name'];
        $headmark = $_POST['headmark'];
        $subcont = $_POST['subcont'];
        $id = $_POST['id'];
        $sql = "DELETE FROM MASTER_DRAWING_ASSIGNED WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark' AND SUBCONT_ID = '$subcont' AND ID = '$id'";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            $query = "UPDATE MASTER_DRAWING SET SUBCONT_STATUS = 'NOTASSIGNED' WHERE PROJECT_NAME = '$project_name' AND HEAD_MARK = '$headmark'";
            $parseQuery = oci_parse($conn, $query);
            $exe2 = oci_execute($parseQuery);
            if($exe2){
                oci_commit($conn);
                 echo "SUKSES UPDATE";
            }else{
                oci_rollback($conn);
                echo "GAGAL UPATE";
            }
            oci_commit($conn);
            echo "SUKSES DELETE";
        } else {
            oci_rollback($conn);
            echo "GAGAL DELETE";
        }
        break;
    default:
        break;
}
?>