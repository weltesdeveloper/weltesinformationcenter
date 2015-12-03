<?php 
   require_once '../../../../dbinfo.inc.php';
   require_once '../../../../FunctionAct.php';
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

   $HM          = $_POST['HM'];
   $ID          = $_POST['ID'];
   $PROJ_NM     = SingleQryFld("SELECT PROJECT_NAME FROM VW_PNT_INFO WHERE HEAD_MARK = '$HM' AND ID='$ID'",$conn);
   $Qty_opn     = $_POST['Qty_opn'];
   $entryDT_opn = $_POST['entryDT_opn'];
   $memo_opn    = $_POST['memo_opn'];
   $type_opn    = $_POST['type_opn'];

   $len         = sizeof($memo_opn);
   for ($i=0; $i < $len ; $i++) { 
     # code...
     // echo $HM."--".$ID."--$PROJ_NM--".$Qty_opn[$i]."--".$entryDT_opn[$i]."--".$memo_opn[$i]."<hr>";
    $Ins_Opn_Sql = "INSERT INTO PAINTING_OPN(HEAD_MARK,ID,PROJECT_NAME,OPN_DATE,OPN_QTY,OPN_SIGN,SIGN_DATE,MEMO,OPN_TYPE) 
      VALUES (:HEADMARK, :ID, :PROJNAME, TO_DATE('".$entryDT_opn[$i]."','MM/DD/YYYY'), :OPN_QTY, '$username', SYSDATE, :MEMO, :TYPE)";
    $Ins_Opn_Parse = oci_parse($conn, $Ins_Opn_Sql);
    oci_bind_by_name($Ins_Opn_Parse, ":HEADMARK", $HM);
    oci_bind_by_name($Ins_Opn_Parse, ":PROJNAME", $PROJ_NM);
    oci_bind_by_name($Ins_Opn_Parse, ":ID", $ID);
    oci_bind_by_name($Ins_Opn_Parse, ":OPN_QTY", $Qty_opn[$i]);
    oci_bind_by_name($Ins_Opn_Parse, ":MEMO", $memo_opn[$i]);
    oci_bind_by_name($Ins_Opn_Parse, ":TYPE", $type_opn[$i]);

    $Ins_Opn_Res = oci_execute($Ins_Opn_Parse);

    if ($Ins_Opn_Res){
        oci_commit($conn);
        // echo "<script>alert('PACKLIST UPDATED');</script>";
    } else {    
        oci_rollback($conn);
        // echo "<script>alert('PACKLIST NOT UPDATED');</script>";
    }
   }

   if ($len<>0) {
     # code...
    echo "<script>alert('INSERTION SUCCESS')</script>";
   }

?>