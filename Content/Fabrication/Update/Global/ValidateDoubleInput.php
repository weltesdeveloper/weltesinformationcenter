<?php 
    require_once '../../../../dbinfo.inc.php';
    require_once '../../../../FunctionAct.php';

    $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

	$HM_ID              = intval($_POST['HM_ID']);
	$HEAD_MARK          = strval($_POST['HEAD_MARK']);
	$ProjNme            = strval($_POST['ProjNme']);
	$no                 = intval($_POST['no']);
	$firstQTY			= intval($_POST['firstQTY']);

	if ($_POST["type"] == "FAB_QC_PASS") {
		# code...
		$sql = "SELECT $_POST[type] FROM FABRICATION_QC WHERE PROJECT_NAME = '$ProjNme' AND HEAD_MARK='$HEAD_MARK' AND ID='$HM_ID' ";
	} else {
		# code...
		$sql = "SELECT $_POST[type] FROM FABRICATION WHERE PROJECT_NAME = '$ProjNme' AND HEAD_MARK='$HEAD_MARK' AND ID='$HM_ID' ";
	}	
	// echo "$sql";

	$jumlhFrst = SingleQryFld("$sql",$conn);

	if ($jumlhFrst <> $firstQTY) {
		# code...
		echo "<script>showDoubleInput('$no','$HEAD_MARK')</script>";
	}
 ?>