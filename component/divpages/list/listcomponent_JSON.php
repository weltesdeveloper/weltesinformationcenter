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

    case "selectProject";
        $query = "SELECT DISTINCT project_no FROM project where PROJECT_TYP = 'STRUCTURE' ORDER BY project_no ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }


        echo json_encode($arr);
        break;

    case "selectSubJob";

        $project_no = $_POST['id'];
        $query = "SELECT DISTINCT (PROJECT_NAME_OLD) PROJECT_NAME,PROJECT_NAME_NEW  "
                . "FROM VW_PROJ_INFO "
                . "WHERE PROJECT_TYP = 'STRUCTURE' AND PROJECT_NO = '$project_no' ORDER BY PROJECT_NAME ASC";


        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;


    case 'ViewList':
        $job = $_POST['id1'];
        $jobname = $_POST['id2'];

        $query = "SELECT PROJECT_NO,
                        PROJECT_NAME_OLD,
                        HEAD_MARK,
                        TOTAL_QTY,
                        WEIGHT,
                        SUM (COMP_WEIGHT*COMP_MST_QTY) COMP_WEIGHT,
                        SUM (COMP_MST_QTY) MST,
                        SUM (COMP_ASG_QTY) ASG,
                        SUM (CUTTING) CUT,
                        SUM (FINISHING) FIN                        
                   FROM VW_MD_INFO_COMP
                  WHERE PROJECT_NO = '$job' AND PROJECT_NAME_OLD = '$jobname'
                  GROUP BY PROJECT_NO, PROJECT_NAME_OLD, HEAD_MARK, TOTAL_QTY, WEIGHT";

        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    case 'ViewDetilCompHM':
        $head = $_POST['id1'];
        $query = "SELECT COMP_NAME,
                            COMP_PROFILE,
                            COMP_LENGTH,
                            COMP_MST_QTY,
                            COMP_WEIGHT,
                            SUM (COMP_ASG_QTY) COMP_ASG_QTY
                       FROM VW_MD_INFO_COMP
                      WHERE HEAD_MARK = '$head'
                   GROUP BY COMP_NAME,
                            COMP_PROFILE,
                            COMP_LENGTH,
                            COMP_MST_QTY,
                            COMP_WEIGHT
                   ORDER BY COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    // VIEW LIST UNTUK Monitoring Component By Plat
    case 'ViewListByPlat':
        $job = $_POST['id1'];
        $jobname = $_POST['id2'];

        $query = "SELECT mc.COMP_NAME,
                            mc.COMP_LENGTH,
                            mc.COMP_WEIGHT,
                            mc.COMP_PROFILE,
                            mc.CUTTING,
                            mc.FINISHING,
                            mc.NESTING_FILE,
                            SUM (mc.COMP_MST_QTY) COMP_MST_QTY
                       FROM VW_MD_INFO_COMP mc
                      WHERE mc.PROJECT_NO = '$job' AND mc.PROJECT_NAME_OLD = '$jobname' 
                   GROUP BY mc.COMP_NAME,
                            mc.COMP_LENGTH,
                            mc.COMP_WEIGHT,
                            mc.COMP_PROFILE,
                            mc.CUTTING,
                            mc.FINISHING,
                            mc.NESTING_FILE
                   ORDER BY COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    case 'detilCompPlat':
        $CompName = $_POST['CompName__'];

        $query = "SELECT * FROM MST_COMP_STOCK WHERE COMP_NAME = '$CompName'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "edit_data":
        $pk_NestingFile = $_POST['pk_NestingFile__'];
        $pk_compName = $_POST['pk_compName__'];
        $setDataUpdate = $_POST['setDataUpdate__'];
        $newValue = str_replace("'", '"', $_POST['newValue']);

        $sql_text = "UPDATE MST_COMP_STOCK SET $setDataUpdate = '$newValue' WHERE NESTING_FILE = '$pk_NestingFile' AND COMP_NAME ='$pk_compName' ";
        $parse = oci_parse($conn, $sql_text);
        $res = oci_execute($parse);
        if ($res) {
            oci_commit($conn);
            echo json_encode('success');
        } else {
            oci_rollback($conn);
            echo oci_error();
        }
        break;
}
?>