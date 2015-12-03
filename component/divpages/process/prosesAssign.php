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
    case 'ViewCompAsign':
        $AssHeadMark = $_POST['id1'];

        $query = "SELECT  COMP_ID,COMP_NAME, COMP_PROFILE, COMP_LENGTH,COMP_WEIGHT, COMP_MST_QTY, CUTTING,FINISHING,COMP_STOCK_QTY, HEAD_MARK,COMP_ASG_QTY
                    FROM VW_MD_INFO_COMP
                    WHERE HEAD_MARK = '$AssHeadMark'
                    ORDER BY COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;
    case 'tentukanPenguranganStock':
        $tdNilai = $_POST['id1'];

        $query = "SELECT  COMP_ID,COMP_NAME, COMP_PROFILE, COMP_LENGTH,COMP_WEIGHT, COMP_MST_QTY, COMP_STK_QTY, HEAD_MARK
                    FROM VW_MD_INFO_COMP
                    WHERE HEAD_MARK = '$AssHeadMark'
                    ORDER BY COMP_NAME ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_array($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case 'inputAssignStockComp':
        $id = $_POST['id1'];
        $tdStock = $_POST['id2'];
        $StockComp = $_POST['id3'];
        $CompName = $_POST['id4'];

//        $AI = 'SEQ_MAST_COMP_ASG.NEXTVAL';
//        $date = 'SYSDATE';

        $query_stock = "UPDATE MST_COMP_STOCK_TRIGER SET COMP_STOCK_QTY = '$StockComp' WHERE COMP_NAME = '$CompName' ";
        $hasil_stock = oci_parse($conn, $query_stock);
        oci_execute($hasil_stock);

//        $query = "INSERT INTO MASTER_COMP_ASSIGN (COMP_ASG_ID,COMP_ID,COMP_ASG_QTY,COMP_ASG_USER,COMP_ASG_SYSDATE)"
//                . "VALUES(SEQ_MAST_COMP_ASG.NEXTVAL,'$id','$tdStock','$username',SYSDATE) ";
//        $hasil = oci_parse($conn, $query);
//        $exe = oci_execute($hasil);

        $insertSql = "BEGIN SP_MST_COMP_ASS (SEQ_MAST_COMP_ASG.NEXTVAL,'$id','$tdStock','$username',SYSDATE); END;";
        echo $insertSql;
        $insertParse = oci_parse($conn, $insertSql);
        $exe = oci_execute($insertParse);

        if ($exe) {
            echo json_encode('BERHASIL');
        }
        break;

    default:
        break;
}
?>