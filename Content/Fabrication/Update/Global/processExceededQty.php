<?php
// $time = microtime();
// $time = explode(' ', $time);
// $time = $time[1] + $time[0];
// $start = $time;
?>

<?php
require_once '../../../../dbinfo.inc.php';
require_once '../../../../FunctionAct.php';
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
// echo "DALAM PERBAIKAN";
// echo "<script>alert('FABRICATION UPDATED');</script>";
// exit();
$HM_ID = intval($_POST['HM_ID']);
$HEAD_MARK = strval($_POST['HEAD_MARK']);
$no = intval($_POST['no']);
$ProjNme = strval($_POST['ProjNme']);

if (isset($_POST['submit'])) {
    // echo "<script>alert('FABRICATION $HEAD_MARK UPDATED');</script>";
    // exit();

    $AssgQTY = intval($_POST['AssgQTY']);
    $dataMarking = intval($_POST['dataMarking']);
    $dataCutting = intval($_POST['dataCutting']);
    $dataAssembly = intval($_POST['dataAssembly']);
    $dataWelding = intval($_POST['dataWelding']);
    $dataDrilling = intval($_POST['dataDrilling']);
    $dataFinishing = intval($_POST['dataFinishing']);
    $ValueFNISHFrst = intval($_POST['ValueFNISHFrst']);
    $dataQCPass = intval($_POST['dataQCPass']);
    $ValueQCPASSFrst = intval($_POST['ValueQCPASSFrst']);
    $proc_date = $_POST['proc_date'] . ' ' . date("H:m:s");

    // echo "HM ID = $HM_ID -- i = $no -- HM = $HEAD_MARK -- $dataMarking -- $dataCutting -- $dataAssembly -- $dataWelding -- $dataDrilling -- $dataFinishing -- $dataQCPass -- AWL QC = $ValueQCPASSFrst";

    $alertBOX = "";

    // PROSES FABRICATION
    if ($dataFinishing == 0 and $dataDrilling == 0 and $dataMarking == 0 and $dataCutting == 0 and $dataWelding == 0 and $dataAssembly == 0) {
        # code...
        // echo "<script>alert('FABRICATION $HEAD_MARK NOT UPDATED');</script>";
        $alertBOX .= "Fabrication $HEAD_MARK NOT UPDATED ## ";
    } else {

        if ($dataMarking > 0) {

            $markingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET MARKING = MARKING+$dataMarking, MARKING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), MARKING_FAB_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($markingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($markingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($markingUpdateParse, ":sign", $username);

            $markingUpdateRes = oci_execute($markingUpdateParse);

            if ($markingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('MARKING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('MARKING NOT UPDATED');</script>";
                exit();
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataCutting > 0) {

            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET CUTTING = CUTTING+$dataCutting, CUTTING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), CUTTING_FAB_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($cuttingUpdateParse, ":sign", $username);

            $cuttingUpdateRes = oci_execute($cuttingUpdateParse);

            if ($cuttingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('ASSEMBLY UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('ASSEMBLY NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataAssembly > 0) {

            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET ASSEMBLY = ASSEMBLY+$dataAssembly, ASSEMBLY_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), ASSEMBLY_FAB_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($cuttingUpdateParse, ":sign", $username);

            $cuttingUpdateRes = oci_execute($cuttingUpdateParse);

            if ($cuttingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('ASSEMBLY UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('ASSEMBLY NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataWelding > 0) {

            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET WELDING = WELDING+$dataWelding, WELDING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), WELDING_FAB_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($cuttingUpdateParse, ":sign", $username);

            $cuttingUpdateRes = oci_execute($cuttingUpdateParse);

            if ($cuttingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('WELDING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('WELDING NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataDrilling > 0) {

            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET DRILLING = DRILLING+$dataDrilling, DRILLING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), DRILLING_FAB_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($cuttingUpdateParse, ":sign", $username);

            $cuttingUpdateRes = oci_execute($cuttingUpdateParse);

            if ($cuttingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('DRILLING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('DRILLING NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataFinishing > 0) {

            if ($AssgQTY == ($ValueFNISHFrst + $dataFinishing)) {
                # code...
                $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET FINISHING = FINISHING+$dataFinishing, FINISHING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FINISHING_FAB_SIGN = :sign, FAB_STATUS = 'COMPLETE'   WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            } else {
                $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION SET FINISHING = FINISHING+$dataFinishing, FINISHING_FAB_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FINISHING_FAB_SIGN = :sign  WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            }

            oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($cuttingUpdateParse, ":sign", $username);

            $cuttingUpdateRes = oci_execute($cuttingUpdateParse);

            if ($cuttingUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('FINISHING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('FINISHING NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }

        //INSERT TO HISTORY FAB
        $finishingHistUpdateParse = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, MARKING, CUTTING, "
                . "ASSEMBLY, WELDING, DRILLING, FINISHING) "
                . "VALUES (:projName, :headmarkToUpdate, :idToUpdate, TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), :sign,$dataMarking,$dataCutting,$dataAssembly,$dataWelding,$dataDrilling,$dataFinishing)");
        oci_bind_by_name($finishingHistUpdateParse, ":projName", $ProjNme);
        oci_bind_by_name($finishingHistUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
        oci_bind_by_name($finishingHistUpdateParse, ":idToUpdate", $HM_ID);
        oci_bind_by_name($finishingHistUpdateParse, ":sign", $username);
        $finishingHistUpdateRes = oci_execute($finishingHistUpdateParse);
        if ($finishingHistUpdateRes) {
            oci_commit($conn);
            // echo "<script>alert('HISTORY INSERTED');</script>";
        } else {
            oci_rollback($conn);
            // echo "<script>alert('HISTORY NOT INSERTED');</script>";
        }

        $alertBOX .= "FABRICATION $HEAD_MARK UPDATED ## ";
    }
    // END PROSES FABRICATION
    // PROSES FABRICATION QC
    if ($dataQCPass > 0) {
        // insert Fab QC
        if ($AssgQTY == ($ValueQCPASSFrst + $dataQCPass)) {
            # code...
            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION_QC SET 
              MARKING_QC = MARKING_QC+$dataQCPass, MARKING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), MARKING_QC_SIGN = '$username', 
              CUTTING_QC = CUTTING_QC+$dataQCPass, CUTTING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), CUTTING_QC_SIGN = '$username', 
              ASSEMBLY_QC = ASSEMBLY_QC+$dataQCPass, ASSEMBLY_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), ASSEMBLY_QC_SIGN = '$username', 
              WELDING_QC = WELDING_QC+$dataQCPass, WELDING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), WELDING_QC_SIGN = '$username', 
              DRILLING_QC = DRILLING_QC+$dataQCPass, DRILLING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), DRILLING_QC_SIGN = '$username', 
              FINISHING_QC = FINISHING_QC+$dataQCPass, FINISHING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FINISHING_QC_SIGN = '$username',
              FAB_QC_PASS = FAB_QC_PASS+$dataQCPass, FAB_QC_PASS_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FAB_QC_PASS_SIGN = '$username', FAB_QC_STATUS = 'PASSED'
            WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            $memo = "QCPASS";
        } else {
            # code...
            $cuttingUpdateParse = oci_parse($conn, "UPDATE FABRICATION_QC SET 
              MARKING_QC = MARKING_QC+$dataQCPass, MARKING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), MARKING_QC_SIGN = '$username', 
              CUTTING_QC = CUTTING_QC+$dataQCPass, CUTTING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), CUTTING_QC_SIGN = '$username', 
              ASSEMBLY_QC = ASSEMBLY_QC+$dataQCPass, ASSEMBLY_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), ASSEMBLY_QC_SIGN = '$username', 
              WELDING_QC = WELDING_QC+$dataQCPass, WELDING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), WELDING_QC_SIGN = '$username', 
              DRILLING_QC = DRILLING_QC+$dataQCPass, DRILLING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), DRILLING_QC_SIGN = '$username', 
              FINISHING_QC = FINISHING_QC+$dataQCPass, FINISHING_QC_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FINISHING_QC_SIGN = '$username', 
              FAB_QC_PASS = FAB_QC_PASS+$dataQCPass, FAB_QC_PASS_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), FAB_QC_PASS_SIGN = '$username'
            WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            $memo = "QCNOTPASS";
        }
        // echo "$cuttingUpdateParse <br>";
        oci_bind_by_name($cuttingUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
        oci_bind_by_name($cuttingUpdateParse, ":idToUpdate", $HM_ID);
        $cuttingUpdateRes = oci_execute($cuttingUpdateParse);
        if ($cuttingUpdateRes) {
            oci_commit($conn);
            // echo "<script>alert('QC PASS UPDATED');</script>";
        } else {
            oci_rollback($conn);
            // echo "<script>alert('QC PASS NOT UPDATED');</script>";
        }

        //insert Fab Qc History
        $updateQcHistSql = "INSERT INTO FABRICATION_QC_HIST (PROJECT_NAME, HEAD_MARK, ID, 
              MARKING_QC, CUTTING_QC, ASSEMBLY_QC, WELDING_QC, DRILLING_QC, FINISHING_QC,
              FAB_QC_HIST_SIGN, FAB_QC_ENTRY_DATE, MEMO)           
              VALUES (:pn, :hm, :id, '$dataQCPass', '$dataQCPass', '$dataQCPass', '$dataQCPass', '$dataQCPass', '$dataQCPass', '$username', TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), '$memo')";
        $updateQcHistParse = oci_parse($conn, $updateQcHistSql);

        oci_bind_by_name($updateQcHistParse, ":pn", $ProjNme);
        oci_bind_by_name($updateQcHistParse, ":hm", $HEAD_MARK);
        oci_bind_by_name($updateQcHistParse, ":id", $HM_ID);
        $updateQcHistRes = oci_execute($updateQcHistParse);
        if ($updateQcHistRes) {
            oci_commit($conn);
            // echo "<script>alert('HISTORY QC PASS INSERTED');</script>";
        } else {
            oci_rollback($conn);
            // echo "<script>alert('HISTORY QC PASS NOT INSERTED');</script>";
        }


        $jmlHMPaint = SingleQryFld("SELECT count(*) FROM PAINTING WHERE HEAD_MARK = '$HEAD_MARK' AND ID = '$HM_ID'", $conn);
        // echo "JML = $jmlHMPaint<br>";
        //Insert OR Update Painting
        if ($jmlHMPaint == 0) {
            # code...          
            //INSERT INTO PAINTING WHEN FINISHED
            $transferToPaintingParse = oci_parse($conn, "INSERT INTO PAINTING (PROJECT_NAME, HEAD_MARK, ID, BLASTING, PRIMER, "
                    . " INTERMEDIATE, FINISHING, ENTRY_DATE, "
                    . "PAINT_STATUS, UNIT_QTY) "
                    . "VALUES (:projName, :headMark, :idToUpdate, 0,0,0,0, TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss'), "
                    . "'NOTCOMPLETE', :unitQty)");
            oci_bind_by_name($transferToPaintingParse, ":projName", $ProjNme);
            oci_bind_by_name($transferToPaintingParse, ":headMark", $HEAD_MARK);
            oci_bind_by_name($transferToPaintingParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($transferToPaintingParse, ":unitQty", $dataQCPass);
            $transferToPaintingRes = oci_execute($transferToPaintingParse);

            //INSERT INTO PAINTING QC
            $transferToPaintingQcParse = oci_parse($conn, "INSERT INTO PAINTING_QC (PROJECT_NAME, HEAD_MARK, ID, BLASTING_QC, PRIMER_QC, INTERMEDIATE_QC, FINISHING_QC, PAINT_QC_PASS,  "
                    . " UNIT_QTY, PAINT_QC_STATUS) "
                    . "VALUES (:projName, :headMark, :idToUpdate, 0,0,0,0,0, :unitQty, 'NOTPASSED')");
            oci_bind_by_name($transferToPaintingQcParse, ":projName", $ProjNme);
            oci_bind_by_name($transferToPaintingQcParse, ":headMark", $HEAD_MARK);
            oci_bind_by_name($transferToPaintingQcParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($transferToPaintingQcParse, ":unitQty", $dataQCPass);
            $transferToPaintingQcRes = oci_execute($transferToPaintingQcParse);

            if ($transferToPaintingRes && $transferToPaintingQcRes) {
                oci_commit($conn);
                // echo "<script>alert('TRF PAINT SUCCESS');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('TRF PAINT FAILED');</script>";
            }
        } else {
            # code...
            $updateInPaintingSql = "UPDATE PAINTING SET UNIT_QTY = UNIT_QTY+$dataQCPass, PAINT_STATUS='NOTCOMPLETE' ,ENTRY_DATE = TO_DATE('$proc_date','MM/DD/YYYY hh24:mi:ss') "
                    . "WHERE PROJECT_NAME = :PROJNAME AND HEAD_MARK = :HEADMARK AND ID = :IDENT";
            $updateInPaintingParse = oci_parse($conn, $updateInPaintingSql);
            oci_bind_by_name($updateInPaintingParse, ":PROJNAME", $ProjNme);
            oci_bind_by_name($updateInPaintingParse, ":HEADMARK", $HEAD_MARK);
            oci_bind_by_name($updateInPaintingParse, ":IDENT", $HM_ID);
            $updateInPaintingRes = oci_execute($updateInPaintingParse);

            $updateInPaintingQcSql = "UPDATE PAINTING_QC SET UNIT_QTY = UNIT_QTY+$dataQCPass , PAINT_QC_STATUS = 'NOTPASSED'"
                    . "WHERE PROJECT_NAME = :PROJNAME AND HEAD_MARK = :HEADMARK AND ID = :IDENT";
            $updateInPaintingQcParse = oci_parse($conn, $updateInPaintingQcSql);
            oci_bind_by_name($updateInPaintingQcParse, ":PROJNAME", $ProjNme);
            oci_bind_by_name($updateInPaintingQcParse, ":HEADMARK", $HEAD_MARK);
            oci_bind_by_name($updateInPaintingQcParse, ":IDENT", $HM_ID);
            $updateInPaintingQcRes = oci_execute($updateInPaintingQcParse);

            if ($updateInPaintingRes && $updateInPaintingQcRes) {
                oci_commit($conn);
                // echo "<script>alert('PAINT UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('PAINT NOT UPDATED');</script>";
            }
        }

        $alertBOX .= "QC PASS $HEAD_MARK UPDATED ## ";
    }
    // END PROSES FABRICATION QC                
}
?>
<?php

// exit();
function jumlahREMS($HM, $ID, $PROC_SUB_TYPE, $conn) {
    $sql_jml = "SELECT COUNT(*) FROM MD_PROC_DELAY_REMS WHERE REGEXP_REPLACE (HEAD_MARK, '[[:space:]]+', '') = '" . str_replace(' ', '', $HM) . "' AND ID='$ID' "
            . " AND PROC_SUB_TYPE='$PROC_SUB_TYPE' AND ENTRY_DATE = TO_DATE('" . date("m/d/Y") . "','MM/DD/YYYY')";
    $jml_now_rems = SingleQryFld($sql_jml, $conn);

    return $jml_now_rems;
}

// SHOW IN ROW TABLE
$tableSql = " SELECT * FROM VW_FAB_UPDATE WHERE PROJECT_NAME= '{$ProjNme}' AND HEAD_MARK = '$HEAD_MARK' AND ID = '$HM_ID'";
// echo "$tableSql";
$tableParse = oci_parse($conn, $tableSql);
oci_execute($tableParse);
$i = $no;
while ($row = oci_fetch_assoc($tableParse)) {
    // echo $row['ASSG_QTY']."==".$row['MARK']."<br>";
    $availableMarking = $row['ASSG_QTY'] - $row['MARK'];
    $availableCutting = $row['MARK'] - $row['CUT'];
    $availableAssembly = $row['CUT'] - $row['ASSY'];
    $availableWelding = $row['ASSY'] - $row['WELD'];
    $availableDrilling = $row['WELD'] - $row['DRILL'];
    $availableFinishing = $row['DRILL'] - $row['FINISH'];

    $availableQCPass = $row['FINISH'] - $row['FAB_QC_PASS'];

    // css for delay 
    $today = date("m/d/Y");

    $css_delay_MARK = '';
    $css_delay_CUT = '';
    $css_delay_ASSY = '';
    $css_delay_WELD = '';
    $css_delay_DRILL = '';
    $css_delay_FINISH = '';

    $css_rems_btn_MARK = 'btn-default';
    $css_rems_btn_CUT = 'btn-default';
    $css_rems_btn_ASSY = 'btn-default';
    $css_rems_btn_WELD = 'btn-default';
    $css_rems_btn_DRILL = 'btn-default';
    $css_rems_btn_FINISH = 'btn-default';

    if ($row['MARK'] == 0 and $row['MARK_MAXDATE'] != null) {
        $max_date_MARK = $row['MARK_MAXDATE'];
        $max_date_MARK = new dateTime($max_date_MARK);
        $max_date_MARK = $max_date_MARK->format('m/d/Y');
        if ($today >= $max_date_MARK) {
            $css_delay_MARK = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'MARK', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_MARK = 'btn-success';
        }
    }
    if ($row['CUT'] == 0 and $row['CUT_MAXDATE'] != null) {
        $max_date_CUT = $row['CUT_MAXDATE'];
        $max_date_CUT = new dateTime($max_date_CUT);
        $max_date_CUT = $max_date_CUT->format('m/d/Y');
        if ($today >= $max_date_CUT) {
            $css_delay_CUT = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'CUT', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_CUT = 'btn-success';
        }
    }
    if ($row['ASSY'] == 0 and $row['ASSY_MAXDATE'] != null) {
        $max_date_ASSY = $row['ASSY_MAXDATE'];
        $max_date_ASSY = new dateTime($max_date_ASSY);
        $max_date_ASSY = $max_date_ASSY->format('m/d/Y');
        if ($today >= $max_date_ASSY) {
            $css_delay_ASSY = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'ASSY', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_ASSY = 'btn-success';
        }
    }
    if ($row['WELD'] == 0 and $row['WELD_MAXDATE'] != null) {
        $max_date_WELD = $row['WELD_MAXDATE'];
        $max_date_WELD = new dateTime($max_date_WELD);
        $max_date_WELD = $max_date_WELD->format('m/d/Y');
        if ($today >= $max_date_WELD) {
            $css_delay_WELD = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'WELD', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_WELD = 'btn-success';
        }
    }
    if ($row['DRILL'] == 0 and $row['DRILL_MAXDATE'] != null) {
        $max_date_DRILL = $row['DRILL_MAXDATE'];
        $max_date_DRILL = new dateTime($max_date_DRILL);
        $max_date_DRILL = $max_date_DRILL->format('m/d/Y');
        if ($today >= $max_date_DRILL) {
            $css_delay_DRILL = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'DRILL', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_DRILL = 'btn-success';
        }
    }
    if ($row['FINISH'] == 0 and $row['FINISH_MAXDATE'] != null) {
        $max_date_FINISH = $row['FINISH_MAXDATE'];
        $max_date_FINISH = new dateTime($max_date_FINISH);
        $max_date_FINISH = $max_date_FINISH->format('m/d/Y');
        if ($today >= $max_date_FINISH) {
            $css_delay_FINISH = 'delay_notif';
        }

        $jml_now_rems = jumlahREMS($row['HEAD_MARK'], $row['ID'], 'FAB FINS', $conn);
        if ($jml_now_rems > 0) {
            $css_rems_btn_FINISH = 'btn-success';
        }
    }
    ?>
    <td style="text-align:left;" ><font size='2'><b><?php echo $row["HEAD_MARK"] ?></b></font></td>
    <td style="text-align:left;" ><font size='2'><b><?php echo $row["COMP_TYPE"] ?></b></font></td>
    <td style="text-align:left;" ><font size='2'><b><?php echo $row["PROFILE"] ?></b></font></td>
    <td ><font size='2' id="<?php echo "HM_ID$i" ?>"><b><?php echo $row["ID"] ?></b></font></td>
    <td ><font size='2' id="<?php echo "AssgDate$i" ?>"><b><?php echo $row["ASSG_DATE"] ?></b></font></td>
    <td style="background-color:#EEEEEE;"><font size='2' color='#009933'><b><?php echo $row["TOTAL_QTY"] ?></b></font></td>
    <td style="background-color:#EEEEEE;"><font size='2' color='#0000FF' id="<?php echo "AssgQTY$i" ?>"><b><?php echo $row["ASSG_QTY"] ?></b></font></td>
    <td style="background-color:#EEEEEE;"><font size='2' color='#0000FF'><b><?php echo $row["WEIGHT"] ?></b></font></td>
    <td>
        <font size='2' ><b><?php echo $row["SUBCONT_ID"] ?></b></font>
    </td>
    <td class="<?php echo $css_delay_MARK ?>">
        <input type="hidden" id="<?php echo "maxMARKFrst$i" ?>" value="<?php echo $availableMarking ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['MARK']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['MARKING_FAB_DATE'] ?></small></i>
            <input  name="<?php echo "dataMarking$i" ?>" id="<?php echo "dataMarking$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableMarking ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'MARK');
                        ValidateDbleInput('<?php echo $i ?>', 'MARKING', '<?php echo $row['MARK'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataMarking$i" ?>" class='form-control' id="<?php echo "dataMarking$i" ?>" type='number' min='0' value="0" max='<?php echo $availableMarking ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remMark" . $i; ?>"><?php echo $availableMarking ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_MARK ?>" onclick="showRemaks('<?php echo $i ?>', '0', 'MARKING')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_0"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
        <?php }
        ?>
    </td>
    <td class="<?php echo $css_delay_CUT ?>">
        <input type="hidden" id="<?php echo "maxCUTFrst$i" ?>" value="<?php echo $availableCutting ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['CUT']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['CUTTING_FAB_DATE'] ?></small></i>
            <input name="<?php echo "dataCutting$i" ?>" id="<?php echo "dataCutting$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableCutting ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'CUT');
                        ValidateDbleInput('<?php echo $i ?>', 'CUTTING', '<?php echo $row['CUT'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataCutting$i" ?>" class='form-control' id="<?php echo "dataCutting$i" ?>" type='number' min='0' value="0" max='<?php echo $availableCutting ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remCut" . $i; ?>"><?php echo $availableCutting ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_CUT ?>" onclick="showRemaks('<?php echo $i ?>', '1', 'CUTTING')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_1"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
        <?php }
        ?>
    </td>
    <td class="<?php echo $css_delay_ASSY ?>">
        <input type="hidden" id="<?php echo "maxASSYFrst$i" ?>" value="<?php echo $availableAssembly ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['ASSY']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['ASSEMBLY_FAB_DATE'] ?></small></i>
            <input  name="<?php echo "dataAssembly$i" ?>" id="<?php echo "dataAssembly$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableAssembly ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'ASSY');
                        ValidateDbleInput('<?php echo $i ?>', 'ASSEMBLY', '<?php echo $row['ASSY'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataAssembly$i" ?>" class='form-control' id="<?php echo "dataAssembly$i" ?>" type='number' min='0' value="0" max='<?php echo $availableAssembly ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remAssy" . $i; ?>"><?php echo $availableAssembly ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_ASSY ?>" onclick="showRemaks('<?php echo $i ?>', '2', 'ASSEMBLY')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_2"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
            <?php
        }
        ?>
    </td>
    <td class="<?php echo $css_delay_WELD ?>">
        <input type="hidden" id="<?php echo "maxWELDFrst$i" ?>" value="<?php echo $availableWelding ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['WELD']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['WELDING_FAB_DATE'] ?></small></i>
            <input  name="<?php echo "dataWelding$i" ?>" id="<?php echo "dataWelding$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableWelding ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'WELD');
                        ValidateDbleInput('<?php echo $i ?>', 'WELDING', '<?php echo $row['WELD'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataWelding$i" ?>" class='form-control' id="<?php echo "dataWelding$i" ?>" type='number' min='0' value="0" max='<?php echo $availableWelding ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remWeld" . $i; ?>"><?php echo $availableWelding ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_WELD ?>" onclick="showRemaks('<?php echo $i ?>', '3', 'WELDING')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_3"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
            <?php
        }
        ?>
    </td>
    <td class="<?php echo $css_delay_DRILL ?>">
        <input type="hidden" id="<?php echo "maxDRILLFrst$i" ?>" value="<?php echo $availableDrilling ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['DRILL']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['DRILLING_FAB_DATE'] ?></small></i>
            <input name="<?php echo "dataDrilling$i" ?>" id="<?php echo "dataDrilling$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableDrilling ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'DRILL');
                        ValidateDbleInput('<?php echo $i ?>', 'DRILLING', '<?php echo $row['DRILL'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataDrilling$i" ?>" class='form-control' id="<?php echo "dataDrilling$i" ?>" type='number' min='0' value="0" max='<?php echo $availableDrilling ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remDrill" . $i; ?>"><?php echo $availableDrilling ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_DRILL ?>" onclick="showRemaks('<?php echo $i ?>', '4', 'DRILLING')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_4"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
            <?php
        }
        ?>
    </td>
    <td class="<?php echo $css_delay_FINISH ?>">
        <input type="hidden" id="<?php echo "maxFNISHFrst$i" ?>" value="<?php echo $availableFinishing ?>">
        <input type="hidden" id="<?php echo "ValueFNISHFrst$i" ?>" value="<?php echo $row['FINISH'] ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['FINISH']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <i><small><?php echo $row['FINISHING_FAB_DATE'] ?></small></i>
            <input name="<?php echo "dataFinishing$i" ?>" id="<?php echo "dataFinishing$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableFinishing ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'FINISH');
                        ValidateDbleInput('<?php echo $i ?>', 'FINISHING', '<?php echo $row['FINISH'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataFinishing$i" ?>" class='form-control' id="<?php echo "dataFinishing$i" ?>" type='number' min='0' value="0" max='<?php echo $availableFinishing ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remFinish" . $i; ?>"><?php echo $availableFinishing ?></sup>
                    <button type="button" class="morphbutton btn-xs <?php echo $css_rems_btn_FINISH ?>" onclick="showRemaks('<?php echo $i ?>', '5', 'FABRICATION FINISH')" style="float:right;" data-target="#div_rems_hist<?php echo $i ?>_5" ><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
                </div>
            </div>
            <?php
        }
        ?>
    </td>
    <td style="background-color: #5BC0DE;">
        <input type="hidden" id="<?php echo "maxQCPASSFrst$i" ?>" value="<?php echo $availableQCPass ?>">
        <input type="hidden" id="<?php echo "ValueQCPASSFrst$i" ?>" value="<?php echo $row['FAB_QC_PASS'] ?>">
        <?php
        if ($row['ASSG_QTY'] === $row['FAB_QC_PASS']) {
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input name="<?php echo "dataQCPass$i" ?>" id="<?php echo "dataQCPass$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableQCPass ?>' width='10'>
            <?php
        } else {
            ?>
            <div class="input-group">
                <input onchange="FabChange('<?php echo $i ?>', 'QCPASS');
                        ValidateDbleInput('<?php echo $i ?>', 'FAB_QC_PASS', '<?php echo $row['FAB_QC_PASS'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataQCPass$i" ?>" class='form-control' id="<?php echo "dataQCPass$i" ?>" type='number' min='0' value="0" max='<?php echo $availableQCPass ?>' style="width:75px;">
                <div class="input-group-btn">
                    <sup class="badge" id="<?php echo "remQcPass" . $i; ?>"><?php echo $availableQCPass ?></sup>
                </div>
            </div>
            <?php
        }
        ?>
    </td>
    <td><input type="text" class="form-control" readonly="" id="proc_date<?php echo $i ?>" value="<?php echo date("m/d/Y") ?>"></td></td>
    <td>
        <?php
        if ($row['FAB_QC_PASS'] === $row['ASSG_QTY']) {
            echo "<input type='button' class='btn btn-success btn-default btn-sm' name='submit' id='submit' value='FABDONE' disabled>";
        } else {
            ?>
            <input type='button' onclick="return doSubmit('<?php echo $i ?>', '<?php echo $row['HEAD_MARK'] ?>');" class='btn btn-success btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='DOUBLE CHECK YOUR DATA BEFORE SUBMITTING !!!' name='submit<?php echo $i ?>' id='submit<?php echo $i ?>' value='SUBMIT !'>
            <?php
        }
        ?>
    </td>
    <?php
}
?>
<script type="text/javascript">
    var alert = "<?php echo $alertBOX ?>";

    $(document).ready(function () {
        var index = "<?php echo $i ?>";
        $('#proc_date' + index).datepick({
            dateFormat: 'mm/dd/yyyy',
            renderer: $.extend({}, $.datepick.defaultRenderer,
                    {picker: $.datepick.defaultRenderer.picker.
                                replace(/\{link:clear\}/, '')})
        });

        $('tr[id=baris' + index + ']').each(function () {
            var btn = $(this).find('td').find('div div button[class ^= "morphbutton"]');
            btn.each(function () {
                var idbton = $(this).attr('id');

                if (idbton == undefined) { // jika belum ada plugin
                    var datatrget = $(this).attr('data-target').replace('#', '');
                    var iddtaTrget = datatrget.replace('div_rems_hist' + index + '_', '');
                    $('#div_rems').append('<div id="' + datatrget + '" class="morphbutton-content">' +
                            '<div class="box">' +
                            '<button type="button" class="morphbutton-close"><span class="glyphicon glyphicon-remove"></span></button>' +
                            '<div id="div_rems_hist_conten' + index + '_' + iddtaTrget + '"></div>',
                            '</div>' +
                            '</div>');

                    $(this).morphButton();
                    $(this).attr('id', 'btn-rems' + index + '_' + iddtaTrget);
                    // console.log(datatrget);
                }
            });
        });
    });
</script>