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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | MD REVISION</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara Master Drawing Revision">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" />

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function doSubmit() {
                if (confirm('Are you sure you want to submit Project Edit Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }

            function ValIdate() {
                // body...
                $('input[type="text"]').on('keyup change', function () {
                    if ($(this).val() != '') {
                        ValBtton(true);
                    } else {
                        ValBtton(false);
                    }
                });
            }

            function ValBtton(state) {
                if (state == true) {
                    $('input[type="submit"]').removeAttr('disabled');
                } else {
                    $('input[type="submit"]').attr('disabled', 'disabled');
                }
            }
        </script>
        <script src="../jQuery/jquery-1.11.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-select.min.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/mdRevision.js"></script>
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>ADMIN TOOLS</b></font> ~ <font color="#CC0000" size="5"><b>MASTER DRAWING REVISION</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" id="MDRevision" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit='return doSubmit()'>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                        <div class="col-sm-10">
                            <?php
                            $sql_project = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO,PROJECT_NAME_NEW";
                            $proj_result = oci_parse($conn, $sql_project);

                            oci_execute($proj_result);

                            echo '<select class="form-control" name="projName" id="projName" onChange="getComponent(this.value);ValBtton(false);" data-live-search="true">' . '<br>';
                            echo '<option value=" " selected="" disabled="">' . "" . '</OPTION>';

                            while ($row = oci_fetch_array($proj_result)) {
                                $proj = $row['PROJECT_NAME_OLD'];
                                echo "<OPTION VALUE='$proj'>" . $row['PROJECT_NO'] . " - " . $row['PROJECT_NAME_NEW'] . "</OPTION>";
                            }
                            echo '</select>';
                            ?>
                        </div>
                    </div>

                    <div class="form-group" id="revisionComp"></div>
                    <div class="form-group" id="revisionHeadmark"></div>
                    <div class="form-group" id="reviseableElements"></div>

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-success btn-sm" name="revise" value="REVISE HEADMARK">
                                <!--<input type="submit" class="btn btn-danger btn-sm" name="delete" value="DELETE HEADMARK">-->
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  
            <?php
            if (isset($_POST['revise'])) {
                // echo "DALAM PERBAIKAN";exit();
                $weightRev = $_POST["unitWeightRev"];
                $unitGRWeightRev = $_POST['unitGRWeightRev'];
                $surfaceRev = $_POST["unitSurfaceRev"];
                $lengthRev = $_POST["unitLengthRev"];
                $qtyRev = $_POST["unitQtyRev"];
                $projRev = $_POST["projName"];
                $compRev = $_POST["compSelect"];
                $hmRev = $_POST["hmSelect"];
                $profileRev = $_POST["unitProfile"];
                $revisionRemarks = $_POST["revRemarks"];
                $actDistrib = $_POST["actDistrib"];
                $actREV = $_POST["actREV"];
                $drawingStatus = $_POST["drawingStatus"];
                $dwg_typ = $_POST['dwg_typ'];

                $perHruf = concatHM($hmRev);
                $str_HM = @$perHruf[0];
                $int_HM = @$perHruf[1];
                $pad_HM = @$perHruf[2];
                if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
                    $str_HM = $hmRev;
                    $int_HM = 0;
                    $pad_HM = 0;
                }


                $jmlFbrc = SingleQryFld("SELECT SUM(UNIT_QTY) FROM FABRICATION WHERE HEAD_MARK='$hmRev' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))", $conn);
                $ppic_chk = SingleQryFld("SELECT PPIC_CHECK FROM MASTER_DRAWING WHERE DWG_STATUS = 'ACTIVE' AND HEAD_MARK='$hmRev' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))", $conn);
                $ppic_chk_sign = SingleQryFld("SELECT PPIC_CHECK_SIGN FROM MASTER_DRAWING WHERE DWG_STATUS = 'ACTIVE' AND HEAD_MARK='$hmRev' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))", $conn);
                $ppic_chk_id = SingleQryFld("SELECT PPIC_CHECK_ID FROM MASTER_DRAWING WHERE DWG_STATUS = 'ACTIVE' AND HEAD_MARK='$hmRev' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))", $conn);
                // echo "$jmlFbrc";exit();

                $updateMdSql = "UPDATE MASTER_DRAWING SET DWG_STATUS = 'INACTIVE', REV_SUBJ= :REVSBJ "
                        . " WHERE REV=$actREV AND (HEAD_MARK = :HEADMARK OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) )";
                $updateMdParse = oci_parse($conn, $updateMdSql);
                oci_bind_by_name($updateMdParse, ":HEADMARK", $hmRev);
                oci_bind_by_name($updateMdParse, ":REVSBJ", $revisionRemarks);

                $updateMdRes = oci_execute($updateMdParse);


                $updateMdAssignedSql = "UPDATE MASTER_DRAWING_ASSIGNED SET REVISION_NO = (REVISION_NO + 1) "
                        . " WHERE HEAD_MARK = :HEADMARK OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                $updateMdAssignedParse = oci_parse($conn, $updateMdAssignedSql);
                oci_bind_by_name($updateMdAssignedParse, ":HEADMARK", $hmRev);

                $updateMdAssignedRes = oci_execute($updateMdAssignedParse);

                if ($updateMdRes && $updateMdAssignedRes) {
                    oci_commit($conn);
                    echo 'MASTER DRAWING UPDATED,<br>';
                } else {
                    oci_rollback($conn);
                    echo 'ERROR OCCURED,<br>';
                }

                // if ($drawingStatus == "ASSIGNED") {
                # code...
                if ($jmlFbrc > $qtyRev or $jmlFbrc == 0) {
                    $drawingStatus = "NOTASSIGNED";
                    $actDistrib = 0;
                    $ppic_chk = 0;
                    $ppic_chk_id = 0;

                    // Delete MD ASSIGN
                    $MDDelSql = "DELETE FROM MASTER_DRAWING_ASSIGNED WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET MASTER_DRAWING_ASSIGNED<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET MASTER_DRAWING_ASSIGNED<br>";
                    }

                    // Delete FAB
                    $MDDelSql = "DELETE FROM FABRICATION WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION_HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION_HIST<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION QC<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION QC<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION QC HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION QC HIST<br>";
                    }

                    // DEL PAINT
                    $PaintDelSql = "DELETE FROM PAINTING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING HIST<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING QC<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING QC<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING QC HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING QC HIST<br>";
                    }

                    // DEL PACKING DTL
                    $PckDelSql = "DELETE FROM PREPACKING_LIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PckDelParse = oci_parse($conn, $PckDelSql);
                    oci_bind_by_name($PckDelParse, ":headmark", $hmRev);
                    $PckDelRes = oci_execute($PckDelParse);
                    if ($PckDelRes) {
                        oci_commit($conn);
                        echo "DELET PREPACK<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PREPACK<br>";
                    }

                    $PckDelSql = "DELETE FROM DTL_PACKING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PckDelParse = oci_parse($conn, $PckDelSql);
                    oci_bind_by_name($PckDelParse, ":headmark", $hmRev);
                    $PckDelRes = oci_execute($PckDelParse);
                    if ($PckDelRes) {
                        oci_commit($conn);
                        echo "DELET DTL PACK<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET DTL PACK<br>";
                    }
                } else {

                    // FABRICATION SYNC PROCESS
                    // 1. SYNC FABRICATION.UNIT_WEIGHT WITH MASTER_DRAWING_ASSIGNED.ASSIGNED_WEIGHT
//                    $fabricationWeightSyncSql = "MERGE INTO FABRICATION DST USING (SELECT ASSIGNED_WEIGHT, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_WEIGHT = SRC.ASSIGNED_WEIGHT WHERE PROJECT_NAME = :PROJNAME";
//                    $fabricationWeightSyncParse = oci_parse($conn, $fabricationWeightSyncSql);
//                    oci_bind_by_name($fabricationWeightSyncParse, ":PROJNAME", $projRev);
//                    $fabricationWeightSyncRes = oci_execute($fabricationWeightSyncParse);
//                    if ($fabricationWeightSyncRes) {
//                        oci_commit($conn);
//                        echo 'FABRICATION.UNIT_WEIGHT UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING FABRICATION.UNIT_WEIGHT, <br>';
//                    }
                    // 2. SYNC FABRICATION.UNIT_SURFACE WITH MASTER_DRAWING_ASSIGNED.SURFACE
//                    $fabricationSurfaceSyncSql = "MERGE INTO FABRICATION DST USING (SELECT SURFACE, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_SURFACE = SRC.SURFACE WHERE PROJECT_NAME = :PROJNAME";
//                    $fabricationSurfaceSyncParse = oci_parse($conn, $fabricationSurfaceSyncSql);
//                    oci_bind_by_name($fabricationSurfaceSyncParse, ":PROJNAME", $projRev);
//                    $fabricationSurfaceSyncRes = oci_execute($fabricationSurfaceSyncParse);
//                    if ($fabricationSurfaceSyncRes) {
//                        oci_commit($conn);
//                        echo 'FABRICATION.UNIT_SURFACE UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING FABRICATION.UNIT_SURFACE, <br>';
//                    }
                    // 3. SYNC FABRICATION_QC.UNIT_WEIGHT WITH MASTER_DRAWING_ASSIGNED.ASSIGNED_WEIGHT
//                    $fabricationQcWeightSyncSql = "MERGE INTO FABRICATION_QC DST USING (SELECT ASSIGNED_WEIGHT, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_WEIGHT = SRC.ASSIGNED_WEIGHT WHERE PROJECT_NAME = :PROJNAME";
//                    $fabricationQcWeightSyncParse = oci_parse($conn, $fabricationQcWeightSyncSql);
//                    oci_bind_by_name($fabricationQcWeightSyncParse, ":PROJNAME", $projRev);
//                    $fabricationQcWeightSyncRes = oci_execute($fabricationQcWeightSyncParse);
//                    if ($fabricationQcWeightSyncRes) {
//                        oci_commit($conn);
//                        echo 'FABRICATION_QC.UNIT_WEIGHT UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING FABRICATION_QC.UNIT_WEIGHT, <br>';
//                    }
                    // 4. SYNC FABRICATION_QC.UNIT_SURFACE WITH MASTER_DRAWING_ASSIGNED.SURFACE
//                    $fabricationQcSurfaceSyncSql = "MERGE INTO FABRICATION_QC DST USING (SELECT SURFACE, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_SURFACE = SRC.SURFACE WHERE PROJECT_NAME = :PROJNAME";
//                    $fabricationQcSurfaceSyncParse = oci_parse($conn, $fabricationQcSurfaceSyncSql);
//                    oci_bind_by_name($fabricationQcSurfaceSyncParse, ":PROJNAME", $projRev);
//                    $fabricationQcSurfaceSyncRes = oci_execute($fabricationQcSurfaceSyncParse);
//                    if ($fabricationQcSurfaceSyncRes) {
//                        oci_commit($conn);
//                        echo 'FABRICATION_QC.UNIT_SURFACE UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING FABRICATION_QC.UNIT_SURFACE, <br>';
//                    }
//
//                    if ($jmlFbrc <> $qtyRev) {
//                        $drawingStatus = "NOTASSIGNED";
//                    }
                    // PAINTING SYNC PROCESS
                    // 1. SYNC PAINTING.UNIT_SURFACE WITH MASTER_DRAWING_ASSIGNED.SURFACE
//                    $paintingSurfaceSyncSql = "MERGE INTO PAINTING DST USING (SELECT SURFACE, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_SURFACE = SRC.SURFACE WHERE PROJECT_NAME = :PROJNAME";
//                    $paintingSurfaceSyncParse = oci_parse($conn, $paintingSurfaceSyncSql);
//                    oci_bind_by_name($paintingSurfaceSyncParse, ":PROJNAME", $projRev);
//                    $paintingSurfaceSyncRes = oci_execute($paintingSurfaceSyncParse);
//                    if ($paintingSurfaceSyncRes) {
//                        oci_commit($conn);
//                        echo 'PAINTING.UNIT_SURFACE UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING PAINTING.UNIT_SURFACE, <br>';
//                    }
                    // 2. SYNC PAINTING.UNIT_WEIGHT WITH MASTER_DRAWING_ASSIGNED.ASSIGNED_WEIGHT
//                    $paintingWeightSyncSql = "MERGE INTO PAINTING DST USING (SELECT ASSIGNED_WEIGHT, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_WEIGHT = SRC.ASSIGNED_WEIGHT WHERE PROJECT_NAME = :PROJNAME";
//                    $paintingWeightSyncParse = oci_parse($conn, $paintingWeightSyncSql);
//                    oci_bind_by_name($paintingWeightSyncParse, ":PROJNAME", $projRev);
//                    $paintingWeightSyncRes = oci_execute($paintingWeightSyncParse);
//                    if ($paintingWeightSyncRes) {
//                        oci_commit($conn);
//                        echo 'PAINTING.UNIT_WEIGHT UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING PAINTING.UNIT_WEIGHT, <br>';
//                    }
                    // 3. SYNC PAINTING_QC.UNIT_SURFACE WITH MASTER_DRAWING_ASSIGNED.SURFACE
//                    $paintingQcSurfaceSyncSql = "MERGE INTO PAINTING_QC DST USING (SELECT SURFACE, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_SURFACE = SRC.SURFACE WHERE PROJECT_NAME = :PROJNAME";
//                    $paintingQcSurfaceSyncParse = oci_parse($conn, $paintingQcSurfaceSyncSql);
//                    oci_bind_by_name($paintingQcSurfaceSyncParse, ":PROJNAME", $projRev);
//                    $paintingQcSurfaceSyncRes = oci_execute($paintingQcSurfaceSyncParse);
//                    if ($paintingQcSurfaceSyncRes) {
//                        oci_commit($conn);
//                        echo 'PAINTING_QC.UNIT_SURFACE UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING PAINTING_QC.UNIT_SURFACE, <br>';
//                    }
                    // 4. SYNC PAINTING_QC.UNIT_WEIGHT WITH MASTER_DRAWING_ASSIGNED.ASSIGNED_WEIGHT
//                    $paintingQcWeightSyncSql = "MERGE INTO PAINTING_QC DST USING (SELECT ASSIGNED_WEIGHT, HEAD_MARK FROM MASTER_DRAWING_ASSIGNED) SRC 
//                            ON (DST.HEAD_MARK = SRC.HEAD_MARK) WHEN MATCHED THEN UPDATE SET DST.UNIT_WEIGHT = SRC.ASSIGNED_WEIGHT WHERE PROJECT_NAME = :PROJNAME";
//                    $paintingQcWeightSyncParse = oci_parse($conn, $paintingQcWeightSyncSql);
//                    oci_bind_by_name($paintingQcWeightSyncParse, ":PROJNAME", $projRev);
//                    $paintingQcWeightSyncRes = oci_execute($paintingQcWeightSyncParse);
//                    if ($paintingQcWeightSyncRes) {
//                        oci_commit($conn);
//                        echo 'PAINTING_QC.UNIT_WEIGHT UPDATED, <br>';
//                    } else {
//                        oci_rollback($conn);
//                        echo 'ERROR OCCURED IN UPDATING PAINTING_QC.UNIT_WEIGHT, <br>';
//                    }
//
//                    if ($jmlFbrc <> $qtyRev) {
//                        $drawingStatus = "NOTASSIGNED";
//                    }
                }
                // }

                $insertMdHistSql = "INSERT INTO MASTER_DRAWING (HEAD_MARK,ENTRY_DATE,COMP_TYPE,WEIGHT,SURFACE,PROFILE,"
                        . "PROJECT_NAME,LENGTH,TOTAL_QTY,SUBCONT_STATUS,DWG_STATUS,REV,DISTRIBUTION_COUNT,"
                        . "GR_WEIGHT,DWG_TYP,ENTRY_SIGN,PPIC_CHECK,PPIC_CHECK_SIGN,PPIC_CHECK_ID) "
                        . "VALUES (('$str_HM'||LPAD('$int_HM',$pad_HM)), SYSDATE, :COMPTYPE, :WEIGHT, :SURFACE, :PROFILE, "
                        . ":PROJNAME, :LENGTH, :TOTALQTY, '$drawingStatus','ACTIVE', $actREV+1, :DISTRIB, "
                        . ":GRWEIGHT,'$dwg_typ','$username','$ppic_chk','$ppic_chk_sign','$ppic_chk_id')";

                $insertMdHistParse = oci_parse($conn, $insertMdHistSql);

                oci_bind_by_name($insertMdHistParse, ":PROJNAME", $projRev);
                // oci_bind_by_name($insertMdHistParse, ":HEADMARK", $hmRev);
                oci_bind_by_name($insertMdHistParse, ":COMPTYPE", $compRev);
                oci_bind_by_name($insertMdHistParse, ":LENGTH", $lengthRev);
                oci_bind_by_name($insertMdHistParse, ":SURFACE", $surfaceRev);
                oci_bind_by_name($insertMdHistParse, ":WEIGHT", $weightRev);
                oci_bind_by_name($insertMdHistParse, ":TOTALQTY", $qtyRev);
                oci_bind_by_name($insertMdHistParse, ":PROFILE", $profileRev);
                oci_bind_by_name($insertMdHistParse, ":DISTRIB", $actDistrib);
                oci_bind_by_name($insertMdHistParse, ":GRWEIGHT", $unitGRWeightRev);


                $insertMdHistRes = oci_execute($insertMdHistParse);

                if ($insertMdHistRes) {
                    oci_commit($conn);
                    echo 'MASTER DRAWING INSERT NEW REVISION, <br>';
                } else {
                    oci_rollback($conn);
                    echo 'ERROR OCCURED, <br>';
                }

                echo "<script>alert('REVISION OK');window.location.href='$_SERVER[PHP_SELF]'</script>";
            } elseif (isset($_POST['delete'])) {

                $weightRev = $_POST["unitWeightRev"];
                $surfaceRev = $_POST["unitSurfaceRev"];
                $lengthRev = $_POST["unitLengthRev"];
                $qtyRev = $_POST["unitQtyRev"];
                $projRev = $_POST["projName"];
                $compRev = $_POST["compSelect"];
                $hmRev = $_POST["hmSelect"];
                $revisionRemarks = $_POST["revRemarks"];

                $perHruf = concatHM($hmRev);
                $str_HM = @$perHruf[0];
                $int_HM = @$perHruf[1];
                $pad_HM = @$perHruf[2];
                if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
                    $str_HM = $hmRev;
                    $int_HM = 0;
                    $pad_HM = 0;
                }

                $deletionHistSql = "INSERT INTO MD_REVISION_HISTORY (PROJECT_NAME_REV, HEAD_MARK_REV, COMP_TYPE_REV, "
                        . " LENGTH_REV, SURFACE_REV, WEIGHT_REV, TOTAL_QTY_REV, REV_STATUS, REVISION_SIGN, REV_DATE_REV) "
                        . " VALUES (:PROJNAME, "
                        . " (SELECT HEAD_MARK FROM MASTER_DRAWING WHERE ROWNUM = 1 "
                        . " AND (HEAD_MARK = '$hmRev' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))), "
                        . " :COMPTYPE, :LENGTH, :SURFACE, :WEIGHT, :TOTALQTY, 'DELETION', '$username', SYSDATE)";

                $deletionSql = "UPDATE MASTER_DRAWING SET DWG_STATUS = 'INACTIVE',REV_SUBJ= :REVSBJ WHERE HEAD_MARK = :HEADMARK OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";

                $deletionHistParse = oci_parse($conn, $deletionHistSql);
                $deletionParse = oci_parse($conn, $deletionSql);

                oci_bind_by_name($deletionHistParse, ":PROJNAME", $projRev);
                // oci_bind_by_name($deletionHistParse, ":HEADMARK", $hmRev);
                oci_bind_by_name($deletionHistParse, ":COMPTYPE", $compRev);
                oci_bind_by_name($deletionHistParse, ":LENGTH", $lengthRev);
                oci_bind_by_name($deletionHistParse, ":SURFACE", $surfaceRev);
                oci_bind_by_name($deletionHistParse, ":WEIGHT", $weightRev);
                oci_bind_by_name($deletionHistParse, ":TOTALQTY", $qtyRev);

                // oci_bind_by_name($deletionParse, ":PROJNAME", $projRev);
                oci_bind_by_name($deletionParse, ":HEADMARK", $hmRev);
                // oci_bind_by_name($deletionParse, ":COMPTYPE", $compRev);
                oci_bind_by_name($deletionParse, ":REVSBJ", $revisionRemarks);

                $deletionHistRes = oci_execute($deletionHistParse);
                $deletionRes = oci_execute($deletionParse);

                if ($deletionHistRes && $deletionRes) {
                    echo 'ITEM DELETED<br>';
                    // Delete MDA
                    $DelMdAssignedSql = "DELETE FROM MASTER_DRAWING_ASSIGNED WHERE HEAD_MARK = :HEADMARK OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $DelMdAssignedParse = oci_parse($conn, $DelMdAssignedSql);
                    oci_bind_by_name($DelMdAssignedParse, ":HEADMARK", $hmRev);

                    $DelMdAssignedRes = oci_execute($DelMdAssignedParse);

                    if ($DelMdAssignedRes) {
                        oci_commit($conn);
                        echo 'DELET MASTER DRAWING ASSIGN<br>';
                    } else {
                        oci_rollback($conn);
                        echo 'ERROR OCCURED,<br>';
                    }

                    // Delete FAB
                    $MDDelSql = "DELETE FROM FABRICATION WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION_HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION_HIST<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION QC<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION QC<br>";
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $hmRev);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                        echo "DELET FABRICATION QC HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET FABRICATION QC HIST<br>";
                    }

                    // DEL PAINT
                    $PaintDelSql = "DELETE FROM PAINTING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING HIST<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING QC<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING QC<br>";
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $hmRev);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                        echo "DELET PAINTING QC HIST<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PAINTING QC HIST<br>";
                    }

                    // DEL PACKING DTL
                    $PckDelSql = "DELETE FROM PREPACKING_LIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PckDelParse = oci_parse($conn, $PckDelSql);
                    oci_bind_by_name($PckDelParse, ":headmark", $hmRev);
                    $PckDelRes = oci_execute($PckDelParse);
                    if ($PckDelRes) {
                        oci_commit($conn);
                        echo "DELET PREPACK<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET PREPACK<br>";
                    }

                    $PckDelSql = "DELETE FROM DTL_PACKING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
                    $PckDelParse = oci_parse($conn, $PckDelSql);
                    oci_bind_by_name($PckDelParse, ":headmark", $hmRev);
                    $PckDelRes = oci_execute($PckDelParse);
                    if ($PckDelRes) {
                        oci_commit($conn);
                        echo "DELET DTL PACK<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET DTL PACK<br>";
                    }

                    oci_commit($conn);
                    echo "<script>alert('DELETED OK');window.location.href='$_SERVER[PHP_SELF]'</script>";
                } else {
                    oci_rollback($conn);
                    echo 'ERROR OCCURED LAPOR GOBIS MAS<br>';
                    echo "<script>alert('DELETED FAILED');window.location.href='$_SERVER[PHP_SELF]'</script>";
                }
            }
            ?>
        </div> <!-- panel-default -->
        <script type="text/javascript">
            $(document).ready(function () {
                ValBtton(false);

                $('#projName').selectpicker();
            });
        </script>
    </body>
</html>    
