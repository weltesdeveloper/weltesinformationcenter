<?php

require_once '../../config/DBConfig.php';
$dbconfig = new DBConfig();
switch ($_POST['action']) {
    case "show_hm":
        $job = $_POST['job'];
        $query = "SELECT HEAD_MARK, "
                . "COMP_TYPE, "
                . "WEIGHT, "
                . "SURFACE, "
                . "PROFILE, "
                . "LENGTH, "
                . "TOTAL_QTY, "
                . "SUBCONT_STATUS, "
                . "GR_WEIGHT, "
                . "DWG_TYP, "
                . "TYPE_BLD "
                . "FROM "
                . "MASTER_DRAWING "
                . "WHERE PROJECT_NAME = '$job'";

        $response = $dbconfig->SelectFrom($query);
        echo json_encode($response);
        break;
    case "show_rev":
        $project_name = $_POST['project_name'];
        $head_mark = $_POST['head_mark'];
        $comp_type = $_POST['comp_type'];
        $profile = $_POST['profile'];
        $surface = $_POST['surface'];
        $length = $_POST['length'];
        $qty = $_POST['qty'];
        $status = $_POST['status'];
        $weight = $_POST['weight'];
        $gr_weight = $_POST['gr_weight'];
        $type_bld = $_POST['type_bld'];
        $dwg_typ = $_POST['dwg_type'];
        $remark = $_POST['remark'];

        $responseInsert = "";
        for ($i = 0; $i < count($head_mark); $i++) {
            //INSERT KE MASTER REVISI
            $query = "INSERT INTO MD_REVISION_HISTORY("
                    . "HEAD_MARK_REV, "
                    . "COMP_TYPE_REV, "
                    . "WEIGHT, "
                    . "SURFACE, "
                    . "PROFILE, "
                    . "PROJECT_NAME_REV, "
                    . "LENGTH, "
                    . "TOTAL_QTY, "
                    . "SUBCONT_STATUS, "
                    . "GR_WEIGHT, "
                    . "DWG_TYP, "
                    . "TYPE_BLD, "
                    . "REV_REMARK, "
                    . "REV_DATE) "
                    . "VALUES("
                    . "'$head_mark[$i]', "
                    . "'$comp_type[$i]', "
                    . "'$weight[$i]', "
                    . "'$surface[$i]', "
                    . "'$profile[$i]', "
                    . "'$project_name', "
                    . "'$length[$i]', "
                    . "'$qty[$i]', "
                    . "'$status[$i]', "
                    . "'$gr_weight[$i]', "
                    . "'$dwg_typ[$i]', "
                    . "'$type_bld[$i]', "
                    . "'$remark[$i]', "
                    . "SYSDATE "
                    . ")";
            $responseInsert .= $dbconfig->InserTable($query)[0];
        }

        $responseUpdate = "";
        for ($i = 0; $i < count($head_mark); $i++) {
            $query = "UPDATE MASTER_DRAWING "
                    . "SET "
                    . "COMP_TYPE = '$comp_type[$i]',"
                    . "PROFILE = '$profile[$i]',"
                    . "SURFACE = '$surface[$i]',"
                    . "LENGTH = '$length[$i]',"
                    . "TOTAL_QTY = '$qty[$i]',"
                    . "SUBCONT_STATUS = '$status[$i]',"
                    . "WEIGHT = '$weight[$i]',"
                    . "GR_WEIGHT = '$gr_weight[$i]',"
                    . "TYPE_BLD = '$type_bld[$i]',"
                    . "DWG_TYP = '$dwg_typ[$i]'"
                    . " WHERE HEAD_MARK = '$head_mark[0]'";
            $responseInsert .= $dbconfig->UpdateTable($query)[0];
        }
        $response = $responseInsert . $responseUpdate;
        if (strrpos($responseInsert, "GAGAL")) {
            echo json_encode("GAGAL UPDATE");
        } else {
            echo json_encode("SUKSES UPDATE");
        }

        break;
    default:
        break;
}