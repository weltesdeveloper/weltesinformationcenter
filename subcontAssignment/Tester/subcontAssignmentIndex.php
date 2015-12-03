<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | SUBCONT ASSIGNMENT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara SUBCONT ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css" />
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap-formhelpers.min.css" />
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap-select.min.css" />
        <!-- BootstrapValidator CSS -->
        <link rel="stylesheet" href="../../css/bootstrapValidator.min.css"/>
        <!-- Prime UI CSS Picklist -->
        <!-- <link rel="stylesheet" type="text/css" href="Plugin/primeui/primeui-1.1-min.css">
        <link rel="stylesheet" type="text/css" href="Plugin/primeui/demo.css" />
        <link rel="stylesheet" href="Plugin/primeui/jquery-ui.css" />
        <link rel="stylesheet" href="Plugin/primeui/theme.css" /> -->
        <!-- DATA TABLE CSS -->
        <link href="../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <!-- Autocomplete CSS Suggest Box -->
        <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css">



        <script src="../../jQuery/jquery-1.11.1.min.js"></script>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->         
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/bootstrap-formhelpers.js"></script>
        <script src="../../js/bootstrap-select.js"></script>
        <!-- BootstrapValidator JS -->
        <script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
        <!-- prime UI JS Picklist-->
        <!-- // <script type="text/javascript" src="Plugin/primeui/jquery.js"></script> 
        <script type="text/javascript" src="Plugin/primeui/jquery-ui.js"></script>
        <script type="text/javascript" src="Plugin/primeui/js/inputtext/inputtext.js"></script>
        <script type="text/javascript" src="Plugin/primeui/js/button/button.js"></script>
        <script type="text/javascript" src="Plugin/primeui/js/picklist/picklist.js"></script>--> 
        <!-- DATA TABLES SCRIPT -->
        <script src="../../js/jquery.dataTables.min.js" type="text/javascript"></script>   
        <!-- AUTocomplete JS SUggest box -->
        <script type="text/javascript" src="../../js/jquery-ui.js"></script>

    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>ADMIN TOOLS</b></font> | Subcont Assignment <font color="green" size="5"><b> SINGLE &amp; MULTIPLE QUANTITY</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="attributeForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit='return doSubmit()' data-bv-message="This value is not valid">

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="projName" id="projName" data-live-search="true">
                                <option value="" selected disabled>[select project]</option>
                                <?php
                                $sql = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO";
                                $parse = oci_parse($conn, $sql);
                                oci_execute($parse);
                                while ($row1 = oci_fetch_array($parse)) {
                                    $projNo = $row1['PROJECT_NO'];
                                    ?>
                                    <optgroup label="<?php echo $projNo; ?>">
                                        <?php
                                        $projectNameSql = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' AND PROJECT_NO = '$projNo' ORDER BY PROJECT_NAME_NEW";
                                        $projectNameParse = oci_parse($conn, $projectNameSql);
                                        oci_execute($projectNameParse);
                                        while ($row = oci_fetch_array($projectNameParse)) {
                                            $proj = $row['PROJECT_NAME_NEW'];
                                            $projNmLma = $row['PROJECT_NAME_OLD'];

                                            echo "<OPTION VALUE='$projNmLma'>$proj</OPTION>";
                                        }
                                        ?>
                                    </optgroup>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="form-group" id="revisionComp"></div> -->
                    <div class="form-group" id="revisionHeadmark"></div>
                    <div id="ValInput" style="display:none;">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 control-label">SUBCONTRACTOR</label>
                            <div class="col-sm-10">
                                <?php
                                $subcontSql = "SELECT SUBCONT_ID FROM SUBCONTRACTOR ORDER BY SUBCONT_ID";
                                $subcontParse = oci_parse($conn, $subcontSql);
                                oci_execute($subcontParse);

                                echo '<select class="form-control" name="subcontAssign" id="subcontAssign" data-bv-notempty="true" data-bv-notempty-message="SUBCONTRACTOR is required and cannot be empty">' . '<br>';
                                echo '<option value="">' . "" . '</OPTION>';

                                while ($row = oci_fetch_array($subcontParse, OCI_ASSOC)) {
                                    $subcont = $row['SUBCONT_ID'];
                                    echo "<OPTION VALUE='$subcont'>$subcont</OPTION>";
                                }
                                echo '</select>';
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 control-label">SPV. FABR.</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="spvFab" name="spvFab" maxlength="25" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="SPV. FABR. is required and cannot be empty">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 control-label">QC. INSPECTOR</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="QCInsp" name="QCInsp" maxlength="25" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="QC. INSPECTOR is required and cannot be empty">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ASSIGNDate" class="col-sm-2 control-label"><font color="black">DATE of DWG DOWN</font></label>
                            <div class="col-sm-10">
                                <div id="ASSIGNDate" data-name="ASSIGNDate" class="bfh-datepicker" data-date="today"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="DUEDate" class="col-sm-2 control-label">DUE DATE</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">In Days <span class="caret"></span></button>
                                    </div><!-- /btn-group -->
                                    <input type="number" min="0" class="form-control" id="unitDueDate" name="unitDueDate" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="DUE DATE is required and cannot be empty">
                                </div><!-- /input-group -->
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="form-control" readonly="" value="" id="DUEDate" name="DUEDate">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 control-label">LABEL PROFILE for SCURVE</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="scurve_profile" name="scurve_profile" maxlength="50" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="PROFILE SCURVE is required and cannot be empty">
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <a href="#" id="prn_dwg" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Print Dwg Assg</a>
                                <input type="submit" class="btn btn-success btn-sm" name="submitBTN" >
                                <input type="reset" class="btn btn-default btn-sm" onclick="location.href = '<?php echo $_SERVER['PHP_SELF'] ?>'">
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="PrintDWG">                          
                    </div>
                </div>
            </div>

            <?php
            if (isset($_POST['submitBTN'])) {
                // echo "Dalam Perbaikan";exit();

                $projName = strval($_POST['projName']);
                $subcontId = strval($_POST['subcontAssign']);
                $dueDate = strval($_POST['DUEDate']) . " " . date("H:i:s");
                $spvFab = strval($_POST['spvFab']);
                $QCInsp = strval($_POST['QCInsp']);
                $ASSIGNDate = strval($_POST['ASSIGNDate']) . " " . date("H:i:s");

                $jmlSelectHM = intval($_POST['totTRGET']);

                for ($i = 0; $i <= $jmlSelectHM; $i++) {
                    # code...
                    if (isset($_POST["HM$i"])) {
                        $headMarkSelect = $_POST["HM$i"];
                        $updateQty = intval($_POST['AsignQty' . $i]);
                        $ActQty = intval($_POST['ActQty' . $i]);

                        // echo "$headMarkSelect --assign = $updateQty -- total = $ActQty<br>";
                        // PROCESS UPDATING MASTER DRAWING
                        $updateSubcontStatusSql = "UPDATE MASTER_DRAWING SET DISTRIBUTION_COUNT = DISTRIBUTION_COUNT + 1 WHERE HEAD_MARK = :HEADMARK AND PROJECT_NAME = :PROJNAME AND DWG_STATUS='ACTIVE'";
                        $updateSubcontStatusParse = oci_parse($conn, $updateSubcontStatusSql);

                        oci_bind_by_name($updateSubcontStatusParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($updateSubcontStatusParse, ":PROJNAME", $projName);

                        $updateSubcontStatusRes = oci_execute($updateSubcontStatusParse);

                        if ($updateSubcontStatusRes) {
                            oci_commit($conn);
                        } else {
                            oci_rollback($conn);
                        }

                        //SELECT HM 
                        $projectSql = "SELECT WEIGHT,SURFACE,DISTRIBUTION_COUNT,REV,COMP_TYPE FROM MASTER_DRAWING WHERE PROJECT_NAME='$projName' AND HEAD_MARK = '$headMarkSelect' AND DWG_STATUS='ACTIVE'";
                        $projectParse = oci_parse($conn, $projectSql);
                        oci_execute($projectParse);
                        $row = oci_fetch_array($projectParse);
                        $WEIGHT = $row['WEIGHT'];
                        $SURFACE = $row['SURFACE'];
                        $DISTRIB_CNT = $row['DISTRIBUTION_COUNT'];
                        $REV = $row['REV'];
                        $compName = $row['COMP_TYPE'];

                        // PROCESS INSERTION
                        $modSql = "INSERT INTO MASTER_DRAWING_ASSIGNED (ASSIGNED_QTY, SUBCONT_ID, REVISION_NO, ASSIGNMENT_DATE, "
                                . "PROJECT_NAME, ID, HEAD_MARK, ASSIGNED_DUE_DATE, SIGNATURE, SPV_FAB, QC_INSP) "
                                . "VALUES (:UPDATEQTY, "
                                . ":SUBCONTID, '$REV', TO_DATE('$ASSIGNDate','MM/DD/YYYY hh24:mi:ss'), :PROJNAME, '$DISTRIB_CNT', "
                                . ":HEADMARK, TO_DATE('$dueDate','MM/DD/YYYY hh24:mi:ss'), '$username', '$spvFab', '$QCInsp')";

                        $insertIntoFabSql = "INSERT INTO FABRICATION (HEAD_MARK, ID, ENTRY_DATE, MARKING, CUTTING, ASSEMBLY, WELDING, "
                                . " DRILLING, FINISHING, PROJECT_NAME, FAB_STATUS, UNIT_QTY, SIGN_DATE) "
                                . "VALUES (:HEADMARK, '$DISTRIB_CNT', "
                                . " TO_DATE('$ASSIGNDate','MM/DD/YYYY hh24:mi:ss'), 0,0,0,0,0,0, :PROJNAME, 'NOTCOMPLETE', "
                                . ":UPDATEQTY, SYSDATE)";

                        $insertIntoFabQcSql = "INSERT INTO FABRICATION_QC (HEAD_MARK, ID, MARKING_QC, CUTTING_QC, ASSEMBLY_QC, WELDING_QC, "
                                . " DRILLING_QC, FINISHING_QC, PROJECT_NAME, FAB_QC_PASS, UNIT_QTY, FAB_QC_STATUS) "
                                . "VALUES (:HEADMARK, '$DISTRIB_CNT', "
                                . "0,0,0,0,0,0, :PROJNAME, 0, "
                                . ":UPDATEQTY, 'NOTPASSED')";

                        $modParse = oci_parse($conn, $modSql);
                        $insertIntoFabParse = oci_parse($conn, $insertIntoFabSql);
                        $insertIntoFabQcParse = oci_parse($conn, $insertIntoFabQcSql);

                        oci_bind_by_name($modParse, ":UPDATEQTY", $updateQty);
                        oci_bind_by_name($modParse, ":SUBCONTID", $subcontId);
                        oci_bind_by_name($modParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($modParse, ":PROJNAME", $projName);

                        oci_bind_by_name($insertIntoFabParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($insertIntoFabParse, ":PROJNAME", $projName);
                        oci_bind_by_name($insertIntoFabParse, ":UPDATEQTY", $updateQty);

                        oci_bind_by_name($insertIntoFabQcParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($insertIntoFabQcParse, ":PROJNAME", $projName);
                        oci_bind_by_name($insertIntoFabQcParse, ":UPDATEQTY", $updateQty);

                        $modRes = oci_execute($modParse);
                        $insertIntoFabRes = oci_execute($insertIntoFabParse);
                        $insertIntoFabQcRes = oci_execute($insertIntoFabQcParse);


                        if ($modRes && $insertIntoFabRes && $insertIntoFabQcRes) {
                            oci_commit($conn);
                        } else {
                            oci_rollback($conn);
                        }

                        // Update Status MD
                        if ($ActQty == $updateQty) {
                            // echo "ASSIGNED FULL<br>";
                            $updateStatusMdSql = "UPDATE MASTER_DRAWING SET SUBCONT_STATUS = 'ASSIGNED' "
                                    . "WHERE HEAD_MARK = :HEADMARK AND PROJECT_NAME = :PROJNAME";

                            $updateStatusMdParse = oci_parse($conn, $updateStatusMdSql);

                            oci_bind_by_name($updateStatusMdParse, ":HEADMARK", $headMarkSelect);
                            oci_bind_by_name($updateStatusMdParse, ":PROJNAME", $projName);

                            $updateStatusMdRes = oci_execute($updateStatusMdParse);

                            if ($updateStatusMdRes) {
                                oci_commit($conn);
                            } else {
                                oci_rollback($conn);
                            }
                        } else {
                            // echo "ASSIGNED SEPARO<br>";    
                        }
                    }
                }
                if ($jmlSelectHM > 0) {
                    # code...
                    echo "<script>alert('INSERTION');location.href='$_SERVER[PHP_SELF]';</script>";
                }
            }
            ?>
        </div>

        <?php
        $suggestLabelScurve = "";
        $projectSql = "SELECT DISTINCT(S_CURV_LBL) AS S_CURV_LBL FROM MASTER_DRAWING_SCURVE ORDER BY S_CURV_LBL";
        $projectParse = oci_parse($conn, $projectSql);
        oci_execute($projectParse);

        while ($row = oci_fetch_array($projectParse)) {
            $suggestLabelScurve .= '"' . $row['S_CURV_LBL'] . '",';
        }
        if ($suggestLabelScurve <> "") {
            $suggestLabelScurve = substr_replace($suggestLabelScurve, "", -1);
        }
        ?>
        <!-- panel-default -->
        <script type="text/javascript">
            function doSubmit() {
                if (confirm('Are you sure you want to submit Subcont Assignment Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }
            function ValTextShow(jmlTrget) {
                // var oTable      = $('#listHM2').dataTable();
                // var counter     = oTable.fnGetData().length;
                if (jmlTrget == 0) {
                    $("#ValInput").hide();
                    $('input[type="submit"]').attr('disabled', 'disabled');
                } else {
                    $("#ValInput").show();
                    $('input[type="submit"]').removeAttr('disabled');
                }
            }
        </script>

        <!-- JS ON LOAD -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#attributeForm").bootstrapValidator();
                $('#projName').selectpicker();

                $('input[type="submit"]').attr('disabled', 'disabled');
                $('input[type="text"]').keyup(function () {
                    if ($(this).val() != '') {
                        $('input[type="submit"]').removeAttr('disabled');
                    }
                });
                $("#projName").change(
                        function () {
                            ValTextShow('0');
                            $("#revisionHeadmark").html('<div class="col-sm-2">&nbsp;</div><div class="col-sm-10"><h3><b>Please Wait</b></h3></div>');
                            $.get('findHeadmark.php', {
                                projName: $("#projName").val()
                            },
                            function (res) {
                                $("#revisionHeadmark").html(res);
                            }
                            );
                        }
                );

                $('#prn_dwg').on('click',
                        function () {
                            $('#PrintDWG').load("showDWG_PRINT.php");
                        }
                );

                // for SCURVE
                var availableLabel = [<?php echo $suggestLabelScurve ?>];
                $('#scurve_profile').on('keyup', function () {
                    $(this).val($(this).val().toUpperCase());
                }).autocomplete({
                    source: availableLabel
                });
            });

            // Add DATE
            $(document).ready(function () {

                function DateFromString(str, addys) {
                    var addys = parseInt(addys);
                    str = str.split(/\D+/);
                    // alert(str+" + "+addys);
                    str = new Date(str[2], str[0] - 1, (parseInt(str[1]) + addys));
                    // alert(str);
                    return MMDDYYYY(str);
                }

                function MMDDYYYY(str) {
                    var ndateArr = str.toString().split(' ');
                    var Months = 'Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec';
                    return (parseInt(Months.indexOf(ndateArr[1]) / 4) + 1) + '/' + ndateArr[2] + '/' + ndateArr[3];
                }

                function AddDays() {
                    var date = $('input[name="ASSIGNDate"]').val();
                    var date_add = $('#unitDueDate').val();
                    var add_Non_sun = 0;
                    var fnal_add_day = parseInt(date_add) + parseInt(add_Non_sun);
                    // alert(date);
                    var ndate = DateFromString(date, fnal_add_day);
                    return ndate;
                }

                function addDateNonSunday() {
                    // body...
                    var date_frst = $('input[name="ASSIGNDate"]').val();
                    var date_add = $('#unitDueDate').val();
                    var add = 0;

                    if (date_add == 0) {
                        var ndate = date_frst;
                    } else {
                        for (var i = 1; i <= date_add; i++) {
                            var ndate = DateFromString(date_frst, i);
                            var d = new Date(ndate);
                            var n = d.getDay();
                            // alert(n);
                            if (n == 0) {
                                add = 1;
                                date_frst = DateFromString(date_frst, add);
                                ndate = DateFromString(date_frst, i);
                            }
                        }
                    }

                    return ndate;
                }

                function chngeDt() {
                    $('#DUEDate').val(addDateNonSunday());
                }
                $('#unitDueDate').on('change focus',
                        function () {
                            chngeDt();
                        });
                $('.bfh-datepicker-calendar').on('click',
                        function () {
                            // $('#unitDueDate').val('0');
                            setTimeout(function () {
                                $('#unitDueDate').focus()
                            }, 500);
                        }
                );
            });
        </script>
    </body>
</html>    
