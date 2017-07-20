<?php

//ini_set('display_errors',0);
include 'db.php';

/*
  http://localhost/Ebay_clone/web-api/login.php?username=admin&password=111111
 */
//Fetch the parameters passed along with the url 
if (isset($_REQUEST['username']) && ($_REQUEST['password'])) {

    $username = $_REQUEST['username'];
    $password = md5($_REQUEST['password']);

//Match the parameters with the database for login purpose

    $sql = "SELECT u_id, u_user_name, u_email,u_type  FROM `users`  WHERE `u_user_name`= '$username' AND `u_password`='$password' ";
    $result = mysql_query($sql);
    $cnt = mysql_num_rows($result);
    $row = mysql_fetch_row($result);
    if ($cnt>0) {
            $msg['status'] = '1';
            $msg['message'] = "Successfully Login";
            $msg['id'] = $row[0];
            $msg['u_user_name'] = $row[1];
            $msg['u_email'] = $row[2];
        
    } else {

        $msg['status'] = '0';
        $msg['message'] = "Please check username or password";
        
    }
} else {

    $msg['success'] = '0';
    $msg['message'] = "Parameters Missing";
    //$msg['id'] = $id; 
}
$error['status'] = $msg;
$json_encoded_string = json_encode($error);
$json_encoded_string = str_replace("\\/", '/', $json_encoded_string);
print $json_encoded_string;
?>