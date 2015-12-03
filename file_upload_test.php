<?php
require_once './dbinfo.inc.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.php">LOGIN PAGE</a><p>
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

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Multiple File Upload with progress bar</title>
        
    </head>
<body>
<div class="container">  

    <div class="status"></div>
  
    <form action="file_upload_execute.php" method="post" enctype="multipart/form-data" class="pure-form">
        <input type="file" name="files[]" multiple="multiple" id="files">
        <input type="submit" value="Upload" class="pure-button pure-button-primary">
    </form>
  
    <!-- progress bar -->
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>

</div>
    <script src="jQuery/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="jQuery/jquery.form.min.js" type="text/javascript"></script>
</body>
</html>

<script>
$( document ).ready(function() {
    var status = $('.status');
    var percent = $('.percent');
    var bar = $('.bar');
    
    $('form').ajaxForm({

        dataType:'json',

        beforeSend: function() {
            status.fadeOut();
            bar.width('0%');
            percent.html('0%');
        },

        uploadProgress: function(event, position, total, percentComplete) {
            var pVel = percentComplete + '%';
            bar.width(pVel);
            percent.html(pVel);
        },

        complete: function(data) {
          status.html(data.responseJSON.count + ' Files uploaded!').fadeIn();
        }
    });
});
</script>

