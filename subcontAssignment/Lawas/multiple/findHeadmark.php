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
$componentType    = strval($_GET['componentValue']); 

$query = "SELECT HEAD_MARK FROM MASTER_DRAWING WHERE PROJECT_NAME = :projectName AND COMP_TYPE = :compType AND TOTAL_QTY > 1 AND SUBCONT_STATUS = 'NOTASSIGNED' AND DWG_STATUS = 'ACTIVE'";
$result = oci_parse($conn, $query);

oci_bind_by_name($result, ":projectName", $projectName);
oci_bind_by_name($result, ":compType", $componentType);
        
oci_execute($result);
?>

<label for="name" class="col-sm-2 control-label">HEAD MARK</label>
    <div class="col-sm-10">
        <select name="hmSelect" class="form-control" size="15" onChange="getElements('<?php echo $projectName?>','<?php echo $componentType?>',(this.value));">
            <?php 
                while($row = oci_fetch_array($result, OCI_BOTH)) { ?>
                    <option><?php echo $row['HEAD_MARK']?></option>
            <?php } ?>
        </select>
     </div>