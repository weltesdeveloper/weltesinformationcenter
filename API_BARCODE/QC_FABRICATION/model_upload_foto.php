<?php

//memberi pengalamatan khusus untuk json
header('Content-type: application/json');

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);


//print_r($_FILES);
$nama_file = $_FILES["file"]["name"];
$lob_upload = $_FILES["file"]["tmp_name"];

$projectName = $_POST['projectName'];
$head = $_POST['headMark'];
$element = $_POST['element'];
$user = $_POST['user'];
$qty = $_POST['qty'];
$unit_qty = $_POST['unit_qty'];
$unit_exe = $_POST['unit_exe'];
$remark = "Fit Up Oke";
$subcont = $_POST['subCont'];



if ($element == 'mark') {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, MARKING,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET MARKING = MARKING+$qty, MARKING_FAB_DATE = SYSDATE, MARKING_FAB_SIGN = '$user' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
} else if ($element == 'cutt') {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, CUTTING,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET CUTTING = CUTTING+$qty, CUTTING_FAB_DATE = SYSDATE, CUTTING_FAB_SIGN = '$user' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
} else if ($element == 'assy') {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, ASSEMBLY,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET ASSEMBLY = ASSEMBLY+$qty, ASSEMBLY_FAB_DATE = SYSDATE, ASSEMBLY_FAB_SIGN = '$user' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
} else if ($element == 'weld') {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, WELDING,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET WELDING = WELDING+$qty, WELDING_FAB_DATE = SYSDATE, WELDING_FAB_SIGN = '$user' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
} else if ($element == 'drill') {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, DRILLING,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET DRILLING = DRILLING+$qty, DRILLING_FAB_DATE = SYSDATE, DRILLING_FAB_SIGN = '$user' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
} else {
    //#################### QUERY INSERT FABRICATION_HIST
    $stmt = oci_parse($conn, "INSERT INTO FABRICATION_HIST (PROJECT_NAME, HEAD_MARK, ID, FAB_ENTRY_DATE, FAB_HIST_SIGN, FINISHING,IMG,REMARK)
               values('$projectName','$head','$subcont',SYSDATE,'$user','$qty',EMPTY_BLOB(),'$remark') returning IMG into :IMG2");

    //#################### QUERY UPDATE FABRICATION
    $query = "UPDATE FABRICATION SET FINISHING = FINISHING+$qty, FINISHING_FAB_DATE = SYSDATE, FINISHING_FAB_SIGN = '$user', FAB_STATUS = 'NOTCOMPLETE' "
            . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";

    if ($unit_qty == ($unit_exe + $qty)) {
        $query = "UPDATE FABRICATION SET FINISHING = FINISHING+$qty, FINISHING_FAB_DATE = SYSDATE, FINISHING_FAB_SIGN = '$user', FAB_STATUS = 'COMPLETE' "
                . "WHERE HEAD_MARK = '$head' AND ID = '$subcont'";
    }
}


// ######################### INSERT KE FABRICATION HIST
$lob = oci_new_descriptor($conn, OCI_D_LOB);
oci_bind_by_name($stmt, ':IMG2', $lob, -1, OCI_B_BLOB);
$exe = oci_execute($stmt, OCI_DEFAULT);
$lob->savefile($lob_upload);
oci_commit($conn);

$lob->free();
oci_free_statement($stmt);
oci_close($conn);
// ######################### INSERT KE FABRICATION HIST END
// ######################### UPDATE KE FABRICATION
$hasil = oci_parse($conn, $query);
$exe = oci_execute($hasil);
if ($exe) {
    oci_commit($conn);
    echo json_encode('1');
} else {
    oci_rollback($conn);
    echo json_encode('0');
}
// ######################### UPDATE KE FABRICATION END


?>
