<?php

require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

$invtype = $_POST['invtype'];

    $specificParse = oci_parse($conn, "SELECT MI.INV_ID, MI.INV_DESC FROM MASTER_INV@WELTES_LOGINV_LINK MI WHERE MI.INV_TYPE = '$invtype' ORDER BY INV_DESC ASC");
    $invExc = oci_execute($specificParse);

    if (!$invExc){
        $e = oci_error($specificParse);
        print htmlentities($e['message']);
        print "\n<pre>\n";
        print htmlentities($e['sqltext']);
        printf("\n%".($e['offset']+1)."s", "^");
        print  "\n</pre>\n";
    }
?>

    <label>SPECIFIC</label>
    <select class="form-control selectpicker" id="specificElement" data-live-search="true">
        <?php
            while ($row = oci_fetch_array($specificParse)){
        ?>
                <option value='<?php echo $row['INV_ID']; ?>'><?php echo $row['INV_DESC']; ?></option>;
        <?php
            }
        ?>
    </select>
<script>
    $('.selectpicker').selectpicker();
    
    $('#specificElement').on('change', function(){
        $.ajax({
            type: 'POST',
            url: 'divpages/process/wasteDetails.php',
            cache: false,
            beforeSend: function () 
            {
                        
            }, success: function (html)
            {
                $('#waste-details').html(html);
            }
        });
    });
</script>