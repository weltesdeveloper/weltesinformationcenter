<?php
require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION
if (!isset($_SESSION['username'])) {
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
?>

<?php
$projectName = strval($_GET['project']);
$componentType = strval($_GET['componentValue']);
$headmarkValue = strval($_GET['headmarkValue']);

$perHruf = concatHM($headmarkValue);
$str_HM = @$perHruf[0];
$int_HM = @$perHruf[1];
$pad_HM = @$perHruf[2];
if (strlen($int_HM) > 4 || sizeof($perHruf) == 0) {
    $str_HM = $headmarkValue;
    $int_HM = 0;
    $pad_HM = 0;
}
?>

<?php
$weightSql = "SELECT WEIGHT AS UNIT_WEIGHT, GR_WEIGHT FROM MASTER_DRAWING WHERE DWG_STATUS='ACTIVE' AND (HEAD_MARK = :HM OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))";
$weightParse = oci_parse($conn, $weightSql);

// oci_bind_by_name($weightParse, ":PROJNAME", $projectName);
// oci_bind_by_name($weightParse, ":COMP", $componentType);
oci_bind_by_name($weightParse, ":HM", $headmarkValue);

oci_define_by_name($weightParse, "UNIT_WEIGHT", $unitWeight);
oci_define_by_name($weightParse, "GR_WEIGHT", $unit_GRWeight);


oci_execute($weightParse);
?>

<?php
$surfaceSql = "SELECT SURFACE AS UNIT_SURFACE FROM MASTER_DRAWING WHERE DWG_STATUS='ACTIVE' AND (HEAD_MARK = :HM OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))";
$surfaceParse = oci_parse($conn, $surfaceSql);

// oci_bind_by_name($surfaceParse, ":PROJNAME", $projectName);
// oci_bind_by_name($surfaceParse, ":COMP", $componentType);
oci_bind_by_name($surfaceParse, ":HM", $headmarkValue);

oci_define_by_name($surfaceParse, "UNIT_SURFACE", $unitSurface);

oci_execute($surfaceParse);
?>

<?php
$lengthSql = "SELECT LENGTH AS UNIT_LENGTH FROM MASTER_DRAWING WHERE DWG_STATUS='ACTIVE' AND (HEAD_MARK = :HM OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))";
$lengthParse = oci_parse($conn, $lengthSql);

// oci_bind_by_name($lengthParse, ":PROJNAME", $projectName);
// oci_bind_by_name($lengthParse, ":COMP", $componentType);
oci_bind_by_name($lengthParse, ":HM", $headmarkValue);

oci_define_by_name($lengthParse, "UNIT_LENGTH", $unitLength);

oci_execute($lengthParse);
?>

<?php
$qtySql = "SELECT TOTAL_QTY AS UNIT_QTY FROM MASTER_DRAWING WHERE DWG_STATUS='ACTIVE' AND (HEAD_MARK = :HM OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))";
$qtyParse = oci_parse($conn, $qtySql);

// oci_bind_by_name($qtyParse, ":PROJNAME", $projectName);
// oci_bind_by_name($qtyParse, ":COMP", $componentType);
oci_bind_by_name($qtyParse, ":HM", $headmarkValue);

oci_define_by_name($qtyParse, "UNIT_QTY", $unitQty);

oci_execute($qtyParse);
?>

<?php
$profileSql = "SELECT PROFILE AS UNIT_PROFILE,SUBCONT_STATUS AS SUBCSTAT, REV, DISTRIBUTION_COUNT,DWG_TYP FROM MASTER_DRAWING WHERE DWG_STATUS='ACTIVE' AND (HEAD_MARK = :HM OR HEAD_MARK = ('$str_HM'||LPAD('$int_HM',$pad_HM)))";
// echo "$profileSql";
$profileParse = oci_parse($conn, $profileSql);

// oci_bind_by_name($profileParse, ":PROJNAME", $projectName);
// oci_bind_by_name($profileParse, ":COMP", $componentType);
oci_bind_by_name($profileParse, ":HM", $headmarkValue);

oci_define_by_name($profileParse, "UNIT_PROFILE", $unitProfile);
oci_define_by_name($profileParse, "SUBCSTAT", $SubcStat);
oci_define_by_name($profileParse, "REV", $REV);
oci_define_by_name($profileParse, "DISTRIBUTION_COUNT", $Distrib);
oci_define_by_name($profileParse, "DWG_TYP", $dwg_typ);

oci_execute($profileParse);
?>

<?php
while (oci_fetch($weightParse)) {
    $unitWeight;
    $unit_GRWeight;
}
?>
<?php
while (oci_fetch($surfaceParse)) {
    $unitSurface;
}
?>
<?php
while (oci_fetch($lengthParse)) {
    $unitLength;
}
?>
<?php
while (oci_fetch($qtyParse)) {
    $unitQty;
}
?>
<?php
while (oci_fetch($profileParse)) {
    $unitProfile;
    $SubcStat;
    $REV;
    $Distrib;
    $dwg_typ;
}
?>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="red">DRAWING STATUS</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="drawingStatus" name="drawingStatus" placeholder="" value="<?php echo $SubcStat ?>" readonly="true">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">PROFILE</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="name" name="unitProfile" placeholder="" value="<?php echo $unitProfile; ?>" data-bv-notempty="true" data-bv-notempty-message="PROFILE is required and cannot be empty">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">WEIGHT</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="name" name="unitWeightRev" placeholder="" value="<?php echo $unitWeight; ?>" data-bv-notempty="true" data-bv-notempty-message="WEIGHT is required and cannot be empty">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">GROSS WEIGHT</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="name" name="unitGRWeightRev" placeholder="" value="<?php echo $unit_GRWeight; ?>" data-bv-notempty="true" data-bv-notempty-message="GROSS WEIGHT is required and cannot be empty">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">SURFACE</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="name" name="unitSurfaceRev" placeholder="" value="<?php echo $unitSurface; ?>" data-bv-notempty="true" data-bv-notempty-message="SURFACE is required and cannot be empty">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">LENGTH</font></label>
    <div class="col-sm-10">
        <input type="name" class="form-control" id="name" name="unitLengthRev" placeholder="" value="<?php echo $unitLength; ?>" data-bv-notempty="true" data-bv-notempty-message="LENGTH is required and cannot be empty">
    </div>
</div>
<div>
    <label for="name" class="col-sm-2 control-label"><font color="blue">TOTAL QUANTITY</font></label>
    <div class="col-sm-10">
        <input type="hidden" name="actDistrib" value="<?php echo $Distrib; ?>">
        <input type="number" class="form-control" id="name" name="unitQtyRev" placeholder="" value="<?php echo $unitQty; ?>" data-bv-notempty="true" data-bv-notempty-message="TOTAL QTY is required and cannot be empty">
    </div>
</div>
<div>
    <label for="revisionRemarks" class="col-sm-2 control-label"><font color="blue">DWG TYPE</font></label>
    <div class="col-sm-10">
        <select class="form-control" id="" id="dwg_typ" name="dwg_typ">
            <option value="H" <?php if ($dwg_typ == 'H'): ?>  selected="" <?php endif ?> >HOTROLL</option>                                
            <option value="W" <?php if ($dwg_typ == 'W'): ?>  selected="" <?php endif ?>>WELDED</option>
        </select>
    </div>
</div>
<div>
    <label for="revisionRemarks" class="col-sm-2 control-label"><font color="blue">REVISION REMARKS</font></label>
    <div class="col-sm-10">
        <input type="hidden" name="actREV" value="<?php echo $REV; ?>">
        <input type="text" class="form-control" id="revRemarks" name="revRemarks" placeholder="" value="" data-bv-notempty="true" data-bv-notempty-message="REVISION REMARKS is required and cannot be empty">
    </div>
</div>
