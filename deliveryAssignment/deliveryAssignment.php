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
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | DELIVERY ASSIGNMENT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara DELIVERY ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-formhelpers.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css" />
        <!-- BootstrapValidator CSS -->
        <link rel="stylesheet" href="../css/bootstrapValidator.min.css"/>
        <!-- Date Time Picker -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-datetimepicker.min.css">
        <!-- bootstrap tagsinput -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-tagsinput.css">
        <!-- datatable -->
        <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" />
        <!-- bootstrap checkbox -->
        <link rel="stylesheet" type="text/css" href="../dist/montrezorro-bootstrap-checkbox/css/bootstrap-checkbox.css">

        <style type="text/css">
            span.tag {
                font-size: 12px;
            }
            #tbl_packing tbody tr td{
                vertical-align: middle;
            }
        </style>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../jQuery/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>

        <script language="javascript" type="text/javascript"  src="revisionJs/delivDropdown.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/bootstrap-formhelpers.js"></script>
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-select.min.js"></script>
        <!-- BootstrapValidator JS -->
        <script type="text/javascript" src="../js/bootstrapValidator.min.js"></script>
        <!-- FIlter JS -->
        <script type="text/javascript" src="revisionJs/jcfilter.min.js"></script>
        <!-- Date Time Picker -->
        <script src="../js/moment.js"></script>
        <script src="../js/bootstrap-datetimepicker.js"></script>
        <!-- bootstrap tagsinput -->
        <script language="javascript" type="text/javascript"  src="../js/angular.min.js"></script>
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-tagsinput.min.js"></script>
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-tagsinput-angular.min.js"></script>
        <!-- datatable -->
        <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
        <!-- bootstrap checkbox -->
        <script type="text/javascript" src="../dist/montrezorro-bootstrap-checkbox/js/bootstrap-checkbox.js"></script>

    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>DELIVERY ~ </b></font> <font color="#CC0000" size="5"><b> ASSIGNMENT</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="attributeForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onSubmit="return cekCMbox('chkCN[]');">

                    <!-- <div class="form-group"> -->                  
                    <div class="form-group">
                        <label for="doNumber" class="col-sm-2 control-label"><font color="black">DO# (SELECT PROJECT)</font></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><font size="2"><b>J. </b></font></button>
                                </div><!-- /btn-group -->
                                <?php
                                $projectNameSql = "SELECT DISTINCT PROJECT_TYP FROM VW_PROJ_INFO WHERE PROJECT_TYP in ('STRUCTURE','TANKAGE')  ORDER BY PROJECT_TYP";
                                $projectNameParse = oci_parse($conn, $projectNameSql);
                                oci_execute($projectNameParse);
                                echo '<select class="form-control" name="projNo" id="projNo" data-live-search="true">';
                                echo '<option value="" selected disabled></option>';
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
                                echo '</select>';
                                ?>
                            </div><!-- /input-group -->
                        </div>
                    </div>

                    <div id="DONumberElement" class="form-group">
                        <label for="orderNumber" class="col-sm-2 control-label"><font color="black">DELIVERY ORDER NUMBER</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="orderNumber" name="orderNumber" placeholder="" value="" readonly=""></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="delivDate" class="col-sm-2 control-label"><font color="black">DELIVERY DATE</font></label>
                        <div class="col-sm-10">
                            <div class='input-group date' id='deliveryDate' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <input type='text' name="deliveryDate" class="form-control" data-date-format="MM/DD/YYYY" readonly="" value="<?php echo date('m/d/Y') ?>" style="position:static;" />
                            </div>
                            <!-- <div id="deliveryDate" data-name="deliveryDate" class="bfh-datepicker" data-date="today" ></div> -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driverphone" class="col-sm-2 control-label"><font color="black">DELIVERY ADDRESS</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control bfh-phone" id="do_addr" name="do_addr" placeholder="" value="" maxlength="200" data-bv-notempty="true" data-bv-notempty-message="DELIVERY ADDR is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driverphone" class="col-sm-2 control-label"><font color="black">DELIVERY CITY</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control bfh-phone" id="do_city" name="do_city" placeholder="" value="" maxlength="200" data-bv-notempty="true" data-bv-notempty-message="DELIVERY CITY is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="spk" class="col-sm-2 control-label"><font color="black">SPK NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="10" id="spkNo" name="spkNo" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="SPK NO NUMBER is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label"><font color="black">SUBJECT</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="subject" name="subject" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="SUBJECT is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">                  
                        <label for="PONo" class="col-sm-2 control-label"><font color="black">PO NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="PONo" name="PONo" placeholder="" value="" ></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vhcleNo" class="col-sm-2 control-label"><font color="black">VEHICLE NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="15" id="vehicleno" name="vehicleno" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message=" VEHICLE NO is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transporter" class="col-sm-2 control-label"><font color="black">TRANSPORTER</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="transporterName" name="transporterName" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="TRANSPORTER is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driver" class="col-sm-2 control-label"><font color="black">DRIVER</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="15" id="driverName" name="driverName" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="DRIVER is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driverphone" class="col-sm-2 control-label"><font color="black">ATTENTION</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control bfh-phone" id="attn" name="attn" maxlength="100" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="ATTN is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driverphone" class="col-sm-2 control-label"><font color="black">SPV</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control bfh-phone" id="do_spv" name="do_spv" placeholder="" value="" maxlength="200" data-bv-notempty="true" data-bv-notempty-message="SPV is required and cannot be empty"></input>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="SerchBox" style="display:none;">
                            <label for="name" class="col-sm-2 control-label">SEARCH COLI NUMBER</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="filter" onkeyup="">
                            </div>
                        </div>

                        <label for="packingNo" class="col-sm-2 control-label">COLI NUMBER</label>
                        <div class="col-sm-10" id="contenCOLI">
                            <table class="table table-hover table-condensed" style="background-color:#F2F2F2;" id="tbl_packing">
                                <thead>
                                    <tr>
                                        <th><input class="checkbox-inline" type="checkbox" id="chkAll" name="chkAll" style="display:none;" onchange="checkAll('chkAll', 'chkCN[]');"/></th>
                                        <th>Coli No.</th>
                                        <th>Pack Type</th>
                                        <th>Volume ( M<sup>3</sup>)</th>
                                        <th>Project Type</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- ISI CONTEN COLI NUMBER -->
                                </tbody>
                            </table>
                        </div>

                        <label for="name" class="col-sm-2 control-label">CHECKED COLI LIST</label>
                        <div class="col-sm-10">
                          <!-- <textarea class="form-control label-default" id="PickList" ></textarea> -->
                            <select id="PickList" multiple> <!-- data-role="tagsinput" -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driverphone" class="col-sm-2 control-label"><font color="black">REMARKS</font></label>
                        <div class="col-sm-10" id="txtarea_rems">
                            <textarea id="rems" name="rems" class="form-control bfh-phone"></textarea>
                        </div>
                    </div>
                    <!-- </div> -->

                    <!-- <div class="form-group" id="deliveryAssignmentElements"></div> -->

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <a href="#" id="prn_dwg" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Print DO List</a>
                                <input type="submit" class="btn btn-success btn-sm" name="btnSubmit" >
                                <input type="button" class="btn btn-default btn-sm" value="Reset" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'] ?>');">
                                <!-- <input type="submit" class="btn btn-danger btn-sm" name="delete" value="delete"> -->
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  


        </div> <!-- panel-default -->
        <?php
        if (isset($_POST['btnSubmit'])) {
            // echo "dalam Perbaikan broo";exit();

            $orderNumber = str_replace(" ", "", strval($_POST['orderNumber']));
            $subject = strval($_POST['subject']);
            $spkNo = strval($_POST['spkNo']);
            $delivDt = strval($_POST['deliveryDate']);
            $PONo = strval($_POST['PONo']);
            $vehicleno = strval($_POST['vehicleno']);
            $transporterName = strval($_POST['transporterName']);
            $projectNum = strval($_POST['projNo']);
            $driverVal = strval($_POST['driverName']);
            $do_addr = strval($_POST['do_addr']);
            $attn = strval($_POST['attn']);
            $rems = rtrim(ltrim($_POST['rems']));
            $do_spv = strval($_POST['do_spv']);
            $do_city = strval($_POST['do_city']);

            $delivDate = date('m-d-Y h:i:s', strtotime($delivDt));

            $jmlDLV = SingleQryFld("SELECT COUNT(*) FROM MST_DELIV WHERE DO_NO='$orderNumber'", $conn);
            if ($jmlDLV > 0) {
                # code...
                // echo "$orderNumber<br>$delivDate<br>$spkNo<br>$subject<br>$PONo<br>$vehicleno<br>$transporterName<br>$driverVal<br>";

                $delDtlDELIV_sql = "DELETE FROM DTL_DELIV WHERE DO_NO='$orderNumber' ";
                $delDtlDELIV_parse = oci_parse($conn, $delDtlDELIV_sql);
                oci_execute($delDtlDELIV_parse);

                $delMstDELIV_sql = "DELETE FROM MST_DELIV WHERE DO_NO='$orderNumber' ";
                $delMstDELIV_parse = oci_parse($conn, $delMstDELIV_sql);
                oci_execute($delMstDELIV_parse);

                if ($delDtlDELIV_parse && $delMstDELIV_parse) {
                    oci_commit($conn);
                    echo "Delete DO NO SUCCESS <br>";
                } else {
                    oci_rollback($conn);
                    echo "<script>alert('Update Delivery FAILED');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    exit();
                }


                // AUTO UPDATE MST PACKING DLV STATUS
                //trigger MST_PACK_AUTOUPD_DLV
                // echo "DELIVERY ORDER NUMBER $orderNumber Already EXIST!";
                // exit();
            }

            // echo "Dalam Perbaikan";exit();
            //insert to Master delivery

            $insertDLVSql = "BEGIN MST_DLV_INS(:DO_NUM, to_date(:DO_DT,'MM/DD/YYYY hh24:mi:ss'), :PO, :PROJNO, :V_NO, :T_PORT, :D_VR, :IN_SIGN, :SPK, :SUB, :ATTN ,:ADDR ,:REMS, :CITY, :SPV); END;";

            $insertDLVParse = oci_parse($conn, $insertDLVSql);

            oci_bind_by_name($insertDLVParse, ":DO_NUM", $orderNumber);
            oci_bind_by_name($insertDLVParse, ":DO_DT", $delivDate);
            oci_bind_by_name($insertDLVParse, ":PO", $PONo);
            oci_bind_by_name($insertDLVParse, ":PROJNO", $projectNum);
            oci_bind_by_name($insertDLVParse, ":V_NO", $vehicleno);
            oci_bind_by_name($insertDLVParse, ":T_PORT", $transporterName);
            oci_bind_by_name($insertDLVParse, ":D_VR", $driverVal);
            oci_bind_by_name($insertDLVParse, ":IN_SIGN", $username);
            oci_bind_by_name($insertDLVParse, ":SPK", $spkNo);
            oci_bind_by_name($insertDLVParse, ":SUB", $subject);
            oci_bind_by_name($insertDLVParse, ":ATTN", $attn);
            oci_bind_by_name($insertDLVParse, ":ADDR", $do_addr);
            oci_bind_by_name($insertDLVParse, ":REMS", $rems);
            oci_bind_by_name($insertDLVParse, ":CITY", $do_city);
            oci_bind_by_name($insertDLVParse, ":SPV", $do_spv);


            $insertDLVRes = oci_execute($insertDLVParse);
            if ($insertDLVRes) {
                oci_commit($conn);
                // insert ke master delivery detail
                $CNArry = $_POST['chkCN'];
                if (!empty($CNArry)) {
                    // Loop to store and display values of individual checked checkbox.
                    foreach ($CNArry as $selected) {

                        $insertDtlDLVSQL = "BEGIN DTL_DLV_INS(:DO_NUM, :C_NUM); END;";
                        $insertDtlDLVPARSE = oci_parse($conn, $insertDtlDLVSQL);

                        oci_bind_by_name($insertDtlDLVPARSE, ":DO_NUM", $orderNumber);
                        oci_bind_by_name($insertDtlDLVPARSE, ":C_NUM", $selected);

                        $insertDtlDLVRES = oci_execute($insertDtlDLVPARSE);

                        if ($insertDtlDLVRES) {
                            oci_commit($conn);
                            echo "$selected INSERTION SUCCESS<br>";

                            // UPDATE STATUS PACK PAKE TRIGER
                            // $updatePackSQL   = "UPDATE MST_PACKING SET DLV_STAT='D' WHERE COLI_NUMBER like :C_NUM";
                            // $updatePackPARSE = oci_parse($conn, $updatePackSQL);
                            // oci_bind_by_name($updatePackPARSE, ":C_NUM", $selected);
                            // $updatePackRES = oci_execute($updatePackPARSE);
                            // if ($updatePackRES){
                            //     oci_commit($conn);
                            //     echo "$selected UPDATE MST PACK SUCCESS<br>";
                            // } else {
                            //     oci_rollback($conn);
                            //     echo "<font color='red'>$selected UPDATE MST PACK FAILED</font><br>";
                            // }
                        } else {
                            oci_rollback($conn);
                            echo "<font color='red'>$selected INSERTION FAILED</font><br>";
                        }

                        // echo "DO NUMBER : $orderNumber<br>Dlv Date : $delivDate<br>spkNo : $spkNo<br>";
                        // echo $selected."  --- </br>";
                    }

                    if ($jmlDLV > 0) {
                        echo "<script>alert('Update Delivery Success');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    } else {
                        echo "<script>alert('Input Delivery Success');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    }
                } else {
                    echo "<script>alert('Input Delivery Detail FAILED');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                }
            } else {
                oci_rollback($conn);
                echo "<script>alert('Input Delivery FAILED');window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            }
        } // isset($_POST['btnSubmit']) ENDS
        ?> 
        <script type="text/javascript">
            function doSubmit() {
                if (confirm('Are you sure you want to submit DELIVERY Assignment Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }
            function expandTextarea() {
                // var $element = $('#PickList').get(0);  

                // // $element.addEventListener('change', function() {
                //     $element.style.overflow = 'hidden';
                //     $element.style.height = 0;
                //     $element.style.height = $element.scrollHeight + 'px';
                // // }, false);
            }

            function cekCMbox(ChkChild) {
                // body...
                var numberOfChecked = $("input[name='" + ChkChild + "']:checkbox:checked").length;
                if (numberOfChecked == 0) {
                    alert('Please Choice COLI NUMBER');
                    $('#filter').select();
                    return false;
                } else {
                    return doSubmit();
                }
            }
        </script>

        <script>
            $(document).ready(function () {
                $('#projNo').selectpicker();
                $('#deliveryDate').datetimepicker({
                    pickTime: false
                            // pickDate: false
                });

                // $('.selectpicker').selectpicker('hide');  
                expandTextarea();

                $('#attributeForm').bootstrapValidator({
                    // feedbackIcons: {
                    //         valid: 'glyphicon glyphicon-ok',
                    //         invalid: 'glyphicon glyphicon-remove',
                    //         validating: 'glyphicon glyphicon-refresh'
                    //     },
                    fields: {
                        // password: {
                        //     enabled: false,
                        //     validators: {
                        //         notEmpty: {
                        //             message: 'The password is required and cannot be empty'
                        //         },
                        //         identical: {
                        //             field: 'confirm_password',
                        //             message: 'The password and its confirm must be the same'
                        //         }
                        //     }
                        // },
                        PONo: {
                            enabled: false,
                            // validators: {
                            //     notEmpty: {
                            //         message: 'The confirm password is required and cannot be empty'
                            //     },
                            //     identical: {
                            //         field: 'password',
                            //         message: 'The password and its confirm must be the same'
                            //     }
                            // }
                        },
                        'chkCN[]': {
                            validators: {
                                choice: {
                                    min: 1,
                                    message: 'Please Choice COLI NUMBER'
                                }
                            }
                        }
                        // ,
                        // // 'chkAll':{
                        // //     validators:{
                        // //         choice: {
                        // //             min: 1,
                        // //             max: 1,
                        // //             message: 'Please Choice COLI NUMBER'
                        // //         }
                        // //     }
                        // // },
                        // PickList: {
                        //     validators: {
                        //         notEmpty: {
                        //             message: 'Please Choice COLI NUMBER'
                        //         }
                        //     }
                        // }
                    }
                });

                $('input[type="submit"]').attr('disabled', 'disabled');
                $('input[type="text"]').keyup(function () {
                    var numberOfChecked = $("input[name='chkCN[]']:checkbox:checked").length;

                    if ($(this).val() != '' && numberOfChecked > 0) {
                        $('input[type="submit"]').removeAttr('disabled');
                    }
                });

                $('#projNo').change(
                        function () {
                            $.get("showUpdateableElements.php", {
                                action: 'show_do_number',
                                projNo: $(this).val()
                            },
                            function (res) {
                                $("#DONumberElement").html(res);
                            }
                            );
                        }
                );

                $('#prn_dwg').on('click',
                        function () {
                            $('#PrintDWG').load("showDO_PRINT.php");
                        }
                );

                // tagsinput
                $('#PickList').tagsinput({
                    tagClass: ['label label-primary'],
                    itemValue: 'id',
                    itemText: 'text',
                    freeInput: false
                });
                $('#PickList').on('itemAdded', function (event) {
                    // console.log(event.item);
                });
                $('#PickList').on('itemRemoved', function (event) {
                    // console.log(event.item);
                    var idCHK = event.item.id;
                    $('#' + idCHK).checkbox({
                        checked: false
                    });
                    $('#' + idCHK).prop("checked", false);
                });

                $('div[class="bootstrap-tagsinput"] input[type="text"]').hide();
            });


        </script>

        <!-- JS FILTER -->
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("#filter").jcOnPageFilter({animateHideNShow: true,
                    focusOnLoad: true,
                    highlightColor: 'yellow',
                    textColorForHighlights: '#000000',
                    caseSensitive: false,
                    hideNegatives: true,
                    parentLookupClass: 'jcorgFilterTextParent',
                    childBlockClass: 'jcorgFilterTextChild'});
            });
        </script>

        <!-- Modal for print do -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xs">
                <div class="modal-content" id="PrintDWG">                          
                </div>
            </div>
        </div>

        <!-- Modal for choice weight show YES or NO-->
        <div class="modal fade" id="myModalPCKLIST" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-xs">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="ttl-myModalLabel2"></h4>
                        <h4 class="modal-title"><b>Showing Building Weight ?</b></h4>
                    </div>
                    <div class="modal-body" id="pckLIST_cnfrm">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>                        
                </div>
            </div>
        </div>
    </body>
</html>    
