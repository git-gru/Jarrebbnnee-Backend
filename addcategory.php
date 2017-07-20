<?php
//include 'common.php';
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}
?>
<?php
$type='';
$msg='';
if (isset($_POST) && !empty($_POST)) {
    $result = addCategories($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:categories_list.php');
        exit;
    }
}

if (isset($_GET['pc_id']) && $_GET['pc_id'] != '') {
    $serviceData = getByCategoryId($_GET['pc_id']);
}
//p($serviceData);
if (isset($serviceData['pc_name'])) {
    $c_title = $serviceData['pc_name'];
} else {
    $c_title = '';
}
//if (isset($serviceData['c_is_parent_id'])) {
//    $c_is_parent_id = $serviceData['c_is_parent_id'];
//} else {
//    $c_is_parent_id = '';
//}
//if (isset($serviceData['c_images'])) {
//    $c_images = $serviceData['c_images'];
//} else {
//    $c_images = '';
//}

?>	
<?php
include 'layout/header.php';
include 'layout/menu.php';
?>
<!-- BEGIN PAGE -->
<div class="page-content">
    <div>

     <?php showFlash();?>

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
                                                    <label class="control-label">CATEGORY NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $c_title ?>" name="cate_name" id="c_title"/>
                                                </div>
<!--                                                <div class="form-group">
                                                    <label class="control-label">SELECT PARENT CATEGORY<span class="required">*</span></label>
                                                    <?php echo serviceCateDropdown($c_is_parent_id);?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Category Image</label>
                                                    <input type="file" class="form-control input-xlarge" value="<?php echo $c_images; ?>" name="u_img"/>
                                                    <?php
                                                    if (isset($serviceData['c_id']) && $serviceData['c_id'] != '') {
                                                        if (isset($c_images) && $c_images != '') {
                                                            ?>
                                                            <img src="uploads/serviceprofile/<?php echo $c_images ?>" width="150" height="150"> <?php } else {
                                                            ?>
                                                            <img src="uploads/serviceprofile/no-image.png"width="150" height="150">
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>-->
                                                <input type="hidden" name="c_id" value="<?php if (isset($_GET['c_id'])) echo $_GET['c_id']; ?>">
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="categories_list.php" class="btn default"> Cancel </a>
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

