<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>WEN | LOGIN</title>
        
            
            <link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
            <link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
            <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
            <link rel="manifest" href="images/manifest.json">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="images/ms-icon-144x144.png">
            <meta name="theme-color" content="#ffffff">
        
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="AdminLte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header bg-olive">Weltes Information Center</div>
            <form action="checklogin.php" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  
                </div>
            </form>

            <div class="margin text-center">
                <span><i class="fa fa-vk"></i>&nbsp;&nbsp;&nbsp;&nbsp;Copyright 2014. PT. Weltes Energi Nusantara</span><br/>
            </div>
        </div>
        <script src="jQuery/jquery-2.1.1.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>