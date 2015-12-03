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

    case 'inputStockComp':
        $comp_name = $_POST['id1'];
        $cutt = $_POST['id2'];
        $job = $_POST['id3'];
        $subJob = $_POST['id4'];
        $finsh = $_POST['id5'];

        $insertSql = "BEGIN SP_MST_COMP_STOCK_INS ('$comp_name','$job','$subJob','$cutt',$finsh); END;";
        echo $insertSql;
        $insertParse = oci_parse($conn, $insertSql);
        $exe = oci_execute($insertParse);
        if ($exe) {
            oci_commit($conn);
            echo json_encode("SUKSES");
        } else {
            echo json_encode("GAGAL");
        }
        break;

    case 'inputStockCompAll':
        $comp_name = $_POST['id1'];
        $cutt = $_POST['id2'];
        $job = $_POST['id3'];
        $subJob = $_POST['id4'];
        $finsh = $_POST['id5'];
        $dateCutt = $_POST['id6'];
        $dateFinsh = $_POST['id7'];
        $mesinCutt = $_POST['id8'];
        $mesinFinsh = $_POST['id9'];
        $nestingFile = $_POST['id10'];

        for ($i = 0; $i < sizeof($comp_name); $i++) {
            $insertSql = "BEGIN SP_MST_COMP_STOCK_INS "
                    . "('$comp_name[$i]','$job','$subJob','$cutt[$i]','$finsh[$i]','$dateCutt','$dateFinsh','$mesinCutt','$mesinFinsh','$nestingFile'); "
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

    case 'ViewList_input':
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

    case 'ViewListNesting':
        $comp = $_POST['id1'];
        $q = "SELECT wm_concat (NESTING_FILE) NESTING_FILE
                    FROM MST_COMP_STOCK
                   WHERE comp_name = '$comp' AND NESTING_FILE IS NOT NULL ";
        $h = oci_parse($conn, $q);
        oci_execute($h);
        $arr = array();
        while ($row = oci_fetch_array($h)) {
            array_push($arr, $row);
        }

        $dataBalik = array();
        array_push($dataBalik, $arr);

        echo json_encode($dataBalik);
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