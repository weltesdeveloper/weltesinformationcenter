<?php 
   require_once '../../../dbinfo.inc.php';
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

   //$_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
        //if(isset( $_POST['cd-dropdown'])) $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Import</title>

    <!-- Bootstrap -->
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="lib/css/jasny-bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

        <script type="text/javascript">
            function doSubmit(){
                if (confirm('Are you sure you want to submit DATA INTO THE DATABASE ?')) {
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
                <h3 class="panel-title"><font size="5"><b>DATABASE ~ </b></font> <font color="#CC0000" size="5"><b> IMPORT .XLS</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" onSubmit='doSubmit()'>  
                <div class="form-group">  
                    
                    <label for="doNumber" class="col-sm-2 control-label"><font color="black">SELECT FILE TO UPLOAD</font></label>
                    <div class="col-sm-10">
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                        <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="fileinputtodb"></span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                    </div>

                </div>
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
        </div> <!-- panel-default -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../../../jQuery/jquery-1.11.0.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../js/bootstrap.min.js"></script>
    
    <script src="lib/js/jasny-bootstrap.min.js"></script>
            <?php
            if (isset($_POST['submit'])){
                    //  Include PHPExcel_IOFactory
                include '../PHPExcel/IOFactory.php';
              
                //$inputTest = strval($_POST['fileinputtodb']);
                
                    if ($_FILES["fileinputtodb"]["error"] > 0) {
                        echo "Error: " . $_FILES["fileinputtodb"]["error"] . "<br>";
                    } else {
                        echo "Upload: " . $_FILES["fileinputtodb"]["name"] . "<br>";
                        echo "Type: " . $_FILES["fileinputtodb"]["type"] . "<br>";
                        echo "Size: " . ($_FILES["fileinputtodb"]["size"] / 1024) . " kB<br>";
                        echo "Stored in: " . $_FILES["fileinputtodb"]["tmp_name"];
                    }
                
                $inputFileName = $_FILES['fileinputtodb'];

                //  Read your Excel workbook
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                } catch(Exception $e) {
                    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                }

                //  Get worksheet dimensions
                $sheet = $objPHPExcel->getSheet(0); 
                $highestRow = $sheet->getHighestRow(); 
                $highestColumn = $sheet->getHighestColumn();

                //  Loop through each row of the worksheet in turn
                for ($row = 1; $row <= $highestRow; $row++){ 
                    //  Read a row of data into an array
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                    NULL,
                                                    TRUE,
                                                    FALSE);
                    //  Insert row data array into your database of choice here
                }
            }
            ?>
  </body>
</html>
