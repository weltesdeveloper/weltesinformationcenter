<?php
require_once '../../../../../dbinfo.inc.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/texts<p>
       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
EOD;
    exit;
}
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
setlocale(LC_MONETARY, "en_US");
// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$idopname = $_POST['idopname'];
$projectname = $_POST['projectname'];
$subcont = $_POST['subcont'];
$headmark = $_POST['headmark'];
$profile = $_POST['profile'];
$length = $_POST['length'];
$opnameqty = $_POST['opnameqty'];
$qcpassqty = $_POST['qcpassqty'];
$unitweight = $_POST['unitweight'];
$priceunit = ltrim(str_replace("IDR", "", $_POST['priceunit']));
$periode = intval(substr($idopname, strlen($idopname) - 13, 4));
$index = $_POST['index'];
$opnamedate = new DateTime($_POST['opnamedate']);
$opnamedate = $opnamedate->format("m/d/Y");
?>
<link rel="stylesheet" type="text/css" href="../../../../css/bootstrap-datetimepicker.min.css">
<script src="../../../../js/moment.js"></script>
<script src="../../../../js/bootstrap-datetimepicker.js"></script>

<link href="../../../../css/datepicker.css" rel="stylesheet" type="text/css" />

<form class="form-horizontal">
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">OPNAME ID</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-opnameid"><?php echo $idopname; ?></label>
            <input type="hidden" id="opnameidbayangan" value="<?php echo "$idopname"; ?>">
            <input type="hidden" id="opnameidasli" value="<?php echo "$idopname"; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">PROJECT NAME</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-projectname"><?php echo $projectname; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail" class="control-label col-xs-4">SUBCONT</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-subcont"><?php echo $subcont; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">HEADMARK</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-headmark"><?php echo $headmark; ?></label>
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">PROFILE</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-profile"><?php echo $profile; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">LENGTH</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-length"><?php echo $length; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">QC PASS QTY</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-qtyqcpass"><?php echo $qcpassqty; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">WEIGHT</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-7">
            <label for="inputEmail" class="control-label col-xs-10" id="edit-weight"><?php echo $unitweight; ?></label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail" class="control-label col-xs-4">PERIODE</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-4">
            <input type="number" class="form-control" id="edit-periode" value="<?php echo $periode; ?>" onchange="changePeriode()" min="0" max="999">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">OPNAME DATE</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" id="edit-date" value="<?php echo $opnamedate; ?>" readonly="" onchange="changeDate();">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">QTY OPNAME</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-4">
            <input type="number" class="form-control" id="edit-qtyopname" value="<?php echo $opnameqty; ?>" min="0" max="<?php echo $qcpassqty; ?>" onchange="cekQty();">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-4">PRICE OPNAME</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" id="edit-priceopname" value="<?php echo $priceunit; ?>" onchange="cekPrice();">
        </div>
    </div>  
    <div class="form-group hide">
        <label for="inputPassword" class="control-label col-xs-4">INDEX</label>
        <label for="inputEmail" class="control-label col-xs-1">:</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" id="edit-index" value="<?php echo $index; ?>" onchange="cekPrice();">
        </div>
    </div>  
</form>
<script>
    $(document).ready(function () {
        $('#edit-date').datetimepicker({
            format: 'MM/DD/YYYY'
        });
    });

    function changeDate() {
        var date = $('#edit-date').val();
//        console.log(date);
        var firstDate = date.substring(0, 5).replace("/", "");
        var secondDate = date.substring(6,11);
//        console.log(firstDate + "" + secondDate);
        var opnameid = $('#opnameidbayangan').val();
        var opnamesubs = opnameid.substring(0, opnameid.length-8);
        var newopnameid = opnamesubs+""+firstDate + "" + secondDate;
        $('#opnameidbayangan').val(newopnameid);
        $('#edit-opnameid').html(newopnameid);
        //console.log(newopnameid);
    }

    function changePeriode() {
        var periode = $('#edit-periode').val();
        var opnameid = $('#opnameidbayangan').val();
        periode = parseInt(periode);
        if (isNaN(periode) || periode < 0 || periode > 999) {
            $('#edit-periode').val(0);
        }
        var panjang_opnameid = opnameid.length - 13;
//        console.log(panjang_opnameid);
        var newopnameid = opnameid.substring(panjang_opnameid, panjang_opnameid + 4);
        var strpad = pad(4, periode, '0');

        var opnameidbaru = $('#edit-opnameid').text().trim().replace(newopnameid, strpad);
        $('#opnameidbayangan').val(opnameidbaru);
        $('#edit-opnameid').html(opnameidbaru);
    }

    function pad(width, string, padding) {
        return (width <= string.length) ? string : pad(width, padding + string, padding)
    }


    function cekQty() {
        var max = $('#edit-qtyopname').attr('max');
        max = parseInt(max);
        var qtyOpname = $('#edit-qtyopname').val();
        qtyOpname = parseInt(qtyOpname);
        if (qtyOpname > max || isNaN(qtyOpname) || qtyOpname < 0) {
            $('#edit-qtyopname').val(1);
        }
    }

    function cekPrice() {
        var opnameprice = $('#edit-priceopname').val();
        opnameprice = parseFloat(opnameprice);
        if (isNaN(opnameprice) || opnameprice < 0) {
            $('#edit-priceopname').val(0);
        }
    }


</script>

<style>
    .control-label.col-xs-10 {
        text-align: left;
    }
</style>