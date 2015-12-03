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
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-formhelpers.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css" />
        <link rel="stylesheet" type="text/css" href="revisionCss/own.css">

        <!--         DROPZONE.JS CSS 
                <link href="revisionCss/basic.css" rel="stylesheet" type="text/css"/>
                <link href="revisionCss/dropzone.css" rel="stylesheet" type="text/css"/>-->

        <!-- BootstrapValidator CSS -->
        <link rel="stylesheet" href="revisionCss/bootstrapValidator.min.css"/>
        <!-- file INput CSS -->
        <link href="../dist/bootstrap-fileinput-master/css/fileinput.min.css" rel="stylesheet">
        <!-- DATA TABLE CSS -->
        <link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .dropdown-header{
                font-style: italic;
                font-weight: bold !important;
                color:#bc0000 !important;
                background:#DDDDDD;
            }
        </style>


        <!-- JS SRC -->
        <script src="../jQuery/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap-select.min.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/subcontDropdownNEW.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/bootstrap-formhelpers.js"></script>

        <!-- FIlter JS -->
        <!-- // <script type="text/javascript" src="revisionJs/jcfilter.min.js"></script> -->
        <!-- DATA TABLES SCRIPT -->
        <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <!-- BootstrapValidator JS -->
        <script type="text/javascript" src="revisionJs/bootstrapValidator.min.js"></script>
        <!-- DropDown -->
        <script src="../js/bootstrap-dropdown.js"></script>
        <!-- input file -->
        <script src="../dist/bootstrap-fileinput-master/js/fileinput.min.js"></script>

        <!-- REVISION JS 
                <script src="revisionJs/dropzone.js"></script>-->

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function doSubmit() {
                var searchTRGET = $("input[type='search'][aria-controls='listHM2']");
                if (searchTRGET.val() != "") {
                    alert("Please Remove Search Filter TARGET");
                    setTimeout(function() {
                        searchTRGET.focus()
                    }, 500);
                    return false;
                } else {
                    if (confirm('Are you sure you want to submit PACKING ASSIGNMENT Data?')) {
                        // yes
                        return true;
                    } else {
                        // Do nothing!
                        return false;
                    }
                }
            }

            // function expandTextarea() {
            //     var $element = $('#PickList').get(0);  

            //     // $element.addEventListener('change', function() {
            //         $element.style.overflow = 'hidden';
            //         $element.style.height = 0;
            //         $element.style.height = $element.scrollHeight + 'px';
            //     // }, false);
            // }

            function valChek() {
                // body...
                $("#ValInput").hide();
                $('input[type="submit"]').attr('disabled', 'disabled');
            }

            function PopupCenter(pageURL, title, w, h) {
                var left = (screen.width / 2) - (w / 2);
                var top = (screen.height / 2) - (h / 2);
                var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no,status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
                targetWin.focus();
            }
        </script>

    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>PACKING</b></font> | <font color="#CC0000" size="5"><b>  ASSIGNMENT UPDATE</b></font></h3>
            </div> <!-- panel heading   data-bv-feedbackicons-valid="glyphicon glyphicon-ok"    data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"    data-bv-feedbackicons-validating="glyphicon glyphicon-refresh"-->
            <div class="panel-body">
                <form class="form-horizontal dropzone" id="attributeForm" role="form" data-bv-message="This value is not valid" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return doSubmit();"   enctype="multipart/form-data">

                    <div class="form-group">   
                        <label for="packingDate" class="col-sm-2 control-label"><font color="black">PACKING DATE</font></label>
                        <div class="col-sm-10">
                            <div id="packingDate" data-name="packingDate" class="bfh-datepicker" data-date="today"></div>
                        </div>

                        <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="projName" id="projName" data-live-search="true" onChange="valChek();
                                    getHeadmark(this.value);">
                                <option value="" selected disabled>[select project]</OPTION>
                                <?php
                                $proj_parse = oci_parse($conn, "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO");
                                oci_execute($proj_parse);
                                while ($row1 = oci_fetch_array($proj_parse)) {
                                    echo "<optgroup label='$row1[PROJECT_NO]'>";

                                    $sql_project = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' AND PROJECT_NO = '$row1[PROJECT_NO]' ORDER BY PROJECT_NAME_NEW";
                                    $proj_result = oci_parse($conn, $sql_project);
                                    oci_execute($proj_result);
                                    while ($row = oci_fetch_array($proj_result)) {
                                        $proj = $row['PROJECT_NAME_NEW'];

                                        $projNmLma = $row['PROJECT_NAME_OLD'];

                                        echo "<OPTION VALUE='$projNmLma'>$proj</OPTION>";
                                    }

                                    echo '</optgroup>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="packingAssignmentHeadmark"></div>
                    <div class="form-group" id="packingAssignmentElements"></div>

                    <div class="form-group" id="ValInput" style="display:none;">
                        <label for="length" class="col-sm-2 control-label"><font color="green">LENGTH</font></label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="coliLength" name="coliLength" placeholder="" value="" min="0" data-bv-notempty="true" data-bv-notempty-message="LENGTH is required and cannot be empty"></input>
                        </div>

                        <label for="width" class="col-sm-2 control-label"><font color="green">WIDTH</font></label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="coliWidth" name="coliWidth" placeholder="" value="" min="0" data-bv-notempty="true" data-bv-notempty-message="WIDTH is required and cannot be empty"></input>
                        </div>

                        <label for="height" class="col-sm-2 control-label"><font color="green">HEIGHT</font></label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="coliHeight" name="coliHeight" placeholder="" value="" min="0" data-bv-notempty="true" data-bv-notempty-message="HEIGHT is required and cannot be empty"></input>
                        </div>

                        <label for="name" class="col-sm-2 control-label">PACKAGE TYPE</label>
                        <div class="col-sm-10">
                            <?php
                            $packageSql = "SELECT * FROM PACKAGETYPE";
                            $packageParse = oci_parse($conn, $packageSql);
                            oci_execute($packageParse);

                            echo '<select class="form-control" name="packageAssign" id="packageAssign" data-bv-notempty="true" data-bv-notempty-message="PACKAGE TYPE is required and cannot be empty">' . '<br>';
                            echo '<option value=" ">' . "" . '</OPTION>';

                            while ($row = oci_fetch_array($packageParse, OCI_ASSOC)) {
                                $package = $row['TYPE'];
                                echo "<OPTION VALUE='$package'>$package</OPTION>";
                            }
                            echo '</select>';
                            ?>
                        </div>

                        <div id="PnlPckWT">
                            <label for="PackWeight" id="PackWeight" class="col-sm-2 control-label"><font color="green">PACKAGE WEIGHT</font></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="PackWT" name="PackWT" placeholder="" value="0" min="0" data-bv-notempty="true" data-bv-notempty-message="PACKAGE WEIGHT is required and cannot be empty"></input>
                            </div>
                        </div>

                        <label for="zone" class="col-sm-2 control-label"><font color="green">ZONE</font></label>
                        <div class="col-sm-10">
                            <div class="row hide">
                                <div class="col-sm-1" style="width:150px;">
                                    <div class="input-group">
                                        <div class="input-group-btn btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span data-bind="zone1">A</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">A</a></li>
                                                <li><a href="#">B</a></li>
                                                <li><a href="#">C</a></li>
                                                <li><a href="#">D</a></li>
                                                <li><a href="#">E</a></li>
                                                <li><a href="#">F</a></li>
                                                <li><a href="#">G</a></li>
                                                <li><a href="#">H</a></li>
                                                <li><a href="#">I</a></li>
                                                <li><a href="#">J</a></li>
                                                <li><a href="#">K</a></li>
                                                <li><a href="#">L</a></li>
                                                <li><a href="#">M</a></li>
                                                <li><a href="#">N</a></li>
                                                <li><a href="#">O</a></li>
                                                <li><a href="#">P</a></li>
                                                <li><a href="#">Q</a></li>
                                                <li><a href="#">R</a></li>
                                                <li><a href="#">S</a></li>
                                                <li><a href="#">T</a></li>
                                                <li><a href="#">U</a></li>
                                                <li><a href="#">V</a></li>
                                                <li><a href="#">W</a></li>
                                                <li><a href="#">X</a></li>
                                                <li><a href="#">Y</a></li>
                                                <li><a href="#">Z</a></li>

                                                <!-- <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li> -->
                                            </ul>
                                        </div><!-- /btn-group -->
                                        <input type="number" class="form-control" name="zone2" min="0" value="0">
                                    </div><!-- /input-group -->
                                </div><!-- /.col-lg-6 -->

                                <div class="col-sm-1" style="width:90px;" >
                                    <div class="input-group">
                                        <select class="form-control selected" name="zone3">
                                            <option value="B" selected="">B</option>
                                            <option value="T">T</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span data-bind="zone4">C.</span>
                                        </span>
                                        <input type="text" name="zone4" class="form-control" value="000" maxlength="3">
                                    </div><!-- /input-group -->
                                </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->
                            <input name="zoneArea" id="zoneArea" type="text" class="form-control" maxlength="6" data-bv-notempty="true" data-bv-notempty-message="ZONE is required and cannot be empty">
                        </div>

                        <label for="height" class="col-sm-2 control-label"><font color="blue">COLI IMAGE</font></label>
                        <div class="col-sm-10">
                            <input id="img_pack" name="img_pack[]" type="file" multiple="multiple" accept="image/*" >
                        </div>

                    </div>

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" name="btnSubmit" class="btn btn-success btn-sm" >
                                <input type="reset" class="btn btn-default btn-sm" onclick="">
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  
        </div> <!-- panel-default -->


        <!-- JS On Load -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('#attributeForm').bootstrapValidator();
                $('#projName').selectpicker();
                $('select[name=packageAssign]').change(
                        function() {
                            var newText = $('option:selected', this).text();
                            $('#PackWeight').text(newText + ' WEIGHT');
                        }
                );

                $('input[type="submit"]').attr('disabled', 'disabled').on('click', function() {
                    var imgPick = $('#img_pack').val();
                    if (imgPick == '') {
                        alert('Please Upload COLI Image');
                        return false;
                    }
                });

                $('input[type="text"]').keyup(function() {
                    var numberOfChecked = $("input[name='chkHM[]']:checkbox:checked").length;

                    if ($(this).val() !== '' && numberOfChecked > 0) {
                        $('input[type="submit"]').removeAttr('disabled');
                    }
                });

                $("#img_pack").fileinput({
                    showUpload: false,
                    maxFileCount: 3,
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

            $(document.body).on('click', '.dropdown-menu li', function(event) {

                var $target = $(event.currentTarget);

                $target.closest('.btn-group')
                        .find('[data-bind="zone1"]').text($target.text())
                        .end()
                        .children('.dropdown-toggle').dropdown('toggle');

                return false;

            });

            $('#zoneArea').keyup(function() {
                this.value = this.value.toUpperCase();
            });

        </script>
    </body>

    <?php

    function resize_image($file, $filename, $w, $h, $crop = FALSE) {
        list($width, $height) = getimagesize($file);
        // echo $filename;
        $r = $width / $height;
        // echo $width.' -- '.$height." == $r <br>";
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        //        echo "$newwidth -- $newheight <br>";
        $resizedFile = 'img_packing/' . $filename;

        //call the function (when passing path to pic)
        $img = smart_resize_image($file, null, $newwidth, $newheight, TRUE, $resizedFile, false, false, 100);
        //call the function (when passing pic as string)
        //        $img = smart_resize_image(null, file_get_contents($file), $newwidth, $newheight, true, $resizedFile, false, false, 100);
        //    $src = imagecreatefromjpeg($file);
        //    echo $src;
        //    $dst = imagecreatetruecolor($newwidth, $newheight);
        //    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $img;
    }

//$img = resize_image('img_packing/weltesLogo.jpg', 200, 200);
//done!

    if (isset($_POST['btnSubmit'])) {

        // echo "Dalam Perbaikan";exit();

        $projName = strval($_POST['projName']);
        $coliNo = trim(strval($_POST['coliNumber']));
        $coliLength = intval($_POST['coliLength']);
        $coliWidth = intval($_POST['coliWidth']);
        $coliHeight = intval($_POST['coliHeight']);
        $actualPacking = strval($_POST['packingDate']);
        $actualPackingDate = date('m-d-Y h:i:s', strtotime($actualPacking));
        $packageType = strval($_POST['packageAssign']);
        $PackWT = intval($_POST['PackWT']);
        $zoneArea = strval($_POST['zoneArea']);
        // $PCKPrntSze            = strval($_POST['PCKPrntSze']);

        $coliVol = $coliLength * $coliWidth * $coliHeight;

        $jmlPck = SingleQryFld("SELECT COUNT(*) FROM MST_PACKING WHERE COLI_NUMBER='$coliNo' AND PCK_STAT='ACTIVE'", $conn);
        if ($jmlPck > 0) {
            # code...
            echo "COLI NUMBER $coliNo Already EXIST!";
            exit();
        }

        // Delete Coli Exist
        $delMstPckPARSE = oci_parse($conn, "DELETE FROM MST_PACKING WHERE COLI_NUMBER='$coliNo' AND PCK_STAT = 'INACTIVE'");
        $delMstPckRES = oci_execute($delMstPckPARSE);
        $delDtlPckPARSE = oci_parse($conn, "DELETE FROM DTL_PACKING WHERE COLI_NUMBER='$coliNo'");
        $delDtlPckRES = oci_execute($delDtlPckPARSE);
        if ($delDtlPckRES && $delMstPckRES) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }

        //insert to Master Packing
        // $insertColiSql = "BEGIN MST_PACK_INS($coliNo, $coliLength, $coliHeight, $coliWidth, $coliVol, $username, $packageType, to_date(SYSDATE,'MM/DD/YYYY hh24:mi:ss'), $projName, to_date('". $actualPackingDate ."','MM/DD/YYYY hh24:mi:ss'), $PackWT); END;";
        // echo "$insertColiSql"; exit();
        $insertColiSql = "BEGIN MST_PACK_INS(:C_NUM, :PCK_LEN, :PCK_HT, :PCK_WID, :PCK_SIGN, :PCK_TYP, :PROJNAME, to_date(:ACT_PCK_DATE,'MM/DD/YYYY hh24:mi:ss'), :B_WT, :Z_ONE); END;";
        

        $insertColiParse = oci_parse($conn, $insertColiSql);

        oci_bind_by_name($insertColiParse, ":C_NUM", $coliNo);
        oci_bind_by_name($insertColiParse, ":PCK_LEN", $coliLength);
        oci_bind_by_name($insertColiParse, ":PCK_HT", $coliHeight);
        oci_bind_by_name($insertColiParse, ":PCK_WID", $coliWidth);
        oci_bind_by_name($insertColiParse, ":PCK_SIGN", $username);
        oci_bind_by_name($insertColiParse, ":PCK_TYP", $packageType);
        // oci_bind_by_name($insertColiParse, ":PCK_DATE", SYSDATE);
        oci_bind_by_name($insertColiParse, ":PROJNAME", $projName);
        oci_bind_by_name($insertColiParse, ":ACT_PCK_DATE", $actualPackingDate);
        oci_bind_by_name($insertColiParse, ":B_WT", $PackWT);
        oci_bind_by_name($insertColiParse, ":Z_ONE", $zoneArea);


        $insertColiRes = oci_execute($insertColiParse);
        if ($insertColiRes) {
            // insert ke master packing detail
            $jmlSelectHM = intval($_POST['totTRGET']);
            echo "Jumlah Insert $jmlSelectHM<br>";
            for ($i = 0; $i <= $jmlSelectHM; $i++) {
                # code...
                if (isset($_REQUEST["HM$i"])) {
                    $HM = $_REQUEST["HM$i"];
                    $AssgnQty = intval($_REQUEST['AsignQty' . $i]);
                    $ActQty = intval($_REQUEST['ActQty' . $i]);
                    

                    $insertDtlColiSQL = "BEGIN DTL_PACK_INS(:C_NUM, :H_MARK, :UNT_PCK_QTY); END;";

                    $insertDtlColiPARSE = oci_parse($conn, $insertDtlColiSQL);

                    oci_bind_by_name($insertDtlColiPARSE, ":C_NUM", $coliNo);
                    oci_bind_by_name($insertDtlColiPARSE, ":H_MARK", $HM);
                    oci_bind_by_name($insertDtlColiPARSE, ":UNT_PCK_QTY", $AssgnQty);

                    $insertDtlColiRES = oci_execute($insertDtlColiPARSE);

                    if ($insertDtlColiRES) {
                        // oci_commit($conn);
                        echo "$i. $HM --assign = $AssgnQty -- total = $ActQty SUCCESS<br>";
                        // Update Status Prepacking List
                        if ($ActQty == $AssgnQty) {
                            # code...
                            // echo "Update Prepacking List Jd PACKED >> ";
                            // $updatePackingStatusAndColiSql = "UPDATE PREPACKING_LIST SET PACKING_STATUS = 'P' "
                            //     . "WHERE PROJECT_NAME = :PROJNAME AND HEAD_MARK = :HEADMARK";

                            $updatePackingStatusAndColiSql = "BEGIN UPD_PREPACK_LST_STAT_P(:H_MARK, :PROJNAME); END;";
                        } else {
                            // echo "Update Prepacking List jd PARCIAL PACKED >>";
                            // $updatePackingStatusAndColiSql = "UPDATE PREPACKING_LIST SET PACKING_STATUS = 'PP' "
                            // . "WHERE PROJECT_NAME = :PROJNAME AND HEAD_MARK = :HEADMARK";

                            $updatePackingStatusAndColiSql = "BEGIN UPD_PREPACK_LST_STAT_PP(:H_MARK, :PROJNAME); END;";
                        }
                        $updatePackingStatusAndColiParse = oci_parse($conn, $updatePackingStatusAndColiSql);

                        oci_bind_by_name($updatePackingStatusAndColiParse, ":PROJNAME", $projName);
                        oci_bind_by_name($updatePackingStatusAndColiParse, ":H_MARK", $HM);

                        $updatePackingStatusAndColiRes = oci_execute($updatePackingStatusAndColiParse);

                        if ($updatePackingStatusAndColiRes) {
                            // oci_commit($conn);
                            echo "UPD PREPACKING_LIST $HM SUCCESS<br>";
                        } else {
                            echo "UPD PREPACKING_LIST $HM FAILED<br>";
                            oci_rollback($conn);
                            exit();
                        }
                    } else {
                        echo "$i. $HM --assign = $AssgnQty -- total = $ActQty FAILED<br>";
                        oci_rollback($conn);
                        exit();
                    }
                }
            }

            // Loop through each file
            $jmlIMG = count($_FILES['img_pack']['name']);
            // echo 'jumlah image '.$jmlIMG;
            for ($i = 0; $i < $jmlIMG; $i++) {
                //Get the temp file path
                if ($_FILES['img_pack']['tmp_name'][$i] <> '') {
                    $tmpFilePath = $_FILES['img_pack']['tmp_name'][$i];
                    $path = $_FILES['img_pack']['name'][$i];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $uploadfile = $coliNo . "_PCKimg_" . str_pad(($i + 1), 2, "0", STR_PAD_LEFT) . "." . $ext;

                    $insertIMG = oci_parse($conn, "INSERT INTO MST_PACKING_IMG(COLI_NUMBER,IMG_NAME) VALUES('$coliNo','$uploadfile')");
                    oci_execute($insertIMG);
                    if ($insertIMG) {
                        resize_image($tmpFilePath, $uploadfile, 350, 350);
                    }
                }
            }

            oci_commit($conn);
            echo "<script>alert('SUCCESS : Input Package Success');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        } else {
            oci_rollback($conn);
            echo "<script>alert('FAILED : Input Package Success');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }

        //     echo "<script>alert('PRINT COLI NUMBER');PopupCenter('coliBarcode.php?coliNumber=".$coliNo."&PCKPrntSze=$PCKPrntSze','popupCN','700','842');</script>";                  
        // echo "Dalam Perbaikan";exit();
    } // END OF SUBMIT 
    ?>
</html>    
