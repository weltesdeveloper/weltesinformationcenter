<?php

   require_once '../../../dbinfo.inc.php';
   require_once '../../../FunctionAct.php';
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
   
   if(isset( $_POST['cd-dropdown'])) $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];    
   date_default_timezone_set('Asia/Jakarta'); //CDT
   $current_date = date('H:i:s');

error_reporting(E_ALL);

$date1 = $_GET['var1'];
$date2 = $_GET['var2'];

$projectValSQL = "PROJECT_NAME LIKE '%'";
        
  if ($_GET["projData"]<>"ALL") {
      # code...
      $projectValSQL = 'PROJECT_NAME in ';
      $projNM     = '(';
      list($proj_no,$proj_code) = explode("^", $_GET["projData"]);
      if ($proj_code=="ALL") {
          $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
          
      } else {
          $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' AND PROJECT_CODE='$proj_code' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
      } 

      $projectNameParse = oci_parse($conn, $projectNameSql);                       
      oci_execute($projectNameParse);
      while($projectNameROW = oci_fetch_array($projectNameParse))
      {
          $projNM .= "'".$projectNameROW['PROJECT_NAME']."',";
      }
      $projectValSQL .= substr_replace($projNM, "", -1).")";
      // echo "$projectValSQL";
  }

$dt1 = new DateTime($date1);
$dt2 = new DateTime($date2);

$projKategori = $_GET['projKategori'];

 
if ($date1 == $date2){
  $titleJdul = "Not Started Drawing Assigned Analysis Before ~ ".$dt1->format('l, F d, Y').".";
  $sqlDate = "AND ASSG_DATE <= TO_DATE('$date1 23:59:59','MM/DD/YYYY hh24:mi:ss')";
}else{
  $sqlDate = "AND ASSG_DATE >= TO_DATE('$date1 00:00:01','MM/DD/YYYY hh24:mi:ss') AND ASSG_DATE <= TO_DATE('$date2 23:59:59','MM/DD/YYYY hh24:mi:ss')";
  $titleJdul = "Not Started Drawing Assigned Analysis Between ".$dt1->format('l, F d, Y')." TO ".$dt2->format('l, F d, Y').".";
}

if ($projKategori=="notFABR") {
    # code...
    $projKategori = "(MARK=0)";
} elseif ($projKategori="notPAINT") {
    # code...
    $projKategori = "(BLAST=0)";
} else{
    $projKategori = "(MARK=0 or BLAST=0)";
}

//query mysql, ganti baris ini sesuai dengan query kamu
    $outstandingFabSql = "SELECT * FROM COMP_VW_INFO "
                          . "WHERE $projectValSQL $sqlDate AND ASSG_QTY > '0' AND "
                          . "$projKategori ORDER BY COMP_TYPE,HEAD_MARK";
    // echo "$outstandingFabSql";exit();
    $outstandingFabParse = oci_parse($conn, $outstandingFabSql);
    // oci_bind_by_name($outstandingFabParse, ":PROJNAME", $projectVal);
    oci_execute($outstandingFabParse);
?>

<?php
header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
if ($date1 == $date2){
  header('Content-Disposition: attachment;filename="Not_StartedDrawing_Before_'.$date1.'_GeneratedOn_'.$formattedFileName.'.xls"');
}else{
  header('Content-Disposition: attachment;filename="Not_StartedDrawing_Between_'.$date1.'_to_'.$date2.'_GeneratedOn_'.$formattedFileName.'.xls"');
}
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>

<div class="box">
    <div class="box-header">
        <?php 
        if (!($date1)){
                    echo '<b><font color="red" size="5">ERROR : DATES SELECTION ERROR !!!</font>    Message : Date1 = NULL'; 
                    exit();
        } else if ($date1 === $date2){ 
         ?>
        <h3 class="box-title">Not Started Drawing Assigned Analysis Before ~ <b><u><?php echo $dt1->format('l, F d, Y');?></u></b></h3>
        <?php
        } else { 
        ?>
        <h3 class="box-title">Not Started Drawing Assigned Analysis Between <b><u><?php echo $dt1->format('l, F d, Y');?></u></b> TO <b><u><?php echo $dt2->format('l, F d, Y');?></u></b></h3>
        <h4>Values are calculated based on the date selected</h4>
        <?php        
        }
        ?>
        <div class="box-tools"></div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="example2" class="table table-bordered table-striped" border="1">
            <thead>
                <tr>
                    <th>HEAD MARK</th>
                    <th>ID</th>
                    <th>ASSG DATE</th>
                    <th>COMP TYPE</th>
                    <th>PROFILE</th>
                    <th>DWG QTY</th>
                    <th>ASSG QTY</th>
                    <th>TOT.ASSG WEIGHT</th>
                    <th>TOT.ASSG SURFACE</th>
                    <th>SUBCONT</th>
                    <th>FAB. STATUS</th>
                    <th>PAINT. STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = oci_fetch_array($outstandingFabParse)){
                        $dtAssg = new DateTime($row['ASSG_DATE']);
                            echo '<tr>';
                                echo '<td>'.$row['HEAD_MARK'].'</td>';
                                echo '<td>'.$row['ID'].'</td>';
                                echo '<td>'.$dtAssg->format('m/d/Y').'</td>';
                                echo '<td>'.$row['COMP_TYPE'].'</td>';
                                echo '<td>'.$row['PROFILE'].'</td>';
                                echo '<td>'.$row['TOTAL_QTY'].'</td>';
                                echo '<td>'.$row['ASSG_QTY'].'</td>';
                                echo '<td>'.$row['WEIGHT']*$row['ASSG_QTY'].'</td>';
                                echo '<td>'.$row['SURFACE']*$row['ASSG_QTY'].'</td>';

                                if (isset($row['SUBCONT_ID'])) {
                                    # code...
                                    echo '<td>'.$row['SUBCONT_ID'].'</td>';
                                } else {
                                    # code...
                                    echo '<td><span class="label label-danger">NOT ASSIGNED</span></td>';
                                }
                                                                            
                                if ($row['MARK'] == 0){
                                    echo '<td><span class="label label-danger">FAB-NOTSTART</span></td>';
                                } elseif($row['MARK'] <> $row['ASSG_QTY']){
                                    echo '<td><span class="label label-warning">FAB-STARTED</span><sup>Not Finish</sup></td>';
                                } else {
                                    echo '<td><span class="label label-success">FAB-STARTED</span></td>';
                                }

                                if ($row['BLAST'] == 0){
                                    echo '<td><span class="label label-danger">PNT-NOTSTART</span></td>';
                                } elseif($row['BLAST'] <> $row['ASSG_QTY']){
                                    echo '<td><span class="label label-warning">PNT-STARTED</span><sup>Not Finish</sup></td>';
                                } else {
                                    echo '<td><span class="label label-success">PNT-STARTED</span></td>';
                                }
                            echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
