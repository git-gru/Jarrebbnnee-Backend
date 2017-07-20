<?php

session_start();
include 'common/common.php';
date_default_timezone_set("Asia/Calcutta");
define("TODAY", date('Y-m-d'));
define("TODAY_TIME_DATE", date('Y-m-d H:i:s'));



$_SESSION['page'] = 0;

function setTemplate($file) {
    $headers = '';
    $headers = file_get_contents('template/eheader.php');
    $headers.= file_get_contents('template/' . $file);
    $headers.= file_get_contents('template/efooter.php');
    return $headers;
}

function sendMailCore($email, $subject, $message, $headers) {
    $sendMail = mail($email, $subject, $message, $headers);
    if ($sendMail) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function loginAdmin($data) {
    if (isset($data['username']) && $data['username'] != '') {
        $username = $data['username'];
    } else {
        return ['status' => 1, 'message' => 'Please enter email'];
    }

    if (isset($data['password']) && $data['password'] != '') {
        $password = $data['password'];
    } else {
        return ['status' => 1, 'message' => 'Please enter password'];
    }

    $commonObj = new General();
    $params = [
        'email' => $username
    ];
    $userString = $commonObj->queryOne('admin', $params);
    $row = $userString;
    if (empty($row)) {
        return ['status' => 1, 'message' => 'Enter email is not registered'];
    } else {
        if ($row['password'] == md5($data['password'])) {
            if (session_id() == '') {
                session_start();
            }
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            return ['status' => 0, 'message' => 'Login success'];
        } else {
            return ['status' => 1, 'message' => 'Please enter valid password'];
        }
    }
}

function updateAdminProfile($data) {

    if (isset($data['admin_username']) && $data['admin_username'] != '') {
        $admin_username = $data['admin_username'];
    } else {
        return ['status' => 1, 'message' => 'Admin name is required'];
    }

    $admin_id = $data['admin_id'];
    $updateString = "UPDATE users SET u_user_name = '$admin_username' WHERE u_id = $admin_id";
    mysql_query($updateString);

    $_SESSION['admin_name'] = $admin_username;

    return ['status' => 0, 'message' => 'Profile Updated Success'];
}

function validatePassword($password) {

    $password = md5($password);

    $selectUser = "SELECT * FROM users WHERE u_password = '$password'";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    if (!empty($row)) {

        return TRUE;
    } else {

        return FALSE;
    }
}

function changePasswordAdmin($data) {

    if (isset($data['new_password']) && $data['new_password'] != '') {

        $new_password = md5($data['new_password']);
    } else {

        return ['status' => 1, 'message' => 'Please Enter New Password'];
    }

    if (isset($data['old_password']) && $data['old_password'] != '') {

        $validate = validatePassword($data['old_password']);
    } else {

        return ['status' => 1, 'message' => 'Please Enter Old Password'];
    }



    if ($validate) {

        $admin_id = $data['admin_id'];

        $updateString = "UPDATE users SET u_password = '$new_password' WHERE u_id = $admin_id";

        mysql_query($updateString);

        return ['status' => 0, 'message' => 'Password Change Success'];
    } else {

        return ['status' => 1, 'message' => 'Please Enter correct Old Password'];
    }
}

function checkUserEmail($email) {

    $selectString = "SELECT * FROM user WHERE u_email = '$email'";

    $query = mysql_query($selectString);

    $row = mysql_fetch_assoc($query);

    if (!empty($row)) {

        return FALSE;
    } else {

        return TRUE;
    }
}

function checkServiceProviderEmail($email) {

    $selectString = "SELECT * FROM service_provider WHERE sp_email = '$email'";

    $query = mysql_query($selectString);

    $row = mysql_fetch_assoc($query);

    if (!empty($row)) {

        return FALSE;
    } else {

        return TRUE;
    }
}

function getByAdminId($id) {

    $selectAdmin = "SELECT * FROM users WHERE u_id = $id AND u_type ='1'";

    $query = mysql_query($selectAdmin);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByAdvertismentId($id) {

    $selectUser = "SELECT * FROM advertise WHERE advt_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByUserId($id) {

    $selectUser = "SELECT * FROM user WHERE u_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByPlanId($id) {

    $selectUser = "SELECT * FROM plans WHERE p_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByServiceProviderId($id) {

    $selectUser = "SELECT * FROM service_provider WHERE sp_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByCategoryId($id) {

    $selectUser = "SELECT * FROM product_category WHERE pc_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getBySliderId($id) {

    $selectUser = "SELECT * FROM slider WHERE slider_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getBySliderImageId($id) {

    $selectUser = "SELECT * FROM slider_img_relation WHERE sir_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getByServiceId($id) {

    $selectUser = "SELECT * FROM service WHERE s_id = $id";

    $query = mysql_query($selectUser);

    $row = mysql_fetch_assoc($query);

    return $row;
}

function getAllcategory() {

    $selectUser = "SELECT * FROM category";

    $query = mysql_query($selectUser);
    $categoryData = [];
    $i = 0;
    while ($row = mysql_fetch_assoc($query)) {
        $categoryData[$i] = $row;
        $i++;
    }

    return $row;
}

function addAdvertisment($data) {
//p($data);
    if (isset($data['advt_id']) && $data['advt_id'] != '') {
        $userData = getByAdvertismentId($data['advt_id']);
    }

    $commonObj = new General();
    $insert_data = [];
    if (isset($data['advt_name']) && !empty($data['advt_name'])) {
        $insert_data['advt_title'] = $data['advt_name'];
    } else {
        return ['status' => 1, 'message' => 'Advertise Name Required'];
    }
    if (isset($data['advt_description']) && !empty($data['advt_description'])) {
        $insert_data['advt_description'] = $data['advt_description'];
    } else {
        return ['status' => 1, 'message' => ' Advertise Description Required'];
    }
    if (isset($data['advt_link']) && !empty($data['advt_link'])) {
        $insert_data['advt_link'] = $data['advt_link'];
    } else {
        return ['status' => 1, 'message' => 'Advertise Link Required'];
    }
    if (isset($data['c_title']) && !empty($data['c_title'])) {
        $insert_data['advt_category_id'] = $data['c_title'];
    } else {
        return ['status' => 1, 'message' => 'Category Name Required'];
    }
    if (isset($data['s_title']) && !empty($data['s_title'])) {
        $insert_data['advt_service_id'] = $data['s_title'];
    }
    if (isset($data['u_id']) && !empty($data['u_id'])) {
        $insert_data['advt_user_id'] = $data['u_id'];
    } else {
        return ['status' => 1, 'message' => 'User Name Required'];
    }

    if (isset($_FILES['u_img']['name']) && $_FILES['u_img']['name'] != '') {
        // remove old image from folder befor editing images 
        if (isset($data['advt_id']) && !empty($data['advt_id'])) {
            $params = [
                'advt_id' => $data['advt_id']
            ];

            $getImage = $commonObj->queryOne('advertise', $params);
            $oldImage = $getImage['u_img'];
            $imagePath = "uploads/serviceprofile/" . $oldImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
// upload image
        $file_type = $_FILES['u_img']['type'];

        $pieces = explode("/", $file_type);

        $data['u_img'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];

        $targetfolder = "uploads/serviceprofile/";

        $targetfolder = $targetfolder . basename($data['u_img']);


        if (@move_uploaded_file($_FILES['u_img']['tmp_name'], $targetfolder)) {

            basename($_FILES['u_img']['name']) . " is uploaded";
        }
    }
    if (isset($data['u_img']) && !empty($data['u_img'])) {

        $insert_data['advt_image'] = $data['u_img'];
    }
    if (isset($_GET['advt_id']) && !empty($_GET['advt_id'])) {

        $params = [

            'advt_id' => $_GET['advt_id']
        ];
//        $insert_data['u_status'] = 0;
//        $insert_data['u_type'] = 2;

        $modified = date('Y-m-d H:i:s');

        $insert_data['advt_modified'] = $modified;

        $result = $commonObj->update('advertise', $insert_data, $params);

        return ['status' => 0, 'message' => 'Advertise updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['advt_created'] = $created;
        $insert_data['advt_status'] = 0;
//        $insert_data['u_type'] = 2;
//p($insert_data);
        $result = $commonObj->insert('advertise', $insert_data);

        return ['status' => 0, 'message' => 'Advertise Inserted successfully'];
    }
}

function getLatLong($address) {
//    p("jdfhjsdhfjsgfjhgf");
    if (!empty($address)) {
        //Formatted address
        $formattedAddr = str_replace(' ', '+', $address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false');
        // $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key=e824414fd9fa15590b44e810474779f7c5b81126d78d277db243380deeec0004');
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        $data['latitude'] = $output->results[0]->geometry->location->lat;
        $data['longitude'] = $output->results[0]->geometry->location->lng;
        //Return latitude and longitude of the given address
        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function addUser($data) {
//p($data);
    $userData['u_email'] = '';

    if (isset($data['u_id']) && $data['u_id'] != '') {
        $userData = getByUserId($data['u_id']);
    }

    $commonObj = new General();
    $insert_data = [];
//    $latLong = getLatLong($data['u_address']);
//    $latitude = $latLong['latitude']?$latLong['latitude']:'Not found';
//    $longitude = $latLong['longitude']?$latLong['longitude']:'Not found';
    if (isset($data['u_first_name']) && !empty($data['u_first_name'])) {
        $insert_data['u_first_name'] = $data['u_first_name'];
    } else {
        return ['status' => 1, 'message' => 'User First Name Required'];
    }
    if (isset($data['u_last_name']) && !empty($data['u_last_name'])) {
        $insert_data['u_last_name'] = $data['u_last_name'];
    } else {
        return ['status' => 1, 'message' => 'User Last Name Required'];
    }
    if (isset($data['u_address']) && !empty($data['u_address'])) {
        $insert_data['u_address'] = $data['u_address'];
    } else {
        return ['status' => 1, 'message' => 'User Address Required'];
    }
//    if (isset($data['u_phone']) && !empty($data['u_phone'])) {
//        $insert_data['u_phone'] = $data['u_phone'];
//    } else {
//        return ['status' => 1, 'message' => 'User Phone number Required'];
//    }
//    if (isset($data['u_postcode']) && !empty($data['u_postcode'])) {
//        $insert_data['u_postcode'] = $data['u_postcode'];
//    } else {
//        return ['status' => 1, 'message' => 'User Post Code Required'];
//    }
//    if (isset($data['city_name']) && !empty($data['city_name'])) {
//        $insert_data['u_city'] = $data['city_name'];
//    } else {
//        return ['status' => 1, 'message' => 'User City Required'];
//    }
      if (isset($data['phone_prefix']) && !empty($data['phone_prefix'])) {
          $insert_data['u_country'] = $data['phone_prefix'];
      } else {
          return ['status' => 1, 'message' => 'User Country Required'];
      }
//    if (isset($data['u_gender']) && !empty($data['u_gender'])) {
//        $insert_data['u_gender'] = $data['u_gender'];
//    } else {
//        return ['status' => 1, 'message' => 'User Gender Required'];
//    }
//    $insert_data['u_latitute']=$latitude;
//    $insert_data['u_longitute']=$longitude;

    if (isset($data['u_type']) && !empty($data['u_type'])) {
        $insert_data['u_type'] = $data['u_type'];
    } else {
        return ['status' => 1, 'message' => 'User Type Required'];
    }

    if (isset($data['u_email']) && !empty($data['u_email'])) {

        if (checkUserEmail($data['u_email']) || $userData['u_email'] == $data['u_email']) {

            $insert_data['u_email'] = $data['u_email'];
        } else {

            return ['status' => 1, 'message' => 'Email Address already exists'];
        }
    } else {

        return ['status' => 1, 'message' => 'Email Address Required'];
    }
    if (isset($data['u_password']) && !empty($data['u_password'])) {

        $insert_data['u_password'] = md5($data['u_password']);
    }

//    if (isset($_FILES['u_img']['name']) && $_FILES['u_img']['name'] != '') {
//        // remove old image from folder befor editing images 
//        if (isset($data['u_id']) && !empty($data['u_id'])) {
//            $params = [
//                'u_id' => $data['u_id']
//            ];
//
//            $getImage = $commonObj->queryOne('users', $params);
//            $oldImage = $getImage['u_img'];
//            $imagePath = "uploads/serviceprofile/" . $oldImage;
//            if (file_exists($imagePath)) {
//                unlink($imagePath);
//            }
//        }
//// upload image
//        $file_type = $_FILES['u_img']['type'];
//
//        $pieces = explode("/", $file_type);
//
//        $data['u_img'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];
//
//        $targetfolder = "uploads/serviceprofile/";
//
//        $targetfolder = $targetfolder . basename($data['u_img']);
//
//
//        if (@move_uploaded_file($_FILES['u_img']['tmp_name'], $targetfolder)) {
//
//            basename($_FILES['u_img']['name']) . " is uploaded";
//        }
//    }
//    if (isset($data['u_img']) && !empty($data['u_img'])) {
//
//        $insert_data['u_img'] = $data['u_img'];
//    }
//    if (isset($data['u_id']) && !empty($data['u_id'])) {
//        $params = [
//            'u_id' => $data['u_id']
//        ];
//        $getImage = $commonObj->queryOne('users', $params);
//    }


    if (isset($_GET['u_id']) && !empty($_GET['u_id'])) {

        $params = [

            'u_id' => $_GET['u_id']
        ];
        $insert_data['u_status'] = 0;
//        $insert_data['u_type'] = 2;

        $modified = date('Y-m-d H:i:s');

        $insert_data['u_modified'] = $modified;

        $result = $commonObj->update('user', $insert_data, $params);

        return ['status' => 0, 'message' => 'User updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['u_crteated'] = $created;
        $insert_data['u_status'] = 0;
//        $insert_data['u_type'] = 2;   

        $result = $commonObj->insert('user', $insert_data);

        return ['status' => 0, 'message' => 'User Inserted successfully'];
    }
}

function addPlans($data) {
//p($data);
//    $userData['u_email'] = '';

    if (isset($data['p_id']) && $data['p_id'] != '') {
        $userData = getByUserId($data['p_id']);
    }

    $commonObj = new General();
    $insert_data = [];
    if (isset($data['p_name']) && !empty($data['p_name'])) {
        $insert_data['p_title'] = $data['p_name'];
    } else {
        return ['status' => 1, 'message' => 'Plan Name Required'];
    }
    if (isset($data['p_type']) && !empty($data['p_type'])) {
        $insert_data['p_type'] = $data['p_type'];
    } else {
        return ['status' => 1, 'message' => 'Plan Type Required'];
    }
    if (isset($data['p_amount']) && !empty($data['p_amount'])) {
        $insert_data['p_amount'] = $data['p_amount'];
    } else {
        return ['status' => 1, 'message' => 'Plan Amount Required'];
    }

    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {

        $params = [

            'p_id' => $_GET['p_id']
        ];
//        $insert_data['u_type'] = 2;

        $modified = date('Y-m-d H:i:s');

        $insert_data['p_modified'] = $modified;

        $result = $commonObj->update('plans', $insert_data, $params);

        return ['status' => 0, 'message' => 'Plan updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['p_created'] = $created;
        $insert_data['p_status'] = 0;
        $insert_data['p_total_subscriber'] = 0;
        $result = $commonObj->insert('plans', $insert_data);

        return ['status' => 0, 'message' => 'Plan Inserted successfully'];
    }
}

function addservice_provider($data) {

    $userData['sp_email'] = '';

    if (isset($data['sp_id']) && $data['sp_id'] != '') {
        $userData = getByServiceProviderId($data['sp_id']);
    }

    $commonObj = new General();
    $insert_data = [];
    if (isset($data['sp_name']) && !empty($data['sp_name'])) {
        $insert_data['sp_name'] = $data['sp_name'];
    } else {
        return ['status' => 1, 'message' => 'Srvice Provider Name Required'];
    }

    if (isset($data['sp_address']) && !empty($data['sp_address'])) {
        $insert_data['sp_address'] = $data['sp_address'];
    } else {
        return ['status' => 1, 'message' => 'Service Provider Address Required'];
    }
    if (isset($data['sp_phone']) && !empty($data['sp_phone'])) {
        $insert_data['sp_phone'] = $data['sp_phone'];
    } else {
        return ['status' => 1, 'message' => 'Service Provider Phone number Required'];
    }
    if (isset($data['sp_city']) && !empty($data['sp_city'])) {
        $insert_data['sp_city'] = $data['sp_city'];
    } else {
        return ['status' => 1, 'message' => 'Service Provider City Required'];
    }
    if (isset($data['phone_prefix']) && !empty($data['phone_prefix'])) {
        $insert_data['sp_country'] = $data['phone_prefix'];
    } else {
        return ['status' => 1, 'message' => 'User Country Required'];
    }
    if (isset($data['s_id']) && !empty($data['s_id'])) {
        $insert_data['sp_type'] = $data['s_id'];
    } else {
        return ['status' => 1, 'message' => 'Service Name Required'];
    }

    if (isset($data['sp_email']) && !empty($data['sp_email'])) {

        if (checkServiceProviderEmail($data['sp_email']) || $userData['sp_email'] == $data['sp_email']) {

            $insert_data['sp_email'] = $data['sp_email'];
        } else {

            return ['status' => 1, 'message' => 'Email Address already exists'];
        }
    } else {

        return ['status' => 1, 'message' => 'Email Address Required'];
    }
    if (isset($data['sp_id']) && !empty($data['sp_id'])) {
        $params = [
            'sp_id' => $data['sp_id']
        ];
    }
    if (isset($_GET['sp_id']) && !empty($_GET['sp_id'])) {

        $params = [

            'sp_id' => $_GET['sp_id']
        ];
        $insert_data['sp_status'] = 0;
        $modified = date('Y-m-d H:i:s');

        $insert_data['sp_modified'] = $modified;

        $result = $commonObj->update('service_provider', $insert_data, $params);

        return ['status' => 0, 'message' => 'Service Provider updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['sp_create'] = $created;
        $result = $commonObj->insert('service_provider', $insert_data);

        return ['status' => 0, 'message' => 'Service Privider Inserted successfully'];
    }
}

function addService($data) {
//    p($data);
    $commonObj = new General();
    $insert_data = [];

    if (isset($data['s_name']) && !empty($data['s_name'])) {
        $insert_data['s_title'] = $data['s_name'];
    } else {
        return ['status' => 1, 'message' => 'Service Name Required'];
    }

    if (isset($data['s_desc']) && !empty($data['s_desc'])) {
        $insert_data['s_description'] = $data['s_desc'];
    } else {
        return ['status' => 1, 'message' => 'Srvice Description Required'];
    }

    if (isset($data['c_title']) && !empty($data['c_title'])) {
        $insert_data['s_category_id'] = $data['c_title'];
    } else {
        return ['status' => 1, 'message' => 'Category Name Required'];
    }

    if (isset($data['city_name']) && !empty($data['city_name'])) {
        $insert_data['s_city_id'] = $data['city_name'];
    } else {
        return ['status' => 1, 'message' => 'User City Required'];
    }
    if (isset($data['phone_prefix']) && !empty($data['phone_prefix'])) {
        $insert_data['s_country_id'] = $data['phone_prefix'];
    } else {
        return ['status' => 1, 'message' => 'User Country Required'];
    }

    if (isset($_FILES['s_img']['name']) && $_FILES['s_img']['name'] != '') {
        // remove old image from folder befor editing images 
        if (isset($data['s_id']) && !empty($data['s_id'])) {
            $params = [
                's_id' => $data['s_id']
            ];

            $getImage = $commonObj->queryOne('service', $params);
            $oldImage = $getImage['s_img'];
            $imagePath = "uploads/serviceprofile/" . $oldImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
// upload image
        $file_type = $_FILES['s_img']['type'];

        $pieces = explode("/", $file_type);

        $data['s_img'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];

        $targetfolder = "uploads/serviceprofile/";

        $targetfolder = $targetfolder . basename($data['s_img']);

        if (@move_uploaded_file($_FILES['s_img']['tmp_name'], $targetfolder)) {

            basename($_FILES['s_img']['name']) . " is uploaded";
        }
    }
    if (isset($data['s_img']) && !empty($data['s_img'])) {

        $insert_data['s_image'] = $data['s_img'];
    }


    if (isset($_GET['s_id']) && !empty($_GET['s_id'])) {
        $params = [
            's_id' => $_GET['s_id']
        ];
//        $insert_data['s_status'] = 0;
        $modified = date('Y-m-d H:i:s');

        $insert_data['s_modified'] = $modified;

        $result = $commonObj->update('service', $insert_data, $params);

        return ['status' => 0, 'message' => 'Service updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['s_created'] = $created;
//        $insert_data['s_status'] = 0;
        $result = $commonObj->insert('service', $insert_data);
        return ['status' => 0, 'message' => 'Service Inserted successfully'];
    }
}

function addCategories($data) {
//    p($_FILE['c_images']);
    $commonObj = new General();
    $insert_data = [];
    if (isset($data['cate_name']) && $data['cate_name'] == 0) {
        $insert_data['pc_name'] = $data['cate_name'];
    } else {
        return ['status' => 1, 'message' => 'Categories Name Required'];
    }

    if (isset($_GET['pc_id']) && !empty($_GET['pc_id'])) {
        $params = [
            'pc_id' => $_GET['pc_id']
        ];
        $modified = date('Y-m-d H:i:s');
        $insert_data['pc_modifide'] = $modified;
        $result = $commonObj->update('product_category', $insert_data, $params);
        return ['status' => 0, 'message' => 'Category updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');

        $insert_data['pc_created'] = $created;
        $insert_data['pc_status'] = 0;
        $result = $commonObj->insert('product_category', $insert_data);
        return ['status' => 0, 'message' => 'Category Inserted successfully'];
    }
}

function addSlider($data) {
//    p($_GET);
    $commonObj = new General();
    $insert_data = [];
    if (isset($data['slider_name']) && $data['slider_name'] == 0) {
        $insert_data['slider_name'] = $data['slider_name'];
    } else {
        return ['status' => 1, 'message' => 'Slider Name Required'];
    }

    if (isset($data['slider_id']) && !empty($data['slider_id'])) {
        $params = [
            'slider_id' => $data['slider_id']
        ];
        $modified = date('Y-m-d H:i:s');
        $insert_data['slider_modifide'] = $modified;
        $result = $commonObj->update('slider', $insert_data, $params);
        return ['status' => 0, 'message' => 'Slider updated successfully'];
    } else {

        $created = date('Y-m-d H:i:s');
        $insert_data['slider_created'] = $created;

        $result = $commonObj->insert('slider', $insert_data);
        return ['status' => 0, 'message' => 'Slider Inserted successfully'];
    }
}

function addSliderImage($data) {
     $commonObj = new General();
    $insert_data = [];
    if (isset($data['s_id']) && $data['s_id'] != '') {
        $SliderId = $data['s_id'];
        
        if (isset($data['slider_mtitle']) && $data['slider_mtitle'] == 0) {
            $insert_data['sir_heading_text'] = $data['slider_mtitle'];
        } else {
            return ['status' => 1, 'message' => 'Slider Main Name Required'];
        }
        
        if (isset($data['slider_stitle']) && $data['slider_stitle'] == 0) {
            $insert_data['sir_sub_heading_text'] = $data['slider_stitle'];
        } else {
            return ['status' => 1, 'message' => 'Slider Sub Heading Required'];
        }

        if (isset($_FILES['slider_images']['name']) && $_FILES['slider_images']['name'] != '') {
            if (isset($data['sir_id']) && !empty($data['sir_id'])) {
                $params = [
                    'sir_id' => $data['sir_id']
                ];
                $getImage = $commonObj->queryOne('slider_img_relation', $params);
                $oldImage = $getImage['sir_img'];
                $imagePath = "uploads/slider/" . $oldImage;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $file_type = $_FILES['slider_images']['type'];
            $pieces = explode("/", $file_type);
            $data['slider_images'] = time() . strtotime(date('Y-m-d')) . rand(0, 9) . '.' . $pieces['1'];
            $targetfolder = "uploads/slider/";
            $targetfolder = $targetfolder . basename($data['slider_images']);
            if (@move_uploaded_file($_FILES['slider_images']['tmp_name'], $targetfolder)) {
                basename($_FILES['slider_images']['name']) . " is uploaded";
            }
        }

        if (isset($data['slider_images']) && !empty($data['slider_images'])) {
            $insert_data['sir_img'] = $data['slider_images'];
        } else {
            if (!isset($data['sir_id'])) {
                return ['status' => 1, 'message' => 'slider image required'];
            }
        }
        if (isset($data['sir_id']) && !empty($data['sir_id'])) {
            $params = [
                'sir_id' => $data['sir_id']
            ];
            $modified = date('Y-m-d H:i:s');
            $insert_data['sir_modified'] = $modified;
            $result = $commonObj->update('slider_img_relation', $insert_data, $params);
            return ['status' => 0, 'message' => 'Slider Image updated successfully'];
        } else {
            $created = date('Y-m-d H:i:s');
            $insert_data['sir_created'] = $created;
            $insert_data['sir_slider_id'] = $SliderId;
            $result = $commonObj->insert('slider_img_relation', $insert_data);
            return ['status' => 0, 'message' => 'Slider Image Inserted successfully'];
        }
    } else {
        return ['status' => 1, 'message' => 'No Slider is selected'];
    }
}

if (!function_exists('userDropdown')) {

    function userDropdown($selected = '') {
        $vendor = [];
        $select = "SELECT * FROM users";
        $query = mysql_query($select);
        $i = 0;
        while ($row = mysql_fetch_assoc($query)) {
            $vendor[$i] = $row;
            $i++;
        }
        $htmlString = '<option value="0">Select Service</option>';
        foreach ($vendor as $row) {
            if ($selected == $row['u_id']) {
                $select = 'selected';
                $htmlString.="<option " . $select . " value='" . $row['u_id'] . "'>" . $row['u_first_name'] . " " . $row['u_last_name'] . "</option>";
            } else {
                $htmlString.="<option value='" . $row['u_id'] . "'>" . $row['u_first_name'] . " " . $row['u_last_name'] . "</option>";
            }
        }
        $dropDown = "<select name='u_id' id='u_id' class='form-control input-xlarge'>" . $htmlString . "</select>";
        return $dropDown;
    }

}
function saveProductImport() {
//    p($_FILES);
    if (isset($_FILES['product_file']['tmp_name']) && $_FILES['product_file']['tmp_name'] != '') {
        $time = time();
        $productfile = rand(1111111, 99999999999999) . time() . ".csv";
        move_uploaded_file($_FILES['product_file']['tmp_name'], "csv/" . $productfile);
        $row = 1;
        $prod_arr = array();
        if (($handle = fopen("csv/" . $productfile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($row == '1') {
                    
                } else {
                    $product_ref_id = mysql_real_escape_string($data[0]);
                    $product_name = mysql_real_escape_string($data[1]);
                    $category = mysql_real_escape_string($data[2]);
                    $price = mysql_real_escape_string($data[4]);
                    $weight = mysql_real_escape_string($data[5]);
                    $product_description = mysql_real_escape_string($data[6]);
                    $product_warehouse = mysql_real_escape_string($data[8]);
                    $product_option = mysql_real_escape_string($data[9]);
                    $selectProduct = "SELECT * FROM product_details WHERE pd_refer_id = '$product_ref_id'";
                    $productQuery = mysql_query($selectProduct);
                    $productData = mysql_fetch_assoc($productQuery);
                    if (empty($productData)) {
                        $pd_created = date('Y-m-d H:i:s');
                        $insertProduct = "INSERT INTO product_details(pd_refer_id,pd_name,pd_price,pd_description,pd_option,pd_warehouse,pd_weight,pd_created) VALUES('$product_ref_id','$product_name','$price','$product_description','$product_option','$product_warehouse','$weight','$pd_created')";
                        mysql_query($insertProduct);
                    }
                }
                $row++;
            }
        }
        fclose($handle);
    } else {
        return ['status' => '1', 'message' => 'Please select csv of product details'];
    }
}

function saveProductImportImg() {
    if (isset($_FILES['product_images']['tmp_name']) && $_FILES['product_images']['tmp_name'] != '') {
        $time = time();
        $productfile = rand(1111111, 99999999999999) . time() . ".csv";
        move_uploaded_file($_FILES['product_images']['tmp_name'], "csv/" . $productfile);
        $row = 1;
        $prod_arr = array();
        if (($handle = fopen("csv/" . $productfile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($row == '1') {
                    
                } else {
                    $pir_product_id = mysql_real_escape_string($data[0]);
                    $imgurl = mysql_real_escape_string($data[1]);
//                $imgurl = 'http://www.foodtest.ru/images/big_img/sausage_3.jpg';
                    $imagename = basename($imgurl);
                    $image = getImg($imgurl);
                    file_put_contents('uploads/products/' . $imagename, $image);
                    if (empty($productData)) {
                        $pir_created = date('Y-m-d H:i:s');
                        $insertProduct = "INSERT INTO product_img_rel(pir_product_id,pir_image,pir_created) VALUES('$pir_product_id','$imagename','$pir_created')";
                        mysql_query($insertProduct);
                    }
                }
                $row++;
            }
        }
        fclose($handle);
        return ['status' => '0', 'message' => 'Images imported Success'];
    } else {
        return ['status' => '1', 'message' => 'Please select csv of images'];
    }
}

/* We will use at product image import by amrut START [ 1:29 - 16-03-2017 ] */

function getImg($url) {
    $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
    $headers[] = 'Connection: Keep-Alive';
    $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
    $user_agent = 'php';
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_USERAGENT, $user_agent); //check here         
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $return = curl_exec($process);
    curl_close($process);
    return $return;
}

/* We will use at product image import by amrut END  */