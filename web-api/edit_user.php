<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/edit_user.php?id=20&username=ppp&email=sanit03@gmail.com&city=ahm&address=kalol&country=+91&zip=382721&phone=9033311201 */

if (isset($_REQUEST['username']) && isset($_REQUEST['id'])  && isset($_REQUEST['city']) && isset($_REQUEST['address'])&& isset($_REQUEST['country'])&& isset($_REQUEST['zip'])&& isset($_REQUEST['phone']) ) {
    $name = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $id = $_REQUEST['id'];
    
    $modified = date('Y-m-d H:i:s');
    $address = $_REQUEST['address'];
    $country = $_REQUEST['country'];
    $zip = $_REQUEST['zip'];
    $phone = $_REQUEST['phone'];
    $city = $_REQUEST['city'];
   
	
    //dfdfd
    $select = "SELECT * FROM users WHERE u_id = '$id'";
    $query = mysql_query($select);
    $row = mysql_fetch_assoc($query);
    if (!empty($row)) {
        $selectEmail = "SELECT * FROM users WHERE u_email = '$email'";
        $queryEmail = mysql_query($selectEmail);
        $rowEmail = mysql_fetch_assoc($queryEmail);
        if (!empty($rowEmail)) {
            $msg['status'] = "0";
            $msg['message'] = "Email Already Exits";
        } else {
           
                $qry1 = "UPDATE users SET u_user_name='$name' , u_address='$address' , u_country='$country' , u_postcode='$zip' , u_phone='$phone' , u_city='$city' , u_modified='$modified' WHERE u_id='" . $id . "'";            
            $res_select = mysql_query($qry1);
            if ($res_select) {
                $msg['status'] = "1";
                $msg['userid'] = $id;
                $msg['message'] = "User Updated successfully";
            } else {
                $msg['status'] = "0";
                $msg['message'] = "Can't Updated";
            }
        }
    } else {
        $msg['status'] = "0";
        $msg['message'] = "User Not Found";
    }
}else{
    $msg['message'] = "wrong or missing parameters!";
}

$ar['status'] = $msg;
print json_encode($ar);

?>