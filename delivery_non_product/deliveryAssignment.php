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
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | DELIVERY NON PRODUCT ASSIGNMENT</title>
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="../AdminLTE/css/ionicons.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.min.css" />
        <!-- Date Time Picker -->
        <link rel="stylesheet" type="text/css" href="../AdminLTE/css/bootstrap-datetimepicker.min.css">
        <!-- datatable -->
        <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" />
        <!--sweet alert-->
        <link rel="stylesheet" type="text/css" href="../AdminLTE/generalReport/Final_Img_QC/assets/plugins/alerts/sweet-alert.css">

        <!--jquery main-->
        <script src="../jQuery/jquery-2.1.1.min.js"></script>
        <!--bootstrap-->
        <script src="../js/bootstrap.min.js"></script>
        <!--bootstrap select-->
        <script src="../js/bootstrap-select.min.js"></script>
        <!-- Date Picker -->
        <script src="../js/moment.js"></script>
        <script src="../js/bootstrap-datetimepicker.js"></script>
        <!-- datatable -->
        <script src="../js/jquery.dataTables.min.js"></script>
        <!--sweet alert-->
        <script src="../AdminLTE/generalReport/Final_Img_QC/assets/plugins/alerts/sweet-alert.js"></script>
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>DELIVERY ~ </b></font> <font color="#CC0000" size="5"><b>  NON PRODUCT</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">DATE</font></label>
                        <div class="col-sm-10">
                            <div class='input-group date' id='deliveryDate' >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <input type='text' id="tanggal" class="form-control" readonly="" value="<?php echo date('m/d/Y') ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">DO#</font></label>
                        <div class="col-sm-10">
                            <div class="col-sm-1" style="padding-left: 0px; padding-right: 0px;">
                                <select class="form-control" onchange="ChangeDO();" id="type-do">
                                    <option value="J">J</option>
                                    <option value="L">L</option>
                                </select>
                            </div>
                            <div class="col-sm-11"  style="padding-left: 0px; padding-right: 0px;">
                                <select class="selectpicker" id="job" data-live-search='true' data-width='100%' onchange="ChangeJob();">
                                    <option value="" selected="" disabled=""></option>
                                    <?php
                                    $sql = "SELECT DISTINCT PROJECT_NO FROM PROJECT ORDER BY PROJECT_NO ASC";
                                    $parse = oci_parse($conn, $sql);
                                    oci_execute($parse);
                                    while ($row = oci_fetch_array($parse)) {
                                        ?>
                                        <option value="<?php echo $row['PROJECT_NO']; ?>"><?php echo $row['PROJECT_NO']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="DONumberElement" class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">DO NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="do-no" name="do-no" placeholder="" onchange="ChangeDONO();">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">ADDRESS</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" placeholder="Contoh : Jalan Kedamaian Nomer 45 Gresik" maxlength="200" onkeyup="Capslock('alamat');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">CITY</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kota" placeholder="Contoh : Banyuwangi" maxlength="200" onkeyup="Capslock('kota');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">SPK NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="10" id="spk-no" placeholder="Contoh : 001/123.456" onkeyup="Capslock('spk-no');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">SUBJECT</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="subject" placeholder="Contoh : BGH, BDC" onkeyup="Capslock('subject');">
                        </div>
                    </div>

                    <div class="form-group">                  
                        <label class="col-sm-2 control-label"><font color="black">PO NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="po-no" placeholder="Contoh : PO.123.090/B1" onkeyup="Capslock('po-no');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">VEHICLE NO</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="15" id="vehicle-no" placeholder="B 1234567 AP" onkeyup="Capslock('vehicle-no');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">TRANSPORTER</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="50" id="transporter" placeholder="Contoh : Erick" onkeyup="Capslock('transporter');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">DRIVER</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="15" id="driver" placeholder="Contoh : Dian" onkeyup="Capslock('driver');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">ATTENTION</font></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="attention" maxlength="100" placeholder="Contoh : Bpk Dian" onkeyup="Capslock('attention');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">SPV</font></label>
                        <div class="col-sm-10"> 
                            <input type="text" class="form-control" id="spv" placeholder="Contoh : Ebit" maxlength="200" onkeyup="Capslock('spv');">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black">REMARKS</font></label>
                        <div class="col-sm-10">
                            <textarea id="remark-do" name="remark-do" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font color="black"></font></label>
                        <div class="col-sm-10" id="div-tablex">
                            <table class="table table-striped table-bordered" id="table-input">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            ITEM&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-success btn-sm" onclick="AddItem();">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </th>
                                        <th class="text-center" style="width: 80px;">
                                            QTY
                                        </th>
                                        <th class="text-center" style="width: 80px;">
                                            UNIT
                                        </th>
                                        <th class="text-center">
                                            DETAIL
                                        </th>
                                        <th class="text-center" style="width: 50px;">
                                            ADD DTL/REMOVE
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group" id="div-submit">
                        <label class="col-sm-2 control-label"><font color="black"></font></label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-success btn-outline col-sm-12" id="button-submit" onclick="SubmitTODB();">SUBMIT DATA</button>
                        </div>
                    </div>
                </form>
            </div> <!-- panel-body -->  
        </div> <!-- panel-default -->

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center" id="judul-detail">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <textarea style="width: 100%; height: 200px;" class="form-control" id="content" onkeydown="if (event.keyCode === 9) {
                                    var v = this.value, s = this.selectionStart, e = this.selectionEnd;
                                    this.value = v.substring(0, s) + '\t' + v.substring(e);
                                    this.selectionStart = this.selectionEnd = s + 1;
                                    return false;
                                }">
                            
                        </textarea> 
                        <input type="hidden" id="baris">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="SubmitData();">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </body>
    <script src="js/controller.js"></script>
</html>