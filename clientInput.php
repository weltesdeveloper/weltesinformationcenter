<?php
   require_once './dbinfo.inc.php';
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
        <title>PT. WELTES ENERGI NUSANTARA | CLIENT INPUT FORM</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara Client Input">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function doSubmit(){
                if (confirm('Are you sure you want to submit Client Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }
        </script>
    
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font color="#CC0000" size="5"><b>PT. WELTES ENERGI NUSANTARA</b></font> ~ <font size="5"><b>NEW CLIENT INPUT FORM</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
              <form class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onSubmit='doSubmit()'>
                  
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">CLIENT ID</label>
                  <div class="col-sm-10">
                    <input type="name" class="form-control" id="name" name="clientId" placeholder="">
                  </div>
                </div>  
                  
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">CLIENT NAME</label>
                  <div class="col-sm-10">
                    <input type="name" class="form-control" id="name" name="clientName" placeholder="">
                  </div>
                </div>
                  
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">CLIENT DESCRIPTION</label>
                  <div class="col-sm-10">
                    <input type="name" class="form-control" id="name" name="clientDescription" placeholder="">
                  </div>
                </div>
                  
                <div class="form-group">
                    <label for="Address" class="col-sm-2 control-label">CLIENT ADDRESS</label>
                    <div class="col-sm-10">
                        <textarea cols="" rows="" class="form-control" name="clientAddress"></textarea>
                    </div>
                </div>
                  
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">CLIENT CONTACT</label>
                  <div class="col-sm-10">
                    <input type="name" class="form-control" id="name" name="clientContact" placeholder="">
                  </div>
                </div>
                  
                <div class="form-group">
                    <label for="phone" class="col-sm-2 control-label">CLIENT PHONE</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="clientPhone" placeholder="">
                        </div>
                    </div>
                </div>
                  
                <div class="form-group">
                    <label for="phone" class="col-sm-2 control-label">CLIENT FAX</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="clientFax" placeholder="">
                        </div>
                    </div>
                </div>
                  
                <!--<div class="form-group">
                    <label for="State" class="col-sm-2 control-label">Client Project</label>
                    <div class="col-sm-10">
                        <?php /*
                            $sql_project = "SELECT DISTINCT PROJECT_NAME FROM MASTER_DRAWING";
                            $proj_result = oci_parse($conn, $sql_project);
                            
                            oci_execute($proj_result);

                            echo '<select class="form-control" name="projectName" id="combobox">'.'<br>';
                            echo '<option value=" ">'."".'</OPTION>';
                            
                            while($row = oci_fetch_array($proj_result, OCI_ASSOC))
                            {
                                $proj = $row['PROJECT_NAME'];
                                echo "<OPTION VALUE='$proj'>$proj</OPTION>";
                            }      
                            echo '</select>'; */
                        ?>
                    </div>
                </div> -->
                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" class="btn btn-success btn-sm" name="submit" >
                              <input type="reset" class="btn btn-default btn-sm">
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->
                </form>
            </div> <!-- panel-body -->  
            <?php
            if (isset($_POST['submit'])){  

                $clientId       = $_POST["clientId"];
                $clientName     = $_POST["clientName"];
                $clientDesc     = $_POST["clientDescription"];
                $clientAddr     = $_POST["clientAddress"];
                $clientCont     = $_POST["clientContact"];
                $clientPhone    = $_POST["clientPhone"];
                $clientFax      = $_POST["clientFax"];

                $revisionHistMdSql = "INSERT INTO CLIENT (CLIENT_NAME, CLIENT_DESCRIPTION, CLIENT_ADDRESS, CLIENT_CONTACT, CLIENT_PHONE1, CLIENT_FAX, CLIENT_ID) "
                        . "VALUES (:CLIENTNAME, :CLIENTDESC, :CLIENTADDR, :CLIENTCONT, :CLIENTPHONE, :CLIENTFAX, :CLIENTIDENT)";
                $revisionHistMdParse = oci_parse($conn, $revisionHistMdSql);
                
                
                oci_bind_by_name($revisionHistMdParse, ":CLIENTNAME", $clientName);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTDESC", $clientDesc);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTADDR", $clientAddr);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTCONT", $clientCont);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTPHONE", $clientPhone);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTFAX", $clientFax);
                oci_bind_by_name($revisionHistMdParse, ":CLIENTIDENT", $clientId);

                $revisionHistMdRes = oci_execute($revisionHistMdParse);

                if ($revisionHistMdRes){
                    oci_commit($conn);
                    echo 'CLIENT SUBMITTED';
                } else {
                    oci_rollback($conn);
                    echo 'SUBMIT ERROR';
                }
            }
            ?>
        </div> <!-- panel-default -->
    </body>
</html>    