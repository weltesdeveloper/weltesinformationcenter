<?php
// echo "PERBAIKAN 5 Menit ----";exit();
    require_once '../../../../dbinfo.inc.php';
    require_once '../../../../FunctionAct.php';
    session_start();
   
   // CHECK IF THE USER IS LOGGED ON ACCORDING
   // TO THE APPLICATION AUTHENTICATION
   if(!isset($_SESSION['username'])){
       echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
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
     $FAB_ACCS       = HakAksesUser($username,'FAB_ACCS',$conn);
     if ($FAB_ACCS<>1) {
       # code...
        echo <<< EOD
       <h1>You Can't ACCESS FABRICATION PAGE !</h1>
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
    <title>PT.WELTES ENERGI NUSANTARA | Update Fabrication Progress</title>

    <!-- Bootstrap -->
    <link href="../../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../../css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../../../css/scrollyou.css" rel="stylesheet">
    <link href="../../../../css/stickyfooter.css" rel="stylesheet">
    <link rel="icon" type="image/ico" href="../../../../favicon.ico">

    <!-- DATA TABLES -->
    <link href="../../../../css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <!-- JQUERY DATEPICK -->
    <link href="../../../../AdminLTE/css/jquery.datepick.css" rel="stylesheet" type="text/css" />
    <!-- CSS Morph-Button BUTTOn -->
    <link href="../../../../dist/Morph-Button/css/morphbutton.css" rel="stylesheet" type="text/css">
    <!-- <link href="../../../../dist/Morph-Button/css/demo.css" rel="stylesheet"> -->
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../../../../jQuery/jquery-1.11.0.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../../js/bootstrap.min.js"></script>
    <script src="../../../../js/bootstrap-select.min.js"></script>
    <script src="../../../../js/scrollyou.js"></script>

    <!-- DATA TABLES SCRIPT -->
    <script src="../../../../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!-- JS DATEPICK -->
    <script src="../../../../AdminLTE/js/jquery.plugin.js" type="text/javascript"></script>
    <script src="../../../../AdminLTE/js/jquery.datepick.js" type="text/javascript"></script>
    <!-- JS MORP BUTTOn -->
    <script src="../../../../dist/Morph-Button/js/jquery.morphbutton.js" type="text/javascript"></script>
    <!-- JS for eComboBox -->
    <script src="../../../../js/jquery.eComboBox.min.js" type="text/javascript"></script>
  </head>
  <body>
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
              <a class="navbar-brand" href="../../../../login_fabrication.php"><b>PROCESS | FABRICATION</b></a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../../../../index.html">HOME</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="../FabricationNCR/NCR_fabrication.php">Fabrication NCR</a></li>
                  <!-- <li><a href="../FabricationQCPass/update_fabrication_qc_pass.php">Fabrication QC Pass Single</a></li>
                  <li><a href="../FabricationQCPassMult/update_fabrication_qc_pass_mult.php">Fabrication QC Pass Multiple</a></li> -->
                  <li class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="../../../../SmartAdmin/index.php">Monitoring</a></li>
                  <li><a href="#">One more separated link</a></li>
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
          <h1>FABRICATION <span class="glyphicon glyphicon-play"></span>
          <font color="#0033CC"><b>UPDATE</b></font></h1>
        </div> 
        <div class="page-body"> 
          <div class="form-group" id="projectDropdown">
            <form class="form-inline" role="form"><br />
              <!-- DROPDOWN FOR HEADMARK -->  
              <SELECT name="ProjNme" id="ProjNme" class="selectpicker" data-style="btn-warning" data-live-search="true">
                <OPTION VALUE="" selected disabled>[project name select]</OPTION>
                <?php  
                  $sql = "SELECT DISTINCT(PROJECT_NO) FROM VW_PROJ_INFO VPROJ INNER JOIN FABRICATION_QC FQC ON FQC.PROJECT_NAME=VPROJ.PROJECT_NAME_OLD INNER JOIN MASTER_DRAWING MD ON MD.HEAD_MARK = FQC.HEAD_MARK WHERE FAB_QC_STATUS = 'NOTPASSED' AND DWG_STATUS = 'ACTIVE' ORDER BY PROJECT_NO";
                  $parse = oci_parse($conn, $sql);
                  oci_execute($parse);
                  while ( $row_P_NO = oci_fetch_array($parse) ) {
                  ?>
                    <optgroup label="<?php echo $row_P_NO['PROJECT_NO'] ?>">
                      <?php 
                      $projectSQL = "SELECT DISTINCT(PROJECT_NAME_OLD),PROJECT_NAME_NEW FROM VW_PROJ_INFO VPROJ INNER JOIN FABRICATION_QC FQC ON FQC.PROJECT_NAME=VPROJ.PROJECT_NAME_OLD INNER JOIN MASTER_DRAWING MD ON MD.HEAD_MARK = FQC.HEAD_MARK WHERE FAB_QC_STATUS = 'NOTPASSED' AND DWG_STATUS = 'ACTIVE' AND PROJECT_NO = '$row_P_NO[PROJECT_NO]' ORDER BY PROJECT_NAME_NEW";
                      $projectParse = oci_parse($conn, $projectSQL);
                      oci_execute($projectParse);
                      while($row = oci_fetch_array($projectParse)){
                      $PROJECT_NAME_OLD = $row ['PROJECT_NAME_OLD'];
                      $PROJECT_NAME_NEW = $row ['PROJECT_NAME_NEW'];
                      echo "<OPTION VALUE='$PROJECT_NAME_OLD'>$PROJECT_NAME_NEW</OPTION>";
                      }
                      ?>
                    </optgroup>
                  <?php
                  }
                ?> 
              </SELECT>           
              <input class="btn btn-success" type="button" value="SHOW DATA" name="SHOW RECORD" id="show" onclick="showdata();" />
            </form> <!-- <form class="form-inline" role="form"> END -->  
          </div> 
        </div>
      </div> <!-- CONTAINER End -->

      <!-- CONTAINER TO SHOW THE TABLE -->
        <div class="col-xs-12">
          <div align="left" class="form-group" id="result"></div>
        </div>
    </div> <!-- WRAP End -->
    <div id="footer">
      <div class="container">
        <p class="text-muted credit"><a href="http://weltes.co.id">PT. Weltes Energi Nusantara</a></p>
      </div>
    </div>
<script>
  function showdata() {
    // body...
    $('#result').html("<h3><b>Please Wait</b></h3><img src='../../../../AdminLTE/img/76.gif'>");
    $.post('update_fabrication_data.php',
      {action: "show", 
       ProjNme:$('#ProjNme').val()},
      function(res){
        $('#result').html(res);
    });
  }
  $(window).on('load', function () {

      $('.selectpicker').selectpicker({
          'selectedText': 'cat'
      });

      // $('.selectpicker').selectpicker('hide');
  });

  $("#ProjNme").change(
    function(){
      $('#result').text("Click Button ");
      $('#result').append("<h4 class=''>'SHOW DATA'</h4>");
    }
  );
</script>
</body>
</html>