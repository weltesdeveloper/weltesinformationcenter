<?php
   require_once '../dbinfo.inc.php';
   session_start();
   
   // CHECK IF THE USER IS LOGGED ON ACCORDING
   // TO THE APPLICATION AUTHENTICATION
   if(!isset($_SESSION['username'])){
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
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <title>PT. WELTES ENERGI NUSANTARA | REPORT DELIVERY ORDER</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="PT. Weltes Energi Nusantara DELIVERY ASSIGNMENT">
      <meta name="author" content="Chris Immanuel">

      <!-- Le styles -->
      <link rel="icon" type="image/ico" href="../favicon.ico">
      <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
      <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" />
      <style type="text/css">
        .product{
          /*background-color:#aaa;*/
        /*margin-bottom:10px;*/
        }
        
        #sidebar, #other{
          /*background-color:pink;*/
        }
      </style>


      <script src="../js/bootstrap.min.js"></script>
      <script src="../jQuery/jquery-1.11.0.min.js"></script>
  </head>
<body>
    <div class="container">
     <!-- <h1>Delivery Order</h1> -->

    <!-- <div class="navbar navbar-inverse navbar-default">
        <div class="navbar-header">Testing</div>
        <ul class="nav navbar-nav">Yeah</ul>
    </div> -->
    <div id="content" class="row">
        <div id="main" class="col-md-12">
             <h2>Delivery Order</h2>
            <div class="row">
                <!-- start nested row -->
                <div class="product col-md-6">
                    <p><img src="img/logo.jpg" ></p>
                </div>
                <div class="product col-md-6">
                    <p></p>
                </div>
            </div>
            <!-- end nested row -->
            <div class="row">
                <!-- start nested row -->
                <div class="product col-md-6">
                    <p>Item 3</p>
                </div>
                <div class="product col-md-6">
                    <p>Item 4</p>
                </div>
            </div>
            <!-- end nested row -->
        </div>
        <!-- <div id="sidebar" class="col-md-3">
             <h2>Side</h2>

        </div> -->
    </div>
    <!-- close row -->
    <div id="other" class="row">
        <div class="col-md-12">
            <p>other details here</p>
        </div>
    </div>
</div>
</body>
</html>