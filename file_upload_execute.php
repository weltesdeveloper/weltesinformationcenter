<?php
$max_size = 1024*200;
$extensions = array('jpeg', 'jpg', 'png');
$dir = '192.168.15.195/WeltesInformationCenter/file_uploads/';
$count = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['files']))
{
    // LOOP THE ARRAY
    foreach ( $_FILES['files']['name'] as $i => $name )
    {
        if ( !is_uploaded_file($_FILES['files']['tmp_name'][$i]) ){
            continue;
        }

        // SKIP OVERSIZED FILE
        if ( $_FILES['files']['size'][$i] >= $max_size ) {
            continue;
        }

        // MOVE UPLOADED FILE
        if( move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir . $name) ) {
            $count++;
        }
    }
    echo json_encode(array('count' => $count));
}
?>
