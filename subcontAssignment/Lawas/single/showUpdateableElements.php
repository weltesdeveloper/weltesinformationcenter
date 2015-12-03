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
    $weightSql = "SELECT WEIGHT AS UNIT_WEIGHT FROM MASTER_DRAWING WHERE PROJECT_NAME = :PROJNAME AND COMP_TYPE = :COMP AND HEAD_MARK = :HM";
    $weightParse = oci_parse($conn, $weightSql);
    
    oci_bind_by_name($weightParse, ":PROJNAME", $projectName);
    oci_bind_by_name($weightParse, ":COMP", $componentType);
    oci_bind_by_name($weightParse, ":HM", $headmarkType);
    
    oci_define_by_name($weightParse, "UNIT_WEIGHT", $unitWeight);
    
    oci_execute($weightParse);
    ?>

    <?php
    $surfaceSql = "SELECT SURFACE AS UNIT_SURFACE FROM MASTER_DRAWING WHERE PROJECT_NAME = :PROJNAME AND COMP_TYPE = :COMP AND HEAD_MARK = :HM";
    $surfaceParse = oci_parse($conn, $surfaceSql);
    
    oci_bind_by_name($surfaceParse, ":PROJNAME", $projectName);
    oci_bind_by_name($surfaceParse, ":COMP", $componentType);
    oci_bind_by_name($surfaceParse, ":HM", $headmarkType);
    
    oci_define_by_name($surfaceParse, "UNIT_SURFACE", $unitSurface);
    
    oci_execute($surfaceParse);
    ?>

    <?php
    $lengthSql = "SELECT LENGTH AS UNIT_LENGTH FROM MASTER_DRAWING WHERE PROJECT_NAME = :PROJNAME AND COMP_TYPE = :COMP AND HEAD_MARK = :HM";
    $lengthParse = oci_parse($conn, $lengthSql);
    
    oci_bind_by_name($lengthParse, ":PROJNAME", $projectName);
    oci_bind_by_name($lengthParse, ":COMP", $componentType);
    oci_bind_by_name($lengthParse, ":HM", $headmarkType);
    
    oci_define_by_name($lengthParse, "UNIT_LENGTH", $unitLength);
    
    oci_execute($lengthParse);
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

    <?php while(oci_fetch($weightParse)){$unitWeight;} ?>
    <?php while(oci_fetch($surfaceParse)){$unitSurface;} ?>
    <?php while(oci_fetch($lengthParse)){$unitLength;} ?>
    <?php while(oci_fetch($qtyParse)){$unitQty;} ?>
<!-- 
    <label for="name" class="col-sm-2 control-label">SUBCONTRACTOR</label>
        <div class="col-sm-10">
            <?php
                // $subcontSql = "SELECT SUBCONT_ID FROM SUBCONTRACTOR";
                // $subcontParse = oci_parse($conn, $subcontSql);        
                // oci_execute($subcontParse);

                // echo '<select class="form-control" name="subcontAssign" id="subcontAssign" data-bv-notempty="true" data-bv-notempty-message="SUBCONTRACTOR is required and cannot be empty">'.'<br>';
                // echo '<option value=" ">'."".'</OPTION>';
                            
                // while($row = oci_fetch_array($subcontParse, OCI_ASSOC)){
                //     $subcont = $row['SUBCONT_ID'];
                //     echo "<OPTION VALUE='$subcont'>$subcont</OPTION>";}      
                // echo '</select>';
            ?>
        </div>
    </div> -->
   
   <!--  <label for="name" class="col-sm-2 control-label">DUE DATE</label>
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
                    </div> -->
                    <!-- /btn-group -->
<!--                 <input type="number" class="form-control" id="name" name="unitDueDate" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="In Days is required and cannot be empty">
            </div> -->
            <!-- /input-group -->
        <!-- </div> -->