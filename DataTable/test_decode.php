
<?php
$json = '{"tes": 12345}';

$obj = json_decode($json);
print $obj->{'tes'};

?>
