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
    case 'detilCompAss';
        $compName = $_POST['id1'];

        $query = "SELECT DISTINCT(comp_name) comp_name, head_mark, comp_asg_qty, COMP_PROFILE, COMP_LENGTH
                    FROM VW_MD_INFO_COMP
                    WHERE comp_name = '$compName'
                ORDER BY head_mark ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;
        
        
    case 'ViewList_input':
        $job = $_POST['id1'];
        $jobname = $_POST['id2'];

        $query = "SELECT mc.COMP_NAME,
                            mc.COMP_LENGTH,
                            mc.COMP_WEIGHT,
                            mc.COMP_PROFILE,
                            mc.CUTTING,
                            mc.FINISHING,
                            mc.COMP_STOCK_QTY,
                            SUM (mc.COMP_MST_QTY) COMP_MST_QTY
                       FROM VW_MD_INFO_COMP mc
                      WHERE mc.PROJECT_NO = '$job' AND mc.PROJECT_NAME_OLD = '$jobname'
                   GROUP BY mc.COMP_NAME,
                            mc.COMP_LENGTH,
                            mc.COMP_WEIGHT,
                            mc.COMP_PROFILE,
                            mc.CUTTING,
                            mc.FINISHING,
                            mc.COMP_STOCK_QTY
                   ORDER BY COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    case "selectProject_input_stock";
//        $query = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO";
        $query = "SELECT DISTINCT project_no FROM project where PROJECT_TYP = 'STRUCTURE' ORDER BY project_no ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "selectSubJob_input_stock";

        $project_no = $_POST['id1'];

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
}
?>