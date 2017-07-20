<?php
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}
?>
<?php
if (isset($_POST) && !empty($_POST)) {
    $_POST['s_id'] = isset($_GET['s_id']) ? $_GET['s_id'] : "";
    $result = addSliderImage($_POST);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:slider_image_list.php?s_id='.$_POST['s_id'].'&type=s&msg=' . $result['message']);
        exit;
    } else {
        if (isset($_GET['ab_id']) && $_GET['ab_id'] != '') {
            header('location:addslider_image.php?s_id='.$_POST['s_id'].'&type=e&sir_id=' . $_GET['sir_id'] . '&msg=' . $result['message']);
            exit;
        } else {
            header('location:addslider_image.php?s_id='.$_POST['s_id'].'&type=e&msg=' . $result['message']);
            exit;
        }
    }
}

if (isset($_GET['sir_id']) && $_GET['sir_id'] != '') {
    $sliderData = getBySliderImageId($_GET['sir_id']);
}

if (isset($sliderData['sir_heading_text'])) {
    $slider_mtitle = $sliderData['sir_heading_text'];
} else {
    $slider_mtitle = '';
}
if (isset($sliderData['sir_sub_heading_text'])) {
    $slider_stitle = $sliderData['sir_sub_heading_text'];
} else {
    $slider_stitle = '';
}
if (isset($sliderData['sir_img'])) {
    $slider_images = $sliderData['sir_img'];
} else {
    $slider_images = '';
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
                    <div class="caption">ADD SLIDER IMAGE </div>
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
                                                    <label class="control-label">Main Title</label>
                                                    <input type="text" class="form-control input-xlarge" value="<?php echo $slider_mtitle; ?>" name="slider_mtitle"/>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label">Sub Title</label>
                                                    <input type="text" class="form-control input-xlarge" value="<?php echo $slider_stitle; ?>" name="slider_stitle"/>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label">Slider Image <?php if (!isset($_GET['sir_id'])) { ?> <span class="required">*</span> <?php } ?></label>
                                                    <input <?php if (!isset($_GET['sir_id'])) { ?> required="true" <?php } ?> type="file" class="form-control input-xlarge" value="<?php echo $slider_images; ?>" name="slider_images"/>
                                                    <?php
                                                    if (isset($_GET['sir_id']) && $_GET['sir_id'] != '') {
                                                        if (isset($slider_images) && $slider_images != '') {
                                                            ?>
                                                            <img src="uploads/slider/<?php echo $slider_images ?>" width="150" height="150"> <?php } else {
                                                            ?>
                                                            <img src="uploads/slider/no-image.png"width="150" height="150">
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" name="sir_id" value="<?php if (isset($_GET['sir_id'])) echo $_GET['sir_id']; ?>">
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="slider_image_list.php?s_id=<?php echo $_GET['s_id'];?>" class="btn default"> Cancel </a>
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

