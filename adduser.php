<?php
//include 'common.php';
include './functions.php';
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 0) {
    header('Location: login.php');
}
?>
<?php
if (isset($_POST) && !empty($_POST)) {
    
    $result = addUser($_POST);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:user_list.php?type=s&msg=' . $result['message']);
        exit;
    } else {
        if (isset($_GET['u_id']) && $_GET['u_id'] != '') {
            header('location:adduser.php?type=e&u_id=' . $_GET['u_id'] . '&msg=' . $result['message']);
            exit;
        } else {
            header('location:adduser.php?type=e&msg=' . $result['message']);
            exit;
        }
    }
}

if (isset($_GET['u_id']) && $_GET['u_id'] != '') {
    $userData = getByUserId($_GET['u_id']);
}
//p($userData);
if (isset($userData['u_first_name'])) {
    $u_first_name = $userData['u_first_name'];
} else {
    $u_first_name = '';
}
if (isset($userData['u_last_name'])) {
    $u_last_name = $userData['u_last_name'];
} else {
    $u_last_name = '';
}
if (isset($userData['u_email'])) {
    $u_email = $userData['u_email'];
} else {
    $u_email = '';
}
if (isset($userData['u_phone'])) {
    $u_phone = $userData['u_phone'];
} else {
    $u_phone = '';
}
if (isset($userData['u_address'])) {
    $u_address = $userData['u_address'];
} else {
    $u_address = '';
}
if (isset($userData['u_city'])) {
    $cityEditData = $userData['u_city'];
} else {
    $cityEditData = '';
}
if (isset($userData['u_postcode'])) {
    $u_postcode = $userData['u_postcode'];
} else {
    $u_postcode = '';
}
if (isset($userData['u_country'])) {
    $u_country = $userData['u_country'];
} else {
    $u_country = '';
}
//if (isset($userData['u_type'])) {
//    $u_type = $userData['u_type'];
//} else {
//    $u_type = '0';
//}
if (isset($userData['u_img'])) {
    $u_img = $userData['u_img'];
} else {
    $u_img = '';
}

if (isset($userData['u_gender'])) {
    $u_gender = $userData['u_gender'];
} else {
    $u_gender = '';
}
if (isset($userData['u_type'])) {
    $u_type = $userData['u_type'];
} else {
    $u_type = '';
}
if (isset($u_country) && $u_country != '') {
    $subcatData = '';
    $selectSubcat = "SELECT * FROM city WHERE city_country_id = $u_country"; 
    $subcatQuery = mysql_query($selectSubcat);

    while ($row2 = mysql_fetch_assoc($subcatQuery)) {
        if ($u_country == $row2['city_country_id']) {
            $selectSring = 'selected="true"';
        } else {
            $selectSring = '';
        }
        $subcatData.='<option ' . $selectSring . ' value="' . $row2['city_id'] . '">' . $row2['city_name'] . '</option>';
    }
    $cityEditData = '<select id="city_name" name="city_name" class="form-control input-xlarge">' . $subcatData . '</select>';
//    p($cityEditData);
} else {
    $u_country = '';
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
                    <div class="caption">ADD USERS</div>
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
                                                    <label class="control-label">SELECT USER TYPE<span class="required">*</span></label>
                                                    <div class="dropdown show">

                                                        <select class="form-control input-xlarge" name="u_type">
                                                            <option  value="1" <?php
                                                            if ($u_type == 1) {
                                                                echo "selected='true'";
                                                            }
                                                            ?>>User</option>
                                                            <option  value="2" <?php
                                                            if ($u_type == 2) {
                                                                echo "selected='true'";
                                                            }
                                                            ?>>Seller</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">FIRST NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $u_first_name ?>" name="u_first_name" id="u_name"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">LAST NAME<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $u_last_name ?>" name="u_last_name" id="u_name"/>
                                                </div>
<!--                                                <div class="form-group">
                                                    <label class="control-label">GENDER</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="u_gender" value="M"<?php //echo ($u_gender == 'M') ? 'checked' : '' ?>>MALE
                                                    </label>
                                                    <label class="radio-inline">

                                                        <input type="radio" name="u_gender" value="F"<?php //echo ($u_gender == 'F') ? 'checked' : '' ?>>FEMALE
                                                    </label>
                                                </div>-->
                                                <div class="form-group">
                                                    <label class="control-label">EMAIL<span class="required">*</span></label>
                                                    <input required="true" type="email" class="form-control input-xlarge" value="<?php echo $u_email; ?>" name="u_email"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">PASSWORD<span class="required">*</span></label>
                                                    <input <?php
                                                    if (isset($_GET['u_id']) && $_GET['u_id'] != '')
                                                        echo '';
                                                    else
                                                        echo 'required="true"';
                                                    ?>  type="password" class="form-control input-xlarge" name="u_password"/>
                                                </div>

                                              <div class="form-group">    
                                                    <label class="control-label">COUNTRY NAME</label>
                                                    <div class="input-medium bfh-countries">
                                                        <?php echo countryDropdown($u_country); ?>
                                                    </div>
                                                </div><!--  
                                              
                                                 <?php
                                                //if (isset($_GET['u_id']) && $_GET['u_id'] != '') {
                                                    ?>  
                                                    <div class="form-group" id="city_list_new">
                                                        <label class="control-label">CITY NAME<span class="required">*</span></label>
                                                        <div class="" id="city_new">
                                                            <?php //echo $cityEditData; ?>
                                                        </div>
                                                    </div>
                                                <?php //} else {
                                                    ?>
                                                    <div class="form-group" id="city_list_new">
                                                        <label class="control-label">CITY NAME<span class="required">*</span></label>
                                                        <select class="form-control input-xlarge">
                                                            <option>Select city</option>
                                                        </select>
                                                    </div>
                                                <?php //} ?>
                                                
                                                <div class="form-group hide" id="city_list">
                                                    <label class="control-label">CITY NAME<span class="required">*</span></label>
                                                    <div class="" id="city">

                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <label class="control-label">PHONE NUMBER<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php //echo $u_phone; ?>" onkeypress="validate(event)" name="u_phone" id="u_phone" />
                                                </div>-->
                                                <!--                                                <div class="form-group">
                                                                                                    <label class="control-label">User Image</label>
                                                                                                    <input type="file" class="form-control input-xlarge" value="<?php //echo $u_photo; ?>" name="user_photo"/>
                                                                                                    <img src="uploads/userprofile/<?php //echo $u_photo ?>" width="150" height="150">
                                                                                                </div>-->
                                                <div class="form-group">
                                                    <label for="comment">Address</label>
                                                    <textarea class="form-control input-xlarge" rows="3" id="comment" name="u_address"><?php echo $u_address; ?></textarea>
                                                </div>
<!--                                                <div class="form-group">
                                                    <label class="control-label">POST CODE<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php //echo $u_postcode; ?>" name="u_postcode"/>
                                                </div>-->
<!--                                                <div class="form-group">
                                                    <label class="control-label">TOWN/CITY<span class="required">*</span></label>
                                                    <input required="true" type="text" class="form-control input-xlarge" value="<?php echo $u_city; ?>" name="u_city"/>
                                                </div>-->
<!--                                                <div class="form-group">
                                                    <label class="control-label">User Image</label>
                                                    <input type="file" class="form-control input-xlarge" value="<?php echo $u_img; ?>" name="u_img"/>
                                                    <?php
                                                    //if (isset($userData['u_id']) && $userData['u_id'] != '') {
                                                      //  if (isset($u_img) && $u_img != '') {
                                                            ?>
                                                            <img src="uploads/serviceprofile/<?php //echo $u_img ?>" width="150" height="150"> <?php //} else {
                                                            ?>
                                                            <img src="uploads/serviceprofile/no-image.png"width="150" height="150">
                                                            <?php
                                                        //}
                                                    //}
                                                    ?>
                                                </div>-->
                                                <?php // if (isset($_GET['u_id']) && $_GET['u_id'] != '') {   ?>
                                                <!--                                                    <div class="form-group">
                                                                                                        <label class="control-label">USER TYPE</label>
                                                                                                        <label class="radio-inline">
                                                                                                            <input type="radio" name="u_type" value="2"<?php echo ($u_type == '2') ? 'checked' : '' ?> >USER
                                                                                                        </label>
                                                                                                        <label class="radio-inline">
                                                                                                            <input type="radio" name="u_type" value="1"<?php echo ($u_type == '1') ? 'checked' : '' ?> >ADMIN
                                                                                                        </label>
                                                                                                    </div> -->
                                                <?php // }   ?>
                                                <input type="hidden" name="u_id" value="<?php if (isset($_GET['u_id'])) echo $_GET['u_id']; ?>">
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
                        <!-- END PROFILE CONTENT -->
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>

</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
                                                        $(document).ready(function () {
                                                            var dateToday = new Date();
                                                            $("#u_dob").datepicker({
                                                                changeMonth: true,
                                                                changeYear: true,
                                                                yearRange: "-116:+100",
                                                                dateFormat: 'dd-mm-yy'
                                                            });
                                                        });

                                                        function validate(evt) {
                                                            var theEvent = evt || window.event;
                                                            var key = theEvent.keyCode || theEvent.which;
                                                            key = String.fromCharCode(key);
                                                            var regex = /[0-9]|\./;
                                                            if (!regex.test(key)) {
                                                                theEvent.returnValue = false;
                                                                if (theEvent.preventDefault)
                                                                    theEvent.preventDefault();
                                                            }
                                                            var getphoneno = $('#u_phone').val();
                                                            var result = getphoneno.indexOf('0');
                                                            console.log(result);
                                                            if (result == 0) {
                                                                alert('You can not enter first digit as 0 please enter your number without zero');
                                                            }
                                                        }
                                                        function getCityname(obj) {
                                                            var cityID = obj.value;
                                                            if (cityID != null) {
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: 'controller.php?action=getCityname&city_id=' + cityID,
                                                                    success: function (response) {
                                                                        var data = $.parseJSON(response);
//                                                                        consol.log(data);
                                                                        $('#city').html(data);
                                                                        $('#city_list').removeClass('hide');
                                                                        $('#city_list_new').addClass('hide');
                                                                    }
                                                                });
                                                            }
                                                        }
</script>
<!-- END PAGE -->
</div>
<!-- END CONTAINER -->

<?php
include 'layout/footer.php';
?>

