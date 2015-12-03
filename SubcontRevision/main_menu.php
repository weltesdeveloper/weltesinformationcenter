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
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css" />
        <!--<link rel="stylesheet" type="text/css" href="revisionCss/jquery-ui.css">-->
        <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="../AdminLTE/css/bootstrap-editable.css">

        <script src="../jQuery/jquery-1.11.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script language="javascript"  src="../js/bootstrap-select.min.js"></script>
        <!--<script src="revisionJs/jquery-ui.js"></script>-->
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../AdminLTE/js/bootstrap-editable.min.js"></script>
        <script src="js/jquery.mockjax.js"></script>
        <script src="js/typeaheadjs.js"></script>
        <script src="js/typeahead.min.js"></script>

    </head>
    <body>
        <!-- <div class="ui-widget">
          <label for="tags">Tags: </label>
          <input id="tags">
        </div> -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"></font><font color="#CC0000" size="5"><b> SUBCONT REVISION </b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" id="frmHM" role="form">
                    <div class="form-group">                 
                        <label for="projectName" class="col-sm-1 control-label">PROJECT NAME</label>
                        <div class="col-sm-11">
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
                    </div>
                    <div class="form-group">  
                        <label for="headmark" class="col-sm-1 control-label"><font color="blue">SUBCONT</font></label>
                        <div class="col-sm-11">
                            <select class="selectpicker" id="subcont" data-live-search='true' data-width='100%' onchange="ChangeSubcont();"></select>
                        </div>
                    </div>

                    <div class="form-group">  
                        <label for="headmark" class="col-sm-1 control-label"><font color="blue"></font></label>
                        <div class="col-sm-11" id="data-marking">
                            <table class="table table-striped table-bordered" id="table-subcont">
                                <thead>
                                    <tr>
                                        <th class="text-center">HEAD MARK</th>
                                        <th class="text-center" style="width: 100px;">ASSIGN DATE</th>
                                        <th class="text-center">SUBCONT</th>
                                        <th class="text-center">ID</th>
                                        <th class="text-center" style="width: 100px;">QTY ASSIGN</th>
                                        <th class="text-center">QC</th>
                                        <th class="text-center">SPV</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div> <!-- panel-body -->  
            <?php
            $arraySubcont = "";
            $sql = "SELECT DISTINCT SUBCONT_ID FROM SUBCONTRACTOR WHERE SUBCONT_ACTUAL = 'ACTIVE' ORDER BY SUBCONT_ID ASC";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            while ($row1 = oci_fetch_array($parse)) {
                $xx = "{value:'$row1[SUBCONT_ID]', text: '$row1[SUBCONT_ID]'},";
                $arraySubcont .= $xx;
            }

            $arraySubcont = substr($arraySubcont, 0, strlen($arraySubcont) - 1);
            $arraySubcont = "[" . $arraySubcont . "]";
            ?>
            <script type="text/javascript">
                var aSubcont = <?php echo "$arraySubcont"; ?>;
                $(function () {
                    $('#projectName').selectpicker();
                    $('#projectName').change(function () {
                        var project_name = $(this).val();
                        $.ajax({
                            type: 'POST',
                            url: "main_menu_ACT.php",
                            dataType: "JSON",
                            data: {project_name: project_name, "action": "change_subjob"},
                            beforeSend: function (xhr) {
                                $('#subcont').empty();
                                $('#table-subcont').DataTable().destroy();
                                $('#table-subcont tbody').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                var subcont = "<option value='' selected='' disabled=''></option>";
                                $.each(response, function (key, value) {
                                    subcont += "<option value='" + value.SUBCONT_ID + "'>" + value.SUBCONT_ID + "</option>"
                                });
                                $("#subcont").html(subcont).selectpicker('refresh');
                            }
                        });
                    });
                });

                function ChangeSubcont() {
                    var project_name = $('#projectName').val();
                    var subcont = $('#subcont').val();
                    $.ajax({
                        type: 'POST',
                        url: "main_menu_ACT.php",
                        dataType: "JSON",
                        data: {project_name: project_name, subcont: subcont, "action": "change_subcont"},
                        beforeSend: function (xhr) {
                            $('#table-subcont').DataTable().destroy();
                            $('#table-subcont tbody').empty();
                        },
                        success: function (response, textStatus, jqXHR) {
                            var content = "";
                            $.each(response, function (key, value) {
                                content += "<tr id='row" + key + "'>\n\
                                                <td class='text-center' id='headmark" + key + "'>" + value.HEAD_MARK + "</a></td>\n\
                                                <td class='text-center'>" + value.ASSIGNMENT_DATE + "</td>\n\
                                                <td class='text-center'><a href='#' data-pk='" + value.HEAD_MARK + "' data-id='" + value.ID + "' id='subcont" + key + "' data-type='select'>" + value.SUBCONT_ID + "</a></td>\n\
                                                <td class='text-center' id='subcontid" + key + "'>" + value.ID + "</td>\n\
                                                <td class='text-center'><a href='#' data-pk='" + value.HEAD_MARK + "' data-id='" + value.ID + "' id='qty" + key + "' data-type='number'>" + value.ASSIGNED_QTY + "</a></td>\n\
                                                <td class='text-center'><a href='#' data-pk='" + value.HEAD_MARK + "' data-id='" + value.ID + "' id='qc" + key + "' data-type='text'>" + value.QC_INSP + "</a></td>\n\
                                                <td class='text-center'><a href='#' data-pk='" + value.HEAD_MARK + "' data-id='" + value.ID + "' id='spv" + key + "' data-type='text'>" + value.SPV_FAB + "</a></td>\n\
                                                <td class='text-center'><button onclick=DeleteHM('" + key + "') type='button' class='btn btn-warning btn-sm'><span class='glyphicon glyphicon-trash'></span></button></td>\n\
                                            </tr>"
                            });
                            $('#table-subcont tbody').html(content);
                        },
                        complete: function (jqXHR, textStatus) {
                            $('#table-subcont').DataTable({
                                "drawCallback": function (settings) {
                                    $('a[id^=subcont]').editable({
                                        source: aSubcont,
                                        success: function (response, newValue) {
                                            var project_name = $('#projectName').val();
                                            var subcont_id = newValue;
                                            var element = $(this);
                                            var headmark = element.data("pk");
                                            var id = element.data("id");
                                            var sentReq = {
                                                project_name: project_name,
                                                subcont_id: subcont_id,
                                                headmark: headmark,
                                                id: id,
                                                action: "update_assign",
                                                type: "subcont"
                                            };
                                            console.log(sentReq);
                                            $.ajax({
                                                type: 'POST',
                                                url: "main_menu_ACT.php",
                                                data: sentReq,
                                                success: function (response, textStatus, jqXHR) {
                                                    if (response.indexOf("SUKSES") == -1) {
                                                        alert("GAGAL UPDATE");
                                                    }
                                                }
                                            });
                                        }
                                    });
                                    $('a[id^=qty]').editable({
                                        success: function (response, newValue) {
                                            var project_name = $('#projectName').val();
                                            var qty = newValue;
                                            var element = $(this);
                                            var headmark = element.data("pk");
                                            var id = element.data("id");
                                            var sentReq = {
                                                project_name: project_name,
                                                qty: qty,
                                                headmark: headmark,
                                                id: id,
                                                action: "update_assign",
                                                type: "qty"
                                            };
                                            console.log(sentReq);
                                            $.ajax({
                                                type: 'POST',
                                                url: "main_menu_ACT.php",
                                                data: sentReq,
                                                success: function (response, textStatus, jqXHR) {
                                                    if (response.indexOf("SUKSES") == -1) {
                                                        alert("GAGAL UPDATE");
                                                    }
                                                }
                                            });
                                        }
                                    });
                                    $('a[id^=qc]').editable({
                                        mode: 'inline',
                                        showbuttons: false,
                                        success: function (response, newValue) {
                                            var project_name = $('#projectName').val();
                                            var qc = newValue;
                                            var element = $(this);
                                            var headmark = element.data("pk");
                                            var id = element.data("id");
                                            var sentReq = {
                                                project_name: project_name,
                                                qc: qc,
                                                headmark: headmark,
                                                id: id,
                                                action: "update_assign",
                                                type: "qc"
                                            };
                                            console.log(sentReq);
                                            $.ajax({
                                                type: 'POST',
                                                url: "main_menu_ACT.php",
                                                data: sentReq,
                                                success: function (response, textStatus, jqXHR) {
                                                    if (response.indexOf("SUKSES") == -1) {
                                                        alert("GAGAL UPDATE");
                                                    }
                                                }
                                            });
                                        }
                                    });
                                    $('a[id^=spv]').editable({
                                        mode: 'inline',
                                        showbuttons: false,
                                        success: function (response, newValue) {
                                            var project_name = $('#projectName').val();
                                            var spv = newValue;
                                            var element = $(this);
                                            var headmark = element.data("pk");
                                            var id = element.data("id");
                                            var sentReq = {
                                                project_name: project_name,
                                                spv: spv,
                                                headmark: headmark,
                                                id: id,
                                                action: "update_assign",
                                                type: "spv"
                                            };
                                            console.log(sentReq);
                                            $.ajax({
                                                type: 'POST',
                                                url: "main_menu_ACT.php",
                                                data: sentReq,
                                                success: function (response, textStatus, jqXHR) {
                                                    if (response.indexOf("SUKSES") == -1) {
                                                        alert("GAGAL UPDATE");
                                                    }
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }

                function DeleteHM(param) {
                    var project_name = $('#projectName').val();
                    var subcont = $('#subcont').val();
                    var headmark = $('#headmark' + param).text().trim();
                    var id = $('#subcontid' + param).text().trim();
                    var sentReq = {
                        project_name: project_name,
                        headmark: headmark,
                        subcont: subcont,
                        id: id,
                        action: "delete"
                    };
                    console.log(sentReq);
                    var cf = confirm("DELETE THIS HEAD MARK?");
                    if (cf == true) {
                        $.ajax({
                            type: 'POST',
                            url: "main_menu_ACT.php",
                            data: sentReq,
                            success: function (response, textStatus, jqXHR) {
                                if (response.indexOf("SUKSES") == -1) {
                                    alert("GAGAL DELETE");
                                } else {
                                    $('#table-subcont').DataTable().row('.selected').remove().draw(false);
                                }
                            }
                        });
                    } else {
                        return false;
                    }
                }
            </script>
        </div> <!-- panel-default -->
    </body>
</html>    