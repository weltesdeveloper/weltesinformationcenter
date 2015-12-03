<?php
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

   $coliNumber = strval($_GET['coliNumber']);

   //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'barcode/temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'barcode/temp/';

    include "barcode/qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'H';
    // if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
    //     $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 7;
    // if (isset($_REQUEST['size']))
    //     $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

    
    //it's very important!
    if (trim($coliNumber) == '')
        die('data cannot be empty! <a href="?">back</a>');
        
    // user data
    $filename = $PNG_TEMP_DIR.md5($coliNumber).'.png';
    if (!file_exists($filename)) {
      # code...
      QRcode::png($coliNumber, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
    }    
    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PT. WELTES ENERGI NUSANTARA | COLI BARCODE</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PT. Weltes Energi Nusantara PACKING ASSIGNMENT">
  <meta name="author" content="Chris Immanuel">

  <!-- Le styles -->
  <link rel="icon" type="image/ico" href="../favicon.ico">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="revisionCss/own.css">
  <style>
  body{
    background-color: #F8F8F8;
  }
  .center {
      margin-left: auto;
      margin-right: auto;
      width: 100%;
      background-color: #fff;
      /*height: auto;*/
      vertical-align: middle;
  }
  </style>
</head>
<!-- onload="window.print();" -->
<body >
<script type="text/javascript">
  function ShowHide() {
    // alert("OKKK");
    // body...
    var PCKSize = $("#PCKSize");
    var PCKSize2 = $("#PCKSize2");
    if (PCKSize.is(":visible")) {
      PCKSize.hide();
      PCKSize2.hide();
      $("#SHPack").text("+");
    }else{
      PCKSize.show();
      PCKSize2.show();
      $("#SHPack").text("-");
    }
  }
</script>
<?php 
    $sql = "SELECT PCK.* FROM MST_PACKING PCK WHERE PCK.COLI_NUMBER = '$coliNumber' ";
    // echo "$sql";
    $sqlPck = oci_parse($conn, $sql);
    oci_execute($sqlPck);
    $rowPck = oci_fetch_array($sqlPck,OCI_BOTH);

        $PACKING_LENGTH     = $rowPck['PACK_LEN'];
        $PACKING_WIDTH      = $rowPck['PACK_WID'];
        $PACKING_HEIGHT     = $rowPck['PACK_HT'];
        $PROJECT_NAME       = $rowPck['PROJECT_NAME'];
        $ZON_LOC            = $rowPck['ZON_LOC'];
        $PACK_TYP           = $rowPck['PACK_TYP'];
        $BOX_WT             = $rowPck['BOX_WT'];
        $PACKING_VOLUME     = round((($PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT)/1000000000),2);
        $PACKING_WEIGHT     = SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM WELTESADMIN.DTL_PACKING WHERE COLI_NUMBER='$coliNumber'",$conn);  

 ?>
  <div class="container">
    <table align="center">
      <thead>
        <tr>
          <th>
            <div class="row center">
              <div class="col-xs-8">
                <div class="row center">
                  <img src="img_packing/weltesLogo.jpg">
                </div>
                <div class="row center">
                  <h1><b><?php echo $coliNumber; ?></b></h1>
                </div>
                <div class="row" style="text-align:left;" id="PCKSize">
                  <div class="col-xs-4">
                    <h5>Length: <b><?php echo $PACKING_LENGTH ?></b> <small>mm</small></h5>
                  </div>
                  <div class="col-xs-4">
                    <h5>Width: <b><?php echo $PACKING_WIDTH ?></b> <small>mm</small></h5>
                  </div>
                  <div class="col-xs-4">
                    <h5>Height: <b><?php echo $PACKING_HEIGHT ?></b> <small>mm</small></h5>
                  </div>
                </div>
                <div class="row" style="text-align:center;" id="PCKSize">
                  <div class="col-xs-6">
                    <h5>Gross Weight: <b><?php echo $PACKING_WEIGHT+$BOX_WT ?></b> <small>kg</small></h5>
                  </div>
                  <div class="col-xs-6">
                    <h5>Net Weight: <b><?php echo $PACKING_WEIGHT?></b> <small>kg</small></h5>
                  </div>
                </div>          
              </div>
              <div class="col-xs-4 table-bordered" style="text-align:center;">
                <?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" width="190" height="190" />'; ?>
              </div>
            </div>   
          </th>
        </tr>
        <tr>
          <th>
            <div class="row center">
              <!-- <div class="col-xs-8"><h5><div style="cursor:pointer;" onclick="ShowHide();">Packing Size; <b><label id="SHPack">-</label></b></div></h5></div> -->
              <div class="col-xs-12" style="text-align:right;"><h5>Packing Type: <b><?php echo $PACK_TYP ?></b></h5></div>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="row center">
              <div class="col-xs-12 table-bordered" style="padding-top:17px;">
                <table class="table table-striped table-condensed table-bordered">
                 <thead>
                    <tr>
                        <th style="text-align:center;">Head Mark</th>
                        <th style="text-align:center;">QTY (pcs)</th>
                        <th style="text-align:center;">Head Mark</th>
                        <th style="text-align:center;">QTY (pcs)</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                    $j =  0;
                    $query = "SELECT * from WELTESADMIN.MASTER_DRAWING LEFT JOIN WELTESADMIN.DTL_PACKING ON WELTESADMIN.MASTER_DRAWING.HEAD_MARK=WELTESADMIN.DTL_PACKING.HEAD_MARK where WELTESADMIN.DTL_PACKING.COLI_NUMBER='".$coliNumber."' order By WELTESADMIN.DTL_PACKING.HEAD_MARK";
                    // echo "$query";
                    $sqlPPck    = oci_parse($conn, $query);
                    oci_execute($sqlPPck);
                    while ($rowPPck = oci_fetch_array($sqlPPck,OCI_BOTH)) {

                        $HEAD_MARK  = $rowPPck['HEAD_MARK'];
                        $COMP_TYPE  = $rowPPck['COMP_TYPE'];
                        $PROFILE    = $rowPPck['PROFILE'];
                        $OVLENGTH   = $rowPPck['LENGTH'];
                        $UNIT_QTY   = $rowPPck['UNIT_PCK_QTY'];
                        $UNIT_WEIGHT= $rowPPck['WEIGHT'];
                        $SubTotWg   = $rowPPck['UNIT_PCK_WT'];
                        // $TotWg += $SubTotWg;
                        // echo "$j == $jml<br>";
                        // if ($j==$jml) {
                            // $fnlTotWg    = $TotWg;
                        // }

                        for ($i=0; $i < 10 ; $i++) { 
                          # code...
                          if ($j%2 == 0) {
                              # code...
                            
                        ?>
                        <tr class="isi">
                            <td style="width:auto;"><?php echo $HEAD_MARK ?></td>
                            <td><?php echo $UNIT_QTY ?></td>
                        <?php 
                            }else{
                              ?>
                            <td style="width:auto;"><?php echo $HEAD_MARK ?></td>
                            <td><?php echo $UNIT_QTY ?></td>
                        </tr>
                              <?php
                            }
                        $j++;
                        }  
                    } ?>
                 </tbody>
                </table>
              </div>
            </div>
          </td>
        </tr>  
      </tbody>
    </table>        
  </div>
  <!-- JS SRC -->
        <script src="../js/bootstrap.min.js"></script>
        <script src="../jQuery/jquery-1.11.0.min.js"></script>
</body>
</html>