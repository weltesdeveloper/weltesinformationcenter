<?php
    require_once '../../../../dbinfo.inc.php';
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

<style type="text/css">
  table td{
    text-align:center;
  }
</style>
        <script type="text/javascript">
        function PaintChange(id,type) {
          // alert("OK");
          // first Max Value
          var maxBLASTFrst   = parseInt($('#maxBLASTFrst'+id).attr("value"));
          var maxPRIMERFrst  = parseInt($('#maxPRIMERFrst'+id).attr("value"));
          var maxINTMDFrst   = parseInt($('#maxINTMDFrst'+id).attr("value"));
          var maxFNISHFrst  = parseInt($('#maxFNISHFrst'+id).attr("value"));
          var maxQCPASSFrst = parseInt($('#maxQCPASSFrst'+id).attr("value"));

          // data Value
          var dataBlast     = $('#dataBlast'+id);
          var dataPrimer     = $('#dataPrimer'+id);
          var dataIntermd    = $('#dataIntermd'+id);
          var dataFinishing   = $('#dataFinishing'+id);
          var dataQCPass      = $('#dataQCPass'+id);

          if (type=="BLAST") {
              var Vlue = parseInt(dataBlast.val());
                // alert(Vlue);

              if (isNaN(Vlue) || Vlue > dataBlast.attr("max")) {
                // alert("Bukan NO");

                dataBlast.val('0');
                Vlue = 0;
              }
              // else{
                $('#remBlast'+id).text("["+ parseInt(maxBLASTFrst - Vlue)+"]");

                dataPrimer.attr('max',Vlue+maxPRIMERFrst);
                $('#remPrimer'+id).text("["+ parseInt(dataPrimer.attr("max"))+"]");
                dataPrimer.val("0");

                dataIntermd.attr("max",maxINTMDFrst);
                $('#remINTMD'+id).text("["+ parseInt(dataIntermd.attr("max"))+"]");
                dataIntermd.val("0");

                dataFinishing.attr("max",maxFNISHFrst);
                $('#remFinish'+id).text("["+ parseInt(dataFinishing.attr("max"))+"]");
                dataFinishing.val("0");

                dataQCPass.attr("max",maxQCPASSFrst);
                $('#remQcPass'+id).text("["+ parseInt(dataQCPass.attr("max"))+"]");
                dataQCPass.val("0");
              // }
              
          }

          if (type=="PRIMER") {
              var Vlue = parseInt(dataPrimer.val());

              if (isNaN(Vlue) || Vlue > dataPrimer.attr("max")) {
                // alert("Bukan NO");

                dataPrimer.val('0');
                Vlue = 0;
              }
              // else{
                $('#remPrimer'+id).text("["+ parseInt(dataPrimer.attr("max") - Vlue)+"]");

                dataIntermd.attr("max",Vlue+maxINTMDFrst);
                $('#remINTMD'+id).text("["+ parseInt(dataIntermd.attr("max"))+"]");
                dataIntermd.val("0");

                dataFinishing.attr("max",maxFNISHFrst);
                $('#remFinish'+id).text("["+ parseInt(dataFinishing.attr("max"))+"]");
                dataFinishing.val("0");

                dataQCPass.attr("max",maxQCPASSFrst);
                $('#remQcPass'+id).text("["+ parseInt(dataQCPass.attr("max"))+"]");
                dataQCPass.val("0");
              // }
              
          }

          if (type=="INTMD") {
              var Vlue = parseInt(dataIntermd.val());

              if (isNaN(Vlue) || Vlue > dataIntermd.attr("max")) {
                // alert("Bukan NO");

                dataIntermd.val('0');
                Vlue = 0;
              }
              // else{
                $('#remINTMD'+id).text("["+ parseInt(dataIntermd.attr("max")-Vlue)+"]");

                dataFinishing.attr("max",Vlue+maxFNISHFrst);
                $('#remFinish'+id).text("["+ parseInt(dataFinishing.attr("max"))+"]");
                dataFinishing.val("0");

                dataQCPass.attr("max",maxQCPASSFrst);
                $('#remQcPass'+id).text("["+ parseInt(dataQCPass.attr("max"))+"]");
                dataQCPass.val("0");
              // }
              
          }

          if(type=="FINISH"){
            var Vlue = parseInt(dataFinishing.val());
            if (isNaN(Vlue) || Vlue > dataFinishing.attr("max")) {
              // alert("Bukan NO");
              dataFinishing.val('0');
              Vlue = 0;
            }
            $('#remFinish'+id).text("["+ parseInt(dataFinishing.attr("max")-Vlue)+"]");

            dataQCPass.attr("max",Vlue+maxQCPASSFrst);
            $('#remQcPass'+id).text("["+ parseInt(dataQCPass.attr("max"))+"]");
            dataQCPass.val("0");
          }

          if (type=="QCPASS") {
            var Vlue = parseInt(dataQCPass.val());
            if (isNaN(Vlue) || Vlue > dataQCPass.attr("max")) {
              // alert("Bukan NO");
              dataQCPass.val('0');
              Vlue = 0;
            }
            $('#remQcPass'+id).text("["+ parseInt(dataQCPass.attr("max")-Vlue)+"]");
          }
        }
        </script> 
    <!-- </head> -->
<!-- ================================================================================ -->
<!-- ================================================================================ -->
<!-- <body> -->
    <?php                
  // IF SHOW KEY HAS BEEN PRESSED
  if($_POST['action'] == 'show')
  {
    // AND DTL_FABR.FAB_PASS_QTY != DTL_FABR.FINISH 
    $tableSql = "
    SELECT PROJECT_NAME,
       HEAD_MARK,
       PROFILE,
       ID,
       TOTAL_QTY,
       PNT_QTY as FAB_PASS_QTY,
       SUBCONT_ID,
       BLASTING as BLAST,
       PRIMER as PRIMER,
       INTERMEDIATE as INTMD,
       FINISHING as FINISH,
       PAINT_QC_PASS
    FROM VW_PNT_INFO       
    WHERE PROJECT_NAME= '{$_POST["ProjNme"]}' AND PNT_QTY <> PAINT_QC_PASS ORDER BY HEAD_MARK,ID";
    $tableParse = oci_parse($conn, $tableSql);
    oci_execute($tableParse);
    ?>
    <!-- <table border="1"> -->
      <table class="table-bordered table-condensed table-hover table-striped" cellspacing="0" cellpadding="0" id="paintTabel" style="width:100%;" >
        <thead>
          <tr>
            <th style="text-align:center;">Head Mark</th>
            <th style="text-align:center;">Profile</th>
            <th style="text-align:center;">ID</th>
            <th style="text-align:center;">Tot Qty</th>
            <th style="text-align:center;">Fab QC Pass Qty</th>
            <th style="text-align:center;">Subcont</th>
            <th style="text-align:center;">Blasting</th>
            <th style="text-align:center;">Primer</th>
            <th style="text-align:center;">Intermediate</th>
            <th style="text-align:center;">Finishing</th>
            <th style="text-align:center;background-color: #EA6767;">QC Pass</th>
            <th style="text-align:center;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 0;
          while ($row = oci_fetch_assoc($tableParse)){
            // echo $row['FAB_PASS_QTY']."==".$row['BLAST']."<br>";
              $availableBlast     = $row['FAB_PASS_QTY'] - $row['BLAST']; 
              $availablePrimer    = $row['BLAST'] - $row['PRIMER'];
              $availableIntermd   = $row['PRIMER'] - $row['INTMD'];
              $availableFinishing = $row['INTMD'] - $row['FINISH'];

              $availableQCPass    = $row['FINISH'] - $row['PAINT_QC_PASS'];
              // $buttonChecker = $row['BLAST'] = $row['PRIMER'] = $row['INTMD'] = $row['FINISH'];
              
           ?>
           
          <tr id="<?php echo "baris$i" ?>">
            <td style="text-align:left;" ><font size='2'><b><?php echo $row["HEAD_MARK"] ?></b></font></td>
            <td style="text-align:left;" ><font size='2'><b><?php echo $row["PROFILE"] ?></b></font></td>
            <td ><font size='2' color='#009933' id="<?php echo "HM_ID$i" ?>"><b><?php echo $row["ID"] ?></b></font></td>
            <td ><font size='2' color='#009933'><b><?php echo $row["TOTAL_QTY"] ?></b></font></td>
            <td ><font size='2' color='#0000FF' id="<?php echo "FabPassQTY$i" ?>"><b><?php echo $row["FAB_PASS_QTY"] ?></b></font></td>
            <td >
              <font size='2' ><b>
                <?php echo $row["SUBCONT_ID"] ?>
              </b></font>
            </td>
            <td >
              <input type="hidden" id="<?php echo "maxBLASTFrst$i" ?>" value="<?php echo $availableBlast ?>">
              <?php 
              // echo $row['FAB_PASS_QTY']."==".$row['BLAST'];
              if ($row['FAB_PASS_QTY'] === $row['BLAST']) {
                # code...
                ?>
                <img src='../../../../images/fabDone.png' width='20' height='20'>
                <input  name="<?php echo "dataBlast$i" ?>" id="<?php echo "dataBlast$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableBlast ?>' width='10'>
              <?php
              }else{
               ?>
                <input onchange="PaintChange('<?php echo $i ?>','BLAST');ValidateDbleInput('<?php echo $i ?>','BLASTING','<?php echo $row['BLAST'] ?>','<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataBlast$i" ?>" class='form-control' id="<?php echo "dataBlast$i" ?>" type='number' min='0' value="0" max='<?php echo $availableBlast ?>' style="width:70px;">
                <sup id="<?php echo "remBlast".$i; ?>">[<?php echo $availableBlast ?>]</sup>
              <?php 
              } ?>
            </td>
            <td >
              <input type="hidden" id="<?php echo "maxPRIMERFrst$i" ?>" value="<?php echo $availablePrimer ?>">
              <?php 
              if ($row['FAB_PASS_QTY'] === $row['PRIMER']) {
                # code...
                ?>
                <img src='../../../../images/fabDone.png' width='20' height='20'>
                <input name="<?php echo "dataPrimer$i" ?>" id="<?php echo "dataPrimer$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availablePrimer ?>' width='10'>
              <?php
              }else{
               ?>
                <input onchange="PaintChange('<?php echo $i ?>','PRIMER');ValidateDbleInput('<?php echo $i ?>','PRIMER','<?php echo $row['PRIMER'] ?>','<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataPrimer$i" ?>" class='form-control' id="<?php echo "dataPrimer$i" ?>" type='number' min='0' value="0" max='<?php echo $availablePrimer ?>' style="width:70px;">
                <sup id="<?php echo "remPrimer".$i; ?>">[<?php echo $availablePrimer ?>]</sup>
              <?php 
              } ?>
            </td>
            <td >
              <input type="hidden" id="<?php echo "maxINTMDFrst$i" ?>" value="<?php echo $availableIntermd ?>">
              <?php 
              if ($row['FAB_PASS_QTY'] === $row['INTMD']) {
                 # code...
                ?>
                <img src='../../../../images/fabDone.png' width='20' height='20'>
                <input  name="<?php echo "dataIntermd$i" ?>" id="<?php echo "dataIntermd$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableIntermd ?>' width='10'>
              <?php
               }else{
              ?>
                <input onchange="PaintChange('<?php echo $i ?>','INTMD');ValidateDbleInput('<?php echo $i ?>','INTERMEDIATE','<?php echo $row['INTMD'] ?>','<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataIntermd$i" ?>" class='form-control' id="<?php echo "dataIntermd$i" ?>" type='number' min='0' value="0" max='<?php echo $availableIntermd ?>' style="width:70px;">
                <sup id="<?php echo "remINTMD".$i; ?>">[<?php echo $availableIntermd ?>]</sup>
              <?php
              } ?>
            </td>
            <td >
              <input type="hidden" id="<?php echo "maxFNISHFrst$i" ?>" value="<?php echo $availableFinishing ?>">
              <input type="hidden" id="<?php echo "ValueFNISHFrst$i" ?>" value="<?php echo $row['FINISH'] ?>">
              <?php 
              if ($row['FAB_PASS_QTY'] === $row['FINISH']) {
                 # code...
                ?>
                <img src='../../../../images/fabDone.png' width='20' height='20'>
                <input name="<?php echo "dataFinishing$i" ?>" id="<?php echo "dataFinishing$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableFinishing ?>' width='10'>
              <?php
               }else{
              ?>
              <input onchange="PaintChange('<?php echo $i ?>','FINISH');ValidateDbleInput('<?php echo $i ?>','FINISHING','<?php echo $row['FINISH'] ?>','<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataFinishing$i" ?>" class='form-control' id="<?php echo "dataFinishing$i" ?>" type='number' min='0' value="0" max='<?php echo $availableFinishing ?>' style="width:70px;">
              <sup id="<?php echo "remFinish".$i; ?>">[<?php echo $availableFinishing ?>]</sup>
              <?php 
              } ?>
            </td>
            <td style="background-color: #EA6767;">
              <input type="hidden" id="<?php echo "maxQCPASSFrst$i" ?>" value="<?php echo $availableQCPass ?>">
              <input type="hidden" id="<?php echo "ValueQCPASSFrst$i" ?>" value="<?php echo $row['PAINT_QC_PASS'] ?>">
              <?php 
              if ($row['FAB_PASS_QTY'] === $row['PAINT_QC_PASS']) {
                 # code...
                ?>
                <img src='../../../../images/fabDone.png' width='20' height='20'>
                <input name="<?php echo "dataQCPass$i" ?>" id="<?php echo "dataQCPass$i" ?>" type='hidden' min='0' value="0" max='<?php echo $availableQCPass ?>' width='10'>
              <?php
               }else{
              ?>
              <input onchange="PaintChange('<?php echo $i ?>','QCPASS');ValidateDbleInput('<?php echo $i ?>','PAINT_QC_PASS','<?php echo $row['PAINT_QC_PASS'] ?>','<?php echo $row['HEAD_MARK'] ?>');" name="<?php echo "dataQCPass$i" ?>" class='form-control' id="<?php echo "dataQCPass$i" ?>" type='number' min='0' value="0" max='<?php echo $availableQCPass ?>' style="width:70px;">
              <sup id="<?php echo "remQcPass".$i; ?>">[<?php echo $availableQCPass ?>]</sup>
              <?php 
              } ?>
            </td>
             <?php 
              if ($row['PAINT_QC_PASS'] === $row['FAB_PASS_QTY']){
                echo "<td><input type='button' class='btn btn-success btn-default btn-sm' name='submit' id='submit' value='PAINTDONE' disabled></td>";
              } else {
                ?>
                <!--  -->
                <td>
                  <input type='button' onclick="return doSubmit('<?php echo $i ?>','<?php echo $row['HEAD_MARK'] ?>');" class='btn btn-success btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='DOUBLE CHECK YOUR DATA BEFORE SUBMITTING !!!' name='submit<?php echo $i ?>' id='submit<?php echo $i ?>' value='SUBMIT !'>
                </td>
              <?php
              }
              ?>
          </tr>
           <?php 
            $i++;
           } 
           ?>
        </tbody>
      </table>
      <div id="ValidateDBLE"></div>
    <!-- </table> -->
<?php
  }//===> END OF 'SHOW'
?>
<script type="text/javascript">
    function doSubmit(id,HEAD_MARK){
        if (confirm('Are you sure you want to submit Painting '+HEAD_MARK+' Data?')) {
            // yes
            // alert("Yes ID = "+id);
            // alert("Ohhh");
            // data Value
            var HM_ID             = $('#HM_ID'+id);
            var FabPassQTY        = $('#FabPassQTY'+id);
            var dataBlast         = $('#dataBlast'+id);
            var dataPrimer        = $('#dataPrimer'+id);
            var dataIntermd       = $('#dataIntermd'+id);
            var dataFinishing     = $('#dataFinishing'+id);
            var ValueFNISHFrst    = $('#ValueFNISHFrst'+id);
            var dataQCPass        = $('#dataQCPass'+id);   
            var ValueQCPASSFrst   = $('#ValueQCPASSFrst'+id);

            $.post('processExceededQty.php',
              {
                submit : "show",
                HM_ID: HM_ID.text(),
                FabPassQTY: FabPassQTY.text(),
                no: id,
                HEAD_MARK: HEAD_MARK,
                dataBlast: dataBlast.val(),
                dataPrimer: dataPrimer.val(),
                dataIntermd: dataIntermd.val(),
                dataFinishing: dataFinishing.val(),
                ValueFNISHFrst: ValueFNISHFrst.val(),
                dataQCPass: dataQCPass.val(),
                ValueQCPASSFrst: ValueQCPASSFrst.val(),
                ProjNme:$('#ProjNme').val()
              },
              function(res){
                  $('#baris'+id).html(res);
              }
            );

            return true;
        } else {
            // Do nothing!
            // alert("NO");
            return false
        }
    }
    function showAlertFAB(altrt) {
      // alert(altrt);
    }
    function showDoubleInput(id,HEAD_MARK) {
      // body...
      // alert("Beda OM");
      var HM_ID           = $('#HM_ID'+id);

      $.post('processExceededQty.php',
        {
          HM_ID: HM_ID.text(),
          no: id,
          HEAD_MARK: HEAD_MARK,
          ProjNme:$('#ProjNme').val()
        },
        function(res){
            $('#baris'+id).html(res);
        }
      );
    }
    function ValidateDbleInput(id,type,firstQTY,HEAD_MARK) {
      // body...
      var HM_ID           = $('#HM_ID'+id);

      $.post("ValidateDoubleInput.php",{
          ProjNme:$('#ProjNme').val(),
          no: id,
          firstQTY: firstQTY,
          HEAD_MARK: HEAD_MARK,
          HM_ID: HM_ID.text(),
          type: type
        },
        function(res){
            $('#ValidateDBLE').html(res);
        }
      );
    }
    $('#paintTabel').dataTable(
      {
          "bPaginate": true,
          "bLengthChange": false,
          "iDisplayLength": 50,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": true   
      }
    );
</script>
