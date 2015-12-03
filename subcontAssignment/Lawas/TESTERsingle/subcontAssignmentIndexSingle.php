<?php
// echo "DALAM PERBAIKAN";exit();
require_once '../../dbinfo.inc.php';
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
        <link rel="stylesheet" type="text/css" href="revisionCss/bootstrap-formhelpers.min.css" />
        <!-- BootstrapValidator CSS -->
        <link rel="stylesheet" href="../../css/bootstrapValidator.min.css"/>

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
            function calValidate() {
                $('#reviseableElementsSubcont').show();
            }
            function bootstrapValidatorOpen() {
                // body...
                $('#attributeForm').bootstrapValidator();
            }
        </script>

        <script src="../../jQuery/jquery-1.11.0.min.js"></script>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../../js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/subcontDropdown.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/bootstrap-formhelpers.js"></script>
        <!-- BootstrapValidator JS -->
        <script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>

    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>ADMIN TOOLS</b></font> | Subcont Assignment <font color="#CC0000" size="5"><b>  SINGLE QUANTITY</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="attributeForm" data-bv-message="This value is not valid" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit='return doSubmit();'>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                        <div class="col-sm-10">
                            <?php
                            $sql_project = "SELECT DISTINCT PROJECT_NAME FROM MASTER_DRAWING WHERE DWG_STATUS = 'ACTIVE' ORDER BY PROJECT_NAME";
                            $proj_result = oci_parse($conn, $sql_project);

                            oci_execute($proj_result);

                            echo '<select class="form-control" name="projName" id="projName" onChange="getComponent(this.value);">' . '<br>';
                            echo '<option value=" ">' . "" . '</OPTION>';

                            while ($row = oci_fetch_array($proj_result, OCI_ASSOC)) {
                                $proj = $row['PROJECT_NAME'];
                                echo "<OPTION VALUE='$proj'>$proj</OPTION>";
                            }
                            echo '</select>';
                            ?>
                        </div>
                    </div>

                    <div class="form-group" id="revisionComp"></div>
                    <div class="form-group" id="revisionHeadmark"></div>
                    <div id="reviseableElements"></div>

                    <div class="form-group" id="reviseableElementsSubcont" style="display:none;">
                        <label for="name" class="col-sm-2 control-label">SUBCONTRACTOR</label>
                        <div class="col-sm-10">
                            <?php
                            $subcontSql = "SELECT SUBCONT_ID FROM SUBCONTRACTOR";
                            $subcontParse = oci_parse($conn, $subcontSql);
                            oci_execute($subcontParse);

                            echo '<select class="form-control" name="subcontAssign" id="subcontAssign" data-bv-notempty="true" data-bv-notempty-message="SUBCONTRACTOR is required and cannot be empty">' . '<br>';
                            echo '<option value=" ">' . "" . '</OPTION>';

                            while ($row = oci_fetch_array($subcontParse, OCI_ASSOC)) {
                                $subcont = $row['SUBCONT_ID'];
                                echo "<OPTION VALUE='$subcont'>$subcont</OPTION>";
                            }
                            echo '</select>';
                            ?>
                        </div>

                        <label for="name" class="col-sm-2 control-label">DUE DATE</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">In Days <span class="caret"></span></button>
                                    <!-- <ul class="dropdown-menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul> -->
                                </div><!-- /btn-group -->
                                <input type="number" class="form-control" id="name" name="unitDueDate" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="DUE DATE is required and cannot be empty">
                            </div><!-- /input-group -->
                        </div>

                        <label for="ASSIGNDate" class="col-sm-2 control-label"><font color="black">DATE of DWG DOWN</font></label>
                        <div class="col-sm-10">
                            <div id="ASSIGNDate" data-name="ASSIGNDate" class="bfh-datepicker" data-date="today"></div>
                        </div>
                    </div>


                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-success btn-sm" name="Btnsubmit" >
                                <input type="reset" class="btn btn-default btn-sm">
                                <input type="submit" class="btn btn-danger btn-sm" name="delete" value="delete">
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  
            <?php
            if (isset($_POST['Btnsubmit'])) {

                // echo "Dlam Perbaikan";exit();

                $projName = strval($_POST['projName']);
                $compName = strval($_POST['compSelect']);
                $subcontId = strval($_POST['subcontAssign']);
                $dueDate = intval($_POST['unitDueDate']);
                $ASSIGNDate = strval($_POST['ASSIGNDate']);

                $arr = array();
                $hms = $_POST['hmSelect'];
                if ($hms) {
                    foreach ($hms as $value) {
                        array_push($arr, $value);

                        // PROCESS UPDATING MASTER DRAWING
                        $updateSubcontStatusSql = "UPDATE MASTER_DRAWING SET SUBCONT_STATUS = 'ASSIGNED' WHERE HEAD_MARK = :HEADMARK "
                                . " AND PROJECT_NAME = :PROJNAME AND COMP_TYPE = :COMPONENT AND DWG_STATUS='ACTIVE'";

                        //SELECT HM 
                        $projectSql = "SELECT WEIGHT,SURFACE,TOTAL_QTY,REV FROM MASTER_DRAWING WHERE PROJECT_NAME='$projName' "
                                . " AND HEAD_MARK = '$value' AND COMP_TYPE = '$compName' AND DWG_STATUS='ACTIVE'";
                        $projectParse = oci_parse($conn, $projectSql);
                        oci_execute($projectParse);
                        $row = oci_fetch_array($projectParse);
                        $WEIGHT = $row['WEIGHT'];
                        $SURFACE = $row['SURFACE'];
                        $TOTAL_QTY = $row['TOTAL_QTY'];
                        $REV = $row['REV'];

                        // PROCESS INSERTION TO MASTER DRAWING ASSIGNED
                        $modSql = "INSERT INTO MASTER_DRAWING_ASSIGNED (ASSIGNED_QTY, SUBCONT_ID, REVISION_NO, ASSIGNMENT_DATE, "
                                . "PROJECT_NAME, ID, HEAD_MARK, ASSIGNED_DUE_DATE, SIGNATURE) "
                                . "VALUES ('$TOTAL_QTY',:SUBCONTID, $REV, SYSDATE, :PROJNAME, 1, :HEADMARK, "
                                . "(SYSDATE + :DUEDATE), '$username')";

                        $insertIntoFabSql = "INSERT INTO FABRICATION (HEAD_MARK, ID, ENTRY_DATE, MARKING, CUTTING, ASSEMBLY, WELDING, "
                                . " DRILLING, FINISHING, PROJECT_NAME, FAB_STATUS, UNIT_QTY, SIGN_DATE) "
                                . "VALUES (:HEADMARK, 1, TO_DATE(:DWGDOWN,'MM/DD/YYYY'), 0,0,0,0,0,0, :PROJNAME, 'NOTCOMPLETE', "
                                . "'$TOTAL_QTY', "
                                . "SYSDATE)";

                        $insertIntoFabQcSql = "INSERT INTO FABRICATION_QC (HEAD_MARK, ID, MARKING_QC, CUTTING_QC, ASSEMBLY_QC, "
                                . "WELDING_QC, DRILLING_QC, FINISHING_QC,  PROJECT_NAME, FAB_QC_PASS, UNIT_QTY, FAB_QC_STATUS) "
                                . "VALUES (:HEADMARK, 1, 0,0,0,0,0,0, :PROJNAME, 0, "
                                . "'$TOTAL_QTY', "
                                . ":SUBCONTID, 'NOTPASSED')";

                        $updateSubcontStatusParse = oci_parse($conn, $updateSubcontStatusSql);
                        $modParse = oci_parse($conn, $modSql);
                        $insertIntoFabParse = oci_parse($conn, $insertIntoFabSql);
                        $insertIntoFabQcParse = oci_parse($conn, $insertIntoFabQcSql);

                        oci_bind_by_name($updateSubcontStatusParse, ":HEADMARK", $value);
                        oci_bind_by_name($updateSubcontStatusParse, ":PROJNAME", $projName);
                        oci_bind_by_name($updateSubcontStatusParse, ":COMPONENT", $compName);

                        oci_bind_by_name($modParse, ":SUBCONTID", $subcontId);
                        oci_bind_by_name($modParse, ":HEADMARK", $value);
                        oci_bind_by_name($modParse, ":PROJNAME", $projName);
                        oci_bind_by_name($modParse, ":DUEDATE", $dueDate);

                        oci_bind_by_name($insertIntoFabParse, ":HEADMARK", $value);
                        oci_bind_by_name($insertIntoFabParse, ":DWGDOWN", $ASSIGNDate);
                        oci_bind_by_name($insertIntoFabParse, ":PROJNAME", $projName);

                        oci_bind_by_name($insertIntoFabQcParse, ":HEADMARK", $value);
                        oci_bind_by_name($insertIntoFabQcParse, ":PROJNAME", $projName);

                        $modRes = oci_execute($modParse);
                        $updateSubcontStatusRes = oci_execute($updateSubcontStatusParse);
                        $insertIntoFabRes = oci_execute($insertIntoFabParse);
                        $insertIntoFabQcRes = oci_execute($insertIntoFabQcParse);

                        if ($modRes && $updateSubcontStatusRes && $insertIntoFabRes && $insertIntoFabQcRes) {
                            oci_commit($conn);
                        } else {
                            oci_rollback($conn);
                        }
                    }
                }
                echo "<script>alert('INSERTION');location.href='$_SERVER[PHP_SELF]';</script>";
            }
            ?>
        </div> <!-- panel-default -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('#attributeForm').bootstrapValidator();
            });

            $(document).ready(function () {
                $('input[type="submit"]').attr('disabled', 'disabled');
                $('input[type="text"]').keyup(function () {

                    if ($(this).val() != '') {
                        $('input[type="submit"]').removeAttr('disabled');
                    }
                });
            });
        </script>
    </body>
</html>    
