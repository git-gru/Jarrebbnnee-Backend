<?php
//include 'common.php';
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}

?>
<?php
if (isset($_POST) && !empty($_POST)) {
     $_POST['slider_id'] = $_GET['s_id'];
    $result = addSlider($_POST);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:slider_list.php?type=s&msg=' . $result['message']);
        exit;
    } else {
        if (isset($_GET['s_id']) && $_GET['s_id'] != '') {
            header('location:addslider.php?type=e&s_id=' . $_GET['s_id'] . '&msg=' . $result['message']);
            exit;
        } else {
            header('location:addslider.php?type=e&msg=' . $result['message']);
            exit;
        }
    }
}

if (isset($_GET['s_id']) && $_GET['s_id'] != '') {
//    p($_GET);
    $sliderData = getBySliderId($_GET['s_id']);
}
if (isset($sliderData['slider_name'])) {
    $slider_name = $sliderData['slider_name'];
} else {
    $slider_name = '';
}

?>	
<?php
include 'layout/header.php';
include 'layout/menu.php';
?>
<!-- BEGIN PAGE -->
<div class="page-content">
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
    <div class="row">
        <div class="col-md-12">
            <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">ADD CATEGORY</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN PROFILE CONTENT -->
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-body">
                                            <form role="form" action="" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="control-label">SLIDER NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $slider_name ?>" name="slider_name" id="c_title"/>
                                                </div>
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="slider_list.php" class="btn default"> Cancel </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PROFILE CONTENT -->
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

