<?php

function SingleQryFld($sql, $conn) {
    // require_once '../dbinfo.inc.php';
    // GENERATE THE APPLICATION PAGE
    // $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    // echo "$sql<br>";
    $sqlSqry = oci_parse($conn, $sql);
    oci_execute($sqlSqry);
    $rowSqry = oci_fetch_array($sqlSqry);
    // echo $rowSqry[0];
    return $rowSqry[0];
}

function HakAksesUser($username, $nm_field, $conn) {
//    $sql = "SELECT $nm_field FROM WELTES_SEC_ADMIN.WELTES_AUTHENTICATION AUT LEFT OUTER JOIN WELTES_SEC_ADMIN.WELTES_AUTH_LEVEL LEV ON LEV.APP_USR_CODE=AUT.APP_USR_CODE WHERE APP_USERNAME = :UN_BV ";
//    $sqlSqry = oci_parse($conn, $sql);
//    oci_bind_by_name($sqlSqry, ":UN_BV", $username);
//    oci_execute($sqlSqry);
//    $rowSqry = oci_fetch_array($sqlSqry);
//    // echo $rowSqry[0];
//    return $rowSqry[0];
    return 1;
}

function nolnoldidepan($value, $places) {
    if (is_numeric($value)) {
        $leading = "";
        for ($x = 1; $x <= $places; $x++) {
            $ceiling = pow(10, $x);
            // echo "$ceiling >> $value --- ";
            if ($value < $ceiling) {
                $zeros = $places - $x;
                // echo "$zeros = $places - $x";
                for ($y = 1; $y <= $zeros; $y++) {
                    $leading .= "0";
                }
                // echo " [$leading] ";
                $x = $places + 1;
            }
        }
        $output = $leading . $value;
        // echo " (($output))";
    } else {
        $output = $value;
    }
    return $output;
}

function ColiNumberGenerate($PrjNo, $PrjCode, $conn) {
    $kdAwal = "CN." . $PrjNo . "." . $PrjCode . ".";
    // echo "SELECT MAX(COLI_NUMBER) FROM MST_PACKING WHERE COLI_NUMBER like '$kdAwal%'";

    $sql = "SELECT MAX(COLI_NUMBER) FROM MST_PACKING WHERE COLI_NUMBER like '$kdAwal%' AND PCK_STAT = 'ACTIVE' ";

    $MaxKd = SingleQryFld($sql, $conn);
    // echo "MAK ID = $MaxKd <br>";
    $NextId = intval(str_replace($kdAwal, "", $MaxKd)) + 1;
    // echo "$NextId<br>";

    return $kdAwal . str_pad($NextId, 4, "0", STR_PAD_LEFT);
}

function DONumberGenerate($PROJECT_NO, $conn) {
    $kdAwal = "J." . $PROJECT_NO . "/SJ" . "/";

    $MaxKd = SingleQryFld("SELECT MAX(DO_NO) FROM MST_DELIV WHERE DO_NO like '$kdAwal%'", $conn);
    // echo "MAK ID = $MaxKd <br>";
    $NextId = intval(str_replace($kdAwal, "", $MaxKd)) + 1;
    // echo "$NextId<br>";

    return $kdAwal . str_pad($NextId, 4, "0", STR_PAD_LEFT);
}

function DONumberGenerateNonProduct($PROJECT_NO, $conn) {
    $kdAwal = "L." . $PROJECT_NO . "/SJ" . "/";

    $MaxKd = SingleQryFld("SELECT MAX(DO_NO) FROM MST_DELIV_NON_PRODUCT WHERE DO_NO like '$kdAwal%'", $conn);
    // echo "MAK ID = $MaxKd <br>";
    $NextId = intval(str_replace($kdAwal, "", $MaxKd)) + 1;
    // echo "$NextId<br>";

    return $kdAwal . str_pad($NextId, 4, "0", STR_PAD_LEFT);
}

function WOFABGenerate($PROJECT_NO, $conn) {
    $kdAwal = "FAB.WO." . $PROJECT_NO . ".";

    $MaxKd = SingleQryFld("SELECT MAX(FAB_NO) FROM MST_FABR WHERE FAB_NO like '$kdAwal%'", $conn);
    // echo "MAK ID = $MaxKd <br>";
    $NextId = intval(str_replace($kdAwal, "", $MaxKd)) + 1;
    // echo "$NextId<br>";

    return $kdAwal . str_pad($NextId, 4, "0", STR_PAD_LEFT);
}

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function hitungProsentase($onsite, $preparation, $erection, $qc) {
    $presentaseOnsite = $onsite / 100 * 5;
    $prsentasePreparation = $preparation / 100 * 5;
    $prsentaseErection = $erection / 100 * 85;
    $presentaseQc = $qc / 100 * 5;     
    $result = $presentaseOnsite + $prsentasePreparation + $prsentaseErection + $presentaseQc;
    return $result;
}

function concatHM($headmarkValue) {
    $headmarkValue = str_replace(" ", "", $headmarkValue);
    $var = array();
    $perHruf = "";
    $str_HM = "";
    $int_HM = "";

    $hm_concat = "";
    $headmark = split('-', $headmarkValue);
    $hmsplit_len = sizeof($headmark);
    for ($i = 0; $i < ($hmsplit_len - 1); $i++) {
        $hm_concat .= $headmark[$i] . '-';
        //echo $headmark[$i].'<br>';
    }

    $headmarkValueLast = $headmark[($hmsplit_len - 1)];
//    echo $hm_concat." -- ".$headmarkValueLast;
    if (is_numeric($headmarkValueLast)) {
        $lenHM = strlen($headmarkValueLast);
        $lenHM2 = 0;

        for ($i = 0; $i < $lenHM; $i++) {
            $perHruf = substr($headmarkValueLast, $i, 1);
            if (is_numeric($perHruf)) {
                $var[0] = $hm_concat . $str_HM; // STRING HM

                $perHruf = "";
                $lenStrHM = strlen($str_HM);
                $var[1] = substr($headmarkValueLast, $lenStrHM); // INTEGER HM

                $lenHM2 = $lenHM - $lenStrHM;
                $k = 0;
                for ($j = 0; $j < $lenHM2; $j++) {
                    $perHruf = substr($headmarkValueLast, ($lenStrHM + $j), 1);
                    if (is_numeric($perHruf)) {
                        $int_HM .= $perHruf;
                        $k += 1;
                    } else {
                        break;
                    }
                }

                $var[2] = 4 + ($lenHM2 - $k); // COUNT L_PAD
                break;
            } else {
                $str_HM .= $perHruf;
            }
        }
    } else {
        $var[0] = $headmarkValue;
        $var[1] = 123456789;
        $var[2] = 0;
    }
//    echo $var[0].' >> '.$var[1].' >> '.$var[2];
    return $var;
}

?>