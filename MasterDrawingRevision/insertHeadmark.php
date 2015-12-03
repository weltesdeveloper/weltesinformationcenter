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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | INSERT NEW HEADMARK/ASSEMBLY</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara DELIVERY ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <!-- <link rel="stylesheet" type="text/css" href="../css/bootstrap-formhelpers.min.css" /> -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <!-- <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" /> -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css" />
        <!-- Autocomplete CSS Suggest Box -->
        <link rel="stylesheet" type="text/css" href="revisionCss/jquery-ui.css">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript">
            function doSubmit() {
                if (confirm('Are you sure you want to submit NEW ASSEMBLY/HEADMARK Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }
        </script>

        <script src="../jQuery/jquery-1.11.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <!-- // <script language="javascript" type="text/javascript"  src="revisionJs/delivDropdown.js"></script> -->
        <!-- // <script language="javascript" type="text/javascript"  src="revisionJs/bootstrap-formhelpers.js"></script> -->
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-select.min.js"></script>
        <!-- AUTocomplete JS SUggest box -->
        <script type="text/javascript" src="revisionJs/jquery-ui.js"></script>

    </head>
    <body>
        <!-- <div class="ui-widget">
          <label for="tags">Tags: </label>
          <input id="tags">
        </div> -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>INSERT NEW ~</b></font><font color="#CC0000" size="5"><b> HEADMARK/ASSEMBLY RECORD</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" id="frmHM" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit='return doSubmit()'>
                    <div class="form-group">                 
                        <label for="projectName" class="col-sm-2 control-label">PROJECT NAME/BUILDING</label>
                        <div class="col-sm-10">
                            <?php
                            $projectSql = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO,PROJECT_NAME_NEW";
                            $projectParse = oci_parse($conn, $projectSql);

                            oci_execute($projectParse);

                            echo '<select class="form-control" name="projectName" id="projectName" data-live-search="true">' . '<br>';
                            echo '<option value="" selected="" disabled="">' . "[select building]" . '</OPTION>';

                            while ($row = oci_fetch_array($projectParse)) {
                                $project = $row['PROJECT_NAME_OLD'];
                                if ($project == $_GET['PROJNAME']) {
                                    # code...
                                    echo "<OPTION VALUE='$project' selected>" . $row['PROJECT_NO'] . " - " . $row['PROJECT_NAME_NEW'] . "</OPTION>";
                                } else {
                                    echo "<OPTION VALUE='$project'>" . $row['PROJECT_NO'] . " - " . $row['PROJECT_NAME_NEW'] . "</OPTION>";
                                }
                            }
                            echo '</select>';
                            ?>
                        </div>
                        <label for="headmark" class="col-sm-2 control-label"><font color="blue">HEADMARK/ASSEMBLY</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="headmark" name="headmark" placeholder="ex. SMS-PH-BM1" value="" maxlength="50"></input>
                        </div>
                        <label for="compType" class="col-sm-2 control-label"><font color="blue">COMPONENT TYPE</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="compType" name="compType" placeholder="" value=""></input>
                        </div>
                        <label for="compType" class="col-sm-2 control-label"><font color="blue">COMPONENT TYPE LVL 2</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="compType2" name="compType2" placeholder="" value=""></input>
                        </div>
                        <label for="weight" class="col-sm-2 control-label"><font color="red">WEIGHT</font></label>
                        <div class="col-sm-10">
                            <input type="number" step="any" class="form-control" id="weight" name="weight" placeholder="" value=""></input>
                        </div>
                        <label for="weight" class="col-sm-2 control-label"><font color="red">GROSS WEIGHT</font></label>
                        <div class="col-sm-10">
                            <input type="number" step="any" class="form-control" id="gr_weight" name="gr_weight" placeholder="" value=""></input>
                        </div>
                        <label for="surface" class="col-sm-2 control-label"><font color="red">SURFACE</font></label>
                        <div class="col-sm-10">
                            <input type="number" step="any" class="form-control" id="surface" name="surface" placeholder="" value=""></input>
                        </div>
                        <label for="length" class="col-sm-2 control-label"><font color="red">LENGTH</font></label>
                        <div class="col-sm-10">
                            <input type="number" step="any" class="form-control" id="length" name="length" placeholder="" value=""></input>
                        </div>
                        <label for="totalQty" class="col-sm-2 control-label"><font color="green">TOTAL QUANTITY</font></label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="totalQty" name="totalQty" placeholder="1" value="1"></input>
                        </div>
                        <label for="profile" class="col-sm-2 control-label"><font color="black">PROFILE</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="profile" name="profile" placeholder="" value=""></input>
                        </div>

                        <label class="col-sm-2 control-label"><font color="black">DWG TYPE</font></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="" id="dwg_typ" name="dwg_typ">
                                <option value="H" selected="">HOTROLL</option>                                
                                <option value="W">WELDED</option>
                            </select>
                        </div>

                        <label for="profile" class="col-sm-2 control-label"><font color="black">&nbsp;</font></label>
                        <div class="col-sm-10" id="contenHM">   
                        </div>
                    </div>

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="button" class="btn btn-warning btn-sm" name="hmBtn" id="HMCek" value="Submit Headmark Data">
                                <input type="button" class="btn btn-default btn-sm" value="Reset Entry" onclick="location.href = '<?php echo $_SERVER['PHP_SELF'] ?>'">
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->          
                </form>
            </div> <!-- panel-body -->  
            <?php
            $suggestCompTYpe = array();
            $projectSql = "SELECT DISTINCT(COMP_TYPE) AS COMP_TYPE FROM MASTER_DRAWING WHERE COMP_TYPE IS NOT NULL ORDER BY COMP_TYPE";
            $projectParse = oci_parse($conn, $projectSql);
            oci_execute($projectParse);

            while ($row = oci_fetch_array($projectParse)) {
                array_push($suggestCompTYpe, $row['COMP_TYPE']);
            }
            ?>
            <script type="text/javascript">
                $(function () {
                    var availableTags = <?php echo json_encode($suggestCompTYpe); ?>;
                    // console.log(availableTags);
                    $("#compType").autocomplete({
                        source: availableTags
                    });

                    $('#projectName').selectpicker();
                    $('#headmark').keyup(function () {
                        this.value = this.value.toUpperCase();
                        this.value = this.value.replace(' ', '');
                    });
                    $("#headmark").change(function () {
                        // alert("OKere");
                        $.get('cekHM.php', {
                            headmark: $.trim($('#headmark').val())
                        },
                        function (res) {
                            $('#contenHM').html(res);
                        }
                        );
                    });
                });
            </script>

            <?php
            if (isset($_POST['headmark'])) {

                $projectNameValue = strval($_POST['projectName']);
                $headmarkValue = str_replace(" ", "", (strval($_POST['headmark'])));
                $compTypeValue = rtrim(ltrim(strval($_POST['compType'])));
                $compTypeValue2 = rtrim(ltrim(strval($_POST['compType2'])));
                $weightValue = floatval($_POST['weight']);
                $surfaceValue = floatval($_POST['surface']);
                $lengthValue = floatval($_POST['length']);
                $qtyValue = intval($_POST['totalQty']);
                $profileValue = strval($_POST['profile']);
                $grossWeight = intval($_POST['gr_weight']);
                $dwg_typ = strval($_POST['dwg_typ']);

                $perHruf = concatHM($headmarkValue);
                $str_HM = @$perHruf[0];
                $int_HM = @$perHruf[1];
                $pad_HM = @$perHruf[2];
                if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
                    $str_HM = $headmarkValue;
                    $int_HM = 0;
                    $pad_HM = 0;
                }

//                echo "Dalam Perbaikan Brooo 10 menit";
//  exit();
                if (isset($_POST['submit'])) {
                    # code...
                    $MDDelSql = "DELETE FROM MASTER_DRAWING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
//                    echo $MDDelSql;
//                    exit();
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    //delete MD ASSIGN
                    $MDDelSql = "DELETE FROM MASTER_DRAWING_ASSIGNED WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    // Delete FAB
                    $MDDelSql = "DELETE FROM FABRICATION WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $MDDelSql = "DELETE FROM FABRICATION_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $MDDelParse = oci_parse($conn, $MDDelSql);
                    oci_bind_by_name($MDDelParse, ":headmark", $headmarkValue);
                    $MDDelRes = oci_execute($MDDelParse);
                    if ($MDDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    // DEL PAINT
                    $PaintDelSql = "DELETE FROM PAINTING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $headmarkValue);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $headmarkValue);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $headmarkValue);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    $PaintDelSql = "DELETE FROM PAINTING_QC_HIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PaintDelParse = oci_parse($conn, $PaintDelSql);
                    oci_bind_by_name($PaintDelParse, ":headmark", $headmarkValue);
                    $PaintDelRes = oci_execute($PaintDelParse);
                    if ($PaintDelRes) {
                        oci_commit($conn);
                    } else {
                        oci_rollback($conn);
                    }

                    // DEL PACKING DTL
                    $PckDelSql = "DELETE FROM PREPACKING_LIST WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))";
                    $PckDelParse = oci_parse($conn, $PckDelSql);
                    oci_bind_by_name($PckDelParse, ":headmark", $headmarkValue);
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
                    oci_bind_by_name($PckDelParse, ":headmark", $headmarkValue);
                    $PckDelRes = oci_execute($PckDelParse);
                    if ($PckDelRes) {
                        oci_commit($conn);
                        echo "DELET DTL PACK<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR DELET DTL PACK<br>";
                    }

                    // UPDATE MASTER TABLE SCURVE
                    $updtScurveSQL = "UPDATE MASTER_DRAWING_SCURVE SET HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) WHERE HEAD_MARK = :headmark ";
                    $updtScurvePARSE = oci_parse($conn, $updtScurveSQL);
                    oci_bind_by_name($updtScurvePARSE, ":headmark", $headmarkValue);
                    $updtScurveRES = oci_execute($updtScurvePARSE);
                    if ($updtScurveRES) {
                        oci_commit($conn);
                        echo "SUCCESS UPDATE MASTER DRAWING SCURVE<br>";
                    } else {
                        oci_rollback($conn);
                        echo "ERROR UPDATE MASTER DRAWING SCURVE<br>";
                    }
                }

//                echo "SELECT ('$str_HM'||LPAD('$int_HM',$pad_HM) FROM DUAL";
//                exit();
                // INSERTION INTO MASTER DRAWING
                $headmarkInsertionSql = "BEGIN MD_INS"
                        . "(('$str_HM'||LPAD('$int_HM',$pad_HM)), :COMPTYPE,:COMPTYPE2, :WEIGHT, "
                        . ":SURFACE, :PROFILE, :PROJNAME, :LENGTH, :QUANTITY, :GROSSWEIGHT,'$dwg_typ','$username'); END;";
                $headmarkInsertionParse = oci_parse($conn, $headmarkInsertionSql);
                oci_bind_by_name($headmarkInsertionParse, ":PROJNAME", $projectNameValue);
                // oci_bind_by_name($headmarkInsertionParse, ":HEADMARK", $headmarkValue);
                oci_bind_by_name($headmarkInsertionParse, ":COMPTYPE", $compTypeValue);
                oci_bind_by_name($headmarkInsertionParse, ":COMPTYPE2", $compTypeValue2);
                oci_bind_by_name($headmarkInsertionParse, ":WEIGHT", $weightValue);
                oci_bind_by_name($headmarkInsertionParse, ":SURFACE", $surfaceValue);
                oci_bind_by_name($headmarkInsertionParse, ":LENGTH", $lengthValue);
                oci_bind_by_name($headmarkInsertionParse, ":QUANTITY", $qtyValue);
                oci_bind_by_name($headmarkInsertionParse, ":PROFILE", $profileValue);
                oci_bind_by_name($headmarkInsertionParse, ":GROSSWEIGHT", $grossWeight);

                $headmarkInsertionRes = oci_execute($headmarkInsertionParse);
                if ($headmarkInsertionRes) {
                    oci_commit($conn);
                    echo 'HEADMARK INSERTED';
                    echo "<script>alert('HEADMARK INSERTED');location.href='$_SERVER[PHP_SELF]?PROJNAME=$projectNameValue';</script>";
                } else {
                    oci_rollback($conn);
                    echo 'ERROR HAS OCCURED';
                    echo "<script>alert('ERROR HAS OCCURED');location.href='$_SERVER[PHP_SELF]';</script>";
                }
            } // isset($_POST['submit']) ENDS
            ?> 
        </div> <!-- panel-default -->
    </body>
</html>    