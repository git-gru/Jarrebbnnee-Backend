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
    $_POST['cc_id']=$_GET['cc_id'];
    $result = addCouponcode($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:couponcode_list.php');
        exit;
    }
}

if (isset($_GET['cc_id']) && $_GET['cc_id'] != '') {
    $couponcodeData = getByCouponId($_GET['cc_id']);
}
//p($couponcodeData);
if (isset($couponcodeData['cc_name'])) {
    $coupon_name = $couponcodeData['cc_name'];
} else {
    $coupon_name = '';
}
if (isset($couponcodeData['cc_code'])) {
    $cc_code = $couponcodeData['cc_code'];
} else {
    $cc_code = '';
}
if (isset($couponcodeData['cc_end_date'])) {
    $cc_end_date = $couponcodeData['cc_end_date'];
} else {
    $cc_end_date = '';
}
if (isset($couponcodeData['cc_start_date'])) {
    $cc_start_date = $couponcodeData['cc_start_date'];
} else {
    $cc_start_date = '';
}
if (isset($couponcodeData['cc_description'])) {
    $cc_description = $couponcodeData['cc_description'];
} else {
    $cc_description = '';
}
if (isset($couponcodeData['cc_discount_amount'])) {
    $cc_discount_amount = $couponcodeData['cc_discount_amount'];
} else {
    $cc_discount_amount = '';
}
if (isset($couponcodeData['cc_discount_type'])) {
    $cc_discount_type = $couponcodeData['cc_discount_type'];
} else {
    $cc_discount_type = '';
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
                    <div class="caption">ADD COUPON</div>
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
                                                    <label class="control-label">COUPON NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $coupon_name ?>" name="coupon_name" id="coupon_name"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">COUPON CODE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $cc_code ?>" name="cc_code" id="cc_code"/>
                                                </div>
                                                 <div class="form-group">
                                                    <label for="cc_description">COUPON DESCRIPTION</label>
                                                    <textarea class="form-control input-xlarge" rows="3" id="cc_description" name="cc_description"><?php echo $cc_description; ?></textarea>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label">COUPON DISCOUNT TYPE<span class="required">*</span></label>
                                                    <select name="cc_discount_type" class="form-control input-xlarge">
                                                        <option name="flat" value="flat" <?php if($cc_discount_type == 'flat') echo "selected='selected'";?> > flat</option>
                                                        <option name="percentage" value="percentage" <?php if($cc_discount_type == 'percentage') echo "selected='selected'";?>>percentage</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">COUPON DISCOUNT PRICE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $cc_discount_amount ?>" name="cc_discount_amount" id="cc_discount_amount"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">START DATE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $cc_start_date ?>" name="cc_start_date" id="cc_start_date"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">END DATE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $cc_end_date ?>" name="cc_end_date" id="cc_end_date"/>
                                                </div>
                                                <input type="hidden" name="cc_id" value="<?php if (isset($_GET['cc_id'])) echo $_GET['cc_id']; ?>">
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="couponcode_list.php" class="btn default"> Cancel </a>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        var dateToday = new Date();
        $( "#cc_end_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-10:+100",
                dateFormat: 'dd-mm-yy'
        });
        $( "#cc_start_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-10:+100",
                dateFormat: 'dd-mm-yy'
        });
    });
</script>
<?php
include 'layout/footer.php';
?>

