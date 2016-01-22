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
        <script src="../../js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="../../js/bootstrap-select.min.js"></script>
        <script src="../../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../../js/numericInput.min.js"></script>

        <script>
            var counteradd = 1;
            var counterremove = -1;
            $(document).ready(function() {
                $('#tgl-opname').datepicker({
//                    "todayBtn": true,
//                    "todayHighlight": true
                });
                $('#viewOpnameTable').DataTable();
                $('.selectpicker').selectpicker({
                    "width": '100%'
                });
                $('#periode').numericInput();
                $('#project-name').on('change', $(this), function() {
                    var project_name = $('#project-name').val();
                    var opname_type = $('#opname-type').val();
                    $.ajax({
                        type: 'POST',
                        url: "divpages/showBuildingDropdown.php",
                        data: {project_name: project_name, opname_type: opname_type},
                        success: function(response) {
                            $('#div-source').html(response);
                        }
                    });
                });
                $('#price-opname').numericInput({
                    allowFloat: true
                });
            });
        </script>

        <style>
            select optgroup{
                background:#000;
                color:#fff;
                font-style:normal;
                font-weight:normal;
            }
            .dropdown-header{
                font-size:15px;
                color: red;
                font-style: italic;
                font-weight: bold ;
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
                    <a class="navbar-brand" href="../../../../login_painting.php"><b>INPUT DATA OPNAME PAINTING</b></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="opname_painting/input_opname_painting.php" style="background-color: orange;">INPUT DATA</a>
                        </li>
                        <li><a href="../opname_painting/revise_opname_painting.php">REVISE</a></li>
                        <li><a href="../opname_painting/monitoring_opname_painting.php">MONITORING</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Signed in as, <font size="4"><b><?php echo $username ?></b></font></a></li></ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <br>
        <div class="page-header text-center">
            <h1><font color="#0033CC"><b>INPUT OPNAME PAINTING</b></font></h1>
        </div>
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="control-label col-sm-1" for="periode">Periode</label>
                <div class="col-sm-11">
                    <input type="number" class="form-control" id="periode" value="1">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Tanggal Opname</label>
                <div class="col-sm-11"> 
                    <input type="text" class="form-control" id="tgl-opname" placeholder="Enter password" value="<?php echo date("m/d/Y"); ?>" readonly="">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Type Opname</label>
                <div class="col-sm-11"> 
                    <select class="form-control" id="opname-type">
                        <option value="PAINT">PAINTING</option>
                        <option value="BLAST">SANDBLAST</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Subcont Opname</label>
                <div class="col-sm-11"> 
                    <input type="text" id="subcont-opname" class="form-control" value="GUNADI">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">Price Opname</label>
                <div class="col-sm-11"> 
                    <input type="text" id="price-opname" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-1" for="pwd">SubJob</label>
                <div class="col-sm-11"> 
                    <select class="selectpicker" id="project-name" data-live-search='true'>
                        <option value="" selected="" disabled=""></option>
                        <?php
                        $optGroupSql = "SELECT DISTINCT VPI.PROJECT_NO "
                                . "FROM PAINTING_QC PQ INNER JOIN VW_PROJ_INFO VPI "
                                . "ON VPI.PROJECT_NAME_OLD = PQ.PROJECT_NAME "
                                . "ORDER BY VPI.PROJECT_NO";
                        $optGroupParse = oci_parse($conn, $optGroupSql);
                        oci_execute($optGroupParse);
                        while ($row = oci_fetch_array($optGroupParse)) {
                            echo "<optgroup label=$row[PROJECT_NO]> <b>$row[PROJECT_NO]</b>";
                            $optSql = "SELECT DISTINCT VPI.PROJECT_NAME_OLD, VPI.PROJECT_NAME_NEW "
                                    . "FROM PAINTING_QC PQ INNER JOIN VW_PROJ_INFO VPI "
                                    . "ON VPI.PROJECT_NAME_OLD = PQ.PROJECT_NAME "
                                    . "WHERE PQ.FINISHING_QC <> 0 "
                                    . "AND VPI.PROJECT_NO = '$row[PROJECT_NO]'"
                                    . "ORDER BY VPI.PROJECT_NAME_NEW";
                            $optParse = oci_parse($conn, $optSql);
                            oci_execute($optParse);
                            while ($row1 = oci_fetch_array($optParse)) {
                                echo "<option value='$row1[PROJECT_NAME_OLD]'>$row1[PROJECT_NAME_NEW]</option>";
                            }
                            echo "</optgroup>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
        <div class="col-sm-6" id="div-source" style="width: 45%;">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">HEAD MARK</th>
                        <th class="text-center" style="vertical-align: middle;">COMP<br>TYPE</th>
                        <th class="text-center" style="vertical-align: middle;">SURFACE(M<sup>2</sup>)</th>
                        <th class="text-center" style="vertical-align: middle;">QC PASS<br>QTY</th>
                        <th class="text-center" style="vertical-align: middle;">ACT</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-sm-6" id="div-target"  style="width: 54%;">
            <table class="table table-bordered table-striped" id="table-target">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">HEAD MARK</th>
                        <th class="text-center" style="vertical-align: middle;">COMP<br>TYPE</th>
                        <th class="text-center" style="vertical-align: middle;">SURFACE(M<sup>2</sup>)</th>
                        <th class="text-center" style="vertical-align: middle;">QC PASS<br>QTY</th>
                        <th class="text-center" style="vertical-align: middle;">OPNAME<br>QTY</th>
                        <!--<th class="text-center" style="vertical-align: middleqdw;">PRICE</th>-->
                        <th class="text-center" style="vertical-align: middle;">ACT</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
            </table>
        </div>
        <div class="col-sm-12" id="div-submit">
            <button class="btn btn-danger btn-sm col-sm-12" onclick="SubmitData();" disabled="" id="btn-submit">SUBMIT BUTTON</button>
        </div>
    </body>
</html>