<?php
    echo "TIDAK BOLEH BROO";exit();
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
  $sql_project = "SELECT * FROM PAINTING_QC WHERE PAINT_QC_PASS<>0 ORDER BY PROJECT_NAME,HEAD_MARK";
  $proj_result = oci_parse($conn, $sql_project);
  
  oci_execute($proj_result);
  $i = 1;
  while($row = oci_fetch_array($proj_result))
  {
      $jml = SingleQryFld("SELECT COUNT(*) FROM PREPACKING_LIST WHERE HEAD_MARK='$row[HEAD_MARK]' AND PROJECT_NAME='$row[PROJECT_NAME]'",$conn);
      
      if ($jml==0) {
        if ($row['HEAD_MARK'] == "SMS-SW-GR33" or $row['HEAD_MARK'] == "SMS-SW-PR45" or $row['HEAD_MARK'] == "SMS-SW-GR50") {
          # code...
          echo "<h2>ASSIGN 2 KALI BRROOO</h2>";
        }else{
          //INSERT INTO PREPACKING LIST WHEN FINISHED
            $transferToPackingParse = oci_parse($conn, "INSERT INTO PREPACKING_LIST (PROJECT_NAME, HEAD_MARK, UNIT_QTY, ENTRY_DATE, ENTRY_SIGN, PACKING_STATUS) "
            . "VALUES (:projName, :headMark, :unitQty, SYSDATE, '$username', 'NP')");
            oci_bind_by_name($transferToPackingParse, ":projName", $row['PROJECT_NAME']);
            oci_bind_by_name($transferToPackingParse, ":headMark", $row['HEAD_MARK']);
            oci_bind_by_name($transferToPackingParse, ":unitQty", $row['PAINT_QC_PASS']);

            $transferToPackingRes = oci_execute($transferToPackingParse);

            if ($transferToPackingRes){
                oci_commit($conn);
                echo "TRF PACKLIST SUCCESS<br>";
                // echo "<script>alert('TRF PACKLIST SUCCESS');</script>";
            } else {
                oci_rollback($conn);
                echo "TRF PACKLIST FAILED<br>";
                // echo "<script>alert('TRF PACKLIST FAILED');</script>";
            }
        }
        echo "$i >> <b>".$row['PROJECT_NAME']." -- ".$row['HEAD_MARK']."</b><hr>";
        $i++;
      } else {
        // echo $row['PROJECT_NAME']." -- ".$row['HEAD_MARK']."<hr>";
      }  
  }      

 ?>


