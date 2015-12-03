<?php
// LOGGING OUT FROM THE SESSION
session_start();
unset($_SESSION['username']);
echo <<<EOD
<!DOCTYPE html>
<body style="font-family: Arial, sans-serif;">
<h2>Goodbye</h2>
<p>You are logged out.<p>
    <form>
        <input TYPE="button" id="login_again" VALUE="LOGIN AGAIN"
        onclick="window.location.href='login.php'"> 
    </form>
</body>
EOD;

?>
