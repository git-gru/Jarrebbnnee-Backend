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
//p($_GET,0);
if (isset($_GET['pd_id']) && $_GET['pd_id'] != '') {
    $productData = getByProductId($_GET['pd_id']);
}
//p($productData,0);
if (isset($productData['pd_name'])) {
    $pd_name = $productData['pd_name'];
} else {
    $pd_name = '';
}
if (isset($productData['pd_price'])) {
    $pd_price = $productData['pd_price'];
} else {
    $pd_price = '';
}
//p($_POST);
if (isset($_POST) && !empty($_POST)) {
    $result = editproduct($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:product_list.php');
        exit;
    }
}
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
                    <div class="caption">EDIT PRODUCT</div>
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
                                                    <label class="control-label">PRODUCT NAME</label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $pd_name ?>" name="pd_name" id="pd_name"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">PRODUCT PRICE</label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $pd_price ?>" name="pd_price" id="pd_price"/>
                                                </div>
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="product_list.php" class="btn default"> Cancel </a>
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

