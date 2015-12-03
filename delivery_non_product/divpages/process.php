<?php

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
session_start();
$username = $_SESSION['username'];
switch ($_POST['action']) {
    case "ubah_do":
        $newDO = "";
        $typeDo = $_POST['typeDo'];
        $job = $_POST['job'];
        $query = "SELECT MAX(TO_NUMBER(REPLACE(DO_NO, '$typeDo.$job/SJ/', ''))) DO_NO FROM MST_DO_NP WHERE PROJECT_NO = '$job' AND DO_NO LIKE '$typeDo%'";
//        echo $query;
        $hasil = SingleQryFld($query, $conn);
        if ($hasil == "") {
            $hasil = str_pad(($hasil + 1), 5, 0, STR_PAD_LEFT);
            $newDO = "$typeDo.$job/SJ/$hasil";
            echo "$newDO";
        } else {
            $oldDO = intval(str_replace("$typeDo.$job/SJ/", "", $hasil));
            $newDO_ = str_pad(($oldDO + 1), 5, 0, STR_PAD_LEFT);
            $newDO = "$typeDo.$job/SJ/$newDO_";
            echo "$newDO";
        }
        break;
    case "submit_data":
        $tanggal = $_POST['tanggal'];
        $job = $_POST['job'];
        $do_no = $_POST['do_no'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $spk = $_POST['spk'];
        $subject = $_POST['subject'];
        $pono = $_POST['pono'];
        $vehicleno = $_POST['vehicleno'];
        $transporter = $_POST['transporter'];
        $driver = $_POST['driver'];
        $attention = $_POST['attention'];
        $spv = $_POST['spv'];
        $remark_do = $_POST['remark_do'];

        $parent_item_name = $_POST['parent_item_name'];
        $parent_item_qty = $_POST['parent_item_qty'];
        $parent_item_unit = $_POST['parent_item_unit'];
        $parent_item_remark = $_POST['parent_item_remark'];
        $child_item_name = $_POST['child_item_name'];
        $child_item_qty = $_POST['child_item_qty'];
        $child_item_unit = $_POST['child_item_unit'];
        $child_item_remark = $_POST['child_item_remark'];

//SUBMIT MASTER
        $insertMstDoSql = "INSERT INTO MST_DO_NP(DO_NO, DO_ACT_DATE, PO_NO, PROJECT_NO, VHC_NO, T_PORTER, "
                . "DVR, DO_SYSDATE, INP_SG, SPK_NO, SBJ, ATTN, DO_ADDR, DO_REMS, DO_SPV, DO_CITY) "
                . "VALUES('$do_no', to_date('$tanggal', 'MM/DD/YYYY'), '$pono', '$job', '$vehicleno', '$transporter', "
                . "'$driver', SYSDATE, '$username', '$spk', '$subject', '$attention', '$address', '$remark_do', '$spv', '$city')";
        $insertMstDoParse = oci_parse($conn, $insertMstDoSql);
        $insertMstDo = oci_execute($insertMstDoParse);
        if ($insertMstDo) {
            echo "SUKSES INSERT MASTER";
            for ($i = 0; $i < sizeof($parent_item_name); $i++) {
                $nomer_item = SingleQryFld("SELECT SEQ_ITEM_ID.NEXTVAL FROM DUAL", $conn);
                $insertItemSql = "INSERT INTO DTL_DO_NP(ITEM_ID, ITEM_NAME, ITEM_QTY, ITEM_UNIT, "
                        . "ITEM_REMARK, DO_NO) "
                        . "VALUES ('$nomer_item', '$parent_item_name[$i]', '$parent_item_qty[$i]', '$parent_item_unit[$i]', "
                        . "'$parent_item_remark[$i]', '$do_no')";
                $insertItemParse = oci_parse($conn, $insertItemSql);
                $insertItem = oci_execute($insertItemParse);
                if ($insertItem) {
                    echo "SUKSES INSERT ITEM";
                    for ($j = 0; $j < sizeof($child_item_name[$i]); $j++) {
                        $child_name = $child_item_name[$i][$j];
                        $child_qty = $child_item_qty[$i][$j];
                        $child_unit = $child_item_unit[$i][$j];
                        $child_remark = $child_item_remark[$i][$j];
                        $insertDtlItemSql = "INSERT INTO DTL_ITEM_NP(ITEM_ID, DTL_ITEM_NAME, DTL_ITEM_QTY, DTL_ITEM_UNIT, DTL_ITEM_REMARK) "
                                . "VALUES ('$nomer_item', '$child_name', '$child_qty', '$child_unit', '$child_remark')";
                        $insertDtlItemParse = oci_parse($conn, $insertDtlItemSql);
                        $insertDtlItem = oci_execute($insertDtlItemParse);
                        if ($insertDtlItem) {
                            oci_commit($conn);
                            echo "SUKSES INSERT DTL ITEM";
                        } else {
                            echo "GAGAL";
                        }
                    }
                } else {
                    echo "GAGAL";
                }
            }
        } else {
            echo "GAGAL";
        }
        break;
    case "cek_do":
        $do_no = $_POST['do_no'];
        $array_master = array();
        $array_item = array();
        $array_dtl_item = array();
        $CEKDONO = SingleQryFld("SELECT COUNT(*) FROM MST_DO_NP WHERE DO_NO = '$do_no'", $conn);
        if ($CEKDONO <> "0") {
            $mstDoSql = "SELECT DO_NO, TO_CHAR(DO_ACT_DATE, 'MM/DD/YYYY')DO_ACT_DATE, PO_NO, PROJECT_NO, VHC_NO, T_PORTER, DVR,"
                    . "DO_SYSDATE, INP_SG, SPK_NO, SBJ, ATTN, DO_ADDR, TO_CHAR(DO_REMS)DO_REMS, DO_SPV, DO_CITY FROM MST_DO_NP WHERE DO_NO = '$do_no'";
            $mstDoParse = oci_parse($conn, $mstDoSql);
            oci_execute($mstDoParse);

            while ($row = oci_fetch_array($mstDoParse)) {
                array_push($array_master, $row);
            }

            $query = "SELECT * FROM DTL_DO_NP WHERE DO_NO = '$do_no'";
            $parse = oci_parse($conn, $query);
            oci_execute($parse);
            while ($row1 = oci_fetch_array($parse)) {
                array_push($array_item, $row1);
                $dtl_item_sql = "SELECT * FROM DTL_ITEM_NP WHERE ITEM_ID = '$row1[ITEM_ID]'";
                $dtl_item_parse = oci_parse($conn, $dtl_item_sql);
                oci_execute($dtl_item_parse);
                while ($row2 = oci_fetch_array($dtl_item_parse)) {
                    array_push($array_dtl_item, [
                        'name' => $row2['ITEM_ID'],
                        'gender' => $row2['DTL_ITEM_NAME']
                    ]);
                }
            }
        }
        $response = array(
//            "mst" => $array_master,
            "item" => $array_item,
            "dtl" => $array_dtl_item,
//            "status" => "$CEKDONO"
        );
        echo json_encode($response);
        break;


    default:
        break;
}
