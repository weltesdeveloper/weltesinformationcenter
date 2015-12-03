<?php

include '../dbinfo.inc.php';
include '../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$this_thurday = strtotime('monday this week');
$start_date = date('m/d/Y', strtotime("last monday", $this_thurday));
$this_saturday = strtotime('saturday this week');
$end_date = date('m/d/Y', strtotime("last saturday", $this_saturday));
$selisih = $end_date - $start_date;
$array = array();
$email = "project.monitoring@weltes.co.id";
$mailheaders = "From: $email\n";
$mailheaders .= "Reply-to: $email\n";
$mailheaders .= "Content-Type: text/html; charset=iso-8859-1\n";

$header = '<table>
                <tr><th style="text-align:center;color:green;" colspan=17><b><u>LAPORAN QC FABRIKASI TGL ' . "$start_date s/d $end_date" . "(FIX)" . '</u></b></th></tr>' .
        '<tr><tr></tr>
            <th style="border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;">SUBCONT</th>';
$content = "";
$arrayTangal = array();
$dateSql = "SELECT TO_CHAR(TO_DATE ('$start_date', 'mm/dd/yyyy') + ROWNUM - 1,'DD-MM-YYYY') as tanggal "
        . "FROM all_objects WHERE ROWNUM <= TO_DATE ('$end_date', 'mm/dd/yyyy') - TO_DATE ('$start_date', 'mm/dd/yyyy') + 1";
$dateParse = oci_parse($conn, $dateSql);
oci_execute($dateParse);
while ($row = oci_fetch_array($dateParse)) {
    $header.= "<th style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;'>$row[TANGGAL]</th>";
    array_push($arrayTangal, $row['TANGGAL']);
}
$header .= "<th style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;'>TOTAL</th></tr>";

$content = "";
$sql = "SELECT DISTINCT SUBCONT_ID FROM SUBCONTRACTOR WHERE SUBCONT_STATUS LIKE '%OUTSOURCE' AND SUBCONT_ACTUAL = 'ACTIVE' AND SUBCONT_JOB_TYPE = 'STRUCTURE' AND SUBCONT_ID NOT LIKE '%DLY%' ORDER BY SUBCONT_ID";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
$i = 0;
$baris = 0;
$kolom = 0;
while ($row1 = oci_fetch_array($parse)) {
    $content .="<tr><td style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                font-size:10px;'>$row1[SUBCONT_ID]</td>";
    $total = 0;
    $kolom = 0;
    $array[$baris][$kolom] = $row1['SUBCONT_ID'];
    for ($index = 0; $index < count($arrayTangal); $index++) {
        $query = SingleQryFld("SELECT SUM(TOTAL_WEIGHT) FROM VW_DAILY_QCPASS WHERE SUBCONT_ID = '$row1[SUBCONT_ID]' AND TO_CHAR (FINISHING_QC_DATE, 'DD-MM-YYYY') = '$arrayTangal[$index]'", $conn);
        if ($query == 0) {
            $content .= "<td style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                font-size:10px; color:red;'>" . number_format($query, 2) . " Kg" . "</td>";
        } else {
            $content .= "<td style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                font-size:10px; color:black;'>" . $query . " Kg" . "</td>";
        }
        $total+=$query;
        $array[$baris][$kolom] = $query;
        $kolom++;
    }
    if ($total == 0) {
        $content .= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:12px;;
                color:red;'>" . number_format($total, 2) . " Kg</th>";
    } else {
        $content .= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:12px;
                color:blue;'>" . number_format($total, 2) . " Kg</th>";
    }
    $content .="</tr>";
}

$arrayTangal = array();
$content .= "<tr><th style='border-width: 1px;
                padding: 10px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;'>SUMMARY</th>";
$dateSql = "SELECT TO_CHAR(TO_DATE ('$start_date', 'mm/dd/yyyy') + ROWNUM - 1,'DD-MM-YYYY') as tanggal "
        . "FROM all_objects WHERE ROWNUM <= TO_DATE ('$end_date', 'mm/dd/yyyy') - TO_DATE ('$start_date', 'mm/dd/yyyy') + 1";
$dateParse = oci_parse($conn, $dateSql);
oci_execute($dateParse);
$total = 0;
while ($row = oci_fetch_array($dateParse)) {
    $query = SingleQryFld("SELECT sum(TOTAL_WEIGHT) FROM VW_DAILY_QCPASS WHERE TO_CHAR (FINISHING_QC_DATE, 'DD-MM-YYYY') = '$row[TANGGAL]'", $conn);
    if ($query == 0) {
        $content.= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:12px;
                color:red;'>" . number_format($query, 2) . " KG" . "</th>";
    } else {
        $content.= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:12px;
                color:blue;'>" . number_format($query, 2) . " KG" . "</th>";
    }
    $total+=$query;
}
if ($total == 0) {
    $content .= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;
                color:red;'>" . number_format($total, 2) . " KG" . "</th>";
} else {
    $content .= "<th style='border-width: 1px;
                padding: 12px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                font-size:10px;
                color:blue;'>" . number_format($total, 2) . " KG" . "</th>";
}

$footer = '</tr></table>';
$qwe = $header . $content . $footer;
echo "$qwe";
$recepient = 'hutagalung.chris@weltes.co.id, edward@weltes.co.id';
$m = mail($recepient, "LAPORAN QC FABRIKASI PER SUBCONT $start_date s/d $end_date", $qwe, $mailheaders);
$IDEMAIL = "email-" . date("d/m/Y");
if ($m) {
    echo 'sukses';
} else {
    echo "fail";
}