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

// HAK AKSES
$DELIV_ACCS = HakAksesUser($username, 'DELIV_ACCS', $conn);
if ($DELIV_ACCS <> 1) {
    # code...
    echo <<< EOD
       <h1>You Can't ACCESS DELIVERY PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>WELTES | Component</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../AdminLTE/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../AdminLTE/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../AdminLTE/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
        <link href="../AdminLTE/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
        <link href="../AdminLTE/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
        <link href="../AdminLTE/css/fileinput.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="divpages/dist/sweetalert.css">
        <link href="bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css"/>
        <style>
/*            input, button, select, textarea {
                font-family: inherit;
                font-size: inherit; 
                height: 33px;
                line-height: inherit;
                width: 220px;
            }*/

            table.dataTable thead th, table.dataTable thead td {
                border-bottom: 1px solid #111;
                padding: 10px 10px;
            }

            .btn.btn-file {
                height: 57px;
                overflow: hidden;
                position: relative;
                width: 139px;
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="../jQuery/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/AdminLTE/demo.js" type="text/javascript"></script>
        <script src="../js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/fileinput.min.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="../AdminLTE/js/plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
        <script src="divpages/dist/sweetalert-dev.js"></script>         
        <script src="bootstrap-editable/js/bootstrap-editable.js" type="text/javascript"></script>
        <script src="bootstrap-editable/js/bootstrap-editable.min.js" type="text/javascript"></script>
        
        <!--<link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>-->
        <!--<script src="../jQuery/jquery-2.1.1.min.js"></script>-->
        <!--<script src="../AdminLTE/js/plugins/datatables/jquery.dataTables.min.js"></script>-->


    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="../../index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                COMPONENT <span id="userINP" class="hidden"><?php echo $username ?></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="selectpicker" name="projNo" id="job" data-live-search="true">
                                <?php
                                $projectNameSql = "SELECT DISTINCT PROJECT_TYP FROM VW_PROJ_INFO WHERE PROJECT_TYP in ('STRUCTURE','TANKAGE')  ORDER BY PROJECT_TYP";
                                $projectNameParse = oci_parse($conn, $projectNameSql);
                                oci_execute($projectNameParse);
                                echo '<option value="" selected disabled>Select JOB</option>';
                                while ($row = oci_fetch_array($projectNameParse)) {
                                    echo "<optgroup label='" . $row['PROJECT_TYP'] . "'>";
                                    $projSQL = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP in ('" . $row['PROJECT_TYP'] . "')  ORDER BY PROJECT_NO";
                                    $projPARSE = oci_parse($conn, $projSQL);
                                    oci_execute($projPARSE);
                                    while ($projROW = oci_fetch_array($projPARSE)) {
                                        $proj = $projROW['PROJECT_NO'];

                                        echo "<option value='$proj'>$proj</option>";
                                    }
                                    echo "</optgroup>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" id="div-subjob">
                            <select class="selectpicker" data-live-search="true" id="subjob" onchange="ChangeSubjob();">
                                <option value="" selected disabled>Select SUBJOB</option>
                            </select>
                        </div>
                    </div>

                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <br/>   
                        <table id="drawingTable" class="table table-condensed">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Assign Component</th>
                                    <th style="width: 3%;"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <br/>
                        <li class="treeview">
                            <a style="cursor: pointer; margin-bottom: -8px;">
                                <i class="fa fa-desktop"></i> <span>Component Monitor</span>
                                <ul class="treeview-menu menu-open" style="display: block; margin-top: 0;">
                                    <li><a onclick="component('MONITOR_COMP_ASIGN')" style="cursor: pointer;"><i class="fa fa-angle-double-right"></i> By Assign</a></li>
                                    <li><a onclick="component('MONITOR_COMP_STOCK')" style="cursor: pointer;"><i class="fa fa-angle-double-right"></i> By Plat</a></li>
                                </ul>
                            </a>
                        </li>
                        <li>
                            <a onclick="component('IMPORT')" style="cursor: pointer;">
                                <i class="fa fa-cloud-download"></i> <span>Component Import</span>
                            </a>
                        </li>
                        <li>
                            <a onclick="component('REVISI')" style="cursor: pointer;">
                                <i class="fa fa-pencil"></i> <span>Component Rev</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a style="cursor: pointer; margin-bottom: -8px;">
                                <i class="fa fa-bank"></i> <span>Component Stock</span>
                                <ul class="treeview-menu menu-open" style="display: block; margin-top: 0;">
                                    <li><a onclick="component('INPUT_CUTT')" style="cursor: pointer;" ><i class="fa fa-angle-double-right"></i> Cutting</a></li>
                                    <li><a onclick="component('INPUT_FINSH')" style="cursor: pointer;"><i class="fa fa-angle-double-right"></i> Finishing</a></li>
                                </ul>
                            </a>
                        </li>
                        <li>
                            <a onclick="component('LIST')" style="cursor: pointer;">
                                <i class="fa fa-file-text"></i> <span>Component List</span>
                            </a>
                        </li>
                        <!--
                        <li>
                            <a onclick="component('MOVEMENT')" style="cursor: pointer;">
                                <i class="fa fa-exchange"></i> <span>Component Transfer</span>
                            </a>
                        </li>
                        -->
                        <li class="treeview">
                            <a style="cursor: pointer; margin-bottom: -8px;">
                                <i class="fa fa-exchange"></i> <span>Waste Bank</span>
                                <ul class="treeview-menu menu-open" style="display: block; margin-top: 0;">
                                    <li><a onclick="component('WASTE_MANUAL')" style="cursor: pointer;" ><i class="fa fa-angle-double-right"></i> Input</a></li>
                                    <li><a onclick="component('LIST_WASTE')" style="cursor: pointer;"><i class="fa fa-angle-double-right"></i> List</a></li>
                                </ul>
                            </a>
                        </li>
                        <li>
                            <a onclick="component('LOGOUT')" style="cursor: pointer;">
                                <i class="fa fa-power-off" style="color: #D90005;"></i> <span>Logout Component</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->

                <!-- Main content -->
                <section class="content" id="maincontent"></section><!-- /.content -->

            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
    </body>

    <script type="text/javascript">
        var section_elemnt = $('section.sidebar');
        $(function () {
            section_elemnt.find('#job').change(function () {
                var job = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: "divpages/change_element.php",
                    data: {job: job, action: "subjob"},
                    success: function (response, textStatus, jqXHR) {
                        $('#div-subjob').html(response);
                    }
                });
            });
        });

        function component(param) {
            var job = section_elemnt.find("#job").val();
            var subjob = section_elemnt.find("#subjob").val();

            switch (param) {
                case "MONITOR_COMP_ASIGN":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/monitorcomponent.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                case "MONITOR_COMP_STOCK":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/monitorcompstock.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                case "IMPORT":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/importcomponent.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                case "REVISI":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/rev_comp.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE

                case "INPUT_CUTT":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/inputstockcomponent.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                case "INPUT_FINSH":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/inputstockcomponectFinsh.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE

                case "LIST":
                    if (confirm('Are you sure you want to navigate away?')) {
                        $.ajax({
                            type: 'POST',
                            url: "divpages/listcomponent.php",
                            data: {job: job, subjob: subjob},
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                case "WASTE_MANUAL":
                    if (confirm('Are you sure you want to navigate away?')) {
                        var user = $('#userINP').text();
                        $.ajax({
                            type: 'POST',
                            url: "divpages/WasteManualInput.php",
                            data: {job: job, subjob: subjob, user__:user},
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE
                    
                    
                case "LIST_WASTE":
                    if (confirm('Are you sure you want to navigate away?')) {
                        var user = $('#userINP').text();
                        $.ajax({
                            type: 'POST',
                            url: "divpages/list_waste.php",
                            data: {job: job, subjob: subjob, user__:user},
                            beforeSend: function (xhr) {
                                $('#maincontent').html();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                            }
                        });
                    }
                    break; // END OF CASE


            }


        }

    </script>
</html>

