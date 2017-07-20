<?php

include 'db.php';
/* http://localhost/Ebay_clone/web-api/delete_service_provider.php?id=3 */
error_reporting(0);
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
   
       $qry1 = "DELETE from service_provider WHERE sp_id='$id'"; 
       $res_select = mysql_query($qry1); 
         if ($res_select) {
             $msg['status'] = "1";
             $msg['userid'] = $id;
             $msg['message'] = "Service Provider Delete successfully";
            } else {
                $msg['status'] = "0";
                $msg['message'] = "mysql error";
            }
        
} else {
    $msg['message'] = "wrong or missing parameters!";
}
$ar['status'] = $msg;
print json_encode($ar);
?>