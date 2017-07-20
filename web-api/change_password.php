<?php

include 'db.php';
/* http://localhost/Ebay_clone/web-api/change_password.php?id=1&old_password=111111&new_password=111111 */
error_reporting(0);
if (isset($_REQUEST['old_password']) && isset($_REQUEST['old_password']) && isset($_REQUEST['id'])) {
    $old_password = $_REQUEST['old_password'];
    $old_password1=md5($old_password);
    $new_password = $_REQUEST['new_password'];
    $new_password1=md5($new_password);
    $id = $_REQUEST['id'];
    $compare = "select u_id,u_password from users where u_id= '$id' AND u_password='$old_password1'"; 
    $row1 = mysql_query($compare);
    if (mysql_num_rows($row1) > 0) {
        $qry1 = "UPDATE `users` SET `u_password`= '$new_password1' WHERE `u_id` = '" . $id . "'"; 
        if ($qry1) {
            $res_select = mysql_query($qry1);
            $id=  mysql_insert_id();
             $msg['status'] = "1";
             $msg['userid'] = $id;
             $msg['message'] = "Password change successfully";
            } else {
                
                 $msg['status'] = "0";
                $msg['message'] = "Can't Updated";
            }
        } else {
            $msg['userid'] = "0";
            $msg['message'] = "Old password does not match";
        }
} else {
    $msg['message'] = "wrong or missing parameters!";
}
$ar['status'] = $msg;
print json_encode($ar);
?>