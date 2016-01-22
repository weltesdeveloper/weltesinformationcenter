<?php

include '../dbinfo.inc.php';
include '../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

function DailyFabrication() {
    $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    $email = "project.monitoring@weltes.co.id";
    $mailheaders = "From: $email\n";
    $mailheaders .= "Reply-to: $email\n";
    $mailheaders .= "Content-Type: text/html; charset=iso-8859-1\n";
    $day = date("d");
    $date = $day . " " . date("F Y");

    $yesterday = date("m/d/Y", strtotime('-1 days'));
//    $comment = SingleQryFld("SELECT to_char(CONTENT_COMMENT) FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$yesterday' AND COMMENT_TYPE = 'ASSEMBLY' AND PROJECT_NO = 'W-IGG'", $conn);
//    $report = SingleQryFld("SELECT COMMENT_SIGN FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$yesterday' AND COMMENT_TYPE = 'ASSEMBLY' AND PROJECT_NO = 'W-IGG'", $conn);
    $header = '<table>'
            . '<tr><th style="text-align:center;" colspan=18><b><u>LAPORAN PROGRESS BY ASSEMBLY W14076 (TANGKI AIR PROYEK UPRITING IPA GUNUNGSARI) TANGGAL ' . "$date" . '</u></b></th></tr>'
            . '<tr><th style="text-align:right;">NOTE</th><th style="text-align:left;" colspan=18> : <b><i>' . "" . '<i></b></th></tr>
                <tr></tr> <tr></tr>
                <tr><th style="text-align:right;">REPORT BY</th><th style="text-align:left;" colspan=18> : <b><i>' . "" . '<i></b></th></tr>' .
            '<tr>
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #aeaeae;
                font-size:10px;">ASSY</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                font-size:10px;">TOT DWG</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>MARK</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>CUTT</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>DRILL</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>BEVELL</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>ROLL</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>ASSY</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>WELD</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">FAB<br>FINS</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FF998C;
                font-size:10px;">PNT<br>PRIMER</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                background-color: #FF998C;
                font-size:10px;">PNT<br>INTD</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FF998C;
                font-size:10px;">PNT<br>FINS</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #8CB0FF;
                background-color: #8CB0FF;
                font-size:10px;">PACK<br>QTY</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #8CFF97;
                font-size:10px;">DLV<br>QTY</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>ONSITE</th>    
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>LAYDOWN</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>FITUP</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>WELDING</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>FINISHING</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>NDT</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>INSPECTION</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>HYDROTEST</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">SITE<br>PAINTING</th>
                

               </tr> 
               <tr> <th colspan=24 style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                text-align:left;
                font-size:10px;">SUBJOB : TK-953</th>
                

               </tr>';
    $content = "";
    $projectSql = "SELECT * FROM VW_EMAIL_PETRO";
    $projectParse = oci_parse($conn, $projectSql);
    oci_execute($projectParse);
    while ($row = oci_fetch_array($projectParse)) {
        $content.='<tr>
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #aeaeae;
                font-size:10px;">' . $row['COMP_TYPE'] . '</th>
                    
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                font-size:10px;">' . $row['QTY_DTL'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">' . $row['MARKING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                background-color: #dedede;
                font-size:10px;">' . $row['CUTTING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">' . $row['DRILLING'] . '</th>
                    
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                background-color: #dedede;
                font-size:10px;">' . $row['BEVELING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">' . $row['ROLLING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                background-color: #dedede;
                font-size:10px;">' . $row['ASSEMBLY'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">' . $row['WELDING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">' . $row['FINISHING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FF998C;
                font-size:10px;">' . $row['PAINT_PRIMER'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FF998C;
                font-size:10px;">' . "N/A" . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FF998C;
                font-size:10px;">' . $row['PAINT_FINISHING'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #8CB0FF;
                font-size:10px;">' . $row['QTY_PACK'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #8CFF97;
                font-size:10px;">' . $row['DLV_QTY'] . '</th>
                    
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_ONSITE'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_LAYDOWN'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_WELD'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_FITUP'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_NDT'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_FINS'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_INSP'] . '</th>
                
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_HYDRO'] . '</th>
                    
                <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #FFFB8C;
                font-size:10px;">' . $row['ERECT_PAINT'] . '</th>'
        ;
    }

    $footer = "</table>";
    $qwe = $header . $content . $footer;
    $recepient = 'miko.hendroc@gmail.com, edward@weltes.co.id, ferlydeska@gmail.com, hutagalung.chris@weltes.co.id, '
            . 'teguh.ppic@weltes.co.id, sukiantoa@gmail.com, kasmuji@weltes.co.id, williama@weltes.co.id, watmodihardjo@gmail.com, kasmujik@gmail.com';
    $m = mail($recepient, 'LAPORAN PROGRESS BY ASSEMBLY W14076 (TANGKI AIR PROYEK UPRITING IPA GUNUNGSARI)', $qwe, $mailheaders);
    $IDEMAIL = "email-" . date("d/m/Y");
    if ($m) {
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'SUCCESS', 'W14076')";
        $insertEmailParse = oci_parse($conn, $insertEmailSql);
        $insertEmail = oci_execute($insertEmailParse);
        if ($insertEmail) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }
        echo "sukses";
    } else {
        $IDEMAIL = "email-" . date("d/m/Y");
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'FAILSS', 'W14076')";
        $insertEmailParse = oci_parse($conn, $insertEmailSql);
        $insertEmail = oci_execute($insertEmailParse);
        if ($insertEmail) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }
        echo "fail";
    }
}

//DailyFabrication();
$hour = date("H");
if ($hour > 15 && $hour < 24) {
    $IDEMAIL = "email-" . date("d/m/Y");
    $cekEmailSql1 = "SELECT COUNT(*) JUMLAH FROM EMAIL_NOTIFICATION WHERE ID_EMAIL = '$IDEMAIL' AND STATUS = 'SUCCESS' AND EMAIL_TYPE = 'W14076'";
    $result1 = SingleQryFld($cekEmailSql1, $conn);
    if ($result1 == "0") {
        DailyFabrication();
    } else
        echo "email W14076 sudah terkirim" . "<br/>";
}
else {
    echo "bukan jam kirim email boss";
}