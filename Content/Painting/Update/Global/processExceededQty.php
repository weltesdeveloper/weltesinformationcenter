<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
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
// // echo "<script>alert('PAINTING UPDATED');</script>";
// exit();
$HM_ID = intval($_POST['HM_ID']);
$HEAD_MARK = strval($_POST['HEAD_MARK']);
$ProjNme = strval($_POST['ProjNme']);
$no = intval($_POST['no']);

if (isset($_POST['submit'])) {
    // echo "<script>alert('PAINTING $HEAD_MARK UPDATED');</script>";
    // exit();

    $FabPassQTY = intval($_POST['FabPassQTY']);
    $dataBlast = intval($_POST['dataBlast']);
    $dataPrimer = intval($_POST['dataPrimer']);
    $dataIntermd = intval($_POST['dataIntermd']);
    $dataFinishing = intval($_POST['dataFinishing']);
    $ValueFNISHFrst = intval($_POST['ValueFNISHFrst']);
    $dataQCPass = intval($_POST['dataQCPass']);
    $ValueQCPASSFrst = intval($_POST['ValueQCPASSFrst']);

    // echo "HM ID = $HM_ID -- i = $no -- HM = $HEAD_MARK -- $dataBlast -- $dataPrimer -- $dataIntermd -- $dataFinishing -- $dataQCPass -- AWL QC = $ValueQCPASSFrst";

    $alertBOX = "";

    // PROSES PAINTING
    if ($dataFinishing == 0 and $dataBlast == 0 and $dataPrimer == 0 and $dataIntermd == 0) {
        # code...
        // echo "<script>alert('PAINTING $HEAD_MARK NOT UPDATED');</script>";
        $alertBOX .= "Painting $HEAD_MARK NOT UPDATED ## ";
    } else {

        if ($dataBlast > 0) {

            $blastUpdateParse = oci_parse($conn, "UPDATE PAINTING SET BLASTING = BLASTING+$dataBlast, BLASTING_PAINT_DATE = SYSDATE, BLASTING_PAINT_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($blastUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($blastUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($blastUpdateParse, ":sign", $username);

            $blastUpdateRes = oci_execute($blastUpdateParse);

            if ($blastUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('MARKING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('MARKING NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataPrimer > 0) {

            $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING SET PRIMER = PRIMER+$dataPrimer, PRIMER_PAINT_DATE = SYSDATE, PRIMER_PAINT_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($SQLUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($SQLUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($SQLUpdateParse, ":sign", $username);

            $SQLUpdateRes = oci_execute($SQLUpdateParse);

            if ($SQLUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('PRIMER UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('PRIMER NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataIntermd > 0) {

            $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING SET INTERMEDIATE = INTERMEDIATE+$dataIntermd, INTERMEDIATE_PAINT_DATE = SYSDATE, INTERMEDIATE_PAINT_SIGN = :sign WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");

            oci_bind_by_name($SQLUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($SQLUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($SQLUpdateParse, ":sign", $username);

            $SQLUpdateRes = oci_execute($SQLUpdateParse);

            if ($SQLUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('ASSEMBLY UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('ASSEMBLY NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }
        if ($dataFinishing > 0) {

            if ($FabPassQTY == ($ValueFNISHFrst + $dataFinishing)) {
                # code...
                $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING SET FINISHING = FINISHING+$dataFinishing, FINISHING_PAINT_DATE = SYSDATE, FINISHING_PAINT_SIGN = :sign, PAINT_STATUS = 'COMPLETE'   WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            } else {
                $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING SET FINISHING = FINISHING+$dataFinishing, FINISHING_PAINT_DATE = SYSDATE, FINISHING_PAINT_SIGN = :sign  WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            }

            oci_bind_by_name($SQLUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
            oci_bind_by_name($SQLUpdateParse, ":idToUpdate", $HM_ID);
            oci_bind_by_name($SQLUpdateParse, ":sign", $username);

            $SQLUpdateRes = oci_execute($SQLUpdateParse);

            if ($SQLUpdateRes) {
                oci_commit($conn);
                // echo "<script>alert('FINISHING UPDATED');</script>";
            } else {
                oci_rollback($conn);
                // echo "<script>alert('FINISHING NOT UPDATED');</script>";
            }

            /* ============================================================= */
            /* ============================================================= */
        }

        //INSERT TO HISTORY PAINT
        $finishingHistUpdateParse = oci_parse($conn, "INSERT INTO PAINTING_HIST (PROJECT_NAME, HEAD_MARK, ID, PAINT_ENTRY_DATE, PAINT_HIST_SIGN, BLASTING, PRIMER, INTERMEDIATE, FINISHING) "
                . "VALUES (:projName, :headmarkToUpdate, :idToUpdate, SYSDATE, :sign,$dataBlast,$dataPrimer,$dataIntermd,$dataFinishing)");
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

        $alertBOX .= "PAINTING $HEAD_MARK UPDATED ## ";
    }
    // END PROSES PAINTING
    // PROSES PAINTING QC
    if ($dataQCPass > 0) {
        // insert PAINT QC
        if ($FabPassQTY == ($ValueQCPASSFrst + $dataQCPass)) {
            # code...
            $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING_QC SET 
                BLASTING_QC = BLASTING_QC+$dataQCPass, BLASTING_QC_DATE = SYSDATE, BLASTING_QC_SIGN = '$username', 
                PRIMER_QC = PRIMER_QC+$dataQCPass, PRIMER_QC_DATE = SYSDATE, PRIMER_QC_SIGN = '$username', 
                INTERMEDIATE_QC = INTERMEDIATE_QC+$dataQCPass, INTERMEDIATE_QC_DATE = SYSDATE, INTERMEDIATE_QC_SIGN = '$username', 
                FINISHING_QC = FINISHING_QC+$dataQCPass, FINISHING_QC_DATE = SYSDATE, FINISHING_QC_SIGN = '$username',
                PAINT_QC_PASS = PAINT_QC_PASS+$dataQCPass, PAINT_QC_PASS_DATE = SYSDATE, PAINT_QC_PASS_SIGN = '$username', PAINT_QC_STATUS = 'PASSED'
              WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            $memo = "QCPASS";
        } else {
            # code...
            $SQLUpdateParse = oci_parse($conn, "UPDATE PAINTING_QC SET 
                BLASTING_QC = BLASTING_QC+$dataQCPass, BLASTING_QC_DATE = SYSDATE, BLASTING_QC_SIGN = '$username', 
                PRIMER_QC = PRIMER_QC+$dataQCPass, PRIMER_QC_DATE = SYSDATE, PRIMER_QC_SIGN = '$username', 
                INTERMEDIATE_QC = INTERMEDIATE_QC+$dataQCPass, INTERMEDIATE_QC_DATE = SYSDATE, INTERMEDIATE_QC_SIGN = '$username', 
                FINISHING_QC = FINISHING_QC+$dataQCPass, FINISHING_QC_DATE = SYSDATE, FINISHING_QC_SIGN = '$username', 
                PAINT_QC_PASS = PAINT_QC_PASS+$dataQCPass, PAINT_QC_PASS_DATE = SYSDATE, PAINT_QC_PASS_SIGN = '$username' 
              WHERE HEAD_MARK = :headmarkToUpdate AND ID = :idToUpdate");
            $memo = "QCNOTPASS";
        }
        // echo "$SQLUpdateParse <br>";
        oci_bind_by_name($SQLUpdateParse, ":headmarkToUpdate", $HEAD_MARK);
        oci_bind_by_name($SQLUpdateParse, ":idToUpdate", $HM_ID);
        $SQLUpdateRes = oci_execute($SQLUpdateParse);
        if ($SQLUpdateRes) {
            oci_commit($conn);
            // echo "<script>alert('QC PASS UPDATED');</script>";
        } else {
            oci_rollback($conn);
            // echo "<script>alert('QC PASS NOT UPDATED');</script>";
        }

        //insert PAINT Qc History
        $updateQcHistSql = "INSERT INTO PAINTING_QC_HIST (PROJECT_NAME, HEAD_MARK, ID, 
                BLASTING_QC, PRIMER_QC, INTERMEDIATE_QC, FINISHING_QC,
                PAINT_QC_HIST_SIGN, PAINT_QC_ENTRY_DATE, MEMO)           
                VALUES (:pn, :hm, :id, '$dataQCPass', '$dataQCPass', '$dataQCPass', '$dataQCPass', '$username', SYSDATE, '$memo')";
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


        $jmlHMPckList = SingleQryFld("SELECT count(*) FROM PREPACKING_LIST WHERE HEAD_MARK = '$HEAD_MARK' AND PROJECT_NAME='$ProjNme'", $conn);
        // echo "JML = $jmlHMPckList<br>";
        //Insert OR Update PACKINGLIST
        if ($jmlHMPckList == 0) {
            # code...
            //INSERT INTO PREPACKING LIST WHEN FINISHED
            $transferToPackingParse = oci_parse($conn, "INSERT INTO PREPACKING_LIST (PROJECT_NAME, HEAD_MARK, UNIT_QTY, ENTRY_DATE, ENTRY_SIGN, PACKING_STATUS) "
                    . "VALUES (:projName, :headMark, :unitQty, SYSDATE, '$username', 'NP')");
            oci_bind_by_name($transferToPackingParse, ":projName", $ProjNme);
            oci_bind_by_name($transferToPackingParse, ":headMark", $HEAD_MARK);
            oci_bind_by_name($transferToPackingParse, ":unitQty", $dataQCPass);

            $transferToPackingRes = oci_execute($transferToPackingParse);

            if ($transferToPackingRes) {
                oci_commit($conn);
                // echo "<script>alert('TRF PACKLIST SUCCESS');</script>";
            } else {
                oci_rollback($conn);
                echo "<script>alert('TRF PACKLIST FAILED');</script>";
            }
        } else {
            # code...
            $updatePckListingSql = "UPDATE PREPACKING_LIST SET UNIT_QTY = UNIT_QTY+$dataQCPass, PACKING_STATUS='PP' ,ENTRY_DATE = SYSDATE ,ENTRY_SIGN = '$username' "
                    . "WHERE PROJECT_NAME = :PROJNAME AND HEAD_MARK = :HEADMARK";
            $updatePckListingParse = oci_parse($conn, $updatePckListingSql);
            oci_bind_by_name($updatePckListingParse, ":PROJNAME", $ProjNme);
            oci_bind_by_name($updatePckListingParse, ":HEADMARK", $HEAD_MARK);
            $updatePckListingRes = oci_execute($updatePckListingParse);

            if ($updatePckListingRes) {
                oci_commit($conn);
                // echo "<script>alert('PACKLIST UPDATED');</script>";
            } else {
                oci_rollback($conn);
                echo "<script>alert('PACKLIST NOT UPDATED');</script>";
            }
        }

        $alertBOX .= "QC PASS $HEAD_MARK UPDATED ## ";
    }
    // END PROSES PAINTING QC                
}
?>
<?php
// exit();
// SHOW IN ROW TABLE
$tableSql = "
    SELECT PROJECT_NAME,
       HEAD_MARK,
       PROFILE,
       ID,
       TOTAL_QTY,
       PNT_QTY as FAB_PASS_QTY,
       SUBCONT_ID,
       BLASTING as BLAST,
       PRIMER as PRIMER,
       INTERMEDIATE as INTMD,
       FINISHING as FINISH,
       PAINT_QC_PASS
    FROM VW_PNT_INFO
    WHERE PROJECT_NAME= '{$ProjNme}' AND HEAD_MARK = '$HEAD_MARK' AND ID = '$HM_ID'";
$tableParse = oci_parse($conn, $tableSql);
oci_execute($tableParse);
$i = $no;
while ($row = oci_fetch_assoc($tableParse)) {
    $availableBlast = $row['FAB_PASS_QTY'] - $row['BLAST'];
    $availablePrimer = $row['BLAST'] - $row['PRIMER'];
    $availableIntermd = $row['PRIMER'] - $row['INTMD'];
    $availableFinishing = $row['INTMD'] - $row['FINISH'];

    $availableQCPass = $row['FINISH'] - $row['PAINT_QC_PASS'];
    ?>
    <td style="text-align:left;" ><font size='2'><b><?php echo $row["HEAD_MARK"] ?></b></font></td>
    <td style="text-align:left;" ><font size='2'><b><?php echo $row["PROFILE"] ?></b></font></td>
    <td ><font size='2' color='#009933' id="<?php echo "HM_ID$i" ?>"><b><?php echo $row["ID"] ?></b></font></td>
    <td ><font size='2' color='#009933'><b><?php echo $row["TOTAL_QTY"] ?></b></font></td>
    <td ><font size='2' color='#0000FF' id="<?php echo "FabPassQTY$i" ?>"><b><?php echo $row["FAB_PASS_QTY"] ?></b></font></td>
    <td >
        <font size='2' ><b>
    <?php echo $row["SUBCONT_ID"] ?>
            </b></font>
    </td>
    <td >
        <input type="hidden" id="<?php echo "maxBLASTFrst$i" ?>" value="<?php echo $availableBlast ?>">
    <?php
    // echo $row['FAB_PASS_QTY']."==".$row['BLAST'];
    if ($row['FAB_PASS_QTY'] === $row['BLAST']) {
        # code...
        ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input  name="<?php echo "dataBlast$i" ?>" id="<?php echo "dataBlast$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableBlast ?>' width='10'>
        <?php
    } else {
        ?>
            <input onchange="PaintChange('<?php echo $i ?>', 'BLAST');
                    ValidateDbleInput('<?php echo $i ?>', 'BLASTING', '<?php echo $row['BLAST'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataBlast$i" ?>" class='form-control' id="<?php echo "dataBlast$i" ?>" type='number' min='0' value="0" max='<?php echo $availableBlast ?>' style="width:70px;">
            <sup id="<?php echo "remBlast" . $i; ?>">[<?php echo $availableBlast ?>]</sup>
            <?php }
        ?>
    </td>
    <td >
        <input type="hidden" id="<?php echo "maxPRIMERFrst$i" ?>" value="<?php echo $availablePrimer ?>">
        <?php
        if ($row['FAB_PASS_QTY'] === $row['PRIMER']) {
            # code...
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input name="<?php echo "dataPrimer$i" ?>" id="<?php echo "dataPrimer$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availablePrimer ?>' width='10'>
            <?php
        } else {
            ?>
            <input onchange="PaintChange('<?php echo $i ?>', 'PRIMER');
                    ValidateDbleInput('<?php echo $i ?>', 'PRIMER', '<?php echo $row['PRIMER'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataPrimer$i" ?>" class='form-control' id="<?php echo "dataPrimer$i" ?>" type='number' min='0' value="0" max='<?php echo $availablePrimer ?>' style="width:70px;">
            <sup id="<?php echo "remPrimer" . $i; ?>">[<?php echo $availablePrimer ?>]</sup>
            <?php }
        ?>
    </td>
    <td >
        <input type="hidden" id="<?php echo "maxINTMDFrst$i" ?>" value="<?php echo $availableIntermd ?>">
        <?php
        if ($row['FAB_PASS_QTY'] === $row['INTMD']) {
            # code...
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input  name="<?php echo "dataIntermd$i" ?>" id="<?php echo "dataIntermd$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableIntermd ?>' width='10'>
        <?php
    } else {
        ?>
            <input onchange="PaintChange('<?php echo $i ?>', 'INTMD');
                    ValidateDbleInput('<?php echo $i ?>', 'INTERMEDIATE', '<?php echo $row['INTMD'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataIntermd$i" ?>" class='form-control' id="<?php echo "dataIntermd$i" ?>" type='number' min='0' value="0" max='<?php echo $availableIntermd ?>' style="width:70px;">
            <sup id="<?php echo "remINTMD" . $i; ?>">[<?php echo $availableIntermd ?>]</sup>
            <?php }
        ?>
    </td>
    <td >
        <input type="hidden" id="<?php echo "maxFNISHFrst$i" ?>" value="<?php echo $availableFinishing ?>">
        <input type="hidden" id="<?php echo "ValueFNISHFrst$i" ?>" value="<?php echo $row['FINISH'] ?>">
    <?php
    if ($row['FAB_PASS_QTY'] === $row['FINISH']) {
        # code...
        ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input name="<?php echo "dataFinishing$i" ?>" id="<?php echo "dataFinishing$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableFinishing ?>' width='10'>
        <?php
    } else {
        ?>
            <input onchange="PaintChange('<?php echo $i ?>', 'FINISH');
                    ValidateDbleInput('<?php echo $i ?>', 'FINISHING', '<?php echo $row['FINISH'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataFinishing$i" ?>" class='form-control' id="<?php echo "dataFinishing$i" ?>" type='number' min='0' value="0" max='<?php echo $availableFinishing ?>' style="width:70px;">
            <sup id="<?php echo "remFinish" . $i; ?>">[<?php echo $availableFinishing ?>]</sup>
        <?php }
    ?>
    </td>
    <td style="background-color: #EA6767;">
        <input type="hidden" id="<?php echo "maxQCPASSFrst$i" ?>" value="<?php echo $availableQCPass ?>">
        <input type="hidden" id="<?php echo "ValueQCPASSFrst$i" ?>" value="<?php echo $row['PAINT_QC_PASS'] ?>">
        <?php
        if ($row['FAB_PASS_QTY'] === $row['PAINT_QC_PASS']) {
            # code...
            ?>
            <img src='../../../../images/fabDone.png' width='20' height='20'>
            <input name="<?php echo "dataQCPass$i" ?>" id="<?php echo "dataQCPass$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableQCPass ?>' width='10'>
            <?php
        } else {
            ?>
            <input onchange="PaintChange('<?php echo $i ?>', 'QCPASS');
                    ValidateDbleInput('<?php echo $i ?>', 'PAINT_QC_PASS', '<?php echo $row['PAINT_QC_PASS'] ?>', '<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataQCPass$i" ?>" class='form-control' id="<?php echo "dataQCPass$i" ?>" type='number' min='0' value="0" max='<?php echo $availableQCPass ?>' style="width:70px;">
            <sup id="<?php echo "remQcPass" . $i; ?>">[<?php echo $availableQCPass ?>]</sup>
            <?php }
        ?>
    </td>
        <?php
        if ($row['PAINT_QC_PASS'] === $row['FAB_PASS_QTY']) {
            echo "<td><input type='button' class='btn btn-success btn-default btn-sm' name='submit' id='submit' value='PAINTDONE' disabled></td>";
        } else {
            ?>
        <!--  -->
        <td>
            <input type='button' onclick="return doSubmit('<?php echo $i ?>', '<?php echo $row['HEAD_MARK'] ?>');" class='btn btn-success btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='DOUBLE CHECK YOUR DATA BEFORE SUBMITTING !!!' name='submit<?php echo $i ?>' id='submit<?php echo $i ?>' value='SUBMIT !'>
        </td>
        <?php
    }
}

echo "<script>showAlertFAB('$alertBOX');</script>";
?>
