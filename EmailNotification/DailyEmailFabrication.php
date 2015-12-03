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
    $comment = SingleQryFld("SELECT CONTENT_COMMENT FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$date' AND COMMENT_TYPE = 'FABRIKASI'", $conn);
    $report = SingleQryFld("SELECT CONTENT_COMMENT FROM MST_COMMENT WHERE TO_CHAR(COMMENT_DATE, 'MM/DD/YYYY')='$date' AND COMMENT_TYPE = 'FABRIKASI'", $conn);
    $header = '<table>
    <tr><th style="text-align:center;" colspan=17><b><u>LAPORAN HARIAN FABRIKASI PT WELTES ENERGI NUSANTARA TGL ' . $date . '</u></b></th></tr>
    <tr><th style="text-align:right;">NOTE</th><th style="text-align:left;" colspan=16> : <b><i>' . $comment . '<i></b></th></tr>
    <tr></tr> <tr></tr>
    <tr><th style="text-align:right;">REPORT BY:</th><th style="text-align:left;" colspan=16> : <b><i>' . $report . '<i></b></th></tr>' .
            '<tr>
    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" rowspan=2>Project Name</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" rowspan=2>Total Weight(KG)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" rowspan=2>Total Surface(M<sup>2</sup>)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" colspan=3>Fabrication(KG)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;" colspan=3>Painting(M<sup>2</sup>)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" colspan=3>Packing(KG)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;" colspan=3>Delivery(KG)</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;" colspan=2>ERECTION(KG)</th>
    </tr>
    <tr>
    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">%</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ALL</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">YESTERDAY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">%</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">ALL</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">YESTERDAY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">%</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ALL</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">YESTERDAY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">%</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">ALL</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #d9d3d0;
    font-size:10px;">YESTERDAY</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">%</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">ALL</th>

    </tr>';
    $content = "";
    $getProjectNoSql = "SELECT DISTINCT PROJECT_NO, CLIENT_INIT FROM VW_PROJ_INFO WHERE PROJECT_TYP = 'STRUCTURE' ORDER BY CLIENT_INIT,PROJECT_NO";
    $getProjectNoParse = oci_parse($conn, $getProjectNoSql);
    oci_execute($getProjectNoParse);
    while ($row = oci_fetch_array($getProjectNoParse)) {
        $PROJECT_NO = $row['PROJECT_NO'];
        $CLIENT_INIT = $row['CLIENT_INIT'];
        $sumproject = 0;
        $sumsurface = 0;
        $sumfaball = 0;
        $sumfabdly = 0;
        $sumpaintall = 0;
        $sumpaintdly = 0;
        $sumpackall = 0;
        $sumpackdly = 0;
        $sumdlvall = 0;
        $sumdlvdly = 0;
        $sumerectall = 0;

        $content.="<tr>"
                . "<th style='text-align:left; background-color:#FD9064;font-style:italic;' colspan=17>$PROJECT_NO - $CLIENT_INIT</th>"
                . "</tr>";

        $sql1 = "SELECT VESP.*, STOV.TOTALPROGRESSNET
        FROM VW_EMAIL_STRICTURE_PERDAY VESP
        LEFT OUTER JOIN
        SITE_TIER_ONE_VW STOV
        ON     VESP.PROJECT_NO = STOV.PROJECTNO
        AND VESP.PROJECT_NAME_OLD = STOV.COMPPROJECTNAME WHERE VESP.PROJECT_NO = '$PROJECT_NO' ORDER BY VESP.PROJECT_NAME_NEW";
        $parse1 = oci_parse($conn, $sql1);
        oci_execute($parse1);
        while ($row1 = oci_fetch_array($parse1)) {
            $content.="<tr>
            <td style='border-width: 1px;
            padding: 12px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row1['PROJECT_NAME_NEW'] . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
            text-align:right;
            font-size:10px;'>" . number_format($row1['TOTAL_WEIGHT'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
            text-align:right;
            font-size:10px;'>" . number_format($row1['TOTAL_SURFACE'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:center;
            font-size:10px;'>" . @number_format($row1['FABRICATION'] / $row1['TOTAL_WEIGHT'] * 100, 2, ".", ",") . " %" . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:right;
            font-size:10px;'>" . number_format($row1['FABRICATION'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:right;
            font-size:10px;'>" . number_format($row1['D_FABRICATION'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:center;
            font-size:10px;'>" . @number_format($row1['PAINTING'] / $row1['TOTAL_SURFACE'] * 100, 2, ".", ",") . " %" . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:right;
            font-size:10px;'>" . number_format($row1['PAINTING'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:right;
            font-size:10px;'>" . number_format($row1['D_PAINTING'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . @number_format($row1['PACKING'] / $row1['TOTAL_WEIGHT'] * 100, 2, ".", ",") . " %" . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:right;
            font-size:10px;'>" . number_format($row1['PACKING'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:right;
            font-size:10px;'>" . number_format($row1['D_PACKING'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:center;
            font-size:10px;'>" . @number_format($row1['DELIVERY'] / $row1['TOTAL_WEIGHT'] * 100, 2, ".", ",") . " %" . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:right;
            font-size:10px;'>" . number_format($row1['DELIVERY'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #d9d3d0;
            text-align:right;
            font-size:10px;'>" . number_format($row1['D_DELIVERY'], 2) . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:center;
            font-size:10px;'>" . @number_format($row1['TOTALPROGRESSNET'] / $row1['TOTAL_WEIGHT'] * 100, 2, ".", ",") . " %" . "</td>" .
                    "<td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            text-align:right;
            font-size:10px;'>" . number_format($row1['TOTALPROGRESSNET'], 2) . "</td>" .
                    "</tr>";

            $sumproject += $row1['TOTAL_WEIGHT'];
            $sumsurface += $row1['TOTAL_SURFACE'];
            $sumfaball += $row1['FABRICATION'];
            $sumfabdly +=$row1['D_FABRICATION'];
            $sumpaintall += $row1['PAINTING'];
            $sumpaintdly += $row1['D_PAINTING'];
            $sumpackall += $row1['PACKING'];
            $sumpackdly += $row1['D_PACKING'];
            $sumdlvall += $row1['DELIVERY'];
            $sumdlvdly += $row1['D_DELIVERY'];
            $sumerectall+= $row1['TOTALPROGRESSNET'];
        }
        $content .= "<tr style='text-align:right; background-color:silver;font-size:10px;'>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>SUMMARY </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumproject, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumsurface, 2) . " </sup></td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumfaball / $sumproject * 100, 2, ".", ",") . " %</td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumfaball, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumfabdly, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpaintall / $sumsurface * 100, 2, ".", ",") . " %</td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpaintall, 2) . " </sup></td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpaintdly, 2) . " </sup></td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpackall / $sumproject * 100, 2, ".", ",") . " %</td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpackall, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumpackdly, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumdlvall / $sumproject * 100, 2, ".", ",") . " %</td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumdlvall, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumdlvdly, 2) . " </td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumerectall / $sumproject * 100, 2, ".", ",") . " %</td>"
                . "<td style='text-align:right; background-color:silver;font-size:12px;'>" . @number_format($sumerectall, 2) . " </td>"
                . "</tr>"
                . "<tr><td colspan=17><hr></td></tr>";
    }
    $footer = "</table>";
    $qwe = $header . $content . $footer;
    $recepient = 'miko.hendroc@gmail.com, edward@weltes.co.id, ferlydeska@gmail.com, hutagalung.chris@weltes.co.id, '
            . 'teguh.ppic@weltes.co.id, skt@weltes.co.id, kasmuji@weltes.co.id, williama@weltes.co.id, watmodihardjo@gmail.com, kasmujik@gmail.com';
    $m = mail($recepient, 'LAPORAN HARIAN FABRIKASI (DO NOT REPLY)', $qwe, $mailheaders);
    $IDEMAIL = "email-" . date("d/m/Y");
    if ($m) {
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'SUCCESS', 'DAILYFAB')";
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
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'FAILSS', 'DAILYFAB')";
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

$hour = date("H");
if ($hour > 12 && $hour < 24) {
    $IDEMAIL = "email-" . date("d/m/Y");
    $cekEmailSql1 = "SELECT COUNT(*) JUMLAH FROM EMAIL_NOTIFICATION WHERE ID_EMAIL = '$IDEMAIL' AND STATUS = 'SUCCESS' AND EMAIL_TYPE = 'DAILYFAB'";
    $result1 = SingleQryFld($cekEmailSql1, $conn);
    if ($result1 == "0") {
        DailyFabrication();
    } else {
        echo "email fab harian sudah terkirim" . "<br/>";
    }
} else {
    echo "bukan jam kirim email boss";
}