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
<!-- JQUERY DATEPICK -->
<!-- <link href="../../AdminLTE/css/jquery.datepick.css" rel="stylesheet" type="text/css" /> -->
<link href="../css/bootstrap-select.min.css" rel="stylesheet">
<script src="../js/bootstrap-select.min.js"></script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>PRINT DELIVERY ORDER</b> <small>List</small></h4>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label">DO NO</label>
        <div class="col-sm-5">
            <?php
            $subcontSql = "SELECT DO_NO FROM MST_DELIV ORDER BY DO_NO";
            $subcontParse = oci_parse($conn, $subcontSql);
            oci_execute($subcontParse);

            echo '<select name="DONumber" id="DONumber" class="selectpicker" data-live-search="true" data-style="btn-default">';
            echo '<option value="" selected disabled>' . "[select DO No.]" . '</OPTION>';

            while ($row = oci_fetch_array($subcontParse)) {
                $DONumber = $row['DO_NO'];
                echo "<OPTION VALUE='$DONumber'>$DONumber</OPTION>";
            }
            echo '</select>';
            ?>
        </div>
    </div>
    <!--  -->
    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-primary btn-sm" onclick="clikPrint('DO', 'hide')" id="prntDOfrm">Print Form DO List</button>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalPCKLIST" id="prntPCKfrm" >Print Form PACKING List</button>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<div id="PrintPCKList"></div>
<script type="text/javascript">
    function PopupCenter(pageURL, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no,status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        if (targetWin == null) {
            alert('Please Allow POPUP Window On Your Browser....!!!');
            return false;
        }
        targetWin.focus();
    }

    function clikPrint(type, show_hide) {
        var DONumber = $('select[name="DONumber"]').val();
        // var DWG_DOWN_SUBCONT    = $('#DWG_DOWN_SUBCONT').val();
        // var DWG_DOWN_PROJNM    = $('#DWG_DOWN_PROJNM').val();
        // // alert(DWG_DOWN_DT+' -- '+DWG_DOWN_PROJNM+' -- '+DWG_DOWN_SUBCONT);
        // // alert(URL);
        if (type == "DO") {
            var URL = 'deliveryAssignment_PRINT.php?type=' + type + '&DONumber=' + DONumber;
            PopupCenter(URL, 'popupInfoDLV', '700', '842');
        } else {
            $('#PrintPCKList').load('deliveryAssignment_PRINT.php?Ambil=yes&DONumber=' + DONumber + '&showWT=' + show_hide);
        }
    }

    $(document).ready(function () {
        $('.selectpicker').selectpicker({
            'selectedText': 'cat'
        });
        $('#prntDOfrm, #prntPCKfrm').attr('disabled', 'disabled');
        $('#DONumber').change(function () {
            $('#prntDOfrm, #prntPCKfrm').removeAttr('disabled');
        });

        $('#prntPCKfrm').click(function () {
            var DONumber = $('#DONumber').val();
            $('#ttl-myModalLabel2').html('PRINT FORM PACKING LIST <small>' + DONumber + '</small>')

            var htmlText = '<div class="row">' +
                    '<div class="col-xs-2">' +
                    '<button type="button" data-dismiss="modal" class="btn btn-success" onclick="clikPrint(' + "'" + 'PCK' + "','" + 'show' + "'" + ')">Yes</button>' +
                    '</div>' +
                    '<div class="col-xs-2">' +
                    '<button type="button" data-dismiss="modal" class="btn btn-warning" onclick="clikPrint(' + "'" + 'PCK' + "','" + 'hide' + "'" + ')">No</button>' +
                    '</div>' +
                    '</div>';
            $('#pckLIST_cnfrm').html(htmlText);
        });

    });
</script>


