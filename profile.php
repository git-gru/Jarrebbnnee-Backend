<?php
//include 'common.php';
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}
?>
<?php
//p($_SESSION['admin_name']);
if (isset($_POST) && !empty($_POST)) {
    $result['message'] = '';
    if (isset($_POST['action']) && $_POST['action'] == 'password') {
        $result = changePasswordAdmin($_POST);
    } else if (isset($_POST['action']) && $_POST['action'] == 'profile') {
        $result = updateAdminProfile($_POST);
    }
     setFlash($result);
//    if (isset($result['status']) && $result['status'] == '0') {
//        header('location:profile.php?type=s&msg=' . $result['message']);
//        exit;
//    } else {
//        header('location:profile.php?type=e&msg=' . $result['message']);
//        exit;
//    }
}
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != '') {
    $userData = getByAdminId($_SESSION['admin_id']);
}

if (isset($userData['admin_username'])) {
    $admin_username = $userData['admin_username'];
} else {
    $admin_username = '';
}
?>	
<?php
include 'layout/header.php';
include 'layout/menu.php';
?>
<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">ADMIN PROFILE</div>
                </div>
                <div>
                    <?php showFlash();?>
                </div>
                <div class="portlet light">

                    <ul class="nav nav-tabs">

                        <li class="active">
                            <a href="#portlet_tab2_2" data-toggle="tab" aria-expanded="true"> Information </a>
                        </li>
                        <li class="">
                            <a href="#portlet_tab2_1" data-toggle="tab" aria-expanded="false"> Change Password </a>
                        </li>
                    </ul>

                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane " id="portlet_tab2_1">
                                <form role="form" action="" method="post">
                                    <div class="form-group">
                                        <label class="control-label">Old Password</label>
                                        <input required="true" type="password" class="form-control input-xlarge" name="old_password"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">New Password</label>
                                        <input required="true" type="password" class="form-control input-xlarge" name="new_password"/>
                                    </div>
                                    <input type="hidden" name="admin_id" value="<?php if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != '') echo $_SESSION['admin_id']; ?>">
                                    <input type="hidden" name="action" value="password">

                                    <div class="margin-top-20">
                                        <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                        <a href="user_list.php" class="btn default"> Cancel </a>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane active" id="portlet_tab2_2">
                                <form role="form" action="" method="post">
                                    <div class="form-group">
                                        <label class="control-label">User Name</label>
                                        <input required="true" type="text" class="form-control input-xlarge"  name="admin_username"/>
                                    </div>
                                    <input type="hidden" name="action" value="profile">
                                    <input type="hidden" name="admin_id" value="<?php if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != '') echo $_SESSION['admin_id']; ?>">
                                    <div class="margin-top-20">
                                        <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                        <a href="user_list.php" class="btn default"> Cancel </a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>

</div>
<!-- END PAGE -->
</div>
<!-- END CONTAINER -->

<?php
include 'layout/footer.php';
?>

