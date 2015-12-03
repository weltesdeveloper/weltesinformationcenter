<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';

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
<html>
    <head>
        <meta charset="UTF-8">
        <title>WELTES | OPNAME FABRICATION FIX</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../../AdminLTE/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- font Awesome -->
        <link href="../../AdminLTE/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

        <!-- Ionicons -->
        <link href="../../AdminLTE/css/ionicons.min.css" rel="stylesheet" type="text/css"/>

        <!-- Theme style -->
        <link href="../../AdminLTE/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
        <link href="../../css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <link href="../../AdminLTE/css/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="../../AdminLTE/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
        <!--<link href="../../AdminLTE/css/fileinput.min.css" rel="stylesheet" type="text/css"/>-->

        <!-- jQuery 2.0.2 -->
        <script src="../../jQuery/jquery-2.1.1.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="../../AdminLTE/js/bootstrap.min.js" type="text/javascript"></script>
        <!--<script src="../../js/bootstrap.min.js" type="text/javascript"></script>-->
        <!-- AdminLTE App -->
        <script src="../../AdminLTE/js/AdminLTE/app.js" type="text/javascript"></script>
        <!--<script src="../../js/AdminLTE/app.js" typ  e="text/javascript"></script>-->
        <!-- AdminLTE for demo purposes -->
        <script src="../../AdminLTE/js/AdminLTE/demo.js" type="text/javascript"></script>
        <!--<script src="../../js/AdminLTE/demo.js" type="text/javascript"></script>-->
        <script src="../../js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="../../AdminLTE/js/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../../AdminLTE/js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <!--<script src="../../AdminLTE/js/fileinput.min.js" type="text/javascript"></script>-->
        <script>
            component("DRAWING_INPUT");
            function component(param) {
                switch (param) {
                    case "NON_DRAWING":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "non_drawing/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("INPUT OPNAME SPECIAL ITEM NON DRAWING<br><small>(Pembuatan Meja, Kursi, dll)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#593E25");
                            }
                        });
                        break;
                    case "NON_DRAWING_REV":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "non_drawing_revisi/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("REVISI OPNAME SPECIAL ITEM NON DRAWING<br><small>(Pembuatan Meja, Kursi, dll)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#B800C9");
                            }
                        });
                        break;
                    case "NON_DRAWING_PRINT":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "non_drawing_print/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("PRINT OPNAME SPECIAL ITEM NON DRAWING<br><small>(Pembuatan Meja, Kursi, dll)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#2EC9C4");
                            }
                        });
                        break;
                    case "SPECIAL_DRAWING":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "special_drawing/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("INPUT OPNAME SPECIAL ITEM DRAWING<br><small>(Penambahan Harga, King Cross, Refabrikasi)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#593E25");
                            }
                        });
                        break;
                    case "SPECIAL_DRAWING_REV":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "special_drawing_revisi/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("REVISI OPNAME SPECIAL ITEM DRAWING<br><small>(Penambahan Harga, King Cross, Refabrikasi)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#B800C9");
                            }
                        });
                        break;
                    case "SPECIAL_DRAWING_PRINT":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "special_drawing_print/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("PRINT OPNAME SPECIAL ITEM DRAWING<br><small>(Penambahan Harga, King Cross, Refabrikasi)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#2EC9C4");
                            }
                        });
                        break;

                        //BAGIAN DRAWING QC
                    case "DRAWING_INPUT":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "normal_drawing/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("INPUT OPNAME DRAWING<br><small>(Drawing Yang Melalui QC Pass Terlebih Dulu)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#593E25");
                            }
                        });
                        break;

                    case "DRAWING_REVISI":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "normal_drawing_revisi/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("REVISI OPNAME DRAWING<br><small>(Drawing Yang Melalui QC Pass Terlebih Dulu)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#B800C9");
                            }
                        });
                        break;
                        
                        case "DRAWING_PRINT":
                        $('#maincontent').empty();
                        $('#input-content').empty();
                        $.ajax({
                            type: 'POST',
                            url: "normal_drawing_print/change_element.php",
                            beforeSend: function (xhr) {
                                $('#maincontent').empty();
                                $('#input-content').empty();
                            },
                            success: function (response, textStatus, jqXHR) {
                                $('#maincontent').html(response);
                                $('#header-maincontent').html("PRINT OPNAME DRAWING<br><small>(Drawing Yang Melalui QC Pass Terlebih Dulu)</small>");
                                $('#input-content').empty();
                                $('.navbar, .logo').css("background-color", "#2EC9C4");
                            }
                        });
                        break;
                }
            }
        </script>
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="#" class="logo">
                OPNAME MENU
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="col-sm-2">
                    <span class="text-center col-sm-12" style="color: white;">
                        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button" style="color: white; margin-top: -8px;">
                            <i class="fa fa-navicon"></i><span class="sr-only">Toggle navigation</span>
                        </a>
                    </span>
                </div>
                <div class="col-sm-10">
                    <span class="text-center col-sm-8" id="header-maincontent" style="color: white; font-size: 18px;"></span>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-sun-o"></i> <span>DRAWING QC PASS<br>
                                    <small>(Drawing Yang Melalui QC Pass Terlebih Dulu)</small></span>
                                <i class="fa fa-angle-double-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a onclick="component('DRAWING_INPUT')" style="cursor: pointer;">
                                        <i class="fa fa-asterisk"></i> <span>INPUT</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('DRAWING_REVISI')" style="cursor: pointer;">
                                        <i class="fa fa-angellist"></i> <span>REVISI</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('DRAWING_PRINT')" style="cursor: pointer;">
                                        <i class="fa fa-print"></i> <span>PRINT</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </section>
                <section class="sidebar">
                    <ul class="sidebar-menu">

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-spotify"></i> <span>SPECIAL DRAWING<br>
                                    <small>(Penambahan Harga, King Cross, Refabrikasi)</small></span>
                                <i class="fa fa-angle-double-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a onclick="component('SPECIAL_DRAWING')" style="cursor: pointer;">
                                        <i class="fa fa-asterisk"></i> <span>INPUT</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('SPECIAL_DRAWING_REV')" style="cursor: pointer;">
                                        <i class="fa fa-angellist"></i> <span>REVISI</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('SPECIAL_DRAWING_PRINT')" style="cursor: pointer;">
                                        <i class="fa fa-print"></i> <span>PRINT</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </section>
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-building"></i> <span>NON DRAWING
                                    <br><small>(Pembuatan Meja, Kursi, dll)</small></span>
                                <i class="fa fa-angle-double-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a onclick="component('NON_DRAWING')" style="cursor: pointer;">
                                        <i class="fa fa-compass"></i> <span>INPUT</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('NON_DRAWING_REV')" style="cursor: pointer;">
                                        <i class="fa fa-pinterest"></i> <span>REVISI</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="component('NON_DRAWING_PRINT')" style="cursor: pointer;">
                                        <i class="fa fa-print"></i> <span>PRINT</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </section>
            </aside>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <section class="content" id="maincontent">
                    <div class="col-sm-12" id="header-maincontent"></div>
                </section><!-- /.content -->
                <section class="content" id="input-content">

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <div class="form-group" id="detail-opname" style="width: 98%; margin-left: 15px;"></div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Modal with Dark Overlay</h4>
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

