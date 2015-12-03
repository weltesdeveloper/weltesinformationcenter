<?php
require_once('dbinfo.inc.php');
session_start();
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = $_POST['username'];
$pass = $_POST['password'];
oci_set_client_identifier($conn, 'admin');
$s = oci_parse($conn, 'select app_username from weltes_sec_admin.weltes_authentication
                 where app_username = :un_bv and app_password = :pw_bv');
oci_bind_by_name($s, ":un_bv", $username);
oci_bind_by_name($s, ":pw_bv", $pass);
oci_execute($s);
$r = oci_fetch_array($s, OCI_ASSOC);
if ($r) {
    $_SESSION['username'] = $username;
    echo ('<script>window.location.replace("mainmenu.php")</script>');
} else {
    // No rows matched so login failed
    echo ('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
    echo ('<script>location.href="../../index.php"</script>');
}
?>