<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
session_start();
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PT.WELTES ENERGI NUSANTARA | INPUT OPNAME</title>
        <!-- Bootstrap -->
        <link rel="icon" type="image/ico" href="../../favicon.ico">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/bootstrap-select.min.css" rel="stylesheet">
        <link href="../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <link href="../../css/datepicker.css" rel="stylesheet" type="text/css" />
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <script src="../../jQuery/jquery-1.11.0.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/bootstrap-select.min.js"></script>
        <script src="../../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../../js/jquery.dataTables.rowGrouping.js" type="text/javascript"></script>
        <script src="../../js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="../../js/numericInput.min.js"></script>


        <script>
            function ShowData() {
                var periode = $('#periode').val();
                var type = $('#opname-type').val();
                var job = $('#job').val();
                var subjob = $('#subjob').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_revise/show_revise_opname.php",
                    data: {periode: periode, type: type, job: job, subjob: subjob},
                    success: function (response, textStatus, jqXHR) {
                        $('#detail-monitoring').html(response);
                    }
                });
            }

            function EditJob() {
                var job = $('#job').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/subjob_dropdown.php",
                    data: {job: job},
                    success: function (response, textStatus, jqXHR) {
                        $('#div-subjob').html(response);
                    }
                });
            }

            function ChangeType() {
                var periode = $('#periode').val();
                var type = $('#opname-type').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/job_dropdown.php",
                    data: {type: type, periode: periode},
                    success: function (response, textStatus, jqXHR) {
                        $('#div-job').html(response);
                    }
                });
            }
        </script>
        <style>
            #detail-monitoring{
                padding-left: 5%;
                width: 98%;
            }
        </style>
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../../../../login_painting.php"><b>REVISE OPNAME PAINTING</b></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="../opname_painting/input_opname_painting.php">INPUT DATA</a></li>
                        <li><a style="background-color: orange;" href="../opname_painting/revise_opname_painting.php">REVISE</a></li>
                        <li><a href="../opname_painting/monitoring_opname_painting.php">MONITORING</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Signed in as, <font size="4"><b><?php echo $username ?></b></font></a></li></ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <br>
        <div class="page-header text-center">
            <h1><font color="#0033CC"><b>REVISION OPNAME PAINTING</b></font></h1>
        </div>
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="control-label col-sm-1" for="periode">SELECT PERIODE</label>
                <div class="col-sm-11">
                    <select class="form-control" id="periode">
                        <option value="" selected="" disabled="">[SELECT PERIODE]</option>
                        <?php
                        $periodeSql = "SELECT DISTINCT OPNAME_PERIOD FROM MST_OPNAME_PNT ORDER BY OPNAME_PERIOD";
                        $periodeParse = oci_parse($conn, $periodeSql);
                        oci_execute($periodeParse);
                        while ($row2 = oci_fetch_array($periodeParse)) {
                            echo "<option value='$row2[OPNAME_PERIOD]'>$row2[OPNAME_PERIOD]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Type Opname</label>
                <div class="col-sm-11"> 
                    <select class="form-control" id="opname-type" onchange="ChangeType();">
                        <option value="" selected="" disabled="">[SELECT TYPE OPNAME]</option>
                        <option value="PAINT">PAINTING</option>
                        <option value="BLAST">SANDBLAST</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Select Job</label>
                <div class="col-sm-11" id="div-job"> 
                    <select class="form-control" id="job" onchange="EditJob();">
                        <option value="" selected="" disabled="">[SELECT JOB]</option>
                        <?php
                        $periodeSql = "SELECT DISTINCT PROJECT_NO FROM VW_REPORT_OPNAME_PNT ORDER BY PROJECT_NO";
                        $periodeParse = oci_parse($conn, $periodeSql);
                        oci_execute($periodeParse);
                        while ($row2 = oci_fetch_array($periodeParse)) {
                            echo "<option value='$row2[PROJECT_NO]'>$row2[PROJECT_NO]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Select SubJob</label>
                <div class="col-sm-11" id="div-subjob"> 
                    <select class="form-control" id="subjob">
                        <option value="" selected="" disabled="">[SELECT SUB JOB]</option>
                        <?php
//                        $periodeSql = "SELECT DISTINCT PROJECT_NAME_NEW FROM VW_REPORT_OPNAME_PNT ORDER BY PROJECT_NAME_NEW";
//                        $periodeParse = oci_parse($conn, $periodeSql);
//                        oci_execute($periodeParse);
//                        while ($row2 = oci_fetch_array($periodeParse)) {
//                            echo "<option value='$row2[PROJECT_NAME_NEW]'>$row2[PROJECT_NAME_NEW]</option>";
//                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>

        <div class="col-sm-12" id="div-submit">
            <button class="btn btn-danger btn-sm pull-right" onclick="ShowData();" id="btn-submit">SHOW REVISE</button>
        </div>

        <br><br>
        <div id="detail-monitoring">
        </div>
    </body>
</html>