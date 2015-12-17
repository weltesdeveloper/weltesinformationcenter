<?php
require_once './dbinfo.inc.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.php">LOGIN PAGE</a><p>
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
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Main Menu</title>

        <link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
            <link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
            <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
            <link rel="manifest" href="images/manifest.json">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="images/ms-icon-144x144.png">
            <meta name="theme-color" content="#ffffff">
        
        <meta name="description" content="Blueprint: Horizontal Slide Out Menu" />
        <meta name="keywords" content="horizontal, slide out, menu, navigation, responsive, javascript, images, grid" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/mainmenu/default.css" />
        <link rel="stylesheet" type="text/css" href="css/mainmenu/component.css" />
        <script src="js/modernizr.custom.js"></script>
    </head>
    <body>
        <div class="container">
            <header class="clearfix">
                <span>PT. Weltes Energi Nusantara <span class="bp-icon bp-icon-archive" data-content="Logged in as <?php echo $username ?>"></span></span>
                <h1>Information Center Gateway</h1>
                <nav>
                    <a href="" class="bp-icon bp-icon-prev" data-info="previous Blueprint"><span></span></a>
                    <a href="" class="bp-icon bp-icon-next" data-info="next Blueprint"><span></span></a>
                    <a href="" class="bp-icon bp-icon-drop" data-info=""><span></span></a>
                    <a href="index.php" class="bp-icon bp-icon-archive" data-info="Sign Out"><span></span></a>
                </nav>
            </header>	
            <div class="main">
                <nav class="cbp-hsmenu-wrapper" id="cbp-hsmenu-wrapper">
                    <div class="cbp-hsinner">
                        <ul class="cbp-hsmenu">
                            <li><a target="_blank" href="/WeltesInformationCenter/component/componentmanagement.php">Component</a></li>
                            <li <?php if ($username == "wahyu") echo "disabled"; ?>>
                                <a href="#">Administrator</a>
                                <ul class="cbp-hssubmenu">
                                    <li>
                                        <a target="_blank" href="MasterDrawingRevision_1/insertHeadmark.php">
                                            <img src="images/insert.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>INSERT NEW HEADMARK</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="MasterDrawingRevision/mdrevisionIndex.php">
                                            <img src="images/revision.jpg" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>REVISE HEADMARK</span></a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="subcontAssignment/Global/subcontAssignmentIndex.php">
                                            <img src="images/assign.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>ASSIGN DRAWING</span></a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="SubcontRevision/main_menu.php">
                                            <img src="images/revision2.jpg" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>REVISION SUBCONT</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Process Division</a>
                                <ul class="cbp-hssubmenu cbp-hssub-rows">
                                    <li>
                                        <a target="_blank" href="Content/Fabrication/Update/Global/update_fabrication.php">
                                            <img src="images/fabrication.jpg" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>FABRICATION</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="Content/opname_special_item/input_item.php">
                                            <img src="images/opname2.jpg" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>FABRICATION OPNAME</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="Content/Painting/Update/Global/update_painting.php">
                                            <img src="images/painting.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>PAINTING</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="Content/opname_painting/input_opname_painting.php">
                                            <img src="images/opname.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>PAINTING OPNAME</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="packingAssignment/packingAssignmentIndexNEW.php">
                                            <img src="images/packing.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>PACKING</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="deliveryAssignment/deliveryAssignment.php">
                                            <img src="images/dlv.png" alt="img01" style="width: 100px; height: 100px;"/>
                                            <span>DELIVERY</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a target="_blank" href="AdminLTE/adminLogin.php">Monitoring</a></li>
                            <li><a target="_blank" href="/WeltesSiteMonitoring/index.php">Erection</a></li>
                            <li><a target="_blank" href="/WeltesTankage/index.php">Tankage</a></li>
                            <li>
                                <a href="#">Non Product</a>
                                <ul class="cbp-hssubmenu cbp-hssub-rows">
                                    <!--<li><a target="_blank" href="packingAssignmentNonProduct/packingAssignmentIndexNEW.php"><img src="images/01.png" alt="img01"/><span>PACKING LAIN LAIN</span></a></li>-->
                                    <!--<li><a target="_blank" href="packingAssignmentNonProduct/packingAssignmentRev.php"><img src="images/01.png" alt="img01"/><span>REV PACKING LAIN LAIN</span></a></li>-->
                                    <li>
                                        <a target="_blank" href="deliveryAssignment_NonProduct/deliveryAssignment.php">
                                            <img src="images/02.png" alt="img02"/><span>DELIVERY LAIN LAIN</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <script src="js/cbpHorizontalSlideOutMenu.min.js"></script>
        <script>
            var menu = new cbpHorizontalSlideOutMenu(document.getElementById('cbp-hsmenu-wrapper'));
        </script>
    </body>
</html>
