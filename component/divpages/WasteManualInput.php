<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
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

<div class="row">
    <div class="col-md-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="fa fa-trash"></i>
                <h3 class="box-title"> Waste <b><span id="txt_job_input_stock"></span> ~ Manual Stock Input</b>
                    <small>Collect Waste Data & Insert It Here For Waste Management <span id="user" class="hidden"><?php echo $username ?></span></small>
                </h3>
            </div> 

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>COMPONENT ORIGIN</label>
                            <select class="form-control selectpicker" id="inv-type" data-live-search="true">
                                <?php
                                    $invParse = oci_parse($conn, "SELECT DISTINCT MI.INV_TYPE FROM MASTER_INV@WELTES_LOGINV_LINK MI WHERE MI.INV_CAT = 'RAW' ORDER BY MI.INV_TYPE ASC");
                                    $invExc = oci_execute($invParse);
                                    
                                    if (!$invExc){
                                        $e = oci_error($invParse);
                                        print htmlentities($e['message']);
                                        print "\n<pre>\n";
                                        print htmlentities($e['sqltext']);
                                        printf("\n%".($e['offset']+1)."s", "^");
                                        print  "\n</pre>\n";
                                    } else {                                   
                                        while($row = oci_fetch_array($invParse)){
                                ?>
                                        <option value="<?php echo $row['INV_TYPE']; ?>"><?php echo $row['INV_TYPE']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="inv-spec-type">
                            <label>SPECIFIC</label>
                            <select class="form-control selectpicker" disabled></select>
                        </div>
                    </div>
                </div>
            </div>            
        </div> 

    </div><!-- /.col -->
</div><!-- /.row -->

<div id="waste-details"></div>

<script>
    $('.selectpicker').selectpicker();
   
    $('#inv-type').on('change', function(){
        var invtype = $('select#inv-type option:selected').attr('value');
        $.ajax({
            type: 'POST',
            url: 'divpages/process/processSpecificDropdown.php',
            data: 'invtype='+invtype,
            cache: false,
            beforeSend: function () 
            {
                        
            }, success: function (html)
            {
                $('#inv-spec-type').html(html);
            }
        });
    });
    
</script>