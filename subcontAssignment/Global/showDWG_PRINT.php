<?php 
   require_once '../../dbinfo.inc.php';
   require_once '../../FunctionAct.php';
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
<!-- JQUERY DATEPICK -->
<!-- <link href="../../AdminLTE/css/jquery.datepick.css" rel="stylesheet" type="text/css" /> -->
<style type="text/css">
    .table thead tr th{
        text-align: center;
    }
</style>
<script type="text/javascript">
  function PopupCenter(pageURL,title,w,h) {
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no,status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
      targetWin.focus();
  }
</script>
<!-- JS DATEPICK -->
<!-- <script src="../../AdminLTE/js/jquery.plugin.js" type="text/javascript"></script> -->
<!-- <script src="../../AdminLTE/js/jquery.datepick.js" type="text/javascript"></script> -->
<!-- Date Time Picker -->
<link rel="stylesheet" type="text/css" href="../../css/bootstrap-datetimepicker.min.css">
<script src="../../js/moment.js"></script>
<script src="../../js/bootstrap-datetimepicker.js"></script>


<!-- <link href="../../css/bootstrap-formhelpers.min.css" rel="stylesheet" type="text/css" />
<script src="../../js/bootstrap-formhelpers.js" language="javascript" type="text/javascript"  ></script> -->

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>PRINT DRAWING ASSIGNMENT</b> <small>To Subcont</small></h4>
</div>
<div class="modal-body">
  <div class="form-group row">
      <label for="name" class="col-sm-4 control-label">DATE of DWG DOWN</label>
      <div class="col-sm-5">
        <div class='input-group date' id='DWG_DOWN_DT'>
            <input type='text' name="DWG_DOWN_DT" class="form-control" data-date-format="MM/DD/YYYY" />
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        <!-- <div id="DWG_DOWN_DT" data-name="DWG_DOWN_DT" class="bfh-datepicker" data-date="today"></div> -->
          <!-- <input type="text" id="dateRange1" data-datepick1="datepick1Value" class="form-control" placeholder="Today's Date"> -->
      </div>
  </div>
  <div class="form-group row">
      <label for="name" class="col-sm-4 control-label">TIME of DWG DOWN >= </label>
      <div class="col-sm-5">
        <div class='input-group date' id='DWG_DOWN_TIME'>
            <input type='text' name="DWG_DOWN_TIME" class="form-control" data-date-format="HH:mm" />
            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
            </span>
        </div>
      </div>
  </div>
  <div class="form-group row">
      <label for="name" class="col-sm-4 control-label">SUBCONTRACTOR</label>
      <div class="col-sm-5">
          <?php
              $subcontSql = "SELECT SUBCONT_ID FROM SUBCONTRACTOR ORDER BY SUBCONT_ID";
              $subcontParse = oci_parse($conn, $subcontSql);        
              oci_execute($subcontParse);

              echo '<select class="form-control" name="DWG_DOWN_SUBCONT" id="DWG_DOWN_SUBCONT">';
              echo '<option value=" ">'."[select subcont]".'</OPTION>';
                          
              while($row = oci_fetch_array($subcontParse, OCI_ASSOC)){
                  $subcont = $row['SUBCONT_ID'];
                  echo "<OPTION VALUE='$subcont'>$subcont</OPTION>";}      
              echo '</select>';
          ?>
      </div>
  </div>
  <div class="form-group row">
    <label for="name" class="col-sm-4 control-label">PROJECT NAME</label>
    <div class="col-sm-5">
          <?php
              $projectNameSql = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO,PROJECT_NAME_NEW";
              $projectNameParse = oci_parse($conn, $projectNameSql);                       
              oci_execute($projectNameParse);

              echo '<select class="form-control" name="DWG_DOWN_PROJNM" id="DWG_DOWN_PROJNM" data-live-search="true">';
              echo '<option value="" selected disabled>'."[select project]".'</OPTION>';

              while($row = oci_fetch_array($projectNameParse))
              {   
                  $proj = $row['PROJECT_NO']." - ".$row['PROJECT_NAME_NEW'];

                  $projNmLma = $row['PROJECT_NAME_OLD'];

                  echo "<OPTION VALUE='$projNmLma'>$proj</OPTION>";
              }      
              echo '</select>';
          ?>
    </div>
  </div>
  <div class="form-group row">
      <div class="col-sm-offset-4 col-sm-5">
        <a href="#" class="btn btn-primary btn-sm" onclick="clikPrint('FABR')">Print Form FABR</a>
        <a href="#" class="btn btn-warning btn-sm" onclick="clikPrint('FABR_REV')">Print Form FABR Rev</a>
      </div>
  </div>
  <div class="form-group row">
      <div class="col-sm-offset-4 col-sm-5">
        <a href="#" class="btn btn-primary btn-sm" onclick="clikPrint('PAINT')">Print Form PAINT</a>
        <a href="#" class="btn btn-warning btn-sm" onclick="clikPrint('PAINT_REV')">Print Form PAINT Rev</a>
      </div>
  </div> 
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script type="text/javascript">
// $(function() {
//     $('#dateRange1').datepick();
//     $('#dateRange2').datepick();
// });
  function clikPrint(type){
    var DWG_DOWN_DT         = $('input[name="DWG_DOWN_DT"]').val();
    var DWG_DOWN_TIME       = $('input[name="DWG_DOWN_TIME"]').val();
    var DWG_DOWN_SUBCONT    = $('#DWG_DOWN_SUBCONT').val();
    var DWG_DOWN_PROJNM     = $('#DWG_DOWN_PROJNM').val();
    // alert(DWG_DOWN_DT+' -- '+DWG_DOWN_TIME+' -- '+DWG_DOWN_PROJNM+' -- '+DWG_DOWN_SUBCONT);
    var URL = 'subcontAssignment_PRINT.php?type='+type+'&projName='+DWG_DOWN_PROJNM+'&subcont='+DWG_DOWN_SUBCONT+'&date1='+DWG_DOWN_DT+'&time1='+DWG_DOWN_TIME;
    // alert(URL);
    PopupCenter(URL,'popupInfoHM','700','842');
  }

  $(document).ready(
    function(){
      // alert("Masuk");
        $('#DWG_DOWN_TIME').datetimepicker({
          // pickTime: false,
          pickDate: false
        });

        $('#DWG_DOWN_DT').datetimepicker({
          pickTime: false
          // pickDate: false
        });

        $('#DWG_DOWN_PROJNM').selectpicker();
    });
</script>

                                             
     