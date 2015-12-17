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

   $HM          = $_GET['HM'];
   $ID          = $_GET['ID'];
   $SURF        = $_GET['SURF'];
   $FAB_PASS    = $_GET['FAB_PASS'];
   $PAINT_PASS  = SingleQryFld("SELECT PAINT_QC_PASS FROM VW_PNT_INFO WHERE HEAD_MARK = '$HM' AND ID='$ID'",$conn);
   $SUBCONT     = $_GET['SUBCONT'];

   $AvlOpn_bls  = SingleQryFld("SELECT SUM(OPN_QTY) FROM PAINTING_OPN WHERE HEAD_MARK = '$HM' AND ID='$ID' AND OPN_TYPE='BLAST'",$conn);
   $AvlOpn_pnt  = SingleQryFld("SELECT SUM(OPN_QTY) FROM PAINTING_OPN WHERE HEAD_MARK = '$HM' AND ID='$ID' AND OPN_TYPE='PAINT'",$conn);

?>
<style type="text/css">
    .table thead tr th{
        text-align: center;
        vertical-align:middle;
    }
    #OPN_tbl_wrapper {
      /*background-color: #91D2E6;*/
      /*width: 96%;*/
      height: 350px;
      overflow-y: scroll; 
    }
    .opndiv table thead {
      background-color: #D7D2CE;
    }
    .opndiv table thead tr th.batas {
      background-color: #FFF;
      width: 5px;
    }
    .opndiv table tbody tr td {
      text-align: left;
      background-color: #FFFCD6;
    }
    .opndiv table tbody tr td.batas {
      background-color: #FFF;
      width: 5px;
    }
</style>
<link href="../../../../css/bootstrap-formhelpers.min.css" rel="stylesheet" type="text/css" />
<script src="../../../../js/bootstrap-formhelpers.js" language="javascript" type="text/javascript"  ></script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>OPNAME</b> <small>PAINTING</small> -> <b><?php echo $HM ?></b></h4>
    <div class="row">
      <div class="col-xs-3">
        <h5 class="modal-title">Subcont;<br><b><?php echo $SUBCONT;?></b></h5>
      </div>
      <div class="col-xs-3">
        <h5 class="modal-title">Unit Surface;<br><b><?php echo number_format($SURF,2);?></b></h5>
      </div>
      <div class="col-xs-3">
        <h5 class="modal-title">Fab QC;<br><b><?php echo number_format($FAB_PASS,0);?></b></h5>
      </div>
      <div class="col-xs-3">
        <h5 class="modal-title">Paint QC;<br><b><?php echo number_format($PAINT_PASS,0);?></b></h5>
      </div>
    </div>
</div>
<div class="modal-header opndiv">
  <table class="table-condensed" align="center" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th colspan="2"><h6>BLASTING</h6></th>
        <th class="batas">&nbsp;</th>
        <th colspan="2"><h6>PAINTING</h6></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="col-xs-2"><label for="opn_dt_bls" class="control-label">Opn. Date</label></td>
        <td class=""><div id="opn_dt_bls" data-id="opn_dt_bls" class="bfh-datepicker" data-date="today" ></div></td>
        <td class="batas">&nbsp;</td>
        <td class="col-xs-2"><label for="opn_dt_pnt" class="control-label">Opn. Date</label></td>
        <td class=""><div id="opn_dt_pnt" data-id="opn_dt_pnt" class="bfh-datepicker" data-date="today" ></div></td>
      </tr>
      <tr>
        <td class="col-xs-2"><label for="opn_qty_bls" class="control-label">Opn. Qty</label></td>
        <td class="">
          <input type="hidden" id="memo_bls" value="NOTPASS">
          <input type="number" onchange="valQTY('bls')" class=" form-control" id="opn_qty_bls" value="0" min="0" max="<?php echo $PAINT_PASS-$AvlOpn_bls ?>">
        </td>
        <td class="batas">&nbsp;</td>
        <td class="col-xs-2"><label for="opn_qty_pnt" class="control-label">Opn. Qty</label></td>
        <td class="">
          <input type="hidden" id="memo_pnt" value="NOTPASS">
          <input type="number" onchange="valQTY('pnt')" class=" form-control" id="opn_qty_pnt" value="0" min="0" max="<?php echo $PAINT_PASS-$AvlOpn_pnt ?>">
        </td>
      </tr>
      <tr>
        <td class="col-xs-2">&nbsp;</td>
        <td class=""><h6 id="rem_bls">remain: <?php echo $PAINT_PASS-$AvlOpn_bls ?></h6></td>
        <td class="batas">&nbsp;</td>
        <td class="col-xs-2">&nbsp;</td>
        <td class=""><h6 id="rem_pnt">remain: <?php echo $PAINT_PASS-$AvlOpn_pnt ?></h6></td>
      </tr>
      <tr>
        <td class="col-xs-2">&nbsp;</td>
        <td class=""><button type="button" onclick="addACT('bls')" class="btn btn-default btn-sm" disabled="" id="add_bls">Add</button></td>
        <td class="batas">&nbsp;</td>
        <td class="col-xs-2">&nbsp;</td>
        <td class=""><button type="button" onclick="addACT('pnt')" class="btn btn-default btn-sm" disabled="" id="add_pnt">Add</button></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="modal-body">
     <table class="table table-condensed table-bordered" style="width:95%;" id="OPN_tbl">
         <thead>
            <tr>
                <th>Opn Date</th>
                <th>Opn Qty<br>Blast</th>
                <th>Opn Qty<br>Paint</th>
                <th>Opn Sign</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $sub_qty_bls = 0;
            $sub_qty_pnt = 0;
            $sql = "SELECT *
                    FROM PAINTING_OPN 
                    WHERE HEAD_MARK = '$HM' AND ID = '$ID'
                    ORDER BY OPN_DATE";
            // echo "$sql";
            $sqlPck = oci_parse($conn, $sql);
            oci_execute($sqlPck);
            while($rowPck = oci_fetch_array($sqlPck)){
                $dt = new DateTime($rowPck['OPN_DATE']);

                $qty_bls = 0;
                $qty_pnt = 0;
                if ($rowPck['OPN_TYPE']=='PAINT') {
                  # code...
                  $qty_pnt = $rowPck['OPN_QTY'];
                  $sub_qty_pnt +=  $qty_pnt;
                }else{
                  $qty_bls = $rowPck['OPN_QTY'];
                  $sub_qty_bls +=  $qty_bls;
                }
                

            ?>
            <tr>
                <td><div><?php echo $dt->format("m/d/Y") ?></div></td>
                <td><?php echo $qty_bls ?></td>
                <td><?php echo $qty_pnt ?></td>
                <td><?php echo $rowPck['OPN_SIGN'] ?></td>
                <td><?php echo $rowPck['MEMO'] ?></td>
                <td>N/A</td>
            </tr>
            <?php } ?>
         </tbody>
         <tfoot>
            <tr>
                <th>Total : </th>
                <th id="th_qty_opn_bls"><?php echo $sub_qty_bls ?></th>
                <th id="th_qty_opn_pnt"><?php echo $sub_qty_pnt ?></th>
                <th colspan="3">&nbsp;</th>
            </tr>
         </tfoot>
     </table>
</div>
<div id="result_opn"></div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary save" >Save</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div> 
<script type="text/javascript">

  var counter = 0;
  $('#OPN_tbl').dataTable(
    {
        // "bPaginate": true,
        // "bLengthChange": false,
        // "iDisplayLength": 10,
        // "bFilter": true,
        // "bSort": true,
        // "bInfo": true,
        "bAutoWidth": true   ,
        // "scrollY": "300px",
        // "scrollCollapse": true,
        // "scrollX": false,
        "paging": false
    }
  );

  $('.btn-primary.save').on('click',function () {
    // body...
    // alert("SUBMIT");
    var HM          = "<?php echo $HM ?>";
    var ID          = "<?php echo $ID ?>";
    var Qty_opn     = [];
    var entryDT_opn = [];
    var memo_opn    = [];
    var type_opn    = [];

    $('tr[id^=row_opn_bls_]').each(
      function(){
        var idTR_Ins    = $(this).attr("id") ;
        var idTR_Ins_1  = idTR_Ins.replace("row_opn_bls_","");

        var opn_qty     = $("#new_opn_qty_bls_"+idTR_Ins_1).text();
        var opn_dt      = $("#new_opn_dt_bls_"+idTR_Ins_1).text();
        var opn_memo      = $("#new_memo_bls_"+idTR_Ins_1).text();

        Qty_opn.push(opn_qty);
        entryDT_opn.push(opn_dt);
        memo_opn.push(opn_memo);
        type_opn.push("BLAST");
        // alert(opn_dt+" -- "+opn_qty);
      }
    );

    $('tr[id^=row_opn_pnt_]').each(
      function(){
        var idTR_Ins    = $(this).attr("id") ;
        var idTR_Ins_1  = idTR_Ins.replace("row_opn_pnt_","");

        var opn_qty     = $("#new_opn_qty_pnt_"+idTR_Ins_1).text();
        var opn_dt      = $("#new_opn_dt_pnt_"+idTR_Ins_1).text();
        var opn_memo      = $("#new_memo_pnt_"+idTR_Ins_1).text();

        Qty_opn.push(opn_qty);
        entryDT_opn.push(opn_dt);
        memo_opn.push(opn_memo);
        type_opn.push("PAINT");
        // alert(opn_dt+" -- "+opn_qty);
      }
    );

    $.ajax({
        type: 'POST',
        url: "update_painting_opn_act.php",
        data: {HM: HM, ID:ID, Qty_opn:Qty_opn, entryDT_opn:entryDT_opn, memo_opn:memo_opn, type_opn:type_opn},
        success: function (response, textStatus, jqXHR) {
            $('#result_opn').html(response);
        }
    });

    $('#myModal').modal('hide');
  });

  function addACT(type) {
    // body...
    // alert("ADD");
    var dataIntermd = $('input[type=number]#opn_qty_'+type);
    var Vlue = parseInt(dataIntermd.val());

    if (isNaN(Vlue) || Vlue > dataIntermd.attr("max") || Vlue == 0 ) {
      alert("OPN QTY IS NOT VALID");

      dataIntermd.val('0');
      Vlue = 0;

      setTimeout(
        function(){
          dataIntermd.focus();
        }
      ,500);
    }else{
      var trget_opn       = $('#OPN_tbl').dataTable();
      var Qty_opn         = parseInt($('#opn_qty_'+type).val());
      var entryDT_opn     = $('#opn_dt_'+type).val();
      var memo            = $('#memo_'+type).val();
      var usernm          = "<?php echo $username; ?>";

      counter ++;
      // alert(usernm);
      if (type=='bls') {
        var newTargetRow = trget_opn.fnAddData( [
            '<div id="new_opn_dt_bls_'+counter+'" >'+entryDT_opn+'</div>',
            '<div id="new_opn_qty_bls_'+counter+'" >'+Qty_opn+'</div>',
            '<div id="new_opn_qty_pnt_'+counter+'" >0</div>',
            usernm,
            '<div id="new_memo_bls_'+counter+'" >'+memo+'</div>',
            '<div style="text-align: center;"><a href="#" class="btn btn-danger btn-xs" onclick="removeTR('+"'"+counter+"'"+','+"'bls'"+')">remove</a></div>'
        ] );
      } else{
        var newTargetRow = trget_opn.fnAddData( [
            '<div id="new_opn_dt_pnt_'+counter+'" >'+entryDT_opn+'</div>',
            '<div id="new_opn_qty_bls_'+counter+'" >0</div>',
            '<div id="new_opn_qty_pnt_'+counter+'" >'+Qty_opn+'</div>',
            usernm,
            '<div id="new_memo_pnt_'+counter+'" >'+memo+'</div>',
            '<div style="text-align: center;"><a href="#" class="btn btn-danger btn-xs" onclick="removeTR('+"'"+counter+"'"+','+"'pnt'"+')">remove</a></div>'
        ] );
      }

      var oSettings = trget_opn.fnSettings();
      var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;

      nTr.setAttribute('id', "row_opn_"+type+"_"+counter);

      // set max 
      var frst = $('#opn_qty_'+type).attr("max");
      $('#opn_qty_'+type).attr("max",(frst-Qty_opn));
      $('#opn_qty_'+type).val('0');

      // sum qty_opn
      var subTot_qty_frst = parseInt($('#th_qty_opn_'+type).text()) ;
      $('#th_qty_opn_'+type).text(subTot_qty_frst+Qty_opn);
    }
  }
  // $('#add').on('click',function () {
  // });

  function valQTY(type) {
    // body...
    var frst = parseInt($('#opn_qty_'+type).attr("max"));
    var used = $('#opn_qty_'+type).val();
    // alert(ts);
    $('#rem_'+type).text('remain: '+(frst-used));

    // $(this).attr("max",(frst-used));
    if (used==0) {
      $('#add_'+type).attr("disabled","disabled");
    }else{
      $('#add_'+type).removeAttr("disabled");
      if ((frst-used)==0) {
        $('#memo_'+type).val("PASSED");
      }else{
        $('#memo_'+type).val("NOTPASS");
      }
    }
  }
  // $('input[type=number]#opn_qty').on('change',function(){
  // });

  function removeTR(idTR,type) {
    // body...
    // alert(idTR);

    var qty1 = $('#opn_qty_'+type);
    var qty1_1 = parseInt(qty1.attr("max"));
    var qty2 = parseInt($('#new_opn_qty_'+type+'_'+idTR).text());

    qty1.attr("max",(qty1_1+qty2));

    // alert((qty2));
    var qty1_2 = qty1.attr("max");
    $('#rem_'+type).text('remain: '+qty1_2);

    // SET MEMO SBJ
    var mmo = $('#new_memo_'+type+'_'+idTR).text();
    if (mmo == "NOTPASS" && qty1_1 == 0) { 
      // alert("YEAH");
      cekPassTR(type);
    }

    // sum qty_opn
      var subTot_qty_frst = parseInt($('#th_qty_opn_'+type).text()) ;
      $('#th_qty_opn_'+type).text(subTot_qty_frst-qty2);

    var trget_opn       = $('#OPN_tbl').DataTable();
    trget_opn.row('#row_opn_'+type+'_'+idTR).remove().draw(false);
  }

  function cekPassTR(type) {
    // body...
    $('div[id^=new_memo_'+type+'_]').each(
      function(){
        var memoTR    = $(this).text();
        if (memoTR=="PASSED") {
          $(this).text("NOTPASS");
        }
      }
    );
  }
</script>