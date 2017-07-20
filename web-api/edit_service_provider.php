<?php

include 'db.php';
error_reporting(0);
/* http://localhost/Ebay_clone/web-api/edit_service_provider.php?id=20&sp_name=rajesh&s_name=hardware&email=rajesh1@gmail.com&city=ahm&address=ahmedabad&country=+91&phone=9033311201 */

if ( isset($_REQUEST['id']) && isset($_REQUEST['sp_name']) && isset($_REQUEST['email']) && isset($_REQUEST['city']) && isset($_REQUEST['address']) && isset($_REQUEST['country']) && isset($_REQUEST['phone']) && isset($_REQUEST['s_name']) ) {
    
    $id = $_REQUEST['id'];
    $modified = date('Y-m-d H:i:s');
    $sp_name = $_REQUEST['sp_name']; 
    $email = $_REQUEST['email'];
    $address = $_REQUEST['address'];
    $country = $_REQUEST['country'];
    $s_name = $_REQUEST['s_name'];
    $phone = $_REQUEST['phone'];
    $city = $_REQUEST['city'];
    //dfdfd
    $select = "SELECT * FROM service_provider WHERE sp_id = '$id'";
    $query = mysql_query($select);
    $row = mysql_fetch_assoc($query);
    if (!empty($row)) {
        $selectEmail = "SELECT * FROM service_provider WHERE sp_email = '$email'";
        $queryEmail = mysql_query($selectEmail);
        $rowEmail = mysql_fetch_assoc($queryEmail);
        if (!empty($rowEmail)) {
            $msg['status'] = "0";
            $msg['message'] = "Email Already Exits";
        } else {
           
                $qry1 = "UPDATE service_provider SET sp_name='$sp_name' , sp_address='$address' , sp_country='$country' , sp_phone='$phone' , sp_city='$city' , sp_modified='$modified' , sp_type='$s_name' WHERE sp_id='" . $id . "'";            
            $res_select = mysql_query($qry1);
            if ($res_select) {
                $msg['status'] = "1";
                $msg['userid'] = $id;
                $msg['message'] = "Service Provider Updated successfully";
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