 <?php
    require_once '../../../../../dbinfo.inc.php';
    require_once '../../../../../FunctionAct.php';
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

   // HAK AKSES
     $PAINT_ACCS       = HakAksesUser($username,'PAINT_ACCS',$conn);
     if ($PAINT_ACCS<>1) {
       # code...
        echo <<< EOD
       <h1>You Can't ACCESS PAINTING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
       exit;
     }
     
     $jobVal = strval($_POST['jobValue']);
?>

<?php         
    $projectParse = oci_parse($conn, "SELECT DISTINCT PROJECT_NAME_NEW FROM VW_PROJ_INFO "
            . "WHERE PROJECT_NO = :PROJNO AND PROJECT_TYP = 'STRUCTURE' ORDER BY PROJECT_NAME_NEW");
    oci_bind_by_name($projectParse, ":PROJNO", $jobVal);
    oci_execute($projectParse);

    echo '<SELECT name="buildingname" id="buildingSelectDropdown" class="selectpicker form-control"
                  data-style="btn-primary" data-live-search="true">';
    echo '<OPTION VALUE="">SELECT BUILDING</OPTION>';

    while($row = oci_fetch_array($projectParse,OCI_ASSOC)){
    $ProjNme = $row ['PROJECT_NAME_NEW'];
    echo "<OPTION VALUE='$ProjNme'>$ProjNme</OPTION>";
}
echo '</SELECT>';
?>

<script>
      $(document).ready(function(){
        $('.selectpicker').selectpicker({
            width:'auto'
        });
        
        $('#buildingSelectDropdown').on('change', $(this), function () {
            var building = $('#buildingSelectDropdown').val();
            $.ajax({
                type: 'POST',
                url: "InputOpname/showSubcontDropdown.php",
                data: {jobValue: '<?php echo $jobVal; ?>', 
                       buildingValue: building},
                success: function (response) {
                    $('#subcontDropdown').html(response);
                }
            });
        });
      });
</script>