<?php
require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
require_once '../smart_resize_image.function.php';
// ini_set('max_input_vars', 3000);


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
$PACK_ACCS = HakAksesUser($username, 'PACK_ACCS', $conn);
if ($PACK_ACCS <> 1) {
    # code...
    echo <<< EOD
       <h1>You Can't ACCESS PACKING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | PACKING ASSIGNMENT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara PACKING ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">
        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" />
        <link href="../dist/bootstrap-fileinput-master/css/fileinput.min.css" rel="stylesheet">

        <script src="../jQuery/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap-select.min.js"></script>
        <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../dist/bootstrap-fileinput-master/js/fileinput.min.js"></script>


        <script>
            $(document).ready(function () {
                $('.selecpicker').selectpicker({
//                    size: "auto",
//                    width: "100%"
                });

                $('#job-dropdown').change(function () {
                    var job = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: "divpages/job.php",
                        dataType: 'JSON',
                        data: {job: job},
                        success: function (response, textStatus, jqXHR) {
                            var option = "<option value='' selected='' disabled=''></option>";
                            $.each(response, function (index, value) {
                                option += "<option value='" + value.PROJECT_NAME_OLD + "'>" + value.PROJECT_NAME_NEW + "</option>"
                            });
                            $('#subjob-dropdown').html(option).selectpicker('refresh');
                        }
                    });
                });

                $('#subjob-dropdown').change(function () {
                    var subjob = $(this).val();
                    var job = $('#job-dropdown').val();
                    $.ajax({
                        type: 'POST',
                        url: "divpages/subjob.php",
                        dataType: 'JSON',
                        data: {job: job, subjob: subjob},
                        success: function (response, textStatus, jqXHR) {
                            var option = "<option value='' selected='' disabled=''></option>";
                            $.each(response, function (index, value) {
                                option += "<option value='" + value + "'>" + value + "</option>"
                            });
                            $('#coli-dropdown').html(option).selectpicker('refresh');
                        }
                    });
                });
                $("#img_pack").fileinput({
                    showUpload: false,
//                    maxFileCount: 3,
                    allowedFileTypes: ["image"],
                    overwriteInitial: false,
                    previewFileType: "image",
                    maxFileSize: 5000,
                    // browseClass: "btn btn-success",
                    browseLabel: " Pick Image",
                    browseIcon: '<i class="glyphicon glyphicon-picture"></i>',
                    removeClass: "btn btn-danger",
                    removeLabel: "Delete",
                    removeIcon: '<i class="glyphicon glyphicon-trash"></i>'
                });
            });

            function SubmitData() {
                var elem = document.getElementById("img_pack");
                var length = elem.files.length;
                var formData = new FormData();
                for (var i = 0; i < length; i++) {
                    var filename = "file" + i;
                    var imagename = $('#img_pack')[0].files[i];
                    formData.append(filename, imagename);
                }
                formData.append("length", length);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    data: formData,
                    url: "divpages/upload_file.php",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response, textStatus, jqXHR) {
//                        swal(response, "", "success");
//                                redirect("PACKING");
//                        window.location.reload();
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>RECEIVING</b></font> | <font color="#CC0000" size="5"><b>  MATERIAL ONSITE</b></font></h3>
            </div> <!-- panel heading   data-bv-feedbackicon s-valid="glyphicon glyphicon-ok"    data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"    data-bv-feedbackicons-validating="glyphicon glyphicon-refresh"-->
            <div class="panel-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Project No : </label>
                        <div class="col-sm-10">
                            <select id="job-dropdown" data-live-search="true" class="selectpicker" data-width="100%">
                                <option value=""></option>
                                <?php
                                $sql = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP = 'STRUCTURE' ORDER BY PROJECT_NO";
                                $parse = oci_parse($conn, $sql);
                                oci_execute($parse);
                                while ($row = oci_fetch_assoc($parse)) {
                                    echo "<option value='$row[PROJECT_NO]'>$row[PROJECT_NO]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Project Name : </label>
                        <div class="col-sm-10">
                            <select id="subjob-dropdown" data-live-search="true" class="selectpicker" data-width="100%">
                                <option value="" selected="" disabled></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Coli Number : </label>
                        <div class="col-sm-10">
                            <select id="coli-dropdown" data-live-search="true" class="selectpicker" data-width="100%">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">LAYDOWN AREA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="laydown-area" placeholder="Enter Lay Down Area">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">PICTURE</label>
                        <div class="col-sm-10">
                            <input id="img_pack" name="img_pack[]" type="file" multiple="multiple" accept="image/*" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-success col-sm-12" onclick="SubmitData();">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>