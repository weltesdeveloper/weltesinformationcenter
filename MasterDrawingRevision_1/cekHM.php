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
?>

<?php

switch ($_POST['action']) {
    case "cek_duplikat":
        $array = array();
        $head_mark = strval($_POST['head_mark']);
        $project_name = strval($_POST['project_name']);
        $sql = "SELECT COUNT(*) FROM MASTER_DRAWING WHERE REPLACE(TRIM(HEAD_MARK), ' ', '') = '$head_mark' AND DWG_STATUS = 'ACTIVE'";
        $jumlah = SingleQryFld($sql, $conn);
        if ($jumlah == "0") {
            $response = array(
                "status" => "tidak ada",
                "data_balik" => $array
            );
            echo json_encode($response);
        } else {
            $query = "SELECT * FROM MASTER_DRAWING WHERE REPLACE(TRIM(HEAD_MARK), ' ', '') = '$head_mark'";
            $parse = oci_parse($conn, $query);
            oci_execute($parse);
            while ($row = oci_fetch_array($parse)) {
                array_push($array, $row);
            }
            $response = array(
                "status" => "ada",
                "data_balik" => $array
            );
            echo json_encode($response);
        }
        break;
    case "insert_data":
        $profile = $_POST['profile'];
        $totalQty = $_POST['totalQty'];
        $length = $_POST['length'];
        $surface = $_POST['surface'];
        $gr_weight = $_POST['gr_weight'];
        $weight = $_POST['weight'];
        $compType2 = $_POST['compType2'];
        $compType = $_POST['compType'];
        $headmark = $_POST['headmark'];
        $project_name = $_POST['project_name'];
        $dwg_typ = $_POST['dwg_typ'];

        $perHruf = concatHM($headmark);
        $str_HM = @$perHruf[0];
        $int_HM = @$perHruf[1];
        $pad_HM = @$perHruf[2];
        if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
            $str_HM = $headmark;
            $int_HM = 0;
            $pad_HM = 0;
        }

        $headmarkInsertionSql = "BEGIN MD_INS"
                . "(('$str_HM'||LPAD('$int_HM',$pad_HM)), :COMPTYPE,:COMPTYPE2, :WEIGHT, "
                . ":SURFACE, :PROFILE, :PROJNAME, :LENGTH, :QUANTITY, :GROSSWEIGHT,'$dwg_typ','$username'); END;";
        $headmarkInsertionParse = oci_parse($conn, $headmarkInsertionSql);
        oci_bind_by_name($headmarkInsertionParse, ":PROJNAME", $project_name);
        // oci_bind_by_name($headmarkInsertionParse, ":HEADMARK", $headmarkValue);
        oci_bind_by_name($headmarkInsertionParse, ":COMPTYPE", $compType);
        oci_bind_by_name($headmarkInsertionParse, ":COMPTYPE2", $compType2);
        oci_bind_by_name($headmarkInsertionParse, ":WEIGHT", $weight);
        oci_bind_by_name($headmarkInsertionParse, ":SURFACE", $surface);
        oci_bind_by_name($headmarkInsertionParse, ":LENGTH", $length);
        oci_bind_by_name($headmarkInsertionParse, ":QUANTITY", $totalQty);
        oci_bind_by_name($headmarkInsertionParse, ":PROFILE", $profile);
        oci_bind_by_name($headmarkInsertionParse, ":GROSSWEIGHT", $gr_weight);

        $headmarkInsertionRes = oci_execute($headmarkInsertionParse);
        if ($headmarkInsertionRes) {
            echo "SUKSES";
        } else {
            echo 'GAGAL';
        }
        break;

    case "getHM":
        $project_name = $_POST['project_name'];
        $array = array();
        $sql = "SELECT * FROM MASTER_DRAWING WHERE PROJECT_NAME = '$project_name' AND DWG_STATUS = 'ACTIVE'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row1 = oci_fetch_array($parse)) {
            array_push($array, $row1);
        }
        echo json_encode($array);
        break;
    default:
        break;
}
    