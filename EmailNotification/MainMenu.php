<?php
include '../dbinfo.inc.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MAIN MENU SENDING EMAIL</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/jquery.dataTables.css">

        <script src="../js/jquery-1.11.0.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery.dataTables.min.js"></script>
        <script>
            $(function () {
                $('.table').DataTable();
                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/DailyEmailFabrication.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 30 * 1000);

// PROJECT FINISHED
//                setInterval(function () {
//                    $.ajax({
//                        type: 'POST',
//                        url: "../EmailNotification/EmaiLW15030.php",
//                        success: function (response, textStatus, jqXHR) {
//                            console.log(response);
//                        }
//                    });
//                }, 60 * 60 * 1000);

                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/EmailIGG.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 90 * 1000);
                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/EmailISMS.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 120 * 1000);


                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/DelayEmailFabrication.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 150 * 1000);

//                setInterval(function () {
//                    $.ajax({
//                        type: 'POST',
//                        url: "../EmailNotification/EmailW15032.php",
//                        success: function (response, textStatus, jqXHR) {
//                            console.log(response);
//                        }
//                    });
//                }, 60 * 180 * 1000);

                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/EmaillW15050.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 210 * 1000);

                setInterval(function () {
                    $.ajax({
                        type: 'POST',
                        url: "../EmailNotification/auto_lock_opname.php",
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                        }
                    });
                }, 60 * 60 * 1000 * 24);
            });
        </script>
    </head>
    <body>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>PROJECT</th>
                <th>HEAD MARK</th>
                <th>ASSIGN DATE</th>
                <th>ASSEMBLY</th>
                <th>PROFILE</th>
                <th>SUBCONT</th>
                <th>ASSIGN QTY</th>
                <th>TOTAL WEIGHT</th>
                <th>STATUS</th>
                <th>REMARK</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM NOTIFICATION_EMAIL WHERE REMS IS NOT NULL";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                while ($row = oci_fetch_array($parse)) {
                    ?>
                    <tr>
                    <td><?php echo $row['PROJECT_NAME']; ?></td>
                    <td><?php echo $row['HEAD_MARK']; ?></td>
                    <td><?php echo $row['ASSG_DATE']; ?></td>
                    <td><?php echo $row['COMP_TYPE']; ?></td>
                    <td><?php echo $row['PROFILE']; ?></td>
                    <td><?php echo $row['SUBCONT_ID']; ?></td>
                    <td><?php echo $row['TOTAL_QTY']; ?></td>
                    <td><?php echo $row['WEIGHT']; ?></td>
                    <td><?php echo $row['PROC_TYPE'] . " " . $row['PROC_SUB_TYPE']; ?></td>
                    <td><?php echo $row['REMS']; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
