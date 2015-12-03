<script type="text/javascript" src="js/autorefresh.js"></script> 
<meta http-equiv="refresh" content="4">

<?php
 require_once './dbinfo.inc.php';
 
 include './include/progressbar.php';
 include './include/finalprogressbar.php';
 session_start();
 
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
   
   //$username = htmlentities($_SESSION['username'], ENT_QUOTES);
   
   $paint_progress_query = 'SELECT CUTTING_QC, ASSEMBLY_QC, WELDING_QC, DRILLING_QC,  
       FINISHING_QC FROM PAINTING';
   $paint_progress_parse = oci_parse ($conn, $paint_progress_query);  
   oci_execute($paint_progress_parse, OCI_DEFAULT);
   
    $cssFile = "css/table.css";
    echo "<link rel='stylesheet' href='" . $cssFile . "'>";
    
   echo '<table cellspacing="0">';
    $ncols = oci_num_fields($paint_progress_parse);
    
   
    echo "<thead>";
    echo "<tr>\n";
    for ($i = 1; $i <= $ncols; ++$i) {
 
    $colname = oci_field_name($paint_progress_parse, $i);

    echo "  <th><b>".htmlentities($colname, ENT_QUOTES)."</b></th>\n";
}
echo "<td align=center><b>OVERALL PROGRESS</b></td>";

echo "</tr>\n"; 
echo "</thead>";

while (($row = oci_fetch_array($paint_progress_parse, OCI_BOTH)) != false) 
{ 
  
    echo '<td>'.$row[0].'</td>';
    echo '<td style=min-width:150px;>'.$row[1].'</td>';
    echo '<td style=min-width:10px;>'.$row[2].'</td>';
    echo '<td style=min-width:10px;>'.$row[3].'</td>';
    if ($row[4]<=$row[2]){
    echo '<td><b>'.$row[4].'</b> / <b>'.$row[2].progressBar(($row[4]/$row[2])*100).'</td>';
    } else {
        echo '<td>'.$row[4].'</b> / <b>'.$row[2].' INPUT ERROR!</td>';
    } 
    if ($row[5]<=$row[2]){
    echo '<td><b>'.$row[5].'</b> / <b>'.$row[2].progressBar(($row[5]/$row[2])*100).'</td>';
    } else {
        echo '<td>'.$row[5].'</b> / <b>'.$row[2].' INPUT ERROR!</td>';
    }
    if ($row[6]<=$row[2]){
    echo '<td><b>'.$row[6].'</b> / <b>'.$row[2].progressBar(($row[6]/$row[2])*100).'</td>';
    } else {
        echo '<td>'.$row[6].'</b> / <b>'.$row[2].' INPUT ERROR!</td>';
    }
    if ($row[7]<=$row[2]){
    echo '<td><b>'.$row[7].'</b> / <b>'.$row[2].progressBar(($row[7]/$row[2])*100).'</td>';
    } else {
        echo '<td>'.$row[7].'</b> / <b>'.$row[2].' INPUT ERROR!</td>';
    }
    
    echo '<td style=min-width:10px;>'.$row[8].'</td>';
    
    $totalProg = ((($row[4]*0.4)+($row[5]*0.3)+($row[6]*0.2)+($row[7]*0.1))/$row[2]*100);
    
    if ($totalProg > 100){
    echo '<td><b>ERROR! INPUT</b>'.$totalProg.'</td>';
    } else {
        echo '<td><b>'.$row[1].'</b>'.finalProgressBar($totalProg).'</td>';
    }
    echo "</tr>\n";
}
echo "</table>\n";
echo "<script type=\"text/javascript\">
            refresh();
            </script>";
?>

