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

<?php
$headmark = strval($_GET['headmark']);
echo "<input type=\"hidden\" id=\"HM\" value=\"$headmark\">";

$hmNoSpace = trim(($headmark), " ");

$perHruf = concatHM($hmNoSpace);
$str_HM = @$perHruf[0];
$int_HM = @$perHruf[1];
$pad_HM = @$perHruf[2];
if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
    $str_HM = $hmNoSpace;
    $int_HM = 0;
    $pad_HM = 0;
//    echo "$str_HM";
}
//echo $str_HM;

$query = "SELECT * FROM MASTER_DRAWING WHERE HEAD_MARK = :headmark OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)) ";
//echo $query;
$result = oci_parse($conn, $query);

oci_bind_by_name($result, ":headmark", $headmark);

oci_execute($result);
$i = 0;
while ($row = oci_fetch_array($result)) {
    $dwg_type = ($row['DWG_TYP'] == 'H') ? 'HOTROOL' : 'WELDED';
    if ($row['DWG_TYP'] == '') {
        $dwg_type = '';
    }
    # code...
    ?>

    <!-- <div class="col-sm-offset-2 col-sm-10"> -->
    <?php echo "<label class='control-label'>HEAD MARK : $row[HEAD_MARK]</label><br>" ?>
    <?php echo "<label class='control-label'>PROJECT_NAME : $row[PROJECT_NAME]</label><br>" ?>
    <?php echo "<label class='control-label'>ENTRY_DATE : $row[ENTRY_DATE]</label><br>" ?>
    <?php echo "<label class='control-label'>COMP_TYPE : $row[COMP_TYPE]</label><br>" ?>
    <?php echo "<label class='control-label'>WEIGHT : $row[WEIGHT]</label><br>" ?>
    <?php echo "<label class='control-label'>SURFACE : $row[SURFACE]</label><br>" ?>
    <?php echo "<label class='control-label'>PROFILE : $row[PROFILE]</label><br>" ?>
    <?php echo "<label class='control-label'>LENGTH : $row[LENGTH]</label><br>" ?>
    <?php echo "<label class='control-label'>DWG_STATUS : $row[DWG_STATUS]</label><br>" ?>
    <?php echo "<label class='control-label'>SUBCONT_STATUS : $row[SUBCONT_STATUS]</label><br>" ?>
    <?php echo "<label class='control-label'>REVISION : $row[REV]</label><br>" ?>
    <?php echo "<label class='control-label'>TOTAL_QTY : $row[TOTAL_QTY]</label><br>" ?>
    <?php echo "<label class='control-label'>DWG TYPE : $row[DWG_TYP] ($dwg_type)</label><hr>" ?>
    <!-- </div> -->
    <?php
    $i++;
}

if ($i > 0) {
    $COLI_NUMBER = SingleQryFld("SELECT DISTINCT COLI_NUMBER FROM VW_PCK_INFO WHERE HEAD_MARK = '$headmark' OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM))", $conn);
    if ($COLI_NUMBER <> '') {
        echo "<label class='control-label text-danger'>Already On PACKING : $COLI_NUMBER </label>";
    }
    ?>
    <script type="text/javascript">
        var coli_number = "<?php echo $COLI_NUMBER; ?>";
        var HM = $("#HM").val();
        // if ($("#HMCek").attr("name") != "submit") {
        alert("Head Mark " + HM + " is EXIST, if you want to update click UPDATE button");
        // }
        $("#HMCek").attr("name", "submit");
        $("#HMCek").attr("type", "submit");
        $("#HMCek").val("Update HEAD MARK");
        if (coli_number != '') {
            alert("You Can't Update This Marking, Because This Marking Already on PACKING " + coli_number);
            $("#HMCek").prop('disabled', true);
        }
        // $("#HMCek").removeAttr("id");
    </script>
    <?php
} elseif ($headmark <> "") {
    # code...
    ?>
    <script type="text/javascript">
        // alert("OK");
        $("#HMCek").attr("name", "hmBtn");
        $("#HMCek").attr("type", "submit");
        $("#HMCek").val("Submit Headmark Data");
        // $("#frmHM").submit();
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">
        $("#HMCek").attr("type", "button");
    </script>
    <?php
}
?>
