<?php
if (isset($_SERVER['PHP_SELF']) && $_SERVER['PHP_SELF'] != '') {
    $path = explode('/', $_SERVER['PHP_SELF'], 5);
    $count = count($path);
    $file_name = $path[$count - 1];
}
?>
<!-- BEGIN HEADER -->   

<div class="header navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner">
        <a class="navbar-brand" href="#">
            <img src="assets/img/logo-1.png" style="" alt="" width="100px;" />

        </a>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="assets/img/menu-toggler.png" alt="" />
        </a> 
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <?php
//		$S_PATH = "/familybless/admin/";
        if (isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) {
            ?>
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username"><?php echo $_SESSION["admin_name"] ?></span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php"><i class="fa fa-user"></i> My Profile</a></li>
                        <li><a href="logout.php"><i class="fa fa-key"></i> <?php echo "LOGOUT"; ?></a></li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <?php
        } else {
            
        }
        ?>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <?php
    if (isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) {
        ?>
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->        
            <ul class="page-sidebar-menu">
                <li style="margin-top: 50px;">
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'user_list.php' )) {
                    echo "class='active'";
                }
                ?> ><a href="user_list.php"><i class="fa fa-user" style="color: #5bc0de !important;"></i>User management</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'categories_list.php' )) {
                    echo "class='active'";
                }
                ?>><a href="categories_list.php"><i class="fa fa-rocket" style="color: #5bc0de !important;"></i>category management</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'product_list.php' )) {
                    echo "class='active'";
                }
                ?>><a href="product_list.php"><i class="fa fa-list" style="color: #5bc0de !important;"></i>product management</a>
                </li>
                
<!--                <li <?php
                //if (isset($file_name) && ($file_name == 'all_adslist.php' )) {
                  //  echo "class='active'";
                //}
                ?>>
                    <a href="all_adslist.php"><i class="fa fa-file-text" style="color: #5bc0de !important;" title="for any transaction between user"></i>Percentage management</a>
                </li>-->
                <li <?php
                if (isset($file_name) && ($file_name == 'slider_list.php' )) {
                    echo "class='active'";
                }
                ?>>
                    <a href="slider_list.php"><i class="fa fa-list" style="color: #5bc0de !important;"></i>Slider management</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'couponcode_list.php' )) {
                    echo "class='active'";
                }
                ?>>
                    <a href="couponcode_list.php"><i class="fa fa-qrcode" style="color: #5bc0de !important;"></i>coupon code</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'voucher_list.php' )) {
                    echo "class='active'";
                }
                ?>>
                    <a href="voucher_list.php"><i class="fa fa-money" style="color: #5bc0de !important;"></i>Manage vouchers</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'lottery_list.php' )) {
                    echo "class='active'";
                }
                ?>>
                    <a href="lottery_list.php"><i class="fa fa-money" style="color: #5bc0de !important;"></i>Manage Lottery</a>
                </li>
                <li <?php
                if (isset($file_name) && ($file_name == 'settings.php' )) {
                    echo "class='active'";
                }
                ?>>
                    <a href="settings.php"><i class="fa fa-cogs" style="color: #5bc0de !important;"></i>Settings</a>
                </li>
            </ul>   
        </div>
    <?php } ?>


    <!-- END SIDEBAR -->







