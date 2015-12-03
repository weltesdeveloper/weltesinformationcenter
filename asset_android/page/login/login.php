<!DOCTYPE html> 
<html>
    <!-- This template was designed and developed by Chris Converse, Codify Design Studio -->
    <head>
        <title>jQuery Mobile Web App</title>
        <meta charset="UTF-8">
        <meta name="description" content="This site was created from a template originally designed and developed by Codify Design Studio. Find more free templates at http://www.adobe.com/devnet/author_bios/chris_converse.html" />
        <link href="jquery-mobile/jquery.mobile-1.0a3.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />

        <script src="jquery-mobile/jquery-1.5.min.js" type="text/javascript"></script>
        <script src="jquery-mobile/jquery.mobile-1.0a3.min.js" type="text/javascript"></script>


    </head> 
    <body>


        <!--################################### HALAMAN LOGIN  -->
        <div data-role="page" id="page3">
            <div data-role="header">
                <h1>Login</h1>
            </div>
            <div data-role="content">	
                <div role="main" class="ui-content">
                    <h3>Sign In</h3>
                    <label for="txt-email">Email Address</label>
                    <input type="text" name="txt-email" id="txt-email" value="">
                    <label for="txt-password">Password</label>
                    <input type="password" name="txt-password" id="txt-password" value="">
                    <fieldset data-role="controlgroup">
                        <input type="checkbox" name="chck-rememberme" id="chck-rememberme" checked="">
                        <label for="chck-rememberme">Remember me</label>
                    </fieldset>
                    <a href="#dlg-invalid-credentials" data-rel="popup" data-transition="pop" data-position-to="window" id="btn-submit" class="ui-btn ui-btn-b ui-corner-all mc-top-margin-1-5">Submit</a>
                    <p class="mc-top-margin-1-5"><a href="begin-password-reset.html">Can't access your account?</a></p>
                    <div data-role="popup" id="dlg-invalid-credentials" data-dismissible="false" style="max-width:400px;">

                        <div role="main" class="ui-content">
                            <h3 class="mc-text-danger">Login Failed</h3>
                            <p>Did you enter the right credentials?</p>
                            <div class="mc-text-center"><a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b mc-top-margin-1-5">OK</a></div>
                        </div>

                    </div>
                </div><!-- /content -->
            </div>
            <div data-role="footer">
                <h4>&copy;2015 &bull; Hadi RentCar</h4>
            </div>
        </div>
        <!--################################### AKHIR HALAMAN LOGIN  -->


    </body>
</html>

<script src="index_script.js" type="text/javascript"></script>