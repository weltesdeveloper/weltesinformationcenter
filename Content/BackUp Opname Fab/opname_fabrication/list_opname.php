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
$this_thurday = strtotime('thursday this week');
$last_thursday = strtotime("last Thursday", $this_thurday);
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PT.WELTES ENERGI NUSANTARA | INPUT OPNAME</title>
        <!-- Bootstrap -->
        <link rel="icon" type="image/ico" href="../../favicon.ico">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/jquery-ui.css" rel="stylesheet">
        <link href="../../css/bootstrap-select.min.css" rel="stylesheet">
        <link href="../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <script src="../../jQuery/jquery-1.11.0.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/bootstrap-select.min.js"></script>
        <script src="../../js/jquery-ui.js"></script>

        <script src="../../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script>
            //RUBAH TANGGAL
            function ChangeDate() {
                var tanggal = $('#tgl-opname').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/change_ELEMENT.php",
                    data: {"action": "change_tanggal", tanggal: tanggal},
                    dataType: "JSON",
                    beforeSend: function (xhr) {
                        $('#job').empty();
                    },
                    success: function (response, textStatus, jqXHR) {
                        var result = "<option value='' selected='' disabled>Select Job.........</option>";
                        $.each(response, function (key, value) {
                            result += "<option value=" + value.PROJECT_NO + ">" + value.PROJECT_NO + "</option>";
                        });
                        $('#job').html(result).selectpicker('refresh');
                        console.log(result);
                    }
                });
            }

            $(document).ready(function () {
                $('#tgl-opname').datepicker({
                    beforeShowDay: DisableMonday,
                    dateFormat: "dd/mm/yy"
                });
            });
            //DISABLE HARI SELAIN KAMIS
            function DisableMonday(date) {
                var day = date.getDay();
                // If day == 1 then it is MOnday
                if (day == 0 || day == 1 || day == 2 || day == 3 || day == 5 || day == 6) {
                    return [false];
                } else {
                    return [true];
                }
            }
            //RUBAH JOB
            function ChangeJob() {
                var job = $('#job').val();
                var tanggal = $('#tgl-opname').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/change_ELEMENT.php",
                    data: {"action": "change_job", job: job, tanggal: tanggal},
                    dataType: "JSON",
                    beforeSend: function (xhr) {
                        $('#subjob').empty();
                        $('#subcont').empty();
                    },
                    success: function (response, textStatus, jqXHR) {
                        var result = "<option value='' selected='' disabled>Select Sub Job.........</option>";
                        $.each(response, function (key, value) {
                            result += '<option value="' + value.PROJECT_NAME + '">' + value.PROJECT_NAME_NEW + '</option>';
                        });
                        $('#subjob').html(result).selectpicker('refresh');
                    }
                });
            }
//RUBAH SUBJOB
            function ChangeSubJob() {
                var job = $('#job').val();
                var subjob = $('#subjob').val();
                var tanggal = $('#tgl-opname').val();
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/change_ELEMENT.php",
                    data: {"action": "change_subjob", job: job, subjob: subjob, tanggal: tanggal},
                    dataType: "JSON",
                    beforeSend: function (xhr) {
                        $('#subcont').empty();
                    },
                    success: function (response, textStatus, jqXHR) {
                        var result = "<option value='' selected='' disabled>Select Subcont.........</option>";
                        $.each(response, function (key, value) {
                            result += "<option value=" + value.SUBCONT_ID + ">" + value.SUBCONT_ID + "</option>";
                        });
                        $('#subcont').html(result).selectpicker('refresh');
                    }
                });
            }
//RUBAH SUBCONT
            function ChangeSubcont() {
                var job = $('#job').val();
                var subjob = $('#subjob').val();
                var subcont = $('#subcont').val();
                var tanggal = $('#tgl-opname').val();
                var opname_id = "";
                $.ajax({
                    type: 'POST',
                    url: "divpages_monitoring/change_ELEMENT.php",
                    dataType: "JSON",
                    data: {"action": "change_subcont", job: job, subjob: subjob, subcont: subcont, tanggal: tanggal},
                    success: function (response, textStatus, jqXHR) {
                        console.log(response);
                        $("#opname-id").val(response[0].OPNAME_ID);
                        $("#periode").val(response[0].OPN_PERIOD);
                        opname_id = response[0].OPNAME_ID;
                    }
                }).then(function () {
                    $.ajax({
                        type: 'POST',
                        url: "divpages_monitoring/show_data.php",
                        data: {opname_id: opname_id, job: job, subjob: subjob, subcont: subcont, tanggal: tanggal},
//            dataType: "JSON",
                        beforeSend: function (xhr) {
                            $('#table-revision').empty();
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#table-revision').html(response);
                        }
                    });
                });
            }

        </script>
        <style>
            #table-revision {
                padding-left: 9%;
                width: 100%;
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
                    <a class="navbar-brand" href="../../../../login_painting.php"><b>INPUT OPNAME</b></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="input_opname_fabrication.php">INPUT</a></li>
                        <li><a href="revise_opname_fabrication.php">REVISE</a></li>
                        <li><a href="list_opname.php">LIST</a></li>
                        <li class="active"><a href="print_opname.php">PRINT</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Signed in as, <font size="4"><b><?php echo $username ?></b></font></a></li></ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="form-group col-sm-12">
            <form class="form-horizontal" role="form">
<!--                <div class="form-group">
                    <label class="control-label col-sm-1" for="pwd">TANGGAL OPNAME</label>
                    <div class="col-sm-11"> 
                        <input type="text" class="form-control" id="tgl-opname" placeholder="Enter password" 
                               value="<?= date("d/m/Y", $this_thurday); ?>" readonly="" onchange="ChangeDate();">
                    </div>
                </div>-->
                <div class="form-group">
                    <label class="control-label col-sm-1">JOB</label>
                    <div class="col-sm-11">
                        <select class="selectpicker" id="job" onchange="ChangeJob();" data-live-search="true" data-width="100%">
                            <option value="" disabled="" selected="">Select Job.........</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-1">SUB JOB</label>
                    <div class="col-sm-11"> 
                        <select class="selectpicker" id="subjob" onchange="ChangeSubJob();" data-live-search="true" data-width="100%">

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-1" for="pwd">SUBCONT</label>
                    <div class="col-sm-11"> 
                        <select class="selectpicker" id="subcont" onchange="ChangeSubcont();" data-live-search="true" data-width="100%">

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-1" for="pwd">PERIODE</label>
                    <div class="col-sm-11"> 
                        <input type="text" class="form-control" id="periode" placeholder="Enter Periode" value="" readonly="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-1" for="pwd">OPNAME ID</label>
                    <div class="col-sm-11"> 
                        <input type="text" class="form-control" id="opname-id" placeholder="Digenerate Otomatis" value="" readonly="">
                    </div>
                </div>
                <div class="form-group" id="table-revision">
                </div>
            </form>
        </div>
        <div class="form-group" id="detail-opname" style="width: 98%; margin-left: 15px;"></div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal with Dark Overlay</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="SubmitEditData();">Submit Revision</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


<!--<script src="divpages_monitoring/controller.js"></script>-->


