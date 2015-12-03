<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$this_thurday = strtotime('thursday this week');
$last_thursday = strtotime("last Thursday", $this_thurday);
?>
<form class="form-horizontal" role="form">
    <div class="form-group">
        <label class="control-label col-sm-1" for="pwd">TANGGAL OPNAME</label>
        <div class="col-sm-11"> 
            <input type="text" class="form-control" id="tgl-opname" value="<?= date("d/m/Y", $this_thurday); ?>" 
                   readonly="" onchange="ChangeDate();">
        </div>
    </div>
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
            <input type="text" class="form-control" id="periode" placeholder="Enter Periode" value="" readonly="" onchange="UbahPeriode();">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-1" for="pwd">OPNAME ID</label>
        <div class="col-sm-11"> 
            <input type="text" class="form-control" id="opname-id" placeholder="Digenerate Otomatis" value="" readonly="">
        </div>
    </div>
</form>

<script>
    $('.selectpicker').selectpicker();
    $('#tgl-opname').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        daysOfWeekDisabled: [0, 1, 2, 3, 5, 6]
    });
    function ChangeDate() {
        var tanggal = $('#tgl-opname').val();
        $.ajax({
            type: 'POST',
            url: "non_drawing_print/drop_down_element.php",
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
            }
        });
    }
    function ChangeJob() {
        $('#subjob').empty();
        $('#subcont').empty();
        $('#periode').attr("readonly", "").val("");
        var job = $('#job').val();
        var tanggal = $('#tgl-opname').val();
        $.ajax({
            type: 'POST',
            url: "non_drawing_print/drop_down_element.php",
            data: {"action": "change_job", job: job, tanggal: tanggal},
            dataType: "JSON",
            success: function (response, textStatus, jqXHR) {
                var result = "<option value='' selected='' disabled>Select Sub Job.........</option>";
                $.each(response, function (key, value) {
                    result += '<option value="' + value.PROJECT_NAME + '">' + value.PROJECT_NAME_NEW + '</option>';
                });
                $('#subjob').html(result).selectpicker('refresh');
            }
        });
    }

    function ChangeSubJob() {
        var job = $('#job').val();
        var subjob = $('#subjob').val();
        var tanggal = $('#tgl-opname').val();
        $('#subcont').empty();
        $('#periode').attr("readonly", "").val("");
        $.ajax({
            type: 'POST',
            url: "non_drawing_print/drop_down_element.php",
            data: {"action": "change_subjob", job: job, subjob: subjob, tanggal: tanggal},
            dataType: "JSON",
            success: function (response, textStatus, jqXHR) {
                var result = "<option value='' selected='' disabled>Select Subcont.........</option>";
                $.each(response, function (key, value) {
                    result += '<option value="' + value.SUBCONT_ID + '">' + value.SUBCONT_ID + '</option>';
                });
                $('#subcont').html(result).selectpicker('refresh');
            }
        });
    }
    function ChangeSubcont() {
        var job = $('#job').val();
        var subjob = $('#subjob').val();
        var subcont = $('#subcont').val();
        var tanggal = $('#tgl-opname').val();
        var opname_id = "";
        $.ajax({
            type: 'POST',
            url: "non_drawing_print/drop_down_element.php",
            dataType: "JSON",
            data: {"action": "change_subcont", job: job, subjob: subjob, subcont: subcont, tanggal: tanggal},
            success: function (response, textStatus, jqXHR) {
                $('#periode').val(response[0].OPN_PERIOD);
                $('#opname-id').val(response[0].OPNAME_ID);
                opname_id = response[0].OPNAME_ID;
            }
        }).then(function () {
            $.ajax({
                type: 'POST',
                url: "non_drawing_print/show_opname_revision.php",
                data: {opname_id: opname_id, job: job, subjob: subjob, subcont: subcont, tanggal: tanggal},
                beforeSend: function (xhr) {
                    $('#input-content').empty();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#input-content').html(response);
                }
            });
        });
    }
</script>