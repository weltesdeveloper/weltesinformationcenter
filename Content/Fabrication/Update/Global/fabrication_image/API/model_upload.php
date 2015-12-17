<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../../../../../dbinfo.inc.php';
require_once '../../../../../../FunctionAct.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
// 
//if (!isset($_SESSION['username'])) {
//    echo <<< EOD
//       <h1>You are UNAUTHORIZED !</h1>
//       <p>INVALID usernames/passwords<p>
//       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
//EOD;
//    exit;
//}
//
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
//oci_set_client_identifier($conn, $_SESSION['username']);
//$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$ACTION = $_GET['action'];
//$ACTION = "view_job";
switch ($ACTION) {

    case "total_img_mark";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

//        $query = "SELECT count(ELEMENT_TYP) ELEMENT_TYP,SUM (QTY) QTY, FROM FAB_QC_MOBILE_DM WHERE HEAD_MARK = '$head_mark' AND SUBCONT_ID = '$subcont' AND ELEMENT_TYP = 'mark'";
//        $query = "SELECT IMAGE_ID, count(ELEMENT_TYP) ELEMENT_TYP, count(IMAGE_ID) id_img, SUM (QTY) QTY FROM FAB_QC_MOBILE_DM WHERE HEAD_MARK = '$head_mark' AND SUBCONT_ID = '$subcont' AND ELEMENT_TYP = 'mark' group by image_id";
          $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'mark'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;
        
    case "total_img_cutt";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'cutt'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;

    case "total_img_assy";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'assy'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;

    case "total_img_weld";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'weld'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;

    case "total_img_drill";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'drill'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;

    case "total_img_finsh";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT IMAGE_ID, SUM (QTY) QTY, count(IMAGE_ID) id_img
                        FROM FAB_QC_MOBILE_DM
                       WHERE     HEAD_MARK = '$head_mark'
                             AND SUBCONT_ID = '$subcont'
                             AND ELEMENT_TYP = 'finsh'
                    GROUP BY image_id order by image_id asc";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);

        break;

    case "view_sub_cont";
        $head_mark = $_GET['id1'];

        $query = "SELECT DISTINCT (subcont_id) subcont_id  FROM vw_fab_info WHERE HEAD_MARK = '$head_mark'";
        $hasil = oci_parse($conn, $query);
        oci_execute($hasil);
        $r = oci_num_rows($hasil);

        $arr = array();
        while ($row = oci_fetch_assoc($hasil)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case "view_list";
        $head_mark = $_GET['id1'];
        $subcont = $_GET['id2'];

        $query = "SELECT ASG_QTY "
                . "FROM vw_fab_info WHERE HEAD_MARK = '$head_mark' AND subcont_id = '$subcont' AND fab_status = 'NOTCOMPLETE'";
//        $query = "SELECT SUM (MARKING) MARK, SUM (CUTTING) CUTT, SUM (ASSEMBLY) ASSY, SUM (WELDING) WELD, SUM (DRILLING) DRILL, SUM (FINISHING) FINSH "
//                . "FROM VW_PROD_FAB WHERE HEAD_MARK = '$head_mark' AND subcont_id = '$subcont'";
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