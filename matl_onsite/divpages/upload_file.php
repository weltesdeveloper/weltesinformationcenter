<?php

require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

$upload_path = "upload/";
if (!is_dir($upload_path)) {
    mkdir($upload_path);
}
//file 1
$length = $_POST['length'];
//echo "$length";
for ($i = 0; $i < $length; $i++) {
    $filename = "file" . $i;
//    $file = $_FILES[$filename]["name"];
    $lob_upload = $_FILES[$filename]['tmp_name'];
//    $extensions = $_FILES[$filename]['extension'];
//    $uploadfile = (file_exists($upload_path . $file)) ? $upload_path . " copy of " . $file : copy($tmp, $upload_path . $file);
//    $target_file = "upload/$file";
//    $resized_file = "upload/$file";
//    $wmax = 800;
//    $hmax = 600;
//    ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);

    $lob = oci_new_descriptor($conn, OCI_D_LOB);
    $stmt = oci_parse($conn, "insert into RECEIVE_MATL_PHOTO (IMG)
               values(EMPTY_BLOB()) returning IMG into :IMG");
    oci_bind_by_name($stmt, ':IMG', $lob, -1, OCI_B_BLOB);
    oci_execute($stmt, OCI_DEFAULT);
    if ($lob->savefile(
            
            
            
            
            
             )) {
        oci_commit($conn);
        echo "Blob successfully uploaded\n";
    } else {
        echo "Couldn't upload Blob\n";
    }
    $lob->free();
    oci_free_statement($stmt);
    oci_close($conn);
}

function ak_img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
        $w = $h * $scale_ratio;
    } else {
        $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif") {
        $img = imagecreatefromgif($target);
    } else if ($ext == "png") {
        $img = imagecreatefrompng($target);
    } else {
        $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 80);
}

//echo "SUCCESS INSERT";
