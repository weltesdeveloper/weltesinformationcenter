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
    case 'inputWest':
        $nestingFile = $_POST['nestingFile__'];
        $width = $_POST['width__'];
        $lenght = $_POST['length__'];
        $weight = $_POST['weight__'];
        $qty = $_POST['qty__'];
        $grade = $_POST['grade__'];
        $remark = $_POST['remark__'];
        $userINP = $_POST['userINP__'];

        $query = "INSERT INTO MST_WASTE@WELTES_LOGINV_LINK (WASTE_ID,WASTE_NM,WASTE_INP_DT,WASTE_REMARKS,WASTE_LENGTH,WASTE_WEIGHT,WASTE_GRADE, WASTE_INP_SIGN,WASTE_WIDTH) "
                . "VALUES(SEQ_MST_WASTE.NEXTVAL@WELTES_LOGINV_LINK,'$nestingFile',SYSDATE,'$remark','$lenght','$weight','$grade', '$userINP', $width)";
        $hasil = oci_parse($conn, $query);
        $exe = oci_execute($hasil);
  
        echo $query;
        $status = '0';
        if ($exe) {
            $status = '1';
            echo json_encode($status);
        }

        break;
        
    case 'selectGrade':
        $query = "SELECT * FROM INV_GRADE@WELTES_LOGINV_LINK";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        
        $arr=array();
        while ($row = oci_fetch_assoc($hasil)){
            array_push($arr, $row);
        }
        
        echo json_encode($arr);

        break;
}