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
<style type="text/css">
    table td{
        text-align:center;
    }
    .delay_notif{
        background-color: #F30001;
    }
    .morphbutton-content {
        background-color: #4878A0;
        color: #fff;
    }
</style>
<script type="text/javascript">
    function FabChange(id, type) {
        var maxMARKFrst = parseInt($('#maxMARKFrst' + id).attr("value"));
        var maxCUTFrst = parseInt($('#maxCUTFrst' + id).attr("value"));
        var maxASSYFrst = parseInt($('#maxASSYFrst' + id).attr("value"));
        var maxWELDFrst = parseInt($('#maxWELDFrst' + id).attr("value"));
        var maxDRILLFrst = parseInt($('#maxDRILLFrst' + id).attr("value"));
        var maxFNISHFrst = parseInt($('#maxFNISHFrst' + id).attr("value"));
        var maxQCPASSFrst = parseInt($('#maxQCPASSFrst' + id).attr("value"));

        // data Value
        var dataMarking = $('#dataMarking' + id);
        var dataCutting = $('#dataCutting' + id);
        var dataAssembly = $('#dataAssembly' + id);
        var dataWelding = $('#dataWelding' + id);
        var dataDrilling = $('#dataDrilling' + id);
        var dataFinishing = $('#dataFinishing' + id);
        var dataQCPass = $('#dataQCPass' + id);

        if (type == "MARK") {
            var markVlue = parseInt(dataMarking.val());

            if (isNaN(markVlue) || markVlue > dataMarking.attr("max")) {
                // alert("Bukan NO");

                dataMarking.val('0');
                markVlue = 0;
            }
            // else{
            $('#remMark' + id).text(parseInt(maxMARKFrst - markVlue));

            dataCutting.attr('max', markVlue + maxCUTFrst);
            $('#remCut' + id).text(parseInt(dataCutting.attr("max")));
            dataCutting.val("0");

            dataAssembly.attr("max", maxASSYFrst);
            $('#remAssy' + id).text(parseInt(dataAssembly.attr("max")));
            dataAssembly.val("0");

            dataWelding.attr("max", maxWELDFrst);
            $('#remWeld' + id).text(parseInt(dataWelding.attr("max")));
            dataWelding.val("0");

            dataDrilling.attr("max", maxDRILLFrst);
            $('#remDrill' + id).text(parseInt(dataDrilling.attr("max")));
            dataDrilling.val("0");

            dataFinishing.attr("max", maxFNISHFrst);
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max")));
            dataFinishing.val("0");

            dataQCPass.attr("max", maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
            // }

        }

        if (type == "CUT") {
            var Vlue = parseInt(dataCutting.val());

            if (isNaN(Vlue) || Vlue > dataCutting.attr("max")) {
                // alert("Bukan NO");

                dataCutting.val('0');
                Vlue = 0;
            }
            // else{
            $('#remCut' + id).text(parseInt(dataCutting.attr("max") - Vlue));

            dataAssembly.attr("max", Vlue + maxASSYFrst);
            $('#remAssy' + id).text(parseInt(dataAssembly.attr("max")));
            dataAssembly.val("0");

            dataWelding.attr("max", maxWELDFrst);
            $('#remWeld' + id).text(parseInt(dataWelding.attr("max")));
            dataWelding.val("0");

            dataDrilling.attr("max", maxDRILLFrst);
            $('#remDrill' + id).text(parseInt(dataDrilling.attr("max")));
            dataDrilling.val("0");

            dataFinishing.attr("max", maxFNISHFrst);
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max")));
            dataFinishing.val("0");

            dataQCPass.attr("max", maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
            // }

        }

        if (type == "ASSY") {
            var Vlue = parseInt(dataAssembly.val());

            if (isNaN(Vlue) || Vlue > dataAssembly.attr("max")) {
                // alert("Bukan NO");

                dataAssembly.val('0');
                Vlue = 0;
            }
            // else{
            $('#remAssy' + id).text(parseInt(dataAssembly.attr("max") - Vlue));

            dataWelding.attr("max", Vlue + maxWELDFrst);
            $('#remWeld' + id).text(parseInt(dataWelding.attr("max")));
            dataWelding.val("0");

            dataDrilling.attr("max", maxDRILLFrst);
            $('#remDrill' + id).text(parseInt(dataDrilling.attr("max")));
            dataDrilling.val("0");

            dataFinishing.attr("max", maxFNISHFrst);
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max")));
            dataFinishing.val("0");

            dataQCPass.attr("max", maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
            // }

        }

        if (type == "WELD") {
            var Vlue = parseInt(dataWelding.val());

            if (isNaN(Vlue) || Vlue > dataWelding.attr("max")) {
                // alert("Bukan NO");

                dataWelding.val('0');
                Vlue = 0;
            }
            // else{
            $('#remWeld' + id).text(parseInt(dataWelding.attr("max") - Vlue));

            dataDrilling.attr("max", Vlue + maxDRILLFrst);
            $('#remDrill' + id).text(parseInt(dataDrilling.attr("max")));
            dataDrilling.val("0");

            dataFinishing.attr("max", maxFNISHFrst);
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max")));
            dataFinishing.val("0");

            dataQCPass.attr("max", maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
            // }

        }

        if (type == "DRILL") {
            var Vlue = parseInt(dataDrilling.val());
            if (isNaN(Vlue) || Vlue > dataDrilling.attr("max")) {
                // alert("Bukan NO");

                dataDrilling.val('0');
                Vlue = 0;
            }
            // else{
            $('#remDrill' + id).text(parseInt(dataDrilling.attr("max") - Vlue));

            dataFinishing.attr("max", Vlue + maxFNISHFrst);
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max")));
            dataFinishing.val("0");

            dataQCPass.attr("max", maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
            // }

        }

        if (type == "FINISH") {
            var Vlue = parseInt(dataFinishing.val());
            if (isNaN(Vlue) || Vlue > dataFinishing.attr("max")) {
                // alert("Bukan NO");
                dataFinishing.val('0');
                Vlue = 0;
            }
            $('#remFinish' + id).text(parseInt(dataFinishing.attr("max") - Vlue));

            dataQCPass.attr("max", Vlue + maxQCPASSFrst);
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max")));
            dataQCPass.val("0");
        }

        if (type == "QCPASS") {
            var Vlue = parseInt(dataQCPass.val());
            if (isNaN(Vlue) || Vlue > dataQCPass.attr("max")) {
                // alert("Bukan NO");
                dataQCPass.val('0');
                Vlue = 0;
            }
            $('#remQcPass' + id).text(parseInt(dataQCPass.attr("max") - Vlue));
        }
    }
</script>

<?php

function jumlahREMS($HM, $ID, $PROC_SUB_TYPE, $conn) {
    $sql_jml = "SELECT COUNT(*) FROM MD_PROC_DELAY_REMS WHERE REGEXP_REPLACE (HEAD_MARK, '[[:space:]]+', '') = '" . str_replace(' ', '', $HM) . "' AND ID='$ID' AND PROC_SUB_TYPE='$PROC_SUB_TYPE' AND ENTRY_DATE = TO_DATE('" . date("m/d/Y") . "','MM/DD/YYYY')";
    $jml_now_rems = SingleQryFld($sql_jml, $conn);

    return $jml_now_rems;
}

// IF SHOW KEY HAS BEEN PRESSED
if ($_POST['action'] == 'show') {
    $project_name = $_POST["ProjNme"];
    ?>


    <table class="display table-bordered table-condensed" cellspacing="0" cellpadding="0" id="fabTabel" style="width:100%;" >
        <thead>
            <tr>
                <th style="text-align:center;">
                    Head Mark
                </th>
                <th style="text-align:center;">
                    COMP TYPE
                </th>
                <th style="text-align:center;">
                    Profile
                </th>
                <th style="text-align:center;width:50px;">
                    Assg<br>ID
                </th>
                <th style="text-align:center;width:70px;">
                    Assg<br>Date
                </th>
                <th style="text-align:center;background-color:#EEEEEE;">
                    Tot<br>Qty
                </th>
                <th style="text-align:center;background-color:#EEEEEE;">
                    Assg<br>Qty
                </th>
                <th style="text-align:center;background-color:#EEEEEE;">
                    Weight<br>(KG)
                </th>
                <th style="text-align:center;">
                    Subcont
                </th>
                <th style="text-align:center;width:110px;">
                    Marking<br>(2%)
                </th>
                <th style="text-align:center;width:110px;">
                    Cutting<br>(3%)
                </th>
                <th style="text-align:center;width:110px;">
                    <?php
                    if ($project_name == "SGRSCLAYCRUSHER" || $project_name == "PACKER" || $project_name == "SGRSMAINBAGFILTER") {
                        echo 'Drilling';
                    } else {
                        echo "Assembly";
                    }
                    ?>
                    <br>(25%)
                </th>
                <th style="text-align:center;width:110px;">
                    <?php
                    if ($project_name == "SGRSCLAYCRUSHER" || $project_name == "PACKER" || $project_name == "SGRSMAINBAGFILTER") {
                        echo 'Assembly';
                    } else {
                        echo "Welding";
                    }
                    ?>
                    <br>(30%)
                </th>
                <th style="text-align:center;width:110px;">
                    <?php
                    if ($project_name == "SGRSCLAYCRUSHER" || $project_name == "PACKER" || $project_name == "SGRSMAINBAGFILTER") {
                        echo 'Welding';
                    } else {
                        echo "Drilling";
                    }
                    ?>
                    <br>(15%)
                </th>
                <th style="text-align:center;width:110px;">
                    Finishing<br>(25%)
                </th>
                <th style="text-align:center;width:110px;background-color: #5BC0DE;">
                    QC Pass
                </th>
                <th style="text-align:center;width:90px;">
                    Process Date
                </th>
                <th style="text-align:center;width:70px;">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            // AND DTL_FABR.ASSG_QTY != DTL_FABR.FINISH 
            $tableSql = "SELECT * FROM VW_FAB_UPDATE WHERE PROJECT_NAME= '{$_POST["ProjNme"]}' ORDER BY ASSG_DATE,HEAD_MARK,ID";
            // echo "$tableSql";
            $tableParse = oci_parse($conn, $tableSql);
            oci_execute($tableParse);
            $i = 0;
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

                <tr id="<?php echo "baris$i" ?>">
                    <td style="text-align:left;" ><font size='2'><b><?php echo $row["HEAD_MARK"] ?></b></font></td>
                    <td style="text-align:left;" ><font size='2'><b><?php echo $row["COMP_TYPE"] ?></b></font></td>
                    <td style="text-align:left;" ><font size='2'><b><?php echo $row["PROFILE"] ?></b></font></td>
                    <td ><font size='2' id="<?php echo "HM_ID$i" ?>"><b><?php echo $row["ID"] ?></b></font></td>
                    <td ><font size='2' id="<?php echo "AssgDate$i" ?>"><b><?php echo $row["ASSG_DATE"] ?></b></font></td>
                    <td style="background-color:#EEEEEE;"><font size='2' color='#009933'><b><?php echo $row["TOTAL_QTY"] ?></b></font></td>
                    <td style="background-color:#EEEEEE;"><font size='2' color='#0000FF' id="<?php echo "AssgQTY$i" ?>"><b><?php echo $row["ASSG_QTY"] ?></b></font></td>
                    <td style="background-color:#EEEEEE;"><font size='2' color='#0000FF'><b><?php echo $row["WEIGHT"] ?></b></font></td>
                    <td >
                        <font size='2' ><b>
                                <?php echo $row["SUBCONT_ID"] ?>
                            </b></font>
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
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
    <div id="ValidateDBLE"></div>
    <div id="div_rems" ></div>
    <select id="fab_sel_rems_utma" class="hide">
        <?php
        $sql = "SELECT * FROM MST_REMAKS WHERE REMS_TYPE = 'FAB' ORDER BY REMS_DESC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            echo "<option>" . $row['REMS_DESC'] . "</option>";
        }
        ?>
    </select>

    <?php
}//===> END OF 'SHOW'
?>
<script type="text/javascript">
    function doSubmit(id, HEAD_MARK) {
        if (confirm('Are you sure you want to submit Fabrication ' + HEAD_MARK + ' Data?')) {
            // data Value
            var HM_ID = $('#HM_ID' + id);
            var AssgQTY = $('#AssgQTY' + id);
            var dataMarking = $('#dataMarking' + id);
            var dataCutting = $('#dataCutting' + id);
            var dataAssembly = $('#dataAssembly' + id);
            var dataWelding = $('#dataWelding' + id);
            var dataDrilling = $('#dataDrilling' + id);
            var dataFinishing = $('#dataFinishing' + id);
            var ValueFNISHFrst = $('#ValueFNISHFrst' + id);
            var dataQCPass = $('#dataQCPass' + id);
            var ValueQCPASSFrst = $('#ValueQCPASSFrst' + id);
            var proc_date = $('#proc_date' + id);
            // remove div element
            $('div[id ^= div_rems_hist' + id + ']').remove();

            var sentData = {
                submit: "show",
                HM_ID: HM_ID.text(),
                AssgQTY: AssgQTY.text(),
                no: id,
                HEAD_MARK: HEAD_MARK,
                dataMarking: dataMarking.val(),
                dataCutting: dataCutting.val(),
                dataAssembly: dataAssembly.val(),
                dataWelding: dataWelding.val(),
                dataDrilling: dataDrilling.val(),
                dataFinishing: dataFinishing.val(),
                ValueFNISHFrst: ValueFNISHFrst.val(),
                dataQCPass: dataQCPass.val(),
                ValueQCPASSFrst: ValueQCPASSFrst.val(),
                proc_date: proc_date.val(),
                ProjNme: $('#ProjNme').val()
            };
            console.log(sentData);
            $.ajax({
                type: 'POST',
                url: 'processExceededQty.php',
                data: sentData,
                success: function (response, textStatus, jqXHR) {
                    $('#baris' + id).html(response);
                }
            });

            return true;
        } else {
            // Do nothing!
            // alert("NO");
            return false
        }
    }
    function showDoubleInput(id, HEAD_MARK) {
        var HM_ID = $('#HM_ID' + id);

        $.post('processExceededQty.php',
                {
                    HM_ID: HM_ID.text(),
                    no: id,
                    HEAD_MARK: HEAD_MARK,
                    ProjNme: $('#ProjNme').val()
                },
                function (res) {
                    $('#baris' + id).html(res);
                }
        );
    }
    function ValidateDbleInput(id, type, firstQTY, HEAD_MARK) {
        // body...
        var HM_ID = $('#HM_ID' + id);

        $.post("ValidateDoubleInput.php", {
            ProjNme: $('#ProjNme').val(),
            no: id,
            firstQTY: firstQTY,
            HEAD_MARK: HEAD_MARK,
            HM_ID: HM_ID.text(),
            type: type
        },
                function (res) {
                    $('#ValidateDBLE').html(res);
                }
        );
    }
    function showRemaks(indx_row, indx_col, title) {
        var sentData = {
            action: 'show_insert_rems',
            indx_row: indx_row,
            indx_col: indx_col,
            title: title,
            HM: $('#baris' + indx_row).find('td:eq(0)').text().trim(),
            HM_ID: $('#HM_ID' + indx_row).text().trim()
        };

        $.ajax({
            type: 'POST',
            url: 'update_remaks_hist.php',
            data: sentData,
            success: function (response, textStatus, jqXHR) {
                $('#div_rems_hist_conten' + indx_row + '_' + indx_col).html(response);
            }
        });
    }



    $(document).ready(function () {

        $('#fabTabel').DataTable(
                {
                    "paging": true,
//          "lengthChange": false,
                    "displayLength": 25,
                    "filter": true,
//          "bSort": false,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "fnDrawCallback": function () {
                        $('tr[id^=baris]').each(function () {
                            var index = $(this).attr('id').replace('baris', '');
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

                            // datepick
                            $('#proc_date' + index).datepick({
                                dateFormat: 'mm/dd/yyyy',
                                renderer: $.extend({}, $.datepick.defaultRenderer,
                                        {picker: $.datepick.defaultRenderer.picker.
                                                    replace(/\{link:clear\}/, '')})
                            });
                        });
                    }
                }
        );


    });
</script>