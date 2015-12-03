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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | SUBCONT ASSIGNMENT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara SUBCONT ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css" />
        <link rel="stylesheet" type="text/css" href="revisionCss/bootstrap-formhelpers.min.css" />
        <!-- BootstrapValidator CSS -->
        <link rel="stylesheet" href="../../css/bootstrapValidator.min.css"/>
        
        <script type="text/javascript">
            function doSubmit(){
                if (confirm('Are you sure you want to submit Subcont Assignment Data?')) {
                    // yes
                    return true;
                } else {
                    // Do nothing!
                    return false;
                }
            }

            function bootstrapValidatorOpen() {
                // body...
                // var validator = $('#attributeForm').validate();
                // validator.resetForm();

                $('#attributeForm').bootstrapValidator();
                
                // Enable validator
                // $(form)
                //     .bootstrapValidator('enableFieldValidators', 'unitDueDate', 'notEmpty', true);
            }
            function ShowDTASIGN() {
                // body...
                // alert("Yeah");
                $("#TglAsign").show();
            }
        </script>
        
        <script src="../../jQuery/jquery-1.11.0.min.js"></script>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../../js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/subcontDropdown.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/bootstrap-formhelpers.js"></script>
        <!-- BootstrapValidator JS -->
        <script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
        
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>ADMIN TOOLS</b></font> | Subcont Assignment <font color="green" size="5"><b>  MULTIPLE QUANTITY</b></font></h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
              <form class="form-horizontal" role="form" id="attributeForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" onSubmit='return doSubmit()' data-bv-message="This value is not valid">
                  
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                  <div class="col-sm-10">
                        <?php
                            $sql_project = "SELECT DISTINCT PROJECT_NAME FROM MASTER_DRAWING WHERE DWG_STATUS = 'ACTIVE' ORDER BY PROJECT_NAME";
                            $proj_result = oci_parse($conn, $sql_project);
                            
                            oci_execute($proj_result);

                            echo '<select class="form-control" name="projName" id="projName" onChange="getComponent(this.value);" data-bv-notempty="true" data-bv-notempty-message="PROJECT NAME is required and cannot be empty">'.'<br>';
                            echo '<option value=" ">'."".'</OPTION>';
                            
                            while($row = oci_fetch_array($proj_result, OCI_ASSOC))
                            {
                                $proj = $row['PROJECT_NAME'];
                                echo "<OPTION VALUE='$proj'>$proj</OPTION>";
                            }      
                            echo '</select>';
                        ?>
                  </div>
                </div>
                  
                <div class="form-group" id="revisionComp"></div>
                <div class="form-group" id="revisionHeadmark"></div>
                <div class="form-group" id="reviseableElements"></div>
                <div class="form-group" id="TglAsign" style="display:none">
                    <label for="ASSIGNDate" class="col-sm-2 control-label"><font color="black">DATE of DWG DOWN</font></label>
                    <div class="col-sm-10">
                        <div id="ASSIGNDate" data-name="ASSIGNDate" class="bfh-datepicker" data-date="today"></div>
                    </div>
                </div>

                <div class="panel-footer" style="overflow:hidden;text-align:right;">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-success btn-sm" name="submitBTN" >
                            <input type="reset" class="btn btn-default btn-sm">
                            <input type="submit" class="btn btn-danger btn-sm" name="delete" value="delete">
                        </div>
                    </div> 
                </div> <!-- panel-footer -->
                  
                </form>
            </div> <!-- panel-body -->  
            <script type="text/javascript">
                $(document).ready(function() {
                    // $('#attributeForm').bootstrapValidator(
                    // // // {
                    // // //     submitHandler: function(validator, form, submitButton) {
                    // // //         // At this point, the form is valid
                    // // //         alert("COCOK"); 
                    // // //     }

                    // // // }
                    // );
                    // $('form').validate({
                    //     rules: {
                    //         unitDueDate: {
                    //             minlength: 3,
                    //             maxlength: 15,
                    //             required: true
                    //         },
                    //         assignedQty: {
                    //             minlength: 3,
                    //             maxlength: 15,
                    //             required: true
                    //         }
                    //     },
                    //     highlight: function(element) {
                    //         $(element).closest('.form-group').addClass('has-error');
                    //     },
                    //     unhighlight: function(element) {
                    //         $(element).closest('.form-group').removeClass('has-error');
                    //     },
                    //     errorElement: 'span',
                    //     errorClass: 'help-block',
                    //     errorPlacement: function(error, element) {
                    //         if(element.parent('.input-group').length) {
                    //             error.insertAfter(element.parent());
                    //         } else {
                    //             error.insertAfter(element);
                    //         }
                    //     }
                    // });

                    // $(':submit').click(function(event){

                    //     $('#attributeForm').bootstrapValidator('validate'); //secondary validation using Bootstrap Validator      
                    //     var bootstrapValidator = $('#attributeForm').data('bootstrapValidator');
                    //     if (bootstrapValidator.isValid()) //if the page fields validate
                    //                 {

                    //                     alert("COCOK");               
                    //                 };  //end if    

                    //                 event.preventDefault();
                    //                 return false;

                    // });//end click
                });

                // $(document).ready(function() {
                //     $("#submit").on('click',function(){
                //           $("#attributeForm").bootstrapValidator();
                //     });
                // });

                $(document).ready(function() {
                    $('input[type="submit"]').attr('disabled','disabled');
                        $('input[type="text"]').keyup(function() {
                            if($(this).val() != '') {
                            $('input[type="submit"]').removeAttr('disabled');
                            }
                        });
                });
            </script>
            <?php
            if (isset($_POST['submitBTN'])){  
                // echo "Dalam Perbaikan";exit();
                
                $projName = strval($_POST['projName']);
                $compName = strval($_POST['compSelect']);
                $subcontId = strval($_POST['subcontAssign']);
                $dueDate = intval($_POST['unitDueDate']);
                $headMarkSelect = strval($_POST['hmSelect']);
                $updateQty = intval($_POST['assignedQty']);
                $ASSIGNDate = strval($_POST['ASSIGNDate']);
                
                        // PROCESS UPDATING MASTER DRAWING
                        $updateSubcontStatusSql = "UPDATE MASTER_DRAWING SET DISTRIBUTION_COUNT = DISTRIBUTION_COUNT + 1 WHERE HEAD_MARK = :HEADMARK AND PROJECT_NAME = :PROJNAME "
                                . "AND COMP_TYPE = :COMPONENT AND DWG_STATUS='ACTIVE'";
                        $updateSubcontStatusParse = oci_parse($conn, $updateSubcontStatusSql);
                        
                        oci_bind_by_name($updateSubcontStatusParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($updateSubcontStatusParse, ":PROJNAME", $projName);
                        oci_bind_by_name($updateSubcontStatusParse, ":COMPONENT", $compName);
                        
                        $updateSubcontStatusRes = oci_execute($updateSubcontStatusParse);
                        
                        if ($updateSubcontStatusRes){
                            oci_commit($conn);
                        } else {
                            oci_rollback($conn);
                        }

                        //SELECT HM 
                        $projectSql = "SELECT WEIGHT,SURFACE,DISTRIBUTION_COUNT,REV FROM MASTER_DRAWING WHERE PROJECT_NAME='$projName' "
                                . " AND HEAD_MARK = '$headMarkSelect' AND COMP_TYPE = '$compName' AND DWG_STATUS='ACTIVE'";
                        $projectParse = oci_parse($conn, $projectSql);
                        oci_execute($projectParse);
                        $row = oci_fetch_array($projectParse);
                        $WEIGHT     = $row['WEIGHT'];
                        $SURFACE    = $row['SURFACE'];
                        $DISTRIB_CNT  = $row['DISTRIBUTION_COUNT'];
                        $REV        = $row['REV'];
                        
                        // PROCESS INSERTION
                        $modSql = "INSERT INTO MASTER_DRAWING_ASSIGNED (ASSIGNED_QTY, SUBCONT_ID, REVISION_NO, ASSIGNMENT_DATE, "
                                . "PROJECT_NAME, ID, HEAD_MARK, ASSIGNED_DUE_DATE, SIGNATURE) "
                                . "VALUES (:UPDATEQTY, "                                
                                . ":SUBCONTID, 0, SYSDATE, :PROJNAME, '$DISTRIB_CNT', "
                                . ":HEADMARK, (SYSDATE + :DUEDATE), '$username')";
                        
                        $insertIntoFabSql = "INSERT INTO FABRICATION (HEAD_MARK, ID, ENTRY_DATE, MARKING, CUTTING, ASSEMBLY, WELDING, "
                                . " DRILLING, FINISHING, PROJECT_NAME, FAB_STATUS, UNIT_QTY, SIGN_DATE) "
                                . "VALUES (:HEADMARK, '$DISTRIB_CNT', "
                                . " TO_DATE('$ASSIGNDate','MM/DD/YYYY'), 0,0,0,0,0,0, :PROJNAME, 'NOTCOMPLETE', "
                                . ":UPDATEQTY, SYSDATE)";                    
                        
                        $insertIntoFabQcSql = "INSERT INTO FABRICATION_QC (HEAD_MARK, ID, MARKING_QC, CUTTING_QC, ASSEMBLY_QC, WELDING_QC,"
                                . " DRILLING_QC, FINISHING_QC,  PROJECT_NAME, FAB_QC_PASS,  UNIT_QTY, FAB_QC_STATUS) "
                                . "VALUES (:HEADMARK, '$DISTRIB_CNT', "
                                . "0,0,0,0,0,0, :PROJNAME, 0, "
                                . ":UPDATEQTY, :SUBCONTID, 'NOTPASSED')";
                        
                        $modParse = oci_parse($conn, $modSql);
                        $insertIntoFabParse = oci_parse($conn, $insertIntoFabSql);
                        $insertIntoFabQcParse = oci_parse($conn, $insertIntoFabQcSql);
                        
                        oci_bind_by_name($modParse, ":UPDATEQTY", $updateQty);
                        oci_bind_by_name($modParse, ":SUBCONTID", $subcontId);
                        oci_bind_by_name($modParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($modParse, ":PROJNAME", $projName);
                        oci_bind_by_name($modParse, ":DUEDATE", $dueDate);
                        
                        oci_bind_by_name($insertIntoFabParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($insertIntoFabParse, ":PROJNAME", $projName);
                        oci_bind_by_name($insertIntoFabParse, ":UPDATEQTY", $updateQty);
                        
                        oci_bind_by_name($insertIntoFabQcParse, ":HEADMARK", $headMarkSelect);
                        oci_bind_by_name($insertIntoFabQcParse, ":PROJNAME", $projName);
                        oci_bind_by_name($insertIntoFabQcParse, ":UPDATEQTY", $updateQty);
                        
                        $modRes = oci_execute($modParse);
                        $insertIntoFabRes = oci_execute($insertIntoFabParse);
                        $insertIntoFabQcRes = oci_execute($insertIntoFabQcParse);

                        
                        if ($modRes && $insertIntoFabRes && $insertIntoFabQcRes){
                            oci_commit($conn);
                        } else {
                            oci_rollback($conn);
                        }
                        
                            $checkerSql = "SELECT MD.TOTAL_QTY AS TOTALQTY, MDA.SUMASSIGNEDQTY 
                                    FROM MASTER_DRAWING MD 
                                    INNER JOIN (SELECT MDA.HEAD_MARK, MDA.PROJECT_NAME, SUM(MDA.ASSIGNED_QTY) AS SUMASSIGNEDQTY 
                                                FROM MASTER_DRAWING_ASSIGNED MDA 
                                                GROUP BY MDA.HEAD_MARK, MDA.PROJECT_NAME)
                                                MDA ON MD.HEAD_MARK = MDA.HEAD_MARK AND MD.PROJECT_NAME = MDA.PROJECT_NAME 
                                    WHERE MD.HEAD_MARK = :HEADMARK AND MD.PROJECT_NAME = :PROJNAME";
                            
                            $checkerParse = oci_parse($conn, $checkerSql);
                            
                            oci_bind_by_name($checkerParse, ":HEADMARK", $headMarkSelect);
                            oci_bind_by_name($checkerParse, ":PROJNAME", $projName);
                            
                            oci_define_by_name($checkerParse, "TOTALQTY", $totalQuantity);
                            oci_define_by_name($checkerParse, "SUMASSIGNEDQTY", $totalAssignedQuantity);
                            
                            oci_execute($checkerParse);
                            
                            while(oci_fetch($checkerParse)){$totalQuantity;} 
                            while(oci_fetch($checkerParse)){$totalAssignedQuantity;} 
                            
                                if ($totalAssignedQuantity == $totalQuantity){                                   
                                    $updateStatusMdSql = "UPDATE MASTER_DRAWING SET SUBCONT_STATUS = 'ASSIGNED' "
                                            . "WHERE HEAD_MARK = :HEADMARK AND PROJECT_NAME = :PROJNAME";
                                    
                                    $updateStatusMdParse = oci_parse($conn, $updateStatusMdSql);
                                    
                                    oci_bind_by_name($updateStatusMdParse, ":HEADMARK", $headMarkSelect);
                                    oci_bind_by_name($updateStatusMdParse, ":PROJNAME", $projName);
                                    
                                    $updateStatusMdRes = oci_execute($updateStatusMdParse);
                                    
                                    if ($updateStatusMdRes){
                                        oci_commit($conn);
                                    } else {
                                        oci_rollback($conn);
                                    }
                                }
            echo "<script>alert('INSERTION');location.href='$_SERVER[PHP_SELF]';</script>";
            }          
            ?>
        </div> <!-- panel-default -->
    </body>
</html>    
