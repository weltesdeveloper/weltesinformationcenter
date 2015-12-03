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
?>
<?php  
$action = $_POST['action'];
switch ($action) {
  case 'show_insert_rems':
    $indx_row   = $_POST['indx_row'];
    $indx_col   = $_POST['indx_col'];
    $title      = $_POST['title'];
    $HM         = $_POST['HM'];
    $HM_ID      = $_POST['HM_ID'];

    $today      = date("m/d/Y");
    ?>
      <div class="form-group">
        <table class="table">
          <thead>
            <tr>
              <td colspan="3"><?php echo "REMAKS ".$title." DELAY" ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo $HM ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>REMS DATE</td>
              <td>:</td>
              <td><input type="text" class="form-control" readonly="" id="fab_rems_date<?php echo $indx_row."_".$indx_col ?>" value="<?php echo date("m/d/Y") ?>"></td>
            </tr>
            <tr>
              <td>REMS DESC</td>
              <td>:</td>
              <td>
                <select class="form-control" id="fab_rems<?php echo $indx_row."_".$indx_col ?>">
                    <option selected disabled value="">[select remarks]</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <button type="button" class="btn-xs btn-info" id="link_btn<?php echo $indx_row."_".$indx_col ?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> show hist remaks</button>
              </td>
              <td colspan="2">
                <button type="button" class="btn-xs btn-success" id="submit_btn<?php echo $indx_row."_".$indx_col ?>">SUBMIT REMS</button>
                <button type="button" class="btn-xs btn-danger" id="close_btn<?php echo $indx_row."_".$indx_col ?>">CANCEL</button>
              </td>
            </tr>
            <tr>
              <td colspan="3" id="td_hist_rems<?php echo $indx_row."_".$indx_col ?>">
                &nbsp;
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          var indx_row  = "<?php echo $indx_row ?>";
          var indx_col  = "<?php echo $indx_col ?>";
          var HM        = "<?php echo $HM ?>" ;
          var HMID      = "<?php echo $HM_ID ?>";
          var proc_sub_typ = 'MARK';
          if (indx_col == 1) {
            proc_sub_typ = 'CUT';
          } else if(indx_col == 2){
            proc_sub_typ = 'ASSY';
          } else if(indx_col == 3){
            proc_sub_typ = 'WELD';
          } else if(indx_col == 4){
            proc_sub_typ = 'DRILL';
          } else if(indx_col == 5){
            proc_sub_typ = 'FAB FINS';
          }
          // console.log(indx_row+' -- '+indx_col);

          $('#close_btn'+indx_row+'_'+indx_col).click(function(){
            // console.log($(this).text());
            var btn = $('#div_rems_hist'+indx_row+'_'+indx_col).find('.morphbutton-close');
            btn.click();
            // console.log(btn);
          });

          $('#fab_rems_date'+indx_row+'_'+indx_col).datepick({
              dateFormat: 'mm/dd/yyyy',
              renderer: $.extend({}, $.datepick.defaultRenderer, 
              {picker: $.datepick.defaultRenderer.picker. 
                  replace(/\{link:clear\}/, '')})
          });

          // ACTIVE eComboBox
            $('#fab_rems'+indx_row+'_'+indx_col).append($('#fab_sel_rems_utma').html());
            $('#fab_rems'+indx_row+'_'+indx_col).eComboBox({
                'allowNewElements' : true,      // default : true
                'editableElements' : false      // default : true
            });
            $('#fab_rems'+indx_row+'_'+indx_col).find('option:contains({NEW ELEMENT})').css('color','blue');
            var td_rems = $('#fab_rems'+indx_row+'_'+indx_col).parents('td');
            $(td_rems).find('input').attr('class','form-control');
            $(td_rems).find('input').css('width','100%');
            $(td_rems).find('input').on('keypress',function(e){
                if(e.which == 13) {
                    // console.log($(this).val());
                    var ths_val         = $(this).val();
                    var ths_td          = $(this).parents('td'); 
                    var ths_td_id       = ths_td.attr('id');

                    if (ths_val.trim() == '') { 
                      $('#fab_rems'+indx_row+'_'+indx_col).val('');
                      return false;
                    }

                    $.post('update_remaks_hist.php',{
                            action : 'add_remarks',
                            remaks : ths_val,
                            rems_type : 'FAB'
                        },
                        function(res){
                            if (res == 'success') {
                                $('#fab_sel_rems_utma').append('<option>'+ths_val+'</option>');
                            }
                        }
                    )                                                                     
                    
                }
            });

          // Submit REms
            $('#submit_btn'+indx_row+'_'+indx_col).click(function(){
              if ($('#fab_rems'+indx_row+'_'+indx_col).val() == null || $('#fab_rems'+indx_row+'_'+indx_col).val() == '{NEW ELEMENT}') {
                  alert('please select remaks');
                  $('#fab_rems'+indx_row+'_'+indx_col).focus();
              }else{
                  var rems    = $('#fab_rems'+indx_row+'_'+indx_col).val().trim();
                  var rems_dt = $('#fab_rems_date'+indx_row+'_'+indx_col).val().trim();
              
                  var sentData = {
                      action          :'add_md_remaks',
                      HM              : HM,
                      HMID            : HMID,
                      REMS            : rems,
                      REMS_DT         : rems_dt,
                      PROC_TYP        : 'FAB',
                      PROC_SUB_TYP    : proc_sub_typ
                  };
                  console.log(sentData);
                  $.ajax({
                      type: 'POST',
                      url: "update_remaks_hist.php",
                      data: sentData,
                      success: function (response, textStatus, jqXHR) {
                          if (response == 'success') {
                              var today = "<?php echo $today ?>";
                              if (today == rems_dt ) {
                                $('#btn-rems'+indx_row+'_'+indx_col).removeClass('btn-default');
                                $('#btn-rems'+indx_row+'_'+indx_col).addClass('btn-success');
                              }
                          }else{
                              $('#btn-rems'+indx_row+'_'+indx_col).removeClass('btn-default');
                              $('#btn-rems'+indx_row+'_'+indx_col).addClass('btn-danger');
                              alert('ERRORR : '+response);
                          }
                          $('#close_btn'+indx_row+'_'+indx_col).click();
                      }
                  });
              } 
            });
          
          // show history rems
            $('#link_btn'+indx_row+'_'+indx_col).click(function(){
              var sentData = {
                  action          :'show_md_remaks',
                  HM              : HM,
                  HMID            : HMID,
                  PROC_TYP        : 'FAB',
                  PROC_SUB_TYP    : proc_sub_typ,
                  title           : "<?php echo $title ?>"
              };
              // console.log(sentData);
              $.ajax({
                  type: 'POST',
                  url: "update_remaks_hist.php",
                  data: sentData,
                  success: function (response, textStatus, jqXHR) {
                      $('#td_hist_rems'+indx_row+'_'+indx_col).html(response);
                  }
              });
            });

        });
      </script>
    <?php 
    break;

  case 'add_remarks':
    $remaks     = $_POST['remaks'];
    $rems_type  = $_POST['rems_type'];
      $duplicate = SingleQryFld("SELECT COUNT(*) FROM MST_REMAKS WHERE REMS_TYPE='$rems_type' AND REMS_DESC='$remaks'",$conn);
      if ($duplicate > 0) {
        echo "duplicate";
      } else {
        $sql = "INSERT INTO MST_REMAKS(REMS_TYPE,REMS_DESC) VALUES ('$rems_type','$remaks')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        if ($parse) {
          oci_commit($conn);
          echo "success";
        } else {
          oci_rollback($conn);
          echo "failed";
        }        
      }      
    break;

  case 'add_md_remaks':
    $HM             = $_POST['HM'];
    $HMID           = $_POST['HMID'];
    $REMS           = $_POST['REMS'];
    $REMS_DT        = $_POST['REMS_DT'];
    $PROC_TYP       = $_POST['PROC_TYP'];
    $PROC_SUB_TYP   = $_POST['PROC_SUB_TYP'];
      $sql = "SELECT COUNT(*) FROM MD_PROC_DELAY_REMS WHERE HEAD_MARK = '$HM' AND ID='$HMID' AND ENTRY_DATE = TO_DATE('$REMS_DT','MM/DD/YYYY') AND PROC_TYPE = '$PROC_TYP' AND PROC_SUB_TYPE = '$PROC_SUB_TYP' ";
      // echo "$sql";
      $duplicate = SingleQryFld($sql,$conn);
      if ($duplicate > 0) {
        $sql = "UPDATE MD_PROC_DELAY_REMS SET REMS = '$REMS',ENTRY_SIGN = '$username' WHERE HEAD_MARK = '$HM' AND ID='$HMID' AND ENTRY_DATE = TO_DATE('$REMS_DT','MM/DD/YYYY') AND PROC_TYPE = '$PROC_TYP' AND PROC_SUB_TYPE = '$PROC_SUB_TYP'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        if ($parse) {
          oci_commit($conn);
          echo "success";
        } else {
          oci_rollback($conn);
          echo "failed";
        }
      } else {
        $sql = "INSERT INTO MD_PROC_DELAY_REMS(HEAD_MARK,ID,REMS,ENTRY_DATE,ENTRY_SIGN,PROC_TYPE,PROC_SUB_TYPE) values ('$HM','$HMID','$REMS',TO_DATE('$REMS_DT','MM/DD/YYYY'),'$username','$PROC_TYP','$PROC_SUB_TYP')";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        if ($parse) {
          oci_commit($conn);
          echo "success";
        } else {
          oci_rollback($conn);
          echo "failed";
        }        
      }
    break;
  
  case 'show_md_remaks':
    $HM             = $_POST['HM'];
    $HMID           = $_POST['HMID'];
    $PROC_TYP       = $_POST['PROC_TYP'];
    $PROC_SUB_TYP   = $_POST['PROC_SUB_TYP'];
    $title          = $_POST['title'];
    ?>
      <table class="table-bordered compact" width="100%">
        <thead style="background-color:gray;">
          <tr>
            <th colspan="3" class="text-center"><?php echo "REMAKS ".$title." DELAY HISTORY" ?></th>
          </tr>
          <tr>
            <th width="75px">DATE</th>
            <th width="100px">SIGN</th>
            <th>DESCRIPTION</th>
          </tr>
        </thead>
        <tbody class="active">
          <?php 
            $sql = "SELECT * FROM MD_PROC_DELAY_REMS WHERE HEAD_MARK = '$HM' AND ID='$HMID' AND PROC_TYPE = '$PROC_TYP' AND PROC_SUB_TYPE='$PROC_SUB_TYP' ORDER BY ENTRY_DATE ";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            while ($row = oci_fetch_array($parse)) {
              ?>
              <tr>
                <td ><?php echo $row['ENTRY_DATE'] ?></td>
                <td ><?php echo $row['ENTRY_SIGN'] ?></td>
                <td ><?php echo $row['REMS'] ?></td>
              </tr>
              <?php
            }
          ?>
        </tbody>
      </table>
    <?php
    break;
  default:
    # code...
    break;
}
?>