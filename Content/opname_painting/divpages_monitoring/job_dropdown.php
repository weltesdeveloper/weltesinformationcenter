<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
oci_set_client_identifier($conn, $_SESSION['username']);
//$username = htmlentities($_SESSION['username'], ENT_QUOTES);
?>

<select class="form-control" id="job" onchange="EditJob();">
    <option value="" selected="" disabled="">[SELECT JOB]</option>
    <?php
    $periodeSql = "SELECT DISTINCT PROJECT_NO FROM VW_REPORT_OPNAME_PNT WHERE OPNAME_TYPE = '$_POST[type]' AND OPNAME_PERIOD = '$_POST[periode]' ORDER BY PROJECT_NO";
    $periodeParse = oci_parse($conn, $periodeSql);
    oci_execute($periodeParse);
    while ($row2 = oci_fetch_array($periodeParse)) {
        echo "<option value='$row2[PROJECT_NO]'>$row2[PROJECT_NO]</option>";
    }
    ?>
</select>