<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="../../jQuery/jquery-1.11.0.js"></script>
        <script>
            setInterval(function () {
                $.ajax({
                    type: 'POST',
                    url: "AutoLockOpname_ACT.php",
                    success: function (response, textStatus, jqXHR) {
                        alert(response)
                    }
                })
            }, 1000);
        </script>
    </head>
    <body>
        <?php
        $dateStr = date('Y-m-d', strtotime('this saturday'));
//            echo "$dateStr";
        $tanggal = date('Y-m-d');
        if ($dateStr == $tanggal) {
            echo "hari ini sabtu";
        } else {
            echo "hari ini bukan sabtu";
        }
        ?>
    </body>
</html>
