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

// HAK AKSES
$PAINT_ACCS = HakAksesUser($username, 'PAINT_ACCS', $conn);
if ($PAINT_ACCS <> 1) {
    # code...
    echo <<< EOD
       <h1>You Can't ACCESS PAINTING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
$this_thurday = strtotime('thursday this week');
$last_thursday = strtotime("last Thursday", $this_thurday);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PT.WELTES ENERGI NUSANTARA | FINAL WORK OPNAME</title>

        <!-- Bootstrap -->
        <link rel="icon" type="image/ico" href="../../../../favicon.ico">
        <link href="../../../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../../../css/bootstrap-select.min.css" rel="stylesheet">
        <link href="../../../../css/scrollyou.css" rel="stylesheet">
        <link href="../../../../css/stickyfooter.css" rel="stylesheet">

        <!-- DATA TABLES -->
        <link href="../../../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <link href="../../../../css/datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <script src="../../../../jQuery/jquery-1.11.0.js"></script>
        <script src="../../../../js/bootstrap.min.js"></script>
        <script src="../../../../js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="../../../../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../../../../js/bootstrap-select.min.js"></script>
        <script src="../../../../js/scrollyou.js"></script>
        <script src="jquery.number/jquery.maskMoney.min.js"></script>

        <div id="wrap">
            <!-- Fixed navbar -->
            <div class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="../../../../login_painting.php"><b>PROCESS | OPNAME</b></a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="../../../../index.html">HOME</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <!-- <li><a href="../FabricationQC/update_painting_qc.php">Painting QC</a></li> -->
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Nav header</li>
                                    <li><a href="../../../../SmartAdmin/index.php">Monitoring</a></li>
                                    <li><a href="workOpname.php">Final Work Opname</a></li>
                                    <li><a href="opnameMonitor.php">Monitor Opname</a></li>
                                    <li><a href="ReviseOpname.php">Revise Opname</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a>Signed in as, <font size="4"><b><?php echo $username ?></b></font></a></li></ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
            <br/>
            <!-- Begin page content -->
            <div class="container-fluid">
                <div class="page-header">
                    <h1>OPNAME <span class="glyphicon glyphicon-play"></span>
                        <font color="#0033CC"><b>FINAL WORK OPNAME</b></font></h1>
                </div>
                <!-- DROPDOWN FOR HEADMARK -->  

                <!--$<input type="text" id="price" name="number" />-->
                <form class="form-inline">
                     <!--<input type="text" id="currency" />-->
                    <!--<input type="number" id="periode" class="form-control" value="0" min="0">-->
                    <select class="selectpicker" id="periode" class="form-control" data-live-search="true">
                        <?php
                        for ($i = 0; $i <= 100; $i++) {
                            echo "<option value=$i>$i</option>";
                        }
                        ?>
                    </select>
                    <input type="date" id="date" class="form-control" value="<?= date("m/d/Y", $this_thurday); ?>" readonly="">

                    <div class="form-group">
                        <?php
                        $projectParse = oci_parse($conn, "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP = 'STRUCTURE' "
                                . "ORDER BY PROJECT_NO");
                        oci_execute($projectParse);

                        echo '<SELECT name="ProjNme" id="jobDropdown" class="selectpicker"
                              data-style="btn-primary" data-live-search="true">';
                        echo '<OPTION VALUE="">SELECT JOB NUMBER</OPTION>';

                        while ($row = oci_fetch_array($projectParse, OCI_ASSOC)) {
                            $ProjNme = $row ['PROJECT_NO'];
                            echo "<OPTION VALUE='$ProjNme'>$ProjNme</OPTION>";
                        }
                        echo '</SELECT>';
                        ?>
                    </div>

                    <div class="form-group" id="buildingDropdown">    
                        <!--<input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">-->
                    </div>

                    <div class="form-group" id="subcontDropdown">
                        <!--<input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">-->
                    </div>
                </form>

                <br/>
                <!-- CONTAINER TO SHOW THE TABLE -->
                <div align="left" class="table-responsive" id="finalOpnameTable"></div>

            </div> <!-- CONTAINER End -->
        </div> <!-- WRAP End -->
        <div id="footer">
            <div class="container">
                <p class="text-muted credit"><a href="http://weltes.co.id">PT. Weltes Energi Nusantara</a></p>
            </div>
        </div>
        <script>
            $(document).ready(function () {
//                $('#date').datepicker({
//                    daysOfWeekDisabled: [0, 1, 2, 3, 5, 6]
//                });
                $('#viewOpnameTable').DataTable();
                $('.selectpicker').selectpicker({
                    width: 'auto'
                });

                $('#jobDropdown').on('change', $(this), function () {
                    var job = $('#jobDropdown').val();
                    $.ajax({
                        type: 'POST',
                        url: "InputOpname/showBuildingDropdown.php",
                        data: {jobValue: job},
                        success: function (response) {
                            $('#buildingDropdown').html(response);
                        }
                    });
                });
            });
        </script>
    </body>
</html>