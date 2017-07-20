<?php
//include 'common.php';
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}
?>
<?php
$type = '';
$msg = '';
if (isset($_POST) && !empty($_POST)) {
    $_POST['ld_id'] = $_GET['ld_id'];
//    p($_POST);
    $result = addlottery($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:lottery_list.php');
        exit;
    }
}

if (isset($_GET['ld_id']) && $_GET['ld_id'] != '') {
    $lotteryData = getByLotteryId($_GET['ld_id']);
}
//p($lotteryData);
//if (isset($lotteryData['ld_number'])) {
//    $ld_number = $lotteryData['ld_number'];
//} else {
//    $ld_number = '';
//}
if (isset($lotteryData['ld_price'])) {
    $ld_price = $lotteryData['ld_price'];
} else {
    $ld_price = '';
}
//if (isset($lotteryData['ld_month'])) {
//    $ld_month = $lotteryData['ld_month'];
//} else {
//    $ld_month = '';
//}
if (isset($lotteryData['ld_name'])) {
    $ld_name = $lotteryData['ld_name'];
} else {
    $ld_name = '';
}
if (isset($lotteryData['ld_end_date'])) {
    $ld_end_date = $lotteryData['ld_end_date'];
} else {
    $ld_end_date = '';
}
if (isset($lotteryData['ld_start_date'])) {
    $ld_start_date = $lotteryData['ld_start_date'];
} else {
    $ld_start_date = '';
}
if (isset($lotteryData['ld_description'])) {
    $ld_description = $lotteryData['ld_description'];
} else {
    $ld_description = '';
}
if (isset($lotteryData['ld_image'])) {
    $ld_image = $lotteryData['ld_image'];
} else {
    $ld_image = '';
}
?>	
<?php
include 'layout/header.php';
include 'layout/menu.php';
?>
<!-- BEGIN PAGE -->
<div class="page-content">
    <div>

        <?php showFlash(); ?>

    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">ADD LOTTRY</div>
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
<!--                                                <div class="form-group">
                                                    <label class="control-label">LOTTRY NO.<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $ld_number ; ?>" name="ld_number" id="ld_number"/>
                                                </div>-->
                                                 <div class="form-group">
                                                    <label class="control-label">COUPON NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $ld_name ?>" name="ld_name" id="ld_name"/>
                                                </div>
                                                 <div class="form-group">
                                                    <label for="ld_description">COUPON DESCRIPTION</label>
                                                    <textarea class="form-control input-xlarge" rows="3" id="ld_description" name="ld_description"><?php echo $ld_description; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">LOTTRY PRICE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $ld_price ; ?>" name="ld_price" id="ld_price"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">START DATE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $ld_start_date ?>" name="ld_start_date" id="ld_start_date"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">END DATE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $ld_end_date ?>" name="ld_end_date" id="ld_end_date"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">LOTTERY IMAGE</label>
                                                    <input type="file" class="form-control input-xlarge" value="<?php echo $ld_image; ?>" name="ld_image"/>
                                                    <?php
                                                    if (isset($lotteryData['ld_id']) && $lotteryData['ld_id'] != '') {
                                                        if (isset($ld_image) && $ld_image != '') {
                                                            ?>
                                                            <img src="uploads/lottery/<?php echo $ld_image ?>" width="150" height="150"> <?php } else {
                                                            ?>
                                                            <img src="uploads/lottery/no-image.png"width="150" height="150">
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
<!--                                                <div class="form-group">
                                                    <label class="control-label">MONTHS</label>
                                                    <select name="ld_month" class="form-control input-xlarge">
                                                        <option value="January" <?=$ld_month == 'January' ? ' selected="selected"' : '';?>>January</option>
                                                        <option value="February" <?=$ld_month == 'February' ? ' selected="selected"' : '';?>>February</option>
                                                        <option value="March" <?=$ld_month == 'March' ? ' selected="selected"' : '';?>>March</option>
                                                        <option value="April" <?=$ld_month == 'April' ? ' selected="selected"' : '';?>>April</option>
                                                        <option value="May" <?=$ld_month == 'May' ? ' selected="selected"' : '';?>>May</option>
                                                        <option value="June" <?=$ld_month == 'June' ? ' selected="selected"' : '';?>>June</option>
                                                        <option value="July" <?=$ld_month == 'July' ? ' selected="selected"' : '';?>>July</option>
                                                        <option value="August" <?=$ld_month == 'August' ? ' selected="selected"' : '';?>>August</option>
                                                        <option value="September" <?=$ld_month == 'September' ? ' selected="selected"' : '';?>>September</option>
                                                        <option value="October" <?=$ld_month == 'October' ? ' selected="selected"' : '';?>>October</option>
                                                        <option value="November" <?=$ld_month == 'November' ? ' selected="selected"' : '';?>>November</option>
                                                        <option value="December" <?=$ld_month == 'December' ? ' selected="selected"' : '';?>>December</option>
                                                    </select>
                                                </div>-->
                                                <input type="hidden" name="ld_id" value="<?php if (isset($_GET['ld_id'])) echo $_GET['ld_id']; ?>">
                                                <div class="margin-top-20">
                                                    <button class="btn blue" type="submit" name="submit"> Save Changes </button>
                                                    <a href="lottery_list.php" class="btn default"> Cancel </a>
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
        $( "#ld_end_date" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-10:+100",
                dateFormat: 'dd-mm-yy'
        });
        $( "#ld_start_date" ).datepicker({
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

