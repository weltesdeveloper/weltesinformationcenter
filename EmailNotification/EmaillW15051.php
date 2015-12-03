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
    /* $comment = SingleQryFld("SELECT to_char(CONTENT_COMMENT) FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$yesterday' AND COMMENT_TYPE = 'ASSEMBLY' AND PROJECT_NO = 'W-IGG'", $conn); */
    /* $report = SingleQryFld("SELECT COMMENT_SIGN FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$yesterday' AND COMMENT_TYPE = 'ASSEMBLY' AND PROJECT_NO = 'W-IGG'", $conn); */
    $header = '<table>'
            . '<tr><th style="text-align:center;" colspan=17><b><u>LAPORAN PROGRESS BY ASSEMBLY W15051 (CLAY CRUSHER, MAIN BAGFILTER, PACKER, & CONTITIONAL TOWER) TANGGAL ' . $date . '</u></b></th></tr>'
            . '<tr><th style="text-align:right;">NOTE</th><th style="text-align:left;" colspan=16> : <b><i>' . "" . '<i></b></th></tr>
    <tr></tr> <tr></tr>
    <tr><th style="text-align:right;">REPORT BY:</th><th style="text-align:left;" colspan=16> : <b><i>' . "" . '<i></b></th></tr>' .
            '<tr>
    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ASSY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">TOT<br>DWG</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">TOT<br>ASSIGN</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">TOT<br>NOT ASSIGN</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">MARK</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">CUTT</th>
    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ASSY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">WELD</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">DRILL</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">FAB<br>FINISH</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">FAB<br>QC</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">BLAST</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">PRIMER</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">INTD</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">TOPCOAT</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">PAINT<br>QC</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">PACK<br>QTY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">DLV<br>QTY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ERECT<br>QTY</th>               
    </tr>';
    $content = "";
    $projectSql = "SELECT DISTINCT PROJECT_NAME_OLD,CLIENT_INIT,PROJECT_NAME_NEW FROM VW_PROJ_INFO "
            . "WHERE PROJECT_TYP = 'STRUCTURE' AND PROJECT_NO = 'W15051' ORDER BY CLIENT_INIT,PROJECT_NAME_NEW";
    $projectParse = oci_parse($conn, $projectSql);
    oci_execute($projectParse);
    while ($row1 = oci_fetch_array($projectParse)) {
        $pn = $row1['PROJECT_NAME_OLD'];
        $content.='<tr><td colspan="22" style="background-color:#fd9064;">' . $row1['CLIENT_INIT'] . ' - ' . $row1['PROJECT_NAME_NEW'] . '</td></tr>';
        $getProjectNoSql = "SELECT * FROM REP_PROG_COMP_WT WHERE PROJECT_NAME = '$pn'";
        $getProjectNoParse = oci_parse($conn, $getProjectNoSql);
        oci_execute($getProjectNoParse);
        while ($row = oci_fetch_array($getProjectNoParse)) {
            $content.="<tr>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['COMP_TYPE'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['TOT_DWG'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['TOT_ASSGNED'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['TOT_NOTASGN'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['MARK'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['CUT'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['ASSY'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['WELD'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['DRILL'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['FIN'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['FAB_QC'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['BLAST'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['PRIMER'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['INTMD'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['TOP_COAT'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['PNT_QC'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['PCK_QTY'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['DLV_QTY'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['ERECT_ERECTION'] . "</td>" .
                    "</tr>";
        }
    }
    $footer = "</table>";
    $qwe = $header . $content . $footer;
//    echo "$qwe";
//    exit();
    $recepient = 'miko.hendroc@gmail.com, edward@weltes.co.id, ferlydeska@gmail.com, hutagalung.chris@weltes.co.id, '
            . 'teguh.ppic@weltes.co.id, skt@weltes.co.id, kasmuji@weltes.co.id, williama@weltes.co.id, watmodihardjo@gmail.com, kasmujik@gmail.com';
    $m = mail($recepient, 'LAPORAN PROGRESS BY ASSEMBLY W15051 (PROJECT SEMEN REMBANG)', $qwe, $mailheaders);
    $IDEMAIL = "email-" . date("d/m/Y");
    if ($m) {
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'SUCCESS', 'W15051')";
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
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'FAILSS', 'W15051')";
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
if ($hour > 12 && $hour < 24) {
    $IDEMAIL = "email-" . date("d/m/Y");
    $cekEmailSql1 = "SELECT COUNT(*) JUMLAH FROM EMAIL_NOTIFICATION WHERE ID_EMAIL = '$IDEMAIL' AND STATUS = 'SUCCESS' AND EMAIL_TYPE = 'W15051'";
    $result1 = SingleQryFld($cekEmailSql1, $conn);
    if ($result1 == "0") {
        DailyFabrication();
    } else
        echo "email WIGG sudah terkirim" . "<br/>";
}
else {
    echo "bukan jam kirim email boss";
}