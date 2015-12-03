<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$username = 'hadi';
$ACTION = $_GET['action'];
switch ($ACTION) {

    // ######################################################### PROSES LOGIN DI WELTES LOGIN APPCELERATOR    
    case "prosesLogin";
        $user = strtoupper($_GET['user__']);
        $pass = strtoupper($_GET['pass__']);
        $query = "select * from weltes_sec_admin.weltes_authentication
                 where app_username ='$user' and app_password ='$pass'";
        ;
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $row = oci_fetch_assoc($hasil);
        $role = $row['APP_PASSWORD'];
        $r = oci_num_rows($hasil);
        if ($r == 1) {
            $_SESSION['username'] = $pass;
            echo json_encode($r);
        } else {
            echo json_encode($r);
        }
        break;

    case "viewSubContId";
        $user = strtoupper($_GET['qc']);
        
//        $query = "select DISTINCT(HEAD_MARK) HEAD_MARK, PROJECT_NAME from vw_fab_info where spv_fab = '$user' OR QC_INSP = '$user'  AND fab_status = 'NOTCOMPLETE' ORDER BY HEAD_MARK asc";
        $query = "SELECT DISTINCT(ID) ID, SUBCONT_ID FROM vw_fab_info  WHERE spv_fab = '$user' OR QC_INSP = '$user' ORDER BY SUBCONT_ID ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $r = oci_num_rows($hasil);
        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    case "viewHeadMark":
        $SubContId = $_GET['valSubContId__'];
        $query = "  SELECT HEAD_MARK, PROJECT_NAME , ID
                    FROM vw_fab_info
                   WHERE fab_status = 'NOTCOMPLETE' AND SUBCONT_ID = '$SubContId'
                ORDER BY HEAD_MARK ASC";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $r = oci_num_rows($hasil);
        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;
        
        
    case "viewQty":
        $head_mark = $_GET['hm__'];
        $id_subcont = $_GET['id'];
        
        $query = "select * from fabrication WHERE HEAD_MARK = '$head_mark' AND ID ='$id_subcont'";
        
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $r = oci_num_rows($hasil);
        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);

        break;
}
?>