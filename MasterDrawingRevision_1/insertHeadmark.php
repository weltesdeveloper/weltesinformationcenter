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
                <form class="form-horizontal" id="frmHM" role="form">
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
                            <input type="text" class="form-control" id="headmark" name="headmark" placeholder="ex. SMS-PH-BM1" value="" maxlength="50" onchange="ChangeHM();"></input>
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
                            <select class="form-control" id="dwg_typ" name="dwg_typ">
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
                                <input type="button" class="btn btn-warning btn-sm" name="hmBtn" id="HMCek" value="Submit Headmark Data" onclick="SubmitHeadMark();">
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
                });
                function ChangeHM() {
                    var head_mark = $('#headmark').val();
                    var project_name = $('#projectName').val();
                    $.ajax({
                        type: 'POST',
                        url: "cekHM.php",
                        data: {head_mark: head_mark, project_name: project_name, action: "cek_duplikat"},
                        dataType: 'JSON',
                        success: function (response, textStatus, jqXHR) {
                            if (response.status == "ada") {
                                $.each(response.data_balik, function (key, value) {
                                    $('#headmark').val(value.HEAD_MARK).prop("disabled", true);
                                    $('#compType').val(value.COMP_TYPE).prop("disabled", true);
                                    $('#compType2').val(value.COMP_TYPE_LVL2).prop("disabled", true);
                                    $('#weight').val(value.WEIGHT).prop("disabled", true);
                                    $('#gr_weight').val(value.GR_WEIGHT).prop("disabled", true);
                                    $('#surface').val(value.SURFACE).prop("disabled", true);
                                    $('#length').val(value.LENGTH).prop("disabled", true);
                                    $('#totalQty').val(value.TOTAL_QTY).prop("disabled", true);
                                    $('#profile').val(value.PROFILE).prop("disabled", true);
                                    $('#dwg_typ option:selected').val(value.DWG_TYPE).prop("disabled", true);
                                    $('#projectName').val(value.PROJECT_NAME).prop("disabled", true);
                                    $('#projectName').selectpicker('refresh')
                                    $('#dwg_typ').prop("disabled", true);
                                });
                                $('#HMCek').prop("disabled", true);
                                $('#contenHM').html("<br><hr><div class='text-center'><b>☹HEAD MARK SUDAH ADA. UNTUK REVISI PILIH MENU REVISI HEAD MARK ATAU HUBUNGI ADMINISTRATOR☹</b></span><hr>")
                            } else {
                                alert("HEAD MARK BELUM ADA");
                            }
                        }
                    });
                }

                function SubmitHeadMark() {
                    var project_name = $('#projectName').val();
                    var headmark = $('#headmark').val();
                    var compType = $('#compType').val();
                    var compType2 = $('#compType2').val();
                    var weight = $('#weight').val();
                    var gr_weight = $('#gr_weight').val();
                    var surface = $('#surface').val();
                    var length = $('#length').val();
                    var totalQty = $('#totalQty').val();
                    var profile = $('#profile').val();
                    var dwg_typ = $('#dwg_typ').val();
                    var sentReq = {
                        profile: profile,
                        totalQty: totalQty,
                        length: length,
                        surface: surface,
                        gr_weight: gr_weight,
                        weight: weight,
                        compType2: compType2,
                        compType: compType,
                        headmark: headmark,
                        project_name: project_name,
                        dwg_typ: dwg_typ,
                        action: "insert_data"
                    };
                    console.log(sentReq);
                    if (sentReq.project_name == null) {
                        alert("TOLONG ISIKAN PROJECT NAME");
                    } else if (sentReq.profile == "") {
                        alert("TOLONG ISIKAN PROFILE");
                    } else if (sentReq.totalQty == "") {
                        alert("TOLONG ISIKAN QTY");
                    } else if (sentReq.length == "") {
                        alert("TOLONG ISIKAN LENGTH");
                    } else if (sentReq.surface == "") {
                        alert("TOLONG ISIKAN SURFACE");
                    } else if (sentReq.gr_weight == "") {
                        alert("TOLONG ISIKAN GROSS WEIGHT");
                    } else if (sentReq.weight == "") {
                        alert("TOLONG ISIKAN WEIGHT");
                    } else if (sentReq.compType == "") {
                        alert("TOLONG ISIKAN COMP TYPE");
                    } else if (sentReq.headmark == "") {
                        alert("TOLONG ISIKAN HEAD MARK");
                    } else {
                        var cf = confirm("SUBMIT THIS HEAD MARK?");
                        if (cf == true) {
                            $.ajax({
                                type: 'POST',
                                url: "cekHM.php",
                                data: sentReq,
                                success: function (response, textStatus, jqXHR) {
                                    if (response.indexOf("SUKSES") >= 0) {
                                        alert("SUKSES INSERT DRAWING");
                                        window.location.reload();
                                    } else {
                                        alert("GAGAL INSERT DRAWING");
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                }


            </script>
        </div> <!-- panel-default -->
    </body>
</html>    