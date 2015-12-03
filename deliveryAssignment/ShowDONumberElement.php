<?php
    require_once '../dbinfo.inc.php';
    require_once '../FunctionAct.php';
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

<?php 
  $projNo    = strval($_GET['projNo']); 

  // echo "$ProjName PROJ<br>";
  // $PROJECT_NO = SingleQryFld("SELECT PROJECT_NO FROM PROJECT WHERE PROJECT_NAME like '$ProjName'",$conn);
  
?>
<input type="hidden" name="projNo" value="<?php echo $projNo; ?>">
<label for="orderNumber" class="col-sm-2 control-label"><font color="black">DELIVERY ORDER NUMBER</font></label>
<div class="col-sm-10">
    <input type="text" class="form-control" id="orderNumber" name="orderNumber" maxlength="20" placeholder="" value="<?php echo DONumberGenerate($projNo,$conn); ?>"></input>
</div>

<script type="text/javascript">
  $(function() {
    $("#orderNumber").on('blur',
        function(){
            // console.log($(this).val());
            $("#contenCOLI").empty();
            $.get("showUpdateableElements.php",
                {
                  action:"show_do_exist",
                  projNo : "<?php echo $projNo ?>",
                  DO_no : $(this).val().replace(" ","")
                },
                function(res){
                    $("#contenCOLI").html(res);
                }
            );
        }
    );
  });

</script>