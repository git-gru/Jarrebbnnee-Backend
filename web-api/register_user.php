<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/register_user.php?username=rajesh&email=rajesh1@gmail.com&password=111111&city=ahm&address=ahmedabad&country=+91&zip=382721&phone=9033311201 */

if ( isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['city']) && isset($_REQUEST['address'])&& isset($_REQUEST['country'])&& isset($_REQUEST['zip'])&& isset($_REQUEST['phone']) ) {
    $name = $_REQUEST['username']; 
    $email = $_REQUEST['email'];
    $password1 = $_REQUEST['password'];
    $password = md5($_REQUEST['password']);
    $address = $_REQUEST['address'];
    $country = $_REQUEST['country'];
    $zip = $_REQUEST['zip'];
    $phone = $_REQUEST['phone'];
    $city = $_REQUEST['city'];
//			$gender = ($_REQUEST['image']);
    $status = "0";
    $type_id = "2";
    $created = date('Y-m-d H:i:s');

    $qry_select = "select * from users where u_email ='" . $email . "'";
    if ($qry_select) {
        $res_select = mysql_query($qry_select);
        if (mysql_num_rows($res_select) > 0) {
            $row = mysql_fetch_array($res_select);
            //print_r($row);
            $msg['userid'] = $row['u_id'];
            $msg['message'] = "Already registred with this email";
        } else {
        
          $query = "INSERT INTO `users`(`u_user_name`, `u_email`, `u_password`, `u_address`, `u_city`, `u_phone`, `u_postcode`, `u_country`, `u_status`, `u_type`, `u_created`)
                    VALUES ('$name','$email','$password','$address','$city','$phone','$zip','$country','$status', '$type_id','$created')";
          
            $result = mysql_query($query);
            if ($result) {
                $id = mysql_insert_id();
                    $msg['status'] = "1";
                    $msg['userid'] = $id;
                    $msg['message'] = "User Successfully Register";
            } else {
                $msg['userid'] = "0";
                $msg['message'] = mysql_error();
            }
        }
    } else {
        $msg['userid'] = "0";
        $msg['message'] = mysql_error();
    }
} else {
    $msg['message'] = "wrong or missing parameters!";
}

$ar['status'] = $msg;
print json_encode($ar);
?>