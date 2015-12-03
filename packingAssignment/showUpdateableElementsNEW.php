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
$projectName    = strval($_GET['ProjectName']); 

$prjSql = "SELECT PROJECT_NO,PROJECT_CODE FROM PROJECT WHERE PROJECT_NAME = :projectName ";

$PrjParse = oci_parse($conn, $prjSql);

oci_bind_by_name($PrjParse, ":projectName", $projectName);

oci_define_by_name($PrjParse, "PROJECT_NO", $PROJECT_NO);
oci_define_by_name($PrjParse, "PROJECT_CODE", $PROJECT_CODE);

oci_execute($PrjParse);


$query = "SELECT HEAD_MARK FROM MASTER_DRAWING WHERE PROJECT_NAME = :projectName ";
$result = oci_parse($conn, $query);

oci_bind_by_name($result, ":projectName", $projectName);
        
oci_execute($result);
?>

    <?php while (oci_fetch($PrjParse)) {
        $PROJECT_CODE;
        $PROJECT_NO;
    } ?>

    <label for="name" class="col-sm-2 control-label"><font color="red">COLI NUMBER</font></label>
        <div class="col-sm-10">
            <!-- <input type="text" class="form-control" id="coliNumber" name="coliNumber" readonly="" placeholder="" value="<?php //echo ColiNumberGenerate($PROJECT_NO,$PROJECT_CODE,$conn); ?>"></input> -->
            <input type="text" class="form-control" id="coliNumber" name="coliNumber" placeholder="" maxlength="25" value="<?php echo ColiNumberGenerate($PROJECT_NO,$PROJECT_CODE,$conn); ?>"></input>
        </div>