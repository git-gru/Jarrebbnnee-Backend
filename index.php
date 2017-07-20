<?php

include './functions.php';

if (isset($_POST["username"]) && $_POST["username"] != '' && isset($_POST["password"]) && $_POST["password"] != '') {
    $result['message'] = '';
    $result = loginAdmin($_POST);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:user_list.php');
    } else {
        header('location:index.php?type=e&msg=' . $result['message']);
    }
}
?>

<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0.2
Version: 1.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

    <!-- BEGIN HEAD -->

    <head>

        <meta charset="utf-8" />

        <title>Login ||  Admin Login</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

        <meta content="" name="description" />

        <meta content="" name="author" />

        <meta name="MobileOptimized" content="320">

        <!-- BEGIN GLOBAL MANDATORY STYLES -->          

        <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>

        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL STYLES --> 

        <link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2_metro.css" />

        <!-- END PAGE LEVEL SCRIPTS -->

        <!-- BEGIN THEME STYLES --> 

        <link href="assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>

        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

        <link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>

        <link href="assets/css/plugins.css" rel="stylesheet" type="text/css"/>

        <link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>

        <link href="assets/css/pages/login.css" rel="stylesheet" type="text/css"/>

        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>

        <!-- END THEME STYLES -->

        <!--<link rel="shortcut icon" href="favicon.ico" />-->

    </head>

    <!-- BEGIN BODY -->

    <body class="login">

        <!-- BEGIN LOGO -->

        <div class="logo">

            <img src="assets/img/logo-1.png" style="" alt="" width="300px;" /> 

        </div>

        <!-- END LOGO -->

        <!-- BEGIN LOGIN -->

        <div class="content">

            <!-- BEGIN LOGIN FORM -->

            <?php

            if (isset($_POST["username"])) {

                ?>

                <div id="alert_login"><?php echo "LOGIN_ERROR"; ?></div>

                <?php

            }

            ?>

            <form name="login" action="" method="post" tmt:validate="true">

                 <div>

                    <?php if (isset($_GET['type']) && $_GET['type'] == 's' && isset($_GET['msg'])): ?>

                        <div class="alert alert-success">

                            <button class="close" data-close="alert"></button>

                            <h4><?php echo 'Success'; ?></h4>

                            <?php

                            echo $_GET['msg'];

                            ?>

                        </div>

                        <div class="clear"></div>

                    <?php endif; ?>

                    <?php if (isset($_GET['type']) && $_GET['type'] == 'e' && isset($_GET['msg'])): ?>

                        <div class="alert alert-danger">

                            <button class="close" data-close="alert"></button>

                            <h4><?php echo 'Error'; ?></h4>

                            <span>  <?php

                                echo $_GET['msg'];

                                ?>

                            </span>

                        </div>

                    <?php endif; ?>

                </div>

                <h3 class="form-title">Login to your account</h3>

                <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <span>Enter any username and password.</span>

                </div>

                <div class="form-group">

                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

                    <label for="username" class="control-label visible-ie8 visible-ie9"><?php echo "LOGIN_USERNAME"; ?></label>

                    <div class="input-icon">

                        <i class="fa fa-user"></i>

                        <input class="form-control placeholder-no-fix" type="email"  id="username" name="username" tmt:required="true" tmt:message="<?php echo "LOGIN_USERNAME_ALERT"; ?>" tmt:errorclass="error_validate" tmt:filters="ltrim,rtrim"/>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label visible-ie8 visible-ie9">Password</label>

                    <div class="input-icon">

                        <i class="fa fa-lock"></i>

                        <input class="form-control placeholder-no-fix" type="password" name="password" id="password" tmt:required="true" tmt:message="<?php echo "LOGIN_PASSWORD_ALERT"; ?>" tmt:errorclass="error_validate" tmt:filters="ltrim,rtrim"/>

                    </div>

                </div>
				<br>

                <div class="form-inline">

                    <label class="checkbox">

                        <input type="checkbox" name="remember" value="1"/> Remember me

                    </label>

                    <button type="submit" id="login_button"class="btn green pull-right">

                        <?php echo "LOGIN"; ?> <i class="m-icon-swapright m-icon-white"></i>

                    </button>            

                </div>

            </form>

            <!-- END LOGIN FORM -->        

            <!-- BEGIN FORGOT PASSWORD FORM -->



            <!-- END FORGOT PASSWORD FORM -->

            <!-- BEGIN REGISTRATION FORM -->



            <!-- END REGISTRATION FORM -->

		<br>
		<br>
            <!-- BEGIN COPYRIGHT -->
            <div class="copyright">
    
                Copyright &copy; JARREBBNNEE, 2017
    
            </div>
             <!-- END COPYRIGHT -->
        </div>

        <!-- END LOGIN -->

       

 
       

        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

        <!-- BEGIN CORE PLUGINS -->   

        <!--[if lt IE 9]>

        <script src="assets/plugins/respond.min.js"></script>

        <script src="assets/plugins/excanvas.min.js"></script> 

        <![endif]-->   

        <script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>

        <script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

        <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>

        <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>

        <script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  

        <script src="assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>

        <script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>

        <!-- END CORE PLUGINS -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>	

        <script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>     

        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->

        <script src="assets/scripts/app.js" type="text/javascript"></script>

        <script src="assets/scripts/login.js" type="text/javascript"></script> 

        <!-- END PAGE LEVEL SCRIPTS --> 

        <script>

            jQuery(document).ready(function () {

                App.init();

                Login.init();

            });

        </script>

        <!-- END JAVASCRIPTS -->

    </body>

    <!-- END BODY -->

</html>