<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/add_service_provider.php?sp_name=rajesh&s_name=hardware&email=rajesh1@gmail.com&city=ahm&address=ahmedabad&country=+91&phone=9033311201 */

if ( isset($_REQUEST['sp_name']) && isset($_REQUEST['email']) && isset($_REQUEST['city']) && isset($_REQUEST['address'])&& isset($_REQUEST['country'])&& isset($_REQUEST['phone']) && isset($_REQUEST['s_name']) ) {
    $sp_name = $_REQUEST['sp_name']; 
    $email = $_REQUEST['email'];
    $address = $_REQUEST['address'];
    $country = $_REQUEST['country'];
    $s_name = $_REQUEST['s_name'];
    $phone = $_REQUEST['phone'];
    $city = $_REQUEST['city'];
//			$gender = ($_REQUEST['image']);
    $status = "0";
    $created = date('Y-m-d H:i:s');

    $qry_select = "select * from service_provider where sp_email ='" . $email . "'";
    if ($qry_select) {
        $res_select = mysql_query($qry_select);
        if (mysql_num_rows($res_select) > 0) {
            $row = mysql_fetch_array($res_select);
            //print_r($row);
            $msg['userid'] = $row['sp_id'];
            $msg['message'] = "Already registred with this email";
        } else {
        
          $query = "INSERT INTO `service_provider`(`sp_name`, `sp_email`, `sp_type`, `sp_address`, `sp_city`, `sp_phone`, `sp_country`, `sp_status`, `sp_create`)
                    VALUES ('$sp_name','$email','$s_name','$address','$city','$phone','$country','$status','$created')";
          
            $result = mysql_query($query);
            if ($result) {
                $id = mysql_insert_id();
                    $msg['status'] = "1";
                    $msg['userid'] = $id;
                    $msg['message'] = "Service Provider Successfully Register";
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