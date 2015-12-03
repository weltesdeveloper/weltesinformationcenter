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
$componentType  = strval($_GET['componentValue']);
$headmarkType  = strval($_GET['headmarkValue']); 

$query = "SELECT HEAD_MARK FROM MASTER_DRAWING WHERE PROJECT_NAME = :projectName AND COMP_TYPE = :compType ";
$result = oci_parse($conn, $query);

oci_bind_by_name($result, ":projectName", $projectName);
oci_bind_by_name($result, ":compType", $componentType);
        
oci_execute($result);
?>

    <?php
    $qtySql = "SELECT TOTAL_QTY AS UNIT_QTY FROM MASTER_DRAWING WHERE PROJECT_NAME = :PROJNAME AND COMP_TYPE = :COMP AND HEAD_MARK = :HM";
    $qtyParse = oci_parse($conn, $qtySql);
    
    oci_bind_by_name($qtyParse, ":PROJNAME", $projectName);
    oci_bind_by_name($qtyParse, ":COMP", $componentType);
    oci_bind_by_name($qtyParse, ":HM", $headmarkType);
    
    oci_define_by_name($qtyParse, "UNIT_QTY", $unitQty);
    
    oci_execute($qtyParse);
    ?>

    <?php
    $assignedQtySql = "SELECT SUM (MDA.ASSIGNED_QTY) AS ASSIGN_QTY "
            . " FROM MASTER_DRAWING_ASSIGNED MDA INNER JOIN MASTER_DRAWING MD ON MD.HEAD_MARK = MDA.HEAD_MARK AND MD.DWG_STATUS = 'ACTIVE' "
            . " WHERE MD.PROJECT_NAME = :PROJNAME AND MD.COMP_TYPE = :COMP AND MD.HEAD_MARK = :HM";
    $assignedQtyParse = oci_parse($conn, $assignedQtySql);
    
    oci_bind_by_name($assignedQtyParse, ":PROJNAME", $projectName);
    oci_bind_by_name($assignedQtyParse, ":COMP", $componentType);
    oci_bind_by_name($assignedQtyParse, ":HM", $headmarkType);
    
    oci_define_by_name($assignedQtyParse, "ASSIGN_QTY", $assignmentQty);
    oci_execute($assignedQtyParse);
    ?>

    <?php while(oci_fetch($qtyParse)){$unitQty;} ?>
    <?php while(oci_fetch($assignedQtyParse)){$assignmentQty;} ?>

    <?php $availQty = $unitQty - $assignmentQty;?>

    <label for="name" class="col-sm-2 control-label">SUBCONTRACTOR</label>
        <div class="col-sm-10">
            <?php
                $subcontSql = "SELECT SUBCONT_ID FROM SUBCONTRACTOR";
                $subcontParse = oci_parse($conn, $subcontSql);        
                oci_execute($subcontParse);

                echo '<select class="form-control" name="subcontAssign" id="subcontAssign" onchange="bootstrapValidatorOpen();" data-bv-notempty="true" data-bv-notempty-message="SUBCONTRACTOR is required and cannot be empty">'.'<br>';
                echo '<option value=" ">'."".'</OPTION>';
                            
                while($row = oci_fetch_array($subcontParse, OCI_ASSOC)){
                    $subcont = $row['SUBCONT_ID'];
                    echo "<OPTION VALUE='$subcont'>$subcont</OPTION>";}      
                echo '</select>';
            ?>
        </div>
    </div>
    
    <label for="name" class="col-sm-2 control-label">ASSIGN QUANTITY</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                    <input type="number" class="form-control" name="assignedQty" min="0" max="<?php echo $availQty; ?>" data-bv-notempty="true" data-bv-notempty-message="ASSIGN QUANTITY is required and cannot be empty">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Total Available Quantity To Be Assigned : <font color="maroon" size="2"><b> <?php echo $availQty; ?></b></font></button>
                      <ul class="dropdown-menu pull-right">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                      </ul>
                    </div><!-- /btn-group -->
                  </div><!-- /input-group -->
                  </div>
  
    <label for="name" class="col-sm-2 control-label">DUE DATE</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">In Days <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div><!-- /btn-group -->
                <input type="number" class="form-control" id="unitDueDate" name="unitDueDate" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="DUE DATE is required and cannot be empty">
            </div><!-- /input-group -->
        </div>