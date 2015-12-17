<?php
require_once '../../../../../dbinfo.inc.php';
require_once '../../../../../FunctionAct.php';
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
$PAINT_ACCS = HakAksesUser($username, 'PAINT_ACCS', $conn);
if ($PAINT_ACCS <> 1) {
    # code...
    echo <<< EOD
       <h1>You Can't ACCESS PAINTING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
    exit;
}

$jobVal = strval($_POST['jobValue']);
$buildingVal = strval($_POST['buildingValue']);
$periode = $_POST['periode'];

$uniqueCodeSql = "SELECT PROJECT_CODE PROJECTCODE FROM SUB_PROJECT WHERE PROJECT_NO = :PROJNO AND PROJECT_NAME = :PROJNAME";
$uniqueCodeParse = oci_parse($conn, $uniqueCodeSql);
oci_bind_by_name($uniqueCodeParse, ":PROJNO", $jobVal);
oci_bind_by_name($uniqueCodeParse, ":PROJNAME", $buildingVal);
oci_define_by_name($uniqueCodeParse, "PROJECTCODE", $uniqueCodeVal);
oci_execute($uniqueCodeParse);
while (oci_fetch($uniqueCodeParse)) {
    $uniqueCodeVal;
}

$uniqueProjectNameSql = "SELECT PROJECT_NAME UNPROJECTNAME FROM PROJECT WHERE PROJECT_CODE = :PROJCODE AND PROJECT_NO = :PROJNO";
$uniqueProjectNameParse = oci_parse($conn, $uniqueProjectNameSql);
oci_bind_by_name($uniqueProjectNameParse, ":PROJCODE", $uniqueCodeVal);
oci_bind_by_name($uniqueProjectNameParse, ":PROJNO", $jobVal);
oci_define_by_name($uniqueProjectNameParse, "UNPROJECTNAME", $uniqueProjectNameVal);
oci_execute($uniqueProjectNameParse);
while (oci_fetch($uniqueProjectNameParse)) {
    $uniqueProjectNameVal;
}
?>

<?php
if ($buildingVal != "") {

    $subcontParse = oci_parse($conn, "SELECT DISTINCT SUBCONT_ID FROM VW_FAB_INFO "
            . "WHERE PROJECT_NAME = :PROJNAME ORDER BY SUBCONT_ID");
    oci_bind_by_name($subcontParse, ":PROJNAME", $uniqueProjectNameVal);
    oci_execute($subcontParse);

    echo '<SELECT name="subcontname" id="subcontSelectDropdownMonitor" class="selectpicker form-control"
                  data-style="btn-warning" data-live-search="true">';
    echo '<OPTION VALUE="xxx">SELECT SUBCONTRACTOR</OPTION>';
    echo '<OPTION VALUE="">ALL</OPTION>';
    while ($row = oci_fetch_array($subcontParse, OCI_ASSOC)) {
        $subcontNme = $row ['SUBCONT_ID'];
        echo "<OPTION VALUE='$subcontNme'>$subcontNme</OPTION>";
    }
    echo '</SELECT>';
}

else{
    $subcontSql = "SELECT DISTINCT SUBCONT_ID FROM VW_FAB_INFO WHERE SUBCONT_ID != ' ' ORDER BY SUBCONT_ID";
    $subcontParse = oci_parse($conn, $subcontSql);
    oci_execute($subcontParse);
    echo '<SELECT name="subcontname" id="subcontSelectDropdownMonitor" class="selectpicker form-control"
                  data-style="btn-warning" data-live-search="true">';
    echo '<OPTION VALUE="">SELECT SUBCONTRACTOR</OPTION>';
    echo '<OPTION VALUE="">ALL</OPTION>';
    while ($subcont = oci_fetch_array($subcontParse)) {
        $subcontNme = $subcont ['SUBCONT_ID'];
        echo "<OPTION VALUE='$subcontNme'>$subcontNme</OPTION>";
    }
}
?>

<script>
    $(document).ready(function () {
        $('#subcontSelectDropdownMonitor').selectpicker({
            width: 'auto'
        });

        $('#subcontSelectDropdownMonitor').on('change', $(this), function () {
            var subcontVal = $('#subcontSelectDropdownMonitor').val();
            $.ajax({
                type: 'POST',
                url: "MonitorOpname/showFinalOpnameTableMonitor.php",
                data: {subcontValue: subcontVal, projectNameValue: '<?php echo $uniqueProjectNameVal ?>', jobValue: "<?php echo $jobVal; ?>", periode:"<?php echo "$periode";?>"},
                success: function (response) {
                    $('#finalOpnameTableMonitor').html(response);
                }
            });
        });
    });
</script>