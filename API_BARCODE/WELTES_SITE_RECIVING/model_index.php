<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$ACTION = $_GET['action'];
$username = 'hadi';
//$ACTION = 'viewDetBarcode';
switch ($ACTION) {
    
    case "prosesLogin";
        $user = $_GET['user__'];
        $pass = $_GET['pass__'];
        $query = "select * from weltes_sec_admin.weltes_authentication
                 where app_username ='$user' and app_password ='$pass'";
        ;
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $row = oci_fetch_assoc($hasil);
        $role = $row['APP_PASSWORD'];
        $r = oci_num_rows($hasil);
        if ($r == 1) {
            echo json_encode($r);
        } else {
            echo json_encode($r);
        }
        break;

    case "viewColi";
        $barcode = $_GET['id1'];
//        $barcode = 'CN.W-IGG.BGH.0206';
//        IGGBAGASSEHOUSE

        $qry1 = "SELECT * FROM VW_DELIV_INFO WHERE COLI_NUMBER = '$barcode' ";
        $sql1 = oci_parse($conn, $qry1);
        oci_execute($sql1);
        $arr1 = array();
        while ($row1 = oci_fetch_assoc($sql1)) {
            array_push($arr1, $row1);
        }

        echo json_encode($arr1);

        break;

    case "viewColiDetail";
        $coli = $_GET['id1'];

        $qry = "SELECT HEAD_MARK, UNIT_PCK_QTY, WEIGHT FROM VW_PCK_INFO WHERE coli_number = 'CN.W-IGG.BGD.7271";
        $sql = oci_parse($conn, $qry);
        oci_execute($sql);
        $arr = array();
        while ($row = oci_fetch_assoc($sql)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        break;

    case "InputColi";
        $coli = trim($_GET['id1']);
        $lokasi = trim($_GET['id2']);
        $remark = $_GET['id3'];
        $tgl = $_GET['id4'];
        $uName = $_GET['user'];

//        $sql = "INSERT INTO RECEIVE_MATL(ID_RCV_MATL, COLI_NUMBER, MATL_LOC, RCV_DATE, RCV_SYSDATE, INPUT_SIGN, REMARK) "
//                . "VALUES(SEQ_MATL_RCV.NEXTVAL, '$coli', '$lokasi', SYSDATE , SYSDATE, '$username', '$remark')";
//        $sql = "INSERT INTO RECEIVE_MATL(ID_RCV_MATL, COLI_NUMBER, MATL_LOC, RCV_DATE, RCV_SYSDATE, INPUT_SIGN, REMARK) "
//                . "VALUES(SEQ_MATL_RCV.NEXTVAL, '$coli', '$location', TO_DATE('$date', 'DD MONTH YYYY'), SYSDATE, '$username', '$remark')";
        $sql = "BEGIN SP_RECEIVE_MATL('$coli', '$lokasi', '$tgl', '$uName', '$remark'); END;";
        $hasil = oci_parse($conn, $sql);
        $exe = oci_execute($hasil);

        if ($exe) {
            echo json_encode('1');
        }
        break;



    // ######################################################### PROSES DELIVERY LIST DI MENU_2 APPCELERATOR    

    case "viewDeliveryList";
        $job = trim($_GET['job__']);
        $start_date = str_replace("/", "-", $_GET["tglStart__"]);
        $end_date = str_replace("/", "-", $_GET['tglFinsh__']);

        $sql = "SELECT RM.COLI_NUMBER, RM.MATL_LOC
                FROM RECEIVE_MATL RM
                     INNER JOIN MST_PACKING MP ON MP.COLI_NUMBER = RM.COLI_NUMBER
                     INNER JOIN PROJECT P ON P.PROJECT_NAME = MP.PROJECT_NAME
               WHERE     RM.RCV_DATE BETWEEN TO_DATE ('$start_date', 'MM/DD/YYYY')
                                         AND TO_DATE ('$end_date', 'MM/DD/YYYY')
                     AND P.PROJECT_NO = '$job'";
        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;
		
    case "viewJob";
        $sql = "SELECT DISTINCT PROJECT_NO FROM COMP_VW_INFO_PCK ORDER BY PROJECT_NO";
        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;
	
	case "viewAssemblyList";
        $cn = trim($_GET['cn__']);

        $sql = "SELECT VDI.DO_NO,
			       RM.COLI_NUMBER,
			       VDI.HEAD_MARK,
			       VDI.UNIT_PCK_QTY,
			       RM.MATL_LOC,
			       RM.RCV_DATE,
			       NVL (TO_CHAR (RM.REMARK), '-') REMARK
			  FROM RECEIVE_MATL RM
			       INNER JOIN VW_DELIV_INFO VDI ON VDI.COLI_NUMBER = RM.COLI_NUMBER
			 WHERE RM.COLI_NUMBER = '$cn'";
        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;
	

    // ######################################################### PROSES ASSEMBLY LIST DI MENU_2 APPCELERATOR    
    case "viewSubJob";
        $sql = "SELECT DISTINCT (PROJECT_NAME) PROJECT_NAME FROM PAINTING WHERE PAINT_STATUS = 'NOTCOMPLETE' ORDER BY PROJECT_NAME";
        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;

    case "viewPaintingList";
        $job = $_GET['job__'];
        
        $sql = "
        SELECT HEAD_MARK,
           TOTAL_QTY,
           FINISHING
        FROM VW_PNT_INFO       
        WHERE PROJECT_NAME= '$job' AND FINISHING = PAINT_QC_PASS ORDER BY HEAD_MARK,ID";
//        WHERE PROJECT_NAME= '$job' AND FINISHING <> PAINT_QC_PASS ORDER BY HEAD_MARK,ID";

        $parse = oci_parse($conn, $sql);

        $array = array();
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($array, $row);
        }
        echo json_encode($array);
        break;
        
        
    // ######################################################### PROSES LOGIN DI WELTES LOGIN APPCELERATOR    
    case "prosesLogin";
        $user = $_GET['user__'];
        $pass = $_GET['pass__'];

        $query = "select * from weltes_sec_admin.weltes_authentication
                 where app_username ='$user' and app_password ='$pass'";

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

}
?>