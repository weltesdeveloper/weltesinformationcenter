<?php

include '../dbinfo.inc.php';
include '../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

function kirim() {
    //    include '../dbinfo.inc.php';
    $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    $email = "project.monitoring@weltes.co.id";
    $mailheaders = "From: $email\n";
    $mailheaders .= "Reply-to: $email\n";
    $mailheaders .= "Content-Type: text/html; charset=iso-8859-1\n";
    $date = date("d F Y");


    $header = "<span style='text-align:center'><b>LAPORAN DELAY FABRIKASI PT WELTES ENERGI NUSANTARA TGL $date</b></span>" .
            '<table>
    <tr>               
    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Head Mark</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Assign Date</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Assembly</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Profile</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Subcont</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Total Qty</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Total Weight</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Status</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Remark</th>

    <th style="border-width: 1px;
    padding: 10px;
    border-style: solid;
    border-color: #666666;
    background-color: #dedede;
    font-size:10px;">Remark date</th>

    </tr>';
    $content = "";
    $sql = "SELECT DISTINCT PROJECT_NAME,PROJECT_NAME_NEW,CLIENT_INIT FROM NOTIFICATION_EMAIL WHERE COMP_TYPE != 'BOLTNUTS' ORDER BY CLIENT_INIT,PROJECT_NAME_NEW ";
    $parse = oci_parse($conn, $sql);
    oci_execute($parse);
    while ($row0 = oci_fetch_array($parse)) {
        $projectName = $row0['PROJECT_NAME'];
        $content .= "<tr>
        <td style='border-width: 1px;
        padding: 10px;
        border-style: solid;
        border-color: #666666;
        background-color:#fd9064;
        font-style:italic;
        font-weight:bold;
        font-size:10px;'
        colspan=10>" . $row0['CLIENT_INIT'] . " - " . $row0['PROJECT_NAME_NEW'] . "</td>";
        $sql2 = "SELECT MAX (REMARK_DATE) REMARK_DATE, "
                . "HEAD_MARK, ASSG_DATE, "
                . "COMP_TYPE, "
                . "PROFILE, "
                . "SUBCONT_ID, "
                . "TOTAL_QTY, "
                . "WEIGHT, "
                . "max(UPPER(REMS)) REMS "
                . "FROM NOTIFICATION_EMAIL "
                . "WHERE PROJECT_NAME = '$projectName'  AND COMP_TYPE != 'BOLTNUTS' "
                . "GROUP BY HEAD_MARK, ASSG_DATE, COMP_TYPE, PROFILE, SUBCONT_ID, TOTAL_QTY, WEIGHT "
                . "ORDER BY TO_NUMBER (REGEXP_REPLACE (HEAD_MARK, '[^[:digit:]]', NULL))";
        $parse2 = oci_parse($conn, $sql2);
        oci_execute($parse2);
        while ($row = oci_fetch_array($parse2)) {
            $content .= "<tr>
            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['HEAD_MARK'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['ASSG_DATE'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['COMP_TYPE'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['PROFILE'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['SUBCONT_ID'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['TOTAL_QTY'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['WEIGHT'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . "NOT START FABRICATION" . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px; '>" . $row['REMS'] . "</td>

            <td style='border-width: 1px;
            padding: 10px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
            font-size:10px;'>" . $row['REMARK_DATE'] . "</td>
            </tr>";
        }
    }
    $footer = "</table>";
    $qwe = $header . $content . $footer;
//    echo "$qwe";
//    exit();
    $recepient = 'miko.hendroc@gmail.com, edward@weltes.co.id, ferlydeska@gmail.com, hutagalung.chris@weltes.co.id, '
            . 'teguh.ppic@weltes.co.id, sukiantoa@gmail.com, kasmuji@weltes.co.id, williama@weltes.co.id, watmodihardjo@gmail.com, kasmujik@gmail.com, faris.prasetyo39@gmail.com';
    $m = mail($recepient, 'LAPORAN DELAY FABRIKASI (DO NOT REPLY)', $qwe, $mailheaders);
    $IDEMAIL = "email-" . date("d/m/Y");
    if ($m) {
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'SUCCESS', 'DELAYFAB')";
        $insertEmailParse = oci_parse($conn, $insertEmailSql);
        $insertEmail = oci_execute($insertEmailParse);
        if ($insertEmail) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }
        echo 'sukses kirim';
    } else {
        $IDEMAIL = "email-" . date("d/m/Y");
        $insertEmailSql = "INSERT INTO EMAIL_NOTIFICATION (ID_EMAIL, RECEIVER, SENT_DATE, STATUS, EMAIL_TYPE) VALUES('$IDEMAIL', '$recepient', SYSDATE, 'FAILSS', 'DELAYFAB')";
        $insertEmailParse = oci_parse($conn, $insertEmailSql);
        $insertEmail = oci_execute($insertEmailParse);
        if ($insertEmail) {
            oci_commit($conn);
        } else {
            oci_rollback($conn);
        }
        echo 'fail kirim';
    }
}

//kirim();
$hour = date("H");
if ($hour > 12 && $hour < 24) {
    $IDEMAIL = "email-" . date("d/m/Y");
    $cekEmailSql = "SELECT COUNT(*) JUMLAH FROM EMAIL_NOTIFICATION WHERE ID_EMAIL = '$IDEMAIL' AND STATUS = 'SUCCESS' AND EMAIL_TYPE = 'DELAYFAB'";
    $result = SingleQryFld($cekEmailSql, $conn);
    if ($result == "0")
        kirim();
    else {
        echo "email sudah terkirim";
    }
} else {
    echo "bukan jam kirim email boss";
}