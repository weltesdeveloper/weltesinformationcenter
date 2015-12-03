<?php
//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../dbinfo.inc.php';
require_once '../../../../../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$ACTION = $_GET['action'];
switch ($ACTION) {

    case "login";
        $user = $_GET['usr'];
        $pass = $_GET['psw'];
		
        $query = "select app_username from weltes_sec_admin.weltes_authentication
                 where app_username ='$user' and app_password ='$pass'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $row = oci_fetch_assoc($hasil);
        $r = oci_num_rows($hasil);

        if ($r == 1) {
            $_SESSION['username'] = $pass;
            echo json_encode($r);
        } else {
            echo json_encode($r);
        }
		
//            echo json_encode('1');
		

        break;
}
?>