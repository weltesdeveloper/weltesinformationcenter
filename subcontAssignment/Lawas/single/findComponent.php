<?php
    require_once '../../dbinfo.inc.php';
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
$projectName    = strval($_GET['project']); 

$query = "SELECT DISTINCT MD.COMP_TYPE FROM MASTER_DRAWING MD WHERE MD.PROJECT_NAME = :projectName AND MD.SUBCONT_STATUS = 'NOTASSIGNED' AND MD.DWG_STATUS = 'ACTIVE'";
$result = oci_parse($conn, $query);

oci_bind_by_name($result, ":projectName", $projectName);
        
oci_execute($result);
?>

<label for="name" class="col-sm-2 control-label">COMPONENT TYPE</label>
    <div class="col-sm-10">
        <select name="compSelect" class="form-control" onChange="getHeadmark('<?php echo $projectName?>',(this.value));">
            <option>--- SELECT COMPONENT ---</option>
            <?php 
                while($row = oci_fetch_array($result)) { ?>
                    <option><?php echo $row['COMP_TYPE']?></option>
            <?php } ?>
        </select>
    </div>