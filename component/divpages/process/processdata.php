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
    case 'check_comp':
        $comp_name = $_POST['id1'];
        $drawing = $_POST['id2'];
        $qty = $_POST['id3'];
        $comp = $_POST['id4'];
        $comp_lenght = $_POST['id5'];
        $comp_weight = $_POST['id6'];


        $arr = array();
        for ($i = 0; $i < sizeof($comp_name); $i++) {

            $query = " SELECT COMP_NAME, HEAD_MARK
                       FROM MASTER_COMP
                       WHERE COMP_NAME = '$comp_name[$i]' AND HEAD_MARK = '$drawing[$i]'
                    ";
            $hasil = oci_parse($conn, $query);
            oci_execute($hasil);
            $data = oci_fetch_array($hasil);
            $row = oci_num_rows($hasil);
            array_push($arr, $row);
        }
        echo json_encode($arr);

        break;

    case 'inputComp':
        $comp_name = $_POST['id1'];
        $drawing = str_replace(" ", "", ($_POST['id2']));
        $qty = $_POST['id3'];
        $comp = $_POST['id4'];
        $comp_lenght = $_POST['id5'];
        $comp_weight = $_POST['id6'];
        $job = $_POST['id7'];
        $subJob = $_POST['id8'];
        $row_id = $_POST['row_id'];
        $arry_resp = array();

        for ($i = 0; $i < sizeof($comp_name); $i++) {

            $perHruf = concatHM($drawing[$i]);
            $str_HM = @$perHruf[0];
            $int_HM = @$perHruf[1];
            $pad_HM = @$perHruf[2];
            if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
                $str_HM = $drawing[$i];
                $int_HM = 0;
                $pad_HM = 0;
            }

            $query = " SELECT COMP_NAME, HEAD_MARK
                       FROM MASTER_COMP
                       WHERE COMP_NAME = '$comp_name[$i]' AND REGEXP_REPLACE (HEAD_MARK, '[[:space:]]*', '') = REGEXP_REPLACE ('$drawing[$i]', '[[:space:]]*', '')
                    ";
            $hasil = oci_parse($conn, $query);
            oci_execute($hasil);
            $jml_row = oci_fetch_all($hasil, $output);
            oci_execute($hasil);
//            $data = oci_fetch_array($hasil);
//            $row = oci_num_rows($hasil);
            //echo $jml_row;
            if ($jml_row == '0') {
                $insertSql = "INSERT INTO MASTER_COMP(COMP_ID,COMP_NAME, HEAD_MARK, COMP_MST_QTY, COMP_IMP_DATE, COMP_IMP_USER, COMP_PROFILE, COMP_LENGTH, COMP_WEIGHT, PROJECT_NO, PROJECT_NAME ) "
                        . "VALUES(SEQ_MAST_COMP.NEXTVAL,'$comp_name[$i]', ('$str_HM'||LPAD('$int_HM',$pad_HM)), '$qty[$i]',SYSDATE,'$_SESSION[username]','$comp[$i]','$comp_lenght[$i]','$comp_weight[$i]','$job','$subJob')";
                $insertParse = oci_parse($conn, $insertSql);
                $exe = oci_execute($insertParse);
                echo $insertSql;
                if ($exe) {
                    oci_commit($conn);
                    $arry = [
                        "row_id" => "$row_id[$i]",
                        "respons" => "SUCCESS"
                    ];
                    array_push($arry_resp, $arry);
                } else {
                    $arry = [
                        "row_id" => "$row_id[$i]",
                        "respons" => "FAILED"
                    ];
                    array_push($arry_resp, $arry);
                }
            } else {
                $arry = [
                    "row_id" => "$row_id[$i]",
                    "respons" => "EXIST"
                ];
                array_push($arry_resp, $arry);
            }
        }
        echo json_encode($arry_resp);

        break;

    default:
        break;
}
?>