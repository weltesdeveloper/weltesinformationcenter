<?php
// echo "dlam perbikan 5 menit";exit();
    require_once '../../../../dbinfo.inc.php';
    require_once '../../../../FunctionAct.php';
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

   // HAK AKSES
     $PAINT_ACCS       = HakAksesUser($username,'PAINT_ACCS',$conn);
     if ($PAINT_ACCS<>1) {
       # code...
        echo <<< EOD
       <h1>You Can't ACCESS PAINTING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
       exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT.WELTES ENERGI NUSANTARA | Update PAINTING Progress</title>

    <!-- Bootstrap -->
    <link rel="icon" type="image/ico" href="../../../../favicon.ico">
    <link href="../../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../../css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../../../css/scrollyou.css" rel="stylesheet">
    <link href="../../../../css/stickyfooter.css" rel="stylesheet">

    <!-- DATA TABLES -->
    <link href="../../../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../../../../jQuery/jquery-1.11.0.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../../js/bootstrap.min.js"></script>
    <script src="../../../../js/bootstrap-select.min.js"></script>
    <script src="../../../../js/scrollyou.js"></script>
    
    <!-- DATA TABLES SCRIPT -->
    <script src="../../../../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!-- <script src="../../../../AdminLTE/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    // <script src="../../../../AdminLTE/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script> -->
    
    <script>
      // ===================================== //
      // ===================================== //
      $(function(){
  		// SHOW RECORD
      	$('#show').click(function(){
          $('#result').html("<h3><b>Please Wait</b></h3><img src='../../../../images/76.gif'>");
    			$.post('update_painting_data.php',
                                    {action: "show", 
                                     ProjNme:$('#ProjNme').val()},
                                     function(res){
    			$('#result').html(res);
    			});		
    		});
    	});
    </script>
   
    <!-- Wrap all page content here -->
<div id="wrap">
  <!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
          <a class="navbar-brand" href="../../../../login_painting.php"><b>PROCESS | PAINTING</b></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="../../../../index.html">HOME</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <!-- <li><a href="../FabricationQC/update_painting_qc.php">Painting QC</a></li> -->
              <li class="divider"></li>
              <li class="dropdown-header">Nav header</li>
              <li><a href="../../../../SmartAdmin/index.php">Monitoring</a></li>
              <li><a href="workOpname.php">Final Work Opname</a></li>
            </ul>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li><a>Signed in as, <font size="4"><b><?php echo $username ?></b></font></a></li></ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  
  <!-- Begin page content -->
  <div class="container">
    <div class="page-header">
      <h1>PAINTING <span class="glyphicon glyphicon-play"></span>
      <font color="#0033CC"><b>UPDATE</b></font></h1>
    </div>
    <!-- DROPDOWN FOR HEADMARK -->  
    <div class="form-group" id="projectDropdown">
      <form class="form-inline" role="form"><br />
            <?php         
                $projectParse = oci_parse($conn, "SELECT DISTINCT (PROJECT_NAME) FROM VW_OPN_INFO WHERE PAINT_QC_PASS <> OPN_BLS_QTY OR PAINT_QC_PASS <> OPN_PNT_QTY ORDER BY PROJECT_NAME");
                oci_execute($projectParse);
                
                echo '<SELECT name="ProjNme" id="ProjNme" class="selectpicker"
                              data-style="btn-warning" data-live-search="true">';
                echo '<OPTION VALUE="">[project name select]</OPTION>';

                while($row = oci_fetch_array($projectParse,OCI_ASSOC)){
                $ProjNme = $row ['PROJECT_NAME'];
                echo "<OPTION VALUE='$ProjNme'>$ProjNme</OPTION>";
            }
            echo '</SELECT>';
            ?>
            <input class="btn btn-success" type="button" accesskey="A" value="SHOW DATA" name="SHOW RECORD" id="show"/>
      </form> <!-- <form class="form-inline" role="form"> END -->
    </div>   
    <!-- CONTAINER TO SHOW THE TABLE -->
    <div align="left" class="table-responsive" id="result"></div>
    
  </div> <!-- CONTAINER End -->
</div> <!-- WRAP End -->
<div id="footer">
  <div class="container">
    <p class="text-muted credit"><a href="http://weltes.co.id">PT. Weltes Energi Nusantara</a></p>
  </div>
</div>
<script>
      $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');

            $("#ProjNme").change(
              function(){
                $('#result').text("Click Button ");
                $('#result').append("<h4 class=''>'SHOW DATA'</h4>");
              }
            );
        });
</script>
</body>
</html>